<?php

namespace Api\V1\Core;

class Controller {
    protected function getRequestData() {
        $json = file_get_contents('php://input');
        return json_decode($json, true) ?? $_POST;
    }

    protected function validateAuth() {
        $token = Auth::getBearerToken();
        if (!$token) {
            Response::error("Authorization token missing", 401);
        }

        $userData = Auth::validateJWT($token);
        if (!$userData) {
            Response::error("Invalid or expired token", 401);
        }

        return $userData;
    }
}
