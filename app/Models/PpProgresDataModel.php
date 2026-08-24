<?php

namespace App\Models;

use CodeIgniter\Model;

class PpProgresDataModel extends Model
{
    protected $table            = 'PP_PROGRES_DATA';
    protected $primaryKey       = 'PPR_ID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['PPR_KODE', 'PP_ID', 'PPR_TGL', 'PPR_KETERANGAN'];

    /**
     * Get timeline history for specific PP_ID
     */
    public function getByPpId($ppId)
    {
        return $this->db->table('PP_PROGRES_DATA pd')
            ->select('pd.*, pr.PPR_URAIAN')
            ->join('PP_PROGRES_REF pr', 'pr.PPR_KODE = pd.PPR_KODE', 'left')
            ->where('pd.PP_ID', $ppId)
            ->orderBy('pd.PPR_ID', 'ASC')
            ->get()->getResultArray();
    }
}
