<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: js_support.php
		Function: Translates system configuration parameters & global variables to javascript
		Created: 1/24/22
		Edited: 
		
		Changes: Moved from config.php
	*/

$positions = array($pos2,$pos1,$pos0,$pos3,$pos4,$pos5,$pos6,$pos7);
$satellites = array();
foreach($satellite_fields as $sat) {
	$satellites[] = $sat['id'];
}
$afld_config_ids = array();
foreach($afld_config_options as $afld_config_option) {
	$afld_config_ids[] = $afld_config_option[0];
}
$airfields = Array(DEFAULT_AFLD_ICAO);
$airfields = array_merge($airfields,$satellites);

$rwy_flows = array(); // Create an array where [flow][rwy][typ]
foreach($flow_override as $rwy=>$flow_rwy) {
	if(!array_key_exists($flow_rwy,$rwy_flows)) {
		$rwy_flows[$flow_rwy] = array('id'=>array(),'arr'=>array(),'dep'=>array());
	}
	if(!in_array($rwy,$rwy_flows[$flow_rwy]['id'])) {
		$rwy_flows[$flow_rwy]['id'][] = $rwy;
	}
	foreach($arrival_runways as $arrival_runway) {
		if(strpos($arrival_runway,strval($rwy)) !== false) {
			$rwy_flows[$flow_rwy]['arr'][] = $arrival_runway;
		}
	}
	foreach($departure_runways as $departure_runway) {
		if(strpos($departure_runway,strval($rwy)) !== false) {
			$rwy_flows[$flow_rwy]['dep'][] = $departure_runway;
		}
	}
}

function js_globals() { // Translates PHP globals into JS globals
	global $positions;
	global $satellites;
	global $departure_gates;
	global $departure_positions;
	global $afld_config_ids;
	global $approach_types; // Added 1/24/22 to fix php warning
	global $departure_types; // Added 1/24/22 to fix php warning
	global $flow_override;
	global $rwy_flows;
	$js_globals = "	const defaultAirfield = '" . DEFAULT_AFLD_ICAO . "';
					const positions = new Array('" . implode("','",array_unique(array_merge($positions[0][1],$positions[1][1],$positions[2][1],$positions[3][1],$positions[4][1],$positions[5][1],$positions[6][1],$positions[7][1]))) . "');
					const underlying_fields = new Array('" . implode("','",$satellites) . "');
					const departure_gates = new Array('" . implode("','",$departure_gates) . "');
					const departure_positions = new Array('" . implode("','",$departure_positions) . "');
					const afld_config_options = new Array('" . implode("','",$afld_config_ids) . "');
					const apch_types = new Array('" . implode("','",$approach_types) . "');
					const dep_types = new Array('" . implode("','",$departure_types) . "');
					const traffic_flows = new Array('" . implode("','",array_unique($flow_override)) . "');";
	$rwy_flow = "	const rwy_flows = {";
	foreach($rwy_flows as $flow=>$ar) {
		$rwy_flow .= "$flow : { arr : ['" . implode("','",$ar['arr']) . "'], dep : ['" . implode("','",$ar['dep']) . "'], id : ['" . implode("','",$ar['id']) . "'] },";
	}
	$rwy_flow .= " };";
	$js_globals .= $rwy_flow;				
	return $js_globals;
}
?>