<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ajax_handler.php
		Function: Handles AJAX requests from main vIDS page to store dynamic/user-entry data
		Created: 4/1/21
		Edited: 
		
		Changes: 
		
	*/

// Set variables
$base_dir = "data/";
$filename = $_GET['type']; // Get data type
$file_ext = "dat";
$reply_string = "";

// Format header as timestamp
$header = "-*-" . time() . "-*--";
$payload = $_GET['payload']; // Get data payload

if($_GET['type'] == "pirep") { // PIREPs get special treatment. We check to see if any of the PIREPs are expired, delete them, timestamp the new ones and write to file
	$header = ""; // We don't use this in the PIREP data file
	$pirep_timeout = 3600; // Timeout in 1 hour (3,600 seconds)
	$file = fopen("data/pirep.dat","r");
	$pireps = array();
	$pireps[] = time() . "|" . $payload;
	while(!feof($file)) { // Read in PIREPs from file one at at time for evaluation
		$pirep = fgets($file);
		$pirep_exp = explode("|",$pirep);
		if(intval($pirep_exp[0]) > (time() - $pirep_timeout)) { // Check if PIREP is still valid
			$pireps[] = $pirep; // If it is valid, add it to the array
		}
	}
	fclose($file);
	$payload = implode("",$pireps);
}

if($_GET['type'] == "template") { // Multi-airfield layout templates also get special treatment.
	// TODO: Store user CID - will enable user who created a template to modify or delete it 
	$header = "";
	$base_dir .= "templates/";
	$file_ext = "templ";
	$digits = 5; // Randomized 5-digit id
	$templ_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
	while(file_exists($base_dir . $templ_id . "." . $file_ext)) { // Make sure we have a unique id
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
	if(file_exists("data/override.dat")) {
		//$reply_string .= "File exists. ";
		$override_data = json_decode(file_get_contents("data/override.dat"),true);
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
	}
	$payload = json_encode($override_data);
}

// Write to file
//file_put_contents("data/" . $_GET['type'] . ".dat",$header . $payload);
file_put_contents($base_dir . $filename . "." . $file_ext ,$header . $payload);

echo $reply_string;