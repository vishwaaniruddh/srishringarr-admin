<?php

namespace Api\V1\Controllers;

use Api\V1\Models\DashboardModel;

class DashboardController {
    private $model;

    public function __construct() {
        $this->model = new DashboardModel();
    }

    public function index() {
        $stats = $this->model->getStats();
        echo json_encode([
            'status' => 'success',
            'data' => $stats
        ]);
    }
}
