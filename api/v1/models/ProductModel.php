<?php

namespace Api\V1\Models;

use PDO;

class ProductModel extends BaseModel {
    
    public function getProducts($limit = 20, $offset = 0, $search = '', $category = 'all', $stock_status = 'all', $featured = null) {
        $where = "1=1";
        $g_where = "1=1";
        $params = [];
        $g_params = [];

        if (!empty($search)) {
            $where .= " AND (p.product_name LIKE ? OR p.product_code LIKE ?)";
            $g_where .= " AND (g.gproduct_name LIKE ? OR g.gproduct_code LIKE ?)";
            $params = ["%$search%", "%$search%"];
            $g_params = ["%$search%", "%$search%"];
        }

        // Category filter
        if ($category !== 'all' && !empty($category)) {
            $parts = explode(':', $category);
            $prefix = $parts[0] ?? '';
            $catId = (int)($parts[1] ?? 0);

            if ($prefix === 'garment') {
                $where = "0=1";
                $g_where .= " AND g.garment_id = ?";
                $g_params[] = $catId;
            } elseif ($prefix === 'jewel_parent') {
                $g_where = "0=1";
                $where .= " AND p.categories_id = ?";
                $params[] = $catId;
            } elseif ($prefix === 'jewel_child') {
                $g_where = "0=1";
                $where .= " AND p.subcatagoty = ?";
                $params[] = $catId;
            }
        }

        // Featured filter
        if ($featured !== null && $featured !== '') {
            $where .= " AND p.featured = ?";
            $g_where .= " AND g.featured = ?";
            $params[] = $featured;
            $g_params[] = $featured;
        }

        $query = "
            SELECT 
                t.id, t.name, t.code, t.type, t.price, t.rent, t.deposit, t.image as legacy_image, t.featured,
                t.category_name as category
            FROM (
                (SELECT 
                    p.product_id as id, p.product_name as name, p.product_code as code,
                    'jewellery' as type, CAST(p.sales_price AS DECIMAL(10,2)) as price,
                    CAST(p.rent_price AS DECIMAL(10,2)) as rent,
                    CAST(p.deposit AS DECIMAL(10,2)) as deposit,
                    p.product_image as image, p.featured,
                    COALESCE(cat.name, 'Uncategorized') as category_name
                FROM product p
                LEFT JOIN jewel_subcat cat ON cat.categories_id = p.categories_id
                WHERE $where
                ORDER BY p.product_id DESC LIMIT 1000)
                UNION ALL
                (SELECT 
                    g.gproduct_id as id, g.gproduct_name as name, g.gproduct_code as code,
                    'garments' as type, CAST(g.sales_price AS DECIMAL(10,2)) as price,
                    CAST(g.rent_price AS DECIMAL(10,2)) as rent,
                    CAST(g.deposit AS DECIMAL(10,2)) as deposit,
                    g.gproduct_image as image, g.featured,
                    cat.name as category_name
                FROM garment_product g
                LEFT JOIN garments cat ON cat.garment_id = g.garment_id
                WHERE $g_where
                ORDER BY g.gproduct_id DESC LIMIT 1000)
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
            
            $posQuery = "SELECT name as code, quantity as stock, unit_price as mrp FROM phppos_items WHERE name IN ($placeholders)";
            
            $stmt = $posDb->prepare($posQuery);
            $stmt->execute($codes);
            $posData = [];
            while ($row = $stmt->fetch()) $posData[$row['code']] = $row;

            foreach ($products as $key => &$p) {
                $p['stock'] = (float)($posData[$p['code']]['stock'] ?? 0);
                $p['mrp'] = (float)($posData[$p['code']]['mrp'] ?? 0);

                // Apply Stock Status Filter after fetching stock
                if ($stock_status !== 'all') {
                    $keep = false;
                    if ($stock_status === 'in_stock' && $p['stock'] > 5) $keep = true;
                    elseif ($stock_status === 'low_stock' && $p['stock'] > 0 && $p['stock'] <= 5) $keep = true;
                    elseif ($stock_status === 'out_of_stock' && $p['stock'] <= 0) $keep = true;

                    if (!$keep) {
                        unset($products[$key]);
                        continue;
                    }
                }
            }
            $products = array_values($products);
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

    public function getTotalCount($search = '', $category = 'all', $stock_status = 'all') {
        $where = "1=1";
        $g_where = "1=1";
        $params = [];
        $g_params = [];

        if (!empty($search)) {
            $where .= " AND (p.product_name LIKE ? OR p.product_code LIKE ?)";
            $g_where .= " AND (g.gproduct_name LIKE ? OR g.gproduct_code LIKE ?)";
            $params = ["%$search%", "%$search%"];
            $g_params = ["%$search%", "%$search%"];
        }

        // Category filter
        if ($category !== 'all' && !empty($category)) {
            $parts = explode(':', $category);
            $prefix = $parts[0] ?? '';
            $catId = (int)($parts[1] ?? 0);

            if ($prefix === 'garment') {
                $where = "0=1";
                $g_where .= " AND g.garment_id = ?";
                $g_params[] = $catId;
            } elseif ($prefix === 'jewel_parent') {
                $g_where = "0=1";
                $where .= " AND p.categories_id = ?";
                $params[] = $catId;
            } elseif ($prefix === 'jewel_child') {
                $g_where = "0=1";
                $where .= " AND p.subcatagoty = ?";
                $params[] = $catId;
            }
        }

        // NOTE: Stock status filter in SQL is hard because it's in another DB.
        // For now, we'll return the count based on categories and search.
        // If stock_status is applied, the count might be slightly off if we don't join POS DB.
        // To be perfect, we would need to join phppos_items, which is in another database.
        
        $query = "
            SELECT (
                (SELECT COUNT(*) FROM product p WHERE $where) +
                (SELECT COUNT(*) FROM garment_product g WHERE $g_where)
            ) as total
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute(array_merge($params, $g_params));
        return (int)$stmt->fetchColumn();
    }

    public function getCategoriesWithCounts() {
        $data = [
            'apparel' => [],
            'jewellery' => []
        ];

        // 1. Apparel (garments table where Main_id is 1 or 3)
        $stmt = $this->db->query("SELECT garment_id as id, name FROM garments WHERE Main_id IN (1, 3) ORDER BY name ASC");
        $garments = $stmt->fetchAll();
        foreach ($garments as $cat) {
            $countStmt = $this->db->prepare("SELECT COUNT(*) FROM garment_product WHERE garment_id = ?");
            $countStmt->execute([$cat['id']]);
            $count = (int)$countStmt->fetchColumn();
            
            $data['apparel'][] = [
                'id' => $cat['id'],
                'name' => $cat['name'],
                'count' => $count
            ];
        }

        // 2. Jewellery (jewel_subcat table where mcat_id is 1 or 3)
        $stmt = $this->db->query("SELECT categories_id as id, name FROM jewel_subcat WHERE mcat_id IN (1, 3) ORDER BY name ASC");
        $jewelParents = $stmt->fetchAll();
        foreach ($jewelParents as $parent) {
            // Count for parent
            $countStmt = $this->db->prepare("SELECT COUNT(*) FROM product WHERE categories_id = ?");
            $countStmt->execute([$parent['id']]);
            $parentCount = (int)$countStmt->fetchColumn();

            // Fetch children (subcat1 table where maincat_id matches parent name? No, usually ID)
            // Legacy code says: SELECT * FROM `subcat1` where `maincat_id`='$rowjew[0]'
            $childStmt = $this->db->prepare("SELECT subcat_id as id, name FROM subcat1 WHERE maincat_id = ? ORDER BY name ASC");
            $childStmt->execute([$parent['id']]);
            $children = $childStmt->fetchAll();
            $childData = [];
            
            foreach ($children as $child) {
                $countStmt = $this->db->prepare("SELECT COUNT(*) FROM product WHERE subcatagoty = ?");
                $countStmt->execute([$child['id']]);
                $childCount = (int)$countStmt->fetchColumn();
                
                $childData[] = [
                    'id' => $child['id'],
                    'name' => $child['name'],
                    'count' => $childCount
                ];
            }

            $data['jewellery'][] = [
                'id' => $parent['id'],
                'name' => $parent['name'],
                'count' => $parentCount,
                'children' => $childData
            ];
        }

        return $data;
    }

    public function getInventoryStats() {
        // This requires joining with POS DB for stock levels
        $posDb = $this->getPosDb();
        
        // We'll get all product codes first
        $allCodes = [];
        $stmt = $this->db->query("SELECT product_code as code FROM product UNION SELECT gproduct_code as code FROM garment_product");
        $allCodes = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($allCodes)) return ['total' => 0, 'in_stock' => 0, 'low_stock' => 0, 'out_of_stock' => 0];

        $placeholders = rtrim(str_repeat('?,', count($allCodes)), ',');
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN quantity > 5 THEN 1 ELSE 0 END) as in_stock,
                    SUM(CASE WHEN quantity > 0 AND quantity <= 5 THEN 1 ELSE 0 END) as low_stock,
                    SUM(CASE WHEN quantity <= 0 THEN 1 ELSE 0 END) as out_of_stock
                  FROM phppos_items WHERE name IN ($placeholders)";
        
        $stmt = $posDb->prepare($query);
        $stmt->execute($allCodes);
        $stats = $stmt->fetch();
        
        return [
            'total' => (int)$stats['total'],
            'in_stock' => (int)$stats['in_stock'],
            'low_stock' => (int)$stats['low_stock'],
            'out_of_stock' => (int)$stats['out_of_stock']
        ];
    }

