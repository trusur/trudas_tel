<?php

namespace App\Controllers\Sensor;

use App\Controllers\BaseController;
use App\Models\Msensor;
use App\Models\Munit;

class Sensor extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        // login check
        $this->loginCheck();

        // model
        $this->sensor = new Msensor();
        $this->unit = new Munit();
    }

    public function index()
    {
        $data['title']      = 'Sensor';
        $data['sensors']    = $this->sensor
            ->select('*,sensors.id as sid, units.name as uname')
            ->join('units', 'units.id = sensors.unit_id')
            ->where('sensors.is_deleted', 0)
            ->orderBy('sensors.id', 'ASC')
            ->findAll();

        echo view('Sensor/Sensor', $data);
    }

    // field sensors
    public function fields()
    {
        $data['labjack_ip']             = $this->request->getPost('labjack_ip');
        $data['ain']                    = $this->request->getPost('ain');
        $data['instrument_param_id']    = $this->request->getPost('instrument_param_id');
        $data['sensor_code']            = $this->request->getPost('sensor_code');
        $data['unit_id']                = $this->request->getPost('unit_id');
        $data['formula']                = $this->request->getPost('formula');
        $data['is_multi_parameter']     = $this->request->getPost('is_multi_parameter');
        $data['is_show']                = $this->request->getPost('is_show');
        $data['extra_parameter']        = $this->request->getPost('extra_parameter');
        $data['o2_correction']          = $this->request->getPost('o2_correction');

        return $data;
    }

    // add sensor
    public function add()
    {
        $data['title']          = 'Add Sensor';
        $data['units']          = $this->unit->where('is_deleted', 0)->findAll();
        $data['validation']     = \Config\Services::validation();

        echo view('Sensor/Add', $data);
    }

    // save sensor
    public function save()
    {
        if (!$this->validate(
            [
                'labjack_ip' => [
                    'rules'    => 'required|max_length[20]',
                    'errors'     => [
                        'required'          => 'Labjack IP Empty! ..',
                        'max_length'        => 'Max Length 20 Character ..',
                    ]
                ],
                'ain' => [
                    'rules'    => 'required|max_length[1]',
                    'errors'     => [
                        'required'          => 'AIN Empty! ..',
                        'max_length'        => 'Max Length 1 Character ..',
                    ]
                ],
                'instrument_param_id' => [
                    'rules'    => 'required|max_length[2]|is_unique[sensors.instrument_param_id]',
                    'errors'     => [
                        'required'          => 'Instrument Parameter ID Empty! ..',
                        'max_length'        => 'Max Length 2 Character ..',
                        'is_unique'         => 'Instrument Parameter ID Exist ..',
                    ]
                ],
                'sensor_code' => [
                    'rules'    => 'required|max_length[20]|is_unique[sensors.sensor_code]',
                    'errors'     => [
                        'required'          => 'Sensor Code Empty! ..',
                        'max_length'        => 'Max Length 20 Character ..',
                        'is_unique'         => 'Sensor Code Exist ..',
                    ]
                ],
                'unit_id' => [
                    'rules'    => 'required',
                    'errors'     => [
                        'required'          => 'Unit Name Empty! ..',
                    ]
                ],
                'formula' => [
                    'rules'    => 'required|max_length[200]',
                    'errors'     => [
                        'required'          => 'Formula Empty! ..',
                        'max_length'        => 'Max Length 200 Character ..',
                    ]
                ],
                'is_multi_parameter' => [
                    'rules'    => 'required',
                    'errors'     => [
                        'required'          => 'Is Multi Parameter Empty! ..',
                    ]
                ],
                'is_show' => [
                    'rules'    => 'required',
                    'errors'     => [
                        'required'          => 'Is Show Empty! ..',
                    ]
                ],
            ]
        )) {
            return redirect()->to('/sensor/add')->withInput();
        }

        $fields = $this->fields() + $this->SaveInfo() + $this->UpdateInfo();

        $this->sensor->save($fields);
        session()->setFlashdata('message', 'Sensor Created');
        return redirect()->to('/sensor');
    }

    // edit sensor
    public function edit($id)
    {
        $data['title']              = 'Edit Sensor';
        $data['units']              = $this->unit->where('is_deleted', 0)->findAll();
        $data['validation']         = \Config\Services::validation();
        $data['sensor']             = $this->sensor->where('id', $id)->first();

        echo view('Sensor/Edit', $data);
    }

    // update sensor
    public function update()
    {
        $instrumentParamID = $this->sensor->where(['id !=' => $this->request->getPost('id'), 'instrument_param_id' => $this->request->getPost('instrument_param_id'), 'is_deleted' => 0])->first();
        $checSensorCode = $this->sensor->where(['id !=' => $this->request->getPost('id'), 'sensor_code' => $this->request->getPost('sensor_code'), 'is_deleted' => 0])->first();
        if (!$this->validate(
            [
                'labjack_ip' => [
                    'rules'    => 'required|max_length[20]',
                    'errors'     => [
                        'required'          => 'Labjack IP Empty! ..',
                        'max_length'        => 'Max Length 20 Character ..',
                    ]
                ],
                'ain' => [
                    'rules'    => 'required|max_length[1]',
                    'errors'     => [
                        'required'          => 'AIN Empty! ..',
                        'max_length'        => 'Max Length 1 Character ..',
                    ]
                ],
                'instrument_param_id' => [
                    'rules'    => 'required|max_length[2]',
                    'errors'     => [
                        'required'          => 'Instrument Parameter ID Empty! ..',
                        'max_length'        => 'Max Length 2 Character ..',
                    ]
                ],
                'sensor_code' => [
                    'rules'    => 'required|max_length[20]',
                    'errors'     => [
                        'required'          => 'Sensor Code Empty! ..',
                        'max_length'        => 'Max Length 20 Character ..',
                    ]
                ],
                'unit_id' => [
                    'rules'    => 'required',
                    'errors'     => [
                        'required'          => 'Unit Name Empty! ..',
                    ]
                ],
                'formula' => [
                    'rules'    => 'required|max_length[200]',
                    'errors'     => [
                        'required'          => 'Formula Empty! ..',
                        'max_length'        => 'Max Length 200 Character ..',
                    ]
                ],
                'is_multi_parameter' => [
                    'rules'    => 'required',
                    'errors'     => [
                        'required'          => 'Is Multi Parameter Empty! ..',
                    ]
                ],
                'is_show' => [
                    'rules'    => 'required',
                    'errors'     => [
                        'required'          => 'Is Show Empty! ..',
                    ]
                ],
            ]
        )) {
            return redirect()->to('/sensor/edit/' . $this->request->getPost('id'))->withInput();
        } else if (!empty($instrumentParamID)) {
            session()->setFlashdata('e_instrument_param_id', 'Instrument Parameter ID Exist ..');
            return redirect()->to('/sensor/edit/' . $this->request->getPost('id'))->withInput();
        } else if (!empty($checSensorCode)) {
            session()->setFlashdata('e_sensor_code', 'Sensor Code Exist ..');
            return redirect()->to('/sensor/edit/' . $this->request->getPost('id'))->withInput();
        }

        $fields = $this->fields() + $this->UpdateInfo();

        $this->sensor->update($this->request->getPost('id'), $fields);

        session()->setFlashdata('message', 'Sensor Edited');
        return redirect()->to('/sensor');
    }

    // delete sensor (change status is_deleted)
    public function delete()
    {
        $fields = ['is_deleted' => 1] + $this->DeleteInfo();
        $this->sensor->update($this->request->getPost('id'), $fields);

        session()->setFlashdata('message', 'Sensor Deleted');
        return redirect()->to('/sensor');
    }
}
