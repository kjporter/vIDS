<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ids_grid_template.php
		Function: GUI definitions for various IDS user grids
		Created: 6/1/21
		Edited: 
		
		Changes: 
		
	*/
?>
	<!-- LOADING OVERLAY -->
	<div id="loading" class="hideLoad">
	<div>
	<h2>Please wait... vIDS can take up to 15 seconds to initialize</h2>
	<div class="progress">
		<div id="loadingProgress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
	</div>
	</div>
	</div>
	<!-- LOCAL IDS DISPLAY -->
	<div id="local_ids" class="container" style="border:1px solid white;display:none;">
	<form><input type="hidden" id="display_template" value="local" /></form>
		<div id="header" class="row" style="border:2px solid white">
			<div class="col-lg-2" style="text-align:left; vertical-align:middle">
				<img src="img/logo.png" height="50px" style="margin-top:15px"/>
			</div>
			<div class="col-lg-8 ids-header template_local"><?php echo DEFAULT_AFLD_ID; ?> ATCT</div>
			<div class="col-lg-8 ids-header template_a80"><?php echo TRACON_LONG_NAME; ?></div>
			<div class="col-lg-2" style="text-align:right;">
				<a href="#" id="acknowledge" onclick="acknowledgeChanges();" class="btn btn-lg btn-primary" style="margin-top:15px">Acknowledge</a>
				<br/>
				<span id="refresh_countdown" style="color:white">Loading... </span>
				<img src="img/gear-loading.gif" height="25px"/>
			</div>
		</div>
		<div class="row" style="border-right:2px solid white">
			<div class="col-lg-1 atis_code h-100" id="atis_code"></div>
			<div class="col-lg-11">
				<div class="row">
					<div class="col-lg-12 text_grid" id="metar"></div>
				</div>
				<div id="row2" class="row rem-bor">
					<div class="col-lg-3 traffic_flow" id="traffic_flow"></div>
					<div class="col-lg-2 text_grid">
						<span class="cell_header">Departure Rwys</span>
						<div id="local_dep_rwys">
						</div>
					</div>
					<div class="col-lg-2 text_grid">
						<span class="cell_header">Arrival Rwys</span>
						<div id="local_arr_rwys">
						</div>
					</div>
					<div class="col-lg-2 text_grid">
						<span class="cell_header">Trips Config</span>
						<div id="TRIPS_info" onclick="clearStyle(this);" class=""></div>
					</div>
					<div class="col-lg-3">
						<span class="cell_header" onclick="configDepSplit();">Departure Split</span>
						<div id="split_dep_rwys" onclick="configDepSplit();"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row info_grid">
			<div class="col-lg-4">
				<div class="row">
					<div class="col-lg-12 cntlPos">
					<?php controller_display("Local Control",array('LC-1'=>'LC-1','LC-2'=>'LC-2','LC-3'=>'LC-3','LC-4'=>'LC-4','LC-5'=>'LC-5'),array('N'=>'N','C43'=>'C43'),true); ?>
						<br/>
						<div class="scroll_content allblack"></div>
					</div>
				</div>
<!-- Reworked this area of the IDS to give TMU info more real estate -->
<!--		<div class="row template_local">
			<div class="col-lg-12 cntlPos">
			<?php //controller_display("Ground Control",array('GC-N'=>'GC-N','GC-C'=>'GC-C','GC-S'=>'GC-S','GM'=>'GM'),array('LC-1'=>'LC-1','LC-2'=>'LC-2','LC-3'=>'LC-3','LC-4'=>'LC-4','LC-5'=>'LC-5','N'=>'N','C43'=>'C43')); ?>
			</div>
		</div>
		<div class="row template_a80">
			<div class="col-lg-12">
			<?php //controller_display(TRACON_ID . " TAR",array('H'=>'H','D'=>'D','L'=>'L','Y'=>'Y'),array('N'=>'N','S'=>'S','C43'=>'C43')); ?>
			</div>
		</div>
-->	
	</div>
	<div class="col-lg-4">
	<div class="row info_grid">
			<div class="col-lg-12">
			<?php controller_display(TRACON_ID . " Departure",array('N'=>'N','S'=>'S','I'=>'I'),array('C43'=>'C43')); ?>
			<div class="row rem-bor"><div class="col-lg-10">Gates assigned:</div></div>
			<div class="row combines rem-bor">
			<div class="col-lg-4 scroll_content" id="dep_gate_n_container" onclick="setDepartureGates();"><p class="marquee_container"><span id="dep_gate_n">&nbsp;</span></p></div>
			<div class="col-lg-4 scroll_content" id="dep_gate_s_container" onclick="setDepartureGates();"><p class="marquee_container"><span id="dep_gate_s">&nbsp;</span></p></div>
			<div class="col-lg-4 scroll_content" id="dep_gate_i_container" onclick="setDepartureGates();"><p class="marquee_container"><span id="dep_gate_i">&nbsp;</span></p></div>
			</div>			
			</div>
			</div>
