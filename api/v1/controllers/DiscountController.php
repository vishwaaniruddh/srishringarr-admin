<?php

namespace Api\V1\Controllers;

use Api\V1\Models\DiscountModel;

class DiscountController {
    private $model;

    public function __construct() {
        $this->model = new DiscountModel();
    }

    public function index() {
        $params = [
            'search' => $_GET['search'] ?? '',
            'scope' => $_GET['scope'] ?? ''
        ];
        $rules = $this->model->getAll($params);
        
        echo json_encode([
            'status' => 'success',
            'data' => $rules
        ]);
    }

    public function show($id) {
        $rule = $this->model->getById($id);
        if ($rule) {
            echo json_encode(['status' => 'success', 'data' => $rule]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Rule not found']);
        }
    }

    public function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['scope'])) {
            echo json_encode(['status' => 'error', 'message' => 'Discount scope is required']);
            return;
        }

        if ($this->model->create($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Rule created successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create rule']);
        }
    }

    public function update($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if ($this->model->update($id, $data)) {
            echo json_encode(['status' => 'success', 'message' => 'Rule updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update rule']);
        }
    }

    public function delete($id) {
        if ($this->model->delete($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Rule deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete rule']);
        }
    }

    public function searchTargets() {
        $type = $_GET['type'] ?? '';
        $term = $_GET['term'] ?? '';
        
        if (empty($type) || empty($term)) {
            echo json_encode(['status' => 'success', 'data' => []]);
            return;
        }

        $results = $this->model->searchTargets($type, $term);
        echo json_encode(['status' => 'success', 'data' => $results]);
    }
}
