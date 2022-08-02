<?php

namespace App\Models;

use CodeIgniter\Model;

class MsensorValueRCA extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'sensor_value_rca';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useTimestamps = false;
    protected $protectFields = false;
}
