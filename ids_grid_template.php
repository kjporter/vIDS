	<!-- LOCAL IDS DISPLAY -->
	<div id="local_ids" class="container" style="border:1px solid white;display:none;">
	<form><input type="hidden" id="display_template" value="local" /></form>
		<div id="header" class="row" style="border:2px solid white">
			<div class="col-lg-2" style="text-align:left">
				<!--<img src="logo.png" height="50px"/>-->
			</div>
			<div class="col-lg-7 ids-header template_local">KATL ATCT</div> <!-- if the display is jacked, this used to be 4 col width... -->
			<div class="col-lg-7 ids-header template_a80">A80 Atlanta Large TRACON</div>
			<div class="col-lg-3" style="text-align:right">
				<a href="" id="acknowledge" onclick="acknowledgeChanges();" class="btn btn-lg btn-primary" data-toggle="modal">Acknowledge</a>
				<br/>
				<span id="refresh_countdown" style="color:white">Loading... </span>
				<img src="img/gear-loading.gif" height="25px"/>
			</div>
		</div>
		<div class="row" style="border-right:2px solid white">
			<div class="col-lg-1 atis_code" id="atis_code"></div>
			<div class="col-lg-11">
				<div class="row">
					<div class="col-lg-12" id="metar"></div>
				</div>
				<div id="row2" class="row rem-bor">
					<div class="col-lg-3 traffic_flow" id="traffic_flow"></div>
					<div class="col-lg-2">
						<span class="cell_header">Departure Rwys</span>
						<div id="local_dep_rwys">
						</div>
					</div>
					<div class="col-lg-2">
						<span class="cell_header">Arrival Rwys</span>
						<div id="local_arr_rwys">
						</div>
					</div>
					<div class="col-lg-2">
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
		<div class="row">
			<div class="col-lg-4">
				<div class="row">
					<div class="col-lg-12">
						<span class="cell_header">Local Control</span>
						<div class="row rem-bor">
							<div class="col-lg-2">LC-1</div>
							<div class="col-lg-2">LC-2</div>
							<div class="col-lg-2">LC-3</div>
							<div class="col-lg-2">LC-4</div>
							<div class="col-lg-2">LC-5</div>
						</div>
						<div class="row rem-bor">
							<div class="col-lg-10">Combined to:</div>
						</div>
						<div class="row combines rem-bor">
							<div class="col-lg-2">
								<select class="custom-select mr-sm-2" id="LC1" onchange="updateCtrlPos();">
				<?php
					$local_select_options = "	<option value=\".\" selected>...</option>
												<option value=\"LC1\">LC-1</option>
												<option value=\"LC2\">LC-2</option>
												<option value=\"LC3\">LC-3</option>
												<option value=\"LC4\">LC-4</option>
												<option value=\"LC5\">LC-5</option>
												<option value=\"N\">N</option>
												<option value=\"C43\">C43</option>";
					echo $local_select_options;
				?>
								</select>
							</div>
							<div class="col-lg-2">
								<select class="custom-select mr-sm-2" id="LC2" onchange="updateCtrlPos();">
									<?php echo $local_select_options; ?>
								</select>
							</div>
							<div class="col-lg-2">
								<select class="custom-select mr-sm-2" id="LC3" onchange="updateCtrlPos();">
									<?php echo $local_select_options; ?>
								</select>
							</div>
							<div class="col-lg-2">
								<select class="custom-select mr-sm-2" id="LC4" onchange="updateCtrlPos();">
									<?php echo $local_select_options; ?>
								</select>
							</div>
							<div class="col-lg-2">
								<select class="custom-select mr-sm-2" id="LC5" onchange="updateCtrlPos();">
									<?php echo $local_select_options; ?>
								</select>
							</div>
						</div>
						<br/>
						<div class="scroll_content allblack"></div>
					</div>
				</div>
		<div class="row template_local">
			<div class="col-lg-12"><span class="cell_header">Ground Control</span>
			<div class="row rem-bor" style="text-align:center">
					<div class="col-lg-3">GC-N</div>
					<div class="col-lg-3">GC-C</div>
					<div class="col-lg-3">GC-S</div>
					<div class="col-lg-3">GM</div>
				</div>
				<div class="row rem-bor"><div class="col-lg-10">Combined to:</div></div>
				<div class="row combines rem-bor">
				<div class="col-lg-3">
				<select class="custom-select mr-sm-2" id="GCN" onchange="updateCtrlPos();">
				<?php
					$ground_select_options = "	<option value=\".\" selected>...</option>
												<option value=\"GCN\">GC-N</option>
												<option value=\"GCC\">GC-C</option>
												<option value=\"GCS\">GC-S</option>
												<option value=\"GM\">GM</option>
												<option value=\"LC1\">LC-1</option>
												<option value=\"LC2\">LC-2</option>
												<option value=\"LC3\">LC-3</option>
												<option value=\"LC4\">LC-4</option>
												<option value=\"LC5\">LC-5</option>
												<option value=\"N\">N</option>
												<option value=\"C43\">C43</option>";
					echo $ground_select_options;
				?>
			</select>
			</div>
			<div class="col-lg-3">
			<select class="custom-select mr-sm-2" id="GCC" onchange="updateCtrlPos();">
			<?php echo $ground_select_options; ?>
			</select>
			</div>
			<div class="col-lg-3">
			<select class="custom-select mr-sm-2" id="GCS" onchange="updateCtrlPos();">
			<?php echo $ground_select_options; ?>
			</select>
			</div>
			<div class="col-lg-3">
			<select class="custom-select mr-sm-2" id="GM" onchange="updateCtrlPos();">
			<?php echo $ground_select_options; ?>
			</select>
			</div>
			</div>
			</div>
		</div>
		<div class="row template_a80">
			<div class="col-lg-12"><span class="cell_header">A80 TAR</span>
			<div class="row rem-bor" style="text-align:center">
					<div class="col-lg-3">H</div>
					<div class="col-lg-3">D</div>
					<div class="col-lg-3">L</div>
					<div class="col-lg-3">Y</div>
				</div>
				<div class="row rem-bor"><div class="col-lg-10">Combined to:</div></div>
				<div class="row combines rem-bor">
				<div class="col-lg-3">
				<select class="custom-select mr-sm-2" id="H" onchange="updateCtrlPos();">
				<?php
					$tar_select_options = "	<option value=\".\" selected>...</option>
												<option value=\"H\">H</option>
												<option value=\"D\">D</option>
												<option value=\"L\">L</option>
												<option value=\"Y\">Y</option>
												<option value=\"N\">N</option>
												<option value=\"S\">S</option>
												<option value=\"C43\">C43</option>";
					echo $tar_select_options;
				?>
			</select>
			</div>
			<div class="col-lg-3">
			<select class="custom-select mr-sm-2" id="D" onchange="updateCtrlPos();">
			<?php echo $tar_select_options; ?>
			</select>
			</div>
			<div class="col-lg-3">
			<select class="custom-select mr-sm-2" id="L" onchange="updateCtrlPos();">
			<?php echo $tar_select_options; ?>
			</select>
			</div>
			<div class="col-lg-3">
			<select class="custom-select mr-sm-2" id="Y" onchange="updateCtrlPos();">
			<?php echo $tar_select_options; ?>
			</select>
			</div>
			</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
	<div class="row">
			<div class="col-lg-12"><span class="cell_header">A80 Departure</span>
			<div class="row rem-bor" style="text-align:center">
					<div class="col-lg-4">N</div>
					<div class="col-lg-4">S</div>
					<div class="col-lg-4">I</div>
				</div>
				<div class="row rem-bor"><div class="col-lg-10">Combined to:</div></div>
				<div class="row combines rem-bor">
				<div class="col-lg-4">
				<select class="custom-select mr-sm-2" id="N" onchange="updateCtrlPos();">
				<?php
					$clnc_select_options = "	<option value=\".\" selected>...</option>
												<option value=\"N\">N</option>
												<option value=\"S\">S</option>
												<option value=\"I\">I</option>
												<option value=\"C43\">C43</option>";
					echo $clnc_select_options;
				?>
			</select>
			</div>
			<div class="col-lg-4">
			<select class="custom-select mr-sm-2" id="S" onchange="updateCtrlPos();">
			<?php echo $clnc_select_options; ?>
			</select>
			</div>
			<div class="col-lg-4">
			<select class="custom-select mr-sm-2" id="I" onchange="updateCtrlPos();">
			<?php echo $clnc_select_options; ?>
			</select>
			</div>
			</div>
			<div class="row rem-bor"><div class="col-lg-10">Gates assigned:</div></div>
			<div class="row combines rem-bor">
			<div class="col-lg-4 scroll_content" id="dep_gate_n_container" onclick="setDepartureGates();"><p class="marquee_container"><span id="dep_gate_n">&nbsp;<!--<input type="text" id="dep_gate_n" class="scroll_content" onclick="setDepartureGates();" />--></span></p></div>
			<div class="col-lg-4 scroll_content" id="dep_gate_s_container" onclick="setDepartureGates();"><p class="marquee_container"><span id="dep_gate_s">&nbsp;<!--<input type="text" id="dep_gate_s" class="scroll_content" onclick="setDepartureGates();" />--></span></p></div>
			<div class="col-lg-4 scroll_content" id="dep_gate_i_container" onclick="setDepartureGates();"><p class="marquee_container"><span id="dep_gate_i">&nbsp;<!--<input type="text" id="dep_gate_i" class="scroll_content" onclick="setDepartureGates();" />--></span></p></div>
			</div>				
			</div>
			</div>
		<div class="row">
			<div class="col-lg-12">
