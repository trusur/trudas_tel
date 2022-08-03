<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Configurations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                    => ['type' => 'BIGSERIAL', 'unsigned' => true, 'auto_increment' => true],
            'name'                  => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => false],
            'server_ip'             => ['type' => 'VARCHAR', 'constraint' => '30', 'null' => false],
            'analyzer_ip'           => ['type' => 'VARCHAR', 'constraint' => '30', 'null' => false],
            'analyzer_port'         => ['type' => 'INT', 'null' => false],
            'unit_id'               => ['type' => 'INT', 'null' => false],
            'start_addr'            => ['type' => 'INT', 'null' => false],
            'addr_num'              => ['type' => 'INT', 'null' => false],
            'server_url'            => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => false],
            'server_apikey'         => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => false],
            'day_backup'            => ['type' => 'VARCHAR', 'null' => false],
            'is_rca'                => ['type' => 'SMALLINT', 'default' => 0],
            'oxygen_reference'      => ['type' => 'INT', 'null' => true],
            'is_deleted'            => ['type' => 'SMALLINT', 'default' => 7],
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
        $this->forge->addKey('server_ip');
        $this->forge->addKey('analyzer_ip');
        $this->forge->addKey('analyzer_port');
        $this->forge->addKey('unit_id');
        $this->forge->addKey('start_addr');
        $this->forge->addKey('addr_num');
        $this->forge->addKey('server_url');
        $this->forge->addKey('server_apikey');
        $this->forge->addKey('day_backup');
        $this->forge->addKey('is_rca');
        $this->forge->addKey('oxygen_reference');
        $this->forge->createTable('configurations');
    }

    public function down()
    {
        $this->forge->dropTable('configurations');
    }
}
