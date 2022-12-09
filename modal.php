<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: modal.php
		Function: Contains definitions for bootstrap modal dialogs
		Created: 7/22/21 (moved from index.php)
		Edited: 1/24/22 (moved user-defined content to user-modals.php)
		
		Changes: Removed ZTL hard-code references from non-static content. Implemented configurable airfield flows.
	*/
include_once "vars/user_modals.php"; // User-defined modal content moved here
?>
<?php // Added to ease commenting out user defned lines below that moved to user_modals.php
/*
    <!-- 
		Modal container markup for local IDS display 
		Webmaster: Edit the modal content below to fit your use case. These modals are triggered when a user clicks on one of the buttons at the bottom of the vIDS grid display.
		Do not edit below the line that says "DO NOT EDIT BELOW THIS LINE" - those definitions are for dynamic content or otherwise have no practical need for editing.
	-->
	
	<!-- RECAT Information - modal available in local and TRACON view -->
    <div id="RECAT" class="modal fade">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">RECAT</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<embed src="https://www.ztlartcc.org/storage/files/RECAT%20Cheatsheet_1604655713.pdf" frameborder="0" style="width:100%; height:70vh" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- Local SOP - modal available in local view -->
	<div id="SOP" class="modal fade">
        <div class="modal-dialog modal-lg">
			<div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">SOP</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<embed src="https://www.ztlartcc.org/storage/files/ATL%20ATCT%207110.65I_1607332373.pdf" frameborder="0" style="width:100%; height:70vh" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- TRACON SOP - modal available in TRACON view -->
	<div id="aSOP" class="modal fade"> 
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">SOP</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<embed src="https://www.ztlartcc.org/storage/files/A80%207110.65F_1604648656.pdf" frameborder="0" style="width:100%; height:70vh" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- Local LOA - modal available in local view -->
	<div id="LOA" class="modal fade">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">LOA</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<embed src="https://www.ztlartcc.org/storage/files/ATL%20-%20A80%20LOA_1607138614.pdf" frameborder="0" style="width:100%; height:70vh" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- TRACON LOAs - modal available in TRACON view --> 
	<div id="aLOA" class="modal fade">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">LOA</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<ul class="nav nav-tabs" id="tabContentX">
						<li class="active"><a href="#a80_atl_loa" data-toggle="tab">A80-ATL ATCT</a></li>
						<li><a href="#a80_ztl_loa" data-toggle="tab">A80-ZTL ARTCC</a></li>
						<li><a href="#a80_sat_loa" data-toggle="tab">A80-Satelite ATCTs</a></li>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	</div>
	<!-- Aircraft Types - modal available in local and TRACON view -->
	<div id="ACFT" class="modal fade">
        <div class="modal-dialog modal-lg">
           <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Aircraft Types</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<embed src="resources/2019-10-10_Order_JO_7360.1E_Aircraft_Type_Designators_FINAL.pdf" frameborder="0" style="width:100%; height:70vh" >
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- Weater Information - modal available in local and TRACON view -->
	<!-- Use this link to find products for your ARTCC: https://www.weather.gov/aviation/cwsu -->
    <div id="WX" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo DEFAULT_AFLD_ID; ?> Weather & Forecast</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<ul class="nav nav-tabs" id="weatherInfo">
						<li class="active"><a href="#wx_terminal" data-toggle="tab">ATL Terminal</a></li>
						<li><a href="#wx_video" data-toggle="tab">Video Briefing</a></li>
						<li><a href="#wx_gates" data-toggle="tab">A80 Convective Gates</a></li>
						<li><a href="#wx_radar" data-toggle="tab">ZTL Wx Radar</a></li>
						<li><a href="#wx_satellite" data-toggle="tab">ZTL Wx Satellite</a></li>
						<li><a href="#wx_rvr" data-toggle="tab">ATL RVR</a></li>
						<li><a href="#wx_sigmets" data-toggle="tab">ZTL SIGMETS</a></li>
						<li><a href="#wx_prog" data-toggle="tab">National Prog</a></li>
						
					</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="wx_terminal">
						<h4><?php echo DEFAULT_AFLD_ID; ?> Terminal Wx</h4>
						<div id="weather_request"></div> <!-- METAR & TAF are auto-filled here, don't delete or modify this line -->
						<div><img id="radar_loop" src="https://radar.weather.gov/ridge/lite/KFFC_loop.gif" alt="FFC Radar Loop" /></div>
					</div>
					<div class="tab-pane" id="wx_video">
						<h4>ZTL Pre-Duty Video Wx Brief</h4>
						<video id="wx_video_s" controls poster="https://www.weather.gov/images/ztl/Thumbnails/Video_Image.png" preload="none" src="https://www.weather.gov/media/ztl/ZTLPreDutyVideo.mp4">&nbsp;</video>
					</div>
					<div class="tab-pane" id="wx_gates">
						<h4>A80 Convective Gates</h4>
						<a href="https://www.weather.gov/ztl/atlgatefcst" target="_blank"><img id="wx_gates_s" src="https://www.weather.gov/images/ztl/ATLGATES.png" alt="A80" /></a>
					</div>
					<div class="tab-pane" id="wx_radar">
						<h4>ZTL Weather Radar</h4>
						<a href="https://radar.weather.gov/?settings=v1_eyJhZ2VuZGEiOnsiaWQiOm51bGwsImNlbnRlciI6Wy04NS40MDUsMzMuMDIyXSwiem9vbSI6Nn0sImJhc2UiOiJzdGFuZGFyZCIsImNvdW50eSI6ZmFsc2UsImN3YSI6ZmFsc2UsInN0YXRlIjpmYWxzZSwibWVudSI6dHJ1ZSwic2hvcnRGdXNlZE9ubHkiOmZhbHNlfQ%3D%3D#/" target="_blank"><img id="wx_radar_s" src="https://www.weather.gov/images/ztl/ZTL_surface_plot.png" alt="Radar" /></a>
					</div>
					<div class="tab-pane" id="wx_satellite">
						<h4>ZTL Weather Satellite</h4>
						<a href="https://cdn.star.nesdis.noaa.gov/GOES16/ABI/SECTOR/se/13/600x600.jpg" target="_blank"><img id="wx_satellite_s" src="https://cdn.star.nesdis.noaa.gov/GOES16/ABI/SECTOR/se/13/600x600.jpg" alt="Satellite" /></a>
					</div>
					<div class="tab-pane" id="wx_rvr">
						<h4><?php echo DEFAULT_AFLD_ID; ?> RVR</h4>
						<div id="rvr_table"></div><!-- RVR table is auto-filled here, don't delete or modify this line -->
					</div>
					<div class="tab-pane" id="wx_sigmets">
						<h4>ZTL Active SIGMETs</h4>
						<a href="https://www.weather.gov/ztl/ztlmap" target="_blank"><img id="wx_sigmets_s" src="https://www.weather.gov/images/ztl/cwsu_frontmap.png" alt="SIGMETs" /></a>
					</div>
					<div class="tab-pane" id="wx_prog">
						<h4>National Prognostic Chart</h4>
						<a href="https://www.weather.gov/images/ztl/wpc_loop.gif" target="_blank"><img id="wx_prog_s" src="https://www.weather.gov/images/ztl/wpc_loop.gif" alt="Prog" /></a>
					</div>
                </div>
                </div>	
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal" onClick="stopVideo('wx_video_s');">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- Local radio frequencies - modal available in local view -->
    <div id="FREQS" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo DEFAULT_AFLD_ID; ?> Tower Frequency List</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- TRACON radio frequencies - modal available in TRACON view -->
    <div id="aFREQS" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo TRACON_ID; ?> Facility Frequency List</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
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
					<li>DR-N: 135.320</li>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- Local airspace diagrams - modal available in local view -->
    <div id="ARSPC" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo DEFAULT_AFLD_ID; ?> Tower Airspace</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<ul class="nav nav-tabs" id="towerAirspace">
						<li class="active"><a href="#atl_eastops" data-toggle="tab">ATL East Ops</a></li>
						<li><a href="#atl_westops" data-toggle="tab">ATL West Ops</a></li>

					</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="atl_eastops">
						<h4>ATL East Ops</h4>
						<img src="resources/atl-east-airspace.png" alt="ATL east ops" />
					</div>
					<div class="tab-pane" id="atl_westops">
						<h4>ATL West Ops</h4>
						<img src="resources/atl-west-airspace.png" alt="ATL west ops" />
					</div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- TRACON airspace diagrams - modal available in TRACON view -->
    <div id="aARSPC" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo TRACON_ID; ?> Airspace Diagrams</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
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
						<img src="resources/class-b-airspace.PNG" alt="ATL class B" />
					</div>
					<div class="tab-pane" id="a80_tar_east">
						<h4>A80 TAR East Ops</h4>
						<img src="resources/tar-east-airspace.PNG" alt="A80 TAR east ops" />
					</div>
					<div class="tab-pane" id="a80_tar_west">
						<h4>A80 TAR West Ops</h4>
						<img src="resources/tar-west-airspace.PNG" alt="A80 tar west ops" />
					</div>
					<div class="tab-pane" id="a80_ar_east">
						<h4>A80 AR Final East Ops</h4>
						<img src="resources/ar-east-airspace.PNG" alt="A80 ar east ops" />
					</div>
					<div class="tab-pane" id="a80_ar_west">
						<h4>A80 AR Final West Ops</h4>
						<img src="resources/ar-west-airspace.PNG" alt="A80 ar west ops" />
					</div>
					<div class="tab-pane" id="a80_dr_dual_east">
						<h4>A80 DR Duals East Ops</h4>
						<img src="resources/dr-east-duals-airspace.PNG" alt="A80 dr duals east ops" />
					</div>
					<div class="tab-pane" id="a80_dr_trip_east">
						<h4>A80 DR Trips East Ops</h4>
						<img src="resources/dr-east-trips-airspace.PNG" alt="A80 dr trips east ops" />
					</div>
					<div class="tab-pane" id="a80_dr_dual_west">
						<h4>A80 DR Duals West Ops</h4>
						<img src="resources/dr-west-duals-airspace.PNG" alt="A80 dr duals west ops" />
					</div>
					<div class="tab-pane" id="a80_dr_trip_west">
						<h4>A80 DR Trips West Ops</h4>
						<img src="resources/dr-west-trips-airspace.PNG" alt="A80 dr trips west ops" />
					</div>
					<div class="tab-pane" id="a80_sat_east">
						<h4>A80 Satellite East Ops</h4>
						<img src="resources/sat-east-airspace.PNG" alt="A80 sat east ops" />
					</div>
					<div class="tab-pane" id="a80_sat_west">
						<h4>A80 Satellite West Ops</h4>
						<img src="resources/sat-west-airspace.PNG" alt="A80 sat west ops" />
					</div>
					<div class="tab-pane" id="a80_pdk_final">
						<h4>A80 PDK Final (SAT-Q)</h4>
						<img src="resources/sat-q-airspace.PNG" alt="A80 sat q" />
					</div>
					<div class="tab-pane" id="a80_macon">
						<h4>A80 Macon Sector</h4>
						<img src="resources/mcn-airspace.PNG" alt="A80 mcn" />
					</div>
					<div class="tab-pane" id="a80_columbus">
						<h4>A80 Columbus Sector</h4>
						<img src="resources/csg-airspace.PNG" alt="A80 csg" />
					</div>
					<div class="tab-pane" id="a80_athens">
						<h4>A80 Athens Sector</h4>
						<img src="resources/ahn-airspace.PNG" alt="A80 ahn" />
					</div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- RNAV Off-The-Ground reference - modal available in local and TRACON view -->
    <div id="ROTG" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">RNAV Off The Ground</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div id="weather_request" class="modal-body">
				<img src="resources/ROTG.png" class="img-responsive" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- Local relief briefing - modal available in local view -->
    <div id="RELIEF" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Relief Briefings</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- TRACON relief briefing - modal available in TRACON view -->
    <div id="aRELIEF" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Relief Briefings</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>
	<!-- Emergency checklist - modal available in local and TRACON view -->
    <div id="EMER" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Emergency Procedures</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
*/
?>	
<!-- 
	DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE 
	DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE 
	DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE   DO NOT EDIT BELOW THIS LINE 
