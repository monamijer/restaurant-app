<?php

class Auth {
    public static function attempt(string $email, string $password): bool {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        self::login($user);
        return true;
    }

    public static function login(array $user): void {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['role'] = $user['role'];
    }

    public static function logout(): void {
        $_SESSION = [];
        session_destroy();
    }

    public static function check(): bool {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array {
        if (!self::check()) return null;
        return [
            'id' => $_SESSION['user_id'],
            'nom' => $_SESSION['nom'],
            'role' => $_SESSION['role'],
        ];
    }

    public static function role(): ?string {
        return $_SESSION['role'] ?? null;
    }
}