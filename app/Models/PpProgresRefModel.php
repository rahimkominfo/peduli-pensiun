<?php

namespace App\Models;

use CodeIgniter\Model;

class PpProgresRefModel extends Model
{
    protected $table            = 'PP_PROGRES_REF';
    protected $primaryKey       = 'PPR_KODE';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['PPR_URAIAN'];
}
