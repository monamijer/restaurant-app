<?php

require_once __DIR__ . '/../app/core/Env.php';
Env::load(__DIR__ . '/../.env');

return [
    'db_host' => $_ENV['DB_HOST'] ?? 'localhost',
    'db_name' => $_ENV['DB_NAME'] ?? 'restaurant_app',
    'db_user' => $_ENV['DB_USER'] ?? 'root',
    'db_pass' => $_ENV['DB_PASS'] ?? '',
    'brevo_api_key' => $_ENV['BREVO_API_KEY'] ?? '',
    'brevo_sender_email' => $_ENV['BREVO_SENDER_EMAIL'] ?? '',
    'brevo_sender_name' => $_ENV['BREVO_SENDER_NAME'] ?? 'Etoile d\'Or',
    'stripe_secret_key' => $_ENV['STRIPE_SECRET_KEY'] ?? '',
    'stripe_publishable_key' => $_ENV['STRIPE_PUBLISHABLE_KEY'] ?? '',
    'stripe_webhook_secret' => $_ENV['STRIPE_WEBHOOK_SECRET'] ?? '',
    'url_site' => $_ENV['URL_SITE'] ?? 'http://restaurant.local',
];
