<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RemoveSensors extends Seeder
{
    public function run()
    {
        $this->db->table('sensor_value_logs')->truncate();
        $this->db->table('sensor_value_rca')->truncate();
        $this->db->table('sensor_value_rca_logs')->truncate();
        $this->db->table('sensor_values')->truncate();
        $this->db->table('sensors')->truncate();
    }
}
