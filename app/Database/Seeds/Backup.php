<?php

namespace App\Database\Seeds;

use App\Controllers\Data\Data;
use CodeIgniter\Database\Seeder;

class Backup extends Seeder
{
    public function run()
    {
        $this->db->table('backup')->truncate();
        $data =
            [
                [
                    'is_backup'         => 0,
                    'xtimestamp'        => date('Y-m-d H:i:s'),
                ],
            ];
        $this->db->table('backup')->insertBatch($data);
    }
}