-->

    <div id="AFLD" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo DEFAULT_AFLD_ID; ?> Airfield Config</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form>
                       <div class="form-group row">
                            <label for="inputName" class="col-sm-4 col-form-label">Traffic flow</label>
							<div class="col-sm-2">
								<select id="flow" onchange="setActiveRunways(this);" disabled>
								<?php
								global $flow_override;
								foreach(array_unique($flow_override) as $flow) {
									print "									<option value=\"$flow\">$flow</option>";
									
								}
								?>
<!--
									<option value="EAST">EAST</option>
									<option value="WEST">WEST</option>
-->
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
						<?php
						// Display customizable airfield options set in config.php
						global $afld_config_options;
						foreach($afld_config_options as $afld_config_option) {
							print "	<div class=\"form-group row\">
										<label for=\"inputName\" class=\"col-sm-4 col-form-label\">$afld_config_option[1]</label>
										<div class=\"col-sm-2\">
											<input type=\"checkbox\" class=\"form-control\" id=\"$afld_config_option[0]\">
										</div>
										<div class=\"col-sm-6\">
											<p>$afld_config_option[2]</p>
										</div>
									</div>";							
						}
						?>
<!--
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
-->
                        <div class="form-group">
                            <label for="inputComment">CIC Notices</label>
							<p>*Note: information entered below will only be shown to users viewing the local vIDS display (does not propagate to A80).</p>
                            <textarea class="form-control" id="CIC_text" rows="4"></textarea>
                        </div>
						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input" id="AutoIDS" onclick="manualControl(this);" readonly>
                            <label class="custom-control-label" for="autoIDS">Auto-populate vIDS from network</label>
							<p>Uncheck this box for manual/CIC control of flow/arrival/departure information</p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAFLD();">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div id="PIREP" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Pilot Weather Report (PIREP)</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
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
                            <input type="text" id="location" class="form-control" placeholder="<?php echo DEFAULT_AFLD_ID; ?>">
							<small id="locationHelp" class="form-text text-muted">Use Airport, NAVAID, or fix identifiers only (no lat/long)</small>
							</select>
                        </div>
                        <div class="form-group">
                            <label for="time">Time</label>
                            <input type="text" id="time" class="form-control" placeholder="0000">
							<small id="timeHelp" class="form-text text-muted">When conditions occurred or were encountered (Zulu), leave blank for current time</small>
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
							<small id="conditionsHelp" class="form-text text-muted">Enter weather conditions (turbulence, icing, windshear) here</small><br>
							<small id="conditionsHelp1" class="form-text text-muted">For turbulence, use these codes: TB (CAT|CHOP|LLWS|MWAVE) (NEG|SMTH-LGT|LGT|LGT-MOD|MOD|MOD-SEV|SEV|SEV-EXTM|EXTM) (ISOL|OCNL|CONT)<br>
							Example: TB CAT LGT OCNL</small><br>
							<small id="conditionsHelp2" class="form-text text-muted">For icing, use these codes: IC (RIME|CLEAR|MIXED) (NEG|NEGclr|TRC|TRC-LGT|LGT|LGT-MOD|MOD|MOD-SEV|HVY|SEV)<br>
							Example: IC MIXED MOD</small>
							</select>
                        </div>
                    </form>
					<p>Note: PIREPs expire 1 hour after they are entered and are auto-purged from this system.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="savePIREP();">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div id="TMU" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">TMU Information</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="inputComment">Enter TMU info below</label>
                            <textarea class="form-control" id="TMU_text" rows="4"></textarea>
                        </div>
                    </form>
					<p>URLs entered in this field will appear as clickable links</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveTMU();">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div id="CIC" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?php echo TRACON_ID; ?> CIC Information</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<p>*Note: information entered below will only be shown to users viewing the <?php echo TRACON_ID; ?> vIDS display (does not propagate to local).</p>
                    <form>
                        <div class="form-group">
                            <label for="inputComment">Enter CIC notes below</label>
                            <textarea class="form-control" id="A80_CIC_text" rows="4"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveA80CIC();">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div id="HELP" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">vIDS Help - Local Display</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<h4>Overview</h4>
				<p>vIDS is a collaboration and information sharing tool. Data entered on your display will populate on the displays of all other users logged 
				in within 15 seconds. Any user that did not make the change will see a change advisory with the updated item highlighted in red and a button 
				asking the user to acknowledge the change. Although all users logged into the system can make changes to the data in vIDS, only the facility 
				CIC should make changes.</p>
				<p>The vIDS system contains multiple display formats, some of which are user-customizable. Critical data is automatically-shared between the 
				different display elements (for example, the <?php echo TRACON_ID; ?> display uses live data elements from the <?php echo DEFAULT_AFLD_ID; ?> local display). Because of this feature, all users 
				benefit from the same data despite viewing different displays.</p>
				<h4>The local display</h4>
				<p>Local view is optimized for use by controllers working the <?php echo DEFAULT_AFLD_ID; ?> tower cab (CIC, LC, GC, CD, GM, FD). The countdown in the upper right 
				corner displays the remainder of the 15-second auto-refresh interval. When this count expires, the system pulls the most current data from sync files 
				and the VATSIM network and refreshes all data on the user's display. Clicking the "refresh" button in your web browser will not speed this process up, 
				as the network limits pulls to 15 seconds in order to manage traffic. Please allow the page to auto-refresh.</p>
				<p>The top line of the grid displays the current ATIS code (or -- if no ATIS), the current METAR, direction of traffic flow, and critical departure 
				and arrival information. The next set of grids display controller combinations - the position is in static text in the top row followed by the 
				controller that the position is combined to in a drop-down menu in the second row. The departure positions also contain a box for the display of 
				departure gates underneath the controller combinations. Clicking on these boxes allows entry of assigned departure gates.</p>
				<p>Located to the right of the controller position combinations, there are display areas for the current airfield configuration and traffic management 
				information. This data can be entered using menus launched via the AFLD and TMU buttons below. At the bottom of the grid, there are display boxes for 
				pilot weather reports (PIREPs) and controller-in-charge (CIC) messages. Similar to the previous displays, this information can be changed through use 
				of the PIREP and AFLD buttons below.</p>
				<h4>Menu buttons</h4>
				<p>At the bottom of the grid, there is a row of buttons that each launch a unique pop-up with additional information or settings.</p>
				<ul>
				<li>HOME: return to landing menu. Also used to submit a bug report.</li>
				<li>WX: button displays local ATL METAR, TAF, and NEXRAD.</li>
				<li>RECAT: displays the current consolidated wake turbulence guidance.</li>
				<li>FREQS: displays the facility frequency map.</li>
				<li>ARSPC: depiction of facility/position airspace.</li>
				<li>ROTG: shows the current RNAV-off-the-ground cheat sheet.</li>
				<li>SOP: launches the facility SOP.</li>
				<li>LOA: displays applicable LOAs.</li>
				<li>PIREP: allows a controller to enter a PIREP.</li>
				<li>ACFT: displays the FAA JO7360.1E Aircraft Type Designators.</li>
				<li>RELIEF: displays a position relief checklist.</li>
				<li>AFLD: allows the CIC to set airfield parameters.</li>
				<li>TMU: allows entry of traffic management information.</li>
				<li>EMER: displays an emergency checklist.</li>
				<li>HELP: launches the help information you are reading.</li>
				</ul>
				<p>vIDS is currently in development. If you notice a bug, please <a href="#" onclick="showBugReportReferal('HELP');">file a bug report.</a></p>
				<p>If you have additional questions or would like to interact with the development team, <a href="https://discord.gg/bZky9bv697" alt="Discord">feel free to join us on Discord.</a></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>	
	<div id="aHELP" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">vIDS Help - TRACON Display</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<h4>Overview</h4>
				<p>vIDS is a collaboration and information sharing tool. Data entered on your display will populate on the displays of all other users logged 
				in within 15 seconds. Any user that did not make the change will see a change advisory with the updated item highlighted in red and a button 
				asking the user to acknowledge the change. Although all users logged into the system can make changes to the data in vIDS, only the facility 
				CIC should make changes.</p>
				<p>The vIDS system contains multiple display formats, some of which are user-customizable. Critical data is automatically-shared between the 
				different display elements (for example, the <?php echo TRACON_ID; ?> display uses live data elements from the <?php echo DEFAULT_AFLD_ID; ?> local display). Because of this feature, all users 
				benefit from the same data despite viewing different displays.</p>
				<h4>The <?php echo TRACON_ID; ?> display</h4>
				<p><?php echo TRACON_ID; ?> view is optimized for use by controllers working the <?php echo TRACON_LONG_NAME; ?>. The countdown in the upper right 
				corner displays the remainder of the 15-second auto-refresh interval. When this count expires, the system pulls the most current data from sync files 
				and the VATSIM network and refreshes all data on the user's display. Clicking the "refresh" button in your web browser will not speed this process up, 
				as the network limits pulls to 15 seconds in order to manage traffic. Please allow the page to auto-refresh.</p>
				<p>The top line of the grid displays the current ATIS code (or -- if no ATIS), the current METAR, direction of traffic flow, and critical departure 
				and arrival information. The next set of grids display controller combinations - the position is in static text in the top row followed by the 
				controller that the position is combined to in a drop-down menu in the second row. The departure positions also contain a box for the display of 
				departure gates underneath the controller combinations. Clicking on these boxes allows entry of assigned departure gates.</p>
				<p>Located to the right of the controller position combinations, there are display areas for the current airfield configuration and traffic management 
				information. This data can be entered using menus launched via the AFLD and TMU buttons below. At the bottom of the grid, there are display boxes for 
				pilot weather reports (PIREPs) and controller-in-charge (CIC) messages. Similar to the previous displays, this information can be changed through use 
				of the PIREP and AFLD buttons below.</p>
				<h4>Satellites & outer fields</h4>
				<p>Below the PIREPs and CIC notices, there is a display containing information for the A80 satellite and outer fields. Each section contains 
				normal tower operating hours, the ATIS code, open/closed status, active runways, and the current METAR. If a local controller is staffing 
				one of these fields, all information will be displayed. If no local controller is online and no ATIS is posted, the field will show "closed" 
				and most of the information will appear as double-dashes.</p>
				<h4>Menu buttons</h4>
				<p>At the bottom of the grid, there is a row of buttons that each launch a unique pop-up with additional information or settings.</p>
				<ul>
				<li>HOME: return to landing menu. Also used to submit a bug report.</li>
				<li>WX: button displays local ATL METAR, TAF, and NEXRAD.</li>
				<li>RECAT: displays the current consolidated wake turbulence guidance.</li>
				<li>FREQS: displays the facility frequency map.</li>
				<li>ARSPC: depiction of facility/position airspace.</li>
				<li>ROTG: shows the current RNAV-off-the-ground cheat sheet.</li>
				<li>SOP: launches the facility SOP.</li>
				<li>LOA: displays applicable LOAs.</li>
				<li>PIREP: allows a controller to enter a PIREP.</li>
				<li>ACFT: displays the FAA JO7360.1E Aircraft Type Designators.</li>
				<li>RELIEF: displays a position relief checklist.</li>
				<li>CIC: allows the CIC to enter notes to controllers.</li>
				<li>TMU: allows entry of traffic management information.</li>
				<li>EMER: displays an emergency checklist.</li>
				<li>HELP: launches the help information you are reading.</li>
				</ul>
				<p>vIDS is currently in development. If you notice a bug, please <a href="#" onclick="showBugReportReferal('aHELP');">file a bug report.</a></p>
				<p>If you have additional questions or would like to interact with the development team, <a href="https://discord.gg/bZky9bv697" alt="Discord">feel free to join us on Discord.</a></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>	
	<div id="mHELP" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">vIDS Help - Multi-Airfield</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<h4>Overview</h4>
				<p>vIDS is a collaboration and information sharing tool. Data entered on your display will populate on the displays of all other users logged 
				in within 15 seconds. Any user that did not make the change will see a change advisory with the updated item highlighted in red and a button 
				asking the user to acknowledge the change. Although all users logged into the system can make changes to the data in vIDS, only the facility 
				CIC should make changes.</p>
				<p>The vIDS system contains multiple display formats, some of which are user-customizable. Critical data is automatically-shared between the 
				different display elements (for example, the A80 display uses live data elements from the ATL local display). Because of this feature, all users 
				benefit from the same data despite viewing different displays.</p>
				<h4>Multi-airfield TRACON/ARTCC display</h4>
				<p>Multi-airfield view is optimized for use by controllers that require situational awareness of multiple airfields (typically, TRACON and ARTCC). 
				Approximately every 15 seconds, the system pulls the most current data from sync files and the VATSIM network and refreshes all data on the user's 
				display. Clicking the "refresh" button in your web browser will not speed this process up, as the network limits pulls to 15 seconds in order to 
				manage traffic. Please allow the page to auto-refresh.</p>
				<h4>Templates</h4>
				<p>Users have the option to select a pre-built template or create a custom template. Templates consist of a list of airfields that the user wants 
				vIDS to display. On most display devices, vIDS will show 6 airfields without the need to scroll. To create a new template list of airfields, click 
				on the TRACON/ARTCC IDS button and use the drop-down to select "Create Template." Enter a name for the template (ex. June FNO). Enter ICAO 
				airfield IDs (ex. KATL), click Add, and then use the controls to order the airfields in your desired display sequence. Click Save. Your template 
				is now available for your use and any other user.</p>
				<h4>Airfield grid</h4>
				<p>After selecting or creating a template, you will see a grid of airfields and associated data. Each airfield is displayed in a similar format 
				for ease of use. The 3-letter FAA ID is displayed vertically on the left side to identify the airfield. Next, the ATIS code is displayed when an 
				ATIS is active for the airfield. If ATIS is offline, double-dashes are displayed. The advertized approach type is displayed in yellow directly 
				above the reported winds and altimeter setting in green. The middle of the display contains the most current METAR in white text. On the far 
				right, the active runway visual range (RVR) is displayed, where available. This display was created to closely replicate what is used in a 
				real TRACON facility.</p>
				<p>vIDS is currently in development. If you notice a bug, please <a href="#" onclick="showBugReportReferal('mHELP');">file a bug report.</a></p>
				<p>If you have additional questions or would like to interact with the development team, <a href="https://discord.gg/bZky9bv697" alt="Discord">feel free to join us on Discord.</a></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
	</div>	
    <div id="DepartureGates" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Assign <?php echo TRACON_ID; ?> Departure Gates</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form>
					<?php
					global $departure_positions;
					foreach($departure_positions as $departure_position) {
						print "<div class=\"form-group row\">
                            <label for=\"inputName\" class=\"col-sm-2 col-form-label\">" . $departure_position . ": </label>
							<div class=\"col-sm-6\">
								<input type=\"text\" id=\"depGate" . $departure_position . "\" />
							</div>
							<div class=\"col-sm-4\">
								<p>Sets assigned departure gate for " . TRACON_ID . " " . $departure_position . " position.</p>
							</div>
						</div>";
					}
					?>
