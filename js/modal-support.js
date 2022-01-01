	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: modal_support.js
		Function: Javscript methods supporting modals and forms
		Created: 9/17/21 (broken out from ids.js)
		Edited: 
		
		Changes: 
	
	*/

	function savePIREP() { // Saves a newly-entered PIREP
		// Validate first!
		var valid = true;
		// Clear previous validation
		clearPirepValidation();
		
		if(document.getElementById("location").value.length < 3) {
			valid = false;
			document.getElementById("location").classList.add("is-invalid");
		}
		else {
			document.getElementById("location").classList.add("is-valid");
		}
		if(document.getElementById("time").value.length < 4) {
			valid = false;
			document.getElementById("time").classList.add("is-invalid");
		}
		else {
			document.getElementById("time").classList.add("is-valid");
		}
		if(document.getElementById("altitude").value.length < 3) {
			valid = false;
			document.getElementById("altitude").classList.add("is-invalid");
		}
		else {
			document.getElementById("altitude").classList.add("is-valid");
		}
		if(document.getElementById("aircraft").value.length < 3) {
			valid = false;
			document.getElementById("aircraft").classList.add("is-invalid");
		}
		else {
			document.getElementById("aircraft").classList.add("is-valid");
		}
		if(document.getElementById("conditions").value.length < 1) {
			valid = false;
			document.getElementById("conditions").classList.add("is-invalid");
		}
		else {
			document.getElementById("conditions").classList.add("is-valid");
		}
		
		if (valid) {
			var pirep_string = '/' + document.getElementById("urgency").value;
			pirep_string += ' /OV ' + document.getElementById("location").value;
			pirep_string += ' /TM ' + document.getElementById("time").value;
			pirep_string += ' /' + document.getElementById("altitude").value;
			pirep_string += ' /TP ' + document.getElementById("aircraft").value;
			pirep_string += ' /RM ' + document.getElementById("conditions").value;
			pirep_string += '\r';
			if(document.getElementById("PIREP_info").innerHTML == "No PIREPs to display") {
				document.getElementById("PIREP_info").innerHTML = pirep_string;
			}
			else {
				document.getElementById("PIREP_info").innerHTML = pirep_string + document.getElementById("PIREP_info").innerHTML;
			}
			$('#PIREP').modal('toggle');
			saveConfiguration('pirep',pirep_string.replace('/RM','/R')); // String replace deals with the /RM HTTP server error
			document.getElementById("pirep_entry").reset();
			clearPirepValidation();
		}
	}
	
	function clearPirepValidation() { // Resets PIREP validation tooltips
		var fields = new Array("location","time","altitude","aircraft","conditions");
		for(var x=0;x<fields.length;x++) {
			document.getElementById(fields[x]).classList.remove("is-valid");
			document.getElementById(fields[x]).classList.remove("is-invalid");
		}		
	}
	
	function saveTMU() { // Saves user-entered TMU notes
		document.getElementById("TMU_info").innerHTML = linkify(document.getElementById("TMU_text").value);
		$('#TMU').modal('toggle');
		saveConfiguration('tmu',document.getElementById("TMU_text").value);
	}
	
	function saveA80CIC() { // Saves user-entered A80 CIC notes
		document.getElementById("A80_CIC_info").innerHTML = document.getElementById("A80_CIC_text").value;
		$('#CIC').modal('toggle');
		saveConfiguration('a80cic',document.getElementById("A80_CIC_text").value);
	}
	
	function airfieldConfig(afldId) {
		document.getElementById("override_active").checked = false;
		document.getElementById("override_rwy_apch").value = "";
		document.getElementById("ac_afldId").innerHTML = afldId.toUpperCase();
		document.getElementById("override_afld_id").value = afldId.toUpperCase();
		if(document.getElementById(afldId.toUpperCase() + "_override").value != 'false') {
			document.getElementById("override_active").checked = true;
			document.getElementById("override_rwy_apch").value = document.getElementById(afldId.toUpperCase() + "_override").value;
		}
		$('#AirfieldConfig').modal('toggle');
	}
	
	function saveAfldConfigOverride(action) {
		var override = new Array(document.getElementById("override_afld_id").value);
		if(action) { // Save a new override
			override.push(document.getElementById("override_rwy_apch").value);
		}
		else { // Remove an existing override 
			override.push(false);
		}
		//alert(JSON.stringify(override));
		saveConfiguration('override',JSON.stringify(override));
		$('#AirfieldConfig').modal('toggle');
	}
	
	function setDepartureGates(save = false) {
		if(save) {
			// Update fields in IDS and then save the data to file
			departure_positions.forEach(function(position) {
				document.getElementById("dep_gate_" + position).innerHTML = "<p><span>" + document.getElementById("depGate" + position).value + "</span></p>";
				var gateConfig = position + ":" + document.getElementById("depGate" + position).value + "\n";
			});
/*
			document.getElementById("dep_gate_n").innerHTML = "<p><span>" + document.getElementById("depGateN").value + "</span></p>";
			document.getElementById("dep_gate_s").innerHTML = "<p><span>" + document.getElementById("depGateS").value + "</span></p>";
			document.getElementById("dep_gate_i").innerHTML = "<p><span>" + document.getElementById("depGateI").value + "</span></p>";
			var gateConfig = "N:" + document.getElementById("depGateN").value + "\n";
			gateConfig += "S:" + document.getElementById("depGateS").value + "\n";
			gateConfig += "I:" + document.getElementById("depGateI").value;
*/
			saveConfiguration('gates',gateConfig);
			// Responsive font sizing
			//fitty('#dep_gate_n');
			//fitty('#dep_gate_s');
			//fitty('#dep_gate_i');
			//alert(document.getElementById("dep_gate_n").getElementsByTagName("p")[0].innerHTML);
		}
		$('#DepartureGates').modal('toggle');
	}
	
	function configDepSplit(save = false, refresh = false) {
		if(save || refresh) {
			// Update fields in IDS and then save the data to file
			// First, build display text
			//var splits = new Array(); 
			var saveData = new Array();
			var splits = "<table id=\"splits\">";
			//var gates = new Array("n1","n2","w2","w1","s2","s1","e1","e2");
			var gates = departure_gates;
			for(var i=1; i<4 ; i++) {
				var saveRunway = new Array();
				//splits[i] = document.getElementById("splits_rwy_id_" + i).value + " " + document.getElementById("splits_n1_" + i).value + " " + document.getElementById("splits_n1t_" + i).value + " " + document.getElementById("splits_n2_" + i).value + " " + document.getElementById("splits_n2t_" + i).value + " " + document.getElementById("splits_w2_" + i).value + " " + document.getElementById("splits_w2t_" + i).value + " " + document.getElementById("splits_w1_" + i).value + " " + document.getElementById("splits_w1t_" + i).value + " " + document.getElementById("splits_s2_" + i).value + " " + document.getElementById("splits_s2t_" + i).value + " " + document.getElementById("splits_s1_" + i).value + " " + document.getElementById("splits_s1t_" + i).value + " " + document.getElementById("splits_e1_" + i).value + " " + document.getElementById("splits_e1t_" + i).value + " " + document.getElementById("splits_e2_" + i).value + " " + document.getElementById("splits_e2t_" + i).value;
				splits += "<tr><th>" + document.getElementById("splits_rwy_id_" + i).value + "</th>";
				saveRunway.push(document.getElementById("splits_rwy_id_" + i).value);
				for(var j=0; j<gates.length; j++) {
					var gate = "&nbsp;&nbsp;&nbsp;";
					var dir = checkboxToName(document.getElementById("splits_" + gates[j] + "_" + i));
					var fix = document.getElementById("splits_" + gates[j] + "t_" + i).value;
					saveRunway.push(dir);
					saveRunway.push(fix);
					if((dir.length > 0)&&(fix.length > 0)) {
						gate = dir + "+" + fix;
					}
					else {
						gate = dir + fix;
					}
					splits += "<td>" + gate + "</td>";
				}
				splits += "</tr>";
				saveData.push(saveRunway);
			}
			splits += "</table>";
			document.getElementById("split_dep_rwys").innerHTML = splits; 
			//document.getElementById("split_dep_rwys").innerHTML = splits[1] + "<br/>" + splits[2] + "<br/>" + splits[3]; 
			if (save) {
				saveConfiguration('splits',JSON.stringify(saveData));
			}
		}
		if(!refresh) {
			$('#DepartureSplit').modal('toggle');
		}
	}
	
	function saveConfiguration(type,payload) { // Saves configuration data to server
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				//alert(xhttp.responseText);
				if(type == 'template') {
					addTemplateItem(xhttp.responseText);
				}
				if(type == 'override') {
					//alert(xhttp.responseText);
				}
			}
			else {
			}
		};
		xhttp.open("GET", "ajax_handler.php?type=" + type + "&cid=" + document.getElementById('cid').value + "&payload=" + encodeURIComponent(payload), true);
		xhttp.send();
	}
	function fetchWeather(icao) { // Retrieves METAR and TAF data from data provider
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById('weather_request').innerHTML = xhttp.responseText;
			}
			else {
			}
		};
		xhttp.open("GET", "ajax_weather.php?icao=" + icao, true);
		xhttp.send();
		// Fetch RVR and update display
		var xhttp1;
		xhttp1 = new XMLHttpRequest();
		xhttp1.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById('wx_rvr').innerHTML = xhttp1.responseText;
			}
			else {
			}
		};
		xhttp1.open("GET", "ajax_rvr.php?icao=" + icao, true);
		xhttp1.send();
		// Update static images from sources
		document.getElementById("wx_video_s").src = document.getElementById("wx_video_s").src + "?r=" + Math.floor(Math.random() * 1000);
		document.getElementById("wx_gates_s").src = document.getElementById("wx_gates_s").src + "?r=" + Math.floor(Math.random() * 1000);
		document.getElementById("wx_radar_s").src = document.getElementById("wx_radar_s").src + "?r=" + Math.floor(Math.random() * 1000);
		document.getElementById("wx_satellite_s").src = document.getElementById("wx_satellite_s").src + "?r=" + Math.floor(Math.random() * 1000);
		document.getElementById("wx_sigmets_s").src = document.getElementById("wx_sigmets_s").src + "?r=" + Math.floor(Math.random() * 1000);
		document.getElementById("wx_prog_s").src = document.getElementById("wx_prog_s").src + "?r=" + Math.floor(Math.random() * 1000);
	}
	
		function clearStyle(x) { // Removes classes applied to an element
		x.className = '';
	}
	


	function saveBugReport() { // Saves bug reports to RSS feed
		// Validate bug report first to ensure user actually reported something...
		var validationErrors = new Array();
		if(document.getElementById("bug_report_name").value.length < 1) {
			validationErrors.push("Please submit your name or Discord user id with the bug report. This will help us contact you if we need more information about the bug that you found.");
		}
		if(document.getElementById("bug_description").value.length < 1) {
			validationErrors.push("Oops... looks like you forgot to tell us all about that bug that you found. Please provide us with a detailed description of the bug so we can fix it!");
		}
		if(validationErrors.length > 0) { // Validation errors found
			alert(validationErrors.join("\n\n"));
		}
		else { // Form was valid, so save the bug report
			$('#BUG').modal('toggle');
			var xhttp;
			xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					alert('Thanks for reporting that bug!');
					//document.getElementById("bug_report_name").value = "";
					document.getElementById("bug_description").value = "";
				}
				else {
				}
			};
			var description = document.getElementById("bug_description").value + '<br/>Reported by: ' + document.getElementById("bug_report_name").value;
			//alert(description);
			xhttp.open("GET", "bug_reporting.php?bug_description=" + description, true); 
			xhttp.send(); 
		}
	}
	
	function showAboutHelp() { // Makes the multi-airfield display visible
		$('#about_help').modal('toggle');
	}
	
	function showBugReportReferal(x) {
		$('#' + x).modal('toggle');
		$('#BUG').modal('toggle');
	}
	


