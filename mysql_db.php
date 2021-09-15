<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: mysql_db.php
		Function: MySQL/MariaDB integration
		Created: 9/7/2021
		Edited: 
		
		Changes: 
	*/
	
include_once "vars/db_variables.php";

class MySQL_db {
	
	private $db_vars = null;
	private $db_conn = null;
	private $errors = null;
	
	function __construct($my_url,$db_variables) { // Import db variables and init the class
		foreach($db_variables as $db_var) { // Determine db variable set to use based on server URL
			if(strpos($my_url,$db_var['site_host'])!== false) {
				$this->db_vars = $db_var;
			}
		}
		if(is_array($this->db_vars)) {
			$this->init();
		}
		else {
			$this->errors .= "Unable to set database variables<br/>";
		}
	}

	private function init() { // Initialize db server connection
		$this->dbconn = mysqli_connect($this->db_vars['server_host'],$this->db_vars['username'],$this->db_vars['password'],$this->db_vars['database_name']);
		if(mysqli_connect_errno()) {
			$this->errors .= mysqli_connect_error() . "<br/>";
		}
	}
	
	public function query($str) { // Process queries
		$ret = mysqli_query($this->dbconn,$str);
		if(!$ret) {
			$this->errors .= mysqli_error($this->dbconn) . "<br/>";
		}
		return $ret;
	}
	
	public function escape($str) { // Escape a string
		return mysqli_real_escape_string($this->dbconn,$str);
	}
	
	public function row_exists($result) { // Determines is a row exists, returns boolean
		return (mysqli_num_rows($result) > 0) ? true : false;
	}
	
	public function fetch_array($result) { // Returns the query result as an array
		return mysqli_fetch_array($result);
	}
	
	public function error() { // Return errors
		echo $this->errors;
	}
	
	function __destruct() { // Close db server connection
		mysqli_close($this->dbconn);
	}
}
?>