<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ajax_refresh.php
		Function: Handles AJAX requests from main vIDS page to refresh shared data displays
		Created: 4/1/21
		Edited: 
		
		Changes: 
	*/
// *** THIS FILE IS AN ABOMINATION TO CODERS EVERYWHERE... IT'S ON MY LIST OF THINGS TO DO ***
//error_reporting(0); //This file always results in an AJAX reply. Turn errors off.

include_once "config.php";
include_once "common.php";

$live_network = false;
if (isset($_REQUEST['live'])) {
	//$live_network = ($_REQUEST['live'] == 'on' ? true : false);
	$live_network = filter_var($_REQUEST['live'],FILTER_VALIDATE_BOOLEAN);
}

$error = false;
$error_msg = array();

/*
// Determine if json needs to be fetched and stored on the local server (15 second interval based on timestamp)
$refresh = false;
$refreshInterval = 15; // Interval for refresh/stale data in seconds
$localPath = fetch_my_url();
$networkData = $localPath . "vatsim.json"; // Local filename for cached network data in JSON format
$airfields = array('KATL','KCLT','KGSO','KAVL','KTYS','KBHM'); // Configurable list of airfields (for TRACON/ARTCC view)
$icao_id = $airfields[0]; // Primary airfield (for local view)
$reply_dataset = null;

if(file_exists($networkData)) {
	$cached_stats = json_decode(file_get_contents($networkData));
	if(strtotime($cached_stats['general']['update_timestamp']) < (time()-$refreshInterval)) {
		$refresh = true;
	}
}
else { // If the file doesn't exist, then fetch it
	$refresh = true;
}

if($refresh) { // We've determined that the network data is stale or doesn't exist locally, so fetch it
	// First, grab the status.json from VATSIM to determine current datasource URLs
	$vatsim_status_url = "https://status.vatsim.net/status.json";
	$vatsim_status = curl_request($vatsim_status_url);
	if(isJSON($vatsim_status)) { // Make sure the CURL returned a JSON
		$vatsim_data_urls = json_decode($vatsim_status,true);
		if(filter_var($vatsim_data_urls['data']['v3'], FILTER_VALIDATE_URL)) { // Make sure the data v3 URL is valid
			if ($live_network) {
				$vatsim_data = curl_request($vatsim_data_urls['data']['v3']);
			}
			else {
				$vatsim_data = curl_request($localPath . "test_data/vatsim.json"); // For test use only!
			}
			$stats_array = json_decode($vatsim_data);
			file_put_contents("vatsim.json",$vatsim_data); // Store the JSON on the server
		}
		else {
			$error = true;
			$error_msg[] = "Unable to load data from VATSIM status server.";			
		}
	}
	else {
		$error = true;
		$error_msg[] = "Unable to load data from VATSIM status server.";
	}
*/
	// Configuration
	$ids_type = "B"; // L = local view, C = TRACON/ARTCC view, B = both (demo mode)
