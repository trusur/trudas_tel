<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Units extends Seeder
{
    public function run()
    {
        $this->db->table('units')->truncate();
        $data =
            [
                [
                    'name'              => 'ppm',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
                [
                    'name'              => 'μg/m3',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
                [
                    'name'              => 'mg/m3',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
                [
                    'name'              => 'l/min',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
                [
                    'name'              => 'm3/min',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
                [
                    'name'              => 'm3/h',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
                [
                    'name'              => 'Nm3/h',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
                [
                    'name'              => 'minutes',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
                [
                    'name'              => 'ton',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
                [
                    'name'              => '%',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
                [
                    'name'              => 'm/sec',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
                [
                    'name'              => 'C',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
                [
                    'name'              => 'kg/h',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
            ];
        $this->db->table('units')->insertBatch($data);
    }
}
