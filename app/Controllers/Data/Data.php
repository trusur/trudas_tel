<?php

namespace App\Controllers\Data;

use App\Controllers\BaseController;
use App\Models\MdasLog;
use App\Models\Msensor;
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
        $where                              = "id > '0'";
        if ($instrument_param_id != '') $where .= "AND instrument_param_id = '{$instrument_param_id}'";
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
            'data'                  => $dasLog
        ];
        echo json_encode($results);
    }
}
