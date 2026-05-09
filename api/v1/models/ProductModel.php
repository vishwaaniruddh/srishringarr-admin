<?php

namespace Api\V1\Models;

class ProductModel extends BaseModel {
    
    public function getProducts($limit = 20, $offset = 0, $search = '') {
        $where = "1=1";
        $g_where = "1=1";
        $params = [];
        $g_params = [];

        if (!empty($search)) {
            $where = "(p.product_name LIKE ? OR p.product_code LIKE ?)";
            $g_where = "(g.gproduct_name LIKE ? OR g.gproduct_code LIKE ?)";
            $params = ["%$search%", "%$search%"];
            $g_params = ["%$search%", "%$search%"];
        }

        $query = "
            SELECT 
                t.id, t.name, t.code, t.type, t.price, t.rent, t.deposit, t.image as legacy_image, t.featured,
                t.category
            FROM (
                (SELECT 
                    p.product_id as id, p.product_name as name, p.product_code as code,
                    'jewellery' as type, CAST(p.sales_price AS DECIMAL(10,2)) as price,
                    CAST(p.rent_price AS DECIMAL(10,2)) as rent,
                    CAST(p.deposit AS DECIMAL(10,2)) as deposit,
                    p.product_image as image, p.featured,
                    p.maincatagory as category
                FROM product p
                WHERE $where
                ORDER BY p.product_id DESC LIMIT 500)
                UNION ALL
                (SELECT 
                    g.gproduct_id as id, g.gproduct_name as name, g.gproduct_code as code,
                    'garments' as type, CAST(g.sales_price AS DECIMAL(10,2)) as price,
                    CAST(g.rent_price AS DECIMAL(10,2)) as rent,
                    CAST(g.deposit AS DECIMAL(10,2)) as deposit,
                    g.gproduct_image as image, g.featured,
                    cat.name as category
                FROM garment_product g
                LEFT JOIN garments cat ON cat.garment_id = g.garment_id
                WHERE $g_where
                ORDER BY g.gproduct_id DESC LIMIT 500)
            ) t
            ORDER BY t.id DESC 
            LIMIT $limit OFFSET $offset";

        $stmt = $this->db->prepare($query);
        $stmt->execute(array_merge($params, $g_params));
        $products = $stmt->fetchAll();

        // Fetch POS data (stock, mrp) separately
        if (!empty($products)) {
            $codes = array_map(function($p) { return $p['code']; }, $products);
            $posDb = $this->getPosDb();
            $placeholders = rtrim(str_repeat('?,', count($codes)), ',');
            $stmt = $posDb->prepare("SELECT name as code, quantity as stock, unit_price as mrp FROM phppos_items WHERE name IN ($placeholders)");
            $stmt->execute($codes);
            $posData = [];
            while ($row = $stmt->fetch()) $posData[$row['code']] = $row;

            foreach ($products as &$p) {
                $p['stock'] = $posData[$p['code']]['stock'] ?? 0;
                $p['mrp'] = $posData[$p['code']]['mrp'] ?? 0;
            }
        }

        // Fetch real images from product_images_new
        if (!empty($products)) {
            $jewelIds = [];
            $garmentIds = [];
            foreach ($products as $p) {
                if ($p['type'] === 'jewellery') $jewelIds[] = $p['id'];
                else $garmentIds[] = $p['id'];
            }

            $images = [];
            if (!empty($jewelIds)) {
                $placeholders = rtrim(str_repeat('?,', count($jewelIds)), ',');
                $stmt = $this->db->prepare("SELECT product_id as id, img_name FROM product_images_new WHERE product_id IN ($placeholders) AND rank = 0");
                $stmt->execute($jewelIds);
                while ($row = $stmt->fetch()) $images['jewellery_' . $row['id']] = $row['img_name'];
            }
            if (!empty($garmentIds)) {
                $placeholders = rtrim(str_repeat('?,', count($garmentIds)), ',');
                $stmt = $this->db->prepare("SELECT gproduct_id as id, img_name FROM product_images_new WHERE gproduct_id IN ($placeholders) AND rank = 0");
                $stmt->execute($garmentIds);
                while ($row = $stmt->fetch()) $images['garments_' . $row['id']] = $row['img_name'];
            }

            foreach ($products as &$p) {
                $p['image'] = $images[$p['type'] . '_' . $p['id']] ?? $p['legacy_image'];
            }
        }

        return $products;
    }

