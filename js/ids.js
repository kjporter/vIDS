	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ids.js
		Function: Javscript backend for IDS site
		Created: 4/1/21
		Edited: 
		
		Changes: 
	
	*/
	
	// Load other JS dependencies
	$.getScript("js/ids-grid.js");
	$.getScript("js/ids-multi.js");
	$.getScript("js/modal-support.js");
	$.getScript("js/helpers.js");
	
	// Code below handles vIDS automatic data refreshes
	var setRefreshTime = 15; // Defines vIDS refresh rate. Default is 15 (VATSIM JSON updates every 15 seconds)
	var refreshTime = setRefreshTime;
	var refreshTimer = setInterval(function(){
		if(refreshTime <= 0){
			refreshData(false,document.getElementById("pickMulti").value);
			refreshTime = setRefreshTime;
		}
		var sec = " second";
		if (refreshTime > 1) {
			sec += "s";
		}
		document.getElementById("refresh_countdown").innerHTML = "Refresh in " +refreshTime + sec;
		refreshTime -= 1;
	}, 1000);
	
	window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
	}, 2000);

	function returnToLanding(closeDiv) { // Hides an active display and returns to the landing page
		document.getElementById(closeDiv).style.display = "none";
		document.body.style.backgroundImage = "url('" + document.getElementById("bgimg").value + "')";
		document.getElementById("landing_hdr").style.display = "block";
		document.getElementById("landing").style.display = "table";
		//document.getElementById("landing").style.visibility = "visible";
		if(closeDiv == "multi_ids") {
			document.getElementById("pickMulti").value = "0";
		}
	}

	function refreshData(init=false,template='0') { // Fetches current dataset from network and server and inits display update
		//if(!init) { alert("Data refresh requested, template " + template); }
		if(init) {
			//loadingNotice();
		}
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				if(!!document.getElementById('json_test_dump')) {
					document.getElementById('json_test_dump').innerHTML = xhttp.responseText; // Dump the full JSON into a div
				}
				var reply = xhttp.responseText;
				var error = "";
				// Capture errors from PHP reply, prevent JS errors
				if(reply.substring(0,1) != '{') { // JSON start with '{', lack of this means there is error text before the JSON
					error = reply.substring(0,reply.indexOf('{')); // Capture the error text
					reply = reply.substring(reply.indexOf('{')+1); // Trim the error off of the JSON so we can use it normally
				}
				//alert(reply);
				updateIDSFields(init,reply); // Send this JSON to another function for parse and display
				//updateIDSFields(init,xhttp.responseText); // Send this JSON to another function for parse and display
				if(error.length > 0) { // An error occurred - notify the user
					document.getElementById('alert').classList.add('alert-warning');
					document.getElementById('alert_text').innerHTML = "A data refresh error occurred. Some vIDS fields may contain outdated information.";
				}
			}
			else {
			}
		};
		//alert(document.getElementById('live').checked);
		var liveData = true;
		if(!!document.getElementById('live')) {
			liveData = document.getElementById('live').checked;
		}
		//xhttp.open("GET", "ajax_refresh.php?live=" + liveData, true); 
		//alert(template);
		xhttp.open("GET", "ajax_refresh.php?live=" + liveData + "&template=" + template, true); 
		xhttp.send(); 
		//if(init) { alert("Initial load data pull complete"); }
		if(init) { // Check for browser type/capabilities and alert the user, if necessary
			browser_detection();
		}
	}

	function updateIDSFields(init,data) { // Called by refreshData() - updates all data fields with info retrieved from AJAX
		//alert(data);
		var json = JSON.parse(data);
		var changes = false;
		// Set Tower IDS fields
		//init = false; // For testing only, this needs to be removed
		changes = changeDetection(init,changes,document.getElementById("atis_code").innerHTML,json.airfield_data[defaultAirfield]['atis_code'],"atis_code");
		if(json.airfield_data[defaultAirfield]['atis_code'] != 'undefined') {
			document.getElementById("atis_code").innerHTML = json.airfield_data[defaultAirfield]['atis_code'];
		}
		else {
			document.getElementById("atis_code").innerHTML = '--';
		}
		changes = changeDetection(init,changes,document.getElementById("metar").innerHTML,json.airfield_data[defaultAirfield]['metar'],"metar");
		if(json.airfield_data[defaultAirfield]['metar'] != 'undefined') {
			document.getElementById("metar").innerHTML = json.airfield_data[defaultAirfield]['metar'];
		}
		else {
			document.getElementById("metar").innerHTML = 'METAR unavailable';
		}
		changes = changeDetection(init,changes,document.getElementById("traffic_flow").innerHTML,json.airfield_data[defaultAirfield]['traffic_flow'],"traffic_flow");
		document.getElementById("traffic_flow").innerHTML = json.airfield_data[defaultAirfield]['traffic_flow'];
		
		var departure_rwys = "";
		for(var x=0;x<json.airfield_data[defaultAirfield]['dep_rwys'].length;x++) {
			departure_rwys += json.airfield_data[defaultAirfield]['dep_type'] + " " + json.airfield_data[defaultAirfield]['dep_rwys'][x];
			var this_rwy = json.airfield_data[defaultAirfield]['dep_rwys'][x].substr(0,json.airfield_data[defaultAirfield]['dep_rwys'][x].indexOf(' '));
			if(json.airfield_data[defaultAirfield]['rvr_detail']['RWY'].hasOwnProperty(this_rwy)) {
				if((parseInt(json.airfield_data[defaultAirfield]['rvr_detail']['RWY'][this_rwy]['WORST']) < 6000)||(parseInt(json.airfield_data[defaultAirfield]['visibility_numeric']) < 1)) {
					departure_rwys += ' [' + json.airfield_data[defaultAirfield]['rvr_detail']['RWY'][this_rwy]['WORST'] + ']';
				}
			}
			departure_rwys += "<br/>";
		}
		//changes = changeDetection(init,changes,document.getElementById("local_dep_rwys").innerHTML,departure_rwys,"local_dep_rwys");
		document.getElementById("local_dep_rwys").innerHTML = departure_rwys;
		
		// Departure splits
		var origDepSplit = document.getElementById("split_dep_rwys").innerHTML;
		/*
		if(json.airfield_data[defaultAirfield]['traffic_flow'] == "EAST") {
			document.getElementById("splits_rwy_1").innerHTML = document.getElementById("splits_rwy_id_1").value = "8R/L";
			document.getElementById("splits_rwy_2").innerHTML = document.getElementById("splits_rwy_id_2").value = "9R/L";
			document.getElementById("splits_rwy_3").innerHTML = document.getElementById("splits_rwy_id_3").value = "10";
		}
		else if(json.airfield_data[defaultAirfield]['traffic_flow'] == "WEST") {
			document.getElementById("splits_rwy_1").innerHTML = document.getElementById("splits_rwy_id_1").value = "26R/L";
			document.getElementById("splits_rwy_2").innerHTML = document.getElementById("splits_rwy_id_2").value = "27R/L";
			document.getElementById("splits_rwy_3").innerHTML = document.getElementById("splits_rwy_id_3").value = "28";
		}
		*/
		var i=1;
		rwy_flows[json.airfield_data[defaultAirfield]['traffic_flow']].id.forEach(function(val){
			document.getElementById("splits_rwy_" + i).innerHTML = document.getElementById("splits_rwy_id_" + i).value = val;
			i++;
		});
		//alert(json.splits[0][0]);
		if(!$('#DepartureSplit').is(':visible')) { // This conditional prevents the refresh script from updating data entry fields when a modal is in use
			if(json.splits != null) { // Prevents initialization error
				for(var i=1; i<4; i++) {
					departure_gates.forEach(function(gate,index){
						document.getElementById("splits_" + gate + "_" + i).checked = valueToCheckbox(json.splits[i-1][index * 2 + 1]);
						document.getElementById("splits_" + gate + "t_" + i).value = json.splits[i-1][index * 2 + 2];
					}); 
		/*
			document.getElementById("splits_n1_" + i).checked = valueToCheckbox(json.splits[i-1][1]);
			document.getElementById("splits_n1t_" + i).value = json.splits[i-1][2];
			document.getElementById("splits_n2_" + i).checked = valueToCheckbox(json.splits[i-1][3]);
			document.getElementById("splits_n2t_" + i).value = json.splits[i-1][4];
			document.getElementById("splits_w2_" + i).checked = valueToCheckbox(json.splits[i-1][5]);
			document.getElementById("splits_w2t_" + i).value = json.splits[i-1][6];
			document.getElementById("splits_w1_" + i).checked = valueToCheckbox(json.splits[i-1][7]);
			document.getElementById("splits_w1t_" + i).value = json.splits[i-1][8];
			document.getElementById("splits_s2_" + i).checked = valueToCheckbox(json.splits[i-1][9]);
			document.getElementById("splits_s2t_" + i).value = json.splits[i-1][10];
			document.getElementById("splits_s1_" + i).checked = valueToCheckbox(json.splits[i-1][11]);
			document.getElementById("splits_s1t_" + i).value = json.splits[i-1][12];
			document.getElementById("splits_e1_" + i).checked = valueToCheckbox(json.splits[i-1][13]);
			document.getElementById("splits_e1t_" + i).value = json.splits[i-1][14];
			document.getElementById("splits_e2_" + i).checked = valueToCheckbox(json.splits[i-1][15]);
			document.getElementById("splits_e2t_" + i).value = json.splits[i-1][16];
		*/
				}
			}
		}
		configDepSplit(false, true); // Recycle the display, but don't save
		changes = changeDetection(init,changes,origDepSplit,document.getElementById("split_dep_rwys").innerHTML,"split_dep_rwys");
		
		var arrival_rwys = "";
		for(var x=0;x<json.airfield_data[defaultAirfield]['apch_rwys'].length;x++) {
			arrival_rwys += json.airfield_data[defaultAirfield]['apch_type'] + " " + json.airfield_data[defaultAirfield]['apch_rwys'][x];
			var this_rwy = json.airfield_data[defaultAirfield]['apch_rwys'][x].substr(0,json.airfield_data[defaultAirfield]['apch_rwys'][x].indexOf(' '));
			if(json.airfield_data[defaultAirfield]['rvr_detail']['RWY'].hasOwnProperty(this_rwy)) {
				if(parseInt(json.airfield_data[defaultAirfield]['rvr_detail']['RWY'][this_rwy]['WORST']) < 6000) {
					arrival_rwys += ' [' + json.airfield_data[defaultAirfield]['rvr_detail']['RWY'][this_rwy]['WORST'] + ']';
				}
			}
			arrival_rwys += "<br/>";
		}
		//changes = changeDetection(init,changes,document.getElementById("local_arr_rwys").innerHTML,arrival_rwys,"local_arr_rwys");
		document.getElementById("local_arr_rwys").innerHTML = arrival_rwys;
		// Set controller position combines in select boxes
		var controllers = json.controllers.split("|");
		// Note: this was moved to global definitions in the config.php file
		//var positions = new Array("LC-1","LC-2","LC-3","LC-4","LC-5","GC-N","GC-C","GC-S","GM","CD-1","CD-2","FD","N","S","I","P","F","X","G","Q","O","V","A","H","D","L","Y","M","W","Z","R","E","3E");
		//var controllerPos = "";
		for(var x=0; x<controllers.length;x++) {
			//controllerPos += "Position: " + positions[x] + " Controller selected: " + controllers[x] + "\n";
			changes = changeDetection(init,changes,document.getElementById(positions[x]).value,controllers[x],positions[x]);
			changes = changeDetection(init,changes,document.getElementById(positions[x]).value,controllers[x],positions[x] + "_disp");
			selectOption(positions[x],controllers[x]);
			document.getElementById(positions[x] + "_disp").value = (controllers[x] == '.' ? '' : controllers[x]);
		}
		//alert(changes);
		// Set text fields
		if(!json.pirep.includes('No PIREPs to display')) { // Only show change detection if something new happens, not simply if everything expires
			changes = changeDetection(init,changes,document.getElementById("PIREP_info").innerHTML.replace(/(\r\n|\n|\r)/gm,""),json.pirep.replace(/(\r\n|\n|\r)/gm,""),"PIREP_info");
		}
		//alert("Current: \"" + document.getElementById("PIREP_info").innerHTML + "\"\nUpdate: \"" + json.pirep + "\"");
		document.getElementById("PIREP_info").innerHTML = json.pirep; // Display-only (entries auto-timeout)
		changes = changeDetection(init,changes,unlinkify(document.getElementById("TMU_info").innerHTML),json.tmu,"TMU_info");
		//var tmuText = json.tmu.map(i => i.replace(/\n/g, '<br />')).join('');
		//alert(json.tmu);
		//alert(unlinkify(document.getElementById("TMU_info").innerHTML));
		document.getElementById("TMU_info").innerHTML = linkify(json.tmu); //.replaceAll(/\n/g, '<br />')); // Display
		if(!$('#TMU').is(':visible')) { // This conditional prevents the refresh script from updating data entry fields when a modal is in use
			document.getElementById("TMU_text").innerHTML = json.tmu // Data entry
		}
		if(!$('#A80_CIC_info').is(':visible')) { // Only do change detection on visible elements
			changes = changeDetection(init,changes,document.getElementById("A80_CIC_info").innerHTML,json.a80cic,"A80_CIC_info");
		}
		document.getElementById("A80_CIC_info").innerHTML = json.a80cic;
		if(!$('#CIC').is(':visible')) { // This conditional prevents the refresh script from updating data entry fields when a modal is in use
			document.getElementById("A80_CIC_text").value = json.a80cic; // Data entry
		}		
		/*
		changes = changeDetection(init,changes,document.getElementById("TRIPS_info").innerHTML,json.trips['raw'],"TRIPS_info");
		document.getElementById("TRIPS_info").innerHTML = json.trips['raw']; // Display
		*/
		//document.getElementById("fta").checked = json.trips['FTA']; // Data entry
		//alert("FTA: " + json.trips['FTA'] + "\nFTD: " + json.trips['FTD']);
		//document.getElementById("ftd").checked = json.trips['FTD']; // Data entry
		//document.getElementById("ninelm2").checked = json.config['9L@M2']; // Data entry
		//document.getElementById("lahso").checked = json.config['LAHSO']; // Data entry
		//document.getElementById("AutoIDS").checked = json.config['AUTO']; // Data entry
		/*
		if(!json.config['AUTO']) {
			document.getElementById("flow").disabled = false;
			document.getElementById("arr_rwy").disabled = false;
			document.getElementById("dep_rwy").disabled = false;
		}
		*/
		/*
		if(json.config['AUTO']) {
			document.getElementById("flow").disabled = true;
			document.getElementById("arr_rwy").disabled = true;
			document.getElementById("dep_rwy").disabled = true;
		}
		*/
		//alert("." + json.airfield_data['KATL']['traffic_flow'].trim() + ".");
		/*
		document.getElementById("flow").value = json.airfield_data['KATL']['traffic_flow'].trim();
		setActiveRunways(document.getElementById("flow"));
		for (var i = 0; i < document.getElementById("arr_rwy").options.length; i++) {
			document.getElementById("arr_rwy").options[i].selected = json.airfield_data['KATL']['apch_rwys'].indexOf(document.getElementById("arr_rwy").options[i].value) >= 0;
		}
		for (var i = 0; i < document.getElementById("dep_rwy").options.length; i++) {
			document.getElementById("dep_rwy").options[i].selected = json.airfield_data['KATL']['dep_rwys'].indexOf(document.getElementById("dep_rwy").options[i].value) >= 0;
		}
		*/
		//alert("Current: \"" + document.getElementById("AFLD_info").innerHTML + "\"\nUpdate: \"" + json.config['raw'] + "\"");
		// Temporary fix while combining TRIPS and AFLD info data fields
//		var afldInfoData = json.trips['raw'] + '<br/>' + json.config['raw'];
//		changes = changeDetection(init,changes,document.getElementById("AFLD_info").innerHTML,afldInfoData,"AFLD_info");
//		document.getElementById("AFLD_info").innerHTML = afldInfoData; // Display

		changes = changeDetection(init,changes,document.getElementById("AFLD_info").innerHTML,json.config['raw'],"AFLD_info");
		document.getElementById("AFLD_info").innerHTML = json.config['raw']; // Display
		if(!$('#CIC_info').is(':visible')) { // Only do change detection on visible elements
			changes = changeDetection(init,changes,document.getElementById("CIC_info").innerHTML,json.cic,"CIC_info");
		}
		//changes = changeDetection(init,changes,document.getElementById("CIC_info").innerHTML,json.cic,"CIC_info");
		//alert("CIC Info\nIn DOM: \"" + document.getElementById("CIC_info").innerHTML + "\"\nFrom file: \"" + json.cic.replace(/(\n)/gm,"") + "\"");
		document.getElementById("CIC_info").innerHTML = json.cic; // Display
		//document.getElementById("CIC_text").innerHTML = json.cic; // Data entry
		if(!$('#AFLD').is(':visible')) { // This conditional prevents the refresh script from updating data entry fields when a modal is in use
			document.getElementById("flow").value = json.airfield_data[defaultAirfield]['traffic_flow'].trim();
			setActiveRunways(document.getElementById("flow"));
			if(json.airfield_data[defaultAirfield]['apch_rwys'].length != undefined) {
			for (var i = 0; i < document.getElementById("arr_rwy").options.length; i++) {
				document.getElementById("arr_rwy").options[i].selected = json.airfield_data[defaultAirfield]['apch_rwys'].indexOf(document.getElementById("arr_rwy").options[i].value) >= 0;
			}
			}
			if(json.airfield_data[defaultAirfield]['dep_rwys'].length != undefined) {
			for (var i = 0; i < document.getElementById("dep_rwy").options.length; i++) {
				document.getElementById("dep_rwy").options[i].selected = json.airfield_data[defaultAirfield]['dep_rwys'].indexOf(document.getElementById("dep_rwy").options[i].value) >= 0;
			}
			}
			/*
			document.getElementById("fta").checked = json.trips['FTA']; // Data entry
			document.getElementById("ftd").checked = json.trips['FTD']; // Data entry
			*/
			//alert(afld_config_options.toString());
			afld_config_options.forEach(function(opt){
				//alert(opt);
				document.getElementById(opt).checked = json.config[opt]; // Data entry
			});
			/*
			document.getElementById("ninelm2").checked = json.config['9L@M2']; // Data entry
			document.getElementById("lahso").checked = json.config['LAHSO']; // Data entry
			document.getElementById("CIC_text").innerHTML = json.cic; // Data entry
			document.getElementById("AutoIDS").checked = json.config['AUTO']; // Data entry
			*/
		}
		if(!json.config['AUTO']) {
			document.getElementById("flow").disabled = false;
			document.getElementById("arr_rwy").disabled = false;
			document.getElementById("dep_rwy").disabled = false;
		}
		//alert(json.gates);
		// Update departure gate/controller assignments
		for(var x=0; x < json.gates.length; x++) {
//			alert("dep_gate_" + departure_positions[x]);
			changes = changeDetection(init,changes,document.getElementById("dep_gate_" + departure_positions[x]).innerHTML,json.gates[x],"dep_gate_"  + departure_positions[x]);
			document.getElementById("dep_gate_" + departure_positions[x]).innerHTML = json.gates[x];	
			if(!$('#DepartureGates').is(':visible')) { // This conditional prevents the refresh script from updating data entry fields when a modal is in use
				document.getElementById("depGate" + departure_positions[x]).value = json.gates[x];
			}
		}
/*
		if(json.gates.length == 3) {
			changes = changeDetection(init,changes,document.getElementById("dep_gate_n").innerHTML,json.gates[0],"dep_gate_n");
			changes = changeDetection(init,changes,document.getElementById("dep_gate_s").innerHTML,json.gates[1],"dep_gate_s");
			changes = changeDetection(init,changes,document.getElementById("dep_gate_i").innerHTML,json.gates[2],"dep_gate_i");
			document.getElementById("dep_gate_n").innerHTML = json.gates[0];	
			document.getElementById("dep_gate_s").innerHTML = json.gates[1];
			document.getElementById("dep_gate_i").innerHTML = json.gates[2];
*/
			// Responsive font sizing
			//fitty('#dep_gate_n');
			//fitty('#dep_gate_s');
			//fitty('#dep_gate_i');
			//var txt_overrun = "";
/*			//Scrolling marquee design... removed 9/22 at Joe's request			
			if ($('#dep_gate_n')[0].scrollWidth >  $('#dep_gate_n_container').innerWidth()) { // When true, the text overruns the box, so we want to marquee
				document.getElementById("dep_gate_n_container").classList.add("start_marquee");
				//txt_overrun += "N";
			}
			else {
				document.getElementById("dep_gate_n_container").classList.remove("start_marquee");
			}
			if ($('#dep_gate_s')[0].scrollWidth >  $('#dep_gate_s_container').innerWidth()) { // When true, the text overruns the box, so we want to marquee
				document.getElementById("dep_gate_s_container").classList.add("start_marquee");
				//txt_overrun += "S";
			}
			else {
				document.getElementById("dep_gate_s_container").classList.remove("start_marquee");
			}
			if ($('#dep_gate_i')[0].scrollWidth >  $('#dep_gate_i_container').innerWidth()) { // When true, the text overruns the box, so we want to marquee
				document.getElementById("dep_gate_i_container").classList.add("start_marquee");
				//txt_overrun += "I";
			}
			else {
				document.getElementById("dep_gate_i_container").classList.remove("start_marquee");
			}
*/
/*
			alert("N - Text width: " + $('#dep_gate_n')[0].scrollWidth + " Div width: " + $('#dep_gate_n_container').innerWidth() + 
			"\nS - Text width: " + $('#dep_gate_s')[0].scrollWidth + " Div width: " + $('#dep_gate_s_container').innerWidth() + 
			"\nI - Text width: " + $('#dep_gate_i')[0].scrollWidth + " Div width: " + $('#dep_gate_i_container').innerWidth() + 
			"\n Overruns: " + txt_overrun);
*/
//			document.getElementById("dep_gate_n").value = json.gates[0];	
//			document.getElementById("dep_gate_s").value = json.gates[1];
//			document.getElementById("dep_gate_i").value = json.gates[2];
/*
			$('#dep_gate_n').marquee({ speed: 20 });
			$('#dep_gate_s').marquee({ speed: 20 });
			$('#dep_gate_i').marquee({ speed: 20 });
*/
/*
			if(!$('#DepartureGates').is(':visible')) { // This conditional prevents the refresh script from updating data entry fields when a modal is in use
				document.getElementById("depGateN").value = json.gates[0];
				document.getElementById("depGateS").value = json.gates[1];
				document.getElementById("depGateI").value = json.gates[2];
			}	
		}
*/
		// Set A80 satellite and outer field info
		//var underlying_fields = new Array("KPDK","KFTY","KMGE","KRYY","KLZU","KMCN","KWRB","KAHN","KCSG"); // I intentionally left LSF out... we never use it
		//var underlying_fields = new Array("KPDK"); // For testing only.. real world use the line above to get all of the satellites
		var openCloseStatus = "";
		for(var y=0;y<underlying_fields.length;y++) {
			var open_closed = "CLOSED";
			if(underlying_fields[y] in json.airfield_data) {
			document.getElementById(underlying_fields[y] + "_atis_code").innerHTML = json.airfield_data[underlying_fields[y]].atis_code;
			//if(json.airfield_data[underlying_fields[y]].atis_code != "--") {
				//open_closed = "OPEN";
			//}
			// New schema to make field OPEN/CLOSED status reflect real-world tower operating hours
			var curDate = new Date();
			var dayofweek = curDate.getUTCDay(); // 0 = Sunday, 1 = Monday <- we actually need to pull the local day that the airfield OPENED... not the Z day
			var cur24time = curDate.getUTCHours() * 100 + curDate.getUTCMinutes(); // Get UTC time and format HHmm
			var cur24timex = cur24time;
			var opHours = document.getElementById(underlying_fields[y] + "_hours_mf").value;
			//alert(underlying_fields[y] + ': ' + document.getElementById(underlying_fields[y] + "_hours_mf").value);
			// Note: there is still a bug in this code... airfields that close on a Z day other than the local day will not display proper times
			// I need to make this logic more robust
			if ((dayofweek == 0)||(dayofweek == 6)) { // Sunday(0) or Saturday(6)
				opHours = document.getElementById(underlying_fields[y] + "_hours_ss").value;
			}
			opHours = opHours.split('-');
			var opHoursStart = parseInt(opHours[0]);
			var opHoursEnd = parseInt(opHours[1]);
			if((document.getElementById(underlying_fields[y] + "_hours_dstAdjust").value)&&(checkDST())) { // Adjust for DST
				opHoursStart -= 100;
				opHoursEnd -= 100;
			}
			if(opHoursEnd < opHoursStart) { // This happens when a tower closes after 0000Z
				opHoursEnd += 2400;
				cur24timex += 2400;
				
			}
			if((cur24time > opHoursStart)&&(cur24time < opHoursEnd)||(cur24time < opHoursStart)&&(cur24timex < opHoursEnd)) { // Airfield is open
//			if((cur24time > opHoursStart)&&(cur24timex < opHoursEnd)) { // Airfield is open - attempt to fix > 2400 closing error
				open_closed = "OPEN";
				//alert(underlying_fields[y] + ' Current UTC time: ' + cur24time + ' Field opening time: ' + opHoursStart + ' Field closing time: ' + opHoursEnd + ' Field is: ' + open_closed);				
			}
			openCloseStatus += underlying_fields[y] + ' Current UTC time: ' + cur24time + ' Field opening time: ' + opHoursStart + ' Current UTC time: ' + cur24timex + ' Field closing time: ' + opHoursEnd + ' Field is: ' + open_closed + '\n';
			document.getElementById(underlying_fields[y] + "_open_closed").innerHTML = open_closed;
			// New schema to display del/gnd/twr status
			//alert(json.airfield_data[underlying_fields[y]].tower_cab.del);
			if(json.airfield_data[underlying_fields[y]].tower_cab.del) {
				//alert(underlying_fields[y] + ' delivery online ' + document.getElementById(underlying_fields[y] + "_online_del").classList);
				document.getElementById(underlying_fields[y] + "_online_del").classList.add('badge-del');
				//alert(underlying_fields[y] + ' delivery online ' + document.getElementById(underlying_fields[y] + "_online_del").classList);
			}
			else {
				document.getElementById(underlying_fields[y] + "_online_del").classList.remove('badge-del');
			}
			if(json.airfield_data[underlying_fields[y]].tower_cab.gnd) {
				document.getElementById(underlying_fields[y] + "_online_gnd").classList.add('badge-gnd');
			}
			else {
				document.getElementById(underlying_fields[y] + "_online_gnd").classList.remove('badge-gnd');
			}			
			if(json.airfield_data[underlying_fields[y]].tower_cab.twr) {
				document.getElementById(underlying_fields[y] + "_online_twr").classList.add('badge-twr');
			}
			else {
				document.getElementById(underlying_fields[y] + "_online_twr").classList.remove('badge-twr');				
			}
			document.getElementById(underlying_fields[y] + "_metar").innerHTML = json.airfield_data[underlying_fields[y]].metar;
			/*
			var active_rwys = "--";
			if(json.airfield_data[underlying_fields[y]].dep_rwys != null) {
				for(var z=0;z<json.airfield_data[underlying_fields[y]].dep_rwys.length;z++) {
					active_rwys += json.airfield_data[underlying_fields[y]].dep_rwys[z] + " ";
				}
			}
			*/
			// New scheme to display active runways/traffic flow with the option for manual override
			var active_apch_rwys = "--";
			var override = false;
			var afld = underlying_fields[y];
			if(json.override != null) {
			if(json.override.hasOwnProperty(afld)) { // An override exists, so display it
				active_apch_rwys = json.override[afld];
				override = json.override[afld];
			}
			}
			else if(json.airfield_data[afld].apch_rwys != "") { // Display generated active runway and approach type
				for(var rwy in json.airfield_data[afld]['apch_rwys']) {
					if(active_apch_rwys.length > 0) {
						active_apch_rwys += ", ";
					}
					active_apch_rwys += json.airfield_data[afld]['apch_rwys'][rwy] + " " + json.airfield_data[afld]['apch_type'];
				}
			}
			document.getElementById(underlying_fields[y] + "_runway").innerHTML = active_apch_rwys;
			document.getElementById(underlying_fields[y] + "_override").value = override;
			}
		}
		//alert(openCloseStatus);
		// Set MULTI-IDS fields
		//var multi_disp_str = '<div class=\"landing_menu\"><a onclick=\"returnToLanding(\'multi_ids\');\"><i class=\"fas fa-bars\"></i></a></div>';
		if((document.getElementById("ad").value == '1')||(document.getElementById("cid").value == json.template_creator)) {
			document.getElementById("templateDeleteMenu").classList.remove('disabled');
		}
		else {
			document.getElementById("templateDeleteMenu").classList.add('disabled');
		}
		var multi_disp_str = '';
		//alert("KATL approach type: " + json.airfield_data['KATL']['apch_rwys'].join(", "));
		//alert("Resetting Multi-IDS airfields..." + JSON.stringify(json.template));
		//var airfield_listing = "";
		//for(afld in json.airfield_data.template) {
		if(json.template === null) { // Refresh isn't finished... show a loading message
			multi_disp_str = "<div class=\"row\"><div class=\"col-sm\"><h3>Multi-IDS display is loading... please wait</h3></div></div>";
		}
		else {
		//var defaultAirfieldNoChange = "alert('Configuration for this airfield must be set through the local vIDS display by the tower CIC.');";
		// Note: I had to break-out refresh vs redraw logic below to allow users to click/drag/reorder airfields
		// Create array of all airfields displayed by walking DOM and getting IDs
		var nextAfld = document.getElementById('multi_ids_data').firstChild;
		var displayedAirfields = new Array();
		while(nextAfld) {
			displayedAirfields.push(nextAfld.id);
			nextAfld = nextAfld.nextSibling;
		}
		displayedAirfields = displayedAirfields.map(function(x){ return x.toUpperCase(); }).sort();
		templateAirfields = Object.values(json.template).map(function(x){ return x.toUpperCase(); }).sort();
		/*
		if(JSON.stringify(displayedAirfields) === JSON.stringify(templateAirfields)) { // Displayed and template are the same, so just refresh data
			//alert(displayedAirfields + "\n" + templateAirfields);
			//alert('refresh');
			for(afld in displayedAirfields) {
				afld = displayedAirfields[afld];
				//alert(afld);
				document.getElementById(afld + '_ATIS').innerHTML = json.airfield_data[afld].atis_code;
				document.getElementById(afld + '_WX').innerHTML = json.airfield_data[afld].winds + "&nbsp;&nbsp;&nbsp;" + json.airfield_data[afld].altimeter;
				document.getElementById(afld + '_METAR').innerHTML = json.airfield_data[afld].metar;
				var active_rwy_apch = "";
				// New scheme to display active runways/traffic flow with the option for manual override
				var override = false;
				if(json.override.hasOwnProperty(afld)) { // An override exists, so display it
					active_rwy_apch = json.override[afld];
					override = json.override[afld];
				}
				else if(json.airfield_data[afld].apch_rwys != "") { // Display generated active runway and approach type
					for(var rwy in json.airfield_data[afld]['apch_rwys']) {
						if(active_rwy_apch.length > 0) {
							active_rwy_apch += ", ";
						}
						active_rwy_apch += json.airfield_data[afld]['apch_rwys'][rwy] + " " + json.airfield_data[afld]['apch_type'];
					}
				}
				document.getElementById(afld + '_RWYAPCH').innerHTML = active_rwy_apch;
				var rvr = "";
				for(var x=0; x<json.airfield_data[afld].rvr_display.length; x++) {
					rvr += json.airfield_data[afld].rvr_display[x] + "<br/>";
				}
				document.getElementById(afld + '_RVR').innerHTML = rvr;
			}
		}
		
		else { // Redraw multi-ids view
		*/
		if(JSON.stringify(displayedAirfields) !== JSON.stringify(templateAirfields)) { // Displayed and template are not the same, so just redraw grid
		for(afld in json.template) { //json.airfield_data
			afld = json.template[afld];
			afld = afld.toUpperCase();
			multi_disp_str += "<div id=\"" + afld + "\" class=\"row moveable\" draggable=\"true\" ondragstart=\"dragStarted(event);\" ondragover=\"draggingOver(event);\" ondrop=\"dropped(event);\">";
			multi_disp_str += "<div class=\"col-sm-1\"><div class=\"vert_id\">" + json.airfield_data[afld].icao_id.substr(1,1).toUpperCase() + "</div><div class=\"vert_id\">" + json.airfield_data[afld].icao_id.substr(2,1).toUpperCase() + "</div><div class=\"vert_id\">" + json.airfield_data[afld].icao_id.substr(3,1).toUpperCase() + "</div></div>";

//			multi_disp_str += "<div id=\"" + afld + "_ATIS\" class=\"col-sm-1 atis_code_m\">" + json.airfield_data[afld].atis_code + "</div>";
			multi_disp_str += "<div id=\"" + afld + "_ATIS\" class=\"col-sm-1 atis_code_m\"></div>";
/*			
			var active_rwy_apch = "";
			// New scheme to display active runways/traffic flow with the option for manual override
			var override = false;
			if(json.override.hasOwnProperty(afld)) { // An override exists, so display it
				active_rwy_apch = json.override[afld];
				override = json.override[afld];
			}
			else if(json.airfield_data[afld].apch_rwys != "") { // Display generated active runway and approach type
				for(var rwy in json.airfield_data[afld]['apch_rwys']) {
					if(active_rwy_apch.length > 0) {
						active_rwy_apch += ", ";
					}
					active_rwy_apch += json.airfield_data[afld]['apch_rwys'][rwy] + " " + json.airfield_data[afld]['apch_type'];
				}
			}

			else {
				//active_rwy_apch = json.airfield_data[afld].apch_rwys.join(", ");
			}
*/
/*
			multi_disp_str += "<div class=\"col-lg-2 arrival_info\"><div id=\"" + afld + "_RWYAPCH\" class=\"apch_type\">" + active_rwy_apch + "</div><div></div>";
			multi_disp_str += "<div id=\"" + afld + "_WX\" class=\"wx\">" + json.airfield_data[afld].winds + "&nbsp;&nbsp;&nbsp;" + json.airfield_data[afld].altimeter + "</div>" + generateDropdown(afld,defaultAirfield) + "</div>";
			multi_disp_str += "<div id=\"" + afld + "_METAR\" class=\"col-lg-5 metar_m\">" + json.airfield_data[afld].metar + "</div>";
			multi_disp_str += "<div class=\"col-lg-3 metar_m\">RY RVR<div id=\"" + afld + "_RVR\" class=\"rvr\">";
*/
			multi_disp_str += "<div class=\"col-sm-2 arrival_info\"><div id=\"" + afld + "_RWYAPCH\" class=\"apch_type\"></div><div></div>";
			multi_disp_str += "<div id=\"" + afld + "_WX\" class=\"wx\"></div>" + generateDropdown(afld,defaultAirfield);
			multi_disp_str += "<span class=\"cab_status\">&nbsp;&nbsp;<span id=\"" + afld + "_multi_online_del\" class=\"badge badge-secondary\">D</span>&nbsp;<span id=\"" + afld + "_multi_online_gnd\" class=\"badge badge-secondary\">G</span>&nbsp;<span id=\"" + afld + "_multi_online_twr\" class=\"badge badge-secondary\">T</span></span>";
			multi_disp_str += "</div>";
			multi_disp_str += "<div id=\"" + afld + "_METAR\" class=\"col-sm-5 metar_m\"></div>";
			multi_disp_str += "<div class=\"col-sm-3 metar_m\">RY RVR<div id=\"" + afld + "_RVR\" class=\"rvr\">";
/*			for(var x=0; x<json.airfield_data[afld].rvr_display.length; x++) {
				multi_disp_str += json.airfield_data[afld].rvr_display[x] + "<br/>";
			}
*/
		multi_disp_str += "	</div></div><input type=\"hidden\" id=\"" + afld + "_override\" value=\"false\" /></div>";
		//airfield_listing += json.airfield_data[afld].icao_id.toUpperCase() + ", ";
		}
		document.getElementById('multi_ids_data').innerHTML = multi_disp_str;
		}
		// Refresh data fields
		//while(document.getElementById('multi_ids_data').innerHTML == '') {
			// Wait... causes the code to delay until the template is loaded into the DOM ;)
		//}
			//alert(displayedAirfields + "\n" + templateAirfields);
			//alert('refresh');
			for(afld in templateAirfields) {
				afld = templateAirfields[afld];
				if(json.airfield_data[afld] != undefined) { // Sometimes the refresh is too fast for the redraw... this prevents errors
				//alert(afld);
				document.getElementById(afld + '_ATIS').innerHTML = json.airfield_data[afld].atis_code;
				document.getElementById(afld + '_WX').innerHTML = json.airfield_data[afld].winds + "&nbsp;&nbsp;&nbsp;" + json.airfield_data[afld].altimeter;
				document.getElementById(afld + '_METAR').innerHTML = json.airfield_data[afld].metar;
				var active_rwy_apch = "";
				// New scheme to display active runways/traffic flow with the option for manual override
				var override = false;
				//if(json.override.hasOwnProperty(afld)) { // An override exists, so display it
				if(json.hasOwnProperty(override.afld)) { // An override exists, so display it
					active_rwy_apch = json.override[afld];
					override = json.override[afld];
				}
				else if(json.airfield_data[afld].apch_rwys != "") { // Display generated active runway and approach type
					for(var rwy in json.airfield_data[afld]['apch_rwys']) {
						if(active_rwy_apch.length > 0) {
							active_rwy_apch += ", ";
						}
						active_rwy_apch += json.airfield_data[afld]['apch_rwys'][rwy] + " " + json.airfield_data[afld]['apch_type'];
					}
				}
				document.getElementById(afld + '_override').value = override;
				document.getElementById(afld + '_RWYAPCH').innerHTML = active_rwy_apch;
				var rvr = "";
				for(var x=0; x<json.airfield_data[afld].rvr_display.length; x++) {
					rvr += json.airfield_data[afld].rvr_display[x] + "<br/>";
				}
				document.getElementById(afld + '_RVR').innerHTML = rvr;
				}
				// Update D/G/T badge colors based on tower cab staffing
				if(json.airfield_data[afld].tower_cab.del) {
					document.getElementById(afld + "_multi_online_del").classList.add('badge-del');
				}
				else {
					document.getElementById(afld + "_multi_online_del").classList.remove('badge-del');
				}
				if(json.airfield_data[afld].tower_cab.gnd) {
					document.getElementById(afld + "_multi_online_gnd").classList.add('badge-gnd');
				}
				else {
					document.getElementById(afld + "_multi_online_gnd").classList.remove('badge-gnd');
				}		
				if(json.airfield_data[afld].tower_cab.twr) {
					document.getElementById(afld + "_multi_online_twr").classList.add('badge-twr');
				}
				else {
					document.getElementById(afld + "_multi_online_twr").classList.remove('badge-twr');				
				}
			}
		}
		//alert(airfield_listing);
		//document.getElementById('multi_ids_data').innerHTML = multi_disp_str;
		if(changes) {
			document.getElementById("acknowledge").style.visibility = "visible";
		}
		document.getElementById('loading').className = "hideLoad"; // If the loading screen is active, hide it
	}