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

        echo view('Data/Data', $data);
    }

    // ajax das log
    public function ajaxDasLog()
    {
        $instrument_param_id                = @$this->request->getPost('instrument_param_id');
		$date_start                			= @$this->request->getPost('date_start');
		$date_start = @$date_start ? str_replace("T", " ", $date_start) : "";
		$date_end                			= @$this->request->getPost('date_end');
		$date_end = @$date_end ? str_replace("T", " ", $date_end) : "";
        $where                              = "id > '0'";
        if ($instrument_param_id != '') $where .= " AND instrument_param_id = '{$instrument_param_id}'";
		if ($date_start != '') $where .= " AND measured_at >= '{$date_start}'";
		if ($date_end != '') $where .= " AND measured_at <= '{$date_end}'";
        $length = @$this->request->getPost('length') ? (int) $this->request->getPost('length') : -1;
        $start = @$this->request->getPost('start') ? (int) $this->request->getPost('start') : 0;
        $dasLog             = [];
        $numrow             = $this->dasLog->where($where)->countAllResults();
        if ($length == -1) {
            $listDasLog         = $this->dasLog->where($where)->orderBy('id DESC')->findAll();
        } else {
            $listDasLog         = $this->dasLog->where($where)->orderBy('id DESC')->findAll(@$length, @$start);
        }
        $no = @$this->request->getPost('start');
        foreach ($listDasLog as $key => $lLog) {
            $no++;
            $param          = @$this->sensor->where('instrument_param_id', $lLog->instrument_param_id)->first()->sensor_code;
            $unit           = @$this->unit->where('id', $lLog->unit_id)->first()->name;
            $dasLog[$key]   = [
                $no,
                @$param,
                @$lLog->measured_at,
                @$lLog->data,
                @$lLog->voltage,
                $unit,
                @$lLog->is_sent == 0 ? '<span class="badge bg-danger badge-sm">Not Yet</span>' : '<span class="badge bg-success badge-sm">Sent</span>',
            ];
        }

        $results = [
            'draw'                  => @$this->request->getPost('draw'),
            'recordsTotal'          => $numrow,
            'recordsFiltered'       => $numrow,
            'data'                  => $dasLog,
			'where' => $where
        ];
        echo json_encode($results);
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