    public function getTotalCount($search = '') {
        $params = [];
        $where = "1=1";
        $g_where = "1=1";

        if (!empty($search)) {
            $where = "(product_name LIKE ? OR product_code LIKE ?)";
            $g_where = "(gproduct_name LIKE ? OR gproduct_code LIKE ?)";
            $params = ["%$search%", "%$search%"];
            $g_params = ["%$search%", "%$search%"];
        } else {
            $g_params = [];
        }

        $stmt1 = $this->db->prepare("SELECT COUNT(*) FROM product WHERE $where");
        $stmt1->execute($params);
        $count1 = $stmt1->fetchColumn();

        $stmt2 = $this->db->prepare("SELECT COUNT(*) FROM garment_product WHERE $g_where");
        $stmt2->execute($g_params);
        $count2 = $stmt2->fetchColumn();

        return (int)$count1 + (int)$count2;
    }

    public function getInventoryStats() {
        // 1. Get all product codes from both tables
        $codes = $this->db->query("
            SELECT product_code as code FROM product
            UNION ALL
            SELECT gproduct_code as code FROM garment_product
        ")->fetchAll(\PDO::FETCH_COLUMN);

        if (empty($codes)) {
            return ['in_stock' => 0, 'low_stock' => 0, 'out_of_stock' => 0];
        }

        // 2. Query POS DB for these codes
        $posDb = $this->getPosDb();
        $placeholders = rtrim(str_repeat('?,', count($codes)), ',');
        $query = "
            SELECT 
                SUM(CASE WHEN quantity > 5 THEN 1 ELSE 0 END) as in_stock,
                SUM(CASE WHEN quantity > 0 AND quantity <= 5 THEN 1 ELSE 0 END) as low_stock,
                SUM(CASE WHEN quantity <= 0 OR quantity IS NULL THEN 1 ELSE 0 END) as out_of_stock
            FROM phppos_items 
            WHERE name IN ($placeholders)";
        
        $stmt = $posDb->prepare($query);
        $stmt->execute($codes);
        return $stmt->fetch();
    }

    public function getProductById($id, $type) {
        $idField = ($type === 'jewellery') ? 'product_id' : 'gproduct_id';
        
        $query = "";
        if ($type === 'jewellery') {
            $query = "SELECT 
                        p.product_id as id, p.product_name as name, p.product_code as code,
                        'jewellery' as type, CAST(p.sales_price AS DECIMAL(10,2)) as price,
                        CAST(p.rent_price AS DECIMAL(10,2)) as rent,
                        CAST(p.deposit AS DECIMAL(10,2)) as deposit,
                        p.product_desc as description, p.featured,
                        cat.categories_name as category
                      FROM product p
                      LEFT JOIN jewel_subcat cat ON cat.subcat_id = p.subcat_id
                      WHERE p.product_id = ?";
        } else {
            $query = "SELECT 
                        p.gproduct_id as id, p.gproduct_name as name, p.gproduct_code as code,
                        'garments' as type, CAST(p.sales_price AS DECIMAL(10,2)) as price,
                        CAST(p.rent_price AS DECIMAL(10,2)) as rent,
                        CAST(p.deposit AS DECIMAL(10,2)) as deposit,
                        p.gproduct_desc as description, p.featured,
                        cat.name as category
                      FROM garment_product p
                      LEFT JOIN garments cat ON cat.garment_id = p.garment_id
                      WHERE p.gproduct_id = ?";
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if ($product) {
            // Fetch POS data (stock, mrp) separately
            $posDb = $this->getPosDb();
            $stmt = $posDb->prepare("SELECT quantity as stock, unit_price as mrp FROM phppos_items WHERE name = ?");
            $stmt->execute([$product['code']]);
            $posData = $stmt->fetch();
            $product['stock'] = $posData['stock'] ?? 0;
            $product['mrp'] = $posData['mrp'] ?? 0;

            // Fetch Images from product_images_new
            $imgQuery = "SELECT img_name FROM product_images_new WHERE $idField = ? ORDER BY rank";
            $stmt = $this->db->prepare($imgQuery);
            $stmt->execute([$id]);
            $images = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            
            $product['image'] = !empty($images) ? $images[0] : '';
            $product['extra_images'] = count($images) > 1 ? array_slice($images, 1) : [];

            // Fetch Recent Bookings
            $bookingQuery = "SELECT bill_id, pickup_date as bill_date, rent as rent_amount, return_date, is_status as status
                             FROM order_detail 
                             WHERE item_id = ? 
                             ORDER BY pickup_date DESC LIMIT 5";
            $stmt = $this->db->prepare($bookingQuery);
            $stmt->execute([$product['code']]);
            $product['recent_bookings'] = $stmt->fetchAll();
        }

        return $product;
    }

    public function updateProduct($id, $type, $data) {
        $table = ($type === 'jewellery') ? 'product' : 'garment_product';
        $idField = ($type === 'jewellery') ? 'product_id' : 'gproduct_id';
        
        $fields = [];
        $params = [];

        if (isset($data['name'])) {
            $fields[] = ($type === 'jewellery' ? 'product_name' : 'gproduct_name') . " = ?";
            $params[] = $data['name'];
        }
        if (isset($data['description'])) {
            $fields[] = ($type === 'jewellery' ? 'product_desc' : 'gproduct_desc') . " = ?";
            $params[] = $data['description'];
        }
        if (isset($data['price'])) {
            $fields[] = "sales_price = ?";
            $params[] = $data['price'];
        }
        if (isset($data['rent'])) {
            $fields[] = "rent_price = ?";
            $params[] = $data['rent'];
        }
        if (isset($data['deposit'])) {
            $fields[] = "deposit = ?";
            $params[] = $data['deposit'];
        }
        if (isset($data['featured'])) {
            $fields[] = "featured = ?";
            $params[] = (int)$data['featured'];
        }

        $result = true;
        if (!empty($fields)) {
            $query = "UPDATE $table SET " . implode(', ', $fields) . " WHERE $idField = ?";
            $params[] = $id;
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute($params);
        }

        // Handle Image Update in product_images_new
        if (isset($data['image'])) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM product_images_new WHERE $idField = ? AND rank = 0");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() > 0) {
                $stmt = $this->db->prepare("UPDATE product_images_new SET img_name = ? WHERE $idField = ? AND rank = 0");
                $stmt->execute([$data['image'], $id]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO product_images_new ($idField, img_name, rank) VALUES (?, ?, 0)");
                $stmt->execute([$id, $data['image']]);
            }
        }

        return $result;
    }

    public function addProductImage($id, $type, $path) {
        $idField = ($type === 'jewellery') ? 'product_id' : 'gproduct_id';
        // Get current max rank
        $stmt = $this->db->prepare("SELECT MAX(rank) FROM product_images_new WHERE $idField = ?");
        $stmt->execute([$id]);
        $maxRank = $stmt->fetchColumn();
        $nextRank = ($maxRank !== null) ? (int)$maxRank + 1 : 0;

        $stmt = $this->db->prepare("INSERT INTO product_images_new ($idField, img_name, rank) VALUES (?, ?, ?)");
        return $stmt->execute([$id, $path, $nextRank]);
    }

    public function deleteProductImage($id, $type, $path) {
        $idField = ($type === 'jewellery') ? 'product_id' : 'gproduct_id';
        $stmt = $this->db->prepare("DELETE FROM product_images_new WHERE $idField = ? AND img_name = ?");
        return $stmt->execute([$id, $path]);
    }

    public function deleteProduct($id, $type) {
        $table = ($type === 'jewellery') ? 'product' : 'garment_product';
        $idField = ($type === 'jewellery') ? 'product_id' : 'gproduct_id';

        try {
            $this->db->beginTransaction();

            // 1. Delete from main table
            $stmt = $this->db->prepare("DELETE FROM $table WHERE $idField = ?");
            $stmt->execute([$id]);

            // 2. Delete from images table
            $stmt = $this->db->prepare("DELETE FROM product_images_new WHERE $idField = ?");
            $stmt->execute([$id]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function setPrimaryImage($id, $type, $path) {
        $idField = ($type === 'jewellery') ? 'product_id' : 'gproduct_id';
        
        // 1. Set current rank 0 to max + 1
        $stmt = $this->db->prepare("SELECT MAX(rank) FROM product_images_new WHERE $idField = ?");
        $stmt->execute([$id]);
        $nextRank = (int)$stmt->fetchColumn() + 1;
        
        $stmt = $this->db->prepare("UPDATE product_images_new SET rank = ? WHERE $idField = ? AND rank = 0");
        $stmt->execute([$nextRank, $id]);
        
        // 2. Set target image to rank 0
        $stmt = $this->db->prepare("UPDATE product_images_new SET rank = 0 WHERE $idField = ? AND img_name = ?");
        return $stmt->execute([$id, $path]);
    }
}
