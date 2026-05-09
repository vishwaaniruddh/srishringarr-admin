<?php

namespace Api\V1\Core;

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Dynamically determine the base path (e.g., /sri/admin/project/api/v1)
        $scriptName = $_SERVER['SCRIPT_NAME']; // e.g. /sri/admin/project/api/v1/index.php
        $basePath = str_replace('/index.php', '', $scriptName);
        $relativePath = str_replace($basePath, '', $path);
        
        foreach ($this->routes as $route) {
            $pattern = "#^" . $route['path'] . "$#";
            if ($route['method'] === $method && preg_match($pattern, $relativePath, $matches)) {
                array_shift($matches); // Remove full match
                
                $handler = $route['handler'];
                if (is_callable($handler)) {
                    return call_user_func_array($handler, $matches);
                }
                
                if (is_string($handler) && strpos($handler, '@') !== false) {
                    list($controllerName, $methodName) = explode('@', $handler);
                    $controllerClass = "Api\\V1\\Controllers\\" . $controllerName;
                    
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        if (method_exists($controller, $methodName)) {
                            return call_user_func_array([$controller, $methodName], $matches);
                        }
                    }
                }
            }
        }

        Response::error("Route not found", 404);
    }
}
