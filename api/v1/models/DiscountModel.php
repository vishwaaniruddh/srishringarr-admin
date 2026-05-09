<?php

namespace Api\V1\Models;

class DiscountModel extends BaseModel {
    public function __construct() {
        parent::__construct();
    }

    public function getAll($params = []) {
        $search = $params['search'] ?? '';
        $scope = $params['scope'] ?? '';
        
        $where = ["1=1"];
        $queryParams = [];

        if (!empty($search)) {
            $where[] = "(target LIKE ? OR scope LIKE ?)";
            $queryParams[] = "%$search%";
            $queryParams[] = "%$search%";
        }

        if (!empty($scope)) {
            $where[] = "scope = ?";
            $queryParams[] = $scope;
        }

        $whereClause = " WHERE " . implode(' AND ', $where);
        $sql = "SELECT * FROM discount_rules $whereClause ORDER BY weight DESC, id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($queryParams);
        $rules = $stmt->fetchAll();

        foreach ($rules as &$rule) {
            $rule['target_display'] = $this->resolveTargetNames($rule['target']);
        }

        return $rules;
    }

    public function getById($id) {
        $sql = "SELECT * FROM discount_rules WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $rule = $stmt->fetch();
        if ($rule) {
            $rule['target_objects'] = $this->resolveTargetObjects($rule['target']);
        }
        return $rule;
    }

    public function create($data) {
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = "INSERT INTO discount_rules (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;

        $sql = "UPDATE discount_rules SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete($id) {
        $sql = "DELETE FROM discount_rules WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function searchTargets($type, $term) {
        $term = "%$term%";
        $results = [];

        if ($type === 'product') {
            $sql = "(SELECT product_id as id, product_name as name, product_code as code, 'jewellery' as type 
                     FROM product 
                     WHERE product_name LIKE ? OR product_code LIKE ? LIMIT 10)
                    UNION
                    (SELECT gproduct_id as id, gproduct_name as name, gproduct_code as code, 'garments' as type 
                     FROM garment_product 
                     WHERE gproduct_name LIKE ? OR gproduct_code LIKE ? LIMIT 10)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$term, $term, $term, $term]);
            while ($row = $stmt->fetch()) {
                $results[] = [
                    'id' => $row['id'] . ':' . $row['type'],
                    'text' => $row['name'] . ' (' . $row['code'] . ')'
                ];
            }
        } elseif ($type === 'category') {
            // Apparel Categories
            $sql1 = "SELECT garment_id as id, name FROM garments WHERE name LIKE ? LIMIT 10";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute([$term]);
            while ($row = $stmt1->fetch()) {
                $results[] = [
                    'id' => $row['id'] . ':garment',
                    'text' => $row['name'] . ' (Apparel)'
                ];
            }
            // Jewellery Categories
            $sql2 = "SELECT subcat_id as id, categories_name as name FROM jewel_subcat WHERE categories_name LIKE ? LIMIT 10";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([$term]);
            while ($row = $stmt2->fetch()) {
                $results[] = [
                    'id' => $row['id'] . ':jewel_parent',
                    'text' => $row['name'] . ' (Jewel Category)'
                ];
            }
        }

        return $results;
    }

    private function resolveTargetNames($targetStr) {
        if (empty($targetStr) || $targetStr === 'all') return 'Global (All Products)';
        
        $targets = explode(',', $targetStr);
        $names = [];
        
        foreach ($targets as $t) {
            if (strpos($t, ':') === false) {
                $names[] = $t;
                continue;
            }
            
            $parts = explode(':', $t);
            $id = (int)$parts[0];
            $type = $parts[1] ?? '';
            
            if ($type === 'jewellery') {
                $q = "SELECT product_name, product_code FROM product WHERE product_id = ?";
                $stmt = $this->db->prepare($q);
                $stmt->execute([$id]);
                if ($row = $stmt->fetch()) $names[] = $row['product_name'] . ' (' . $row['product_code'] . ')';
            } elseif ($type === 'garments') {
                $q = "SELECT gproduct_name, gproduct_code FROM garment_product WHERE gproduct_id = ?";
                $stmt = $this->db->prepare($q);
                $stmt->execute([$id]);
                if ($row = $stmt->fetch()) $names[] = $row['gproduct_name'] . ' (' . $row['gproduct_code'] . ')';
            } elseif ($type === 'garment') {
                $q = "SELECT name FROM garments WHERE garment_id = ?";
                $stmt = $this->db->prepare($q);
                $stmt->execute([$id]);
                if ($row = $stmt->fetch()) $names[] = $row['name'];
            } elseif ($type === 'jewel_parent') {
                $q = "SELECT categories_name FROM jewel_subcat WHERE subcat_id = ?";
                $stmt = $this->db->prepare($q);
                $stmt->execute([$id]);
                if ($row = $stmt->fetch()) $names[] = $row['categories_name'];
            } else {
                $names[] = $t;
            }
        }
        
        return implode(', ', $names);
    }

    private function resolveTargetObjects($targetStr) {
        if (empty($targetStr) || $targetStr === 'all') return [];
        
        $targets = explode(',', $targetStr);
        $objects = [];
        
        foreach ($targets as $t) {
            if (strpos($t, ':') === false) continue;
            
            $parts = explode(':', $t);
            $id = (int)$parts[0];
            $type = $parts[1] ?? '';
            
            $name = '';
            if ($type === 'jewellery') {
                $q = "SELECT product_name, product_code FROM product WHERE product_id = ?";
                $stmt = $this->db->prepare($q);
                $stmt->execute([$id]);
                if ($row = $stmt->fetch()) $name = $row['product_name'] . ' (' . $row['product_code'] . ')';
            } elseif ($type === 'garments') {
                $q = "SELECT gproduct_name, gproduct_code FROM garment_product WHERE gproduct_id = ?";
                $stmt = $this->db->prepare($q);
                $stmt->execute([$id]);
                if ($row = $stmt->fetch()) $name = $row['gproduct_name'] . ' (' . $row['gproduct_code'] . ')';
            } elseif ($type === 'garment') {
                $q = "SELECT name FROM garments WHERE garment_id = ?";
                $stmt = $this->db->prepare($q);
                $stmt->execute([$id]);
                if ($row = $stmt->fetch()) $name = $row['name'] . ' (Apparel)';
            } elseif ($type === 'jewel_parent') {
                $q = "SELECT categories_name FROM jewel_subcat WHERE subcat_id = ?";
                $stmt = $this->db->prepare($q);
                $stmt->execute([$id]);
                if ($row = $stmt->fetch()) $name = $row['categories_name'] . ' (Jewel)';
            }
            
            if ($name) {
                $objects[] = ['id' => $t, 'text' => $name];
            }
        }
        
        return $objects;
    }
}
