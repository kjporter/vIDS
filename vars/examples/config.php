<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: config.php
		Function: Defines system configuration parameter & global variables
		Created: 8/12/21
		Edited: 
		
		Changes: 
		
		Notes: Use this file to configure vIDS for your facility. Limitations: this configuration does not impact static text and references in modal boxes - you need to edit modal.php to adjust this information to fit your
		ARTCC or facility. 
		
		To set your ARTCC logo - place a file named "logo.png" in the img/ directory
	*/
	
//namespace vids;

// Toggles debug mode on/off. Debug mode is NOISY - it throws additional execution data and dumps the reply JSON in a viewable container
define('DEBUG', false);
// Set persistent storage mode. False = stores data in .dat files on server. True = stores data in database.
define('USE_DB', false);
// Enable bug reporting system for users (stores reported bugs in rss feed)
define('BUG_REPORTING', true);
// Set to true if traffic management module is available
define('TRAFFIC_MANAGEMENT', false);
// URL for production site
define('PROD_URL', 'ztlarcc.org');
// Grant admin priveleges (optional, enter CID). Grants 1 user admin priveleges without being an ARTCC staff member.
define('ACONST', 999);
// Identifier for the overarching facility (normally the ARTCC). Ex: ZTL
define('FACILITY_ID', 'ZTL');
// Default major airfield ID. Ex: ATL (3-letter FAA ID)
define('DEFAULT_AFLD_ID', 'ATL');
// Default major airfield ICAO ID. Ex: KATL (4-letter ICAO ID)
define('DEFAULT_AFLD_ICAO', 'KATL');
// Large TRACON ID. Ex: A80
define('TRACON_ID', 'A80');
// Large TRACON long name. Ex: A80 Atlanta Large TRACON
define('TRACON_LONG_NAME', 'A80 Atlanta Large TRACON');
// Traffic flow direction - displayed in the IDS next to the ATIS ID (Ex. EAST/WEST). Create custom flow names by adding them to this array in format RWY ID=>FLOW NAME (Ex. '09'=>'EAST'). Only enter runway numbers - do not enter left/right/center characters.
$flow_override = array('08'=>'EAST','09'=>'EAST','10'=>'EAST','26'=>'WEST','27'=>'WEST','28'=>'WEST');
// Arrival runways
$arrival_runways = array('08L','09R','10','08R','09L','26R','27L','28','26L','27R');
$approach_types = array('VIS','ILS');
// Departure runways
$departure_runways = array('08R','09L','10','08L','09R','26L','27R','28','26R','27L');
$departure_types = array('RV','ROTG');
// Airfield configuration options
// This allows you to have custom airfield configuration options that are switchable (on/off) and displayed under "Afld config"
// Format = array("ShortNameNoSpaces","Long Name","Description");
$afld_config_options = array(array("FTA","Full triple arrivals?",""),array("FTD","Full triple departures?",""),array("9L@M2","9L departures @ intersection M2",""),array("LAHSO","LAHSO","Land and hold short operations in effect."));
// Controller positions - used to build controller/position combination grids & selects
// 1st index in each array is the display name, 2nd index are the positions, 3rd index are the possible combinations (don't duplicate items from 2nd index)
// You can have 8 total staffing areas. pos0-5 are displayed on the local view and pos2-7 are displayed on the TRACON view
$pos0 = array("Clearance Delivery",array('CD-1'=>'CD-1','CD-2'=>'CD-2','FD'=>'FD'),array('GC-N'=>'GC-N','LC-1'=>'LC-1','LC-2'=>'LC-2','N'=>'N','C43'=>'C43'));
$pos1 = array("Ground Control",array('GC-N'=>'GC-N','GC-C'=>'GC-C','GC-S'=>'GC-S','GM'=>'GM'),array('LC-1'=>'LC-1','LC-2'=>'LC-2','LC-3'=>'LC-3','LC-4'=>'LC-4','LC-5'=>'LC-5','N'=>'N','C43'=>'C43'));
$pos2 = array("Local Control",array('LC-1'=>'LC-1','LC-2'=>'LC-2','LC-3'=>'LC-3','LC-4'=>'LC-4','LC-5'=>'LC-5'),array('N'=>'N','C43'=>'C43'));
$pos3 = array(TRACON_ID . " Departure",array('N'=>'N','S'=>'S','I'=>'I'),array('C43'=>'C43'));
$pos4 = array(TRACON_ID . " Satellite",array('P'=>'P','F'=>'F','X'=>'X','G'=>'G','Q'=>'Q'),array('N'=>'N','C43'=>'C43'));
$pos5 = array(TRACON_ID . " AR",array('O'=>'O','V'=>'V','A'=>'A'),array('N'=>'N','H'=>'H','D'=>'D','C43'=>'C43'));
$pos6 = array(TRACON_ID . " TAR",array('H'=>'H','D'=>'D','L'=>'L','Y'=>'Y'),array('N'=>'N','S'=>'S','C43'=>'C43'));
$pos7 = array(TRACON_ID . " Outer",array('M'=>'M','W'=>'W','Z'=>'Z','R'=>'R','E'=>'E','3E'=>'3E'),array('N'=>'N','P'=>'P','F'=>'F','X'=>'X','G'=>'G','C43'=>'C43','C49'=>'C49','C16'=>'C16','C19'=>'C19','C21'=>'C21','C09'=>'C09'));
// Departure gates - define the departure gate names for your airspace. This is used for the display in the upper right corner of the local and large TRACON
// displays to turn individual gates on/off and define RNAV fixes for routing traffic through
$departure_gates = array('N1','N2','W2','W1','S2','S1','E1','E2');
// This is used to build a UI to assign departure gates to a specific controller. Format: array('position name','position name',etc...). Use STARS position IDs - no spaces!
$departure_positions = $pos3[1];
// Configurable list of airfields (for TRACON/ARTCC view) - you may create as many of these as you'd like, the view is optimized for multiples of 3
// For the 'id' field, ensure that you use the 4-character ICAO airfield ID (Ex. KATL)
$satellite_fields = array(
array("id"=>"KPDK","name"=>"DeKalb-Peachtree (PDK)","hours"=>"1130–0400Z‡ Mon–Fri, 1200–0400Z‡ Sat–Sun","MF"=>"1130-0400","SS"=>"1200-0400","DST_Adjust"=>true),
array("id"=>"KFTY","name"=>"Fulton County (FTY)","hours"=>"Attended continuously","MF"=>"0000-2400","SS"=>"0000-2400","DST_Adjust"=>true),
array("id"=>"KMGE","name"=>"Dobbins ARB (MGE)","hours"=>"1200–0400Z‡","MF"=>"1200-0400","SS"=>"1200-0400","DST_Adjust"=>true),
array("id"=>"KRYY","name"=>"Cobb Co/McCollum (RYY)","hours"=>"1200–0400Z‡","MF"=>"1200-0400","SS"=>"1200-0400","DST_Adjust"=>true),
array("id"=>"KLZU","name"=>"Gwinnett Co (LZU)","hours"=>"1200–0200Z‡","MF"=>"1200-0200","SS"=>"1200-0200","DST_Adjust"=>true),
array("id"=>"KAHN","name"=>"Athens (AHN)","hours"=>"1300–0100Z‡","MF"=>"1300-0100","SS"=>"1300-0100","DST_Adjust"=>true),
array("id"=>"KMCN","name"=>"Macon Regional (MCN)","hours"=>"1300–0100Z‡","MF"=>"1300-0100","SS"=>"1300-0100","DST_Adjust"=>true),
array("id"=>"KWRB","name"=>"Robins AFB (WRB)","hours"=>"Attended continuously","MF"=>"0000-2400","SS"=>"0000-2400","DST_Adjust"=>true),
array("id"=>"KCSG","name"=>"Columbus (CSG)","hours"=>"1400–0200Z‡","MF"=>"1400-0200","SS"=>"1400-0200","DST_Adjust"=>true)
);

