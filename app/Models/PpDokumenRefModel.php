<?php

namespace App\Models;

use CodeIgniter\Model;

class PpDokumenRefModel extends Model
{
    protected $table            = 'PP_DOKUMEN_REF';
    protected $primaryKey       = 'DOKUMEN_KODE';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['NM_DOKUMEN', 'NM_KONSEP', 'KONSEP_DOKUMEN'];
}
