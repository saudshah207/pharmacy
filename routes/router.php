<?php

class Router {
    private $routes = [];

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) { 
        $this->routes['POST'][$uri] = $action;
    }

    public function resolve($uri, $method) {
        $action = $this->routes[$method][$uri] ?? null;

        if (!$action) {
            require __DIR__ . '/../app/Views/404.php';
            return;
        }

        [$controller, $method] = explode('@', $action);

        require __DIR__ . "/../app/Controller/$controller.php";

        $modelsGetter = "get" . $controller . "Models";
        $requiredModels = function_exists($modelsGetter) ? $modelsGetter() : null; 
        
        if ($requiredModels) {
            $controller = new $controller(...$requiredModels);
        } else {
            $controller::$method();

            return;
        }

        $controller->$method();
    }
}