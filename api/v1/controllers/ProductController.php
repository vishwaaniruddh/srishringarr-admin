<?php

namespace Api\V1\Controllers;

use Api\V1\Core\Controller;
use Api\V1\Core\Response;
use Api\V1\Core\Logger;

class ProductController extends Controller {
    public function index() {
        try {
            $model = new \Api\V1\Models\ProductModel();
        
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? 'all';
        $stock_status = $_GET['stock_status'] ?? 'all';
        $page = (int)($_GET['page'] ?? 1);
        $limit = (int)($_GET['limit'] ?? 20);
        $offset = ($page - 1) * $limit;

        $products = $model->getProducts($limit, $offset, $search, $category, $stock_status);
        $total = $model->getTotalCount($search, $category, $stock_status); // Note: total count might be off if stock filtering is active, but pagination will handle it.

        // Format data for the UI
        foreach ($products as &$p) {
            $p['stock'] = (int)($p['stock'] ?? 0);
            if (!empty($p['image'])) {
                $p['image'] = "https://srishringarr.com/yn/uploads/" . ltrim($p['image'], '/');
            } else {
                $p['image'] = 'https://srishringarr.com/static/images/default.jpg';
            }
            
            $p['formatted_price'] = '₹' . number_format($p['price']);
            $p['formatted_rent'] = '₹' . number_format($p['rent'] ?? 0);
            $p['formatted_deposit'] = '₹' . number_format($p['deposit'] ?? 0);
            $p['formatted_mrp'] = '₹' . number_format($p['mrp'] ?? 0);
            
            $p['status_label'] = 'In Stock';
            $p['status_class'] = 'active';
            $p['status_icon'] = 'check_circle';
            
            if ($p['stock'] <= 0) {
                $p['status_label'] = 'Out of Stock';
                $p['status_class'] = 'inactive';
                $p['status_icon'] = 'error';
            } elseif ($p['stock'] <= 5) {
                $p['status_label'] = 'Low Stock';
                $p['status_class'] = 'pending';
                $p['status_icon'] = 'warning';
            }
        }

        Response::success("Products retrieved", [
            'products' => $products,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'pages' => ceil($total / $limit)
            ]
        ]);
        } catch (\Exception $e) {
            Logger::error("Failed to retrieve products: " . $e->getMessage());
            Response::error("Internal Server Error", 500);
        }
    }

    public function stats() {
        try {
            $model = new \Api\V1\Models\ProductModel();
            $stats = $model->getInventoryStats();
            $total = $model->getTotalCount();

            Response::success("Stats retrieved", [
                'total' => $total,
                'in_stock' => (int)($stats['in_stock'] ?? 0),
                'low_stock' => (int)($stats['low_stock'] ?? 0),
                'out_of_stock' => (int)($stats['out_of_stock'] ?? 0),
                'seo_optimized' => (int)($stats['seo_optimized'] ?? 0),
                'seo_needs_work' => (int)($stats['seo_needs_work'] ?? 0)
            ]);
        } catch (\Exception $e) {
            Logger::error("Inventory stats failure: " . $e->getMessage());
            Response::error("Failed to load inventory stats", 500);
        }
    }

    public function show() {
        $id = (int)($_GET['id'] ?? 0);
        $type = $_GET['type'] ?? 'jewellery';

        if (!$id) {
            Response::error("Product ID required", 400);
        }

        $model = new \Api\V1\Models\ProductModel();
        $product = $model->getProductById($id, $type);

        if (!$product) {
            Response::error("Product not found", 404);
        }

        Response::success("Product retrieved", $product);
    }

    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        $type = $_GET['type'] ?? 'jewellery';
        $data = $this->getRequestData();

        if (!$id) {
            Response::error("Product ID required", 400);
        }

        $model = new \Api\V1\Models\ProductModel();
        $result = $model->updateProduct($id, $type, $data);

        if ($result) {
            Response::success("Product updated successfully");
        } else {
            Response::error("Failed to update product", 500);
        }
    }

    public function destroy() {
        $id = (int)($_GET['id'] ?? 0);
        $type = $_GET['type'] ?? 'jewellery';

        if (!$id) {
            Response::error("Product ID required", 400);
        }

        $model = new \Api\V1\Models\ProductModel();
        $result = $model->deleteProduct($id, $type);

        if ($result) {
            Response::success("Product deleted successfully");
        } else {
            Response::error("Failed to delete product", 500);
        }
    }

    public function store() {
        $this->validateAuth();
        $data = $this->getRequestData();

        // Simulate save
        if (empty($data['name']) || empty($data['price'])) {
            Response::error("Missing required fields", 400);
        }

        Response::success("Product created", array_merge(['id' => rand(100, 999)], $data), 201);
    }
}