//	$airfields = array('KATL','KPDK','KFTY','KMGE','KRYY','KLZU','KMCN','KWRB','KAHN','KCSG'); // Configurable list of airfields (for TRACON/ARTCC view)
	//$icao_id = $airfields[0]; // Primary airfield (for local view)
	$icao_id = DEFAULT_AFLD_ICAO;
	
	$template = $_REQUEST['template']; // Is user requesting a specific multi-IDS template?
	//echo "TEMPLATE: $template";
	$reply_dataset['template'] = null;
	if(intval($template) > 0) { // Zero is the default airfield template
		if(file_exists("data/templates/" . $template . ".templ")) { 
			$templ_data = file_get_contents("data/templates/" . $template . ".templ"); // Fetch the template file
			$templ_data = strtoupper($templ_data); // Normalize ICAO IDs to upper case
			//$templ_data = explode("\n",$templ_data);
			//$templ_data = unset($templ_data[0]); // Remove the timestamp - not needed
			//$airfields = $template_airfields_list = json_encode($templ_data);
			$template_airfields = explode("\n",$templ_data);
			//unset($template_airfields_list[0]); // Remove the timestamp
			unset($template_airfields[0]); // We don't care about the template name
			$templ_creator = null;
			if(is_numeric($template_airfields[1])) { // Not all templates had this info...
				$templ_creator = $template_airfields[1];
				unset($template_airfields[1]); // We don't care about the template creator (here)
			}
			$airfields = $template_airfields; // Now, overwrite the airfields array
			$reply_dataset['template'] = $template_airfields;
			$reply_dataset['template_creator'] = $templ_creator;
		}
	}
	//$reply_dataset['template'] = $template_airfields;	
	$refreshInterval = 15; // How many seconds between requests to pull data from VATSIM data service
	//$localPath = "http://127.0.0.1/ids/";
	//$localPath = "https://kplink.net/ids/";
	$localPath = fetch_my_url();
	//echo $localPath;
	
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
		//echo "data is stale and needs to be refreshed";
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
			//echo "Using VATSIM status JSON URLs";
			$vatsim_stats_url = $status_array['data']['v3'][0];
			//print_r($status_array['data']['v3']);
		}
		else {
			//echo "Using hard-coded URLs";
			$vatsim_stats_url = "https://data.vatsim.net/v3/vatsim-data.json";
		}
		if(isset($status_array['metar'][0])) {
			$vatsim_metar_url = $status_array['metar'][0];
			//echo $status_array['metar'][0];
		}
		else {
			//echo "Using hard-coded URLs";
			$vatsim_metar_url = "http://metar.vatsim.net/metar.php";
		}
		
	// CURL to ingest JSON from VATSIM data service
	if ($live_network) {
		//$vatsim_stats_url = "https://data.vatsim.net/v3/vatsim-data.json"; // Hard-coded URLs replaced with the vatsim status json
	}
	else {
		$vatsim_stats_url = $localPath . "test_data/vatsim.json"; // For test use only!
	}
	$cu = curl_init();
	curl_setopt($cu,CURLOPT_URL,$vatsim_stats_url);
	curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
	curl_setopt($cu,CURLOPT_TIMEOUT,10); // Added to prevent execution errors when VATSIM JSON hangs
	curl_setopt($cu, CURLOPT_ENCODING, "gzip");
	curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
	$curl_raw = curl_exec($cu); // Execute CURL
	//$stats_array = json_decode($curl_raw, true); // decode JSON
	curl_close($cu);
	$stats_array = array(); // Error prevention in the case the the CURL does not return in the allotted time
	if(isJSON($curl_raw)) {	// Note: In some instances, this CURL request to VATSIM has a tendancy to last more than 30 seconds and time-out. When it times out, a non-JSON is returned. This allows us to catch the error.
		$stats_array = json_decode($curl_raw, true); // Execute CURL and decode JSON
		file_put_contents("data/vatsim.json",$curl_raw);
	}

	//print_r($airfields);
	// CURL to pull METAR from VATSIM data service
	foreach($airfields as $afld) {
	if ($live_network) {
		$vatsim_metar_str = $vatsim_metar_url . "?id=" . $afld;
		//echo $vatsim_metar_str;
		//$vatsim_metar_url = "http://metar.vatsim.net/metar.php?id=$afld"; // Hard-coded URLs replaced with the vatsim status json
	}
	else {
		$vatsim_metar_str = $localPath . "test_data/" . $afld . ".metar"; // For test use only!
	}
	$cu = curl_init();
	curl_setopt($cu,CURLOPT_URL,$vatsim_metar_str);
	curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
	curl_setopt($cu,CURLOPT_TIMEOUT,10); // Added to prevent execution errors when VATSIM JSON hangs
	curl_setopt($cu, CURLOPT_ENCODING, "gzip");
	curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
	$metar = curl_exec($cu); // Execute CURL
	curl_close($cu);
	file_put_contents("data/" . $afld . ".metar",$metar);
	}
}
	else {
		$refreshInterval = (strtotime($cached_stats['general']['update_timestamp']) + $refreshInterval) - time();
		//echo "data is current";
		$refresh = false;
		$stats_array = $cached_stats;
	}

	//print_r($stats_array);

	// Create parsed data array
	$airfield_data = array();
	//$airfield_data['template'] = $template_airfields_list;
	foreach($airfields as $afld) {
		//echo "Evaluating: $afld ";
		unset($afld_data);
		$afld_data['icao_id'] = $afld;
	// Does the ATIS we're looking for currently exist on the network?
	$atis_found = false;
	if(array_key_exists('atis',$stats_array)) { // Error prevention when no atis field is returned...
	for($a=0;$atis_found==false && $a < count($stats_array['atis']);$a++) {
		$atis = $stats_array['atis'][$a];
		$atis_found = ($atis['callsign'] == $afld . "_ATIS") ? true : false;
	}
	}
	if ($atis_found && ($atis['atis_code'] != null)) { // ATIS code null protect from malformed vATIS messages
		//print "Station found!<br/>";
		//print_r($atis);
		$afld_data['atis_online'] = $atis_found;
		$afld_data['atis_code'] = $atis['atis_code'];
		$afld_data['atis_text'] = implode(" ",$atis['text_atis']);
		$afld_data['traffic_flow'] = null; // Added to fix error on 6/9
		$afld_data['apch_rwys'] = null; // Added to fix error on 6/9
		$afld_data['dep_rwys'] = null; // Added to fix error on 6/9
		if (preg_match('/A(\d{4})/',$afld_data['atis_text'],$alt_set)) {
			$afld_data['altimeter'] = substr($alt_set[0],1,2) . "." . substr($alt_set[0],3);
		}
	}
	else {
		//print "Station $icao_id offline";
		$afld_data['atis_online'] = 0;
		$afld_data['atis_code'] = "--";
		$afld_data['traffic_flow'] = "OFFLINE";
		$afld_data['apch_rwys'] = null;
		$afld_data['dep_rwys'] = null;
		
		// Go fetch D-ATIS info so we can still get information on active runways and approaches/departures
		unset($datis);
		if($live_network && $refresh) {
			$d_atis_url = "https://datis.clowd.io/api/$afld";
		}
		else {
			$d_atis_url = $localPath . "test_data/" . $afld . ".atis"; // For test use only
		}
		//echo $d_atis_url;
		$cu = curl_init();
		curl_setopt($cu,CURLOPT_URL,$d_atis_url);
		curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
		curl_setopt($cu, CURLOPT_ENCODING, "gzip");
		curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
		$datis = curl_exec($cu); // Execute CURL
		curl_close($cu);
		file_put_contents("data/" . $afld . ".atis",$datis);
		$afld_data['atis_text'] = $datis;
		//echo $datis;
	}
		// Process ATIS... parse approach and departure info
		//echo substr($afld_data['atis_text'],strpos($afld_data['atis_text'],")")+3,strpos($afld_data['atis_text'],"NOTAMS")-strpos($afld_data['atis_text'],")")-5);
		$atxt = explode(".",$afld_data['atis_text']);
		//print_r($atxt);
		$terminal_search = array("APCH","APCHS","APPR","EXPECT");
		$terminal_strings = array();
		foreach($atxt as $atis_part) {
			foreach($terminal_search as $search_str) {
				if(is_numeric(strpos($atis_part,$search_str))) {
					//print $atis_part;
					if(is_numeric(strpos($atis_part,"VIS"))) {
						$afld_data['apch_type'] = "VIS";
					}
					if(is_numeric(strpos($atis_part,"ILS"))) {
						$afld_data['apch_type'] = "ILS";
					}
					preg_match_all('/(\d{2}[LRC]?)+/',$atis_part,$apch_rwys);
					$afld_data['apch_rwys'] = $apch_rwys[0];
				}
			}
		}
		//print_r($afld_data['apch_rwys']);
		$terminal_search = array("DEPS","LDG","DEPG");
		$terminal_strings = array();
		foreach($atxt as $atis_part) {
			foreach($terminal_search as $search_str) {
				if(is_numeric(strpos($atis_part,$search_str))) {
					preg_match_all('/(\d{2}[LRC]?)+/',$atis_part,$dep_rwys);
					$afld_data['dep_rwys'] = $dep_rwys[0];
				}
			}
		}
		//print_r($afld_data['dep_rwys']);
		//$rwy = 8;
		//$afld_data['traffic_flow'] = traffic_flow($rwy);
		
		if(count($afld_data['apch_rwys'])>0) {
			preg_match('/(\d{2})+/',$afld_data['apch_rwys'][0],$rwy);
			$afld_data['traffic_flow'] = traffic_flow($rwy[0]);
			//echo $rwy[0];
		}
		elseif(count($afld_data['dep_rwys'])>0) {
			preg_match('/(\d{2})+/',$afld_data['dep_rwys'][0],$rwy);
			$afld_data['traffic_flow'] = traffic_flow($rwy[0]);	
			//echo $rwy[0];
		}
		else {
			$afld_data['traffic_flow'] = "UNK";
		}
		
		// Extract the departure type (ROTG or RV) from the ATIS
		if(is_numeric(strpos($afld_data['atis_text'],"RNAV OFF THE GND"))) {
			$type_dep = "ROTG";
		}
		else {
			$type_dep = "RV";
		}
		$afld_data['dep_type'] = $type_dep;
		
	$afld_data['metar'] = file_get_contents("data/" . $afld . ".metar");
	if (!array_key_exists('altimeter',$afld_data)) {
		preg_match('/A(\d{4})/',$afld_data['metar'],$alt_set);
		if(isset($alt_set[0])) { // Added for error prevention
			$afld_data['altimeter'] = substr($alt_set[0],1,2) . "." . substr($alt_set[0],3);
		}
	}
	preg_match('/\d*\w+(KT)/',$afld_data['metar'],$wind_arr);
	//$wind = substr($afld_data['metar'],strpos($afld_data['metar'],"KT")-6,6);
	//$afld_data['winds'] = substr($wind,1,3) . "/" . substr($wind,4); 
	if(isset($wind_arr[0])) {
		$afld_data['winds'] = substr($wind_arr[0],0,3) . "/" . substr($wind_arr[0],3,-2);
	}
	else {
		$afld_data['winds'] = "Error";
	}
	if (!isset($afld_data['apch_type'])) {
		$afld_data['apch_type'] = "";
	}
	// RVR capture/decoding logic
	$rvr = array();
	$rvr_strings = null;
	preg_match('/(R\d{2})(\S*)/',$afld_data['metar'],$rvr_strings);
	foreach($rvr_strings as $rvr_string) {
		preg_match('/(?<=R)(\d{2})(\w*)/',$rvr_string,$a);
		if(isset($a[0])) {
			$runway = $a[0];
		}			
		/*
		// Following are not used, but saved just in case I need to pull a variable RVR string apart at some point...
		preg_match('/([PM]*\d{4})/',$rvr_string,$b);
		preg_match('/([V]\d{4})/',$rvr_string,$c);
		$rvr = $b[0];
		$variable = $c[0];
		*/
		preg_match('/(?<=\/)(\S*)/',$rvr_string,$d);
		if(isset($d[0])) {
			$rvr[$runway] = $d[0];
		}
	}
	$afld_data['rvr'] = $rvr;
	$rvr_disp = array();
	// First, combine approach and departure runways into a unique array so there are no repeats
		if(is_array($afld_data['apch_rwys'])||is_array($afld_data['dep_rwys'])) {
			if(is_array($afld_data['apch_rwys'])&&is_array($afld_data['dep_rwys'])) {
				$active_rwys = array_unique(array_merge($afld_data['apch_rwys'],$afld_data['dep_rwys']));
			}
			elseif(is_array($afld_data['apch_rwys'])) {
				$active_rwys = $afld_data['apch_rwys'];
			}
			else {
				$active_rwys = $afld_data['dep_rwys'];
			}
			//if($afld == "KATL") {
			//	print_r($active_rwys);
			//}
			if(is_array($active_rwys)) {
				foreach($active_rwys as $rwy) {
					//echo ($afld == "KATL" ? "HERE $rwy" : "");
					$rvr_val = "P6000FT";
					if(array_key_exists($rwy,$afld_data['rvr'])) {
						$rvr_val = $afld_data['rvr'][$rwy];
					}
				$rvr_disp[] = "RY$rwy $rvr_val";
				}
			}
			else {
				$rvr_disp[] = "RVR Not Available";
			}
		}
		else {
			$rvr_disp[] = "RVR Not Available";
		}
	$afld_data['rvr_display'] = $rvr_disp;
	
	$tower_cab = array('del'=>0,'gnd'=>0,'twr'=>0);
	// Search for online controllers in tower cab
	if(array_key_exists('controllers',$stats_array)) { // Error prevention for when controllers array isn't returned...
	foreach($stats_array['controllers'] as $controller) {
		if(is_numeric(strpos($controller['callsign'],substr($afld,1)))) {
			if(is_numeric(strpos($controller['callsign'],"DEL"))) {
				$tower_cab['del'] = 1;
			}
			if(is_numeric(strpos($controller['callsign'],"GND"))) {
				$tower_cab['gnd'] = 1;
			}
			if(is_numeric(strpos($controller['callsign'],"TWR"))) {
				$tower_cab['twr'] = 1;
			}
		}			
	}
	}
	/*
	// Testing use only
	if($afld == "KPDK") {
		$tower_cab['del'] = 0;
		$tower_cab['gnd'] = 1;
		$tower_cab['twr'] = 1;
	}
	*/
	$afld_data['tower_cab'] = $tower_cab;
	
	$airfield_data[$afld] = $afld_data;
	}
	//print "<br/><br/>$header<br/>$atis_code<br/>$metar<br/>$traffic_flow";
	//print_r($airfield_data);
	
	$reply_dataset['airfield_data'] = $airfield_data;
	
	function traffic_flow($rwy) { // Traffic flow decoder ring
	//print "Finding flow using $rwy";
	//$rwy = 8; // This is for testing... need to replace with actual runway when I can extract this info...
	$traffic_flow = null;
	$rwy = intval($rwy);
	if (($rwy > 31)||($rwy < 4)) { // Note: this algorithm will not apply to all airports. Perhaps allow user to define flow nomenclature?
		$traffic_flow = "NORTH";
	}
	else if (($rwy > 4)&&($rwy < 12)) {
		$traffic_flow = "EAST";
	}	
	else if (($rwy > 12)&&($rwy < 22)) {
		$traffic_flow = "SOUTH";
	}
	else if (($rwy > 22)&&($rwy < 31)) {
		$traffic_flow = "WEST";
	}	
	else {
		$traffic_flow = "ERROR";
	}
	return $traffic_flow;
	}
	
	/*
	// Code below automates controller position identification based on primed frequencies - feature on hold for now...
	
	// Logic to identify controller positions and combines
	$atl_ctl = array();
	$atl_ctl[] = array("LC-1","119.100","DR-N","local");
	$atl_ctl[] = array("LC-2","125.320","LC-1","local");
	$atl_ctl[] = array("LC-3","123.850","LC-4","local");
	$atl_ctl[] = array("LC-4","119.300","LC-1","local");
	$atl_ctl[] = array("LC-5","119.500","LC-4","local");
	$atl_ctl[] = array("GC-N","121.900","LC-2","ground");
	$atl_ctl[] = array("GC-C","121.750","GC-N","ground");
	$atl_ctl[] = array("GC-S","121.650","LC-5","ground");
	$atl_ctl[] = array("GM","125.000","GC-N","ground");
	$atl_ctl[] = array("CD-1","118.100","GC-N","clnc");
	$atl_ctl[] = array("CD-2","118.700","CD-1","clnc");
	$atl_ctl[] = array("AR-O","124.600","DR-N","apch");
	$atl_ctl[] = array("AR-V","127.250","AR-O","apch");
	$atl_ctl[] = array("AR-A","135.370","AR-O","apch");
	$atl_ctl[] = array("DR-N","125.700","C-43","apch");
	$atl_ctl[] = array("DR-S","125.650","DR-N","apch");
	$atl_ctl[] = array("DR-I","121.220","DR-S","apch");
	$atl_ctl[] = array("SAT-P","126.970","DR-N","apch");
	$atl_ctl[] = array("SAT-F","121.000","SAT-P","apch");
	$atl_ctl[] = array("SAT-X","119.800","SAT-F","apch");
	$atl_ctl[] = array("SAT-G","128.570","SAT-P","apch");
	$atl_ctl[] = array("SAT-Q","124.300","SAT-P","apch");
	$atl_ctl[] = array("C-43","132.970","","ctr");
	
	// Make an array of ATL frequencies
	$atl_freqs = array();
	foreach($atl_ctl as $position) {
		$atl_freqs[] = $position[1];
	}
	
	// Search VATSIM data for callsign containing "ATL" and one of the identified freqs above
	$primary_controllers = array();
	$online_positions = array();
	foreach($stats_array['controllers'] as $controller) {
		if(is_numeric(strpos($controller['callsign'],"ATL"))&&in_array($controller['frequency'],$atl_freqs)) { // We found a match - this is someone conrolling ATL
//		if(is_numeric(strpos($controller['callsign'],"ATL"))) { // We found a match - this is someone conrolling ATL
//			print $controller['callsign'] . " " . $controller['frequency'];
			// What position are they logged in as?
			foreach($atl_ctl as $position) {
				if($controller['frequency'] == $position[1]) {
					$primary_controllers[$position[0]] = $controller;
					$online_positions[] = $position[0];
				}
			}
		}
	}
	//print_r($primary_controllers);
	//print_r($online_positions);
	// Build controller list and combines
	if(in_array("CD",$online_positions)) {
		$delivery_control = "CD-1 CD-2 FD<br/>CD-1";
		if(in_array("CD-2",$online_positions)) {
			$delivery_control .= " CD-2 CD-2";
		}
		else $delivery_conrol .= " CD-1 CD-1";
	}
	else {
		if(in_array("GC",$online_positions)) {
			$delivery_control = "CD-1 CD-2 FD<br/>GC-N GC-N GC-N";
		}
		else {
			if(in_array("LC",$online_positions)) {
				if(in_array("LC-2",$online_positions)) {
					$delivery_control = "CD-1 CD-2 FD<br/>LC-2 LC-2 LC-2";
				}
				else {
					$delivery_control = "CD-1 CD-2 FD<br/>LC-1 LC-1 LC-1";
				}
			}
			else {
				$delivery_control = "CLNC OFFLINE";
			}
		}
	}
	if(in_array("GC",$online_positions)||in_array("LC",$online_positions)) {
	$ground_control = "GC-N GC-C GC-S GM<br/>CMBND TO:<br/>";
	if(in_array("GC-N",$online_positions)||in_array("LC-2",$online_positions)||in_array("LC-1",$online_positions)) {
		if(in_array("GC-N",$online_positions)) {
			$ground_control .= "GC-N";
		}
		elseif(in_array("LC-2",$online_positions)) {
			$ground_control .= "LC-2";
		}
		else {
			$ground_control .= "LC-1";
		}
	}
	
	if(in_array("GC-C",$online_positions)||in_array("GC-N",$online_positions)||in_array("LC-2",$online_positions)||in_array("LC-1",$online_positions)) {
		if(in_array("GC-C",$online_positions)) {
			$ground_control .= " GC-C";
		}
		elseif(in_array("GC-N",$online_positions)) {
			$ground_control .= " GC-N";
		}
		elseif(in_array("LC-2",$online_positions)) {
			$ground_control .= " LC-2";
		}
		else {
			$ground_control .= " LC-1";
		}
	}

	if(in_array("GC-S",$online_positions)||in_array("LC-5",$online_positions)||in_array("LC-4",$online_positions)||in_array("LC-1",$online_positions)) {
		if(in_array("GC-S",$online_positions)) {
			$ground_control .= " GC-S";
		}
		elseif(in_array("LC-5",$online_positions)) {
			$ground_control .= " LC-5";
		}
		elseif(in_array("LC-4",$online_positions)) {
			$ground_control .= " LC-4";
		}
		else {
			$ground_control .= " LC-1";
		}
	}	
	
	if(in_array("GM",$online_positions)||in_array("GC-N",$online_positions)||in_array("LC-2",$online_positions)||in_array("LC-1",$online_positions)) {
		if(in_array("GM",$online_positions)) {
			$ground_control .= " GM";
		}
		elseif(in_array("GC-N",$online_positions)) {
			$ground_control .= " GC-N";
		}
		elseif(in_array("LC-2",$online_positions)) {
			$ground_control .= " LC-2";
		}
		else {
			$ground_control .= " LC-1";
		}
	}
	}
	else {
		$ground_control = "GROUND OFFLINE";
	}

	if(in_array("LC-1",$online_positions)) {
		$local_control = "LC-1 LC-2 LC-3 LC-4 LC-5<br/>CMBND TO:<br/>LC-1";
	if(in_array("LC-2",$online_positions)||in_array("LC-1",$online_positions)) {
		if(in_array("LC-2",$online_positions)) {
			$local_control .= " LC-2";
		}
		else {
			$local_control .= " LC-1";
		}
	}
	if(in_array("LC-3",$online_positions)||in_array("LC-4",$online_positions)||in_array("LC-1",$online_positions)) {
		if(in_array("LC-3",$online_positions)) {
			$local_control .= " LC-3";
		}
		elseif(in_array("LC-4",$online_positions)) {
			$local_control .= " LC-4";
		}
		else {
			$local_control .= " LC-1";
		}
	}
	if(in_array("LC-4",$online_positions)||in_array("LC-1",$online_positions)) {
		if(in_array("LC-4",$online_positions)) {
			$local_control .= " LC-4";
		}
		else {
			$local_control .= " LC-1";
		}
	}
	if(in_array("LC-5",$online_positions)||in_array("LC-4",$online_positions)||in_array("LC-1",$online_positions)) {
		if(in_array("LC-5",$online_positions)) {
			$local_control .= " LC-5";
		}
		elseif(in_array("LC-4",$online_positions)) {
			$local_control .= " LC-4";
		}
		else {
			$local_control .= " LC-1";
		}
	}
	}
	else {
		$local_control = "LOCAL OFFLINE";
	}
	if(in_array("DR-N",$online_positions)) {
		$dep_control = "N S I<br/>CMBND TO:<br/>N";
		$app_control = "V O A<br/>CMBND TO:<br/>";
		$sat_control = "P F X G Q<br/>CMBND TO:<br/>";
		if(in_array("DR-S",$online_positions)) {
			$dep_control .= " S";
			if(in_array("DR-I",$online_positions)) {
				$dep_control .= " I";
			}
			else {
				$dep_control .= " S";
			}
		}
		else {
			$dep_control .= " N N";
		}
	}
	else {
		$dep_control = "OFFLINE";
	}

	if(in_array("AR-O",$online_positions)) {
		//$app_control = "V O A<br/>CMBND TO:<br/>";
		if(in_array("AR-V",$online_positions)) {
			$app_control .= "V";
		}
		else {
			$app_control .= "O";
		}
		$app_control .= " O";
		if(in_array("AR-A",$online_positions)) {
			$app_control .= " A";
		}
		else {
			$app_control .= " O";
		}
	}
	elseif (in_array("DR-N",$online_positions)) {
		$app_control .= "N N N";
	}
	else {
		$app_control = "OFFLINE";
	}
	
	if(in_array("SAT-P",$online_positions)) {
		//$sat_control = "P F X G Q<br/>CMBND TO:<br/>P";
		$sat_control .= " P";
		if(in_array("SAT-F",$online_positions)) {
			$sat_control .= " F";
		}
		else {
			$sat_control .= " P";
		}
		if(in_array("SAT-X",$online_positions)) {
			$sat_control .= " X";
		}
		else {
			$sat_control .= " F";
		}
		if(in_array("SAT-G",$online_positions)) {
			$sat_control .= " G";
		}
		else {
			$sat_control .= " P";
		}
		if(in_array("SAT-Q",$online_positions)) {
			$sat_control .= " Q";
		}
		else {
			$sat_control .= " P";
		}
	}
	elseif (in_array("DR-N",$online_positions)) {
		$sat_control .= "N N N N N";
	}
	else {
		$sat_control = "OFFLINE";
	}
*/

