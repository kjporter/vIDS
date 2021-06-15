<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ajax_weather.php
		Function: Handles AJAX requests to fetch weather (METARs and TAFs)
		Created: 4/1/21
		Edited:
		
		Changes:
	*/

	header('Access-Control-Allow-Origin: *'); // Required for NWS pulls due to CORS policy
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With'); 

	$station = $_GET['icao'];
	// Fetch METAR

	$url = "https://www.aviationweather.gov/adds/dataserver_current/httpparam?dataSource=metars&requestType=retrieve&format=xml&stationString=" . strtoupper($station) . "&hoursBeforeNow=1";
	$cu = curl_init();
	curl_setopt($cu,CURLOPT_URL,$url);
	curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
	curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
	$weather = curl_exec($cu); // Execute CURL
	curl_close($cu);
	$xml = simplexml_load_string($weather) or die ("Error fetching METAR");
	$metar =  $xml->data->METAR->raw_text;

	// Fetch TAF
	$url = "https://www.aviationweather.gov/adds/dataserver_current/httpparam?dataSource=tafs&requestType=retrieve&format=xml&stationString=" . strtoupper($station) . "&hoursBeforeNow=1";
	$cu = curl_init();
	curl_setopt($cu,CURLOPT_URL,$url);
	curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
	curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
	$weather = curl_exec($cu); // Execute CURL
	curl_close($cu);
	$xml = simplexml_load_string($weather) or die ("Error fetching TAF");
	$taf = $xml->data->TAF->raw_text;
	echo $metar . "<br/><br/>" . $taf;