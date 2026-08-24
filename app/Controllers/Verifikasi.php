<?php

namespace App\Controllers;

use App\Models\PpDataModel;
use App\Models\PpProgresDataModel;
use App\Models\PpProgresRefModel;
use App\Models\PpDokumenModel;

class Verifikasi extends BaseController
{
    public function index()
    {
        // Default to candidate #1 if none specified
        return $this->detail(1);
    }

    public function detail($id = 1)
    {
        $ppDataModel   = new PpDataModel();
        $progresModel  = new PpProgresDataModel();
        $progresRef    = new PpProgresRefModel();
        $dokumenModel  = new PpDokumenModel();

        $candidate = $ppDataModel->getDetail($id);

        if (!$candidate) {
            $redirectUrl = can_access_persiapan() ? '/data-persiapan' : '/dashboard';
            return redirect()->to($redirectUrl)->with('error', 'Data pegawai tidak ditemukan.');
        }

        $timeline  = $progresModel->getByPpId($id);
        $dokumenList = $dokumenModel->getDokumenByPpId($id, $candidate['PPJ_ID']);
        $refProgres = $progresRef->findAll();

        $data = [
            'title'       => 'Detail Dokumen & Verifikasi - Peduli Pensiun',
            'activeMenu'  => 'verifikasi',
            'candidate'   => $candidate,
            'timeline'    => $timeline,
            'dokumenList' => $dokumenList,
            'refProgres'  => $refProgres,
        ];

        return view('verifikasi/index', $data);
    }

    public function updateContact($id)
    {
        $ppDataModel = new PpDataModel();

        $noHp   = $this->request->getPost('no_hp');
        $noHpSi = $this->request->getPost('no_hp_si');
        $email  = $this->request->getPost('email');

        $updateData = [
            'NO_HP'    => $noHp,
            'NO_HP_SI' => $noHpSi,
            'EMAIL'    => $email,
        ];

        $ppDataModel->update($id, $updateData);

        return redirect()->to('/data-verifikasi/' . $id)->with('success', 'Kontak berhasil diperbarui!');
    }

    public function addProgres($id)
    {
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
            return redirect()->to('/data-verifikasi/' . $id)->with('success', 'Status progres berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Pilih status progres terlebih dahulu.');
    }

    public function uploadDokumen($id)
    {
        $dokumenModel = new PpDokumenModel();
        $ppDataModel  = new PpDataModel();
        $dokjesId     = $this->request->getPost('dokjes_id');

        $candidate    = $ppDataModel->find($id);
        $unitId       = $candidate['UNIT_ID'] ?? 0;
        $nip          = $candidate['NIP'] ?? '';

        $file = $this->request->getFile('file_dokumen');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $uploadPath = ROOTPATH . 'public/uploads/dokumen/' . $unitId;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $newName);

            // Check if record exists
            $existing = $dokumenModel->where('PP_ID', $id)->where('DOKJES_ID', $dokjesId)->first();
            if ($existing) {
                $dokumenModel->update($existing['DOKUMEN_ID'], [
                    'NIP'           => $nip,
                    'FILE_DOKUMEN'  => $newName,
                    'APPROVE'       => 0, // Reset status to pending after upload
                    'KET_PENOLAKAN' => ''
                ]);
            } else {
                $dokumenModel->insert([
                    'PP_ID'         => $id,
                    'NIP'           => $nip,
                    'DOKJES_ID'     => $dokjesId,
                    'FILE_DOKUMEN'  => $newName,
                    'APPROVE'       => 0,
                    'KET_PENOLAKAN' => ''
                ]);
            }

            return redirect()->to('/data-verifikasi/' . $id)->with('success', 'Dokumen berhasil diunggah!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file.');
    }

    public function approveDokumen($id)
    {
        if (!can_approve_dokumen()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk memverifikasi dokumen.');
        }

        $dokumenModel = new PpDokumenModel();

        $dokumenId    = $this->request->getPost('dokumen_id');
        $approveStatus = (int)$this->request->getPost('approve_status'); // 1 = Disetujui, 2 = Ditolak
        $ketPenolakan = $this->request->getPost('ket_penolakan') ?? '';

        if ($dokumenId) {
            $dokumenModel->update($dokumenId, [
                'APPROVE'       => $approveStatus,
                'KET_PENOLAKAN' => ($approveStatus === 1) ? '' : $ketPenolakan
            ]);
            return redirect()->to('/data-verifikasi/' . $id)->with('success', 'Status verifikasi dokumen berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Gagal memverifikasi dokumen.');
    }

    public function delete($id)
    {
        if (!can_delete_pensiun()) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki hak akses untuk menghapus data pensiun.');
        }

        $ppDataModel  = new PpDataModel();
        $progresModel = new PpProgresDataModel();
        $dokumenModel = new PpDokumenModel();

        $candidate = $ppDataModel->find($id);

        if (!$candidate) {
            $redirectUrl = can_access_persiapan() ? '/data-persiapan' : '/dashboard';
            return redirect()->to($redirectUrl)->with('error', 'Data pensiun tidak ditemukan.');
        }

        // Delete child records first to ensure integrity
        $dokumenModel->where('PP_ID', $id)->delete();
        $progresModel->where('PP_ID', $id)->delete();
        $ppDataModel->delete($id);

        $redirectUrl = can_access_persiapan() ? '/data-persiapan' : '/dashboard';
        return redirect()->to($redirectUrl)->with('success', 'Data pensiun ' . $candidate['NAMA'] . ' (NIP: ' . $candidate['NIP'] . ') berhasil dihapus.');
    }
}
