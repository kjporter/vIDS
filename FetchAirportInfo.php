<?php
// Drop-in replacement for legacy VATEUD API that provided airport arrival/departure info
// Kyle J. Porter, 2022

error_reporting(0); //This file always results in an AJAX reply. Turn errors off.

define("DEBUG_REFRESH",false); // Enables noisy mode for debugging

include_once "vars/config.php";
include_once "common.php";
include_once "ajax_rvr.php";
include_once "data_management.php";	

// Configuration
$refreshInterval = 15; // How many seconds between requests to pull data from VATSIM data service, 15 sec is the max refresh rate 

// Init variables
$server_reply = array();
$afld_icao = isset($_REQUEST['id']) ? strtoupper($_REQUEST['id']): 0;
$sortie_type = isset($_REQUEST['type']) ? $_REQUEST['type']: 0;

// Harvest timestamp from cached JSON to determine if update is needed (prevents flooding VATSIM data service with unnecessary requests)
$cached_stats = data_read("vatsim.json","string");
if(isJSON($cached_stats)) {
	$cached_stats = json_decode($cached_stats,true);
	if(strtotime($cached_stats['general']['update_timestamp']) < (time()-$refreshInterval)) {
		$refresh = true;
	}
}
else { // Need to initialize the data source
	$refresh = true;
}
	
if($refresh) {
	if(DEBUG_REFRESH) echo "VATSIM JSON data is stale and needs to be refreshed";
	// Fetch VATSIM status JSON to get appropriate feed URLs for data and metars
	$status_array = json_decode(curl_request("https://status.vatsim.net/status.json",true),true);
	// Check to make sure that the data exists in the status JSON
	if(isset($status_array['data']['v3'][0])) {
		$vatsim_stats_url = $status_array['data']['v3'][0];
		if(DEBUG_REFRESH) {
			echo "Using VATSIM status JSON URLs";
			print_r($status_array['data']['v3']);
		}
	}
	else { // If the status JSON fails, fall back to a hard-coded URL
		if(DEBUG_REFRESH) echo "Using hard-coded URLs";
		$vatsim_stats_url = "https://data.vatsim.net/v3/vatsim-data.json";
	}
	// Fetch the JSON from the selected data source
	$curl_raw = curl_request($vatsim_stats_url);
	$stats_array = array(); // Error prevention in the case the the CURL does not return in the allotted time
	if(isJSON($curl_raw)) {	// Note: In some instances, this CURL request to VATSIM has a tendancy to last more than 30 seconds and time-out. When it times out, a non-JSON is returned. This allows us to catch the error.
		$stats_array = json_decode($curl_raw, true); // Execute CURL and decode JSON
		//file_put_contents("data/vatsim.json",$curl_raw);
		data_save("vatsim.json",$curl_raw);
	}
}
else {
	$refreshInterval = (strtotime($cached_stats['general']['update_timestamp']) + $refreshInterval) - time();
	if(DEBUG_REFRESH) echo "data is current";
	$refresh = false;
	$stats_array = $cached_stats;
}

if(isset($stats_array['pilots'])) {
	foreach($stats_array['pilots'] as $sortie) {
		if($sortie['flight_plan'][$sortie_type] == $afld_icao) {
//			$server_reply[] = "{'callsign':'" . $sortie['callsign'] . "','aircraft':'" . $sortie['flight_plan']['aircraft_faa'] . "','flight_type':'" . $sortie['flight_plan']['flight_rules'] . "','origin':'" . $sortie['flight_plan']['departure'] . "','destination':'" . $sortie['flight_plan']['arrival'] . "','route':'" . $sortie['flight_plan']['route'] . "'}";
			$server_reply[] = array('callsign'=> $sortie['callsign'], 'aircraft'=> $sortie['flight_plan']['aircraft_faa'], 'flight_type'=> $sortie['flight_plan']['flight_rules'], 'origin'=> $sortie['flight_plan']['departure'], 'destination'=> $sortie['flight_plan']['arrival'], 'route'=> $sortie['flight_plan']['route']);
		}
	}
}
echo json_encode($server_reply);

// *** Helper functions ***
function curl_request($url,$timeout_limit=false,$ssl_required=false) { // Simplified CURL request and return result
	$cu = curl_init();
	curl_setopt($cu,CURLOPT_URL,$url);
	curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
	if($timeout_limit) curl_setopt($cu,CURLOPT_TIMEOUT,10); // Added to prevent execution errors when VATSIM JSON hangs
	curl_setopt($cu, CURLOPT_ENCODING, "gzip");
	curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,$ssl_required);
	$curl_raw = @curl_exec($cu); // Errors suppressed to prevent them being thrown into reply JSON
	curl_close($cu);	
	return $curl_raw;
}

function isJSON($string){ // Check to verify that string is a JSON
   return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}

?>