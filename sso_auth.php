<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: sso_auth.php
		Function: Handles VATSIM SSO OAUTH2 integration
		Created: 5/15/21
		Edited: 
		
		Changes: 
		
		VATSIM OAUTH2 Provider: https://auth.vatsim.net/
		VATSIM Connect SSO Documentation: https://github.com/vatsimnetwork/documentation/blob/master/connect.md
	*/

include_once "config.php";	
include_once "shared_functions.php";
include_once "sso_variables.php";

$arr_var = null;
if(strpos(fetch_my_url(),"www.ztlarcc.org")!== false) {
	$arr_var = 2;
}
elseif(strpos(fetch_my_url(),"127.0.0.1")!== false) {
	$arr_var = 0;
}
else {
	$arr_var = 1;
}

$client_id = $sso_variables[$arr_var][0];
$client_secret = $sso_variables[$arr_var][1];
$redirect_uri = $sso_variables[$arr_var][2];
$sso_endpoint = $sso_variables[$arr_var][3];

//$dump = "";
$alert_text = "";
$alert_style = "";
//$full_name = "";
$valid_auth = false;
$access_token = null;

session_start();

if(isset($_SESSION['access_token'])) { // Session in progress, use existing token to get auth data
	$access_token = $_SESSION['access_token'];
	//echo "ACCESS TOKEN FROM SESSION:";
	//print_r($access_token);
}
elseif(isset($_GET['code'])) { // Need to get a new authentication - session does not exist
	//echo "CODE";
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $sso_endpoint . "/oauth/token");
	curl_setopt($ch,CURLOPT_POST, true);
	//curl_setopt($ch,CURLOPT_HEADER,true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query(array ('grant_type'=>'authorization_code', 'client_id'=>$client_id, 'client_secret'=>$client_secret, 'redirect_uri'=>$redirect_uri, 'code'=>$_GET['code'])));
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	$token = curl_exec($ch);
	curl_close($ch);
	//echo "Raw Token: " . $token;
	// We've got the token, now extract the pieces needed for the API call
	// VATSIM API Documentation: https://api.vatsim.net/api/
	$token_json = json_decode($token,true);
	$access_token = $token_json['access_token'];
	$_SESSION['access_token'] = $token_json['access_token'];
	//echo "JSON Token: " . $token_json;
	//print_r($token_json);
	//$dump = $token_json['access_token'];
}
else {} // Do nothing, authentication sequence has not started

$full_name = "";
$user_rating = "";
$vatsim_cid = "";
if($access_token != null) {
	//echo "CHECK TOKEN";
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $sso_endpoint . "/api/user");
	curl_setopt($ch,CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $access_token, "Accept: application/json"));
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	$userData = curl_exec($ch);
	$userData_json = json_decode($userData,true);
	curl_close($ch);
	if(isset($userData_json['data']['vatsim']['rating']['id'])) {
		// Check to see if the user is blacklisted before authorizing them
		$blacklisted = false;
		$blacklist = "data/blacklist.dat";
		if(file_exists($blacklist)) {
			if(strpos(file_get_contents($blacklist),$userData_json['data']['cid']) !== false) {
				$blacklisted = true;
			}
		}
		//print_r($userData_json['data']['vatsim']['rating']['id']);
		// We've got the user data from the API, check to make sure they have a valid controller rating (> 0)
		//$dump .= "<br/>Controller rating: " . $userData_json['vatsim']['rating']['id'];
		if(!$blacklisted && ($userData_json['data']['vatsim']['rating']['id']>0)) { // The user logging in is at least an observer
			// Check the VATUSA API to see if the user is a controller at THIS facility before granting access
			// *Note* this API call will only work for facilities within the VATUSA region
			$cu = curl_init();
			curl_setopt($cu,CURLOPT_URL,"https://api.vatusa.net/v2/facility/" . FACILITY_ID . "/roster/both");
			curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
			curl_setopt($cu, CURLOPT_ENCODING, "gzip");
			curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
			$roster = curl_exec($cu); // Execute CURL
			curl_close($cu);
			if((strpos($roster,$userData_json['data']['cid']) !== false)||(strpos($sso_endpoint,"dev") !== false)) { // Does the CID exist in the roster JSON... OR are we using the dev endpoint (fake CIDs)?
			$valid_auth = true;
			if(isset($userData_json['data']['personal']['name_full'])) { // This shouldn't really be necessary, but it prevents an error when the full name isn't available
				$full_name = $userData_json['data']['personal']['name_full'];
			}
			$user_rating = $userData_json['data']['vatsim']['rating']['short'];
			$vatsim_cid = $userData_json['data']['cid']; // Added to identify users so they can delete templates they create
			}
			else {
				$alert_text = "Insufficient privileges to use this system (must be a home or visiting controller at this facility). Contact a member of your ARTCC staff.";
				$alert_style = "alert alert-danger alert-visible";						
			}
		}
		elseif($blacklisted) {
			$alert_text = "Your access to this system has been revoked. Contact a member of your ARTCC staff.";
			$alert_style = "alert alert-danger alert-visible";				
		}
		else {
			$alert_text = "Insufficient privileges to use this system (must have at least an observer ATC rating). Contact a member of your ARTCC staff.";
			$alert_style = "alert alert-danger alert-visible";		
		}
	}
}
/*
else {
	session_destroy();
	$alert_text = "Authentication error.";
	$alert_style = "alert alert-danger";
}
*/
?>