function loadProc(afld) { // Calls FAA data class, retrieves JSON, and serves it to caller as a dialog menu
	if(!!!document.getElementById("RDY_" + afld)) { // Prevents the data from loading twice in the same session
		// Since we reuse this modal, return it to a blank slate before loading
		document.getElementById("PROC_afldId").innerHTML = afld;
		document.getElementById("PROC_iap").innerHTML = "";
		document.getElementById("PROC_dp").innerHTML = "";
		document.getElementById("PROC_star").innerHTML = "";
		document.getElementById("PROC_misc").innerHTML = "";
		document.getElementById("PROC_load").innerHTML = "Please wait... loading";
		
		//document.getElementById("PROC_" + afld).innerHTML = "<li class=\"dropdown-header\">" + afld + " Procedures</li><li class=\"divider\"></li><li class=\"disabled\"><a>Please wait...</a></li>";
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var procedures = formatProcList(xhttp.responseText); // Send this JSON to another function for parse and display
				//document.getElementById("PROC_" + afld).innerHTML = "<li id=\"RDY_" + afld + "\" class=\"dropdown-header\">" + afld + " Procedures</li><li class=\"divider\"></li>" + procedures;
				document.getElementById("PROC_iap").innerHTML = procedures.iap;
				document.getElementById("PROC_dp").innerHTML = procedures.dp;
				document.getElementById("PROC_star").innerHTML = procedures.star;
				document.getElementById("PROC_misc").innerHTML = procedures.misc;
				document.getElementById("PROC_load").innerHTML = "<span id=\"RDY_" + afld + "\"></span>";				
			}
		};
		xhttp.open("GET", "faa_data.php?afld_id=" + afld, true); 
		xhttp.send();
	} 	
}

