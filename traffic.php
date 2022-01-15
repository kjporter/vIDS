<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: traffic.php
		Function: Initial traffic management prototype
		Created: 9/21/21
		Edited: 
		
		Changes: 
		
	*/
	
	define("DEBUGT",false);
	define("DEV",false);
	
	include_once "data_management.php";	
	include_once "traffic/point-library.php";	

	// Check if the user has signed in via vIDS. If not, redirect to vIDS SSO
	session_start();
	if(!DEV && !$_SESSION["vids_authenticated"]) {
		header("Location: " . fetch_my_url()); 
		exit();
	}
	
$artcc = $primary_afld['artcc'];
$afld_id = $primary_afld['id'];

// Picks a random image from the $imagesDir to display in the landing page background
$imagesDir = 'traffic/logo/';
$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
$randomImage = $images[array_rand($images)];
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>vIDS - Traffic Management</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="img/favicon.ico" />
	
<?php
function is_connected()
{
    $connected = @fsockopen("www.example.com", 80); 
                                        //website, port  (try 80 or 443)
    if ($connected){
        $is_conn = true; //action when connected
        fclose($connected);
    }else{
        $is_conn = false; //action in connection failure
    }
    return $is_conn;

}
// Ensures CDN resource connectivity
if(is_connected()) { // Return CDN external resources
	print "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>
	<link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We\" crossorigin=\"anonymous\">
	<script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj\" crossorigin=\"anonymous\"></script>
	<script src=\"https://kit.fontawesome.com/9bd47a7738.js\" crossorigin=\"anonymous\"></script>
	<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css\">";
}
else { // Return local resources
	print "<script src=\"cdn/jquery.min.js\"></script>
	<link href=\"cdn/bootstrap.min.css\" rel=\"stylesheet\">
	<script src=\"cdn/bootstrap.bundle.min.js\"></script>
	<script defer src=\"cdn/fontawesome/js/all.js\"></script>
	<link href=\"cdn/fontawesome/css/all.css\" rel=\"stylesheet\">";
}
?>
<!--
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/9bd47a7738.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
	<script data-cfasync="false" src="traffic/traffic.js"></script>
	<script>
	var artcc = '<?php echo $artcc; ?>';
	var afld_id = '<?php echo $primary_afld['id']; ?>';
	</script>
	  <style>
	  /* Custom colors for TGUI */
	.tgui_green{
		color: rgb(0,253,0);
	}
	.tgui_yellow {
		color: rgb(228,228,0);
	}
	.tgui_blue {
		color: rgb(0,199,254);
	}
	.tgui_orange {
		color: rgb(255,139,51);
	}
	#contextMenu {
		position: absolute;
		display:none;
	}
  </style>
</head>
<body style="height=100%" onload="refreshData(1);">
<div id="header" style="position:fixed; top:0; left:0; width:100%; background-color:black; color:#FFB000;">
<div id="clock" style="width:20%; float:left;"></div>
<div style="width:60%; float:left; text-align:center"><?php echo $artcc; ?> Traffic Management Unit</div>
<div id="refresh_countdown" style="width:20%; float:left; text-align:right"></div>
</div>

