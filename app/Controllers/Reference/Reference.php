<?php

namespace App\Controllers\Reference;

use App\Controllers\BaseController;
use App\Models\Mreference;
use App\Models\Msensor;

class Reference extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        // login check
        $this->loginCheck();

        // model
        $this->reference = new Mreference();
        $this->sensor = new Msensor();
    }

    public function index()
    {
        $data['title']          = 'Reference';
        $data['references']     = $this->reference
            ->select('reference_s.id as refID, reference_s.range_start as rangeSTART, reference_s.range_end as rangeEND, reference_s.formula as refFORMULA, sensors.sensor_code as sensorCODE')
            ->join('sensors', 'sensors.instrument_param_id = reference_s.instrument_param_id')
            ->orderBy('reference_s.id', 'ASC')
            ->findAll();

        echo view('Reference/Reference', $data);
    }

    // field references
    public function fields()
    {
        $data['instrument_param_id']        = $this->request->getPost('instrument_param_id');
        $data['range_start']                = $this->request->getPost('range_start');
        $data['range_end']                  = $this->request->getPost('range_end');
        $data['formula']                    = $this->request->getPost('formula');
        $data['xtimestamp']                 = date('Y-m-d H:i:s');

        return $data;
    }

    // add reference
    public function add()
    {
        $data['title']          = 'Add Reference';
        $data['sensors']        = $this->sensor->where(['is_deleted' => 0, 'is_multi_parameter' => 1])->findAll();
        $data['validation']     = \Config\Services::validation();

        echo view('Reference/Add', $data);
    }

    // save reference
    public function save()
    {
        if (!$this->validate(
            [
                'instrument_param_id' => [
                    'rules'    => 'required|max_length[2]',
                    'errors'     => [
                        'required'          => 'Instrument Parameter ID Empty! ..',
                        'max_length'        => 'Max Length 2 Character ..',
                    ]
                ],
                'range_start' => [
                    'rules'    => 'required|max_length[5]',
                    'errors'     => [
                        'required'          => 'Range Start Empty! ..',
                        'max_length'        => 'Max Length 5 Character ..',
                    ]
                ],
                'range_end' => [
                    'rules'    => 'required|max_length[5]',
                    'errors'     => [
                        'required'          => 'Range END Empty! ..',
                        'max_length'        => 'Max Length 5 Character ..',
                    ]
                ],
                'formula' => [
                    'rules'    => 'required',
                    'errors'     => [
                        'required'          => 'Formula Empty! ..',
                    ]
                ],
            ]
        )) {
            return redirect()->to('/reference/add')->withInput();
        }

        $fields = $this->fields();

        $this->reference->save($fields);
        session()->setFlashdata('message', 'Reference Created');
        return redirect()->to('/reference');
    }

    // edit reference
    public function edit($id)
    {
        $data['title']                  = 'Edit Reference';
        $data['sensors']                = $this->sensor->where(['is_deleted' => 0, 'is_multi_parameter' => 1])->findAll();
        $data['validation']             = \Config\Services::validation();
        $data['reference']              = $this->reference->where('id', $id)->first();

        echo view('Reference/Edit', $data);
    }

    // update reference
    public function update()
    {
        if (!$this->validate(
            [
                'instrument_param_id' => [
                    'rules'    => 'required|max_length[2]',
                    'errors'     => [
                        'required'          => 'Instrument Parameter ID Empty! ..',
                        'max_length'        => 'Max Length 2 Character ..',
                    ]
                ],
                'range_start' => [
                    'rules'    => 'required|max_length[5]',
                    'errors'     => [
                        'required'          => 'Range Start Empty! ..',
                        'max_length'        => 'Max Length 5 Character ..',
                    ]
                ],
                'range_end' => [
                    'rules'    => 'required|max_length[5]',
                    'errors'     => [
                        'required'          => 'Range END Empty! ..',
                        'max_length'        => 'Max Length 5 Character ..',
                    ]
                ],
                'formula' => [
                    'rules'    => 'required',
                    'errors'     => [
                        'required'          => 'Formula Empty! ..',
                    ]
                ],
            ]
        )) {
            return redirect()->to('/reference/edit/' . $this->request->getPost('id'))->withInput();
        }

        $fields = $this->fields();

        $this->reference->update($this->request->getPost('id'), $fields);

        session()->setFlashdata('message', 'Reference Edited');
        return redirect()->to('/reference');
    }

    // delete reference
    public function delete()
    {
        $this->reference->delete($this->request->getPost('id'));

        session()->setFlashdata('message', 'Reference Deleted');
        return redirect()->to('/reference');
    }
}
