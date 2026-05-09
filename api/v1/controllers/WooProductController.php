<?php

namespace Api\V1\Controllers;

use Api\V1\Models\WooProductModel;

class WooProductController {
    private $wooModel;

    public function __construct() {
        $this->wooModel = new WooProductModel();
    }

    public function index() {
        if (!$this->wooModel->isConnected()) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Unable to connect to remote WooCommerce database'
            ]);
            return;
        }

        $search = $_GET['search'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 20);

        $products = $this->wooModel->getProducts([
            'search' => $search,
            'page' => $page,
            'limit' => $limit
        ]);

        $totalCount = $this->wooModel->getTotalCount($search);

        echo json_encode([
            'status' => 'success',
            'data' => [
                'products' => $products,
                'total' => $totalCount,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($totalCount / $limit)
            ]
        ]);
    }
}
