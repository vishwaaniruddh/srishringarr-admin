<?php

namespace Api\V1\Controllers;

use Api\V1\Core\Controller;
use Api\V1\Core\Response;

class OrderController extends Controller {
    public function index() {
        $model = new \Api\V1\Models\OrderModel();
        
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? 'All';
        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 20);
        $offset = ($page - 1) * $limit;

        $orders = $model->getOrders($limit, $offset, $search, $status);
        $total = $model->getTotalCount($search, $status);
        $stats = $model->getOrderStats();

        foreach ($orders as &$o) {
            $o['formatted_date'] = date('M d, Y', strtotime($o['created_at']));
            $o['formatted_amount'] = '₹' . number_format($o['total_amount'] ?? 0);
            $o['initials'] = strtoupper(substr($o['first_name'], 0, 1) . substr($o['last_name'], 0, 1));
            
            // Map status classes for UI
            $st = strtolower($o['status']);
            if (in_array($st, ['completed', 'active', 'paid'])) {
                $o['status_class'] = 'active';
                $o['status_label'] = 'Completed';
            } elseif (in_array($st, ['processing', 'pending'])) {
                $o['status_class'] = 'pending';
                $o['status_label'] = 'Processing';
            } else {
                $o['status_class'] = 'overdue';
                $o['status_label'] = ucfirst($o['status']);
            }
        }

        Response::success("Orders retrieved", [
            'orders' => $orders,
            'stats' => [
                'total' => $stats['total'],
                'completed' => $stats['completed'],
                'processing' => $stats['processing'],
                'cancelled' => $stats['cancelled'],
                'revenue' => '₹' . number_format($stats['revenue'] ?? 0, 0, '.', ',')
            ],
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }

    public function show() {
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) Response::error("Order ID required", 400);

        $model = new \Api\V1\Models\OrderModel();
        
        // Fetch order basic info
        $sql = "SELECT * FROM orders WHERE id = ?";
        $stmt = $model->getDb()->prepare($sql);
        $stmt->execute([$id]);
        $order = $stmt->fetch();

        if (!$order) Response::error("Order not found", 404);

        $order['formatted_date'] = date('M d, Y h:i A', strtotime($order['created_at']));
        $order['formatted_amount'] = '₹' . number_format($order['total_amount'] ?? 0, 2);
        
        // Fetch items
        $items = $model->getOrderItems($id);
        foreach ($items as &$item) {
            $item['formatted_price'] = '₹' . number_format($item['price'], 2);
            $item['total'] = '₹' . number_format($item['price'] * ($item['quantity'] ?? 1), 2);
            
            if (!empty($item['img_name'])) {
                $item['image_url'] = "https://srishringarr.com/yn/uploads/" . ltrim($item['img_name'], '/');
            } else {
                $item['image_url'] = 'https://placehold.co/100x100?text=Product';
            }
        }

        Response::success("Order details retrieved", [
            'order' => $order,
            'items' => $items
        ]);
    }
}