<div style="height: 4vh; width:100%;"></div>
<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="config-tab" href="#config" data-bs-toggle="tab" data-bs-target="#config" type="button" role="tab" aria-controls="config" aria-selected="true">Config</a>
  </li>
  <li class="nav-item" <?php echo DEV ? "" : " style=\"display:none\""; ?> >
    <a class="nav-link" id="raw-tab" ref="#raw" data-bs-toggle="tab" data-bs-target="#raw" type="button" role="tab" aria-controls="raw" aria-selected="false">Raw</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="arrivals-tab" data-bs-toggle="tab" data-bs-target="#arrivals" type="button" role="tab" aria-controls="arrivals" aria-selected="false">Arrival List</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="arrivals-tab" data-bs-toggle="tab" data-bs-target="#departures" type="button" role="tab" aria-controls="departures" aria-selected="false">Departure List</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="loadgraph-tab" data-bs-toggle="tab" data-bs-target="#loadgraph" type="button" role="tab" aria-controls="loadgraph" aria-selected="false">Load Graph</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="aadc-tab" data-bs-toggle="tab" data-bs-target="#aadc" type="button" role="tab" aria-controls="aadc" aria-selected="false">AADC</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="tgui-tab" data-bs-toggle="tab" data-bs-target="#tgui" type="button" role="tab" aria-controls="tgui" aria-selected="false">TGUI</a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="pgui-tab" data-bs-toggle="tab" data-bs-target="#pgui" type="button" role="tab" aria-controls="pgui" aria-selected="false">PGUI</a>
  </li>
   <li class="nav-item">
    <a class="nav-link disabled" id="ground-tab" data-bs-toggle="tab" data-bs-target="#ground" type="button" role="tab" aria-controls="ground" aria-selected="false">Ground Handling</a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="sector-tab" data-bs-toggle="tab" data-bs-target="#sector" type="button" role="tab" aria-controls="sector" aria-selected="false">Sector Saturation</a>
  </li>
  <!-- NOT USED
   <li class="nav-item">
    <a class="nav-link" id="ntos-tab" data-bs-toggle="tab" data-bs-target="#ntos" type="button" role="tab" aria-controls="ntos" aria-selected="false">NTOS</a>
  </li>
  -->
   <li class="nav-item">
    <a class="nav-link" id="vatcscc-tab" data-bs-toggle="tab" data-bs-target="#vatcscc" type="button" role="tab" aria-controls="vatcscc" aria-selected="false">NTML</a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="ids_notes-tab" data-bs-toggle="tab" data-bs-target="#ids_notes" type="button" role="tab" aria-controls="ids_notes" aria-selected="false">TMU Notices</a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="nmap-tab" data-bs-toggle="tab" data-bs-target="#nmap" type="button" role="tab" aria-controls="nmap" aria-selected="false">Map</a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="slot-tab" data-bs-toggle="tab" data-bs-target="#slot" type="button" role="tab" aria-controls="slot" aria-selected="false">Slot Request</a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="edct-tab" data-bs-toggle="tab" data-bs-target="#edct" type="button" role="tab" aria-controls="edct" aria-selected="false">EDCT Display</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="config" role="tabpanel" aria-labelledby="config-tab">
	<h2>Traffic Management Tools Configuration</h2>
	<div class="container">
	<div class="row">
	<div class="col-7">
	<form>
	<div class="mb-3">
		<div class="col-auto"><label for="exampleInputEmail1">Airfield ID (ICAO)</label></div>
		<div class="col-auto"><input type="text" class="form-control w-25" id="afld_id" disabled value="<?php echo $afld_id; ?>"></div>
	</div>
<div class="form-check form-switch" <?php echo DEV ? "" : " style=\"display:none\""; ?>>
  <input class="form-check-input" type="checkbox" id="useArchivedData" <?php if(!is_connected()) echo " checked"; ?>>
  <label class="form-check-label" for="useArchivedData">Use archived traffic data</label>
  <div class="form-text">When enabled, an archived data set is loaded for testing and demo purposes.</div>
</div>
<div class="form-check form-switch" <?php echo DEV ? "" : " style=\"display:none\""; ?>>
  <input class="form-check-input" type="checkbox" id="disableExternalSources" <?php if(!is_connected()) echo " checked"; ?>>
  <label class="form-check-label" for="disableExternalSources">Disable external data sources</label>
  <div class="form-text">Use for testing when internet sources are not available.</div>
</div>
<div class="form-check form-switch">
  <input class="form-check-input" type="checkbox" id="enabledMetering" onchange="meteringSwitch(this);">
  <label class="form-check-label" for="enabledMetering">Enable metering</label>
  <div class="form-text">DP will provide EDCTs, allow slot scheduling, and provide metering guidance based on AAR/ADR.</div>
