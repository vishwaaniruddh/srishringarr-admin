<?php

namespace Api\V1\Models;

class CustomerModel extends BaseModel {
    public function getCustomers($limit = 20, $offset = 0, $search = '') {
        $params = [];
        $where = " WHERE 1=1";
        if ($search) {
            $where .= " AND (Firstname LIKE ? OR Lastname LIKE ? OR email LIKE ? OR Mobile LIKE ?)";
            $params = ["%$search%", "%$search%", "%$search%", "%$search%"];
        }

        $sql = "SELECT registration_id as id, Firstname, Lastname, email, Mobile, city, state, 
                (SELECT COUNT(*) FROM orders WHERE user_id = Registration.registration_id) as total_orders,
                (SELECT SUM(total_amount) FROM orders WHERE user_id = Registration.registration_id) as total_spent
                FROM Registration $where 
                ORDER BY registration_id DESC 
                LIMIT $limit OFFSET $offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getTotalCount($search = '') {
        $params = [];
        $where = " WHERE 1=1";
        if ($search) {
            $where .= " AND (Firstname LIKE ? OR Lastname LIKE ? OR email LIKE ? OR Mobile LIKE ?)";
            $params = ["%$search%", "%$search%", "%$search%", "%$search%"];
        }

        $sql = "SELECT COUNT(*) FROM Registration $where";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getCustomerById($id) {
        $sql = "SELECT *, registration_id as id FROM Registration WHERE registration_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $customer = $stmt->fetch();
        
        if ($customer) {
            // Get orders
            $sqlOrders = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC";
            $stmtOrders = $this->db->prepare($sqlOrders);
            $stmtOrders->execute([$id]);
            $customer['orders'] = $stmtOrders->fetchAll();
            
            // Get stats
            $customer['total_orders'] = count($customer['orders']);
            $customer['total_spent'] = array_sum(array_column($customer['orders'], 'total_amount'));
        }
        
        return $customer;
    }
}
