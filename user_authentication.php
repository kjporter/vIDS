<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: user_authentication.php
		Function: Extends VATSIM_Connect class and implements user authorization
		Created: 5/15/21
		Edited: 9/4/21
		
		Changes:
	*/

include_once "vars/config.php";	
include_once "common.php";
include_once "vatsim_connect.php";	
include_once "data_management.php";	
include_once "vars/sso_variables.php";

if(!isset($db)) {
	// Start DB connection and select the DB
	$db = new MySQL_db(fetch_my_url(),$db_variables);
}

class Security extends VATSIM_Connect {
	
	private $rejectlisted = false;
	private $allowlisted = false;
	private $alert_text = "";
	private $alert_style = "";
	private $valid_auth = false;
	private $artcc_staff = false;
	private $full_name = "";
	private $user_rating = "";
	private $vatsim_cid = "";
	private $auth_cookie = "vidsAuth";
	private $auth_cookie_token = null;
	private $persistent_login_duration = 86400 * 30; // 86400 = 1 day
	
	public function init_sso() { // Start the SSO sequence
		session_start();
		$_SESSION["vids_authenticated"] = false;
		if(!$this->handleCookie()) { // Prevents SSO sequence if user has a valid cookie
			//echo "VATSIM CONNECT AUTH";
			$this->access_token(); // Verify or get a token
			$this->user_data(); // Use token to authenticate and get user data
		}
		$this->authorize(); // Use user data to verify system authorization (call VATUSA API)
		$this->write_access_log(); // Write access attempt to logfile
	}
	
	private function authorize() {
		//echo "VATUSA API PULL";
		$this->dump = "";
		if(isset($this->userData_json['data']['vatsim']['rating']['id'])) {
			// Check to see if the user is rejectlisted before authorizing them
			//$rejectlisted = false;
			$this->dump .= "Checking to see if user is authorized... <br/>";
			//$rejectlist = "data/rejectlist.dat";
			//if(file_exists($rejectlist)) {
			//	if(strpos(file_get_contents($rejectlist),$this->userData_json['data']['cid']) !== false) {
				if(strpos(data_read('rejectlist.dat','string'),$this->userData_json['data']['cid']) !== false) {
					$this->rejectlisted = true;
					$this->dump .= "User rejectlisted :(<br/>";
				}
				elseif(strpos(data_read('allowlist.dat','string'),$this->userData_json['data']['cid']) !== false) {
					$this->allowlisted = true;
					$this->dump .= "User allowlisted :)<br/>";
				}				
				
			//}
			// We've got the user data from the API, check to make sure they have a valid controller rating (> 0)
			//$dump .= "<br/>Controller rating: " . $userData_json['vatsim']['rating']['id'];
			if(!$this->rejectlisted && (($this->userData_json['data']['vatsim']['rating']['id']>0)||$this->allowlisted)) { // The user logging in is at least an observer
				$this->dump .= "User authorized :)<br/>";
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
				if((strpos($roster,$this->userData_json['data']['cid']) !== false)||(strpos($this->sso_vars['sso_endpoint'],"dev") !== false)||$this->allowlisted) { // Does the CID exist in the roster JSON... OR are we using the dev endpoint (fake CIDs) OR are they allowlisted?
					$this->dump .= "User is a home or visiting controller!<br/>";
					$this->valid_auth = true;
					$this->setAuthCookie($this->auth_cookie_token);
					$_SESSION["vids_authenticated"] = true;
					$_SESSION["cid"] = $this->userData_json['data']['cid']; // Added 1/25/22 for passage to plugins for logging purposes
					if(isset($this->userData_json['data']['personal']['name_full'])) { // This shouldn't really be necessary, but it prevents an error when the full name isn't available
						$this->full_name = $this->userData_json['data']['personal']['name_full'];
						$_SESSION["name_full"] = $this->userData_json['data']['personal']['name_full']; // Added 6/29/23 for passage to ASX
						$this->dump .= "Hello " . $this->userData_json['data']['personal']['name_full'] . "<br/>";
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
			elseif($this->rejectlisted) {
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
		if(DEBUG) { echo $this->dump; }
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
		if(isset($this->userData_json['data'])) {
		$log_str = "[" . date("YmdHis") . "] CID " . $this->userData_json['data']['cid'] . "/" . $this->userData_json['data']['personal']['name_full'] . ": Login attempt ";
		$log_str .= $this->valid_auth ? 'successful' : 'failed';
		$log_str .= $this->rejectlisted ? ' - rejectlisted' : '';
		$log_str .= " (" . $_SERVER['REMOTE_ADDR'] . ")";
		// Write action to logfile **Note: logfiles go to both the file AND the database when the db is in use
		file_put_contents("data/access.log",$log_str . "\n",FILE_APPEND);
		if(USE_DB) {
			data_save('access.log',$log_str . "\n",false);
		}
		}
	}

	private function handleCookie($destroy=false) { // Handles persistent login cookie
		if(!$destroy) {
			// Check for cookie, if found extend exp and return true
			if(isset($_COOKIE[$this->auth_cookie])) {
				$result = $GLOBALS['db']->query("SELECT token,userData FROM authUser WHERE token = '" . $_COOKIE[$this->auth_cookie] . "' AND expires_at > now()");
				if($GLOBALS['db']->row_exists($result)) { // Cookie token matches value in DB, then set user variables, update cookie, and return true
					//echo "COOKIE FOUND: ";
					$res_assoc = $GLOBALS['db']->fetch_assoc($result);
					// Set user vars
					$this->auth_cookie_token = $res_assoc['token'];
					//echo $res_assoc['token'];
					$this->userData_json = json_decode($res_assoc['userData'],true);
					//echo "USER DATA: " . $res_assoc['userData'];
					// Update cookie expiration
					//$this->setAuthCookie($_COOKIE[$this->auth_cookie]); //NO! This should be done in the authorize function
					return true;
				}
				//echo "ERROR - NO MATCHING DB TOKEN!";
			}
			//echo "ERROR - COOKIE NOT FOUND!";
			return false;
		}
		else { // Logout... set cookie exp to a date in the past
			$this->setAuthCookie($this->access_token,true);
			return false;
		}
	}

	private function setAuthCookie($val,$destroy=false) {
		//echo "SETTING AUTH COOKIE $val";
		$cookieExpire = time() + $this->persistent_login_duration;
		if($destroy) {
			$cookieExpire = time() - 1000; // set time in the past
		}
		$cookieExpireSQL = date('Y-m-d H:i:s',$cookieExpire);
		$queryStr = "UPDATE authUser SET expires_at = '$cookieExpireSQL', updated_at = now() WHERE token = '$val'";
		if($val==null) {
			$val = hash('sha256', time() . 'vids'); // Generate token
			$json = json_encode($this->userData_json);
			$json_esc = str_replace("'","''",$json); // Shane O'Neill error bugfix!
			$queryStr = "INSERT INTO authUser VALUES ('$val','$json_esc','$cookieExpireSQL',now(),now())";
		}
		$GLOBALS['db']->query($queryStr);
		//echo $GLOBALS['db']->error();
		setcookie($this->auth_cookie,$val,$cookieExpire,"/");
	}

	public function logout() {
		$this->handleCookie(true);
	}
}
?>