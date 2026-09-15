<?php

namespace App\Controllers;

use App\Models\PpDataModel;
use App\Models\PpJenisModel;
use App\Models\PpProgresDataModel;
use App\Models\PpProgresRefModel;

class Persiapan extends BaseController
{
    public function index()
    {
        if (!can_access_persiapan()) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman Data Persiapan.');
        }

        $ppDataModel   = new PpDataModel();
        $ppJenisModel  = new PpJenisModel();
        $progresRef    = new PpProgresRefModel();

        $filters = [
            'unit'    => $this->request->getGet('unit') ?? '',
            'progres' => $this->request->getGet('progres') ?? '',
            'tahun'   => $this->request->getGet('tahun') ?? '',
            'keyword' => $this->request->getGet('keyword') ?? '',
        ];

        // Jika login sebagai admin_unit, kunci akses hanya ke unit miliknya sendiri
        if (is_admin_unit()) {
            $userUnitId = (int) (session('unit_id') ?? 0);
            $filters['unit_id'] = $userUnitId;
            unset($filters['unit']);
        }

        $candidates = $ppDataModel->getWithProgres($filters);
        $jenisList  = $ppJenisModel->findAll();
        $refProgres = $progresRef->findAll();
        $yearsList  = $ppDataModel->getAvailableYears();

        $unitList = [];
        if (!is_admin_unit()) {
            $unitList = $ppDataModel->select('UNIT_NAMA')->distinct()->where('UNIT_NAMA IS NOT NULL')->orderBy('UNIT_NAMA', 'ASC')->findAll();
        }

        $data = [
            'title'        => 'Data Persiapan - Peduli Pensiun',
            'activeMenu'   => 'data-persiapan',
            'candidates'   => $candidates,
            'jenisList'    => $jenisList,
            'refProgres'   => $refProgres,
            'yearsList'    => $yearsList,
            'unitList'     => $unitList,
            'filters'      => $filters,
            'selectedUnit' => $filters['unit'] ?? '',
            'totalCount'   => count($candidates),
        ];

        return view('persiapan/index', $data);
    }

    public function create()
    {
        if (!can_create_persiapan()) {
            return redirect()->to('/data-persiapan')->with('error', 'Anda tidak memiliki hak akses untuk menambah data.');
        }

        $ppDataModel = new PpDataModel();
        $progresModel = new PpProgresDataModel();

        $nip        = trim($this->request->getPost('nip') ?? '');
        $tglPensiun = $this->request->getPost('tgl_pensiun');
        $ppjId      = $this->request->getPost('ppj_id');

        if (empty($nip)) {
            return redirect()->back()->withInput()->with('error', 'NIP wajib diisi!');
        }

        if (empty($tglPensiun)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal Pensiun wajib diisi!');
        }

        $unitId   = 1;
        $unitNama = 'BKPSDM';

        if (is_admin_unit()) {
            $unitId   = (int) (session('unit_id') ?? 0);
            $unitNama = (string) (session('user_unit') ?? 'Unit Pegawai');
        }

        $insertData = [
            'PPJ_ID'      => $ppjId ?: 1,
            'NIP'         => $nip,
            'NAMA'        => 'Pegawai NIP ' . $nip,
            'PANGKAT'     => 'Penata Muda / III/a',
            'JABATAN'     => 'Pegawai ASN',
            'UNIT_ID'     => $unitId,
            'UNIT_NAMA'   => $unitNama,
            'NO_HP'       => '',
            'NO_HP_SI'    => '',
            'EMAIL'       => null,
            'TGL_PENSIUN' => $tglPensiun
        ];

        $newId = $ppDataModel->insert($insertData);

        if ($newId) {
            // Insert initial progress
            $progresModel->insert([
                'PPR_KODE'       => 1,
                'PP_ID'          => $newId,
                'PPR_TGL'        => date('Y-m-d H:i:s'),
                'PPR_KETERANGAN' => 'Pengusulan baru ditambahkan'
            ]);
            return redirect()->to('/data-persiapan')->with('success', 'Data pegawai berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
    }

    public function updateProgres($id)
    {
        if (!can_access_persiapan()) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki hak akses untuk memperbarui progres.');
        }

        $ppDataModel = new PpDataModel();
        $candidate   = $ppDataModel->find($id);

        if (!$candidate) {
            return redirect()->to('/data-persiapan')->with('error', 'Data pegawai tidak ditemukan.');
        }

        if (!can_access_unit($candidate['UNIT_ID'])) {
            return redirect()->to('/data-persiapan')->with('error', 'Anda tidak memiliki hak akses ke data unit ini.');
        }

        $progresModel = new PpProgresDataModel();

        $pprKode = $this->request->getPost('ppr_kode');
        $keterangan = $this->request->getPost('keterangan');

        if ($pprKode) {
            $progresModel->insert([
                'PPR_KODE'       => $pprKode,
                'PP_ID'          => $id,
                'PPR_TGL'        => date('Y-m-d H:i:s'),
                'PPR_KETERANGAN' => $keterangan ?: 'Progres diperbarui'
            ]);
            return redirect()->to('/data-persiapan')->with('success', 'Status progres pegawai berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Pilih status progres terlebih dahulu.');
    }
}
