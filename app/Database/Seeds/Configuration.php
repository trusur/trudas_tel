<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Configuration extends Seeder
{
    public function run()
    {
        $data =
            [
                [
                    'name'              => 'PLTU PT. SMI',
                    'server_ip'         => '192.168.1.16',
                    'analyzer_ip'       => '192.168.186.126',
                    'analyzer_port'     => '502',
                    'unit_id'           => '1',
                    'start_addr'        => '0',
                    'addr_num'          => '20',
                    'server_url'        => 'http://192.168.1.16/egateway_api/sensor_values_post.php',
                    'server_apikey'     => 'VHJ1c3VyVW5nZ3VsVGVrbnVzYV9wVA==',
                    'day_backup'        => '1',
                    'is_rca'            => '0',
                    'oxygen_reference'  => '7',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => 'admin@trusur.com',
                    'created_ip'        => '127.0.0.1',
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => 'admin@trusur.com',
                    'updated_ip'        => '127.0.0.1'
                ],
            ];
        $this->db->table('configurations')->insertBatch($data);
    }
}
