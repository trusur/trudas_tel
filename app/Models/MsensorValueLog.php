<?php

namespace App\Models;

use CodeIgniter\Model;

class MsensorValueLog extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'sensor_value_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useTimestamps = false;
    protected $protectFields = false;
}
