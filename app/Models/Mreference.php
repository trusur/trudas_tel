<?php

namespace App\Models;

use CodeIgniter\Model;

class Mreference extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'reference_s';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useTimestamps = false;
    protected $protectFields = false;
}