<span class="cell_header">A80 Satellite</span>
				<div class="row rem-bor" style="text-align:center">
					<div class="col-lg-2">P</div>
					<div class="col-lg-2">F</div>
					<div class="col-lg-2">X</div>
					<div class="col-lg-2">G</div>
					<div class="col-lg-2">Q</div>
				</div>
				<div class="row rem-bor"><div class="col-lg-10">Combined to:</div></div>
				<div class="row combines rem-bor">
				<div class="col-lg-2">
				<select class="custom-select mr-sm-2" id="P" onchange="updateCtrlPos();">
				<?php
					$local_select_options = "	<option value=\".\" selected>...</option>
												<option value=\"P\">P</option>
												<option value=\"F\">F</option>
												<option value=\"X\">X</option>
												<option value=\"G\">G</option>
												<option value=\"Q\">Q</option>
												<option value=\"N\">N</option>
												<option value=\"C43\">C43</option>";
					echo $local_select_options;
				?>
			</select>
			</div>
			<div class="col-lg-2">
			<select class="custom-select mr-sm-2" id="F" onchange="updateCtrlPos();">
			<?php echo $local_select_options; ?>
			</select>
			</div>
			<div class="col-lg-2">
			<select class="custom-select mr-sm-2" id="X" onchange="updateCtrlPos();">
			<?php echo $local_select_options; ?>
			</select>
			</div>
			<div class="col-lg-2">
			<select class="custom-select mr-sm-2" id="G" onchange="updateCtrlPos();">
			<?php echo $local_select_options; ?>
			</select>
			</div>
			<div class="col-lg-2">
			<select class="custom-select mr-sm-2" id="Q" onchange="updateCtrlPos();">
			<?php echo $local_select_options; ?>
			</select>
			</div>
			</div>			
			</div>
			</div>
			
	</div>
	<div class="col-lg-4"><span class="cell_header">Afld Config</span><div id="AFLD_info" onclick="clearStyle(this);" class=""><br/></div></div>