// ************************************ DO NOT EDIT BELOW THIS LINE ************************************
include_once dirname(__DIR__) . "/js_support.php";
/*
$positions = array($pos2,$pos1,$pos0,$pos3,$pos4,$pos5,$pos6,$pos7);
$satellites = array();
foreach($satellite_fields as $sat) {
	$satellites[] = $sat['id'];
}
$afld_config_ids = array();
foreach($afld_config_options as $afld_config_option) {
	$afld_config_ids[] = $afld_config_option[0];
}
$airfields = Array(DEFAULT_AFLD_ICAO);
$airfields = array_merge($airfields,$satellites);

$rwy_flows = array(); // Create an array where [flow][rwy][typ]
foreach($flow_override as $rwy=>$flow_rwy) {
	if(!array_key_exists($flow_rwy,$rwy_flows)) {
		$rwy_flows[$flow_rwy] = array('id'=>array(),'arr'=>array(),'dep'=>array());
	}
	if(!in_array($rwy,$rwy_flows[$flow_rwy]['id'])) {
		$rwy_flows[$flow_rwy]['id'][] = $rwy;
	}
	foreach($arrival_runways as $arrival_runway) {
		if(strpos($arrival_runway,strval($rwy)) !== false) {
			$rwy_flows[$flow_rwy]['arr'][] = $arrival_runway;
		}
	}
	foreach($departure_runways as $departure_runway) {
		if(strpos($departure_runway,strval($rwy)) !== false) {
			$rwy_flows[$flow_rwy]['dep'][] = $departure_runway;
		}
	}
}

function js_globals() { // Translates PHP globals into JS globals
	global $positions;
	global $satellites;
	global $departure_gates;
	global $departure_positions;
	global $afld_config_ids;
	global $approach_types;
	global $departure_types;
	global $flow_override;
	global $rwy_flows;
	$js_globals = "	const defaultAirfield = '" . DEFAULT_AFLD_ICAO . "';
					const positions = new Array('" . implode("','",array_unique(array_merge($positions[0][1],$positions[1][1],$positions[2][1],$positions[3][1],$positions[4][1],$positions[5][1],$positions[6][1],$positions[7][1]))) . "');
					const underlying_fields = new Array('" . implode("','",$satellites) . "');
					const departure_gates = new Array('" . implode("','",$departure_gates) . "');
					const departure_positions = new Array('" . implode("','",$departure_positions) . "');
					const afld_config_options = new Array('" . implode("','",$afld_config_ids) . "');
					const apch_types = new Array('" . implode("','",$approach_types) . "');
					const dep_types = new Array('" . implode("','",$departure_types) . "');
					const traffic_flows = new Array('" . implode("','",array_unique($flow_override)) . "');";
	$rwy_flow = "	const rwy_flows = {";
	foreach($rwy_flows as $flow=>$ar) {
		$rwy_flow .= "$flow : { arr : ['" . implode("','",$ar['arr']) . "'], dep : ['" . implode("','",$ar['dep']) . "'], id : ['" . implode("','",$ar['id']) . "'] },";
	}
	$rwy_flow .= " };";
	$js_globals .= $rwy_flow;				
	return $js_globals;
}
*/
?>