<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: vatsim_connect.php
		Function: Implements VATSIM Connect SSO OAUTH2
		Created: 9/4/21
		Edited: 
		
		Changes:
		
		VATSIM OAUTH2 Provider: https://auth.vatsim.net/
		VATSIM Connect SSO Documentation: https://github.com/vatsimnetwork/documentation/blob/master/connect.md
	*/

include_once "vars/config.php";	
include_once "common.php";
include_once "vars/sso_variables.php";


class VATSIM_Connect {
	
	protected $sso_vars = null;
	protected $access_token = null;
	protected $userData_json = null;
	protected $dump = ""; // Debug string
	
	function __construct($my_url,$sso_variables) {
		foreach($sso_variables as $sso_var) { // Determine SSO variable set to use based on server URL
			if(strpos($my_url,$sso_var['redirect_uri'])!== false) {
				$this->sso_vars = $sso_var;
			}
		}
	}
	
	public function fetch_endpoint() { // Returns VATSIM SSO endpoint variables
		return $this->sso_vars;
	}
	
	public function init_sso() { // Start the SSO sequence
		session_start();
		$this->access_token(); // Verify or get a token
		$this->user_data(); // Use token to authenticate and get user data
	}
	
	protected function access_token() { // Check for an access token and request one if needed.
		if(isset($_SESSION['access_token'])) { // Session in progress, use existing token to get auth data
			$this->access_token = $_SESSION['access_token'];
			$this->dump = "Session in-progress, using existing access token<br/>";
		}
		elseif(isset($_GET['code'])) { // Need to get a new authentication - session does not exist
			$this->dump = "No session found, requesting a new access token<br/>";
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $this->sso_vars['sso_endpoint'] . "/oauth/token");
			curl_setopt($ch,CURLOPT_POST, true);
			curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query(array ('grant_type'=>'authorization_code', 'client_id'=>$this->sso_vars['client_id'], 'client_secret'=>$this->sso_vars['client_secret'], 'redirect_uri'=>$this->sso_vars['redirect_uri'], 'code'=>$_GET['code'])));
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch,CURLOPT_ENCODING, "gzip");
			$token = curl_exec($ch);
			curl_close($ch);
			// We've got the token, now extract the pieces needed for the API call
			// VATSIM API Documentation: https://api.vatsim.net/api/
			$token_json = json_decode($token,true);
			$this->access_token = $token_json['access_token'];
			$_SESSION['access_token'] = $token_json['access_token'];
			$this->dump .= "Access token obtained and user session created!<br/>";
		}
		else {} // Do nothing, authentication sequence has not started
		if(DEBUG) { echo $this->dump; }
	}
	
	protected function user_data() { // Fetch user data JSON using access token
		if($this->access_token != null) {
			$this->dump = "Using access token to fetch user data<br/>";
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $this->sso_vars['sso_endpoint'] . "/api/user");
			curl_setopt($ch,CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $this->access_token, "Accept: application/json"));
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_ENCODING, "gzip");
			$userData = curl_exec($ch);
			$this->userData_json = json_decode($userData,true);
			curl_close($ch);
			$this->dump .= $userData;
		}
		if(DEBUG) { echo $this->dump; }
	}
	
	protected function terminate_session() { // Not currently in-use, but destroys the session variable effectively ending the authorization
		session_destroy();
	}
}
?>