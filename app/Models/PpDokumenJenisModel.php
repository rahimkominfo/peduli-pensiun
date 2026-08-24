<?php

namespace App\Models;

use CodeIgniter\Model;

class PpDokumenJenisModel extends Model
{
    protected $table            = 'PP_DOKUMEN_JENIS';
    protected $primaryKey       = 'DOKJES_ID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['PPJ_ID', 'DOKUMEN_KODE'];
}
