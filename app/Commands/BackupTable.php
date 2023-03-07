<?php

namespace App\Commands;

use App\Models\MdasLog;
use App\Models\MsensorValue;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class BackupTable extends BaseCommand
{
    protected $group = 'CodeIgniter';
    protected $name = 'command:backup_table';
    protected $description = 'Command for backup table';
    protected $usage = 'command:backup_table';
    protected $arguments = [];
    protected $options = [];
    protected $db;
    protected $DasLogM;
    protected $SensorValueM;
    public function __construct(){
        $this->db = \Config\Database::connect();
        $this->DasLogM = new MdasLog();
        $this->SensorValueM = new MsensorValue();
    }
    public function run(array $params)
    {
		while(true){
            $date = (string) date('d H:i');
            if($date == "01 00:01"){
                if($this->cloneTable("sensor_values","sensor_values_".date("Y_m")."_1")){
                    $this->SensorValueM->truncate();

                }
                if($this->cloneTable("das_logs","das_logs_".date("Y_m"))){
                    $this->DasLogM->truncate();
                }
                sleep(58);
            }
            if($date == "16 00:01"){
                if($this->cloneTable("sensor_values","sensor_values_".date("Y_m")."_2")){
                    $this->SensorValueM->truncate();
                    sleep(58);
                }
            }
            $hours = (string) date('H:i');
            if($hours == "00:03"){
                $this->runBackup();
            }
            sleep(1);
        }
    }

    public function cloneTable($tableName, $newName){
        try{
            $query = "CREATE table $newName AS (SELECT * FROM $tableName)";
            if($this->db->query($query)){
                echo "Success Clone $tableName Table".PHP_EOL;
                return true;
            }
        }catch(\Exception $e){
            echo date("Y-m-d H:i:s")." - ".$e->getMessage();
        }
        return false;
    }

    public function runBackup(){
		try{
			$filename = getcwd() . "\backups\\trudas_daily.sql";
            if(!is_dir(getcwd()."\\backups")){
                mkdir(getcwd()."\\backups",077, true);
            }
			$dbName = "trudas_db";
			$commands = [
				"start \"\" \"C:\Program Files\PostgreSQL\\14\bin\pg_dump.exe\"",
				"--dbname=postgresql://postgres:root@localhost:5432/$dbName",
				"-c",
				"-f",
				"\"$filename\"",
			];
			exec(join(" ",$commands));
			echo "Backup weekly successfully: ".$filename.PHP_EOL;
		}catch(\Exception $e){
			echo "Error while running backup daily : {$e->getMessage()}".PHP_EOL;
		}
	}
}
