<?php
/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: faa_dtpp.php
		Function: Experimental module to pull d-TPP from the FAA's shitty data service. This code mines the FAA Aeronav site for data, sorts/formats it, and returns as JSON.
		Created: 8/5/21
		Edited: 
		
		Changes: 
	
		TODO:
		- Cacheing system to speed up subsequent queries
*/

$terps = new FAA_dTPP($_GET['afld_id']);
$terps->echo_json();

class FAA_dTPP
{
	private $airfield_id = null;
	private $faa_data_url = null;
	private $airac_cycle = null;
	private $terps_table = null;

	function __construct($icao_id)	{
		$this->airfield_id = $icao_id;
		$this->airac_cycle = $this->fetch_airac_cycle();
		//echo "AIRAC Cycle: " . $this->airac_cycle;
		$this->faa_data_url = "https://www.faa.gov/air_traffic/flight_info/aeronav/digital_products/dtpp/search/results/?cycle=" . $this->airac_cycle . "&ident=" . $this->airfield_id;
		$this->fetch_dtpp();
	}

	public function fetch_dtpp() {
		$faa_raw = $this->fetch_data($this->faa_data_url);
		// Extract page 1
		$table = $this->extract_rows($faa_raw);
		// Determine number of items & pages
		$pg = substr($faa_raw,strpos($faa_raw,"</table>"));
		$pg = substr($pg,strpos($pg,"of")+3);
		$pg = substr($pg,0,strpos($pg,"</p>"));
		$items = $pg;
		$pages = ceil($pg/50);
		// Extract page 2-n (if necessary)
		$page = 1;
		while($page < $pages) {
			$page++;
			$faa_raw = $this->fetch_data($this->faa_data_url . "&page=" . $page);
			$table .= $this->extract_rows($faa_raw);
		}
		//echo "<table>$table</table>";
		$this->table_to_array($table);
	}

	public function echo_json() {
		echo json_encode($this->terps_table);
	}
	
	private function fetch_airac_cycle() {
		$cu = curl_init();
		curl_setopt($cu,CURLOPT_URL,"https://www.faa.gov/air_traffic/flight_info/aeronav/digital_products/dtpp/search/");
		curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
		curl_setopt($cu, CURLOPT_ENCODING, "gzip");
		curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // Omit SSL verification
		$faa_raw = curl_exec($cu); // Execute CURL
		curl_close($cu);
		// Extract cycle id (XXXX)
		$str = substr($faa_raw,strpos($faa_raw,"<select name=\"cycle\" id=\"cycle\" >"));
		$str = substr($str,0,strpos($str,"</select>"));
		$doc = new DOMDocument();
		$doc->loadHTML($str);
		$option = $doc->getElementsByTagName('option');
		$cycles = array();
		foreach($option as $n) {
			$cycles[] = $n->getAttribute('value'); //$n->nodeValue;
		}
		sort($cycles);
		return $cycles[0];	
	}
	
	private function fetch_data($url) {
		$cu = curl_init();
		curl_setopt($cu,CURLOPT_URL,$url);
		curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
		curl_setopt($cu, CURLOPT_ENCODING, "gzip");
		curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // Omit SSL verification
		$result = curl_exec($cu); // Execute CURL
		curl_close($cu);
		return $result;
	}

	private function extract_rows($faa_raw) {
		$tbl = substr($faa_raw,strpos($faa_raw,"<tbody>")+7);
		$tbl = substr($tbl,0,strpos($tbl,"</tbody>"));
		return $tbl;
	}
	
	private function table_to_array($table) {
		$doc = new DOMDocument();
		$doc->loadHTML($table);
		$tr = $doc->getElementsByTagName('tr');
		$terps = array();
		foreach($tr as $row) {
			$terp = array();
			$row->getAttribute('value'); //$n->nodeValue;
			$terp['name'] = $row->getElementsByTagName('td')->item(7)->getElementsByTagName('a')->item(0)->nodeValue; // Name of procedure
			$terp['type'] = $row->getElementsByTagName('td')->item(6)->nodeValue; // Type of procedure
			$terp['link'] = $row->getElementsByTagName('td')->item(7)->getElementsByTagName('a')->item(0)->getAttribute('href'); // Link to procedure
			$terps[] = $terp;
		}
		//print_r($terps);
		$this->terps_table = $terps;
	}
}
?>