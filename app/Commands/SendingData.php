<?php

namespace App\Commands;

use App\Models\Mconfiguration;
use App\Models\MdasLog;
use App\Models\MsensorValue;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SendingData extends BaseCommand
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->sensor_value = new MsensorValue();
        $this->config = new Mconfiguration();
        $this->dasLog = new MdasLog();
    }

    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'CodeIgniter';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'command:sendingdata';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        while (true) {
            $start = microtime(true);
            $config = $this->config->first();
            $dasLogs = $this->dasLog->where('is_sent', 0)->orderBy('id', 'ASC')->findAll();
            echo "Preparing data to sending...";
            echo PHP_EOL;
            foreach ($dasLogs as $dLog) {
                $data =
                    [
                        'stack_id'              => $config->unit_id,
                        'instrument_param_id'   => $dLog->instrument_param_id,
                        'data'                  => (float)$dLog->data,
                        'voltage'               => (float)$dLog->voltage,
                        'unit_id'               => $dLog->unit_id,
                        'date_data'             => $dLog->measured_at,
                    ];
                // $req["sensor{$dLog->sensor_id}"] = (float) $dLog->data . '|' . (float) $dLog->voltage;

                $json =  json_encode($data, true);
                //print_r($data);
				//exit();

                try {
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $config->server_url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $json,
                        CURLOPT_HTTPHEADER => array(
                            "Api-Key: {$config->server_apikey}",
                            "Content-Type: text/plain"
                        ),
                    ));
                    $response = curl_exec($curl);
                    //print_r($response);
                    //exit();
                    curl_close($curl);
                    $responseArray = json_decode($response, 1);
                    // dd($responseArray);
                    if (@$responseArray['status'] == 200) {
                        $this->dasLog->update($dLog->id, ['is_sent' => 1]);
                        echo "Data Sent Successfully!";
                        echo PHP_EOL;
						//exit();
                        // dd($data);
                    } else {
                        echo "Error sending data!";
                        echo @$responseArray['response']['message'];
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
            echo PHP_EOL;
            $timeExecuted = microtime(true) -  $start;
            echo "Execute in " . substr($timeExecuted, 0, 4) . " milliseconds" . PHP_EOL;
            sleep(3);
        }
    }
}