<!--		<div class="row info_grid">
			<div class="col-lg-12 cntlPos">
			<?php //controller_display(TRACON_ID . " Satellite",array('P'=>'P','F'=>'F','X'=>'X','G'=>'G','Q'=>'Q'),array('N'=>'N','C43'=>'C43')); ?>
			</div>
			</div>
-->
	</div>
	<div class="col-lg-4"><span class="cell_header">Afld Config</span><div id="AFLD_info" onclick="clearStyle(this);" class=""><br/></div></div>
</div>
<div class="row info_grid dynMargin" style="overflow:hidden; position: relative;">
	<div class="col-lg-4">
		<div class="row template_local">
			<div class="col-lg-12 cntlPos">
			<?php controller_display("Ground Control",array('GC-N'=>'GC-N','GC-C'=>'GC-C','GC-S'=>'GC-S','GM'=>'GM'),array('LC-1'=>'LC-1','LC-2'=>'LC-2','LC-3'=>'LC-3','LC-4'=>'LC-4','LC-5'=>'LC-5','N'=>'N','C43'=>'C43')); ?>
			</div>
		</div>
		<div class="row template_a80">
			<div class="col-lg-12">
			<?php controller_display(TRACON_ID . " TAR",array('H'=>'H','D'=>'D','L'=>'L','Y'=>'Y'),array('N'=>'N','S'=>'S','C43'=>'C43')); ?>
			</div>
		</div>
		<div class="row rem-bor-tp">
			<div class="col-lg-12 template_local">
			<?php controller_display("Clearance Delivery",array('CD-1'=>'CD-1','CD-2'=>'CD-2','FD'=>'FD'),array('GC-N'=>'GC-N','LC-1'=>'LC-1','LC-2'=>'LC-2','N'=>'N','C43'=>'C43')); ?>
			</div>
			<div class="col-lg-12 template_a80" id="grid5x1">
			<?php controller_display(TRACON_ID . " Outer",array('M'=>'M','W'=>'W','Z'=>'Z','R'=>'R','E'=>'E','3E'=>'3E'),array('N'=>'N','P'=>'P','F'=>'F','X'=>'X','G'=>'G','C43'=>'C43')); ?>
			</div>
		</div>
		<div class="row" style="border-bottom:0px">
			<div class="col-lg-12"><span class="cell_header">PIREPs</span><div onclick="clearStyle(this);""><textarea id="PIREP_info" class="txt_low" rows="3" readonly></textarea></div></div>
		</div>	
	</div>
	<div class="col-lg-4">
		<div class="row info_grid">
			<div class="col-lg-12 cntlPos">
			<?php controller_display(TRACON_ID . " Satellite",array('P'=>'P','F'=>'F','X'=>'X','G'=>'G','Q'=>'Q'),array('N'=>'N','C43'=>'C43')); ?>
			</div>
			</div>
		<div class="row rem-bor-tp">
			<div class="col-lg-12" id="grid5x2">
			<?php controller_display(TRACON_ID . " AR",array('O'=>'O','V'=>'V','A'=>'A'),array('N'=>'N','H'=>'H','D'=>'D','C43'=>'C43')); ?>
			</div>
		</div>
		<div class="row" style="border-bottom:0px;">
			<div class="col-lg-12"><span class="cell_header">CIC Notices</span>
<!--
			<div id="CIC_info" class="template_local scroller"></div>
			<div id="A80_CIC_info" class="template_a80 scroller"></div>
-->

			<div class="template_local"><textarea id="CIC_info" class="txt_low" rows="3" readonly></textarea></div>
			<div class="template_a80"><textarea id="A80_CIC_info" class="txt_low" rows="3" readonly></textarea></div>

			</div>
		</div>
	</div>
	<div class="col-lg-4 scroll_wrapper"><span class="cell_header">TMU Information</span>
	<!--<div onclick="clearStyle(this);" class="">-->
	<!--<div class="scroll_wrapper">-->
	<div id="TMU_info" class="scroller"></div>
	<!--</div>-->
	<!--<textarea id="TMU_info" class="txt_low" rows="7" readonly></textarea>-->
	<!--</div>-->
	</div>