<!--						
                       <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">N: </label>
							<div class="col-sm-6">
								<input type="text" id="depGateN" />
							</div>
							<div class="col-sm-4">
								<p>Sets assigned departure gate for A80 N position.</p>
							</div>
						</div>
                       <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">S: </label>
							<div class="col-sm-6">
								<input type="text" id="depGateS" />
							</div>
							<div class="col-sm-4">
								<p>Sets assigned departure gate for A80 S position.</p>
							</div>
						</div>
						<div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">I: </label>
							<div class="col-sm-6">
								<input type="text" id="depGateI" />
							</div>
							<div class="col-sm-4">
								<p>Sets assigned departure gate for A80 I position.</p>
							</div>
						</div>
-->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="setDepartureGates(true);">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div id="DepartureSplit" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Configure Runway Departure Split</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<p>Use the controls below to configure the runway departure split. Checkboxes turn on/off departure gates. Text boxes can be used to enter SIDs/RNAV fixes.</p>
                    <form>
                       <div class="form-group row"> 
					   <table border="0" class="departure_split">
					   <?php
	
							global $departure_gates;
							$row_header = "<tr><td></td>";
							foreach($departure_gates as $departure_gate) {
								$row_header .= "<th>" . $departure_gate . "</th>";
							}
							$row_header .= "</tr>";
							for($x=1; $x<4; $x++) {
								$row_chk_body = "<tr><th rowspan=\"2\" id=\"splits_rwy_$x\">--</th>";
								$row_txt_body = "";
								$first = true;
								foreach($departure_gates as $departure_gate) {
									$ins = "";
									if($first) {
										$ins = "<input type=\"hidden\" id=\"splits_rwy_id_$x\" />";
										$first = false;
									}
									$row_chk_body .= "<td>$ins<input type=\"checkbox\" id=\"splits_" . $departure_gate . "_$x\" name=\"" . $departure_gate . "\" /></td>";
									$row_txt_body .= "<td><input type=\"text\" id=\"splits_" . $departure_gate . "t_$x\" size=\"4\" /></td>";
								}
							$row_chk_body .= "</tr>";
							$row_txt_body .= "</tr>";
							print $row_header . $row_chk_body . $row_txt_body . "<tr><td>&nbsp;</td></tr>";
							}
							
