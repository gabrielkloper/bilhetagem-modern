<?php

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Resources\Preference;
use MercadoPago\Resources\Payment;
use Illuminate\Support\Facades\Log;
use Exception;

class MercadoPagoService
{
    protected $preferenceClient;
    protected $paymentClient;
    protected bool $sandbox;

    public function __construct()
    {
        $accessToken = config('mercadopago.access_token');
        $this->sandbox = config('mercadopago.sandbox', true);
        
        if (!$accessToken) {
            throw new Exception('Mercado Pago access token not configured');
        }

        MercadoPagoConfig::setAccessToken($accessToken);
        MercadoPagoConfig::setRuntimeEnviroment($this->sandbox ? MercadoPagoConfig::SANDBOX : MercadoPagoConfig::PRODUCTION);

        $this->preferenceClient = new PreferenceClient();
        $this->paymentClient = new PaymentClient();
    }

    public function createPixPayment(array $data): array
    {
        try {
            $request = [
                "transaction_amount" => $data['amount'],
                "payment_method_id" => "pix",
                "payer" => [
                    "email" => $data['payer_email'] ?? "test@test.com",
                    "first_name" => $data['payer_name'] ?? "Cliente",
                    "identification" => [
                        "type" => "CPF",
                        "number" => $data['payer_document'] ?? "11144477735"
                    ]
                ],
                "description" => $data['description'] ?? "Pagamento adicional - Bilhetagem",
                "external_reference" => $data['external_reference'] ?? null,
                "notification_url" => config('mercadopago.webhook.url'),
                "date_of_expiration" => now()->addMinutes(config('mercadopago.pix.expiration_minutes', 30))->toISOString()
            ];

            $payment = $this->paymentClient->create($request);

            return [
                'success' => true,
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'qr_code' => $payment->point_of_interaction->transaction_data->qr_code ?? null,
                'qr_code_base64' => $payment->point_of_interaction->transaction_data->qr_code_base64 ?? null,
                'ticket_url' => $payment->point_of_interaction->transaction_data->ticket_url ?? null,
                'expiration_date' => $payment->date_of_expiration,
                'amount' => $payment->transaction_amount
            ];

        } catch (Exception $e) {
            Log::error('Mercado Pago PIX payment error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function createCardPayment(array $data): array
    {
        try {
            $request = [
                "transaction_amount" => $data['amount'],
                "token" => $data['card_token'], // Card token from frontend
                "installments" => $data['installments'] ?? 1,
                "payment_method_id" => $data['payment_method_id'],
                "issuer_id" => $data['issuer_id'] ?? null,
                "payer" => [
                    "email" => $data['payer_email'] ?? "test@test.com",
                    "identification" => [
                        "type" => "CPF", 
                        "number" => $data['payer_document'] ?? "11144477735"
                    ],
                    "first_name" => $data['payer_name'] ?? "Cliente"
                ],
                "description" => $data['description'] ?? "Pagamento adicional - Bilhetagem",
                "external_reference" => $data['external_reference'] ?? null,
                "notification_url" => config('mercadopago.webhook.url')
            ];

            $payment = $this->paymentClient->create($request);

            return [
                'success' => true,
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'amount' => $payment->transaction_amount
            ];

        } catch (Exception $e) {
            Log::error('Mercado Pago card payment error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function getPayment(string $paymentId): ?Payment
    {
        try {
            return $this->paymentClient->get($paymentId);
        } catch (Exception $e) {
            Log::error('Error fetching payment: ' . $e->getMessage());
            return null;
        }
    }

    public function getPaymentStatus(string $paymentId): ?string
    {
        $payment = $this->getPayment($paymentId);
        return $payment ? $payment->status : null;
    }

    public function isPaymentApproved(string $paymentId): bool
    {
        return $this->getPaymentStatus($paymentId) === 'approved';
    }

    public function createPreference(array $items, array $payer = [], array $metadata = []): Preference
    {
        $request = [
            "items" => $items,
            "payer" => $payer,
            "back_urls" => [
                "success" => url('/payment/success'),
                "failure" => url('/payment/failure'),
                "pending" => url('/payment/pending')
            ],
            "auto_return" => "approved",
            "notification_url" => config('mercadopago.webhook.url'),
            "metadata" => $metadata
        ];

        return $this->preferenceClient->create($request);
    }
}
