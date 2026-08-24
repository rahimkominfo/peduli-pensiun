<?php

namespace App\Controllers;

use App\Models\PpDokumenRefModel;
use App\Models\PpJenisModel;
use App\Models\PpDokumenJenisModel;
use App\Models\PpDokumenModel;

class Dokumen extends BaseController
{
    /**
     * Halaman Dokumen Pensiun - /dokumen
     * Menampilkan daftar konsep dokumen acuan berdasarkan filter Jenis Pensiun.
     */
    public function index()
    {
        $dokumenRefModel = new PpDokumenRefModel();
        $jenisModel      = new PpJenisModel();

        $jenisList = $jenisModel->findAll();
        $selectedPpjId = $this->request->getGet('ppj_id');

        if (!empty($selectedPpjId) && (int)$selectedPpjId > 0) {
            $dokumenList = $dokumenRefModel->select('PP_DOKUMEN_REF.*')
                ->join('PP_DOKUMEN_JENIS dj', 'dj.DOKUMEN_KODE = PP_DOKUMEN_REF.DOKUMEN_KODE', 'inner')
                ->where('dj.PPJ_ID', (int)$selectedPpjId)
                ->findAll();
        } else {
            $dokumenList = $dokumenRefModel->findAll();
        }

        $isAdmin = (session('user_role') === 'Admin');

        $data = [
            'title'         => 'Dokumen Pensiun - Peduli Pensiun',
            'activeMenu'    => 'dokumen',
            'jenisList'     => $jenisList,
            'selectedPpjId' => $selectedPpjId,
            'dokumenList'   => $dokumenList,
            'isAdmin'       => $isAdmin,
        ];

        return view('dokumen/index', $data);
    }

    /**
     * Update Konsep Dokumen - POST /dokumen/edit_konsep
     */
    public function editKonsep()
    {
        if (!is_admin_kabupaten_or_special_nip()) {
            return redirect()->to('/dokumen')->with('error', 'Anda tidak memiliki hak akses untuk mengubah konsep dokumen.');
        }

        $dokumenRefModel = new PpDokumenRefModel();

        $dokumenKode = $this->request->getPost('DOKUMEN_KODE') ?? $this->request->getPost('dokumen_kode');
        $nmKonsep    = trim($this->request->getPost('NM_KONSEP') ?? $this->request->getPost('nm_konsep') ?? '');

        if (!$dokumenKode) {
            return redirect()->back()->with('error', 'Kode dokumen tidak valid.');
        }

        $existing = $dokumenRefModel->find($dokumenKode);
        if (!$existing) {
            return redirect()->back()->with('error', 'Data dokumen tidak ditemukan.');
        }

        $updateData = [];
        if (!empty($nmKonsep)) {
            $updateData['NM_KONSEP'] = $nmKonsep;
        }

        $file = $this->request->getFile('file_konsep');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = ROOTPATH . 'public/uploads/konsep';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $updateData['KONSEP_DOKUMEN'] = $newName;
        }

        if (!empty($updateData)) {
            $dokumenRefModel->update($dokumenKode, $updateData);
            return redirect()->to('/dokumen')->with('success', 'Dokumen berhasil diperbarui!');
        }

