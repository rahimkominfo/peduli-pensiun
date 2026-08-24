<?php

namespace App\Models;

use CodeIgniter\Model;

class PpJenisModel extends Model
{
    protected $table            = 'PP_JENIS';
    protected $primaryKey       = 'PPJ_ID';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['PPJ_URAIAN'];
}