/*
						for($x=1; $x<4; $x++) {
								print "<tr><td></td><th>N1</th><th>N2</th><th>W2</th><th>W1</th><th>S2</th><th>S1</th><th>E1</th><th>E2</th></tr>
					   <tr><th rowspan=\"2\" id=\"splits_rwy_$x\">--</th><td><input type=\"hidden\" id=\"splits_rwy_id_$x\" /><input type=\"checkbox\" id=\"splits_n1_$x\" name=\"N1\" /></td><td><input type=\"checkbox\" id=\"splits_n2_$x\" name=\"N2\" /></td><td><input type=\"checkbox\" id=\"splits_w2_$x\" name=\"W2\" /></td><td><input type=\"checkbox\" id=\"splits_w1_$x\" name=\"W1\" /></td><td><input type=\"checkbox\" id=\"splits_s2_$x\" name=\"S2\" /></td><td><input type=\"checkbox\" id=\"splits_s1_$x\" name=\"S1\" /></td><td><input type=\"checkbox\" id=\"splits_e1_$x\" name=\"E1\" /></td><td><input type=\"checkbox\" id=\"splits_e2_$x\" name=\"E2\" /></td></tr>
					   <tr><td><input type=\"text\" id=\"splits_n1t_$x\" size=\"4\" /></td><td><input type=\"text\" id=\"splits_n2t_$x\" size=\"4\" /></td><td><input type=\"text\" id=\"splits_w2t_$x\" size=\"4\" />
					   </td><td><input type=\"text\" id=\"splits_w1t_$x\" size=\"4\" /></td><td><input type=\"text\" id=\"splits_s2t_$x\" size=\"4\" /></td><td><input type=\"text\" id=\"splits_s1t_$x\" size=\"4\" /> </td><td><input type=\"text\" id=\"splits_e1t_$x\" size=\"4\" /></td><td><input type=\"text\" id=\"splits_e2t_$x\" size=\"4\" /></td></tr>
						<tr><td>&nbsp;</td></tr>";

							}
*/							
					   ?>
					   </table>
						</div>
                     </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="configDepSplit(true);">Save</button>
                </div>
            </div>
        </div>
    </div>
	<div id="launch_multi" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Launch Multi-Airfield IDS</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                <div class="form-group row">
                    <label for="inputName" class="col-sm-8 col-form-label">Select a multi-airfield IDS template:</label>
					<div class="col-sm-2">
				<form>
					<select id="pickMulti" onchange="showMultiIDS();" >
						<option value="0" selected></option>
						<?php
						if(USE_DB) {
							$template_token = data_read(null,'array',"SELECT token FROM legacy WHERE token LIKE 'templates/_____.templ'");
							$template_name = data_read(null,'array',"SELECT payload FROM legacy WHERE token LIKE 'templates/_____.templ'");
							for($x=0;$x<count($template_token);$x++) {
								echo "<option value=\"" . str_replace('.templ','',str_replace('templates/','',$template_token[$x])) . "\">" . str_getcsv($template_name[$x],",")[0] . "</option>";
							}
						}
						else {
						foreach (array_filter(glob('data/templates/*.templ'), 'is_file') as $file)
						{
							$path_parts = pathinfo($file);
							$fil = fopen($file,"r");
							echo "<option value=\"" . $path_parts['filename'] . "\">" . fgets($fil) . "</option>";
							fclose($fil);
						}
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
                    <h3 class="modal-title">Multi-Airfield IDS Template Creator</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" onclick="templateMod('save');">Save</button>
                </div>
            </div>
        </div>
    </div>
	<div id="BUG" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Report a bug</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<p>vIDS is in development. Help the dev team out by reporting a bug!<p/>
                <div class="form-group row">
                    <label for="inputName" class="col-sm-4 col-form-label">Your name:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="bug_report_name" value="<?php echo $full_name; ?>" />
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" onclick="saveBugReport();">Send report</button>
                </div>
            </div>
        </div>
    </div>
	<div id="about_help" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">vIDS About & Help</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<p>vIDS is the product of a team of controllers from across the VATUSA division and is not officially associated with VATUSA or VATSIM. Its primary function is to enhance the ATC experience from the controller perspective by providing information and collaboration tools that are similar to those used at real ATC facilities.<p/>
					<p>To facilitate sharing information, any changes that you make to vIDS will be stored on the server and displayed to everyone else that is currently viewing the system. Settings such as controller position combinations should only be set by the CIC. Other information, like PIREPs can be entered by any controller.</p>
					<p>vIDS is currently in development. If you notice a bug, please <a href="#" onclick="showBugReportReferal('about_help');">file a bug report.</a></p>
					<p>If you have questions or would like to interact with the development team, <a href="https://discord.gg/bZky9bv697" alt="Discord">feel free to join us on Discord.<br/><img src="img/Discord.png" height="30px" alt="Discord" /></a></p>
					<p>vIDS is open-source software licensed under the GNU GPL v3.0. <a href="https://github.com/kjporter/vIDS" alt="GitHub">Join us on GitHub to contribute to the project.<br/><img src="img/GitHub.png" height="30px" alt="GitHub" /><img src="img/GitHub2.png" height="30px" alt="GitHub" /></a></p>
					<p>This server is running vIDS version <?php echo get_version(); ?></p>
					<!--<p><a onclick=""><i class="fas fa-sign-out-alt fa-lg" style="color:black"></i></a> Click here to logout of your vIDS session</p>-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
	<div id="AirfieldConfig" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Airfield Configuration: <span id="ac_afldId"></span></h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<p>The form below allows you to override vIDS-displayed data. Entries below will be shared with all other users of vIDS at this facility. This should be used to set airfield configuration when a local controller is not online. Caution: Overriding data below will not update ATIS.<p/>
				<div class="form-group row">
                    <label for="inputName" class="col-sm-4 col-form-label">Override rwy/apch:</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="override_rwy_apch" />
					</div>
				</div>				
                <div class="form-group row">
                    <label for="inputName" class="col-sm-4 col-form-label">Override Active?</label>
					<div class="col-sm-8">
						<p><input type="checkbox" id="override_active"  onclick="return false;" /> When the box is checked, vIDS is displaying the manually-entered data above to controllers as the active runway and approach type instead of what is being advertized in ATIS. To clear this override, click the "Clear Override" button.</p>
						<input type="hidden" id="override_afld_id" />
						<input type="button" value="Clear Override" onclick="saveAfldConfigOverride(false);" />
					</div>
				</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" onclick="saveAfldConfigOverride(true);">Save Override</button>
                </div>
            </div>
        </div>
    </div>
	<div id="ControllerEdit" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Controller Positions/Combinations</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<p>The setting below toggles controller edit mode, allowing you to set controller positions in the vIDS display. This setting only impacts
				the interface that you see - other users will not see edit mode unless they enable it (disabled by default).<p/>
                <div class="form-group row">
                    <label for="inputName" class="col-sm-4 col-form-label">Toggle Edit Mode?</label>
					<div class="col-sm-8">
						<p><input type="checkbox" id="controller_edit_active" /> When checked, controller edit mode is active and positions are displayed in vIDS as a selectable drop-down. When unchecked, display mode is active, which provides a clean read-only interface.</p>
					</div>
				</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" onclick="saveControllerEdit();">Save Mode</button>
                </div>
            </div>
        </div>
    </div>
	<div id="PROC" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Instrument Procedures: <span id="PROC_afldId"></span></h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
				<div class="row">
					<div class="col-lg-3">
						<h4>Approaches</h4>
						<ul id="PROC_iap" style="padding:0;">
						</ul>
					</div>
					<div class="col-lg-3">
						<h4>Departures</h4>
						<ul id="PROC_dp" style="padding:0;">
						</ul>
					</div>
					<div class="col-lg-3">
						<h4>Arrivals</h4>
						<ul id="PROC_star" style="padding:0;">
						</ul>
					</div>
					<div class="col-lg-3">
						<h4>Misc</h4>
						<ul id="PROC_misc" style="padding:0;">
						</ul>
					</div>
				</div>
                </div>
				<h3 id="PROC_load">Please wait... loading</h3>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
	<div id="TemplateDelete" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Delete Multi-IDS Template</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<p><span style="color:yellow;font-size:5vw;"><i class="fas fa-exclamation-triangle"></i></span>&nbsp;&nbsp;Are you sure that you want to delete the multi-IDS template: <span id="remTemplate"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" onclick="removeTemplate(true);"><i class="fas fa-trash"></i>&nbsp;Delete</button>
                </div>
            </div>
        </div>
    </div>
	<div id="ADMIN" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">System Administration</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<ul class="nav nav-tabs" id="tabContent">
						<li class="nav-item active"><a class="nav-link" href="#blacklist" data-toggle="tab">Blocklist</a></li>
						<li class="nav-item"><a class="nav-link" href="#whitelist" data-toggle="tab">Allowlist</a></li>
						<li class="nav-item"><a href="#logging" onClick="startLiveLogging();" data-toggle="tab">Live Logging</a></li>
					</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="blacklist">
					<p>Use this control to block users from vIDS by adding a user's CID. When added to this list, users are denied access and given a message to contact the ARTCC staff. Users that are actively logged in
					and using the system are forced out within 15 seconds of being added to the list. Removing a blocklisted CID from the control instentaneously restores access. Enter a CID or click on a blocklisted CID for
					more information about the user.</p>
					<div class="form-group row">
                        <label for="inputName" class="col-sm-4 col-form-label">Enter CID:</label>
						<div class="col-sm-6">
							<input id="blacklistCID" style="width: 100%; max-width: 100%;" type="text" />
						</div>
						<div class="col-sm-2">
							<input type="button" value="Add" onClick="modAccessList('black','add');" />
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-4">
                        <label for="inputName" class="col-form-label">Blocklist:</label><br/><br/>
						<p>Name</p>
						<input id="blacklist_name" type="text" readonly /><br/>
						<p>Rating</p>
						<input id="blacklist_rating" type="text" readonly /><br/>
						<p>Home ARTCC</p>
						<input id="blacklist_facility" type="text" readonly /><br/>
						</div>
						<div class="col-sm-6">
							<select id="blacklist_select" size="6" style="width: 100%; max-width: 100%;" onClick="document.getElementById('blacklistCID').value = this.value;"></select>
						</div>
						<div class="col-sm-2">
							<input type="button" value="Lookup" onClick="modAccessList('black','lookup');" /><br/><br/>
							<input type="button" value="Remove" onClick="modAccessList('black','remove');" />
						</div>
					</div>
                </div>
					<div class="tab-pane" id="whitelist">
					<p>Use this control to allow users access to vIDS by adding a user's CID. When added to this list, users are granted access despite their ARTCC association or VATSIM controller rating. Use this control to temporarily add a controller (ex: grant a member of the ACE team access for an event.) Removing an allowlisted CID from the control instentaneously removes access. Enter a CID or click on a allowlisted CID for more information about the user.</p>
					<div class="form-group row">
                        <label for="inputName" class="col-sm-4 col-form-label">Enter CID:</label>
						<div class="col-sm-6">
							<input id="whitelistCID" style="width: 100%; max-width: 100%;" type="text" />
						</div>
						<div class="col-sm-2">
							<input type="button" value="Add" onClick="modAccessList('white','add');" />
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-4">
                        <label for="inputName" class="col-form-label">Allowlist:</label><br/><br/>
						<p>Name</p>
						<input id="whitelist_name" type="text" readonly /><br/>
						<p>Rating</p>
						<input id="whitelist_rating" type="text" readonly /><br/>
						<p>Home ARTCC</p>
						<input id="whitelist_facility" type="text" readonly /><br/>
						</div>
						<div class="col-sm-6">
							<select id="whitelist_select" size="6" style="width: 100%; max-width: 100%;" onClick="document.getElementById('whitelistCID').value = this.value;"></select>
						</div>
						<div class="col-sm-2">
							<input type="button" value="Lookup" onClick="modAccessList('white','lookup');" /><br/><br/>
							<input type="button" value="Remove" onClick="modAccessList('white','remove');" />
						</div>
					</div>
                </div>
				<div class="tab-pane" id="logging">
				<h4>Access Log</h4>
				<textarea id="access_log" style="width: 100%; max-width: 100%; overflow-y:scroll;" rows="6"></textarea>
				<h4>System Log</h4>
				<textarea id="system_log" style="width: 100%; max-width: 100%; overflow-y:scroll;" rows="6"></textarea>
				<p>Download system events log <a href="data/system.log" target="_blank">system.log</a></p>
				<p>Download system access log <a href="data/access.log" target="_blank">access.log</a></p>
				</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal" onClick="clearCIDfield();">Close</button>
                </div>
            </div>
        </div>
    </div>
	<div id="PRIVACY" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Privacy Policy & Terms and Conditions</h3>
                    <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<h4>Information We Collect</h4>
					<p>To help enhance the virtual air traffic control experience, we collect information across a series of products and services all to enhance the end user's experience. We collect information in the following ways:</p>
					<ul>
					<li>Information you give us. When you register an account with VATSIM and use that account to sign-in on vIDS, information that is generally considered personal is given to us from you and VATSIM to include, but is not limited to: your name and email address.</li>
					<li>Information we get from you. Some other information is passed by your computer or electronic device, web browser, and VATSIM client. Information can include: your IP address, web browser type and version, device specific information (such as operating system, unique device identifiers and mobile network information). This information may be linked to your account.</li>
					<li>Log Information. Each time you perform an action on vIDS, your action is logged. Information logged can include: type of action, data being sent and received, IP address the request originated from, software used to make the request, identification cookies, and the results of the request.</li>
					<li>Location Information. Your location information may be associated with each request through geolocation against the originating IP address, information given to us by you or given to us by you through VATSIM.</li>
					<li>Storage Location. Data is stored and encrypted on services owned or leased by vIDS within the United States.</li>
					</ul>
					<h4>Information Usage</h4>
					<p>To help enhance the virtual air traffic control experience, we collect information across a series of products and services all to enhance the user experience.<br/>We use the information collected to provide, maintain, protect and improve our services.<br/>The information we collect is maintained with confidentiality to the extent possible. The following information is shared with vIDS associated facilities:</p>
					<ul>
					<li>VATSIM CERT Identification Number (CID)</li>
					<li>Your name</li>
					<li>Your VATSIM achievements and ratings</li>
					<li>Your VATSIM associated email address</li>
					<li>ARTCC facility associations, VATSIM region and division associations</li>
					</ul>
					<p>vIDS does not make any personal information available to the public, however the following information may be seen by users logged into the system:</p>
					<ul>
					<li>VATSIM CERT Identification Number (CID)</li>
					<li>Your name</li>
					<li>ARTCC staff associations and ARTCC staff email addresses</li>
					<li>ARTCC facility associations, VATSIM region and division associations</li>
					<li>VATSIM achievements and ratings</li>
					</ul>
					<p>The following information is collected and may be used to protect our services, up to and including cooperation with legal requests for information from law enforcement agencies:</p>
					<ul>
					<li>The above listed public information,</li>
					<li>All IP addresses used and associated with your account</li>
					<li>Geolocation against aforementioned IP addresses</li>
					<li>Activities performed with vIDS web services</li>
					</ul>
					<p>We may store identification tokens and other limited information on your electronic device through web storage or cookie usage.</p>
					<h4>Who We Share With</h4>
					<p>The information we collect may be shared, in limited capacities, with the following:</p>
					<ul>
					<li>Virtual Air Traffic Simulation Network (www.vatsim.net)</li>
					<li>VATUSA (www.vatusa.net)</li>
					<li>Other Virtual ARTCC partners</li>
					<li>Law enforcement agencies</li>
					</ul>
					<p>For more information on what is shared with whom, please see "Information Usage".</p>
					<h4>Cookie Usage</h4>
					<p>We use various technologies to collect and store information when you visit and use a vIDS service. This may include a cookie or other similar technologies to identify your browser or device.<br/>Our cookies are mainly used as a means of tracking virtual users across vIDS services. This allows us to know who is requesting and using our services, provide authentication and authorization checks to restricted areas.<br/>You may choose to disable cookie usage via your browser, but know that doing so will prevent access and use across restricted areas of the website and severely degrade your experience.</p>
					<h4>Opt Out</h4>
					<p>Given the nature of our services, it is not possible to opt out of data collection and use our services. But if you desire to opt out and no longer desire to use our services, we will purge all information we have collected upon written request.<br/>The first step is to deactivate and request VATSIM to purge your data. Please head to <a href="https://membership.vatsim.net" target="_blank">https://membership.vatsim.net</a> to do so.<br/>After VATSIM has purged your data, please send a written request to your virtual ARTCC's Air Traffic Manager via email. It is a manual process, so please allow up to 30 days for information to be purged. You will receive an email response once the data has been purged.<br/>Note vIDS cannot guarantee that information collected by parties outside of vIDS will be purged in the process.</p>
					<h4>Disclaimer!</h4>
					<p>All information on this website is for flight simulation use only and is not to be used for real world navigation or flight. This site is not affiliated with ICAO, the FAA, any actual ARTCC, or any other real world aerospace entity.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>