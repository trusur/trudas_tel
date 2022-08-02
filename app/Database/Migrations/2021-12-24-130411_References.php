<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Reference_s extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'BIGSERIAL', 'unsigned' => true, 'auto_increment' => true],
            'instrument_param_id'   => ['type' => 'INT', 'default' => 0, 'null' => false],
            'range_start'           => ['type' => 'float', 'default' => 0, 'null' => false],
            'range_end'             => ['type' => 'float', 'default' => 0, 'null' => false],
            'formula'               => ['type' => 'TEXT', 'null' => false],
            'xtimestamp'            => ['type' => 'timestamp'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('instrument_param_id');
        $this->forge->addKey('range_start');
        $this->forge->addKey('range_end');
        $this->forge->addKey('formula');
        $this->forge->createTable('reference_s');
    }

    public function down()
    {
        $this->forge->dropTable('reference_s');
    }
}
