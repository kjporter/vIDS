<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: index.php
		Function: Website index file - contains baseline HTML template for site
		Created: 4/1/21
		Edited: 
		
		Changes: 
		
		VATSIM Data Provider: http://status.vatsim.net/
	*/
	
	include_once "sso_auth.php";
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>vIDS</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="ids.css">
	<link rel="shortcut icon" href="img/favicon.ico" />
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<!-- TODO: Add support for bootstrap 4.x and remove bootstrap dependencies 3.X from project -->
	<!--
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous" media="screen">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
	-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="https://kit.fontawesome.com/9bd47a7738.js" crossorigin="anonymous"></script> <!-- used for glyph icons in tower IDS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- used for glyph icons in tower IDS -->
	<!-- This script was buggy, problematic, and not compatible with boostrap 4.X
 	<link href="css/bootstrap-modal-carousel.css" rel="stylesheet" /> 
	<script src="js/bootstrap-modal-carousel.js"/></script>
	-->
	<script src="ids.js"/></script>
</head>
<body style="background-image: url('img/prism.png')" onload="refreshData(true);">  <!-- refreshData call initializes the display data -->
	<div id="alerts" class="container fixed-top">  <!-- alert box displays authentication info -->
		<div class="row no_border" style="visibility:hidden">
			<div id="alert" class="col <?php echo $alert_style; ?>">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<?php echo $alert_text; ?>
			</div>
		</div>
	</div>
	<form id="configForm" name="configForm" method="get" action="<?php //echo $localPath; ?>">
		<!--<input type="checkbox" id="live" name="live" /> Use live network data (if unchecked, an archived dataset is used ***testing only***)-->
	</form>

<?php include "ids_grid_template.php"; ?>

	<!-- MULTI (TRACON/ARTCC) IDS DISPLAY -->
	<div id="multi_ids" class="container-fluid" style="display:none;">
		<div class="landing_menu">
		<div class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fas fa-bars"></i></a>
			<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dLabel">
				<li><a href="#" onclick="returnToLanding('multi_ids');">Return to menu</a></li>
				<li><a href="#" onclick="launchMulti();">Select template</a></li>
				<li><a href="#BUG" data-toggle="modal">Report a bug</a></li>
				<li class="dropdown-item disabled"><a href="#">Remove this template</a></li>
			</ul>
		</div>
		</div>
		<div id="multi_ids_data"></div> <!-- JS dumps the display data here -->
	</div>
	
	<!-- LANDING MENU -->
	<div id="landing" class="container ">
		<div class="row border rounded" style="background-color: black">
			<div class="col-lg-12">
				<div class="row" style="border-bottom:0px">
					<div class="col-lg-3">
						<img src="img/vIDS_logo.png" width="200px"/>
					</div>
					<div class="col-lg-9">
						<span class="ids_title">a collaboration tool for<br/>VATSIM air traffic controllers</span>
					</div>
				</div>