        return redirect()->to('/dokumen')->with('error', 'Tidak ada perubahan data.');
    }

    /**
     * Halaman Hak Akses Konsep Dokumen - /dokumen/konsep_akses
     */
    public function konsepAkses()
    {
        if (!is_admin_kabupaten_or_special_nip()) {
            return redirect()->to('/dokumen')->with('error', 'Anda tidak memiliki hak akses ke halaman Konsep Akses Dokumen.');
        }

        $dokumenRefModel   = new PpDokumenRefModel();
        $jenisModel        = new PpJenisModel();
        $dokumenJenisModel = new PpDokumenJenisModel();

        $jenisList   = $jenisModel->findAll();
        $dokumenList = $dokumenRefModel->findAll();
        $allAkses    = $dokumenJenisModel->findAll();

        // Build matrix mapping: [DOKUMEN_KODE][PPJ_ID] = true
        $matrix = [];
        foreach ($allAkses as $akses) {
            $matrix[$akses['DOKUMEN_KODE']][$akses['PPJ_ID']] = true;
        }

        $isAdmin = (session('user_role') === 'Admin');

        $data = [
            'title'       => 'Dokumen Akses & Matriks Syarat Pensiun - Peduli Pensiun',
            'activeMenu'  => 'dokumen',
            'jenisList'   => $jenisList,
            'dokumenList' => $dokumenList,
            'matrix'      => $matrix,
            'isAdmin'     => $isAdmin,
        ];

        return view('dokumen/konsep_akses', $data);
    }

    /**
     * Tambah Master Dokumen - POST /dokumen/tambah_konsep
     */
    public function tambahKonsep()
    {
        if (!is_admin_kabupaten_or_special_nip()) {
            return redirect()->to('/dokumen')->with('error', 'Anda tidak memiliki hak akses untuk menambah konsep dokumen.');
        }

        $dokumenRefModel = new PpDokumenRefModel();

        $nmDokumen = trim($this->request->getPost('nm_dokumen') ?? '');
        $nmKonsep  = trim($this->request->getPost('nm_konsep') ?? '');

        if (empty($nmDokumen)) {
            return redirect()->back()->with('error', 'Nama Master Dokumen wajib diisi.');
        }

        $fileName = '';
        $file = $this->request->getFile('file_konsep');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = ROOTPATH . 'public/uploads/konsep';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $fileName = $file->getRandomName();
            $file->move($uploadPath, $fileName);
        }

        $dokumenRefModel->insert([
            'NM_DOKUMEN'     => $nmDokumen,
            'NM_KONSEP'      => $nmKonsep ?: $nmDokumen,
            'KONSEP_DOKUMEN' => $fileName,
        ]);

        return redirect()->to('/dokumen/konsep_akses')->with('success', 'Dokumen telah ditambahkan!');
    }

    /**
     * Toggle Akses Dokumen via AJAX - POST /dokumen/ganti_akses
     */
    public function gantiAkses()
    {
        if (!is_admin_kabupaten_or_special_nip()) {
            return $this->response->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki hak akses.'
            ]);
        }

        $dokumenJenisModel = new PpDokumenJenisModel();

        $dokumenKode = (int)$this->request->getPost('dokumen_kode');
        $ppjId       = (int)$this->request->getPost('ppj_id');

        if (!$dokumenKode || !$ppjId) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Parameter tidak lengkap.'
            ]);
        }

        $existing = $dokumenJenisModel
            ->where('DOKUMEN_KODE', $dokumenKode)
            ->where('PPJ_ID', $ppjId)
            ->first();

        if ($existing) {
            $dokumenJenisModel->delete($existing['DOKJES_ID']);
            return $this->response->setJSON([
                'status'  => 'success',
                'action'  => 'deleted',
                'message' => 'Akses telah diganti!'
            ]);
        } else {
            $dokumenJenisModel->insert([
                'PPJ_ID'       => $ppjId,
                'DOKUMEN_KODE' => $dokumenKode
            ]);
            return $this->response->setJSON([
                'status'  => 'success',
                'action'  => 'inserted',
                'message' => 'Akses telah diganti!'
            ]);
        }
    }

    /**
     * Hapus Master Dokumen - GET/POST /dokumen/delete/(:num)
     */
    public function delete($id)
    {
        if (!is_admin_kabupaten_or_special_nip()) {
            return redirect()->to('/dokumen')->with('error', 'Anda tidak memiliki hak akses untuk menghapus dokumen.');
        }

        $dokumenRefModel   = new PpDokumenRefModel();
        $dokumenJenisModel = new PpDokumenJenisModel();
        $dokumenModel      = new PpDokumenModel();

        $doc = $dokumenRefModel->find($id);
        if (!$doc) {
            return redirect()->to('/dokumen/konsep_akses')->with('error', 'Dokumen tidak ditemukan.');
        }

        // Unlink physical file in public/uploads/konsep/ if exists
        if (!empty($doc['KONSEP_DOKUMEN'])) {
            $filePath = ROOTPATH . 'public/uploads/konsep/' . $doc['KONSEP_DOKUMEN'];
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
        }

        // Get DOKJES_IDs to delete dependent user documents
        $dokjesRows = $dokumenJenisModel->where('DOKUMEN_KODE', $id)->findAll();
        foreach ($dokjesRows as $dj) {
            $dokumenModel->where('DOKJES_ID', $dj['DOKJES_ID'])->delete();
        }

        // Delete relations in PP_DOKUMEN_JENIS
        $dokumenJenisModel->where('DOKUMEN_KODE', $id)->delete();

        // Delete from PP_DOKUMEN_REF
        $dokumenRefModel->delete($id);

        return redirect()->to('/dokumen/konsep_akses')->with('success', 'Dokumen ' . $doc['NM_DOKUMEN'] . ' berhasil dihapus.');
    }
}
