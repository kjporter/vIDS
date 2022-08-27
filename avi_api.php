<?php
// Implements Aviation API chart pull for PROC display in vIDS
$icao_id = strtoupper($_GET['afld_id']);
$cu = curl_init();
curl_setopt($cu,CURLOPT_URL,'https://api.aviationapi.com/v1/charts?apt=' . $icao_id);
curl_setopt($cu,CURLOPT_RETURNTRANSFER,true);
curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,3);
curl_setopt($cu, CURLOPT_ENCODING, "gzip");
curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false); // Omit SSL verification
$result = curl_exec($cu); // Execute CURL
curl_close($cu);
$terps_table = array();
if($result) {
    $charts = json_decode($result,true);
    foreach($charts[$icao_id] as $chart) {
        $terp = array();
        $terp['name'] = $chart['chart_name']; // Name of procedure
        $terp['type'] = $chart['chart_code']; // Type of procedure
        $terp['link'] = $chart['pdf_path']; // Link to procedure
        $terps_table[] = $terp;
    }
}
echo json_encode($terps_table);
?>