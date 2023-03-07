<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Models\MdasLog;
use App\Models\Msensor;
use App\Models\MsensorValueRCA;
use App\Models\Munit;

class Data extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        // login check
        $this->loginCheck();

        // model
        $this->dasLog = new MdasLog();
        $this->rcaLog = new MsensorValueRCA();
        $this->unit = new Munit();
        $this->sensor = new Msensor();
    }

    public function index()
    {
        $data['title']      = 'Data';
        $data['sensor']     = $this->sensor->where('is_deleted', 0)->findAll();
        $data['tables']     = $this->getTables();
        return view('Data/Data', $data);
    }

    public function getTables(){
		$db      = \Config\Database::connect();
		$lists             = $db->listTables();
		foreach ($lists as $table) {
			if (substr($table, 0, strlen('das_logs')) == 'das_logs') {
				$tables[] = $table;
			}
		}
		return $tables;
	}
    // ajax das log
    public function ajaxDasLog()
    {
        // REQUEST Data
        $table_source                = @$this->request->getGet('table_source');
        $instrument_param_id                = @$this->request->getGet('instrument_param_id');
		$date_start                			= @$this->request->getGet('date_start');
		$date_start = @$date_start ? str_replace("T", " ", $date_start) : "";
		$date_end                			= @$this->request->getGet('date_end');
		$date_end = @$date_end ? str_replace("T", " ", $date_end) : "";
        // REQUEST Data Datatable
        $length = @$this->request->getGet('length') ? (int) $this->request->getGet('length') : -1;
        $start = @$this->request->getGet('start') ? (int) $this->request->getGet('start') : 0;
        $order_col = $this->request->getGet("order[0][column]");
        $order_dir = $this->request->getGet("order[0][dir]");
        $order = ["$table_source.id","$table_source.instrument_param_id","measured_at","data","voltage","sensors.unit_id","is_sent"];
        // Builder
		$db      = \Config\Database::connect();
		$builder = $db->table($table_source);
        // Where Clause
        $where                              = "1=1";
        if ($instrument_param_id != '') $where .= " AND $table_source.instrument_param_id = '{$instrument_param_id}'";
		if ($date_start != '') $where .= " AND $table_source.measured_at >= '{$date_start}'";
		if ($date_end != '') $where .= " AND $table_source.measured_at <= '{$date_end}'";
        // SQL Query
        $selects = [
            "sensors.sensor_code as parameter",
            "units.name as unit",
            "$table_source.id",
            "$table_source.measured_at",
            "$table_source.measured_at",
            "$table_source.data",
            "$table_source.voltage",
            "$table_source.is_sent"
        ];
        $recordTotal = $builder->countAllResults();
        $data = $builder
            ->select(join(", ",$selects))
            ->join("sensors","$table_source.instrument_param_id=sensors.instrument_param_id","left")
            ->join("units","sensors.unit_id=units.id","left")
            ->where($where)
            ->orderBy($order[$order_col],$order_dir)
            ->get(($length == -1 ? null : $length), $start)
            ->getResultObject();
        $recordFiltered = $builder->where($where)->countAllResults();

        $results = [
            'draw'                  => @$this->request->getGet('draw'),
            'recordsTotal'          => $recordTotal,
            'recordsFiltered'       => $recordFiltered,
            'data'                  => $data,
        ];
        // dd($results);
        return $this->response->setJSON($results);
    }

    public function rca()
    {
        $data['title']      = 'RCA Log';
        $data['sensor']     = $this->sensor->where(['is_deleted' => 0, 'extra_parameter >' => 0])->findAll();

        echo view('Data/RCA', $data);
    }

    // ajax rca log
    public function ajaxRCALog()
    {
		$date_start                			= @$this->request->getPost('date_start');
		$date_start = @$date_start ? str_replace("T", " ", $date_start) : "";
		$date_end                			= @$this->request->getPost('date_end');
		$date_end = @$date_end ? str_replace("T", " ", $date_end) : "";
        $instrument_param_id                = @$this->request->getPost('instrument_param_id');
        $where                              = "id > '0'";
        if ($instrument_param_id != '') $where .= "AND instrument_param_id = '{$instrument_param_id}'";
		if ($date_start != '') $where .= "AND xtimestamp >= '{$date_start}'";
		if ($date_end != '') $where .= "AND xtimestamp <= '{$date_end}'";
        $length = @$this->request->getPost('length') ? (int) $this->request->getPost('length') : -1;
        $start = @$this->request->getPost('start') ? (int) $this->request->getPost('start') : 0;
        $rcaLogs             = [];
        $numrow             = $this->rcaLog->where($where)->countAllResults();
        if ($length == -1) {
            $listRCALog         = $this->rcaLog->where($where)->orderBy('id ASC')->findAll();
        } else {
            $listRCALog         = $this->rcaLog->where($where)->orderBy('id ASC')->findAll(@$length, @$start);
        }
        $no = @$this->request->getPost('start');
        foreach ($listRCALog as $key => $rcaLog) {
            $no++;
            $param          = @$this->sensor->where('instrument_param_id', $rcaLog->instrument_param_id)->first()->sensor_code;
            $unit           = @$this->unit->where('id', $rcaLog->unit_id)->first()->name;
            $rcaLogs[$key]   = [
                $no,
                @$param,
                date('d/m/Y H:i:s', strtotime(@$rcaLog->xtimestamp)),
                @$rcaLog->data,
                @$rcaLog->data_correction,
                $unit,
            ];
        }

        $results = [
            'draw'                  => @$this->request->getPost('draw'),
            'recordsTotal'          => $numrow,
            'recordsFiltered'       => $numrow,
            'data'                  => $rcaLogs
        ];
        echo json_encode($results);
    }

    public function resetRCALog()
    {
        if ($this->rcaLog->truncate()) {
            session()->setFlashdata('message', 'Success Reset Data RCA');
            return redirect()->to('/rca-log');
        }
    }
}
