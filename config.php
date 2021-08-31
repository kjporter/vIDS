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
		
	*/
	
//namespace vids;

// Toggles debug mode on/off. Debug mode is NOISY - it throws additional execution data and dumps the reply JSON in a viewable container
define('DEBUG', true);
// URL for production site
define('PROD_URL', 'ztlarcc.org');
// Authentication variables - do not edit
define('ACONST', pow(56.16379,2)*477);
// Identifier for the overarching facility (normally the ARTCC). Ex: ZTL
define('FACILITY_ID', 'ZTL');
// Default major airfield ID. Ex: ATL
define('DEFAULT_AFLD_ID', 'ATL');
// Large TRACON ID. Ex: A80
define('TRACON_ID', 'A80');
// Large TRACON long name. Ex: A80 Atlanta Large TRACON
define('TRACON_LONG_NAME', 'A80 Atlanta Large TRACON');
// Configurable list of airfields (for TRACON/ARTCC view)
$airfields = array('KATL','KPDK','KFTY','KMGE','KRYY','KLZU','KMCN','KWRB','KAHN','KCSG'); 
$pdk = array("id"=>"KPDK","name"=>"Peachtree-Dekalb (PDK)","hours"=>"1130–0400Z‡ Mon–Fri, 1200–0400Z‡ Sat–Sun","MF"=>"1130-0400","SS"=>"1200-0400","DST_Adjust"=>true);
$fty = array("id"=>"KFTY","name"=>"Fulton County (FTY)","hours"=>"Attended continuously","MF"=>"0000-2400","SS"=>"0000-2400","DST_Adjust"=>true);
$mge = array("id"=>"KMGE","name"=>"Dobbins ARB (MGE)","hours"=>"1200–0400Z‡","MF"=>"1200-0400","SS"=>"1200-0400","DST_Adjust"=>true);
$ryy = array("id"=>"KRYY","name"=>"Cobb Co/McCollum (RYY)","hours"=>"1200–0400Z‡","MF"=>"1200-0400","SS"=>"1200-0400","DST_Adjust"=>true);
$lzu = array("id"=>"KLZU","name"=>"Gwinnette Co (LZU)","hours"=>"1200–0200Z‡","MF"=>"1200-0200","SS"=>"1200-0200","DST_Adjust"=>true);
$ahn = array("id"=>"KAHN","name"=>"Athens (AHN)","hours"=>"1300–0100Z‡","MF"=>"1300-0100","SS"=>"1300-0100","DST_Adjust"=>true);
$mcn = array("id"=>"KMCN","name"=>"Macon Regional (MCN)","hours"=>"1300–0100Z‡","MF"=>"1300-0100","SS"=>"1300-0100","DST_Adjust"=>true);
$wrb = array("id"=>"KWRB","name"=>"Robins AFB (WRB)","hours"=>"Attended continuously","MF"=>"0000-2400","SS"=>"0000-2400","DST_Adjust"=>true);
$csg = array("id"=>"KCSG","name"=>"Columbus (CSG)","hours"=>"1400–0200Z‡","MF"=>"1400-0200","SS"=>"1400-0200","DST_Adjust"=>true);
$a80sat = array($pdk,$fty,$mge,$ryy,$lzu,$ahn,$mcn,$wrb,$csg);

// Do not edit below this line - translates PHP globals into JS globals
function js_globals() {
	$js_globals = "	const defaultAirfield = 'K" . DEFAULT_AFLD_ID . "';
					";
	return $js_globals;
}
?>