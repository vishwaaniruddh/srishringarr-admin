<?php

namespace Api\V1\Controllers;

use Api\V1\Models\CategoryModel;

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    public function index() {
        $categories = $this->categoryModel->getNestedCategories();

        echo json_encode([
            'status' => 'success',
            'data' => [
                'garments' => $categories['apparel'],
                'jewellery' => $categories['jewellery']
            ]
        ]);
    }
}
