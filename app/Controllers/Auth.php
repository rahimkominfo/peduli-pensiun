<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    /**
     * Menampilkan halaman login atau memproses jika dipanggil via index (CI3 compatibility)
     */
    public function index()
    {
        if ($this->request->is('post') || $this->request->getMethod() === 'post') {
            return $this->attemptLogin();
        }

        return $this->login();
    }

    /**
     * Halaman Login
     */
    public function login()
    {
        if (session()->get('isLoggedIn') || session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    /**
     * Proses Verifikasi & Login ke API Pegawai
     */
    public function attemptLogin()
    {
        $rules = [
            'nip' => [
                'rules'  => 'required|alpha_numeric',
                'errors' => [
                    'required'      => 'NIP wajib diisi.',
                    'alpha_numeric' => 'NIP hanya boleh berisi huruf dan angka.'
                ]
            ],
            'password' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Password wajib diisi.'
                ]
            ]
        ];

        if (! $this->validate($rules)) {
            $errorMsg = $this->validator->getError('nip') ?: $this->validator->getError('password');
            return $this->redirectWithError($errorMsg);
        }

        $nip      = trim($this->request->getPost('nip') ?? '');
        $password = $this->request->getPost('password') ?? '';

        // Ambil data pegawai dari API
        $url = SITE_API . 'data_pegawai/?nip=' . urlencode($nip);
        $response = $this->fetchApi($url);

        if ($response === false) {
            return $this->redirectWithError('Server API tidak dapat dihubungi!');
        }

        $data_pegawai = json_decode($response);

        // Cek apakah data pegawai ditemukan
        if (
            !$data_pegawai ||
            !isset($data_pegawai->nip) ||
            (int) $data_pegawai->nip <= 0
        ) {
            return $this->redirectWithError('NIP tidak ditemukan!');
        }

        // Verifikasi password (MD5 hash dari API atau bypass password development)
        // TODO: hapus password bypass 'fokusdisinitugasmu' sebelum production
        $apiPassword = $data_pegawai->password ?? '';
        if (
            md5($password) === $apiPassword ||
            $password === 'fokusdisinitugasmu'
        ) {
            $isAdmin = ((int)($data_pegawai->admin_kabupaten ?? 0) === 1 || (int)($data_pegawai->admin_unit ?? 0) === 1);

            $session_data = [
                // CI3 original session keys
                'nip'               => (int) $data_pegawai->nip,
                'unit_id'           => (int) ($data_pegawai->unit_id ?? 0),
                'jabatan_id'        => (int) ($data_pegawai->jabatan_id ?? 0),
                'jabatan_jenis_id'  => (int) ($data_pegawai->jabatan_jenis_id ?? 0),
                'jabatan_atasan_id' => (int) ($data_pegawai->jabatan_atasan_id ?? 0),
                'nama'              => (string) ($data_pegawai->nama ?? ''),
                'admin_unit'        => (int) ($data_pegawai->admin_unit ?? 0),
                'admin_kabupaten'   => (int) ($data_pegawai->admin_kabupaten ?? 0),
                'is_logged_in'      => true,

                // CI4 application compatibility keys
                'isLoggedIn'        => true,
                'user_nip'          => (string) $data_pegawai->nip,
                'user_nama'         => (string) ($data_pegawai->nama ?? ''),
                'user_role'         => $isAdmin ? 'Admin' : 'Pegawai',
                'user_unit'         => (string) ($data_pegawai->unit_nama ?? ($data_pegawai->unit_id ?? '')),
            ];

            // Regenerasi session ID setelah login untuk keamanan
            session()->regenerate(true);

            // Simpan data ke session
            session()->set($session_data);

            return redirect()->to('/dashboard')->with('success', 'Selamat datang, ' . ($data_pegawai->nama ?? ''));
        }

        // Password salah
        return $this->redirectWithError('Password Salah!');
    }

    /**
     * Proses Logout
     */
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Helper untuk memanggil API eksternal
     */
    private function fetchApi(string $url)
    {
        // Coba gunakan CURLRequest CodeIgniter 4
        try {
            $client = \Config\Services::curlrequest([
                'timeout'         => 10,
                'connect_timeout' => 5,
                'http_errors'     => false,
                'verify'          => false,
            ]);

            $res = $client->get($url);
            if ($res->getStatusCode() === 200) {
                return (string) $res->getBody();
            }
        } catch (\Throwable $e) {
            // Fallback ke file_get_contents jika cURL gagal
        }

        return @file_get_contents($url);
    }

    /**
     * Helper redirect dengan pesan error (mendukung flashdata 'error' & 'pesan')
     */
    private function redirectWithError(string $message)
    {
        // Set flashdata 'pesan' format alert HTML (CI3 style) dan 'error' string (CI4 style)
        session()->setFlashdata('pesan', '<div class="alert alert-danger" role="alert">' . esc($message) . '</div>');
        session()->setFlashdata('error', $message);

        return redirect()->back()->withInput();
    }
}
