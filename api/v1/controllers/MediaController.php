<?php

namespace Api\V1\Controllers;

use Api\V1\Core\Controller;
use Api\V1\Core\Response;

class MediaController extends Controller {
    public function upload() {
        $id = (int)($_POST['product_id'] ?? 0);
        $type = $_POST['product_type'] ?? 'jewellery';
        
        if (!$id || !isset($_FILES['image'])) {
            Response::error("Missing product ID or image", 400);
        }

        // Relative path for DB
        $datePath = date('Y/m/');
        $uploadDir = "C:/xampp/htdocs/sri/yn/uploads/" . $datePath;
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . rand(100, 999) . '.' . $extension;
        $targetPath = $uploadDir . $fileName;
        $dbPath = $datePath . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $model = new \Api\V1\Models\ProductModel();
            $result = $model->addProductImage($id, $type, $dbPath);
            
            if ($result) {
                Response::success("Image uploaded", ['path' => $dbPath]);
            } else {
                Response::error("Failed to save image metadata", 500);
            }
        } else {
            Response::error("Failed to move uploaded file", 500);
        }
    }

    public function delete() {
        $data = $this->getRequestData();
        $id = (int)($data['product_id'] ?? 0);
        $type = $data['product_type'] ?? 'jewellery';
        $path = $data['path'] ?? '';

        if (!$id || !$path) {
            Response::error("ID and path required", 400);
        }

        $model = new \Api\V1\Models\ProductModel();
        if ($model->deleteProductImage($id, $type, $path)) {
            Response::success("Image deleted");
        } else {
            Response::error("Failed to delete image", 500);
        }
    }

    public function setPrimary() {
        $data = $this->getRequestData();
        $id = (int)($data['product_id'] ?? 0);
        $type = $data['product_type'] ?? 'jewellery';
        $path = $data['path'] ?? '';

        if (!$id || !$path) {
            Response::error("ID and path required", 400);
        }

        $model = new \Api\V1\Models\ProductModel();
        if ($model->setPrimaryImage($id, $type, $path)) {
            Response::success("Primary image set");
        } else {
            Response::error("Failed to set primary image", 500);
        }
    }
}