</div>
<br/>
<div class="mb-3">
  <button type="button" class="btn btn-outline-primary btn-sm" onclick="flowSwap();">Swap</button>
  <label class="form-check-label" for="">Arrival/Departure Flow Change</label>
  <div class="form-text">This button purges all runway assignments and should be used when a flow swap is directed at the primary airport.</div>
</div>
	<div class="mb-3">
		<div class="col-auto"><label for="aar">Airport Arrival Rate (AAR)</label></div>
		<div class="col-auto"><input type="text" class="form-control w-25" id="aar" value="96"></div>
		<div class="form-text">Number of arriving aircraft which an airport or airspace can accept from the ARTCC per hour.</div>
	</div>
	<div class="mb-3">
		<div class="col-auto"><label for="adr">Airport Departure Rate (ADR)</label></div>
		<div class="col-auto"><input type="text" class="form-control w-25" id="adr" placeholder="20" disabled></div>
		<div class="form-text">Number of aircraft which can depart an airport and the airspace can accept per 10-min period.</div>
	</div>
	</form>
  </div>
  <div class="col-5"><img id="logo" src="<?php echo $randomImage; ?>" width="500vw"/></div>
  </div>
  </div>
  </div>
  <div class="tab-pane fade" id="raw" role="tabpanel" aria-labelledby="raw-tab"></div>
  <div class="tab-pane fade" id="arrivals" role="tabpanel" aria-labelledby="arrivals-tab">
  </div>
  <div class="tab-pane fade" id="departures" role="tabpanel" aria-labelledby="arrivals-tab">
  </div>
  <div class="tab-pane fade" id="loadgraph" role="tabpanel" aria-labelledby="loadgraph-tab">
	<img id="loadgraph_img" src="" />
  </div>
  <div class="tab-pane fade" id="aadc" role="tabpanel" aria-labelledby="aadc-tab">
<img id="aadc_img" src="" />
  </div>
  <div class="tab-pane fade" id="tgui" role="tabpanel" aria-labelledby="tgui-tab">

<div class="container-fluid">
	<div id="tgui_disp" class="row" style="background-color:black; text-align:center;"></div>
<!--
	<ul id="contextMenu" class="dropdown-menu">
		<li><h6 class="dropdown-header" id="tgui_menu_cs">Callsign Goes Here</h6></li>
		<li class="dropdown-divider"></li>
		<li><a class="dropdown-item" href="#">Reschedule</a></li>
		<li><a class="dropdown-item" href="#">Runway</a></li>
		<li><a class="dropdown-item" href="#">Metering Fix Change</a></li>
		<li><a class="dropdown-item" href="#">Suspend</a></li>
		<li><a class="dropdown-item" href="#">Reset</a></li>
		<li><a class="dropdown-item" href="#">Assign Priority</a></li>
		<li><a class="dropdown-item" href="#">Find Slot</a></li>
	</ul>
-->
</div>
  </div>
  <div class="tab-pane fade" id="pgui" role="tabpanel" aria-labelledby="pgui-tab" style="background-color:black">
    <ul class="nav nav-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" id="pgui-overview-tab" href="#pgui-overview" data-bs-toggle="tab" data-bs-target="#pgui-overview" type="button" role="tab" aria-controls="pgui-overview" aria-selected="true">Overview</a>
		</li>
<?php
	foreach($starid as $star) {
		print "<li class=\"nav-item\">
				<a class=\"nav-link\" id=\"pgui-$star-tab\" href=\"#pgui-$star\" data-bs-toggle=\"tab\" data-bs-target=\"#pgui-$star\" type=\"button\" role=\"tab\" aria-controls=\"pgui-$star\">$star</a>
				</li>";
	}
