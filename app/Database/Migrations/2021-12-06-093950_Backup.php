<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Backup extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'BIGSERIAL', 'unsigned' => true, 'auto_increment' => true],
            'is_backup'             => ['type' => 'SMALLINT', 'default' => 0],
            'updated_at'            => ['type' => 'DATETIME', 'null' => true],
            'updated_by'            => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'updated_ip'            => ['type' => 'VARCHAR', 'constraint' => '16', 'null' => true],
            'xtimestamp'            => ['type' => 'timestamp'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('is_backup');
        $this->forge->createTable('backup');
    }

    public function down()
    {
        $this->forge->dropTable('backup');
    }
}