// *** GET STATIC DATA FROM SERVER ***
// TODO: This will all move to an SQL database eventually...

// Fetch controller position combines
// TODO: IMPLEMENT THIS!
if(file_exists("data/controllers.dat")) {
	$ctrl_data = file_get_contents("data/controllers.dat");
	$ctrl_ts = substr($ctrl_data,3,strpos($ctrl_data,"-*--")-3);
	$ctrl_data = substr($ctrl_data,strpos($ctrl_data,"-*--")+4);
}
else {
	$ctrl_data = "";
}
$reply_dataset['controllers'] = $ctrl_data;

// Fetch PIREPs
if(file_exists("data/pirep.dat")) {
		$pirep_data = file_get_contents("data/pirep.dat");
		$pirep_timeout = 3600; // Timeout in 1 hour (3,600 seconds)
		$pirep_display = "";
		if(strlen($pirep_data) > 0) {
			$pireps = explode("\r",$pirep_data);
			foreach($pireps as $pirep) {
				$rep = explode("|",$pirep);
				if(intval($rep[0]) > (time() - $pirep_timeout)) { // Check if PIREP is still valid
					if(strlen($pirep_display)>0) {
						$pirep_display .= "\r";
					}
					$pirep_display .= str_replace("/R","/RM",$rep[1]); // . "\r";
				}
			}
		}
		if($pirep_display == "") {
			$pirep_display = "No PIREPs to display";
		}
}
$reply_dataset['pirep'] = str_replace("\n","",$pirep_display);

