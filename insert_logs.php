<?php
date_default_timezone_set('Asia/Makassar');
echo date('Y-m-d H:i:s');

// pqsql connection
$db = pg_connect("host=localhost port=5432 dbname=trudas_db user=postgres password=root");

if ($db) {
    $pg_sensors = "SELECT * FROM sensors";
    $pg_sql = pg_query($db, $pg_sensors);
    while ($row = pg_fetch_assoc($pg_sql)) {
        //insert value
        $pg_insert = "INSERT INTO sensor_value_logs (instrument_param_id, data, voltage, updated_at, updated_by, updated_ip, xtimestamp) values ('" . $row['id'] . "','0','0','" . date('Y-m-d H:i:s') . "','system','10.5.1.46','" . date('Y-m-d H:i:s') . "')";
        pg_query($db, $pg_insert);
    }
    exit();
} else {
    echo 'disconnect from sql server';
}
