<?php

namespace App\Models;

use CodeIgniter\Model;

class Mconfiguration extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'configurations';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useTimestamps = false;
    protected $protectFields = false;
}