// Fetch Airfield Configuration
if(file_exists("data/afld.dat")) {
	$afld_data = file_get_contents("data/afld.dat");
	$afld_ts = substr($afld_data,3,strpos($afld_data,"-*--")-3);
	$afld_data = substr($afld_data,strpos($afld_data,"-*--")+4);
	$afld_class = "";
	if($afld_ts > (time()-120)) { // It's a recent update, so highlight the change to the user
		$afld_class = "newupdate";
	}
	$afld_data = str_replace("\n","",$afld_data); // Remove line breaks
	$afld_raw = str_replace("<br>AUTO ON","",$afld_data); // Remove the auto declaration
	$afld_raw = str_replace("<br>AUTO OFF","",$afld_raw); // Remove the auto declaration
	$afld_reply["raw"] = str_replace("AUTO ON","",$afld_raw); // Remove the auto declaration
	$afld_reply["9L@M2"] = is_numeric(strpos($afld_data,"9L@M2 ON")) ? true : false;
	$afld_reply["LAHSO"] = is_numeric(strpos($afld_data,"LAHSO ON")) ? true : false;
	$afld_reply["AUTO"] = is_numeric(strpos($afld_data,"AUTO ON")) ? true : false;
	
	//$afld_reply["raw"] = str_replace("\n","",$afld_data); // Remove line breaks
}
else {
		$afld_reply["9L@M2"] = $afld_reply["LAHSO"] = $afld_reply["AUTO"] = false;
}
$reply_dataset['config'] = $afld_reply;

