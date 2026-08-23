<?php

class Router
{
    private array $routes = [];

    // Routes qui ne peuvent pas porter de token CSRF (appelées par des services externes, pas un navigateur)
    private array $exemptsCsrf = [
        '/webhook/stripe',
    ];

    public function get(string $path, string $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, string $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        if ($method === 'POST' && !in_array($uri, $this->exemptsCsrf)) {
            $token = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);

            if (!Csrf::verifier($token)) {
                $estAjax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
                http_response_code(403);

                if ($estAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Session expirée. Veuillez recharger la page et réessayer.']);
                } else {
                    echo "Session expirée. <a href='javascript:history.back()'>Retour</a> et réessayez.";
                }
                return;
            }
        }

        $handler = $this->routes[$method][$uri] ?? null;

        if (!$handler) {
            http_response_code(404);
            echo 'Page non trouvée';
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