    public function getProductById($id, $type) {
        $idField = ($type === 'jewellery') ? 'product_id' : 'gproduct_id';
        
        if ($type === 'jewellery') {
            $query = "SELECT p.*, cat.name as category_name, sub.name as sub_category_name 
                      FROM product p 
                      LEFT JOIN jewel_subcat cat ON cat.categories_id = p.categories_id
                      LEFT JOIN subcat1 sub ON sub.subcat_id = p.subcatagoty
                      WHERE p.product_id = ?";
        } else {
            $query = "SELECT g.*, cat.name as category_name 
                      FROM garment_product g 
                      LEFT JOIN garments cat ON cat.garment_id = g.garment_id
                      WHERE g.gproduct_id = ?";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        
        if ($product) {
            // Format for unified response
            return [
                'id' => $product[$idField],
                'name' => $product[$type === 'jewellery' ? 'product_name' : 'gproduct_name'],
                'code' => $product[$type === 'jewellery' ? 'product_code' : 'gproduct_code'],
                'type' => $type,
                'price' => $product['sales_price'],
                'rent' => $product['rent_price'],
                'deposit' => $product['deposit'],
                'description' => $product[$type === 'jewellery' ? 'product_desc' : 'gproduct_desc'],
                'category' => $product['category_name'],
                'sub_category' => $product['sub_category_name'] ?? null,
                'featured' => $product['featured'],
                'raw' => $product
            ];
        }
        return null;
    }

    public function toggleFeatured($id, $type, $status) {
        $table = ($type === 'jewellery') ? 'product' : 'garment_product';
        $idField = ($type === 'jewellery') ? 'product_id' : 'gproduct_id';
        
        $stmt = $this->db->prepare("UPDATE $table SET featured = ? WHERE $idField = ?");
        return $stmt->execute([$status, $id]);
    }
}
