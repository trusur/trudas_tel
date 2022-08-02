<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Sensors extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'BIGSERIAL', 'unsigned' => true, 'auto_increment' => true],
            'labjack_ip'            => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => false],
            'ain'                   => ['type' => 'INT', 'null' => false],
            'instrument_param_id'   => ['type' => 'INT', 'null' => false],
            'sensor_code'           => ['type' => 'VARCHAR', 'constraint' => '30', 'null' => false],
            'unit_id'               => ['type' => 'INT', 'null' => false],
            'formula'               => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => false],
            'is_multi_parameter'    => ['type' => 'INT', 'default' => 0, 'null' => false],
            'extra_parameter'       => ['type' => 'INT', 'default' => 0, 'null' => false],
            'is_show'               => ['type' => 'SMALLINT', 'default' => 0],
            'is_deleted'            => ['type' => 'SMALLINT', 'default' => 0],
            'created_at'            => ['type' => 'DATETIME'],
            'created_by'            => ['type' => 'VARCHAR', 'constraint' => '50'],
            'created_ip'            => ['type' => 'VARCHAR', 'constraint' => '16'],
            'updated_at'            => ['type' => 'DATETIME', 'null' => true],
            'updated_by'            => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'updated_ip'            => ['type' => 'VARCHAR', 'constraint' => '16', 'null' => true],
            'deleted_at'            => ['type' => 'DATETIME', 'null' => true],
            'deleted_by'            => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'deleted_ip'            => ['type' => 'VARCHAR', 'constraint' => '16', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('labjack_ip');
        $this->forge->addKey('ain');
        $this->forge->addKey('instrument_param_id');
        $this->forge->addKey('sensor_code');
        $this->forge->addKey('unit_id');
        $this->forge->addKey('formula');
        $this->forge->addKey('is_multi_parameter');
        $this->forge->addKey('extra_parameter');
        $this->forge->addKey('is_show');
        $this->forge->createTable('sensors');
    }

    public function down()
    {
        $this->forge->dropTable('sensors');
    }
}