?>
		<li class="nav-item">
			<a class="nav-link" href="#pgui-config" data-bs-toggle="modal" data-bs-target="#pgui-config"><i class="fas fa-cog"></i></a>
		</li>
	</ul>
	<div class="tab-content" id="pguiTabContent">
	<div class="tab-pane fade show active" id="pgui-overview" role="tabpanel" aria-labelledby="pgui-overview-tab" style="background-color:black">
		<img id="pgui_img" src="" style="max-width:100%; max-height:100%;" />
	</div>
<?php
	$js_stars = array();
	foreach($starid as $star) {
		print "<div class=\"tab-pane fade\" id=\"pgui-$star\" role=\"tabpanel\" aria-labelledby=\"pgui-$star-tab\" style=\"background-color:black\">
		<div class=\"container-fluid\">
			<div class=\"row\">
				<div id=\"tgui_disp_$star\" class=\"col-3\" style=\"text-align:center;\">
				</div>
				<div class=\"col-9\" style=\"text-align:center;\"><img id=\"pgui_img_$star\" src=\"\" style=\"max-width:100%; max-height:100%;\" /></div>
			</div>
		</div></div>";
		$js_stars[] = "'$star'";
	}
	print "<script>var STARS = new Array(" . implode(",",$js_stars) . ");</script>";
?>
	</div>
  </div>
  <div class="tab-pane fade" id="ground" role="tabpanel" aria-labelledby="ground-tab">
  <h2>Ground Handling</h2>
  <div class="container">
	<div class="row">
		<div class="col">
			<table id="ground_handling" class="table table-striped">
			</table>
		</div>
	</div>
  </div>
  </div>
  <div class="tab-pane fade" id="sector" role="tabpanel" aria-labelledby="sector-tab">
  <h2>Sector Saturation</h2>
  <div class="container">
	<div class="row">
		<div class="col">
			<div class="table-responsive">
				<table id="saturation" class="table table-striped">
				</table>
			</div>
			<p>MAP values represent the number of aircraft projected to fly within the bounds of a given sector that exceed the sector's handling capabilities under normal circumstances. For example, if 14 aircraft can transit a sector while maintaining enroute MIT, then 15 aircraft projected in the sector will trigger a MAP value of 1 and an alert. Note: because VATSIM flight plan departure times do not closely correlate to actual departure times, saturation in departure sectors is estimated by the number of aircraft on the ground with a flight plan filed through the airspace.</p>
		</div>
		<div class="col-7">
			<img src="traffic/departure_sectors.png" class="mx-auto" style="width:100%; max-height:80%" />
		</div>
	</div>
  </div>
  </div>
  <div class="tab-pane fade" id="ntos" role="tabpanel" aria-labelledby="ntos-tab">
  <h2>VATUSA TMU Notices (NTOS): <?php echo $artcc; ?></h2>
  <table id="ntos_text" class="table table-striped">
	</table>
  </div>
  <div class="tab-pane fade" id="vatcscc" role="tabpanel" aria-labelledby="vatcscc-tab">
  <h2>VATUSA ATC Command Center National Traffic Management Log (NTML)</h2>
	<!-- Discord widgetbot.io integration -->
	<widgetbot server="790485317437751306" channel="914586990920478731" width="100%" height="500"></widgetbot>
	<script src="https://cdn.jsdelivr.net/npm/@widgetbot/html-embed"></script>
  </div>
  <div class="tab-pane fade" id="nmap" role="tabpanel" aria-labelledby="nmap-tab">
  <object data="https://www.vatusa.net/tmu/map/<?php echo $artcc; ?>" border="0" style="height: 90vh; width: 100%">
</object>
</div>
  <div class="tab-pane fade" id="ids_notes" role="tabpanel" aria-labelledby="ids_notes-tab">
  <h2>TMU Notices</h2>
  <div class="container">
  	<form>
	<div class="mb-3">
		<label for="">ARTCC TMU Notices (displayed in tower and TRACON vIDS views)</label>
		<div class="col-auto"><textarea class="form-control" id="TMU_text" rows="4"></textarea></div>
	</div>
	<p>URLs entered in this field will appear as clickable links</p>
	<button type="button" class="btn btn-primary" onclick="saveTMUnotes();">Save</button>
	</form>
	</div>
	<hr/>
  <div class="container">
  	<form>
	<div class="mb-3">
		<label for="">Public TMU Notices (displayed to pilots and operators via EDCT display)</label>
		<div class="col-auto"><textarea class="form-control" id="TMU_text_public" rows="4"></textarea></div>
	</div>
	<p>URLs entered in this field will appear as clickable links</p>
	<button type="button" class="btn btn-primary" onclick="saveTMUnotes('public');">Save</button>
	</form>
	</div>
