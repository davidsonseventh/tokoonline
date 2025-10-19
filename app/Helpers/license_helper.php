<?php

/**
 * Replikasi mdmv_generate_license_key dari core-functions.php
 * Menggunakan service Encrypter bawaan CodeIgniter.
 *
 * @param string $domain Domain target
 * @param string $expiry_date Tanggal kadaluwarsa (YYYY-MM-DD)
 * @param int $product_id ID Produk
 * @return string Kunci lisensi yang sudah dienkripsi
 */
function generate_license_key(string $domain, string $expiry_date, int $product_id): string
{
    // Kita akan mereplikasi format data dari file lama Anda: domain|expiry_date
    // Kita bisa tambahkan data lain jika perlu, misal: domain|expiry_date|product_id
    $plain_text = $domain . '|' . $expiry_date . '|' . $product_id;

    // Muat service Encrypter (yang otomatis menggunakan kunci dari .env)
    $encrypter = \Config\Services::encrypter();

    // Enkripsi data.
    // Fungsi ini sudah otomatis menggunakan AES-256-CBC dan Base64 encode,
    // jadi ini jauh lebih aman dan ringkas.
    $encrypted_string = $encrypter->encrypt($plain_text);

    return $encrypted_string;
}

/**
 * Fungsi untuk mendekripsi/membaca data lisensi
 * (Nanti akan berguna untuk API verifikasi)
 *
 * @param string $license_key Kunci lisensi yang dienkripsi
 * @return array|null Data lisensi atau null jika gagal
 */
function decrypt_license_key(string $license_key): ?array
{
    try {
        $encrypter = \Config\Services::encrypter();
        $decrypted = $encrypter->decrypt($license_key);

        // Pecah datanya kembali
        $parts = explode('|', $decrypted);

        if (count($parts) === 3) {
            return [
                'domain'      => $parts[0],
                'expiry_date' => $parts[1],
                'product_id'  => $parts[2],
            ];
        }
        return null; // Format tidak valid
    } catch (\Exception $e) {
        return null; // Gagal dekripsi
    }
}