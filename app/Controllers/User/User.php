<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\Muser;

class User extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        // login check
        $this->loginCheck();

        // model
        $this->user = new Muser();
    }

    public function index()
    {
        $data['title']          = 'User';
        $data['validation']     = \Config\Services::validation();
        $data['user']           = $this->user->where('is_deleted', 0)->first();

        echo view('User/User', $data);
    }

    // field user
    public function fields()
    {
        $data['name']               = $this->request->getPost('name');
        $data['email']              = $this->request->getPost('email');
        $data['password']           = $this->request->getPost('password');
        $data['address']            = $this->request->getPost('address');

        return $data;
    }

    // update user
    public function update()
    {
        if (!$this->validate(
            [
                'name' => [
                    'rules'    => 'required|max_length[30]',
                    'errors'     => [
                        'required'          => 'Name Empty! ..',
                        'max_length'        => 'Max Length 30 Character ..',
                    ]
                ],
                'email' => [
                    'rules'    => 'required|max_length[30]',
                    'errors'     => [
                        'required'          => 'Email Empty! ..',
                        'max_length'        => 'Max Length 30 Character ..',
                    ]
                ],
                'address' => [
                    'rules'    => 'required',
                    'errors'     => [
                        'required'          => 'Address Empty! ..',
                    ]
                ],
            ]
        )) {
            return redirect()->to('/user')->withInput();
        }

        if (!empty($this->request->getPost('password'))) {
            $password = ['password' => password_hash($this->request->getPost('password'), PASSWORD_ARGON2ID)];
            $fields = $password + $this->fields() + $this->UpdateInfo();
        } else {
            $fields = $this->fields() + $this->UpdateInfo();
        }

        $this->user->update($this->request->getPost('id'), $fields);

        session()->setFlashdata('message', 'User Edited');
        return redirect()->to('/user');
    }
}
