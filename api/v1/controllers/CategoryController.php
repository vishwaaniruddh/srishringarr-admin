<?php

namespace Api\V1\Controllers;

use Api\V1\Core\Controller;
use Api\V1\Core\Response;
use Api\V1\Models\ProductModel;

class CategoryController extends Controller {
    public function index() {
        try {
            $model = new ProductModel();
            $data = $model->getCategoriesWithCounts();
            Response::success("Categories retrieved", $data);
        } catch (\Exception $e) {
            Response::error("Failed to fetch categories: " . $e->getMessage(), 500);
        }
    }
}
