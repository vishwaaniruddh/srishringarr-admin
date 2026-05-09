<?php

namespace Api\V1\Controllers;

use Api\V1\Core\Controller;
use Api\V1\Core\Response;

class CustomerController extends Controller {
    public function index() {
        $model = new \Api\V1\Models\CustomerModel();
        
        $search = $_GET['search'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 20);
        $offset = ($page - 1) * $limit;

        $customers = $model->getCustomers($limit, $offset, $search);
        $total = $model->getTotalCount($search);

        foreach ($customers as &$c) {
            $c['name'] = trim($c['Firstname'] . ' ' . $c['Lastname']);
            $c['total_spent_formatted'] = '₹' . number_format($c['total_spent'] ?? 0);
            $c['initials'] = strtoupper(substr($c['Firstname'], 0, 1) . substr($c['Lastname'], 0, 1));
            
            // Random tier for UI
            $tiers = ['VIP', 'Premium', 'Standard', 'New'];
            $c['tier'] = $tiers[rand(0, 3)];
            $c['status'] = 'Active';
        }

        Response::success("Customers retrieved", [
            'customers' => $customers,
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
        if (!$id) Response::error("Customer ID required", 400);

        $model = new \Api\V1\Models\CustomerModel();
        $customer = $model->getCustomerById($id);

        if (!$customer) Response::error("Customer not found", 404);

        $customer['name'] = trim($customer['Firstname'] . ' ' . $customer['Lastname']);
        $customer['total_spent_formatted'] = '₹' . number_format($customer['total_spent'] ?? 0);
        
        foreach ($customer['orders'] as &$o) {
            $o['formatted_date'] = date('M d, Y', strtotime($o['date'] ?? 'now'));
            $o['formatted_amount'] = '₹' . number_format($o['total_amount'] ?? 0);
        }

        Response::success("Customer details retrieved", $customer);
    }
}
