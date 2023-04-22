<?php
include_once "data_management.php";

if (!isset($_GET['req'])) { // Return flow data to caller
    $rwy_config = data_read("flow.dat", "string");
    $rwy_config = preg_split('#\r?\n#', $rwy_config, 0); // Split lines into an array so they can be processed/accessed like fgetcsv
    $flow = array_key_exists(1, $rwy_config) ? $rwy_config[1] : ""; //preg_split('#\r?\n#', $rwy_config, 0)[1];
    $arr = array_key_exists(2, $rwy_config) ? str_getcsv($rwy_config[2], ",") : "";
    $dep = array_key_exists(3, $rwy_config) ? str_getcsv($rwy_config[3], ",") : "";

    $dataset = array('traffic_flow' => preg_replace("/\r|\n/", "", $flow), 'apch_rwys' => $arr, 'dep_rwys' => $dep);

    echo json_encode($dataset);
}
elseif($_GET['req'] == 'set_tmu_notes') {
/*
    $cu = curl_init();
	curl_setopt($cu,CURLOPT_URL,'http://127.0.0.1/ajax_handler.php?type=tmu&cid=' . $_GET['setby'] . '&payload=' . $_GET['mesg']);
	curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
	curl_setopt($cu,CURLOPT_ENCODING, "gzip");
	curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
	curl_exec($cu); // Execute CURL
	curl_close($cu);
*/
    data_save('tmu.dat','-*-' . time() . '-*--' . $_GET['mesg']);
    echo 'Success';
}
elseif($_GET['req'] == 'fetch_tmu_notes') {
    $reply = array('mesg'=>null, 'timestamp'=>null);
	$tmu_data = data_read("tmu.dat","string");
	if($tmu_data != '') {
	    $reply['timestamp'] = substr($tmu_data,3,strpos($tmu_data,"-*--")-3);
	    $reply['mesg'] = substr($tmu_data,strpos($tmu_data,"-*--")+4);
    }
    echo json_encode($reply);
}
