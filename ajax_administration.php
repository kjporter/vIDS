<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ajax_administration.php
		Function: Handles AJAX requests in support of system administration functions
		Created: 8/24/21
		Edited: 
		
		Changes: 
	*/

// Fetch blacklist
if(strpos($_GET['function'], 'blacklist') !== false) {
	$blacklist = file("data/blacklist.dat");
}

if($_GET['function'] == 'blacklist_fetch') {
	echo json_encode($blacklist);
}
// Add to blacklist
if($_GET['function'] == 'blacklist_add') {
	if(!in_array($_GET['cid'],$blacklist)) {
		file_put_contents("data/blacklist.dat",$_GET['cid'] . "\n",FILE_APPEND);
		echo 'success';
	}
}
// Remove from blacklist
if($_GET['function'] == 'blacklist_remove') {
	if(in_array(intval($_GET['cid']),$blacklist)) {
		unset($blacklist[array_search($_GET['cid'],$blacklist)]);
		$blacklist = implode("\n", $blacklist);
		file_put_contents("data/blacklist.dat",$blacklist);
		echo 'success';
	}
}

// Fetch user information
if($_GET['function'] == 'blacklist_lookup') {
	$vatusa_api_url = "https://api.vatusa.net/v2/user/";
	$cu = curl_init();
	curl_setopt($cu,CURLOPT_URL,$vatusa_api_url . $_GET['cid']);
	curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
	curl_setopt($cu, CURLOPT_ENCODING, "gzip");
	curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
	$curl_raw = curl_exec($cu); // Execute CURL
	curl_close($cu);
	echo $curl_raw;
}

// Fetch logs
if($_GET['function'] == 'log_fetch') {
	$log = "";
	$file = file('data/' . $_GET['log_type'] . '.log');
	for ($i = max(0, count($file)-21); $i < count($file); $i++) {
		$log .= $file[$i];
	}
	echo $log;
}