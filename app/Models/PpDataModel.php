<?php

namespace App\Models;

use CodeIgniter\Model;

class PpDataModel extends Model
{
    protected $table            = 'PP_DATA';
    protected $primaryKey       = 'PP_ID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'PPJ_ID',
        'NIP',
        'NAMA',
        'PANGKAT',
        'JABATAN',
        'UNIT_ID',
        'UNIT_NAMA',
        'NO_HP',
        'NO_HP_SI',
        'EMAIL',
        'TGL_PENSIUN'
    ];

    /**
     * Get candidate data along with pension type and latest progress status, filtered by params
     */
    public function getWithProgres($filters = [])
    {
        $builder = $this->db->table('PP_DATA d')
            ->select('d.*, j.PPJ_URAIAN, pr.PPR_URAIAN, pd.PPR_KODE, pd.PPR_TGL, pd.PPR_KETERANGAN')
            ->join('PP_JENIS j', 'j.PPJ_ID = d.PPJ_ID', 'left')
            ->join('(SELECT p1.* FROM PP_PROGRES_DATA p1 
                    INNER JOIN (SELECT PP_ID, MAX(PPR_ID) as max_id FROM PP_PROGRES_DATA GROUP BY PP_ID) p2 
                    ON p1.PPR_ID = p2.max_id) pd', 'pd.PP_ID = d.PP_ID', 'left')
            ->join('PP_PROGRES_REF pr', 'pr.PPR_KODE = pd.PPR_KODE', 'left');

        if (is_string($filters)) {
            $unitNama = $filters;
            if (!empty($unitNama) && $unitNama !== 'semua_unit') {
                $builder->like('d.UNIT_NAMA', $unitNama);
            }
        } elseif (is_array($filters)) {
            if (!empty($filters['unit']) && $filters['unit'] !== 'semua_unit') {
                $builder->like('d.UNIT_NAMA', $filters['unit']);
            }
            if (!empty($filters['unit_id'])) {
                $builder->where('d.UNIT_ID', (int)$filters['unit_id']);
            }
            if (!empty($filters['progres'])) {
                $builder->where('pd.PPR_KODE', $filters['progres']);
            }
            if (!empty($filters['tahun'])) {
                $builder->where('YEAR(d.TGL_PENSIUN)', $filters['tahun']);
            }
            if (!empty($filters['keyword'])) {
                $keyword = trim($filters['keyword']);
                $builder->groupStart()
                        ->like('d.NAMA', $keyword)
                        ->orLike('d.NIP', $keyword)
                        ->groupEnd();
            }
        }

        return $builder->orderBy('d.PP_ID', 'DESC')->get()->getResultArray();
    }

    /**
     * Get distinct retirement years from PP_DATA
     */
    public function getAvailableYears()
    {
        $query = $this->db->query("SELECT DISTINCT YEAR(TGL_PENSIUN) as tahun FROM PP_DATA WHERE TGL_PENSIUN IS NOT NULL ORDER BY tahun DESC")->getResultArray();
        $years = array_column($query, 'tahun');
        return array_values(array_filter($years));
    }

    /**
     * Get single detail by PP_ID
     */
    public function getDetail($id)
    {
        return $this->db->table('PP_DATA d')
            ->select('d.*, j.PPJ_URAIAN')
            ->join('PP_JENIS j', 'j.PPJ_ID = d.PPJ_ID', 'left')
            ->where('d.PP_ID', $id)
            ->get()->getRowArray();
    }

    /**
     * Summary stats for dashboard
     */
    public function getSummaryStats($unitId = null)
    {
        $builder = $this->builder();
        if ($unitId !== null && (int)$unitId > 0) {
            $builder->where('UNIT_ID', (int)$unitId);
        }
        $totalPns = $builder->countAllResults();

        $whereClause = ($unitId !== null && (int)$unitId > 0) ? "WHERE d.UNIT_ID = " . (int)$unitId : "";

        // Get highest progress code achieved for each active employee in PP_DATA
        $query = $this->db->query("
            SELECT 
                latest.max_kode,
                COUNT(*) as total
            FROM (
                SELECT 
                    d.PP_ID,
                    COALESCE(MAX(p.PPR_KODE), 1) as max_kode
                FROM PP_DATA d
                LEFT JOIN PP_PROGRES_DATA p ON p.PP_ID = d.PP_ID
                {$whereClause}
                GROUP BY d.PP_ID
            ) latest
            GROUP BY latest.max_kode
        ")->getResultArray();

        $statsByKode = [];
        foreach ($query as $row) {
            $statsByKode[(int)$row['max_kode']] = (int)$row['total'];
        }

        return [
            'pengusulan_opd' => $statsByKode[1] ?? 0,
            'verifikasi'     => $statsByKode[2] ?? 0,
            'pertek_bkn'     => $statsByKode[3] ?? 0,
            'penerbitan_sk'  => $statsByKode[4] ?? 0,
            'selesai'        => $statsByKode[5] ?? 0,
            'total_aktif'    => $totalPns,

            // Backward compatibility keys
            'total'          => $totalPns,
            'pengusulan'     => $statsByKode[1] ?? 0,
            'terbit_sk'      => $statsByKode[3] ?? 0,
            'cetak_sk'       => $statsByKode[4] ?? 0,
            'terima_sk'      => $statsByKode[5] ?? 0,
        ];
    }
}

