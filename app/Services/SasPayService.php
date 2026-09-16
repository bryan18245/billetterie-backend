<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class SasPayService
{
    private string $baseUrl;
    private string $secretKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            config('services.saspay.base_url'),
            '/'
        );

        $this->secretKey = config('services.saspay.secret_key');
    }

    /**
     * Initier un paiement SasPay Softpay.
     *
     * @param float|string $amount
     * @param string $phone
     * @param string $network
     * @param string $description
     * @param array $customer
     * @param string|null $idempotencyKey
     *
     * @return array
     */
    public function createPayment(
        float|string $amount,
        string $phone,
        string $network,
        string $description,
        array $customer = [],
        ?string $idempotencyKey = null
    ): array {
        $idempotencyKey ??= (string) Str::uuid();

        $payload = [
            'amount' => number_format((float) $amount, 2, '.', ''),
            'currency' => 'XOF',
            'country' => 'CI',
            'description' => $description,

            'customer' => [
                'email' => $customer['email'] ?? null,
                'first_name' => $customer['first_name'] ?? '',
                'last_name' => $customer['last_name'] ?? '',
                'phone' => $phone,
            ],

            'network' => $network,
        ];

        // Supprimer les valeurs nulles
        $payload['customer'] = array_filter(
            $payload['customer'],
            fn($value) => $value !== null
        );

        Log::info('SasPay - Initialisation paiement', [
            'amount' => $payload['amount'],
            'network' => $network,
            'phone' => $phone,
            'idempotency_key' => $idempotencyKey,
        ]);

        $response = Http::withToken($this->secretKey)
            ->acceptJson()
            ->withHeaders([
                'Idempotency-Key' => $idempotencyKey,
            ])
            ->timeout(30)
            ->post(
                $this->baseUrl . '/payments/softpay/',
                $payload
            );

        if ($response->failed()) {
            Log::error('SasPay - Erreur initialisation paiement', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            throw new RuntimeException(
                $response->json('message')
                    ?? 'Impossible d\'initialiser le paiement SasPay.'
            );
        }

        $data = $response->json();

        Log::info('SasPay - Paiement initialisé', [
            'payment_id' => $data['id'] ?? null,
            'status' => $data['status'] ?? null,
        ]);

        return $data;
    }

    /**
     * Vérifier le statut d'un paiement SasPay.
     */
    public function verifyPayment(string $paymentId): array
    {
        $response = Http::withToken($this->secretKey)
            ->acceptJson()
            ->timeout(30)
            ->get(
                $this->baseUrl . '/payments/' . $paymentId . '/verify/'
            );

        if ($response->failed()) {
            Log::error('SasPay - Erreur vérification paiement', [
                'payment_id' => $paymentId,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            throw new RuntimeException(
                $response->json('message')
                    ?? 'Impossible de vérifier le paiement SasPay.'
            );
        }

        return $response->json();
    }
}