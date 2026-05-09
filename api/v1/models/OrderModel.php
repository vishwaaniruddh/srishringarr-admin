<?php

namespace Api\V1\Models;

class OrderModel extends BaseModel {
    public function getOrders($limit = 20, $offset = 0, $search = '', $status = '') {
        $params = [];
        $where = " WHERE 1=1";
        
        if ($search) {
            $where .= " AND (first_name LIKE ? OR last_name LIKE ? OR id LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($status && $status !== 'All') {
            $where .= " AND status = ?";
            $params[] = $status;
        }

        $sql = "SELECT *, CONCAT(first_name, ' ', last_name) as cust_name,
                (SELECT COUNT(*) FROM order_items WHERE order_id = orders.id) as item_count
                FROM orders $where 
                ORDER BY id DESC 
                LIMIT $limit OFFSET $offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getTotalCount($search = '', $status = '') {
        $params = [];
        $where = " WHERE 1=1";
        
        if ($search) {
            $where .= " AND (first_name LIKE ? OR last_name LIKE ? OR id LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if ($status && $status !== 'All') {
            $where .= " AND status = ?";
            $params[] = $status;
        }

        $sql = "SELECT COUNT(*) FROM orders $where";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getOrderStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status IN ('Completed', 'active', 'Paid') THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status IN ('Processing', 'pending', 'Pending') THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) as cancelled,
                SUM(total_amount) as revenue
                FROM orders";
        return $this->db->query($sql)->fetch();
    }

    public function getOrderItems($orderId) {
        $sql = "SELECT oi.*, 
                CASE 
                    WHEN oi.product_type = 'jewellery' THEN p.product_name 
                    WHEN oi.product_type = 'garments' THEN gp.gproduct_name 
                    ELSE 'Standard Item'
                END as product_name,
                CASE 
                    WHEN oi.product_type = 'jewellery' THEN p.product_code 
                    WHEN oi.product_type = 'garments' THEN gp.gproduct_code 
                    ELSE CONCAT('#', oi.product_id)
                END as sku,
                (SELECT COALESCE(NULLIF(img_name, ''), prod_image) FROM product_images_new 
                 WHERE (oi.product_type = 'jewellery' AND product_id = oi.product_id)
                 OR (oi.product_type = 'garments' AND gproduct_id = oi.product_id)
                 ORDER BY rank LIMIT 1) as img_name
                FROM order_items oi 
                LEFT JOIN product p ON oi.product_type = 'jewellery' AND p.product_id = oi.product_id
                LEFT JOIN garment_product gp ON oi.product_type = 'garments' AND gp.gproduct_id = oi.product_id
                WHERE oi.order_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }
}
