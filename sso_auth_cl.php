<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: sso_auth.php
		Function: Handles VATSIM SSO OAUTH2 integration
		Created: 5/15/21
		Edited: 8/29/21
		
		Changes: Converted methods into OOP structure
		
		VATSIM OAUTH2 Provider: https://auth.vatsim.net/
		VATSIM Connect SSO Documentation: https://github.com/vatsimnetwork/documentation/blob/master/connect.md
	*/

include_once "config.php";	
include_once "common.php";
include_once "sso_variables.php";

$auth = new Security(fetch_my_url(),$sso_variables);
extract($auth->fetch_endpoint()); // Return SSO variables to be used by login button
$auth->init_sso(); // Attempt to init the sign on sequence
extract($auth->fetch_params(),EXTR_OVERWRITE); // Return authentication parameters

class Security{
	
	private $sso_vars = null;
	private $access_token = null;
	private $blacklisted = false;
	private $userData_json = null;
	private $alert_text = "";
	private $alert_style = "";
	private $valid_auth = false;
	private $artcc_staff = false;
	private $full_name = "";
	private $user_rating = "";
	private $vatsim_cid = "";
	
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
		$this->authorize(); // Use user data to verify system authorization (call VATUSA API)
		$this->write_access_log(); // Write access attempt to logfile
	}
	
	private function access_token() { // Check for an access token and request one if needed.
		if(isset($_SESSION['access_token'])) { // Session in progress, use existing token to get auth data
			$this->access_token = $_SESSION['access_token'];
		}
		elseif(isset($_GET['code'])) { // Need to get a new authentication - session does not exist
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
		}
		else {} // Do nothing, authentication sequence has not started
	}
	
	private function user_data() { // Fetch user data JSON using access token
		if($this->access_token != null) {
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $this->sso_vars['sso_endpoint'] . "/api/user");
			curl_setopt($ch,CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $this->access_token, "Accept: application/json"));
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_ENCODING, "gzip");
			$userData = curl_exec($ch);
			$this->userData_json = json_decode($userData,true);
			curl_close($ch);
		}
	}
	
	private function authorize() {
		if(isset($this->userData_json['data']['vatsim']['rating']['id'])) {
			// Check to see if the user is blacklisted before authorizing them
			//$blacklisted = false;
			$blacklist = "data/blacklist.dat";
			if(file_exists($blacklist)) {
				if(strpos(file_get_contents($blacklist),$this->userData_json['data']['cid']) !== false) {
					$this->blacklisted = true;
				}
			}
			// We've got the user data from the API, check to make sure they have a valid controller rating (> 0)
			//$dump .= "<br/>Controller rating: " . $userData_json['vatsim']['rating']['id'];
			if(!$this->blacklisted && ($this->userData_json['data']['vatsim']['rating']['id']>0)) { // The user logging in is at least an observer
				// Check the VATUSA API to see if the user is a controller at THIS facility before granting access
				// *Note* this API call will only work for facilities within the VATUSA region
				$cu = curl_init();
				curl_setopt($cu,CURLOPT_URL,"https://api.vatusa.net/v2/facility/" . FACILITY_ID . "/roster/both");
				curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
				curl_setopt($cu,CURLOPT_ENCODING, "gzip");
				curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
				$roster = curl_exec($cu); // Execute CURL
				curl_close($cu);
				if((strpos($roster,$this->userData_json['data']['cid']) !== false)||(strpos($this->sso_vars['sso_endpoint'],"dev") !== false)) { // Does the CID exist in the roster JSON... OR are we using the dev endpoint (fake CIDs)?
					$this->valid_auth = true;
					if(isset($this->userData_json['data']['personal']['name_full'])) { // This shouldn't really be necessary, but it prevents an error when the full name isn't available
						$this->full_name = $this->userData_json['data']['personal']['name_full'];
					}
					$this->user_rating = $this->userData_json['data']['vatsim']['rating']['short'];
					$this->vatsim_cid = $this->userData_json['data']['cid']; // Added to identify users so they can delete templates they create
					$vatusa_roster = json_decode($roster,true);
					foreach($vatusa_roster['data'] as $artcc_user) {
						if($artcc_user['cid'] == $this->userData_json['data']['cid']) {
							foreach($artcc_user['roles'] as $role) {
								if(($role['facility'] == FACILITY_ID)&&(in_array($role['role'],array('ATM','DATM','FE','TA','WM','EC')))) {
									$this->artcc_staff = true;
								}
							}
						}
					}
				}
				else {
					$this->alert_text = "Insufficient privileges to use this system (must be a home or visiting controller at this facility). Contact a member of your ARTCC staff.";
					$this->alert_style = "alert alert-danger alert-visible";						
				}
			}
			elseif($blacklisted) {
				$this->alert_text = "Your access to this system has been revoked. Contact a member of your ARTCC staff.";
				$this->alert_style = "alert alert-danger alert-visible";				
			}
			else {
				$this->alert_text = "Insufficient privileges to use this system (must have at least an observer ATC rating). Contact a member of your ARTCC staff.";
				$this->alert_style = "alert alert-danger alert-visible";		
			}
		}
		else { // Condition occurs before login attempt
			//$this->alert_text = "Authentication attempt failed - please try again later.";
			//$this->alert_style = "alert alert-danger alert-visible";					
		}
	}
	
	public function fetch_params() { // Returns parameter array
		$vars = Array('alert_text','alert_style','valid_auth','artcc_staff','full_name','user_rating','vatsim_cid');
		$params = Array();
		foreach($vars as $var) {
			$params[$var] = $this->$var;
		}
		return $params;
	}
	
	private function write_access_log() { // Authentication logging
		$log_str = "[" . date("YmdHms") . "] CID " . $this->userData_json['data']['cid'] . "/" . $this->userData_json['data']['personal']['name_full'] . ": Login attempt ";
		$log_str .= $this->valid_auth ? 'successful' : 'failed';
		$log_str .= $this->blacklisted ? ' - blacklisted' : '';
		$log_str .= " (" . $_SERVER['REMOTE_ADDR'] . ")";
		// Write action to logfile
		file_put_contents("data/access.log",$log_str . "\n",FILE_APPEND);		
	}
	
	private function terminate_session() { // Not currently in-use, but destroys the session variable effectively ending the authorization
		session_destroy();
		$this->alert_text = "User logged out.";
		$this->alert_style = "alert alert-danger";
	}
}
?>