// Fetch Manual Flow & Runway Configuration****************************************************************************************************************************************************
if(file_exists("data/flow.dat")&&!$reply_dataset['config']['AUTO']) {
	$file = fopen("data/flow.dat","r");
	fgets($file);
	$flow = fgets($file);
	$arr = fgetcsv($file,1000,",");
	$dep = fgetcsv($file,1000,",");
	$reply_dataset['airfield_data']['K' . DEFAULT_AFLD_ID]['traffic_flow'] = preg_replace( "/\r|\n/", "", $flow ); //preg_replace removes endline char
	$reply_dataset['airfield_data']['K' . DEFAULT_AFLD_ID]['apch_type'] = "";
	$reply_dataset['airfield_data']['K' . DEFAULT_AFLD_ID]['apch_rwys'] = $arr;
	$reply_dataset['airfield_data']['K' . DEFAULT_AFLD_ID]['dep_type'] = "";
	$reply_dataset['airfield_data']['K' . DEFAULT_AFLD_ID]['dep_rwys'] = $dep;
}

// Fetch CIC Notes
if(file_exists("data/cic.dat")) {
	$cic_data = file_get_contents("data/cic.dat");
	$cic_ts = substr($cic_data,3,strpos($cic_data,"-*--")-3);
	$cic_data = substr($cic_data,strpos($cic_data,"-*--")+4);
	$cic_class = "";
	if($cic_ts > (time()-120)) { // It's a recent update, so highlight the change to the user
		$cic_class = "newupdate";
	}
}
else {
	$cic_data = "";
}
$reply_dataset['cic'] = $cic_data; //str_replace("\n","",$cic_data); // Remove line breaks

