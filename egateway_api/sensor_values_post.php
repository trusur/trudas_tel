<?php
// pqsql connection
$db = pg_connect("host=localhost port=5432 dbname=egateway user=postgres password=root");

if ($_SERVER["HTTP_API_KEY"] == "VHJ1c3VyVW5nZ3VsVGVrbnVzYV9wVA==") {
	$get_data_sqlsrv = json_decode(file_get_contents('php://input'), true);
	//echo json_encode($get_data_sqlsrv['instrument_param_id']);
	//exit();
	if (!empty($get_data_sqlsrv)) {
		$pg_select = "SELECT * FROM parameters WHERE id = '" . $get_data_sqlsrv['instrument_param_id'] . "'";
		$result = pg_query($db, $pg_select);
		$data = pg_fetch_row($result);
		// print_r(pg_fetch_row($result));
		// exit();
		//insert value
		if ($data[0] == $get_data_sqlsrv['instrument_param_id']) {
			@$unit = @$data[8];
		}
		$pg_insert = "INSERT INTO measurement_logs (instrument_id, parameter_id, value, voltage, unit_id, is_averaged, is_das_log, xtimestamp) VALUES ('" . $get_data_sqlsrv['instrument_param_id'] . "','" . $get_data_sqlsrv['instrument_param_id'] . "','" . $get_data_sqlsrv['data'] . "','0','" . @$unit . "','0','0','" . date('Y-m-d H:i:s') . "')";
		if (pg_query($db, $pg_insert)) {
			$return = ["status" => 200, "response" => ["message" => "Success", "data" => $get_data_sqlsrv]];
		} else {
			$return = ["status" => 401, "response" => ["message" => "Failed to Save!"]];
		}
	} else {
		$return = ["status" => 401, "response" => ["message" => "Data is Empty!"]];
	}
} else {
	$return = ["status" => 401, "response" => ["message" => "Invalid api key"]];
}
echo json_encode($return);