function formatProcList(json) { // Formats procedure data into a UL
	//var starter1 = "<li class=\"dropdown-submenu\"><a href=\"#\">";
	//var starter2 = " <i class=\"fas fa-caret-right\"></i></a><ul class=\"dropdown-menu\">";
	//var apd = "<li>None available</li>";
	//var iap = starter1 + "Approaches" + starter2;
	//var star = starter1 + "Arrivals" + starter2;
	//var dp = starter1 + "Departures" + starter2;
	//var misc = starter1 + "Misc" + starter2;
	var def = "<li>None available</li>";
	var iap = star = dp = misc = def;
	json = JSON.parse(json);
	for(var x=0; x<json.length; x++) {
		var proc = json[x];
		var item = "<li><a href=\"" + proc.link + "\" target=\"_blank\">" + proc.name + "</li>";
		if(proc.type == "IAP") {
			if(iap == def) { iap = ""; }
			iap += item;
		}
		else if(proc.type == "STAR") {
			if(star == def) { star = ""; }
			star += item;
		}
		else if(proc.type == "DP") {
			if(dp == def) { dp = ""; }
			dp += item;
		}
		//else if(proc.type == "APD") {
		//	apd += item;
		//}
		else {
			if(misc == def) { misc = ""; }
			misc += item;
		}
	}
	// Build reply
	//var finisher = "</ul></li>";
	//iap += finisher;
	//star += finisher;
	//dp += finisher;
	//misc += finisher;
	//var reply = apd + iap + star + dp + misc;
	const reply = {iap:iap,star:star,dp:dp,misc:misc};
	return reply;
}

