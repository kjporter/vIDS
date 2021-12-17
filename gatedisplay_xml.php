<?php
header('Access-Control-Allow-Origin: https://www.ztlartcc.org/*');

// Fetch datafeed, if needed
$refreshInterval = 15; // How many seconds between requests to pull data from VATSIM data service
$feed = array();
$localPath = fetch_my_url();
// Harvest timestamp from cached network data to determine if update is needed
$vatsim_stats_url = $localPath . "data/vatsim.json";

$cu = curl_init();
curl_setopt($cu,CURLOPT_URL,$vatsim_stats_url);
curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
curl_setopt($cu, CURLOPT_ENCODING, "gzip");
curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
$cached_stats = json_decode(curl_exec($cu), true); // Execute CURL and decode JSON
curl_close($cu);
	
$refresh = false;
if(strtotime($cached_stats['general']['update_timestamp']) < (time()-$refreshInterval)) {
	$refresh = true;
	// Fetch VATSIM status JSON to get appropriate feed URLs for data and metars
	$cu = curl_init();
	curl_setopt($cu,CURLOPT_URL,"https://status.vatsim.net/status.json");
	curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
	curl_setopt($cu,CURLOPT_TIMEOUT,10); // Added to prevent execution errors when VATSIM JSON hangs
	curl_setopt($cu, CURLOPT_ENCODING, "gzip");
	curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
	$curl_raw = curl_exec($cu); // Execute CURL
	$status_array = json_decode($curl_raw, true); // decode JSON
	curl_close($cu);		
	// Check to make sure that the data exists
	if(isset($status_array['data']['v3'][0])) {
		$vatsim_stats_url = $status_array['data']['v3'][0];
	}
	else {
		$vatsim_stats_url = "https://data.vatsim.net/v3/vatsim-data.json";
	}
	// CURL to ingest JSON from VATSIM data service
	$cu = curl_init();
	curl_setopt($cu,CURLOPT_URL,$vatsim_stats_url);
	curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
	curl_setopt($cu,CURLOPT_TIMEOUT,10); // Added to prevent execution errors when VATSIM JSON hangs
	curl_setopt($cu, CURLOPT_ENCODING, "gzip");
	curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
	$curl_raw = curl_exec($cu); // Execute CURL
	curl_close($cu);
	$stats_array = array(); // Error prevention in the case the the CURL does not return in the allotted time
	if(isJSON($curl_raw)) {	// Note: In some instances, this CURL request to VATSIM has a tendancy to last more than 30 seconds and time-out. When it times out, a non-JSON is returned. This allows us to catch the error.
		$stats_array = json_decode($curl_raw, true); // Execute CURL and decode JSON
	}
	$feed = $stats_array;
}
else {
	$feed = $cached_stats;
}

$xml = new SimpleXMLElement("<markers></markers>");
$arrival = $xml->addChild('marker');
$arrival->addAttribute('name','DATEMODED');
$update = date_create_from_format('YmdHis', $feed['general']['update']);
$arrival->addAttribute('dest',date_format($update, 'm/d/y H:i') . ' UTC');
foreach($feed['pilots'] as $sortie) {
	if(($sortie['flight_plan']['departure'] == $_REQUEST['afld'])||($sortie['flight_plan']['arrival'] == $_REQUEST['afld'])) {
		$arrival = $xml->addChild('marker');
		$arrival->addAttribute('name',$sortie['callsign']);
		$arrival->addAttribute('dest',$sortie['flight_plan']['arrival']);
		$arrival->addAttribute('lat',$sortie['latitude']);
		$arrival->addAttribute('lng',$sortie['longitude']);
		$arrival->addAttribute('flightdata',$sortie['groundspeed'] . ' kts ' . $sortie['altitude'] . ' ft');
		$arrival->addAttribute('type',0);
		if($sortie['flight_plan'] == 'null') {
			$PColor = '#FF00FF';
		}
		elseif($sortie['flight_plan']['departure'] == $_REQUEST['afld']) {
			$PColor = '#00FF00';
		}
		elseif($sortie['flight_plan']['arrival'] == $_REQUEST['afld']) {
			$PColor = '#FFFF00';
		}	
		else {
			$PColor = '#FF0000';
		}
		$arrival->addAttribute('PColor',$PColor);
		$arrival->addAttribute('HDG',$sortie['heading']);
		$arrival->addAttribute('ACType',$sortie['flight_plan']['aircraft_faa']);
	}
}
Header('Content-type: text/xml');
print($xml->asXML());

function fetch_my_url() { // Returns server URL
	$ssl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
	return $ssl."://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';
}

function isJSON($string){ // Check to verify that string is a JSON
   return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}