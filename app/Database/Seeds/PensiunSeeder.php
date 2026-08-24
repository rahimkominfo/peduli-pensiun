<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PensiunSeeder extends Seeder
{
    public function run()
    {
        // 1. PP_JENIS
        $this->db->table('PP_JENIS')->emptyTable();
        $this->db->table('PP_JENIS')->insertBatch([
            ['PPJ_ID' => 1, 'PPJ_URAIAN' => 'BUP (Batas Usia Pensiun)'],
            ['PPJ_ID' => 2, 'PPJ_URAIAN' => 'Pensiun Janda/Duda/Yatim'],
            ['PPJ_ID' => 3, 'PPJ_URAIAN' => 'Atas Permintaan Sendiri (APS)'],
            ['PPJ_ID' => 4, 'PPJ_URAIAN' => 'Uzur Kesehatan/Sakit'],
        ]);

        // 2. PP_PROGRES_REF
        $this->db->table('PP_PROGRES_REF')->emptyTable();
        $this->db->table('PP_PROGRES_REF')->insertBatch([
            ['PPR_KODE' => 1, 'PPR_URAIAN' => 'Pengusulan'],
            ['PPR_KODE' => 2, 'PPR_URAIAN' => 'Verifikasi'],
            ['PPR_KODE' => 3, 'PPR_URAIAN' => 'Terbit SK'],
            ['PPR_KODE' => 4, 'PPR_URAIAN' => 'Cetak SK'],
            ['PPR_KODE' => 5, 'PPR_URAIAN' => 'Terima SK'],
        ]);

        // 3. PP_DOKUMEN_REF
        $this->db->table('PP_DOKUMEN_REF')->emptyTable();
        $this->db->table('PP_DOKUMEN_REF')->insertBatch([
            ['DOKUMEN_KODE' => 1, 'NM_DOKUMEN' => 'SK CPNS', 'NM_KONSEP' => 'Konsep SK CPNS', 'KONSEP_DOKUMEN' => 'Format SK CPNS'],
            ['DOKUMEN_KODE' => 2, 'NM_DOKUMEN' => 'Surat Nikah / Akta Nikah', 'NM_KONSEP' => 'Konsep Surat Nikah', 'KONSEP_DOKUMEN' => 'Format Surat Nikah'],
            ['DOKUMEN_KODE' => 3, 'NM_DOKUMEN' => 'Pas Foto Terbaru (Latar Merah)', 'NM_KONSEP' => 'Konsep Pas Foto', 'KONSEP_DOKUMEN' => 'Format Pas Foto'],
            ['DOKUMEN_KODE' => 4, 'NM_DOKUMEN' => 'SK PNS', 'NM_KONSEP' => 'Konsep SK PNS', 'KONSEP_DOKUMEN' => 'Format SK PNS'],
            ['DOKUMEN_KODE' => 5, 'NM_DOKUMEN' => 'Kartu Keluarga', 'NM_KONSEP' => 'Konsep KK', 'KONSEP_DOKUMEN' => 'Format KK'],
            ['DOKUMEN_KODE' => 6, 'NM_DOKUMEN' => 'SK Pangkat Terakhir', 'NM_KONSEP' => 'Konsep SK Pangkat', 'KONSEP_DOKUMEN' => 'Format SK Pangkat'],
        ]);

        // 4. PP_DOKUMEN_JENIS
        $this->db->table('PP_DOKUMEN_JENIS')->emptyTable();
        $dokjens = [];
        $id = 1;
        for ($j = 1; $j <= 4; $j++) {
            for ($d = 1; $d <= 6; $d++) {
                $dokjens[] = [
                    'DOKJES_ID' => $id++,
                    'PPJ_ID' => $j,
                    'DOKUMEN_KODE' => $d
                ];
            }
        }
        $this->db->table('PP_DOKUMEN_JENIS')->insertBatch($dokjens);

        // 5. PP_DATA
        $this->db->table('PP_DATA')->emptyTable();
        $this->db->table('PP_DATA')->insertBatch([
            [
                'PP_ID' => 1,
                'PPJ_ID' => 1,
                'NIP' => '199408132019031008',
                'NAMA' => 'AKBAR HASRUN, S.Kom',
                'PANGKAT' => 'Penata Muda / III/a',
                'JABATAN' => 'Pranata Komputer',
                'UNIT_ID' => 1,
                'UNIT_NAMA' => 'BKPSDM',
                'NO_HP' => '081234567890',
                'NO_HP_SI' => '081298765432',
                'EMAIL' => 'akbar.hasrun@bkpsdma.go.id',
                'TGL_PENSIUN' => '2026-08-13'
            ],
            [
                'PP_ID' => 2,
                'PPJ_ID' => 1,
                'NIP' => '196503121990031005',
                'NAMA' => 'Drs. Budi Santoso, M.Si.',
                'PANGKAT' => 'Pembina Utama Muda / IV/c',
                'JABATAN' => 'Kepala Bidang Pembinaan SMP',
                'UNIT_ID' => 2,
                'UNIT_NAMA' => 'Dinas Pendidikan',
                'NO_HP' => '085211223344',
                'NO_HP_SI' => '085299887766',
                'EMAIL' => 'budi.santoso@sinjaikab.go.id',
                'TGL_PENSIUN' => '2025-03-01'
            ],
            [
                'PP_ID' => 3,
                'PPJ_ID' => 1,
                'NIP' => '196608171992032008',
                'NAMA' => 'Dra. Siti Aminah, M.Pd.',
                'PANGKAT' => 'Pembina / IV/a',
                'JABATAN' => 'Guru Madya - SMPN 1',
                'UNIT_ID' => 2,
                'UNIT_NAMA' => 'Dinas Pendidikan',
                'NO_HP' => '081344556677',
                'NO_HP_SI' => '081388990011',
                'EMAIL' => 'siti.aminah@sinjaikab.go.id',
                'TGL_PENSIUN' => '2026-09-01'
            ],
            [
                'PP_ID' => 4,
                'PPJ_ID' => 3,
                'NIP' => '196411201989021002',
                'NAMA' => 'Ir. Wahyu Hidayat',
                'PANGKAT' => 'Pembina Tingkat I / IV/b',
                'JABATAN' => 'Analis Tata Ruang',
                'UNIT_ID' => 3,
                'UNIT_NAMA' => 'Dinas Pekerjaan Umum',
                'NO_HP' => '082155667788',
                'NO_HP_SI' => '082199001122',
                'EMAIL' => 'wahyu.hidayat@sinjaikab.go.id',
                'TGL_PENSIUN' => '2024-12-01'
            ],
            [
                'PP_ID' => 5,
                'PPJ_ID' => 4,
                'NIP' => '197505102005012003',
                'NAMA' => 'dr. H. Ratna Juwita, Sp.A.',
                'PANGKAT' => 'Pembina / IV/a',
                'JABATAN' => 'Dokter Spesialis Anak',
                'UNIT_ID' => 4,
                'UNIT_NAMA' => 'Dinas Kesehatan',
                'NO_HP' => '081277889900',
                'NO_HP_SI' => '081233445566',
                'EMAIL' => 'ratna.juwita@sinjaikab.go.id',
                'TGL_PENSIUN' => '2025-11-15'
            ]
        ]);

        // 6. PP_PROGRES_DATA
        $this->db->table('PP_PROGRES_DATA')->emptyTable();
        $this->db->table('PP_PROGRES_DATA')->insertBatch([
            // Pegawai 1
            ['PPR_KODE' => 1, 'PP_ID' => 1, 'PPR_TGL' => '2023-10-12 09:30:00', 'PPR_KETERANGAN' => 'Pengusulan diajukan'],
            ['PPR_KODE' => 2, 'PP_ID' => 1, 'PPR_TGL' => '2023-10-15 14:00:00', 'PPR_KETERANGAN' => 'Sedang diproses oleh tim verifikator.'],
            // Pegawai 2
            ['PPR_KODE' => 1, 'PP_ID' => 2, 'PPR_TGL' => '2024-01-10 08:00:00', 'PPR_KETERANGAN' => 'Pengusulan diajukan'],
            ['PPR_KODE' => 2, 'PP_ID' => 2, 'PPR_TGL' => '2024-01-20 10:30:00', 'PPR_KETERANGAN' => 'Verifikasi selesai'],
            // Pegawai 3
            ['PPR_KODE' => 1, 'PP_ID' => 3, 'PPR_TGL' => '2024-02-01 11:15:00', 'PPR_KETERANGAN' => 'Pengajuan berkas'],
            // Pegawai 4
            ['PPR_KODE' => 1, 'PP_ID' => 4, 'PPR_TGL' => '2023-11-05 09:00:00', 'PPR_KETERANGAN' => 'Revisi Dokumen'],
            // Pegawai 5
            ['PPR_KODE' => 1, 'PP_ID' => 5, 'PPR_TGL' => '2024-03-01 10:00:00', 'PPR_KETERANGAN' => 'Pengusulan diajukan'],
            ['PPR_KODE' => 2, 'PP_ID' => 5, 'PPR_TGL' => '2024-03-10 13:00:00', 'PPR_KETERANGAN' => 'Verifikasi selesai'],
            ['PPR_KODE' => 3, 'PP_ID' => 5, 'PPR_TGL' => '2024-04-05 09:00:00', 'PPR_KETERANGAN' => 'Terbit SK Pensiun'],
            ['PPR_KODE' => 4, 'PP_ID' => 5, 'PPR_TGL' => '2024-04-12 11:00:00', 'PPR_KETERANGAN' => 'SK Pensiun Dicetak'],
            ['PPR_KODE' => 5, 'PP_ID' => 5, 'PPR_TGL' => '2024-04-20 15:30:00', 'PPR_KETERANGAN' => 'SK Diterima Pegawai']
        ]);

        // 7. PP_DOKUMEN
        $this->db->table('PP_DOKUMEN')->emptyTable();
        $this->db->table('PP_DOKUMEN')->insertBatch([
            [
                'PP_ID' => 1,
                'DOKJES_ID' => 1, // SK CPNS
                'FILE_DOKUMEN' => 'sk_cpns_akbar.pdf',
                'APPROVE' => 1,
                'KET_PENOLAKAN' => ''
            ],
            [
                'PP_ID' => 1,
                'DOKJES_ID' => 2, // Surat Nikah
                'FILE_DOKUMEN' => 'surat_nikah_akbar.pdf',
                'APPROVE' => 2,
                'KET_PENOLAKAN' => 'Dokumen tidak terbaca dengan jelas.'
            ]
        ]);
    }
}
