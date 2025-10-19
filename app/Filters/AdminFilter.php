<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();

        // Cek apakah pengguna BUKAN admin
        if ($session->get('role') !== 'admin') {
            // Jika bukan admin, tendang ke dashboard mereka
            return redirect()->to(site_url('dashboard'))
                             ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu
    }
}