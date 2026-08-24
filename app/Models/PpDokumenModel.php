<?php

namespace App\Models;

use CodeIgniter\Model;

class PpDokumenModel extends Model
{
    protected $table            = 'PP_DOKUMEN';
    protected $primaryKey       = 'DOKUMEN_ID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['PP_ID', 'DOKJES_ID', 'FILE_DOKUMEN', 'APPROVE', 'KET_PENOLAKAN'];

    /**
     * Get list of required documents for pension type, merged with uploaded files status
     */
    public function getDokumenByPpId($ppId, $ppjId)
    {
        $builder = $this->db->table('PP_DOKUMEN_JENIS dj')
            ->select('dj.DOKJES_ID, dj.PPJ_ID, dj.DOKUMEN_KODE, dr.NM_DOKUMEN, dr.NM_KONSEP, dr.KONSEP_DOKUMEN, 
                      d.DOKUMEN_ID, d.FILE_DOKUMEN, d.APPROVE, d.KET_PENOLAKAN')
            ->join('PP_DOKUMEN_REF dr', 'dr.DOKUMEN_KODE = dj.DOKUMEN_KODE', 'left')
            ->join('PP_DOKUMEN d', 'd.DOKJES_ID = dj.DOKJES_ID AND d.PP_ID = ' . (int)$ppId, 'left')
            ->where('dj.PPJ_ID', $ppjId);

        return $builder->get()->getResultArray();
    }
}
