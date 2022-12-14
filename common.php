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
	return ($artcc_staff || (intval($vatsim_cid) == ACONST) || (strpos($sso_endpoint,"dev") !== false)) ? true : false;
}

function get_version() { // Fetches software version from package.json and returns it in a string
	$return_val = "";
	if(file_exists("package.json")) {
		$str = file_get_contents("package.json");
		$json = json_decode($str,true);
		if(array_key_exists('version',$json)) {
			$return_val = $json['version'];
		}
	}
	return $return_val;
}

function auto_version($file) { // Used to version files and bust CloudFlare's caching system for JS
	if(CACHE_BUSTER) {
  		if(strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
    		return $file;

  		$mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
  		return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
	}
	return $file;
}

// Cachebuster for JS on Cloudflare
$documentRoot = '';
if(strpos(basename(__DIR__),'.') !== true) {
	$documentRoot = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'?'));
	if(strlen($documentRoot) < 1) {
		$documentRoot = $_SERVER['REQUEST_URI'];
	}
}
if(strlen($documentRoot) < 1) {
	$documentRoot = '/';
}

// Picks a random image from the $imagesDir to display in the landing page background
$imagesDir = 'img/bg/';
if(is_dir($imagesDir)) {
	$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
	$randomImage = $images[array_rand($images)];
}
?>