<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ajax_rvr.php
		Function: Handles AJAX requests to fetch runway visual range from the FAA dataserver
		Created: 12/26/21
		Edited:
		
		Changes:
		
		TODO: 
	*/
	
include_once "data_management.php";	

if(isset($_GET['icao'])) {
	fetch_rvr($_GET['icao']);
}

function fetch_rvr($station,$reply_raw_data=true,$ajax_reply=true) {	// Fetch RVR
	// Get cache, determine if a refresh is necessary
	$refresh = true;
	$refreshInterval = 60;
	$cache = data_read("rvr.dat","string");
	$cache = json_decode($cache,true);
	if(array_key_exists($station,$cache)) {
		if($cache[$station]['TIMESTAMP'] + $refreshInterval > time()) {
			$refresh = false;
		}
	}
	if($refresh) {
		$url = "https://rvr.data.faa.gov/cgi-bin/rvr-details.pl?airport=" . strtoupper($station);
		$cu = curl_init();
		curl_setopt($cu,CURLOPT_URL,$url);
		curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
		curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // There is no reason to verify the SSL certificate, skip this
		$rvr = curl_exec($cu); // Execute CURL
		curl_close($cu);
		$rvr_table = strpos($rvr,"<strong>");
		$rvr_array = null;
		if($rvr_table != false) {
			$rvr_table = substr($rvr,$rvr_table,strpos($rvr,"</table></font>") + 15 - $rvr_table); // Extract RVR table
			$begin_links = strpos($rvr_table,"<a href");
			$end_links = strpos($rvr_table,"<pre></pre>") + 11;
			$rvr_hdg = substr($rvr_table,0,$begin_links);
			$rvr_tbl = substr($rvr_table,$end_links);
			$rvr_table = $rvr_hdg . $rvr_tbl;
			// Build JSON data requirements
			preg_match('/(<tr><th>)(.*?)(<\/tr>)/',$rvr_hdg,$rvr_dtg);
			$rvr_dtg = date_create_from_format("H:i:s* m/d/Y",strip_tags($rvr_dtg[0]));
			$rvr_array = array("DTG"=>date_timestamp_get($rvr_dtg),"TIMESTAMP"=>time(),"RAW"=>$rvr_table,"RWY"=>array());
			preg_match_all('/(<tr><th>)(.*?)(<\/tr>)/s',$rvr_tbl,$rwy_rows);
			foreach($rwy_rows[0] as $rwy_row) {
				preg_match('/[0-9]{2}[RLC]{1}/',$rwy_row,$rwy_id);
				//preg_match_all('/[0-9]{3,}/',$rwy_row,$rvr_val); 
				preg_match_all('/(?<=<td align="center">)(.*?)(?=<\/td>)/',$rwy_row,$rvr_val);
				$td = array_key_exists(0,$rvr_val[0]) ? str_replace("&nbsp;","",$rvr_val[0][0]) : null;
				$mp = array_key_exists(1,$rvr_val[0]) ? str_replace("&nbsp;","",$rvr_val[0][1]) : null;
				$ro = array_key_exists(2,$rvr_val[0]) ? str_replace("&nbsp;","",$rvr_val[0][2]) : null;
				$worst = array('TD'=>$td,'MP'=>$mp,'RO'=>$ro);
				$rvr_array["RWY"][$rwy_id[0]] = array("TD"=>$td,"MP"=>$mp,"RO"=>$ro,"WORST"=>min(array($worst)) . array_keys($worst, min($worst))[0]);
			}
		}
	}
	else { // Reply with cached data
		$rvr_array = $cache[$station];
	}
	if($rvr_array != null) {
		$rvr_JSON = $rvr_array;
		if(!$reply_raw_data) {
			$rvr_JSON["RAW"] = "";
		}
		if($ajax_reply) {
			echo $rvr_JSON["RAW"];
		}
		// Update cached data
		$cache[$station] = $rvr_array;
		$cache = json_encode($cache);
		data_save("rvr.dat",$cache);
		return $rvr_JSON;
	}
	else {
		if($ajax_reply) {
			echo null;
		}
		else {
			return null;
		}
	}
}
?>