<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SensorValueLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'BIGSERIAL', 'unsigned' => true, 'auto_increment' => true],
            'instrument_param_id'   => ['type' => 'INT', 'default' => 0, 'null' => false],
            'data'                  => ['type' => 'double precision', 'default' => 0, 'null' => false],
            'voltage'               => ['type' => 'double precision', 'default' => 0, 'null' => false],
            'updated_at'            => ['type' => 'DATETIME', 'null' => true],
            'updated_by'            => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'updated_ip'            => ['type' => 'VARCHAR', 'constraint' => '16', 'null' => true],
            'xtimestamp'            => ['type' => 'timestamp'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('instrument_param_id');
        $this->forge->addKey('data');
        $this->forge->addKey('voltage');
        $this->forge->createTable('sensor_value_logs');
    }

    public function down()
    {
        $this->forge->dropTable('sensor_value_logs');
    }
}