// Fetch A80 CIC Notes
if(file_exists("data/a80cic.dat")) {
	$a80cic_data = file_get_contents("data/a80cic.dat");
	$a80cic_ts = substr($a80cic_data,3,strpos($a80cic_data,"-*--")-3);
	$a80cic_data = substr($a80cic_data,strpos($a80cic_data,"-*--")+4);
	$a80cic_class = "";
	if($a80cic_ts > (time()-120)) { // It's a recent update, so highlight the change to the user
		$a80cic_class = "newupdate";
	}
}
else {
	$a80cic_data = "";
}
$reply_dataset['a80cic'] = $a80cic_data; //str_replace("\n","",$a80cic_data); // Remove line breaks

// Fetch TMU Information
if(file_exists("data/tmu.dat")) {
	$tmu_data = file_get_contents("data/tmu.dat");
	$tmu_ts = substr($tmu_data,3,strpos($tmu_data,"-*--")-3);
	$tmu_data = substr($tmu_data,strpos($tmu_data,"-*--")+4);
	$tmu_class = "";
	if($tmu_ts > (time()-120)) { // It's a recent update, so highlight the change to the user
		$tmu_class = "newupdate";
	}
}
else {
	$tmu_data = "";
}
$reply_dataset['tmu'] = $tmu_data; //str_replace("\n","",$tmu_data); // Remove line breaks

