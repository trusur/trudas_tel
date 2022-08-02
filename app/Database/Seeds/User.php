<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class User extends Seeder
{
    public function run()
    {
        $data =
            [
                [
                    'name'              => 'Admin Cems',
                    'email'             => 'admin@trusur.com',
                    'password'          => '$argon2id$v=19$m=65536,t=4,p=1$SS5pVG80Ylk0aUpQcmxtaA$b92rGFGFco4HfmztCP6SoL1fEGOkDyRcz81jy4oDCO0',
                    'address'           => 'multifuel boiler',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
            ];
        $this->db->table('users')->insertBatch($data);
    }
}
