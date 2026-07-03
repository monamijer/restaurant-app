<?php

class Router {
    private array $routes = [];

    public function get(string $path, string $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, string $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = '/restaurant-app/public'; // à adapter selon le déploiement alwaysdata
        $uri = str_replace($basePath, '', $uri);
        $uri = rtrim($uri, '/') ?: '/';

        $handler = $this->routes[$method][$uri] ?? null;

        if (!$handler) {
            http_response_code(404);
            echo "Page non trouvée";
            return;
        }

        [$controllerName, $action] = explode('@', $handler);
        $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

        if (!file_exists($controllerFile)) {
            http_response_code(500);
            echo "Contrôleur introuvable : $controllerName";
            return;
        }

        require_once $controllerFile;
        $controller = new $controllerName();
        $controller->$action();
    }
}