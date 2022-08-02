<?php

namespace App\Models;

use CodeIgniter\Model;

class Muser extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useTimestamps = false;
    protected $protectFields = false;
}
