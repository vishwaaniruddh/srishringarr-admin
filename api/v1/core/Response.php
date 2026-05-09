<?php

namespace Api\V1\Core;

class Response {
    public static function json($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    public static function success($message = "Success", $data = [], $status = 200) {
        self::json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function error($message = "Error", $status = 400, $errors = []) {
        self::json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $status);
    }
}
