<?php

namespace App\Controllers\Home;

use App\Controllers\BaseController;
use App\Models\Mconfiguration;
use App\Models\Msensor;
use App\Models\MsensorValueLog;
use App\Models\MsensorValueRCALog;
use Exception;

class Home extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        // model
        $this->sensor = new Msensor();
        $this->configuration = new Mconfiguration();
        $this->sensor_value_log = new MsensorValueLog();
        $this->sensor_value_rca_log = new MsensorValueRCALog();
    }
    public function index()
    {
        $data['title']      = 'Data Acquisition System ' . $this->configuration->first()->name;
        $data['sensorvalues']    = $this->sensor_value_log
            ->select('*,sensor_value_logs.id as sid, sensor_value_logs.instrument_param_id as svinstrument_param_id, sensors.sensor_code as scode, units.name as uname')
            ->join('sensors', 'sensors.instrument_param_id = sensor_value_logs.instrument_param_id', 'left')
            ->join('units', 'units.id = sensors.unit_id', 'left')
            ->orderBy('sensor_value_logs.instrument_param_id', 'ASC')
            ->findAll();
        $data['sensorvalue_rca']    = $this->sensor_value_rca_log
            ->select('*,sensor_value_rca_logs.id as sid, sensor_value_rca_logs.instrument_param_id as svinstrument_param_id, sensors.sensor_code as scode, units.name as uname')
            ->join('sensors', 'sensors.instrument_param_id = sensor_value_rca_logs.instrument_param_id', 'left')
            ->join('units', 'units.id = sensors.unit_id', 'left')
            ->where('sensors.extra_parameter >', 0)
            ->orderBy('sensor_value_rca_logs.instrument_param_id', 'ASC')
            ->findAll();
        $data['mode_rca']   = $this->configuration->first()->is_rca;

        echo view('Home/Home', $data);
    }

    public function getSensorValues()
    {
        try {
            $sensorValues = $this->sensor_value_log
                ->select('sensor_value_logs.*, sensors.sensor_code as s_code')
                ->join('sensors', 'sensors.instrument_param_id = sensor_value_logs.instrument_param_id')
                ->findAll();
            echo json_encode(['success' => true, 'data' => $sensorValues], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_PRETTY_PRINT);
        }
    }

    public function getSensorValueRCA()
    {
        try {
            $sensorValues = $this->sensor_value_rca_log
                ->select('sensor_value_rca_logs.*, sensors.sensor_code as s_code')
                ->join('sensors', 'sensors.instrument_param_id = sensor_value_rca_logs.instrument_param_id')
                ->findAll();
            echo json_encode(['success' => true, 'data' => $sensorValues], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_PRETTY_PRINT);
        }
    }

    // start / end mode RCA
    public function modeRca()
    {
        $is_rca = $this->request->getPost('is_rca') == 1 ? 0 : 1;
        $message = $this->request->getPost('is_rca') == 1 ? 'Mode RCA Telah Selesai' : 'Mode RCA Telah Aktif';
        $this->configuration->set(['is_rca' => $is_rca])->update();
        sleep(10);
        session()->setFlashdata('message', $message);
        return redirect()->to('/');
    }
}