</div>
<!-- START A80 SATELLITE & OUTER AIRFIELD DISPLAY -->
	<div class="row template_a80 dynMargin">
		<div class="col-lg-12">
<?php
	$newrow = true;
	for($x=0;$x<count($a80sat);$x++) {
		$str = "";
		if($newrow) {
			$str .= "<div class=\"row\">";
			$newrow = false;
		}
		$str .= "<div class=\"col-lg-4 moveable\" draggable=\"true\" ondragstart=\"dragStarted(event);\" ondragover=\"draggingOver(event);\" ondrop=\"dropped(event);\">
				<input type=\"hidden\" id=\"" . $a80sat[$x]['id'] . "_override\" value=\"false\" />
				<div class=\"dropdown noclear\">
				<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"fas fa-caret-square-down\"></i></a>
				<ul class=\"dropdown-menu\" role=\"menu\" aria-labelledby=\"dLabel\">
					<li class=\"dropdown-header\">" . $a80sat[$x]['id'] . "</li>
					<li class=\"divider\"></li>
					<li><a href=\"#\" onclick=\"airfieldConfig('" . $a80sat[$x]['id'] . "');\">Airfield Config</a></li>
					<li><a href=\"#PROC\" onclick=\"loadProc('" . $a80sat[$x]['id'] . "');\" data-toggle=\"modal\">Instrument Procedures</a></li>
				</ul>
				</div>
				<span class=\"cell_header\">" . $a80sat[$x]['name'] . "</span><span class=\"cab_status\">&nbsp;&nbsp;<span id=\"" . $a80sat[$x]['id'] . "_online_del\" class=\"badge badge-secondary\">D</span>&nbsp;<span id=\"" . $a80sat[$x]['id'] . "_online_gnd\" class=\"badge badge-secondary\">G</span>&nbsp;<span id=\"" . $a80sat[$x]['id'] . "_online_twr\" class=\"badge badge-secondary\">T</span></span>
				<div class=\"op_hours\">" . $a80sat[$x]['hours'] . "</div>
				<div class=\"row rem-bor\">
					<div id=\"" . $a80sat[$x]['id'] . "_atis_code\" class=\"col-lg-3 rem-bor atis_code\"></div>
					<div class=\"col-lg-6\">
						<div id=\"" . $a80sat[$x]['id'] . "_open_closed\" class=\"row rem-bor arrival_info\">
						</div>
						<input type=\"hidden\" id=\"" . $a80sat[$x]['id'] . "_hours_mf\" value=\"" . $a80sat[$x]['MF'] . "\" />
						<input type=\"hidden\" id=\"" . $a80sat[$x]['id'] . "_hours_ss\" value=\"" . $a80sat[$x]['SS'] . "\" />
						<input type=\"hidden\" id=\"" . $a80sat[$x]['id'] . "_hours_dstAdjust\" value=\"" . $a80sat[$x]['DST_Adjust'] . "\" />
						<div id=\"" . $a80sat[$x]['id'] . "_metar\" class=\"row rem-bor\">
						</div>						
					</div>
					<div id=\"" . $a80sat[$x]['id'] . "_runway\" class=\"col-lg-3 apch_type\"></div>
				</div>
			</div>	";
		if(($x+1) % 3 == 0) {
			$str .= "</div>";
			$newrow = true;
		}
		print $str;
	}
?>
	</div>
	</div>