// Fetch Trips Information
if(file_exists("data/trips.dat")) {
	$trips_data = file_get_contents("data/trips.dat");
	$trips_ts = substr($trips_data,3,strpos($trips_data,"-*--")-3);
	$trips_data = substr($trips_data,strpos($trips_data,"-*--")+4);
	$trips_class = "";
	if($trips_ts > (time()-120)) { // It's a recent update, so highlight the change to the user
		$trips = "newupdate";
	}
	$trips_reply["raw"] = $trips_data;
	$trips_reply["FTA"] = is_numeric(strpos($trips_data,"FTA ON")) ? true : false;
	$trips_reply["FTD"] = is_numeric(strpos($trips_data,"FTD ON")) ? true : false;
}
else {
	$trips_reply["FTA"] = $afld_reply["FTD"] = false;
}
$reply_dataset['trips'] = str_replace("\n","",$trips_reply); // Remove line breaks

// Fetch Departure Gate Information
if(file_exists("data/gates.dat")) {
	$gates_data = file_get_contents("data/gates.dat");
	$gates_ts = substr($gates_data,3,strpos($gates_data,"-*--")-3);
	$gates_data = explode(':',substr($gates_data,strpos($gates_data,"-*--")+4));
	$gates_arr = array();
	$gates_arr[] = substr($gates_data[1],0,-1);
	$gates_arr[] = substr($gates_data[2],0,-1);
	$gates_arr[] = substr($gates_data[3],0);
	$gates_class = "";
	if($gates_ts > (time()-120)) { // It's a recent update, so highlight the change to the user
		$gates_class = "newupdate";
	}
}
else {
	$gates_arr = "";
}
$reply_dataset['gates'] = str_replace("\n","",$gates_arr); // Remove line breaks

