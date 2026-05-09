<?php

namespace Api\V1\Models;

class CouponModel extends BaseModel {
    public function __construct() {
        parent::__construct();
    }

    public function getAll($params = []) {
        $search = $params['search'] ?? '';
        $type = $params['type'] ?? '';
        $status = $params['status'] ?? '';
        
        $where = ["1=1"];
        $queryParams = [];

        if (!empty($search)) {
            $where[] = "(code LIKE ? OR description LIKE ?)";
            $queryParams[] = "%$search%";
            $queryParams[] = "%$search%";
        }

        if (!empty($type)) {
            $where[] = "discount_type = ?";
            $queryParams[] = $type;
        }

        if (!empty($status)) {
            $where[] = "status = ?";
            $queryParams[] = $status;
        }

        $whereClause = " WHERE " . implode(' AND ', $where);
        $sql = "SELECT * FROM coupons $whereClause ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($queryParams);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM coupons WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByCode($code) {
        $sql = "SELECT * FROM coupons WHERE code = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    public function create($data) {
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = "INSERT INTO coupons (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
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

        $sql = "UPDATE coupons SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete($id) {
        $sql = "DELETE FROM coupons WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
