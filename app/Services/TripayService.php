<?php
namespace App\Services;

use GuzzleHttp\Client;

class TripayService
{
    protected $apiKey;
    protected $privateKey;
    protected $merchantCode;
    protected $baseURL;
    protected $client;

    public function __construct()
    {
        $this->apiKey = env('tripay.apiKey');
        $this->privateKey = env('tripay.privateKey');
        $this->merchantCode = env('tripay.merchantCode');
        $mode = env('tripay.mode');

        $this->baseURL = ($mode === 'sandbox') 
            ? 'https://tripay.co.id/api-sandbox/' 
            : 'https://tripay.co.id/api/';

        $this->client = new Client(['base_uri' => $this->baseURL]);
    }

    /**
     * Meminta daftar channel pembayaran
     */
    public function getPaymentChannels()
    {
        try {
            $response = $this->client->request('GET', 'merchant/payment-channel', [
                'headers' => [ 'Authorization' => 'Bearer ' . $this->apiKey ]
            ]);
            $body = json_decode($response->getBody(), true);
            return $body['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Membuat transaksi baru di Tripay
     * @param array $orderData Data dari order kita
     * @param array $customerData Data pelanggan
     * @param array $itemsData Item produk
     */
    public function createTransaction(array $orderData, array $customerData, array $itemsData)
    {
        // Buat signature
        $merchantRef = $orderData['merchant_ref'];
        $amount = $orderData['amount'];
        $signature = hash_hmac('sha256', $this->merchantCode . $merchantRef . $amount, $this->privateKey);

        $payload = [
            'method'         => $orderData['payment_method'],
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $customerData['name'],
            'customer_email' => $customerData['email'],
            'customer_phone' => $customerData['phone'],
            'order_items'    => $itemsData,
            'callback_url'   => site_url('checkout?wc-api=wc_gateway_tripay'), // URL Callback "ajaib" kita
            'return_url'     => site_url('dashboard/orders/' . $merchantRef), // Halaman detail order
            'signature'      => $signature
        ];

        try {
            $response = $this->client->request('POST', 'transaction/create', [
                'headers' => [ 'Authorization' => 'Bearer ' . $this->apiKey ],
                'form_params' => $payload
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            // Tangani error Guzzle (misal: validasi)
            log_message('error', 'Tripay API Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Memvalidasi callback signature dari Tripay
     */
    public function validateCallback($jsonPayload)
    {
        $localSignature = hash_hmac('sha256', $jsonPayload, $this->privateKey);
        $tripaySignature = $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] ?? '';

        return hash_equals($localSignature, $tripaySignature);
    }
}