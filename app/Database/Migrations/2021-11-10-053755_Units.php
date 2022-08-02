<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Units extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'BIGSERIAL', 'unsigned' => true, 'auto_increment' => true],
            'name'                  => ['type' => 'VARCHAR', 'constraint' => '30', 'null' => false],
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
        $this->forge->createTable('units');
    }

    public function down()
    {
        $this->forge->dropTable('units');
    }
}