<?php 
// Displays the login prompt if user is not authenticated and the landing menu if user is authenticated
if(!$valid_auth) {
	$url = $redirect_uri . "&response_type=code&scope=vatsim_details";
	print "	<div id=\"auth\" class=\"row\" style=\"border-top:0px\">
			<div class=\"col menu_button\"><br/>
			<a href=\"$sso_endpoint/oauth/authorize?client_id=$client_id&redirect_uri=$url\" class=\"btn btn-lg btn-primary\"><i class=\"fas fa-sign-in-alt fa-lg\"></i><br/>Login</a><br/><br/>
			</div>
			</div>";
}
else {
	print "	<div id=\"menu\" class=\"row\" style=\"border-top:0px\">
			<div class=\"col-lg-6 menu_button\"><br/>
			<a onclick=\"showLocalIDS('local');\" class=\"btn btn-lg btn-block btn-primary\"><i class=\"fas fa-plane-departure fa-lg\"></i><br/>Tower<br/>IDS</a><br/>
			<a onclick=\"showLocalIDS('a80');\" class=\"btn btn-lg btn-block btn-primary\" data-toggle=\"modal\"><i class=\"fas fa-layer-group fa-lg\"></i><br/>A80 Atlanta<br/>Large TRACON IDS</a><br/><br/>
			</div>
			<div class=\"col-lg-6 menu_button\"><br/>
			<a onclick=\"launchMulti();\" class=\"btn btn-lg btn-block btn-primary\" data-toggle=\"modal\"><i class=\"fas fa-compress-arrows-alt fa-lg\"></i><br/>TRACON/ARTCC<br/>IDS</a><br/>
			<a onclick=\"showAboutHelp();\" class=\"btn btn-lg btn-block btn-primary\" data-toggle=\"modal\"><i class=\"far fa-life-ring fa-lg\"></i><br/>Help<br/>& About</a><br/><br/>
			</div>
			</div>";
}
?>
			</div>
		</div>
	</div>

    <!-- Modal container markup for local IDS display -->
    <div id="RECAT" class="modal fade">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">RECAT</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<embed src="https://www.ztlartcc.org/storage/files/RECAT%20Cheatsheet_1604655713.pdf" frameborder="0" style="width:100%; height:70vh" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
   <div id="SOP" class="modal fade">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">SOP</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<embed src="https://www.ztlartcc.org/storage/files/ATL%20ATCT%207110.65I_1607332373.pdf" frameborder="0" style="width:100%; height:70vh" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- SOP Modal for A80 -->
   <div id="aSOP" class="modal fade"> 
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">SOP</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<embed src="https://www.ztlartcc.org/storage/files/A80%207110.65F_1604648656.pdf" frameborder="0" style="width:100%; height:70vh" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
   <div id="LOA" class="modal fade">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">LOA</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<embed src="https://www.ztlartcc.org/storage/files/ATL%20-%20A80%20LOA_1607138614.pdf" frameborder="0" style="width:100%; height:70vh" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- LOA Modal for A80 --> 
   <div id="aLOA" class="modal fade">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">LOA</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<ul class="nav nav-tabs" id="tabContentX">
						<li class="active"><a href="#a80_atl_loa" data-toggle="tab">A80-ATL ATCT</a></li>
						<li><a href="#a80_ztl_loa" data-toggle="tab">A80-ZTL ARTCC</a></li>
						<li><a href="#a80_sat_loa" data-toggle="tab">A80-Sattellite ATCTs</a></li>
					</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="a80_atl_loa">
						<embed src="https://www.ztlartcc.org/storage/files/ATL%20-%20A80%20LOA_1607138614.pdf" frameborder="0" style="width:100%; height:70vh" >
					</div>
					<div class="tab-pane" id="a80_ztl_loa">
						<embed src="https://www.ztlartcc.org/storage/files/A80%20-%20ZTL%20LOA_1602472801.pdf" frameborder="0" style="width:100%; height:70vh" >
					</div>
					<div class="tab-pane" id="a80_sat_loa">
						<embed src="https://www.ztlartcc.org/storage/files/A80-SAT%20ATCT%20LOA_1604650228.pdf" frameborder="0" style="width:100%; height:70vh" >
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	</div>
   <div id="ACFT" class="modal fade">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aircraft Types</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<embed src="resources/2019-10-10_Order_JO_7360.1E_Aircraft_Type_Designators_FINAL.pdf" frameborder="0" style="width:100%; height:70vh" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
    <div id="WX" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ATL Weather & Forecast</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<div id="weather_request"></div> <!-- METAR & TAF go here -->
					<div><img id="radar_loop" src="https://radar.weather.gov/ridge/lite/KFFC_loop.gif" alt="FFC Radar Loop" /></div>
                </div>	
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
    <div id="FREQS" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ATL Tower Frequency List</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<h4>Clearance Delivery</h4>
				<ul>
					<li>Flight Data (FD): N/A</li>
					<li>Clearance Delivery One (CD-1): 118.100</li>
					<li>Clearance Delivery Two (CD-2): 118.700</li>
				</ul>
				<h4>Ground Control</h4>
				<ul>
					<li>Ground Control North (GC-N): 121.900</li>
					<li>Ground Control Center (GC-C): 121.750</li>
					<li>Ground Control South (GC-S): 121.650</li>
					<li>Ground Metering (GM): 125.000</li>
				</ul>
				<h4>Local Control</h4>
				<ul>
					<li>Local Control One (LC-1): 119.100</li>
					<li>Local Control Two (LC-2): 125.320</li>
					<li>Local Control Three (LC-3): 123.850</li>
					<li>Local Control Four (LC-4): 119.300</li>
					<li>Local Control Five (LC-5): 119.500</li>
				</ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
    <div id="aFREQS" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">A80 Facility Frequency List</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<h4>Terminal Arrival Radar (TAR)</h4>
				<ul>
					<li>TAR-H: 127.900</li>
					<li>TAR-D: 128.000</li>
					<li>TAR-L: 128.520</li>
					<li>TAR-Y: 124.720</li>
				</ul>
				<h4>Arrival Radar (AR)</h4>
				<ul>
					<li>AR-O: 124.600</li>
					<li>AR-V: 127.250</li>
					<li>AR-A: 135.370</li>
				</ul>
				<h4>Departure Radar (DR)</h4>
				<ul>
					<li>DR-N: 125.700</li>
					<li>DR-S: 125.650</li>
					<li>DR-I: 121.220</li>
				</ul>
				<h4>Satellite (SAT)</h4>
				<ul>
					<li>Northeast (SAT-P): 126.970</li>
					<li>Northwest (SAT-F): 121.000</li>
					<li>Southwest(SAT-X): 119.800</li>
					<li>Southesat (SAT-G): 128.570</li>
					<li>PDK Final (SAT-Q): 124.300</li>
				</ul>
				<h4>Outer Sectors</h4>
				<ul>
					<li>Macon Low (MCN-M): 124.200</li>
					<li>Macon High (MCN-W): 119.600</li>
					<li>Columbus Low (CSG-Z): 125.500</li>
					<li>Columbus High (CSG-R): 126.550</li>
					<li>Athens Low: 132.470</li>
					<li>Athens High (AHN-3E): 119.870</li>
				</ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
    <div id="ARSPC" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ATL Tower Airspace</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<ul class="nav nav-tabs" id="towerAirspace">
						<li class="active"><a href="#atl_eastops" data-toggle="tab">ATL East Ops</a></li>
						<li><a href="#atl_westops" data-toggle="tab">ATL West Ops</a></li>

					</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="atl_eastops">
						<h4>ATL East Ops</h4>
						<img src="resources/atl-east-airspace.png" atl="ATL east ops" />
					</div>
					<div class="tab-pane" id="atl_westops">
						<h4>ATL West Ops</h4>
						<img src="resources/atl-west-airspace.png" atl="ATL west ops" />
					</div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
    <div id="aARSPC" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">A80 Airspace Diagrams</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<ul class="nav nav-tabs" id="a80Airspace">
						<li class="active"><a href="#atl_bravo" data-toggle="tab">ATL Class B</a></li>
						<li><a href="#a80_tar_east" data-toggle="tab">TAR East Ops</a></li>
						<li><a href="#a80_tar_west" data-toggle="tab">TAR West Ops</a></li>
						<li><a href="#a80_ar_east" data-toggle="tab">AR East Ops</a></li>
						<li><a href="#a80_ar_west" data-toggle="tab">AR West Ops</a></li>
						<li><a href="#a80_dr_dual_east" data-toggle="tab">DR Duals East Ops</a></li>
						<li><a href="#a80_dr_trip_east" data-toggle="tab">DR Trips East Ops</a></li>
						<li><a href="#a80_dr_dual_west" data-toggle="tab">DR Duals West Ops</a></li>
						<li><a href="#a80_dr_trip_west" data-toggle="tab">DR Trips West Ops</a></li>
						<li><a href="#a80_sat_east" data-toggle="tab">Sat East Ops</a></li>
						<li><a href="#a80_sat_west" data-toggle="tab">Sat West Ops</a></li>
						<li><a href="#a80_pdk_final" data-toggle="tab">PD Final (SAT-Q)</a></li>
						<li><a href="#a80_macon" data-toggle="tab">Macon Sector</a></li>
						<li><a href="#a80_columbus" data-toggle="tab">Columbus Sector</a></li>
						<li><a href="#a80_athens" data-toggle="tab">Athens Sector</a></li>
					</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="atl_bravo">
						<h4>ATL Class B</h4>
						<img src="resources/class-b-airspace.png" atl="ATL class B" />
					</div>
					<div class="tab-pane" id="a80_tar_east">
						<h4>A80 TAR East Ops</h4>
						<img src="resources/tar-east-airspace.png" atl="A80 TAR east ops" />
					</div>
					<div class="tab-pane" id="a80_tar_west">
						<h4>A80 TAR West Ops</h4>
						<img src="resources/tar-west-airspace.png" atl="A80 tar west ops" />
					</div>
					<div class="tab-pane" id="a80_ar_east">
						<h4>A80 AR Final East Ops</h4>
						<img src="resources/ar-east-airspace.png" atl="A80 ar east ops" />
					</div>
					<div class="tab-pane" id="a80_ar_west">
						<h4>A80 AR Final West Ops</h4>
						<img src="resources/ar-west-airspace.png" atl="A80 ar west ops" />
					</div>
					<div class="tab-pane" id="a80_dr_dual_east">
						<h4>A80 DR Duals East Ops</h4>
						<img src="resources/dr-east-duals-airspace.png" atl="A80 dr duals east ops" />
					</div>
					<div class="tab-pane" id="a80_dr_trip_east">
						<h4>A80 DR Trips East Ops</h4>
						<img src="resources/dr-east-trips-airspace.png" atl="A80 dr trips east ops" />
					</div>
					<div class="tab-pane" id="a80_dr_dual_west">
						<h4>A80 DR Duals West Ops</h4>
						<img src="resources/dr-west-duals-airspace.png" atl="A80 dr duals west ops" />
					</div>
					<div class="tab-pane" id="a80_dr_trip_west">
						<h4>A80 DR Trips West Ops</h4>
						<img src="resources/dr-west-trips-airspace.png" atl="A80 dr trips west ops" />
					</div>
					<div class="tab-pane" id="a80_sat_east">
						<h4>A80 Satellite East Ops</h4>
						<img src="resources/sat-east-airspace.png" atl="A80 sat east ops" />
					</div>
					<div class="tab-pane" id="a80_sat_west">
						<h4>A80 Satellite West Ops</h4>
						<img src="resources/sat-west-airspace.png" atl="A80 sat west ops" />
					</div>
					<div class="tab-pane" id="a80_pdk_final">
						<h4>A80 PDK Final (SAT-Q)</h4>
						<img src="resources/sat-q-airspace.png" atl="A80 sat q" />
					</div>
					<div class="tab-pane" id="a80_macon">
						<h4>A80 Macon Sector</h4>
						<img src="resources/mcn-airspace.png" atl="A80 mcn" />
					</div>
					<div class="tab-pane" id="a80_columbus">
						<h4>A80 Columbus Sector</h4>
						<img src="resources/csg-airspace.png" atl="A80 csg" />
					</div>
					<div class="tab-pane" id="a80_athens">
						<h4>A80 Athens Sector</h4>
						<img src="resources/ahn-airspace.png" atl="A80 ahn" />
					</div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
    <div id="ROTG" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">RNAV Off The Ground</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div id="weather_request" class="modal-body">
				<img src="resources/ROTG.png" class="img-responsive" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
    <div id="AFLD" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ATL Airfield Config</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form>
                       <div class="form-group row">
                            <label for="inputName" class="col-sm-4 col-form-label">Traffic flow</label>
							<div class="col-sm-2">
								<select id="flow" onclick="setActiveRunways(this);" disabled>
									<option value="EAST">EAST</option>
									<option value="WEST">WEST</option>
									<option selected></option>
								</select>
							</div>
							<div class="col-sm-6">
								<p>Sets traffic flow direction displayed in vIDS.</p>
							</div>
						</div>
                       <div class="form-group row">
                            <label for="inputName" class="col-sm-4 col-form-label">Arrival runways</label>
							<div class="col-sm-2">
								<select id="arr_rwy" onchange="checkDuplicates(this);" multiple disabled>
								</select>
							</div>
							<div class="col-sm-6">
								<p>Sets arrival runways and approach type displayed in vIDS. Hold 'CTRL' and click to select multiple.</p>
							</div>
						</div>
                       <div class="form-group row">
                            <label for="inputName" class="col-sm-4 col-form-label">Departure runways</label>
							<div class="col-sm-2">
								<select id="dep_rwy" onchange="checkDuplicates(this);" multiple disabled>
								</select>
							</div>
							<div class="col-sm-6">
								<p>Sets departure runways and ROTG operation displayed in vIDS. Hold 'CTRL' and click to select multiple.</p>
							</div>
						</div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-4 col-form-label">Full triple arrivals?</label>
							<div class="col-sm-2">
								<input type="checkbox" class="form-control" id="fta">
							</div>
						</div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-4 col-form-label">Full triple departures?</label>
							<div class="col-sm-2">
								<input type="checkbox" class="form-control" id="ftd">
							</div>
						</div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-4 col-form-label">9L departures @ intersection M2</label>
							<div class="col-sm-2">
								<input type="checkbox" class="form-control" id="ninelm2">
							</div>
						</div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-4 col-form-label">LAHSO</label>
							<div class="col-sm-2">
								<input type="checkbox" class="form-control" id="lahso">
							</div>
							<div class="col-sm-6">
								<p>Land and hold short operations in effect.</p>
							</div>
						</div>
                        <div class="form-group">
                            <label for="inputComment">CIC Notices</label>
							<p>*Note: information entered below will only be shown to users viewing the local vIDS display (does not propagate to A80).</p>
                            <textarea class="form-control" id="CIC_text" rows="4"></textarea>
                        </div>
						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input" id="AutoIDS" onclick="manualControl(this);" readonly>
                            <label class="custom-control-label" for="autoIDS">Auto-populate vIDS from network</label>
							<p>Uncheck this box for manual/CIC conrol of flow/arrival/departure information</p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAFLD();">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div id="PIREP" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Pilot Weather Report (PIREP)</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="pirep_entry">
                        <div class="form-group">
                            <label for="urgency">Select Routine/Urgent</label>
                            <select id="urgency" class="form-control">
								<option value="UA" selected>UA - Routine</option>
								<option value="UUA">UUA - Urgent</option>
							</select>
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" id="location" class="form-control" placeholder="KATL">
							<small id="locationHelp" class="form-text text-muted">Use Airport or NAVAID identifiers only</small>
							</select>
                        </div>
                        <div class="form-group">
                            <label for="time">Time</label>
                            <input type="text" id="time" class="form-control" placeholder="0000">
							<small id="timeHelp" class="form-text text-muted">When conditions occurred or were encountered (Zulu)</small>
							</select>
                        </div>
                        <div class="form-group">
                            <label for="altitude">Altitude/Flight Level</label>
                            <input type="text" id="altitude" class="form-control" placeholder="FL310">
							<small id="altitudeHelp" class="form-text text-muted">Examples: FL095, FL310, FLUNKN</small>
							</select>
                        </div>
                        <div class="form-group">
                            <label for="aircraft">Type Aircraft</label>
                            <input type="text" id="aircraft" class="form-control" placeholder="B738">
							<small id="aircraftHelp" class="form-text text-muted">Examples: P28A, RV8, B738, UNKN</small>
							</select>
                        </div>
                        <div class="form-group">
                            <label for="conditions">Flight Conditions/Remarks</label>
                            <textarea id="conditions" class="form-control" rows="3"></textarea>
							<small id="conditionsHelp" class="form-text text-muted">Enter weather conditions (turbulence, icing, windshear) here</small>
							</select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="savePIREP();">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div id="RELIEF" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Relief Briefings</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
				<div class="modal-body">
					<ul class="nav nav-tabs" id="tabContent">
						<li class="active"><a href="#info" data-toggle="tab">Info</a></li>
						<li><a href="#clearance" data-toggle="tab">Clnc Delivery</a></li>
						<li><a href="#lclground" data-toggle="tab">Local/Ground</a></li>
					</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="info">
						<p>Click on the tabs above to access relief briefings from the ATL ATCT SOP, Appendix H.</p><br/><br/>
						<p>NOTE: There must be at least a 4 minute overlap during each position relief briefing: A minimum of 2 minutes prior to receiving the briefing and a minimum of 2 minutes at the end of the briefing. At the beginning of the 2 minutes prior to the briefing, the relieving controller must be monitoring the frequency. Upon completion of the briefing, the controller relieved must monitor the frequency for 2 minutes. </p>
					</div>
					<div class="tab-pane" id="clearance">
						<h4>Appendix H-1: Flight Data/Clearance Delivery Checklist:</h4>
						<ol start="1">
							<li>Status Information Areas: Applicable IDS and PIREP page, etc.</li>
							<li>Equipment Status: Radios (proper frequencies (de)selected), Visibility Range and Center, ATIS, RADAR(s), etc.</li>
							<li>Staffing: Adjacent and inter-facility staffing.</li>
							<li>Airport Conditions: Airspace configuration, Runway(s) in use, runway/taxiway closures, etc.</li>
							<li>Airport Activities: Gate hold procedures, braking action reports, etc.</li>
							<li>Weather: Trends, Windshear, ATIS, PIREPs, SIGMETs, AIRMETs, etc.</li>
							<li>Flow Control: Special programs, etc.</li>
							<li>Special Activities: Events, Evaluations, Emergency, etc.</li>
							<li>Special Instructions: Coordination, CIC instructions, etc.</li> 
							<li>Training in Progress.</li>
							<li>Traffic Information:</li>
							<ol start="a">
								<li>Aircraft standing by for clearance or TMU release, etc.</li>
								<li>PDC eligible flight plans which have not yet been sent a PDC.</li>
								<li>Coordination agreements with other positions.</li>
							</ol>
						</ol>
					</div> 
					<div class="tab-pane" id="lclground">
						<h4>Appendix H-2: Ground & Local Control Checklist:</h4>
						<ol start="1">
							<li>Status Information Areas: Applicable IDS and PIREP page, etc.</li>
							<li>Equipment Status: Radios (proper frequencies (de)selected), Visibility Range and Center, ATIS, RADAR(s), etc.</li>
							<li>Staffing: Adjacent and inter-facility staffing.</li>
							<li>Airport Conditions: Airspace configuration, Runway(s) in use, runway/taxiway closures, etc.</li>
							<li>Airport Activities: Gate hold procedures, braking action reports, etc.</li>
							<li>Weather: Trends, Windshear, ATIS, PIREPs, SIGMETs, AIRMETs, etc.</li>
							<li>Flow Control: Special programs, reportable CLT delays, etc.</li>
							<li>Special Activities: Events, Evaluations, Emergency, etc.</li>
							<li>Special Instructions: Coordination, CIC instructions, LUAW, LAHSO, etc.</li> 
							<li>Training in Progress.</li>
							<li>Verbally State Runway Status: Unavailable, closed, or occupied.</li>
							<li>Traffic Information:</li>
							<ol start="a">
								<li>Status of each aircraft and/or vehicle.</li>
								<li>Point-outs.</li>
								<li>Aircraft affected by Traffic Management Initiatives.</li>
								<li>Coordination agreements with other positions.</li>
								<li>Aircraft holding or standing by for service.</li>
							</ol>
						</ol>
					</div> 
				</div>
				</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
    <div id="aRELIEF" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Relief Briefings</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
				<div class="modal-body">
					<h4>Relief Briefing Checklist:</h4>
					<ol start="1">
						<li>Status Information Areas: Applicable IDS and PIREP page, etc.</li>
						<li>Equipment Status: Radios (proper frequencies (de)selected), Visibility Range and Center, ATIS, RADAR(s), etc.</li>
						<li>Staffing: Adjacent and inter-facility staffing.</li>
						<li>Airport Conditions: Airspace configuration, Runway(s) in use, runway/taxiway closures, etc.</li>
						<li>Weather: Trends, Windshear, ATIS, PIREPs, SIGMETs, AIRMETs, etc.</li>
						<li>Flow Control: Special programs, etc.</li>
						<li>Special Activities: Events, Evaluations, Emergency, etc.</li>
						<li>Special Instructions: Coordination, CIC instructions, etc.</li> 
						<li>Training in Progress.</li>
						<li>Traffic Information:</li>
						<ol start="a">
							<li>Aircraft standing by for clearance or TMU release, etc.</li>
							<li>PDC eligible flight plans which have not yet been sent a PDC.</li>
							<li>Coordination agreements with other positions.</li>
						</ol>
					</ol>
				</div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
    <div id="TMU" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">TMU Information</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="inputComment">Enter TMU info below</label>
                            <textarea class="form-control" id="TMU_text" rows="4"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveTMU();">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div id="CIC" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">A80 CIC Information</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<p>*Note: information entered below will only be shown to users viewing the A80 vIDS display (does not propagate to local).</p>
                    <form>
                        <div class="form-group">
                            <label for="inputComment">Enter CIC notes below</label>
                            <textarea class="form-control" id="A80_CIC_text" rows="4"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveA80CIC();">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div id="EMER" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Emergency Procedures</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<p>Derived from 7110.65<br/>10-2-1. INFORMATION REQUIREMENTS<p/>
				<ol type="a">
				<li>Start assistance as soon as enough information has been obtained upon which to act. Information requirements will vary, depending on the existing situation. Minimum required information for inflight emergencies is:</li>
				<ol start="1">
				<li>Aircraft identification and type.</li>
				<li>Nature of the emergency.</li>
				<li>Pilot's desires.</li>
				</ol>
				<li>After initiating action, obtain the following items or any other pertinent information from the pilot or aircraft operator, as necessary:</li>
				<ol start="1">
				<li>Aircraft altitude.</li>
				<li>Fuel remaining in time.</li>
				<li>Pilot reported weather.</li>
				<li>Pilot capability for IFR flight.</li>
				<li>Time and place of last known position.</li>
				<li>Heading since last known position.</li>
				<li>Airspeed.</li>
				<li>Navigation equipment capability.</li>
				<li>NAVAID signals received.</li>
				<li>Visible landmarks.</li>
				<li>Aircraft color.</li>
				<li>Number of people on board.</li>
				<li>Point of departure and destination.</li>
				<li>Emergency equipment on board.</li>
				</ol></ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
	<div id="launch_multi" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Launch Multi-Airfield IDS</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                <div class="form-group row">
                    <label for="inputName" class="col-sm-8 col-form-label">Select a multi-airfield IDS template:</label>
					<div class="col-sm-2">
				<form>
					<select id="pickMulti" onchange="showMultiIDS();" >
						<option value="X" selected></option>
						<option value="0">ZTL Default</option>
						<?php
						
						foreach (array_filter(glob('data/templates/*.templ'), 'is_file') as $file)
						{
							$path_parts = pathinfo($file);
							$fil = fopen($file,"r");
							echo "<option value=\"" . $path_parts['filename'] . "\">" . fgets($fil) . "</option>";
							fclose($file);
						}
						
						?>
						<option value="?">Create Template</option>
					</select>
				</form>
					</div>
				</div>	
                </div>
            </div>
        </div>
    </div>
	<div id="multi_template" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Multi-Airfield IDS Template Creator</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<p>Use this form to create custom multi-airfield IDS displays. Enter ICAO airfield identifiers of the airfields that you wish to display.<p/>
                <div class="form-group row">
                    <label for="inputName" class="col-sm-4 col-form-label">Template name:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="template_name" />
					</div>
				</div>				
                <div class="form-group row">
                    <label for="inputName" class="col-sm-4 col-form-label">Airfield ICAO ID:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="template_icao" /><input type="button" value="Add" onclick="templateMod('add');" />
					</div>
				</div>
                <div class="form-group row">
                    <label for="inputName" class="col-sm-4 col-form-label">Airfields selected:</label>
					<div class="col-sm-4">
						<select id="template_aflds" multiple readonly></select>
					</div>
					<div class="col-sm-4">
						<input type="button" value="Move Up" onclick="templateMod('up');" /><br/><input type="button" value="Move Down" onclick="templateMod('down');" /><br/><input type="button" value="Remove" onclick="templateMod('rem');" />
					</div>					
				</div>				
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" onclick="templateMod('save');">Save</button>
                </div>
            </div>
        </div>
    </div>
	<div id="BUG" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report a bug</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<p>vIDS is in development. Help the dev team out by reporting a bug!<p/>
                <div class="form-group row">
                    <label for="inputName" class="col-sm-4 col-form-label">Your name:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="bug_report_name" />
					</div>
				</div>			
                <div class="form-group row">
                    <label for="inputName" class="col-sm-4 col-form-label">Tell us what happened:</label>
					<div class="col-sm-8">
						<p>Please use as much detail as possible (where did you see it, what happened, what did you expect to happen, recommendations regarding how we can make it better).</p>
						<textarea class="form-control" id="bug_description"></textarea>
					</div>
				</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" onclick="saveBugReport();">Send report</button>
                </div>
            </div>
        </div>
    </div>
	<div id="about_help" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">vIDS About & Help</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<p>vIDS is the product of a team of controllers from across the VATUSA region and is not officially associated with VATUSA or VATSIM. Its primary function is to enhance the ATC experience from the controller perspective by providing information and collaboration tools that are similar to those used at real ATC facilities.<p/>
					<p>To facilitate sharing information, any changes that you make to vIDS will be stored on the server and displayed to everyone else that is currently viewing the system. Settings such as controller position combinations should only be set by the CIC. Other information, like PIREPs can be entered by any controller.</p>
					<p>vIDS is currently in development. If you notice a bug, please <a href="#" onclick="showBugReportReferal('about_help');">file a bug report.</a></p>
					<p>If you have questions or would like to interact with the development team, <a href="https://discord.gg/bZky9bv697" alt="Discord">feel free to join us on Discord.</a></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

	<!-- For testing only -->
	<!--
	<div id="json_test_dump"></div> 
	-->
</body>
</html>