<?php

class StripeWebhookController extends Controller {
    public function handle() {
        $config = require __DIR__ . '/../../config/config.php';
        \Stripe\Stripe::setApiKey($config['stripe_secret_key']);

        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $config['stripe_webhook_secret']);
        } catch (\Exception $e) {
            http_response_code(400);
            exit;
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $reservationId = (int) $session->metadata->reservation_id;

            $reservationModel = new Reservation();
            $reservationModel->update($reservationId, [
                'statut' => 'CONFIRMEE',
                'statut_acompte' => 'PAYE',
            ]);
        }

        http_response_code(200);
    }
}