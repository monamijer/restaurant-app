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
    if ($method === 'HEAD') {
        $method = 'GET';
    }

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rtrim($uri, '/') ?: '/';

    $handler = $this->routes[$method][$uri] ?? null;
    if (!$handler) {
        http_response_code(404);
        echo "Page non trouvée";
        return;
    }

    [$controllerPath, $action] = explode('@', $handler);
    $parts = explode('/', $controllerPath);
    $className = end($parts);

    if (!class_exists($className)) {
        http_response_code(500);
        echo "Classe introuvable : $className";
        return;
    }

    $controller = new $className();
    $controller->$action();
}
}