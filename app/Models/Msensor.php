<?php

namespace App\Models;

use CodeIgniter\Model;

class Msensor extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'sensors';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useTimestamps = false;
    protected $protectFields = false;
}
