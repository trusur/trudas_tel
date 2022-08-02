<?php

namespace App\Controllers\Unit;

use App\Controllers\BaseController;
use App\Models\Munit;

class Unit extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        // login check
        $this->loginCheck();

        // model
        $this->unit = new Munit();
    }

    public function index()
    {
        $data['title']  = 'Unit';
        $data['units']  = $this->unit->where('is_deleted', 0)->findAll();

        echo view('Unit/Unit', $data);
    }

    // field units
    public function fields()
    {
        $data['name']                = $this->request->getPost('name');

        return $data;
    }

    // add unit
    public function add()
    {
        $data['title']          = 'Add Unit';
        $data['validation']     = \Config\Services::validation();

        echo view('Unit/Add', $data);
    }

    // save unit
    public function save()
    {
        if (!$this->validate(
            [
                'name' => [
                    'rules'    => 'required|max_length[20]|is_unique[units.name]',
                    'errors'     => [
                        'required'        => 'Unit Name Empty! ..',
                        'max_length'    => 'Max Length 20 Character ..',
                        'is_unique'        => 'Unit Name Exist ..',
                    ]
                ],
            ]
        )) {
            return redirect()->to('/unit/add')->withInput();
        }

        $fields = $this->fields() + $this->SaveInfo() + $this->UpdateInfo();

        $this->unit->save($fields);
        session()->setFlashdata('message', 'Unit Created');
        return redirect()->to('/unit');
    }

    // edit unit
    public function edit($id)
    {
        $data['title']            = 'Edit Unit';
        $data['validation']     = \Config\Services::validation();
        $data['unit']            = $this->unit->where('id', $id)->first();

        echo view('Unit/Edit', $data);
    }

    // update unit
    public function update()
    {
        $checkUnitName = $this->unit->where(['id !=' => $this->request->getPost('id'), 'name' => $this->request->getPost('name'), 'is_deleted' => 0])->first();
        if (!$this->validate(
            [
                'name' => [
                    'rules'    => 'required|max_length[20]',
                    'errors'     => [
                        'required'        => 'Unit Name Empty! ..',
                        'max_length'    => 'Max Length 20 Character ..',
                    ]
                ],
            ]
        )) {
            return redirect()->to('/unit/edit/' . $this->request->getPost('id'))->withInput();
        } else if (!empty($checkUnitName)) {
            session()->setFlashdata('u_name', 'Unit Name Exist ..');
            return redirect()->to('/unit/edit/' . $this->request->getPost('id'))->withInput();
        }

        $fields = $this->fields() + $this->UpdateInfo();

        $this->unit->update($this->request->getPost('id'), $fields);

        session()->setFlashdata('message', 'Unit Edited');
        return redirect()->to('/unit');
    }

    // delete unit (change status is_deleted)
    public function delete()
    {
        $fields = ['is_deleted' => 1] + $this->DeleteInfo();
        $this->unit->update($this->request->getPost('id'), $fields);

        session()->setFlashdata('message', 'Unit Deleted');
        return redirect()->to('/unit');
    }
}
