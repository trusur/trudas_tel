<?php

namespace App\Models;

use CodeIgniter\Model;

class Mbackup extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'backup';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useTimestamps = false;
    protected $protectFields = false;
}
