<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: config.php
		Function: Defines system configuration parameter & global variables
		Created: 8/12/21
		Edited: 
		
		Changes: 
		
		Notes: Use this file to configure vIDS for your facility. Limitations: this configuration does not impact static text and references in modal boxes - you need to edit modal.php to adjust this information to fit your
		ARTCC or facility. Editing this file will not update/change the controller positions. A future update to vIDS will allow fully configurable control of the controller selections.
		
		To set your ARTCC logo - place a file named "logo.png" in the img/ directory
	*/
	
//namespace vids;

// Toggles debug mode on/off. Debug mode is NOISY - it throws additional execution data and dumps the reply JSON in a viewable container
define('DEBUG', false);
// Set persistent storage mode. False = stores data in .dat files on server. True = stores data in database.
define('USE_DB', false);
// URL for production site
define('PROD_URL', 'ztlarcc.org');
// Authentication variables - do not edit
define('ACONST', intval(pow(56.16379,2)*477));
//define('ACONST', 10000009/2*2);
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
// Traffic flow direction - displayed in the IDS next to the ATIS ID (Ex. EAST/WEST). By default, the IDS will use an algorithm based on runway ID/mag heading to determine the traffic flow. You can override this or create custom flow names by adding them to this array in format RWY ID=>FLOW NAME (Ex. '09'=>'EAST'). Only enter runway numbers - do not enter left/right/center characters.
$flow_override = array();
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
$pos7 = array(TRACON_ID . " Outer",array('M'=>'M','W'=>'W','Z'=>'Z','R'=>'R','E'=>'E','3E'=>'3E'),array('N'=>'N','P'=>'P','F'=>'F','X'=>'X','G'=>'G','C43'=>'C43','C49'=>'C49'));
// Departure gates - define the departure gate names for your airspace. This is used for the display in the upper right corner of the local and large TRACON
// displays to turn individual gates on/off and define RNAV fixes for routing traffic through
$departure_gates = array('N1','N2','W2','W1','S2','S1','E1','E2');
// Configurable list of airfields (for TRACON/ARTCC view) - you may create as many of these as you'd like, the view is optimized for multiples of 3
// For the 'id' field, ensure that you use the 4-digit ICAO airfield ID (Ex. KATL)
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
// Modal configuration: the modal boxes hold all of the static display information - airspace diagrams, frequencies, checklists, etc. You can configure all
// of the static information here.
// Weather config
$modal_wx_radar = "https://radar.weather.gov/ridge/lite/KFFC_loop.gif"; // Must point to a graphics file (Ex. jpg, gif)
$modal_wx_video = "https://www.weather.gov/media/ztl/ZTLPreDutyVideo.mp4"; // Link to the CWSU video briefing




// ************************************ DO NOT EDIT BELOW THIS LINE ************************************
$positions = array($pos2,$pos1,$pos0,$pos3,$pos4,$pos5,$pos6,$pos7);
$satellites = array();
foreach($satellite_fields as $sat) {
	$satellites[] = $sat['id'];
}
$airfields = Array(DEFAULT_AFLD_ICAO);
$airfields = array_merge($airfields,$satellites);

function js_globals() { // Translates PHP globals into JS globals
	global $positions;
	global $satellites;
	$js_globals = "	const defaultAirfield = '" . DEFAULT_AFLD_ICAO . "';
					const positions = new Array('" . implode("','",array_unique(array_merge($positions[0][1],$positions[1][1],$positions[2][1],$positions[3][1],$positions[4][1],$positions[5][1],$positions[6][1],$positions[7][1]))) . "');
					const underlying_fields = new Array('" . implode("','",$satellites) . "');";
	return $js_globals;
}
?>