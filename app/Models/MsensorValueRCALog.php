<?php

namespace App\Models;

use CodeIgniter\Model;

class MsensorValueRCALog extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'sensor_value_rca_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useTimestamps = false;
    protected $protectFields = false;
}
