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
	
include_once "shared_functions.php";

$sso_endpoint = "https://auth-dev.vatsim.net";

if(strpos(fetch_my_url(),"127.0.0.1")!== false) {
	//Settings for testing on Kyle's local machine
	$client_id = "153";
	$client_secret = "YChl2khdeVii0D9nTdJTmFxApHiSkZGMwHxsmBFY";
	$redirect_uri = "http://127.0.0.1/ids";
}
else {
	// Settings for demo on kplink.net server
	$client_id = "175";
	$client_secret = "ixw9I1haGipndSO2Q4Qwh7nBuCQKog2XcI5uRjjw";
	$redirect_uri = "https://kplink.net/ids";
}

//$dump = "";
$alert_text = "";
$alert_style = "";
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
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_POSTFIELDS,array ('grant_type'=>'authorization_code', 'client_id'=>$client_id, 'client_secret'=>$client_secret, 'redirect_uri'=>$redirect_uri, 'code'=>$_GET['code']));
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	$token = curl_exec($ch);
	curl_close($ch);
	// We've got the token, now extract the pieces needed for the API call
	// VATSIM API Documentation: https://api.vatsim.net/api/
	$token_json = json_decode($token,true);
	$access_token = $token_json['access_token'];
	$_SESSION['access_token'] = $token_json['access_token'];
	//print_r($token_json);
	//$dump = $token_json['access_token'];
}
else {
	//This should not happen
}
if($access_token != null) {
	//echo "CHECK TOKEN";
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $sso_endpoint . "/api/user");
	curl_setopt($ch,CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $access_token, "Accept: application/json"));
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	$userData = curl_exec($ch);
	$userData_json = json_decode($userData,true);
	curl_close($ch);
	if(isset($userData_json['data']['vatsim']['rating']['id'])) {
		//print_r($userData_json['data']['vatsim']['rating']['id']);
		// We've got the user data from the API, check to make sure they have a valid controller rating (> 0)
		//$dump .= "<br/>Controller rating: " . $userData_json['vatsim']['rating']['id'];
		if($userData_json['data']['vatsim']['rating']['id']>0) {
			// Success... the person is at least an observer
			$alert_text = "Authentication successful - access granted.";
			$alert_style = "alert alert-success alert-visible";
			$valid_auth = true;
		}
		else {
			$alert_text = "Insufficient privileges to use this system - contact your ARTCC FE.";
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