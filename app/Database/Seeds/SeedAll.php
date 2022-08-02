<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedAll extends Seeder
{
    public function run()
    {
        $this->call('Configuration');
        $this->call('Units');
        $this->call('Backup');
        $this->call('User');
    }
}
