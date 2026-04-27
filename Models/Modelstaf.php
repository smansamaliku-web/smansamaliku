<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelstaf extends Model
{
    protected $table      = 'staf';
    protected $primaryKey = 'staf_id';
    protected $allowedFields = ['nip', 'nama', 'tmp_lahir', 'tgl_lahir', 'alamat', 'pendidikan', 'jabatan', 'foto'];
}
