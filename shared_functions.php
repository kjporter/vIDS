<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: shared_functions.php
		Function: Catch-all for commonly used/general-purpose functions
		Created: 4/1/21
		Edited: 
		
		Changes: 
	*/

function fetch_my_url() { // Returns server URL
	$ssl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
	return $ssl."://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';
}

function is_sysad($vatsim_cid,$artcc_staff,$sso_endpoint) { // Returns system administrator authorization
	return ($artcc_staff || ($vatsim_cid == ACONST) || (strpos($sso_endpoint,"dev") !== false)) ? true : false;
}
?>