</div>

  <div class="tab-pane fade" id="slot" role="tabpanel" aria-labelledby="slot-tab">
  <h2><?php echo $artcc; ?> External Controller Slot Time Request</h2>
  	<form>
	<div class="row form-group">
		<div class="col-auto"><label for="">Select Callsign</label></div>
		<div class="col-auto"><select class="form-select" id="slot_eligible" onchange="findSlot(this);"></select></div>
		<span class="form-text">Aircraft must have a flight plan on file prior to requesting a slot time</span>
	</div>
	</form>
	<div id="slot_request"></div>
</div>
  <div class="tab-pane fade" id="edct" role="tabpanel" aria-labelledby="edct-tab">
  <h2>Public (Pilot/Virtual Airline) View for EDCTs</h2>
  <a href="https://www.ztlartcc.org/ramp-status/atl" target="_blank">View ATL Ramp Status</a>
  <div class="container p-3 my-3 bg-primary text-white"><h3>Traffic Management Notices:</h3><div id="pilot_tmu_notes"></div></div>
  <table id="edct_view" class="table table-striped">
<!--  <tr style="text-align:center"><th>Callsign</th><th>Departure<br/>Point</th><th>EDCT<br/>Assigned</th><th>STAR</th><th>Metering Fix</th><th>Slot Time @<br/>Metering Fix</th><th>ETA</th></tr>
  <tr><td colspan="8" style="text-align:center">Ground delay program not currently in effect</td></tr>-->
  </table>
</div>
<!-- Modals -->
<div id="pgui-config" class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Configure PGUI Views</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>The configuration settings below apply to all PGUI views.</p>
		<div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">Range Rings</label>
			<div class="col-sm-2">
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" class="form-control" id="rr_on" checked />
				</div>
			</div>
			<div class="col-sm-6">
				<p>Toggles range rings on/off</p>
			</div>
		</div>
		<div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">Ring Spacing</label>
			<div class="col-sm-2">
				<input type="input" class="form-control" id="rr_spacing" size="5" value="20"/>
			</div>
			<div class="col-sm-6">
				<p>Set distance between range rings in NM</p>
			</div>
		</div>
		<div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">J-Rings</label>
			<div class="col-sm-2">
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" class="form-control" id="jr_on" />
				</div>
			</div>
			<div class="col-sm-6">
				<p>Toggles j-range rings on/off</p>
			</div>
		</div>
		<div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">TPA distance</label>
			<div class="col-sm-2">
				<input type="input" class="form-control" id="jr_spacing" size="5" value="3"/>
			</div>
			<div class="col-sm-6">
				<p>J-ring radius in NM</p>
			</div>
		</div>
		<br/>
		<p><i class="fas fa-info-circle"></i> Changes set above will be reflected in the next refresh.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="context-reschedule" class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Scheduler: <span id="c_reschedule_callsign"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>This control can be used to manually reschedule an aircraft's metering fix time (slot time) to arrive at the metering fix. Enter a new date/time in UTC and save when it is necessary to assign a specific time to an aircraft.</p>
		<p><i class="fas fa-info-circle"></i> Note: the reset, suspend, priority, and find slot tools can also be used to reschedule an aircraft without the need to enter a specific time.</p>
		<div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">Current MFT:</label>
			<div class="input-group col-sm-6">
				<input type="text" class="form-control" id="mft_assigned" readonly />
				<span class="input-group-text">@</span>
				<input type="text" class="form-control" style="max-width:40%" id="mfa" readonly /> 
			</div>
		</div>
		<div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">Assign MFT:</label>
			<div class="input-group col-sm-6">
				<input type="text" class="form-control bg-warning" id="mft_select" />
				<span class="input-group-text">@</span>
				<input type="text" class="form-control" style="max-width:40%" id="mfc" readonly /> 
			</div>
			<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
			<script> // Datetime picker https://flatpickr.js.org/examples/
			/*
				$("#mft_select").flatpickr({
					enableTime: true,
					dateFormat: "Y-m-d H:i",
					minDate: "today",
					maxDate: new Date().fp_incr(3), // 3 days from now
					});
					*/
			</script>
		</div>
		<div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">Current time:</label>
			<div class="input-group col-sm-6">
				<input type="text" class="form-control" id="reschedule_clock" readonly /> 
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="context('reschedule');">Save</button>
      </div>
    </div>
  </div>