function modAccessList(list,fn) {
	var reply = '';
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			reply = xhttp.responseText;
			//alert('function: ' + fn + ' status: ' + reply);
			if((fn == 'add')&&(reply == 'success')) { // Add CID to select list
				var opt = document.createElement('option');
				opt.text = document.getElementById(list + 'listCID').value;
				opt.value = document.getElementById(list + 'listCID').value;
				document.getElementById(list + 'list_select').add(opt);
				clearCIDfield();
			}
			if((fn == 'remove')&&(reply == 'success')) { // Remove CID from select list
				var x = document.getElementById(list + 'list_select');
				//alert('removing item');
				for(y=0;y<x.length;y++) {
					if(x.options[y].value == document.getElementById(list + 'listCID').value + '\n') {
						//alert('found');
						x.remove(y);
					}
				}
				clearCIDfield();
			}
			if(fn == 'lookup') { // Dump user data in fields
				reply = JSON.parse(xhttp.responseText);
				document.getElementById(list + 'list_name').value = reply['data']['fname'] + ' ' + reply['data']['lname'];
				document.getElementById(list + 'list_rating').value = reply['data']['rating_short'];
				document.getElementById(list + 'list_facility').value = reply['data']['facility'];
			}
			if(fn == 'fetch') { // Refresh blacklist select list
				document.getElementById(list + 'list_select').innerHTML = "";
				reply = JSON.parse(xhttp.responseText);
				for(y=0;y<reply.length;y++) {
					var opt = document.createElement('option');
					opt.text = reply[y];
					opt.value = reply[y];
					document.getElementById(list + 'list_select').add(opt);					
				}				
			}
		}
		else {
		}
	};
	var valid = true;
	var errorMsg = '';
	var validate = document.getElementById(list + 'listCID').value;
	if((fn == 'add')&&(isNaN(validate)||isNaN(parseInt(validate))||(parseInt(validate)< 100000)||parseInt(validate)>9999999)) {
		valid = false;
		errorMsg += "CID must consist of 6-7 digits\n";
	}
	if(((fn == 'remove')||(fn == 'lookup'))&&(document.getElementById(list + 'listCID').value == "")) {
		valid = false;
		errorMsg += "Please select a CID\n";		
	}
	if(valid) {
		xhttp.open("GET", "ajax_administration.php?list=" + list + "&function=accesslist_" + fn + "&cid=" + document.getElementById(list + 'listCID').value, true); 
		xhttp.send();
	}
	else {
		alert(errorMsg);
	}
}

