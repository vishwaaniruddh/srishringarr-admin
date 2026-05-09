<?php

namespace Api\V1\Controllers;

use Api\V1\Core\Controller;
use Api\V1\Core\Response;
use Api\V1\Core\Auth;

class AuthController extends Controller {
    public function login() {
        $data = $this->getRequestData();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        // Mock login - in real app, check against DB
        if ($username === 'admin' && $password === 'password') {
            $payload = [
                'user_id' => 1,
                'username' => 'admin',
                'role' => 'admin',
                'exp' => time() + (60 * 60 * 24) // 24 hours
            ];

            $token = Auth::generateJWT($payload);
            
            Response::success("Login successful", [
                'token' => $token,
                'user' => [
                    'id' => 1,
                    'username' => 'admin',
                    'role' => 'admin'
                ]
            ]);
        }

        Response::error("Invalid credentials", 401);
    }

    public function me() {
        $userData = $this->validateAuth();
        Response::success("User data retrieved", $userData);
    }
}
