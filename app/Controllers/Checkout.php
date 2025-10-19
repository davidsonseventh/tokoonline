<?php

namespace App\Controllers;

use App\Services\TripayService;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\UserModel;

class Checkout extends BaseController
{
    protected $helpers = ['form', 'url']; // Helper 'cart' tidak diperlukan lagi

    protected $session;
    protected $tripay;
    protected $cart; // Tambahkan properti ini

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->tripay = new TripayService();
        // Muat service keranjang menggunakan sintaks CI4 yang benar
        $this->cart = \Config\Services::cart();
    }

    public function index()
    {
        if ($this->cart->totalItems() == 0) { // Gunakan $this->cart
            return redirect()->to('cart');
        }

        if (! $this->session->get('isLoggedIn')) {
            $this->session->set('redirect_url', current_url());
            return redirect()->to('login')->with('error', 'Silakan login untuk melanjutkan checkout.');
        }

        $data['paymentChannels'] = $this->tripay->getPaymentChannels();

        if (empty($data['paymentChannels'])) {
            log_message('error', 'Gagal mengambil channel pembayaran Tripay. Cek API Key.');
            $this->session->setFlashdata('error', 'Gagal memuat metode pembayaran. Silakan coba lagi nanti.');
        }

        $data['total'] = $this->cart->total(); // Gunakan $this->cart

        return view('store/header')
             . view('store/checkout', $data)
             . view('store/footer');
    }

    public function placeOrder()
    {
        if (! $this->session->get('isLoggedIn')) {
            return redirect()->to('login');
        }

        $orderModel = new OrderModel();
        $orderItemModel = new OrderItemModel();
        $userModel = new UserModel();

        $userId = $this->session->get('user_id');
        $user = $userModel->find($userId);
        $totalAmount = $this->cart->total(); // Gunakan $this->cart
        $paymentMethod = $this->request->getPost('payment_method');

        if (empty($paymentMethod)) {
             $this->session->setFlashdata('error', 'Silakan pilih metode pembayaran.');
             return redirect()->back();
        }

        $orderData = [
            'user_id'      => $userId,
            'total_amount' => $totalAmount,
            'status'       => 'pending',
        ];

        $order_id = $orderModel->insert($orderData, true); 

        if (!$order_id) {
            $this->session->setFlashdata('error', 'Gagal membuat pesanan di database.');
            return redirect()->back();
        }

        $tripayItems = [];
        foreach ($this->cart->contents() as $item) { // Gunakan $this->cart
            $orderItemModel->save([
                'order_id'   => $order_id,
                'product_id' => $item['id'],
                'quantity'   => $item['qty'],
                'price'      => $item['price'],
                'subtotal'   => $item['subtotal'],
            ]);
            $tripayItems[] = [
                'sku'       => 'PROD-' . $item['id'],
                'name'      => $item['name'],
                'price'     => (int)$item['price'],
                'quantity'  => (int)$item['qty'],
            ];
        }

        $tripayOrderData = [
            'merchant_ref'   => $order_id,
            'amount'         => (int)$totalAmount,
            'payment_method' => $paymentMethod,
        ];
        $customerData = [
            'name'  => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone_number,
        ];

        $tripayResponse = $this->tripay->createTransaction($tripayOrderData, $customerData, $tripayItems);

        if ($tripayResponse && $tripayResponse['success'] === true) {

            $orderModel->update($order_id, [
                'tripay_reference' => $tripayResponse['data']['reference'],
                'payment_method'   => $paymentMethod
            ]);

            $this->cart->destroy(); // Gunakan $this->cart

            return redirect()->to($tripayResponse['data']['checkout_url']);

        } else {
            $orderModel->update($order_id, ['status' => 'failed']);

            $errorMessage = 'Gagal membuat transaksi Tripay.';
            if (isset($tripayResponse['message'])) {
                $errorMessage .= ' Pesan: ' . $tripayResponse['message'];
            }

            log_message('error', '[Tripay Error] ' . print_r($tripayResponse, true));
            $this->session->setFlashdata('error', $errorMessage);
            return redirect()->back();
        }
    }
}