<?php

namespace App\Models;

use CodeIgniter\Model;

class MdasLog extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'das_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useTimestamps = false;
    protected $protectFields = false;
}
