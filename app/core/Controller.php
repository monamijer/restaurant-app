<?php

class Controller {
    protected function render(string $view, array $data = []): void {
        extract($data);
        $viewFile = __DIR__ . "/../views/{$view}.php";

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "Vue introuvable : $view";
            return;
        }

        require $viewFile;
    }

    protected function requireRole(string $role): void {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            http_response_code(403);
            echo "Accès refusé";
            exit;
        }
    }
}