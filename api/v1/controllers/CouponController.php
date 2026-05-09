<?php

namespace Api\V1\Controllers;

use Api\V1\Models\CouponModel;

class CouponController {
    private $model;

    public function __construct() {
        $this->model = new CouponModel();
    }

    public function index() {
        $params = [
            'search' => $_GET['search'] ?? '',
            'type' => $_GET['type'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];
        $coupons = $this->model->getAll($params);
        
        echo json_encode([
            'status' => 'success',
            'data' => $coupons
        ]);
    }

    public function show($id) {
        $coupon = $this->model->getById($id);
        if ($coupon) {
            echo json_encode(['status' => 'success', 'data' => $coupon]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Coupon not found']);
        }
    }

    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['code'])) {
            echo json_encode(['status' => 'error', 'message' => 'Coupon code is required']);
            return;
        }

        // Check for duplicate code
        if ($this->model->getByCode($data['code'])) {
            echo json_encode(['status' => 'error', 'message' => 'Coupon code already exists']);
            return;
        }

        if ($this->model->create($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Coupon created successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create coupon']);
        }
    }

    public function update($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($this->model->update($id, $data)) {
            echo json_encode(['status' => 'success', 'message' => 'Coupon updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update coupon']);
        }
    }

    public function delete($id) {
        if ($this->model->delete($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Coupon deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete coupon']);
        }
    }
}
