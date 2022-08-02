<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DasLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'BIGSERIAL', 'unsigned' => true, 'auto_increment' => true],
            'instrument_param_id'   => ['type' => 'INT', 'default' => 0, 'null' => false],
            'time_group'            => ['type' => 'DATETIME'],
            'measured_at'           => ['type' => 'DATETIME'],
            'data'                  => ['type' => 'double precision', 'default' => 0, 'null' => false],
            'voltage'               => ['type' => 'double precision', 'default' => 0, 'null' => false],
            'unit_id'               => ['type' => 'INT', 'default' => 0, 'null' => false],
            'is_sent'               => ['type' => 'SMALLINT', 'default' => 0],
            'updated_at'            => ['type' => 'DATETIME', 'null' => true],
            'updated_by'            => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'updated_ip'            => ['type' => 'VARCHAR', 'constraint' => '16', 'null' => true],
            'xtimestamp'            => ['type' => 'timestamp'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('instrument_param_id');
        $this->forge->addKey('time_group');
        $this->forge->addKey('measured_at');
        $this->forge->addKey('data');
        $this->forge->addKey('voltage');
        $this->forge->addKey('unit_id');
        $this->forge->addKey('is_sent');
        $this->forge->createTable('das_logs');
    }

    public function down()
    {
        $this->forge->dropTable('das_logs');
    }
}