</div>
<div id="context-runway" class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Scheduler: <span id="c_runway_callsign"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Change aircraft runway assignment.</p>
		<p><i class="fas fa-info-circle"></i> Note: only active runways are selectable in this control. Active runways are set in vIDS airfield config.</p>
		<div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">Runway assigned:</label>
			<div class="col-sm-8">
				<input type="input" class="form-control" id="rwy_assigned" readonly />
			</div>
		</div>
		<div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">Change runway:</label>
			<div class="col-sm-8">
				<select class="form-control bg-warning" id="rwy_select"></select>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="context('runway');">Save</button>
      </div>
    </div>
  </div>
</div>
<div id="context-mfchange" class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Scheduler: <span id="c_mfchange_callsign"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Change aircraft metering fix assignment.</p>
		<p><i class="fas fa-info-circle"></i> This function should be used to assign a metering fix to aircraft that are unassigned or to change the metering fix of an aircraft that has an invalid STAR in their flight plan.</p>
		<div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">MF assigned:</label>
			<div class="col-sm-8">
				<input type="input" class="form-control" id="mf_assigned" readonly />
			</div>
		</div>
		<div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">New metering fix:</label>
			<div class="col-sm-8">
				<select class="form-control bg-warning" id="mf_select"></select>
			</div>
		</div><br/>
		<p><i class="fas fa-exclamation-triangle"></i> Metering fixes set/changed internal to TMU tools will help the scheduler sequence aircraft, but it will not update an aircraft's flight plan.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="context('mfchange');">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Context Menu -->
<ul id="contextMenu" class="dropdown-menu">
	<li><h6 class="dropdown-header" id="tgui_menu_cs">Callsign Goes Here</h6></li>
	<li class="dropdown-divider"></li>
	<li><a class="dropdown-item" onclick="modalOpen('reschedule');">Reschedule</a></li>
	<li><a class="dropdown-item" onclick="modalOpen('runway');">Runway</a></li>
	<li><a class="dropdown-item" onclick="modalOpen('mfchange');">Meter Fix Change</a></li>
	<li><a class="dropdown-item" onclick="context('suspend');">Suspend</a></li>
	<li><a class="dropdown-item" onclick="context('reset');">Reset</a></li>
	<li><a class="dropdown-item" onclick="context('priority');">Assign Priority</a></li>
	<li><a class="dropdown-item" onclick="context('findslot');">Find Slot</a></li>
</ul>
<input type="hidden" id="context-callsign" value="" />
<div id="alertbox" class="alert alert-success alert-dismissible fade show align-middle" role="alert" style="position:fixed; bottom: 0; width: 100%; display:none">
  <i class="fas fa-check-circle fa-2x"></i>&nbsp;&nbsp;
  <strong id="alert-title"></strong>&nbsp;<span id="alert-text"></span>
  <button type="button" class="btn-close" aria-label="Close" onclick="hideAlert();"></button>
  </div>
  <input type="hidden" id="currentDateTime" />
</body>
</html>