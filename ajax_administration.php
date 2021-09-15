<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ajax_administration.php
		Function: Handles AJAX requests in support of system administration functions
		Created: 8/24/21
		Edited: 
		
		Changes: 
	*/
	
include_once "data_management.php";

// Fetch blacklist
if(strpos($_GET['function'], 'accesslist') !== false) {
	//$blacklist = file("data/blacklist.dat");
	$accesslist = data_read($_GET['list'] . 'list.dat','array');
}

if($_GET['function'] == 'accesslist_fetch') {
	echo json_encode($accesslist);
}
// Add to blacklist
if($_GET['function'] == 'accesslist_add') {
	if(!in_array($_GET['cid'],$accesslist)) {
		//file_put_contents("data/blacklist.dat",$_GET['cid'] . "\n",FILE_APPEND);
		data_save($_GET['list'] . 'list.dat',$_GET['cid'] . "\n",false);
		echo 'success';
	}
}
// Remove from blacklist
if($_GET['function'] == 'accesslist_remove') {
	if(in_array(intval($_GET['cid']),$accesslist)) {
		unset($accesslist[array_search($_GET['cid'],$accesslist)]);
		$accesslist = implode("\n", $accesslist);
		//file_put_contents("data/blacklist.dat",$blacklist);
		data_save($_GET['list'] . 'list.dat',$blacklist,true);
		echo 'success';
	}
}

// Fetch user information
if($_GET['function'] == 'accesslist_lookup') {
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
	//$file = file('data/' . $_GET['log_type'] . '.log');
	$file = data_read($_GET['log_type'] . '.log','array');
	for ($i = max(0, count($file)-21); $i < count($file); $i++) {
		$log .= $file[$i];
	}
	echo $log;
}