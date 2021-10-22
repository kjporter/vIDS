<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ajax_handler.php
		Function: Handles AJAX requests from main vIDS page to store dynamic/user-entry data
		Created: 4/1/21
		Edited: 10/17/21
		
		Changes: Converted data manipulation to new data management schema including db integration
		
	*/

include_once "data_management.php";	

// Set variables
$base_dir = $target = "data/";
$filename = $_GET['type']; // Get data type
$file_ext = "dat";
$log_str = "[" . date("YmdHis") . "] CID " . $_GET['cid'] . ": ";
$reply_string = "";

// Format header as timestamp
$header = "-*-" . time() . "-*--";
$payload = $_GET['payload']; // Get data payload

if($_GET['type'] == "pirep") { // PIREPs get special treatment. We check to see if any of the PIREPs are expired, delete them, timestamp the new ones and write to file
	$header = ""; // We don't use this in the PIREP data file
	$pirep_timeout = 3600; // Timeout in 1 hour (3,600 seconds)
//	$file = fopen("data/pirep.dat","r"); // <- NEED TO WORK ON THIS TO TRANSITION TO THE NEW DATA MANAGEMENT SCHEMA 
	$pireps = array();
	$pireps[] = time() . "|" . $payload;
	$stored_pireps = data_read('override.dat','array');
//	$file = fopen("data/pirep.dat","r"); // <- NEED TO WORK ON THIS TO TRANSITION TO THE NEW DATA MANAGEMENT SCHEMA 
//	while(!feof($file)) { // Read in PIREPs from file one at at time for evaluation
	foreach($stored_pireps as $pirep) {
//		$pirep = fgets($file);  // <- NEED TO WORK ON THIS TO TRANSITION TO THE NEW DATA MANAGEMENT SCHEMA
		$pirep_exp = explode("|",$pirep);
		if(intval($pirep_exp[0]) > (time() - $pirep_timeout)) { // Check if PIREP is still valid
			$pireps[] = $pirep; // If it is valid, add it to the array
		}
	}
//	fclose($file);
	$payload = implode("",$pireps);
}

if($_GET['type'] == "template") { // Multi-airfield layout templates also get special treatment.
	$header = "";
	$target .= "templates/";
	$file_ext = "templ";
	$digits = 5; // Randomized 5-digit id
	$templ_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
	//while(file_exists($target . $templ_id . "." . $file_ext)) { // Make sure we have a unique id  // <- NEED TO WORK ON THIS TO TRANSITION TO THE NEW DATA MANAGEMENT SCHEMA
	while(data_unique($target . $templ_id . "." . $file_ext)) {
		$templ_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
	}
	$filename = $templ_id;
	$templ_name = preg_split('#\r?\n#', $payload, 0)[0];
	$reply_string = json_encode(array("templ_name"=>$templ_name,"filename"=>$filename));
}

if($_GET['type'] == "override") { // For overrides, we have to read the current file in and append the JSON to it
	$header = "";
	$override = json_decode($payload,true); // $override[0] = icao id, $override[1] = false or override text
	$override_data = "";
	$reply_string = "";
//	if(file_exists("data/override.dat")) { // No longer necessary to check for this here - happens in data mgt module
		//$reply_string .= "File exists. ";
		//$override_data = json_decode(file_get_contents("data/override.dat"),true);
		$override_data = json_decode(data_read('override.dat','string'),true);
		if(!$override[1]) { // Remove the override
			//$reply_string .= "This is a remove operation. ";
			if(array_key_exists($override[0],$override_data)) {
				//$reply_string .= "Key found in JSON, removing now.";
				unset($override_data[$override[0]]);
			}
		}
		else { // Add/Update the override
			//$reply_string .= "This is an add/update operation.";	
			$override_data[$override[0]] = $override[1];
		}
//	}
	$payload = json_encode($override_data);
}


$log_str .= $_GET['type'] . " update (" . $payload . ")";
// Write to file
//file_put_contents("data/" . $_GET['type'] . ".dat",$header . $payload);

if(isset($_GET['delete'])) { // <- COMPLETE
	data_delete("templates/" . $_GET['delete'] . ".templ");
/*	
	if(file_exists("data/templates/" . $_GET['delete'] . ".templ")&&(strlen($_GET['delete'])==5)) {
		unlink("data/templates/" . $_GET['delete'] . ".templ");
	}
*/
	$log_str .= "delete template (" . $_GET['delete'] . ")\n";
}
else {
	//file_put_contents($target . $filename . "." . $file_ext ,$header . $payload);
	data_save(str_replace($base_dir,'',$target) . $filename . "." . $file_ext,$header . $payload); // <- COMPLETED THE NEW WRITE SCHEMA HERE
}

// Write action to logfile **Note: logfiles go to both the file AND the database when the db is in use
file_put_contents("data/system.log",$log_str . "\n",FILE_APPEND);
if(USE_DB) {
	data_save('system.log',$log_str . "\n",false);
}
echo $reply_string;