</div>
<div class="row">
	<div class="col-lg-4">
		<div class="row rem-bor-tp">
			<div class="col-lg-12 template_local"><span class="cell_header">Clearance Delivery</span>
			<div class="row rem-bor" style="text-align:center">
					<div class="col-lg-4">CD-1</div>
					<div class="col-lg-4">CD-2</div>
					<div class="col-lg-4">FD</div>
				</div>
				<div class="row rem-bor"><div class="col-lg-10">Combined to:</div></div>
				<div class="row combines rem-bor">
				<div class="col-lg-4">
				<select class="custom-select mr-sm-2" id="CD1" onchange="updateCtrlPos();">
				<?php
					$clnc_select_options = "	<option value=\".\" selected>...</option>
												<option value=\"CD1\">CD-1</option>
												<option value=\"CD2\">CD-2</option>
												<option value=\"FD\">FD</option>
												<option value=\"FD\">GC-N</option>
												<option value=\"LC1\">LC-1</option>
												<option value=\"LC2\">LC-2</option>
												<option value=\"N\">N</option>
												<option value=\"C43\">C43</option>";
					echo $clnc_select_options;
				?>
			</select>
			</div>
			<div class="col-lg-4">
			<select class="custom-select mr-sm-2" id="CD2" onchange="updateCtrlPos();">
			<?php echo $clnc_select_options; ?>
			</select>
			</div>
			<div class="col-lg-4">
			<select class="custom-select mr-sm-2" id="FD" onchange="updateCtrlPos();">
			<?php echo $clnc_select_options; ?>
			</select>
			</div>
			</div>
			</div>
			<div class="col-lg-12 template_a80"><span class="cell_header">A80 Outer</span>
			<div class="row rem-bor" style="text-align:center">
					<div class="col-lg-3">M</div>
					<div class="col-lg-3">W</div>
					<div class="col-lg-3">Z</div>
					<div class="col-lg-3">R</div>

				</div>
				<div class="row rem-bor"><div class="col-lg-10">Combined to:</div></div>
				<div class="row combines rem-bor">
				<div class="col-lg-3">
				<select class="custom-select mr-sm-2" id="M" onchange="updateCtrlPos();">
				<?php
					$outer_select_options = "	<option value=\".\" selected>...</option>
												<option value=\"M\">M</option>
												<option value=\"W\">W</option>
												<option value=\"Z\">Z</option>
												<option value=\"R\">R</option>
												<option value=\"P\">P</option>
												<option value=\"F\">F</option>
												<option value=\"X\">X</option>
												<option value=\"G\">G</option>
												<option value=\"C43\">C43</option>";
					echo $outer_select_options;
				?>
			</select>
			</div>
			<div class="col-lg-3">
			<select class="custom-select mr-sm-2" id="W" onchange="updateCtrlPos();">
			<?php echo $outer_select_options; ?>
			</select>
			</div>
			<div class="col-lg-3">
			<select class="custom-select mr-sm-2" id="Z" onchange="updateCtrlPos();">
			<?php echo $outer_select_options; ?>
			</select>
			</div>
			<div class="col-lg-3">
			<select class="custom-select mr-sm-2" id="R" onchange="updateCtrlPos();">
			<?php echo $outer_select_options; ?>
			</select>
			</div>
			</div>
			</div>
		</div>
		<div class="row" style="border-bottom:0px">
			<div class="col-lg-12"><span class="cell_header">PIREPs</span><div onclick="clearStyle(this);""><textarea id="PIREP_info" class="txt_low" rows="3" readonly></textarea></div></div>
		</div>	
	</div>
	<div class="col-lg-4">
		<div class="row rem-bor-tp">
			<div class="col-lg-12"><span class="cell_header">A80 Arrival</span>
			<div class="row rem-bor" style="border-top:0px; border-bottom:0px; text-align:center">
					<div class="col-lg-4">O</div>
					<div class="col-lg-4">V</div>
					<div class="col-lg-4">A</div>
				</div>
				<div class="row rem-bor" style="border-top:0px; border-bottom:0px"><div class="col-lg-10">Combined to:</div></div>
				<div class="row combines rem-bor" style="border-top:0px">
				<div class="col-lg-4">
				<select class="custom-select mr-sm-2" id="O" onchange="updateCtrlPos();">
				<?php
					$clnc_select_options = "	<option value=\".\" selected>...</option>
												<option value=\"O\">O</option>
												<option value=\"V\">V</option>
												<option value=\"A\">A</option>
												<option value=\"N\">N</option>
												<option value=\"H\">H</option>
												<option value=\"D\">D</option>
												<option value=\"C43\">C43</option>";
					echo $clnc_select_options;
				?>
			</select>
			</div>
			<div class="col-lg-4">
			<select class="custom-select mr-sm-2" id="V" onchange="updateCtrlPos();">
			<?php echo $clnc_select_options; ?>
			</select>
			</div>
			<div class="col-lg-4">
			<select class="custom-select mr-sm-2" id="A" onchange="updateCtrlPos();">
			<?php echo $clnc_select_options; ?>
			</select>
			</div>
			</div>
			</div>
		</div>
		<div class="row" style="border-bottom:0px">
			<div class="col-lg-12"><span class="cell_header">CIC Notices</span>
			<div class="template_local"><textarea id="CIC_info" class="txt_low" rows="3" readonly></textarea></div>
			<div class="template_a80"><textarea id="A80_CIC_info" class="txt_low" rows="3" readonly></textarea></div>
			</div>
		</div>
	</div>
	<div class="col-lg-4"><span class="cell_header">TMU Information</span><div onclick="clearStyle(this);" class=""><textarea id="TMU_info" class="txt_low" rows="7" readonly></textarea></div></div>
