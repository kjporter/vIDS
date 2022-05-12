<?php
include_once "data_management.php";	

$rwy_config = data_read("flow.dat","string");
$rwy_config = preg_split('#\r?\n#', $rwy_config, 0); // Split lines into an array so they can be processed/accessed like fgetcsv
$flow = array_key_exists(1,$rwy_config) ? $rwy_config[1] : ""; //preg_split('#\r?\n#', $rwy_config, 0)[1];
$arr = array_key_exists(2,$rwy_config) ? str_getcsv($rwy_config[2],",") : "";
$dep = array_key_exists(3,$rwy_config) ? str_getcsv($rwy_config[3],",") : "";

$dataset = array('traffic_flow'=>preg_replace( "/\r|\n/", "", $flow ),'apch_rwys'=>$arr,'dep_rwys'=>$dep);

echo json_encode($dataset);
?>