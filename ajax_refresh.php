<?php
/*
	vIDS (virtual Information Display System) for VATSIM
	
	Filename: ajax_refresh.php
	Function: Handles AJAX requests from main vIDS page to refresh shared data displays. Replies with JSON to requester
	Created: 4/1/21
	Edited: 10/15/21
	
	Changes: Major code cleanup to transition the project from dev to prod
*/
//error_reporting(0); //This file always results in an AJAX reply. Turn errors off.

define("DEBUG_REFRESH",false); // Enables noisy mode for debugging

include_once "vars/config.php";
include_once "common.php";
include_once "ajax_rvr.php";
include_once "data_management.php";	

// Configuration
$refreshInterval = 15; // How many seconds between requests to pull data from VATSIM data service, 15 sec is the max refresh rate 
$localPath = fetch_my_url();
$live_network = true; // When set to false, replaces live network data with the JSON defined on the next line for testing purposes 
$static_json = "test_data/vatsim.json";
$icao_id = DEFAULT_AFLD_ICAO;
$template = isset($_REQUEST['template']) ? $_REQUEST['template']: 0; // Is user requesting a specific multi-IDS template?
if(DEBUG_REFRESH) echo "TEMPLATE: $template";
// Init variables
$error = false;
$error_msg = array();
$reply_dataset['template'] = null;
$refresh = false;
//$init_datasource = false; // Set true when the data source (DB or .dat files) are empty and need to be initialized (like on a first run)

// If a template is requested, find the template and import it
if(intval($template) > 0) { // Zero is the default airfield template
	//if(file_exists("data/templates/" . $template . ".templ")) { 
		//$templ_data = file_get_contents("data/templates/" . $template . ".templ"); // Fetch the template file
		$templ_data = data_read("templates/" . $template . ".templ","string");
		$templ_data = strtoupper($templ_data); // Normalize ICAO IDs to upper case
		$template_airfields = explode("\n",$templ_data);
		unset($template_airfields[0]); // We don't care about the template name
		$templ_creator = null;
		if(is_numeric($template_airfields[1])) { // Not all templates had this info...
			$templ_creator = $template_airfields[1];
			unset($template_airfields[1]); // We don't care about the template creator (here)
		}
		$airfields = $template_airfields; // Now, overwrite the airfields array with the template airfields
		$reply_dataset['template'] = $template_airfields;
		$reply_dataset['template_creator'] = $templ_creator;
	//}
}
	
// Harvest timestamp from cached JSON to determine if update is needed (prevents flooding VATSIM data service with unnecessary requests)
//$vatsim_stats_url = $localPath . "data/vatsim.json";
//$cached_stats = json_decode(curl_request($vatsim_stats_url),true);	
$cached_stats = data_read("vatsim.json","string");
if(isJSON($cached_stats)) {
	$cached_stats = json_decode($cached_stats,true);
	if(strtotime($cached_stats['general']['update_timestamp']) < (time()-$refreshInterval)) {
		$refresh = true;
	}
}
else { // Need to initialize the data source
	//$init_datasource = true;
	$refresh = true;
}
	
//if((strtotime($cached_stats['general']['update_timestamp']) < (time()-$refreshInterval))||$init_datasource) {
//	$refresh = true;
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
	if(isset($status_array['metar'][0])) { // Fetch the METAR URL from the status JSON
		$vatsim_metar_url = $status_array['metar'][0];
	}
	else {
		$vatsim_metar_url = "http://metar.vatsim.net/metar.php";
	}
		
	if (!$live_network) { // Updates from stored JSON for test and demo purposes - not for operational use!
		$vatsim_stats_url = $localPath . $static_json;
	}
	// Fetch the JSON from the selected data source
	$curl_raw = curl_request($vatsim_stats_url);
	$stats_array = array(); // Error prevention in the case the the CURL does not return in the allotted time
	if(isJSON($curl_raw)) {	// Note: In some instances, this CURL request to VATSIM has a tendancy to last more than 30 seconds and time-out. When it times out, a non-JSON is returned. This allows us to catch the error.
		$stats_array = json_decode($curl_raw, true); // Execute CURL and decode JSON
		//file_put_contents("data/vatsim.json",$curl_raw);
		data_save("vatsim.json",$curl_raw);
	}

	// CURL to pull METAR from VATSIM data service
	foreach($airfields as $afld) {
	if ($live_network) {
		$vatsim_metar_str = $vatsim_metar_url . "?id=" . $afld;
	}
	else {
		$vatsim_metar_str = $localPath . "test_data/" . $afld . ".metar"; // For test use only!
	}
	$metar = curl_request($vatsim_metar_str);
	//file_put_contents("data/" . $afld . ".metar",$metar);
	data_save($afld . ".metar",$metar);
	}
}
else {
	$refreshInterval = (strtotime($cached_stats['general']['update_timestamp']) + $refreshInterval) - time();
	if(DEBUG_REFRESH) echo "data is current";
	$refresh = false;
	$stats_array = $cached_stats;
}

