<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: common.php
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

function auto_version($file) { // Used to version files and bust CloudFlare's caching system for JS
  if(strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
    return $file;

  $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
  return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}
?>