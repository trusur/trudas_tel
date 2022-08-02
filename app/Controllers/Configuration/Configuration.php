<?php

namespace App\Controllers\Configuration;

use App\Controllers\BaseController;
use App\Models\Mconfiguration;

class Configuration extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        // login check
        $this->loginCheck();

        // model
        $this->config = new Mconfiguration();
    }

    public function index()
    {
        $data['title']          = 'Configuration';
        $data['validation']     = \Config\Services::validation();
        $data['config']         = $this->config->where('is_deleted', 0)->first();

        echo view('Configuration/Configuration', $data);
    }

    // field configuration
    public function fields()
    {
        $data['name']                   = $this->request->getPost('name');
        $data['server_ip']              = $this->request->getPost('server_ip');
        $data['analyzer_ip']            = $this->request->getPost('analyzer_ip');
        $data['analyzer_port']          = $this->request->getPost('analyzer_port');
        $data['unit_id']                = $this->request->getPost('unit_id');
        $data['start_addr']             = $this->request->getPost('start_addr');
        $data['addr_num']               = $this->request->getPost('addr_num');
        $data['server_url']             = $this->request->getPost('server_url');
        $data['server_apikey']          = $this->request->getPost('server_apikey');
        $data['day_backup']             = $this->request->getPost('day_backup');

        return $data;
    }

    // update configuration
    public function update()
    {
        if (!$this->validate(
            [
                'name' => [
                    'rules'    => 'required|max_length[50]',
                    'errors'     => [
                        'required'          => 'Das Name Empty! ..',
                        'max_length'        => 'Max Length 50 Character ..',
                    ]
                ],
                'server_ip' => [
                    'rules'    => 'required|max_length[20]',
                    'errors'     => [
                        'required'          => 'Server IP Empty! ..',
                        'max_length'        => 'Max Length 20 Character ..',
                    ]
                ],
                'analyzer_ip' => [
                    'rules'    => 'required|max_length[20]',
                    'errors'     => [
                        'required'          => 'Analyzer IP Empty! ..',
                        'max_length'        => 'Max Length 20 Character ..',
                    ]
                ],
                'analyzer_port' => [
                    'rules'    => 'required|max_length[5]',
                    'errors'     => [
                        'required'          => 'Analyzer Port Empty! ..',
                        'max_length'        => 'Max Length 5 Character ..',
                    ]
                ],
                'unit_id' => [
                    'rules'    => 'required|max_length[5]',
                    'errors'     => [
                        'required'          => 'Unit ID Empty! ..',
                        'max_length'        => 'Max Length 5 Character ..',
                    ]
                ],
                'start_addr' => [
                    'rules'    => 'required|max_length[5]',
                    'errors'     => [
                        'required'          => 'Start Address Empty! ..',
                        'max_length'        => 'Max Length 5 Character ..',
                    ]
                ],
                'addr_num' => [
                    'rules'    => 'required|max_length[5]',
                    'errors'     => [
                        'required'          => 'Address Number Empty! ..',
                        'max_length'        => 'Max Length 5 Character ..',
                    ]
                ],
                'server_url' => [
                    'rules'    => 'required|max_length[200]',
                    'errors'     => [
                        'required'          => 'Server Url Empty! ..',
                        'max_length'        => 'Max Length 200 Character ..',
                    ]
                ],
                'server_apikey' => [
                    'rules'    => 'required|max_length[200]',
                    'errors'     => [
                        'required'          => 'Server ApiKey Empty! ..',
                        'max_length'        => 'Max Length 200 Character ..',
                    ]
                ],
                'day_backup' => [
                    'rules'    => 'required|max_length[2]',
                    'errors'     => [
                        'required'          => 'Day Backup Empty! ..',
                        'max_length'        => 'Max Length 2 Character ..',
                    ]
                ],
            ]
        )) {
            return redirect()->to('/configuration')->withInput();
        }

        $fields = $this->fields() + $this->UpdateInfo();

        $this->config->update($this->request->getPost('id'), $fields);

        session()->setFlashdata('message', 'Configuration Edited');
        return redirect()->to('/configuration');
    }
}