// Parse data by airfield and organize it into an array
$airfield_data = array();
foreach($airfields as $afld) {
	if(DEBUG_REFRESH) echo "Evaluating: $afld ";
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
	else { // vATIS is offline or not found in status array... set fields appropriately
		$afld_data['atis_online'] = 0;
		$afld_data['atis_code'] = "--";
		$afld_data['traffic_flow'] = "OFFLINE";
		$afld_data['apch_rwys'] = null;
		$afld_data['dep_rwys'] = null;
	
		// For airfields with real-world D-ATIS, we'll fetch that info to fill in the blanks when vATIS is offline. This provides information on active runways and approaches/departures. We don't fetch the ATIS ID though to prevent confusion.
		unset($datis);
		if($live_network && $refresh) {
			$d_atis_url = "https://datis.clowd.io/api/$afld"; // <- don't know who is responsible for this, but thanks friend!
		}
		else { // Pull cached data if we're in test mode
			$d_atis_url = $localPath . "test_data/" . $afld . ".atis"; // For test use only
		}
		$datis = curl_request($d_atis_url);
		//file_put_contents("data/" . $afld . ".atis",$datis); // Cache data locally to prevent overwhelming the D-ATIS URL with requests
		data_save($afld . ".atis",$datis); // Cache data locally to prevent overwhelming the D-ATIS URL with requests - is this even used anymore?
		$afld_data['atis_text'] = $datis;
	}
	// Process ATIS... parse approach and departure info
	$atxt = explode(".",$afld_data['atis_text']);
	$terminal_search = array("APCH","APCHS","APPR","EXPECT"); // We use these keywords to detect the arrival runways in use
	$terminal_strings = array();
	foreach($atxt as $atis_part) {
		foreach($terminal_search as $search_str) {
			if(is_numeric(strpos($atis_part,$search_str))) { // Extract approach type and runways in use
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
	$terminal_search = array("DEPS","LDG","DEPG"); // We use these keywords to detect the departure runways in use
	$terminal_strings = array();
	foreach($atxt as $atis_part) {
		foreach($terminal_search as $search_str) {
			if(is_numeric(strpos($atis_part,$search_str))) { // Extract departure runways from ATIS text
				preg_match_all('/(\d{2}[LRC]?)+/',$atis_part,$dep_rwys);
				$afld_data['dep_rwys'] = $dep_rwys[0];
			}
		}
	}
	//print_r($afld_data['dep_rwys']);

	// Determines displayed airport flow pattern (Ex. EAST/WEST) displayed in vATIS
//	if(count($afld_data['apch_rwys'])>0) {
	if(isset($afld_data['apch_rwys'][0])) {
		preg_match('/(\d{2})+/',$afld_data['apch_rwys'][0],$rwy); // Extract runway number only (strips L/C/R)
		$afld_data['traffic_flow'] = traffic_flow($rwy[0]);
	}
//	elseif(count($afld_data['dep_rwys'])>0) {
	elseif(isset($afld_data['dep_rwys'][0])) {
		preg_match('/(\d{2})+/',$afld_data['dep_rwys'][0],$rwy);
		$afld_data['traffic_flow'] = traffic_flow($rwy[0]);	
	}
	else { // If we're unable to determine the flow, show UNK
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

	//$afld_data['metar'] = file_get_contents("data/" . $afld . ".metar"); // <- is it necessary to fetch this each time? This isn't guarded by if file exists!
	$afld_data['metar'] = data_read($afld . ".metar","string");
	if (!array_key_exists('altimeter',$afld_data)) { // Extract altimeter setting
		preg_match('/A(\d{4})/',$afld_data['metar'],$alt_set);
		if(isset($alt_set[0])) { // Added for error prevention
			$afld_data['altimeter'] = substr($alt_set[0],1,2) . "." . substr($alt_set[0],3);
		}
	}
	preg_match('/\d*\w+(KT)/',$afld_data['metar'],$wind_arr); // Extract winds
	if(isset($wind_arr[0])) {
		$afld_data['winds'] = substr($wind_arr[0],0,3) . "/" . substr($wind_arr[0],3,-2);
	}
	else {
		$afld_data['winds'] = "Error";
	}
	// Extract visibility from METAR
	preg_match('/(\s{1}\d{1}\s?)*([M\/\d]+SM)/',$afld_data['metar'],$vis);
	$afld_data['visibility'] = $vis[0]; // Not currently using this for anything, but leaving it here just in case we need it
	$vis_numeric = 0;
	if(strpos($vis[0],"/") != false) {
		$vis_numeric = intval(substr($vis[0],strpos($vis[0],"/")-1,1)) / intval(substr($vis[0],strpos($vis[0],"/")+1,1));
		$vis_numeric += intval(str_replace(array('M','SM'),"",substr($vis[0],0,strpos($vis[0],"/")-1)));
	}
	else {
		$vis_numeric = intval(str_replace(array('M','SM'),"",$vis[0]));
	}
	$afld_data['visibility_numeric'] = $vis_numeric;
	
	if (!isset($afld_data['apch_type'])) {
		$afld_data['apch_type'] = "";
	}

	// Fetch RVR data
	$rvr_detail = fetch_rvr($afld,false,false);
	$afld_data['rvr_detail'] = $rvr_detail;
	
	// RVR capture/decoding logic
	$rvr = array();
	$rvr_strings = null;
	preg_match('/(R\d{2})(\S*)/',$afld_data['metar'],$rvr_strings);
	foreach($rvr_strings as $rvr_string) {
		preg_match('/(?<=R)(\d{2})(\w*)/',$rvr_string,$a);
		if(isset($a[0])) {
			$runway = $a[0];
		}			
	
		// Following are not used, but saved just in case I need to pull a variable RVR string apart at some point...
		//	preg_match('/([PM]*\d{4})/',$rvr_string,$b);
		//	preg_match('/([V]\d{4})/',$rvr_string,$c);
		//	$rvr = $b[0];
		//	$variable = $c[0];
	
		preg_match('/(?<=\/)(\S*)/',$rvr_string,$d);
		if(isset($d[0])) {
			$rvr[$runway] = $d[0];
		}
	}
	$afld_data['rvr'] = $rvr;
	$rvr_disp = array();
	
	// Build approach/departure runway unique array so there are no repeats
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
		if(is_array($active_rwys)) {
			foreach($active_rwys as $rwy) {
				$rvr_val = "P6000FT"; // Default value if no RVR value is given
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

	// Build tower cab status (D/G/T) display to indicate when a controller is online
	$tower_cab = array('del'=>0,'gnd'=>0,'twr'=>0);
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
	$afld_data['tower_cab'] = $tower_cab;
	$airfield_data[$afld] = $afld_data; // Dump airfield's data into airfield collection array
}
	
$reply_dataset['airfield_data'] = $airfield_data; // Dump airfield collection into reply array
	
function traffic_flow($rwy) { // Determines traffic flow direction based on active runways
	$traffic_flow = null;
	$rwy = intval($rwy);
	if(array_key_exists($rwy,$GLOBALS["flow_override"])) { // User defined custom traffic flow names... use those first
		$traffic_flow = $GLOBALS["flow_override"][$rwy];
	}
	else if (($rwy > 31)||($rwy < 4)) {
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
	
// *** GET STATIC DATA FROM SERVER ***
// Fetch controller position combines
$ctrl_data = data_read("controllers.dat","string");
//if(file_exists("data/controllers.dat")) {
if($ctrl_data != "") {	
	$ctrl_data = file_get_contents("data/controllers.dat");
	$ctrl_ts = substr($ctrl_data,3,strpos($ctrl_data,"-*--")-3);
	$ctrl_data = substr($ctrl_data,strpos($ctrl_data,"-*--")+4);
}
//else {
//	$ctrl_data = "";
//}
$reply_dataset['controllers'] = $ctrl_data;

// Fetch PIREPs
//if(file_exists("data/pirep.dat")) {
//	$pirep_data = file_get_contents("data/pirep.dat");
	$pirep_data = data_read("pirep.dat","string");
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
//}
$reply_dataset['pirep'] = str_replace("\n","",$pirep_display);

// Fetch Airfield Configuration
$afld_reply["9L@M2"] = $afld_reply["LAHSO"] = $afld_reply["AUTO"] = false; // Init variables
//if(file_exists("data/afld.dat")) {
	//$afld_data = file_get_contents("data/afld.dat");
	$afld_data = data_read("afld.dat","string");
	$afld_ts = substr($afld_data,3,strpos($afld_data,"-*--")-3);
	$afld_data = substr($afld_data,strpos($afld_data,"-*--")+4);
//	$afld_class = "";
//	if($afld_ts > (time()-120)) { // It's a recent update, so highlight the change to the user
//		$afld_class = "newupdate";
//	}
	$afld_data = str_replace("\n","",$afld_data); // Remove line breaks
	$afld_raw = str_replace("<br>AUTO ON","",$afld_data); // Remove the auto declaration
	$afld_raw = str_replace("<br>AUTO OFF","",$afld_raw); // Remove the auto declaration
	$afld_reply["raw"] = $afld_raw;
	global $afld_config_options;
	foreach($afld_config_options as $afld_config_option) {
		$afld_reply[$afld_config_option[0]] = is_numeric(strpos($afld_data,$afld_config_option[0] . " ON")) ? true : false;
	}
//	$afld_reply["9L@M2"] = is_numeric(strpos($afld_data,"9L@M2 ON")) ? true : false;
//	$afld_reply["LAHSO"] = is_numeric(strpos($afld_data,"LAHSO ON")) ? true : false;
//	$afld_reply["AUTO"] = is_numeric(strpos($afld_data,"AUTO ON")) ? true : false;
//}
//else {
//		$afld_reply["9L@M2"] = $afld_reply["LAHSO"] = $afld_reply["AUTO"] = false;
//}
$reply_dataset['config'] = $afld_reply;

// Fetch Manual Flow & Runway Configuration********************************************************************************************************************
$rwy_config = data_read("flow.dat","string");
//$rwy_config = str_getcsv($rwy_config,","); // Uncommented on 1/23/2022 to fix critical php warning
//if(file_exists("data/flow.dat")&&!$reply_dataset['config']['AUTO']) {
if((strlen($rwy_config) > 0)&&!$reply_dataset['config']['AUTO']) {
//	$file = fopen("data/flow.dat","r");
//	fgets($file);
//	$flow = fgets($file);
//	$arr = fgetcsv($file,1000,",");
//	$dep = fgetcsv($file,1000,",");
//	$flow = print_r($rwy_config);
	$rwy_config = preg_split('#\r?\n#', $rwy_config, 0); // Split lines into an array so they can be processed/accessed like fgetcsv
	$flow = array_key_exists(1,$rwy_config) ? $rwy_config[1] : ""; //preg_split('#\r?\n#', $rwy_config, 0)[1];
	$arr = array_key_exists(2,$rwy_config) ? str_getcsv($rwy_config[2],",") : "";
	$dep = array_key_exists(3,$rwy_config) ? str_getcsv($rwy_config[3],",") : "";
	$reply_dataset['airfield_data'][DEFAULT_AFLD_ICAO]['traffic_flow'] = preg_replace( "/\r|\n/", "", $flow ); //preg_replace removes endline char
	$reply_dataset['airfield_data'][DEFAULT_AFLD_ICAO]['apch_type'] = "";
	$reply_dataset['airfield_data'][DEFAULT_AFLD_ICAO]['apch_rwys'] = $arr;
	$reply_dataset['airfield_data'][DEFAULT_AFLD_ICAO]['dep_type'] = "";
	$reply_dataset['airfield_data'][DEFAULT_AFLD_ICAO]['dep_rwys'] = $dep;
}

// Fetch CIC Notes
//if(file_exists("data/cic.dat")) {
//	$cic_data = file_get_contents("data/cic.dat");
	$cic_data = data_read("cic.dat","string");
	if($cic_data != '') {
	$cic_ts = substr($cic_data,3,strpos($cic_data,"-*--")-3);
	$cic_data = substr($cic_data,strpos($cic_data,"-*--")+4);
	}
//	$cic_class = "";
//	if($cic_ts > (time()-120)) { // It's a recent update, so highlight the change to the user
//		$cic_class = "newupdate";
//	}
//}
//else {
//	$cic_data = "";
//}
$reply_dataset['cic'] = $cic_data; //str_replace("\n","",$cic_data); // Remove line breaks

// Fetch Large TRACON CIC Notes
//if(file_exists("data/a80cic.dat")) {
//	$a80cic_data = file_get_contents("data/a80cic.dat");
	$a80cic_data = data_read("a80cic.dat","string"); // <- I need to change the dat file and variable names to get away from the ZTL nomenclature
	if($a80cic_data != '') {
	$a80cic_ts = substr($a80cic_data,3,strpos($a80cic_data,"-*--")-3);
	$a80cic_data = substr($a80cic_data,strpos($a80cic_data,"-*--")+4);
	}
//	$a80cic_class = "";
//	if($a80cic_ts > (time()-120)) { // It's a recent update, so highlight the change to the user
//		$a80cic_class = "newupdate";
//	}
//}
//else {
//	$a80cic_data = "";
//}
$reply_dataset['a80cic'] = $a80cic_data; //str_replace("\n","",$a80cic_data); // Remove line breaks

// Fetch TMU Information
//if(file_exists("data/tmu.dat")) {
//	$tmu_data = file_get_contents("data/tmu.dat");
	$tmu_data = data_read("tmu.dat","string");
	if($tmu_data != '') {
	$tmu_ts = substr($tmu_data,3,strpos($tmu_data,"-*--")-3);
	$tmu_data = substr($tmu_data,strpos($tmu_data,"-*--")+4);
}
//	$tmu_class = "";
//	if($tmu_ts > (time()-120)) { // It's a recent update, so highlight the change to the user
//		$tmu_class = "newupdate";
//	}
//}
//else {
//	$tmu_data = "";
//}
$reply_dataset['tmu'] = $tmu_data; //str_replace("\n","",$tmu_data); // Remove line breaks

// Fetch Trips Information
$trips_reply["FTA"] = $afld_reply["FTD"] = false; // Init variables
//if(file_exists("data/trips.dat")) {
//	$trips_data = file_get_contents("data/trips.dat");
	$trips_data = data_read("trips.dat","string");
	$trips_ts = substr($trips_data,3,strpos($trips_data,"-*--")-3);
	$trips_data = substr($trips_data,strpos($trips_data,"-*--")+4);
	$trips_class = "";
//	if($trips_ts > (time()-120)) { // It's a recent update, so highlight the change to the user
//		$trips = "newupdate";
//	}
	$trips_reply["raw"] = $trips_data;
	$trips_reply["FTA"] = is_numeric(strpos($trips_data,"FTA ON")) ? true : false;
	$trips_reply["FTD"] = is_numeric(strpos($trips_data,"FTD ON")) ? true : false;
//}
//else {
//	$trips_reply["FTA"] = $afld_reply["FTD"] = false;
//}
$reply_dataset['trips'] = str_replace("\n","",$trips_reply); // Remove line breaks

// Fetch Departure Gate Information
//if(file_exists("data/gates.dat")) {
//	$gates_data = file_get_contents("data/gates.dat");
	$gates_data = data_read("gates.dat","string");
	$gates_arr = array();
	if($gates_data != "") {
	$gates_ts = substr($gates_data,3,strpos($gates_data,"-*--")-3);
	$gates_data = explode(':',substr($gates_data,strpos($gates_data,"-*--")+4));
//	$gates_arr = array();
	$gates_arr[] = substr($gates_data[1],0,-1);
	$gates_arr[] = substr($gates_data[2],0,-1);
	$gates_arr[] = substr($gates_data[3],0);
	}
//	$gates_class = "";
//	if($gates_ts > (time()-120)) { // It's a recent update, so highlight the change to the user
//		$gates_class = "newupdate";
//	}
//}
//else {
//	$gates_arr = "";
//}
$reply_dataset['gates'] = str_replace("\n","",$gates_arr); // Remove line breaks

// Fetch Departure Split Information
//if(file_exists("data/splits.dat")) {
//	$splits_data = file_get_contents("data/splits.dat");
	$splits_data = data_read("splits.dat","string");
	$splits_ts = substr($splits_data,3,strpos($splits_data,"-*--")-3);
	$splits_data = substr($splits_data,strpos($splits_data,"-*--")+4);
	$splits_json = json_decode($splits_data);
//}
//else {
//	$splits_json = "";
//}
$reply_dataset['splits'] = $splits_json;

// Fetch Override Information
//if(file_exists("data/override.dat")) {
//	$override_data = file_get_contents("data/override.dat");
	$override_data = data_read("override.dat","string");
	$override_json = json_decode($override_data);
//}
//else {
//	$override_json = "";
//}
$reply_dataset['override'] = $override_json;

// Load error messages into array
$reply_dataset['error'] = array($error,$error_msg);

// Echo JSON back to requester
echo json_encode($reply_dataset);

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