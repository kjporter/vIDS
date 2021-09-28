<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: data_management.php
		Function: Handles I/O of persistent data
		Created: 9/6/2021
		Edited: 
		
		Changes: 
	*/

include_once "config.php";
include_once "common.php";
include_once "mysql_db.php";

$dbconn = null;
if (USE_DB) {
	// Start DB connection and select the DB
	$db = new MySQL_db(fetch_my_url(),$db_variables);
}
// For testing use only
//data_save('blacklist','dat','12345\n67890\n',false);
//data_read('blacklist','dat','array');

function data_read($item,$outputType,$queryString=null) {
	if(USE_DB) {
		if($queryString == null) {
			$queryString = "SELECT payload FROM legacy WHERE token = '$item' LIMIT 1";
		}
		$data = $GLOBALS['db']->query($queryString);
		//if(mysqli_num_rows($data)>0) {
		if($GLOBALS['db']->row_exists($data)) {
			//$data = mysqli_fetch_array($data);
			$data = $GLOBALS['db']->fetch_array($data);
			if($outputType == 'string') {
				$data = $data[0];
				//$data = implode('\n',mysqli_fetch_array($data));
			}
			elseif($outputType == 'array') {
				//$data = mysqli_fetch_assoc($data);
				//$data = preg_split('/\n/', $data[0], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY); // array_filter removes blank fields... 
				$data = array_filter(preg_split('/\n/', $data[0])); // array_filter removes blank fields... 
				array_walk($data, function(&$value, $key) { $value .= "\n"; } ); // For compatibility with file operations, adds an end line character to each element - can eliminate this someday when we get away from file system operations
			}
		}
		else {
			$data = '';
			if($outputType == 'array') {
				$data = array();
			}
		}
	}
	else {
		if(file_exists("data/" . $item)) {
			if($outputType == 'string') {
				$data = file_get_contents("data/" . $item);
			}
			elseif($outputType == 'array') {
				$data = file("data/" . $item);
			}
		}
		else {
			$data = '';
		}
	}
	return $data;
}

function data_save($item,$data,$overwrite=true,$queryString=null) {
	if(USE_DB) {
		$data = $GLOBALS['db']->escape($data);
		if($queryString == null) {
			// Determine if this is an insert or update query
			//if(mysqli_num_rows($GLOBALS['db']->query("SELECT ref FROM legacy WHERE token = '$item'")) > 0) { // Update
			if($GLOBALS['db']->row_exists($GLOBALS['db']->query("SELECT ref FROM legacy WHERE token = '$item'"))) { // Update
				if($overwrite) {
					$queryString = "UPDATE legacy SET payload='$data' WHERE token = '$item'";
				}
				else {
					$queryString = "UPDATE legacy SET payload=CONCAT(payload,'$data') WHERE token = '$item'";
				}
			}
			else { // Insert
				$queryString = "INSERT INTO legacy VALUES (null,'$item','$data')";
			}
		}
		//echo $queryString;
		$data = $GLOBALS['db']->query($queryString);
	}
	else {
		if(!$overwrite) {
			file_put_contents("data/" . $item,$data,FILE_APPEND);
		}
		else {
			file_put_contents("data/" . $item,$data);
		}
	}
}

function data_delete($item,$queryString=null) {
	if(USE_DB) {
		if($GLOBALS['db']->row_exists($GLOBALS['db']->query("SELECT ref FROM legacy WHERE token = '$item'"))) {
			$data = $GLOBALS['db']->query("DELETE FROM legacy WHERE token = '$item'");
		}
	}
	else {
		if(file_exists("data/" . $item)&&(strlen($item)>5)) { // length > 5 prevents accidental deletes from invalid params
			unlink("data/" . $item);
		}
	}
}
?>