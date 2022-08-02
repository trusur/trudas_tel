<?php

namespace App\Commands;

use App\Models\Mbackup;
use App\Models\MdasLog;
use App\Models\Msensor;
use App\Models\MsensorValue;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Average extends BaseCommand
{

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->sensorValue = new MsensorValue();
        $this->dasLog = new MdasLog();
        $this->sensor = new Msensor();
        $this->backup = new Mbackup();
    }

    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'CodeIgniter';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'command:averaging';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function get_dasLog_range($minute)
    {
        $id_end = @$this->sensorValue->orderBy("id DESC")->first()->id;
        $lasttime = date("Y-m-d H:i", mktime(date("H"), date("i") - $minute));
        $mm = date("i") * 1;
        $current_time = date("Y-m-d H:i") . ":00";
        $lastPutData = @$this->dasLog->orderBy("time_group DESC")->first()->time_group;
        if ($mm % $minute == 0 && $lastPutData != $current_time) {
            $id_start = @$this->sensorValue->where("xtimestamp >= '" . $lasttime . ":00'")->where("is_averaged", 0)->orderBy("id")->first()->id;
            if ($id_start > 0) {
                $measurement_logs = $this->sensorValue->where("id BETWEEN '" . $id_start . "' AND '" . $id_end . "'")->where("is_averaged", 0)->findAll();
                $return["id_start"] = $id_start;
                $return["id_end"] = $id_end;
                $return["waktu"] = $current_time;
                $return["data"] = $measurement_logs;
                return $return;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function dasLogAveraging()
    {
        $minute = 1;
        $dasLogs = $this->get_dasLog_range($minute);
        if ($dasLogs != 0) {
            foreach ($dasLogs["data"] as $dasLog) {
                @$instrument_param_id[$dasLog->instrument_param_id] = $dasLog->instrument_param_id;
                @$data[$dasLog->instrument_param_id] += $dasLog->data;
                @$voltage[$dasLog->instrument_param_id] += $dasLog->data;
                @$numdata[$dasLog->instrument_param_id]++;
            }
            foreach ($this->sensor->findAll() as $sensor) {
                // $correcting = true;
                if (@$numdata[$sensor->instrument_param_id] > 0) {
                    $dasLog = [
                        "instrument_param_id" => $instrument_param_id[$sensor->instrument_param_id],
                        "time_group" => $dasLogs["waktu"],
                        "measured_at" => $dasLogs["waktu"],
                        "data" => round($data[$sensor->instrument_param_id] / $numdata[$sensor->instrument_param_id], 3),
                        "voltage" => $voltage[$sensor->instrument_param_id] / $numdata[$sensor->instrument_param_id],
                        "unit_id" => $sensor->unit_id,
                        "updated_at" => date("Y-m-d H:i:s"),
                        "updated_by" => "system",
                        "updated_ip" => "127.0.0.1",
                        "xtimestamp" => date("Y-m-d H:i:s"),
                    ];
                    $this->dasLog->save($dasLog);
                }
            }
            $this->sensorValue->set(["is_averaged" => 1])->where("id BETWEEN '" . $dasLogs["id_start"] . "' AND '" . $dasLogs["id_end"] . "'")->update();
        }
    }

    public function run(array $params)
    {
        $is_looping = 1;

        while ($is_looping) {
            if ($this->backup->first()->is_backup == 0) {
                $this->dasLogAveraging();
                echo date("Y-m-d H:i:s");
            }
            sleep(1);
        }
    }
}