// Fetch Departure Split Information
if(file_exists("data/splits.dat")) {
	$splits_data = file_get_contents("data/splits.dat");
	$splits_ts = substr($splits_data,3,strpos($splits_data,"-*--")-3);
	$splits_data = substr($splits_data,strpos($splits_data,"-*--")+4);
	$splits_json = json_decode($splits_data);
}
else {
	$splits_json = "";
}
$reply_dataset['splits'] = $splits_json;

// Fetch Override Information
if(file_exists("data/override.dat")) {
	$override_data = file_get_contents("data/override.dat");
	$override_json = json_decode($override_data);
}
else {
	$override_json = "";
}
$reply_dataset['override'] = $override_json;

// Load error messages into array
$reply_dataset['error'] = array($error,$error_msg);

// Echo JSON back to requester
echo json_encode($reply_dataset);

// *** Helper functions ***
function curl_request($url,$ssl_required=false) { // Simplified CURL request and return result
	$cu = curl_init();
	curl_setopt($cu,CURLOPT_URL,$url);
	curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
	curl_setopt($cu, CURLOPT_ENCODING, "gzip");
	curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,$ssl_required); // There is no reason to verify the SSL certificate, skip this
	$curl_raw = curl_exec($cu);
	curl_close($cu);	
	return $curl_raw;
}

function isJSON($string){ // Check to verify that string is a JSON
   return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}