</div>
<!-- START A80 SATELLITE & OUTER AIRFIELD DISPLAY -->
	<div class="row template_a80">
		<div class="col-lg-12">
<?php
	$pdk = array("id"=>"KPDK","name"=>"Peachtree-Dekalb (PDK)","hours"=>"1130–0400Z‡ Mon–Fri, 1200–0400Z‡ Sat–Sun");
	$fty = array("id"=>"KFTY","name"=>"Fulton County (FTY)","hours"=>"Attended continuously");
	$mge = array("id"=>"KMGE","name"=>"Dobbins ARB (MGE)","hours"=>"1200–0400Z‡");
	$ryy = array("id"=>"KRYY","name"=>"Cobb Co/McCollum (RYY)","hours"=>"1200–0400Z‡");
	$lzu = array("id"=>"KLZU","name"=>"Gwinnette Co (LZU)","hours"=>"1200–0200Z‡");
	$ahn = array("id"=>"KAHN","name"=>"Athens (AHN)","hours"=>"1300–0100Z‡");
	$mcn = array("id"=>"KMCN","name"=>"Macon Regional (MCN)","hours"=>"1300–0100Z‡");
	$wrb = array("id"=>"KWRB","name"=>"Robins AFB (WRB)","hours"=>"Attended continuously");
	$csg = array("id"=>"KCSG","name"=>"Columbus (CSG)","hours"=>"1400–0200Z‡");
	$a80sat = array($pdk,$fty,$mge,$ryy,$lzu,$ahn,$mcn,$wrb,$csg);
	$newrow = true;
	for($x=0;$x<count($a80sat);$x++) {
		$str = "";
		if($newrow) {
			$str .= "<div class=\"row\">";
			$newrow = false;
		}
		$str .= "<div class=\"col-lg-4\">
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
				<span class=\"cell_header\">" . $a80sat[$x]['name'] . "</span>
				<div class=\"op_hours\">" . $a80sat[$x]['hours'] . "</div>
				<div class=\"row rem-bor\">
					<div id=\"" . $a80sat[$x]['id'] . "_atis_code\" class=\"col-lg-3 rem-bor atis_code\"></div>
					<div class=\"col-lg-6\">
						<div id=\"" . $a80sat[$x]['id'] . "_open_closed\" class=\"row rem-bor arrival_info\">
						</div>
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
<!--
		<div class="row">
			<div class="col-lg-4">
				<input type="hidden" id="KPDK_override" value="false" />
				<div class="dropdown noclear">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-caret-square-down"></i></a>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
					<li class="dropdown-header">KPDK</li>
					<li class="divider"></li>
					<li><a href="#" onclick="airfieldConfig('KPDK');">Airfield Config</a></li>
					<li><a href="#PROC" onclick="loadProc('KPDK');" data-toggle="modal">Instrument Procedures</a></li>
				</ul>
				</div>
				<span class="cell_header">Peachtree-Dekalb (PDK)</span>
				<div class="op_hours">1130–0400Z‡ Mon–Fri, 1200–0400Z‡ Sat–Sun</div>
				<div class="row rem-bor">
					<div id="KPDK_atis_code" class="col-lg-3 rem-bor atis_code"></div>
					<div class="col-lg-6">
						<div id="KPDK_open_closed" class="row rem-bor arrival_info">
						</div>
						<div id="KPDK_metar" class="row rem-bor">
						</div>						
					</div>
					<div id="KPDK_runway" class="col-lg-3 apch_type"></div>
				</div>
			</div>	
			<div class="col-lg-4" onclick="airfieldConfig('KFTY');">
				<input type="hidden" id="KFTY_override" value="false" />
				<span class="cell_header">Fulton County (FTY)</span>
				<span class="op_hours">Attended continuously</span>
				<div class="row rem-bor">
					<div id="KFTY_atis_code" class="col-lg-3 rem-bor atis_code"></div>
					<div class="col-lg-6">
						<div id="KFTY_open_closed" class="row rem-bor arrival_info">
						</div>
						<div id="KFTY_metar" class="row rem-bor">
						</div>						
					</div>
					<div id="KFTY_runway" class="col-lg-3 apch_type"></div>
				</div>
			</div>
			<div class="col-lg-4" onclick="airfieldConfig('KMGE');">
				<input type="hidden" id="KMGE_override" value="false" />
				<span class="cell_header">Dobbins ARB (MGE)</span>
				<span class="op_hours">1200–0400Z‡</span>
				<div class="row rem-bor">
					<div id="KMGE_atis_code" class="col-lg-3 rem-bor atis_code"></div>
					<div class="col-lg-6">
						<div id="KMGE_open_closed" class="row rem-bor arrival_info">
						</div>
						<div id="KMGE_metar" class="row rem-bor">
						</div>						
					</div>
					<div id="KMGE_runway" class="col-lg-3 apch_type"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4" onclick="airfieldConfig('KRYY');">
				<input type="hidden" id="KRYY_override" value="false" />
				<span class="cell_header">Cobb Co/McCollum (RYY)</span>
				<span class="op_hours">1200–0400Z‡</span>
				<div class="row rem-bor">
					<div id="KRYY_atis_code" class="col-lg-3 rem-bor atis_code"></div>
					<div class="col-lg-6">
						<div id="KRYY_open_closed" class="row rem-bor arrival_info">
						</div>
						<div id="KRYY_metar" class="row rem-bor">
						</div>						
					</div>
					<div id="KRYY_runway" class="col-lg-3 apch_type"></div>
				</div>
			</div>
			<div class="col-lg-4" onclick="airfieldConfig('KLZU');">
				<input type="hidden" id="KLZU_override" value="false" />
				<span class="cell_header">Gwinnette Co (LZU)</span>
				<span class="op_hours">1200–0200Z‡</span>
				<div class="row rem-bor">
					<div id="KLZU_atis_code" class="col-lg-3 rem-bor atis_code"></div>
					<div class="col-lg-6">
						<div id="KLZU_open_closed" class="row rem-bor arrival_info">
						</div>
						<div id="KLZU_metar" class="row rem-bor">
						</div>						
					</div>
					<div id="KLZU_runway" class="col-lg-3 apch_type"></div>
				</div>
			</div>
			<div class="col-lg-4" onclick="airfieldConfig('KAHN');">
				<input type="hidden" id="KAHN_override" value="false" />
				<span class="cell_header">Athens (AHN)</span>
				<span class="op_hours">1300–0100Z‡</span>
				<div class="row rem-bor">
					<div id="KAHN_atis_code" class="col-lg-3 rem-bor atis_code"></div>
					<div class="col-lg-6">
						<div id="KAHN_open_closed" class="row rem-bor arrival_info">
						</div>
						<div id="KAHN_metar" class="row rem-bor">
						</div>						
					</div>
					<div id="KAHN_runway" class="col-lg-3 apch_type"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4" onclick="airfieldConfig('KMCN');">
				<input type="hidden" id="KMCN_override" value="false" />
				<span class="cell_header">Macon Regional (MCN)</span>
				<span class="op_hours">1300–0100Z‡</span>
				<div class="row rem-bor">
					<div id="KMCN_atis_code" class="col-lg-3 rem-bor atis_code"></div>
					<div class="col-lg-6">
						<div id="KMCN_open_closed" class="row rem-bor arrival_info">
						</div>
						<div id="KMCN_metar" class="row rem-bor">
						</div>						
					</div>
					<div id="KMCN_runway" class="col-lg-3 apch_type"></div>
				</div>
			</div>
			<div class="col-lg-4" onclick="airfieldConfig('KWRB');">
				<input type="hidden" id="KWRB_override" value="false" />
				<span class="cell_header">Robins AFB (WRB)</span>
				<span class="op_hours">Attended continuously</span>
				<div class="row rem-bor">
					<div id="KWRB_atis_code" class="col-lg-3 rem-bor atis_code"></div>
					<div class="col-lg-6">
						<div id="KWRB_open_closed" class="row rem-bor arrival_info">
						</div>
						<div id="KWRB_metar" class="row rem-bor">
						</div>						
					</div>
					<div id="KWRB_runway" class="col-lg-3 apch_type"></div>
				</div>
			</div>
			<div class="col-lg-4" onclick="airfieldConfig('KCSG');">
				<input type="hidden" id="KCSG_override" value="false" />
				<span class="cell_header">Columbus (CSG)</span>
				<span class="op_hours">1400–0200Z‡</span>
				<div class="row rem-bor">
					<div id="KCSG_atis_code" class="col-lg-3 rem-bor atis_code"></div>
					<div class="col-lg-6">
						<div id="KCSG_open_closed" class="row rem-bor arrival_info">
						</div>
						<div id="KCSG_metar" class="row rem-bor">
						</div>						
					</div>
					<div id="KCSG_runway" class="col-lg-3 apch_type"></div>
				</div>
			</div>
		</div>
		-->
	</div>
	</div>
<div id="buttons" class="row">
	<div class="col">
		<div class="btn-group dropup">

			<a href="#" class="btn btn-lg btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><i class="fas fa-home fa-lg"></i><br/>HOME</a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
				<li><a href="#" onclick="returnToLanding('local_ids');">Return to menu</a></li>
				<li><a href="#BUG" data-toggle="modal">Report a bug</li></li>
			</ul>
		</div>
		<!--<a href="#HOME" class="btn btn-lg btn-primary" data-toggle="modal"><i class="fas fa-home fa-lg"></i><br/>HOME</a>-->
		<a href="#WX" data-remote="https://www.aviationweather.gov/taf/data?ids=katl&format=decoded&metars=on&date=&submit=Get+TAF+data" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#WX" onClick="fetchWeather('KATL');"><i class="fas fa-cloud fa-lg"></i><br/>WX</a>
		<!--<a href="https://www.ztlartcc.org/storage/files/RECAT%20Cheatsheet_1604655713.pdf" title="RECAT" class="btn btn-lg btn-primary view-pdf" data-toggle="modal" data-target="#PDF_MODAL"><i class="fas fa-plane-arrival fa-lg"></i><br/>RECAT</a>-->
		<a href="#RECAT" class="btn btn-lg btn-primary" data-toggle="modal"><i class="fas fa-plane-arrival fa-lg"></i><br/>RECAT</a>
		<a href="#FREQS" class="btn btn-lg btn-primary template_local" data-toggle="modal"><i class="fas fa-broadcast-tower fa-lg"></i><br/>FREQS</a>
		<a href="#aFREQS" class="btn btn-lg btn-primary template_a80" data-toggle="modal"><i class="fas fa-broadcast-tower fa-lg"></i><br/>FREQS</a>
		<a href="#ARSPC" class="btn btn-lg btn-primary template_local" data-toggle="modal"><i class="fas fa-map-marked-alt fa-lg"></i><br/>ARSPC</a>
		<a href="#aARSPC" class="btn btn-lg btn-primary template_a80" data-toggle="modal"><i class="fas fa-map-marked-alt fa-lg"></i><br/>ARSPC</a>
		<a href="#PROC" class="btn btn-lg btn-primary" data-toggle="modal" onclick="loadProc('KATL');"><i class="fas fa-file-invoice fa-lg"></i><br/>PROC</a>
		<a href="#ROTG" class="btn btn-lg btn-primary" data-toggle="modal"><i class="fas fa-plane-departure fa-lg"></i><br/>ROTG</a>
		<!--<a href="https://www.ztlartcc.org/storage/files/ATL%20ATCT%207110.65I_1607332373.pdf" title="SOP" class="btn btn-lg btn-primary view-pdf" data-toggle="modal" data-target="#PDF_MODAL"><i class="fas fa-book fa-lg"></i><br/>SOP</a>-->
		<a href="#SOP" class="btn btn-lg btn-primary template_local" data-toggle="modal"><i class="fas fa-book fa-lg"></i><br/>SOP</a>
		<a href="#aSOP" class="btn btn-lg btn-primary template_a80" data-toggle="modal"><i class="fas fa-book fa-lg"></i><br/>SOP</a>
		<!--<a href="https://www.ztlartcc.org/storage/files/ATL%20-%20A80%20LOA_1607138614.pdf" title="LOA" class="btn btn-lg btn-primary view-pdf" data-toggle="modal" data-target="#PDF_MODAL"><i class="fas fa-handshake fa-lg"></i><br/>LOA</a>-->
		<a href="#LOA" class="btn btn-lg btn-primary template_local" data-toggle="modal"><i class="fas fa-handshake fa-lg"></i><br/>LOA</a>
		<a href="#aLOA" class="btn btn-lg btn-primary template_a80" data-toggle="modal"><i class="fas fa-handshake fa-lg"></i><br/>LOA</a>
		<a href="#PIREP" class="btn btn-lg btn-primary" data-toggle="modal"><i class="fas fa-headset fa-lg"></i><br/>PIREP</a>
		<!--<a href="resources/2019-10-10_Order_JO_7360.1E_Aircraft_Type_Designators_FINAL.pdf" title="Aircraft Types" class="btn btn-lg btn-primary view-pdf" data-toggle="modal" data-target="#PDF_MODAL"><i class="fas fa-plane fa-lg"></i><br/>ACFT</a>-->
		<a href="#ACFT" class="btn btn-lg btn-primary" data-toggle="modal"><i class="fas fa-plane fa-lg"></i><br/>ACFT</a>
		<a href="#RELIEF" class="btn btn-lg btn-primary template_local" data-toggle="modal"><i class="fas fa-couch fa-lg"></i><br/>RELIEF</a>
		<a href="#aRELIEF" class="btn btn-lg btn-primary template_a80" data-toggle="modal"><i class="fas fa-couch fa-lg"></i><br/>RELIEF</a>
		<a href="#AFLD" class="btn btn-lg btn-primary template_local" data-toggle="modal"><i class="fas fa-cogs fa-lg"></i><br/>AFLD</a>
		<a href="#CIC" class="btn btn-lg btn-primary template_a80" data-toggle="modal"><i class="fas fa-user-tie fa-lg"></i><br/>CIC</a>
		<a href="#TMU" class="btn btn-lg btn-primary" data-toggle="modal"><i class="fas fa-traffic-light fa-lg"></i><br/>TMU</a>
		<a href="#EMER" class="btn btn-lg btn-primary" data-toggle="modal"><i class="fas fa-asterisk fa-lg icon-emergency"></i><br/>EMRG</a>
		<a href="#HELP" class="btn btn-lg btn-primary template_local" data-toggle="modal"><i class="far fa-question-circle"></i><br/>HELP</a>
		<a href="#aHELP" class="btn btn-lg btn-primary template_a80" data-toggle="modal"><i class="far fa-question-circle"></i><br/>HELP</a>
	</div>
</div>
</div>