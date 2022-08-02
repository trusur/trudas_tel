<?php

namespace App\Controllers\Login;

use App\Controllers\BaseController;
use App\Models\Muser;

class Login extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        // memanggil model
        $this->user = new Muser();
    }
    public function form()
    {
        if (session('session_id')) {
            return redirect()->to('/');
        }
        $data = [
            'title'         => 'Login Form',
            'validation'    => \Config\Services::validation(),
        ];
        echo view('Login/LoginForm', $data);
    }

    public function processLogin()
    {
        $checkUser = @$this->user->where(['email' => $this->request->getPost('email')])->first();

        if (!$this->validate([
            'email' => [
                'rules'     => 'required',
                'errors'     => [
                    'required'    => 'Email kosong!. silakan isi ..'
                ]
            ],
            'password' => [
                'rules'     => 'required',
                'errors'     => [
                    'required'    => 'Password kosong!. silakan isi ..'
                ]
            ]
        ])) {
            return redirect()->to('/login')->withInput();
        }
        if (empty($checkUser)) {
            session()->setFlashdata('noemail', 'Email not registered yet!..');
            return redirect()->to('/login');
        }

        if (password_verify($this->request->getPost('password'), $checkUser->password)) {
            $getSessionLogin =
                [
                    'loggedin'          => true,
                    'session_id'        => $checkUser->id,
                    'session_name'      => $checkUser->name,
                    'session_email'     => $checkUser->email,
                ];
            session()->set($getSessionLogin);
            session()->setFlashdata('message', 'Wellcome ' . session()->get('session_name') . ' ..');
            return redirect()->to('/');
        } else {
            session()->setFlashdata('notmatch', 'Wrong password!..');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