function clearCIDfield() {
	document.getElementById('blacklistCID').value = "";
	document.getElementById('whitelistCID').value = "";
}

function displayLogs() { // Pull logfiles via AJAX and dump into textarea for display
	var logs = new Array('access','system');
	for(const log of logs) {
		logAJAX(log);
	}	
}

function startLiveLogging() { // Process to start/stop live logging with a auto-refresh interval
	displayLogs();
	var logUpdate = window.setInterval(function(){
		if (!$('#ADMIN').hasClass('in')) { // Stops logging when modal is closed
			clearInterval(logUpdate);
		}
		else {
			displayLogs();
		}
	}, 5000);
}

function logAJAX(log) {
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById(log + '_log').value = xhttp.responseText;
		}
	};
	xhttp.open("GET", "ajax_administration.php?function=log_fetch&log_type=" + log, true); 
	xhttp.send();
}

function stopVideo(id) {
	video = document.getElementById(id);
	video.pause();
	video.currentTime = 0;
}

	function setActiveRunways(el) { // When in manual control (CIC mode), sets active runways and approach/departure type
		var arr_sel = document.getElementById("arr_rwy");
		var dep_sel = document.getElementById("dep_rwy");
		arr_sel.options.length = 0;
		dep_sel.options.length = 0;
		var apch_types = new Array('VIS','ILS');
		var dep_types = new Array('RV','ROTG');
/*		
		if(el.value == "EAST") {
			arr_runways = new Array('8L','9R','10','8R','9L');
			dep_runways = new Array('8R','9L','10','8L','9R');
		}
		else if(el.value == "WEST") {
			arr_runways = new Array('26R','27L','28','26L','27R');
			dep_runways = new Array('26L','27R','28','26R','27L');
		}
		else {
			var arr_runways = new Array();
			var dep_runways = new Array();
		}
*/
		//alert(JSON.stringify(rwy_flows, null, 4));
		var arr_runways = new Array();
		var dep_runways = new Array();
		arr_runways = rwy_flows[el.value].arr;
		dep_runways = rwy_flows[el.value].dep;

		for(var x=0; x<arr_runways.length; x++) {
			for(var y=0; y<apch_types.length; y++) {
				var option = document.createElement("option");
				option.text = arr_runways[x] + ' ' + apch_types[y];
				option.value = arr_runways[x] + ' ' + apch_types[y];
				arr_sel.add(option);
			}
			for(var y=0; y<dep_types.length; y++) {
				var option = document.createElement("option");
				option.text = dep_runways[x] + ' ' + dep_types[y];
				option.value = dep_runways[x] + ' ' + dep_types[y];
				dep_sel.add(option);
			}
		}
		//arr_sel.disabled = false;
		//dep_sel.disabled = false;
	}
	
	function manualControl(el) { //Shifts IDS from automatic (populates from network data) to manual (CIC-entered data) control mode
		if(el.checked) {
			document.getElementById('flow').disabled = true;
			document.getElementById('arr_rwy').disabled = true;
			document.getElementById('dep_rwy').disabled = true;
		}
		else {
			document.getElementById('flow').disabled = false;
			document.getElementById('arr_rwy').disabled = false;
			document.getElementById('dep_rwy').disabled = false;
		}
	}
	
	function checkDuplicate(ar) { // Checks for duplicate runway approach/departure selections
		var valid = true;
		for(var x=0;x<ar.length;x++) {
			var rwt_cur = ar[x].split(' ');
			for(var y=(x+1);y<ar.length;y++) {
				var rwt_nxt = ar[y].split(' ');
				if(rwt_cur[0] == rwt_nxt[0]) {
					valid = false;
				}
			}
		}
		return valid;
	}
	
	function checkDuplicates(el) {
		// Placeholder - function above isn't working as expected
	}
	
	function saveAFLD() { // Saves airfield config settings
		var valid = true;
		var valid_str = '';
		// Validate form
		if(!document.getElementById("AutoIDS").checked) {
			if(document.getElementById("flow").value == "") {
				valid = false;
				valid_str += "- Select a traffic flow direction\n";
			}
			var arr_rwy = $('#arr_rwy').val();
			if(arr_rwy.length == 0) {
				valid = false;
				valid_str += "- Select at least one arrival runway/approach type\n";
			}
			else if(!checkDuplicate(arr_rwy)) {
				valid = false;
				valid_str += "- Cannot select multiple approach types for the same runway\n";				
			}
			var dep_rwy = $('#dep_rwy').val();
			if(dep_rwy.length == 0) {
				valid = false;
				valid_str += "- Select at least one departure runway/guidance type\n";
			}
			else if(!checkDuplicate(dep_rwy)) {
				valid = false;
				valid_str += "- Cannot select multiple departure types for the same runway\n";				
			}
			if (!valid) {
				valid_str += "- Alternatively, use auto-populate for network/vATIS data)";
			}
		}
		if(valid) {
/*
		var fta = "OFF";
		var ftd = "OFF";
		var intdep = "OFF";
		var lahso = "OFF";
		var auto = "ON";
		if (document.getElementById("fta").checked) {
			fta = "ON";
		}
		if (document.getElementById("ftd").checked) {
			ftd = "ON";
		}
		document.getElementById("TRIPS_info").innerHTML = "FTA " + fta + "<br/>FTD " + ftd;
		
		if (document.getElementById("ninelm2").checked) {
			intdep = "ON";
		}
		else {
			intdep = "OFF";
		}
		if (document.getElementById("lahso").checked) {
			lahso = "ON";
		}
		else {
			lahso = "OFF";
		}
		if (document.getElementById("AutoIDS").checked) {
			auto = "ON";
		}
		else {
			auto = "OFF";
		}
		var afld = "9L@M2 " + intdep + "<br>LAHSO " + lahso;
		document.getElementById("AFLD_info").innerHTML = afld;
		afld += "<br>AUTO " + auto;
*/		
		var afld_config_str = '';
		afld_config_options.forEach(function(opt){
			var set = 'OFF';
			if(afld_config_str.length > 0) {
				afld_config_str += '<br>';
			}
			if (document.getElementById(opt).checked) {
				set = "ON";
			}			
			afld_config_str += opt + ' ' + set;
		});
		document.getElementById("AFLD_info").innerHTML = afld_config_str;
		var auto = "ON";
		if (document.getElementById("AutoIDS").checked) {
			auto = "ON";
		}
		else {
			auto = "OFF";
		}
		afld_config_str += "<br>AUTO " + auto; // This option does not get displayed, but it does get output
		document.getElementById("CIC_info").innerHTML = document.getElementById("CIC_text").value;
		$('#AFLD').modal('toggle');

			//saveConfiguration('trips',document.getElementById("TRIPS_info").innerHTML);
			//saveConfiguration('afld',afld);
			saveConfiguration('afld',afld_config_str);
			var arrivals = getSelectValues(document.getElementById("arr_rwy")).toString();
			var departures = getSelectValues(document.getElementById("dep_rwy")).toString();
			saveConfiguration('flow','\n' + document.getElementById("flow").value + '\n' + arrivals + '\n' + departures);
			saveConfiguration('cic',document.getElementById("CIC_text").value);
		}
		else {
			alert('Please fix the following errors: \n' + valid_str);
		}
	}