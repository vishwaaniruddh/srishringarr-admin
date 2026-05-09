<?php

namespace Api\V1\Controllers;

use Api\V1\Core\Controller;
use Api\V1\Models\ProductModel;

class LegacyApiController extends Controller {
    /**
     * Replicates the legacy products API endpoint:
     * ?controller=api&action=products&page=1&search=&category=&featured=
     */
    public function products() {
        $model = new ProductModel();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        $featured = $_GET['featured'] ?? '';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        $offset = ($page - 1) * $limit;
        
        // Use our modern model but adapt to legacy parameters
        // Note: we pass $category directly, our model now handles composite strings like 'garment:22'
        $products = $model->getProducts($limit, $offset, $search, $category, 'all', $featured);
        $totalRecords = $model->getTotalCount($search); // In legacy this was simplified
        $categories = $model->getCategoriesWithCounts();
        
        // Direct JSON response without the new "data" wrapper, matching legacy behavior
        header('Content-Type: application/json');
        echo json_encode([
            'products' => $products,
            'totalRecords' => (int)$totalRecords,
            'totalPages' => ceil($totalRecords / $limit),
            'currentPage' => $page,
            'categories' => $categories
        ]);
        exit;
    }
}