<div id="buttons" class="row">
	<div class="col">
		<div class="btn-group dropup">

			<a href="#" class="btn btn-lg btn-primary dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" href="#"><i class="fas fa-home fa-lg"></i><br/>HOME</a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
				<li><a href="#" onclick="returnToLanding('local_ids');">Return to menu</a></li>
				<li><a href="#BUG" data-toggle="modal">Report a bug</li></li>
			</ul>
		</div>
		<a href="#WX" data-remote="https://www.aviationweather.gov/taf/data?ids=katl&format=decoded&metars=on&date=&submit=Get+TAF+data" class="btn btn-lg btn-primary" data-toggle="modal" data-bs-toggle="modal" data-target="#WX" onClick="fetchWeather('K<?php echo DEFAULT_AFLD_ID; ?>');"><i class="fas fa-cloud fa-lg"></i><br/>WX</a>
		<a href="#RECAT" class="btn btn-lg btn-primary" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-plane-arrival fa-lg"></i><br/>RECAT</a>
		<a href="#FREQS" class="btn btn-lg btn-primary template_local" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-broadcast-tower fa-lg"></i><br/>FREQS</a>
		<a href="#aFREQS" class="btn btn-lg btn-primary template_a80" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-broadcast-tower fa-lg"></i><br/>FREQS</a>
		<a href="#ARSPC" class="btn btn-lg btn-primary template_local" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-map-marked-alt fa-lg"></i><br/>ARSPC</a>
		<a href="#aARSPC" class="btn btn-lg btn-primary template_a80" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-map-marked-alt fa-lg"></i><br/>ARSPC</a>
		<a href="#PROC" class="btn btn-lg btn-primary" data-toggle="modal" data-bs-toggle="modal" onclick="loadProc('K<?php echo DEFAULT_AFLD_ID; ?>');"><i class="fas fa-file-invoice fa-lg"></i><br/>PROC</a>
		<a href="#ROTG" class="btn btn-lg btn-primary" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-plane-departure fa-lg"></i><br/>ROTG</a>
		<a href="#SOP" class="btn btn-lg btn-primary template_local" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-book fa-lg"></i><br/>SOP</a>
		<a href="#aSOP" class="btn btn-lg btn-primary template_a80" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-book fa-lg"></i><br/>SOP</a>
		<a href="#LOA" class="btn btn-lg btn-primary template_local" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-handshake fa-lg"></i><br/>LOA</a>
		<a href="#aLOA" class="btn btn-lg btn-primary template_a80" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-handshake fa-lg"></i><br/>LOA</a>
		<a href="#PIREP" class="btn btn-lg btn-primary" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-headset fa-lg"></i><br/>PIREP</a>
		<a href="#ACFT" class="btn btn-lg btn-primary" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-plane fa-lg"></i><br/>ACFT</a>
		<a href="#RELIEF" class="btn btn-lg btn-primary template_local" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-couch fa-lg"></i><br/>RELIEF</a>
		<a href="#aRELIEF" class="btn btn-lg btn-primary template_a80" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-couch fa-lg"></i><br/>RELIEF</a>
		<a href="#AFLD" class="btn btn-lg btn-primary" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-cogs fa-lg"></i><br/>AFLD</a>
		<a href="#CIC" class="btn btn-lg btn-primary template_a80" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-user-tie fa-lg"></i><br/>CIC</a>
		<a href="#TMU" class="btn btn-lg btn-primary" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-traffic-light fa-lg"></i><br/>TMU</a>
		<a href="#EMER" class="btn btn-lg btn-primary" data-toggle="modal" data-bs-toggle="modal"><i class="fas fa-asterisk fa-lg icon-emergency"></i><br/>EMRG</a>
		<a href="#HELP" class="btn btn-lg btn-primary template_local" data-toggle="modal" data-bs-toggle="modal"><i class="far fa-question-circle"></i><br/>HELP</a>
		<a href="#aHELP" class="btn btn-lg btn-primary template_a80" data-toggle="modal" data-bs-toggle="modal"><i class="far fa-question-circle"></i><br/>HELP</a>
	</div>
</div>
</div>
<?php
function controller_display($heading,$cntlPositions,$selOptions,$edit_toggle=0) { // Creates the controller position display grid
	$edit_link = "";
	$col_size = floor(12 / count($cntlPositions)); // Bootstrap columns always total 12
	$selOptions = array_unique(array_merge($cntlPositions,$selOptions)); // Selections include the facility's control positions
	if($edit_toggle) {
		$edit_link = "&nbsp;<a href=\"#ControllerEdit\" data-toggle=\"modal\"><i class=\"fas fa-edit\"></i></a>";
	}
	$output_str = "<span class=\"cell_header\">$heading</span>$edit_link<div class=\"row rem-bor controllerPositions\">";
	foreach($cntlPositions as $cntlPosition) {
		$output_str .= "<div class=\"col-lg-" . $col_size . "\">$cntlPosition</div>";
	}
	$output_str .= "</div><div class=\"row rem-bor\"><div class=\"col-lg-10\">Combined to:</div></div><div class=\"row combines rem-bor\">";
	$select_options = "<option value=\".\" selected>...</option>";
	foreach($selOptions as $key => $value) {
		$select_options .= "<option value=\"" . $key . "\">" . $value . "</option>";
	}
	foreach($cntlPositions as $cntlPosition => $value) {
	$output_str .= "<div class=\"col-lg-" . $col_size. "\"><select class=\"custom-select mr-sm-2 controllerEdit hideControl\" id=\"" . $cntlPosition . "\" onchange=\"updateCtrlPos();\">
	" . $select_options . "</select><input type=\"text\" class=\"controllerDisplay\" id=\"" . $cntlPosition . "_disp\" size=\"2\" readonly></div>";
	}
	$output_str .= "</div>";
	echo $output_str;
}
?>