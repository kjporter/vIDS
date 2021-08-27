<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: config.php
		Function: Defines system configuration parameter & global variables
		Created: 8/12/21
		Edited: 
		
		Changes: 
		
	*/
	
//namespace vids;

// Toggles debug mode on/off. Debug mode is NOISY - it throws additional execution data and dumps the reply JSON in a viewable container
define('DEBUG', false);
// Admin's CID - grants some special priveleges
define('ADMIN', '10000009');
define('ACONST', pow(56.16379,2)*477);
// Identifier for the overarching facility (normally the ARTCC). Ex: ZTL
define('FACILITY_ID', 'ZTL');
// Default major airfield ID. Ex: ATL
define('DEFAULT_AFLD_ID', 'ATL');
// Large TRACON ID. Ex: A80
define('TRACON_ID', 'A80');
// Large TRACON long name. Ex: A80 Atlanta Large TRACON
define('TRACON_LONG_NAME', 'A80 Atlanta Large TRACON');


// Do not edit below this line - translates PHP globals into JS globals
function js_globals() {
	$js_globals = "	const ADMIN = '" . ADMIN . "';
					";
	return $js_globals;
}
?>