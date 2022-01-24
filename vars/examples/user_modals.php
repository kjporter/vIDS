<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: user_modals.php
		Function: Contains user-defined bootstrap modal dialogs
		Created: 7/22/21 (moved from index.php, moved from modal.php)
		Edited: 1/24/22
	*/

?>
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