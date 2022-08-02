<?php
	$db = new mysqli("localhost", "root", "root", "egateway");
	if($_SERVER["HTTP_API_KEY"] == "VHJ1c3VyVW5nZ3VsVGVrbnVzYV9wVA=="){
		$data = json_decode(file_get_contents('php://input'),true);
		if($data["stack_id"] > 0){
			$sql = "SELECT id FROM instruments WHERE stack_id='".$data["stack_id"]."'";
			$row = $db->query($sql)->fetch_array(MYSQLI_ASSOC);
			if($row["id"] > 0){//instrument_id ditemukan
				$instrument_id = $row["id"];
				$row = $db->query("SELECT id FROM labjacks WHERE instrument_id='".$instrument_id."'")->fetch_array(MYSQLI_ASSOC);
				if($row["id"] > 0){//labjack_id ditemukan
					$labjack_id = $row["id"];
					foreach($data as $key => $value){
						if(substr($key,0,6) == "sensor"){
							$sensor_id = str_replace("sensor","",$key);
							$sql = "SELECT id FROM labjack_values WHERE labjack_id = '".$labjack_id."' AND ain_id = '".$sensor_id."'";
							$row = $db->query($sql)->fetch_array(MYSQLI_ASSOC);
							if(@$row["id"] > 0) $sql = "UPDATE labjack_values SET data='".$value."' WHERE labjack_id = '".$labjack_id."' AND ain_id = '".$sensor_id."'";
							else $sql = "INSERT INTO labjack_values (labjack_id,ain_id,`data`) VALUES ('".$labjack_id."','".$sensor_id."','".$value."')";
							$db->query($sql);
						}
					}
					$return = ["status" => 200,"response" => ["message" => "Success", "data" => $data]];
				}else{
					$return = ["status" => 401,"response" => ["message" => "sensor id undefined, please contact egateway server administrator"]];
				}
			}else{
				$return = ["status" => 401,"response" => ["message" => "Stack id unknown"]];
			}
		}else{
			$return = ["status" => 401,"response" => ["message" => "Stack Id Required"]];
		}
	}else{
		$return = ["status" => 401,"response" => ["message" => "Invalid api key"]];
		
	}
	echo json_encode($return);	
?>