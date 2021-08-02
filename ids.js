	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ids.js
		Function: Javscript backend for IDS site
		Created: 4/1/21
		Edited: 
		
		Changes: 
	
	*/
	
	const defaultAirfield = 'KATL'; // I need this to be pulled from the php defines...

	// Code below handles vIDS automatic data refreshes
	var setRefreshTime = 15; // Defines vIDS refresh rate. Default is 15 (VATSIM JSON updates every 15 seconds)
	var refreshTime = setRefreshTime;
	var refreshTimer = setInterval(function(){
		if(refreshTime <= 0){
			//refreshData(); // <-- experimental... brings dataset from external AJAX instead of forced page reload
			refreshData(false,document.getElementById("pickMulti").value);
			//document.getElementById("configForm").submit();
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
	
	function showLocalIDS(template) { // Makes the local display visible
		//alert("showing local");
		document.getElementById("display_template").value = template;
		var other = "";
		if(template == "local") {
			other = "a80";
		}
		else {
			other = "local";
		}
		document.getElementById("landing_hdr").style.display = "none";
		document.getElementById("landing").style.display = "none";
		//document.getElementById("landing").style.visibility = "hidden";
		document.getElementById("local_ids").style.display = "block";
		$('.template_' + template).show();
		$('.template_' + other).hide();
	}
	
	function showMultiIDS() { // Makes the multi-airfield display visible
		//alert("showing multi");
		$('#launch_multi').modal('toggle');
		if(document.getElementById("pickMulti").value == '?') {
			$('#multi_template').modal('toggle');
		}
		else {
			refreshData(init=false,template=document.getElementById("pickMulti").value)
			document.getElementById("landing_hdr").style.display = "none";
			document.getElementById("landing").style.display = "none";
			//document.getElementById("landing").style.visibility = "hidden";
			document.getElementById("multi_ids").style.display = "block";
		}
	}
	
	function launchMulti() { 
		//document.getElementById("pickMulti").value = "X";
		$('#launch_multi').modal('toggle');
	}
	
	function templateMod(fn) { // Modify a multi-IDS template
		if(fn == 'save') {
			var valid = true;
			var errors = '';
			if(document.getElementById('template_name').value.length < 1) {
				valid = false;
				errors += '- Template name must be at least 1 character long\n';
			}
			if(document.getElementById('template_aflds').options.length < 1) {
				valid = false;
				errors += '- Please add at least 1 airfield to the tempalte\n';
			}
			
			if (valid) {
				// Save the tempalte and display it for the user
				var name = document.getElementById('template_name').value;
				var options = document.getElementById('template_aflds').options;
				var payload = name;
				for(var x=0;x<options.length;x++) {
					payload += '\n' + options[x].text;
				}
				saveConfiguration('template',payload);
				$('#multi_template').modal('toggle');
				document.getElementById("landing_hdr").style.display = "none";
				document.getElementById("landing").style.display = "none";
				document.getElementById("multi_ids").style.display = "block";
			}
			else {
				alert('Please correct the following errors:\n\n' + errors);
			}
		}
		else if(fn == 'add') {
			if(document.getElementById('template_icao').value.length == 4) {
				var option = document.createElement("option");
				option.text = document.getElementById('template_icao').value;
				option.value = 1; // I need to fix this...
				document.getElementById('template_aflds').add(option);
				document.getElementById('template_icao').value = "";
			}
			else {
				alert('ICAO airfield identifier must be 4 characters in length');
			}
		}
		else if(fn == 'rem') {
			for(var i=document.getElementById('template_aflds').options.length-1;i>=0;i--) {
				if(document.getElementById('template_aflds').options[i].selected) {
					document.getElementById('template_aflds').remove(i);
				}
			}
		}
		else if(fn == 'up') {
			var selected = $("#template_aflds").find(":selected");
			var before = selected.prev();
			if (before.length > 0)
			selected.detach().insertBefore(before);
		}
		else if(fn == 'down') {
			var selected = $("#template_aflds").find(":selected");
			var next = selected.next();
			if (next.length > 0)
				selected.detach().insertAfter(next);
		}
	}
	
	function addTemplateItem(json) {
		json = JSON.parse(json);
		//alert("attempting to append option to list. Template name: " + json.templ_name + " Filename: " + json.filename);
		//alert(json);
		el = document.getElementById('pickMulti');
		var opt = document.createElement('option');
		opt.value = json.filename;
		opt.innerHTML = json.templ_name;
		el.appendChild(opt);
		el.value = json.filename;
	}
	
	function returnToLanding(closeDiv) { // Hides an active display and returns to the landing page
		document.getElementById(closeDiv).style.display = "none";
		document.getElementById("landing_hdr").style.display = "block";
		document.getElementById("landing").style.display = "table";
		//document.getElementById("landing").style.visibility = "visible";
		if(closeDiv == "multi_ids") {
			document.getElementById("pickMulti").value = "0";
		}
	}
	
	function setActiveRunways(el) { // When in manual control (CIC mode), sets active runways and approach/departure type
		var arr_sel = document.getElementById("arr_rwy");
		var dep_sel = document.getElementById("dep_rwy");
		arr_sel.options.length = 0;
		dep_sel.options.length = 0;
		var apch_types = new Array('VIS','ILS');
		var dep_types = new Array('RV','ROTG');
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
	
	function checkDuplicates(el) { // Prevents selection of duplicate approach/departure types for the same runway
		// Placeholder for function to prevent duplicate approach/departure types from being selected.
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
	
	function saveAFLD() { // Saves airfield config settings
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
		document.getElementById("CIC_info").innerHTML = document.getElementById("CIC_text").value;
		$('#AFLD').modal('toggle');
		saveConfiguration('trips',document.getElementById("TRIPS_info").innerHTML);
		saveConfiguration('afld',afld);
		var arrivals = getSelectValues(document.getElementById("arr_rwy")).toString();
		var departures = getSelectValues(document.getElementById("dep_rwy")).toString();
		saveConfiguration('flow','\n' + document.getElementById("flow").value + '\n' + arrivals + '\n' + departures);
		saveConfiguration('cic',document.getElementById("CIC_text").value);
	}
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
			pirep_string += '\r\n';
			document.getElementById("PIREP_info").innerHTML = pirep_string + document.getElementById("PIREP_info").innerHTML;
			$('#PIREP').modal('toggle');
			saveConfiguration('pirep',pirep_string);
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
	
	function updateCtrlPos() { // Saves controller position/configuration data
		// TODO: Make the positions array global so it can be used by multiple functions and not duplicated
		var positions = new Array("LC1","LC2","LC3","LC4","LC5","GCN","GCC","GCS","GM","CD1","CD2","FD","N","S","I","P","F","X","G","Q","O","V","A","H","D","L","Y","M","W","Z","R");
		var controllers = "";
		for(var x=0;x<positions.length;x++) {
			if(controllers.length > 0)
				controllers += "|";
			controllers += document.getElementById(positions[x]).value;
		}
		saveConfiguration('controllers',controllers);
	}
	
	function saveTMU() { // Saves user-entered TMU notes
		document.getElementById("TMU_info").innerHTML = document.getElementById("TMU_text").value;
		$('#TMU').modal('toggle');
		saveConfiguration('tmu',document.getElementById("TMU_text").value);
	}
	
	function saveA80CIC() { // Saves user-entered TMU notes
		document.getElementById("A80_CIC_info").innerHTML = document.getElementById("A80_CIC_text").value;
		$('#CIC').modal('toggle');
		saveConfiguration('a80cic',document.getElementById("A80_CIC_text").value);
	}
	
	function acknowledgeAlert(el) { // Clears change notification upon user ack
		el.classList.remove("alert");
		el.classList.remove("alert-danger");
	}
	
	function refreshData(init=false,template='0') { // Fetches current dataset from network and server and inits display update
		//if(!init) { alert("Data refresh requested, template " + template); }
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				if(!!document.getElementById('json_test_dump')) {
					document.getElementById('json_test_dump').innerHTML = xhttp.responseText; // Dump the full JSON into a div
				}
				updateIDSFields(init,xhttp.responseText); // Send this JSON to another function for parse and display
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
	}

	function updateIDSFields(init,data) { // Called by refreshData() - updates all data fields with info retrieved from AJAX
		//alert(data);
		var json = JSON.parse(data);
		var changes = false;
		// Set Tower IDS fields
		//init = false; // For testing only, this needs to be removed
		changes = changeDetection(init,changes,document.getElementById("atis_code").innerHTML,json.airfield_data['KATL']['atis_code'],"atis_code");
		document.getElementById("atis_code").innerHTML = json.airfield_data['KATL']['atis_code'];
		changes = changeDetection(init,changes,document.getElementById("metar").innerHTML,json.airfield_data['KATL']['metar'],"metar");
		document.getElementById("metar").innerHTML = json.airfield_data['KATL']['metar'];
		changes = changeDetection(init,changes,document.getElementById("traffic_flow").innerHTML,json.airfield_data['KATL']['traffic_flow'],"traffic_flow");
		document.getElementById("traffic_flow").innerHTML = json.airfield_data['KATL']['traffic_flow'];
		
		var departure_rwys = "";
		for(var x=0;x<json.airfield_data['KATL']['dep_rwys'].length;x++) {
			departure_rwys += json.airfield_data['KATL']['dep_type'] + " " + json.airfield_data['KATL']['dep_rwys'][x] + "<br/>";
		}
		//changes = changeDetection(init,changes,document.getElementById("local_dep_rwys").innerHTML,departure_rwys,"local_dep_rwys");
		document.getElementById("local_dep_rwys").innerHTML = departure_rwys;
		
		// Departure splits
		var origDepSplit = document.getElementById("split_dep_rwys").innerHTML;
		if(json.airfield_data['KATL']['traffic_flow'] == "EAST") {
			document.getElementById("splits_rwy_1").innerHTML = document.getElementById("splits_rwy_id_1").value = "8R/L";
			document.getElementById("splits_rwy_2").innerHTML = document.getElementById("splits_rwy_id_2").value = "9R/L";
			document.getElementById("splits_rwy_3").innerHTML = document.getElementById("splits_rwy_id_3").value = "10";
		}
		else if(json.airfield_data['KATL']['traffic_flow'] == "WEST") {
			document.getElementById("splits_rwy_1").innerHTML = document.getElementById("splits_rwy_id_1").value = "26R/L";
			document.getElementById("splits_rwy_2").innerHTML = document.getElementById("splits_rwy_id_2").value = "27R/L";
			document.getElementById("splits_rwy_3").innerHTML = document.getElementById("splits_rwy_id_3").value = "28";
		}
		//alert(json.splits[0][0]);
		if(!$('#DepartureSplit').is(':visible')) { // This conditional prevents the refresh script from updating data entry fields when a modal is in use
	
		for(var i=1; i<4; i++) {
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
		}
		}
		configDepSplit(false, true); // Recycle the display, but don't save
		changes = changeDetection(init,changes,origDepSplit,document.getElementById("split_dep_rwys").innerHTML,"split_dep_rwys");
		
		var arrival_rwys = "";
		for(var x=0;x<json.airfield_data['KATL']['apch_rwys'].length;x++) {
			arrival_rwys += json.airfield_data['KATL']['apch_type'] + " " + json.airfield_data['KATL']['apch_rwys'][x] + "<br/>";
		}
		//changes = changeDetection(init,changes,document.getElementById("local_arr_rwys").innerHTML,arrival_rwys,"local_arr_rwys");
		document.getElementById("local_arr_rwys").innerHTML = arrival_rwys;
		// Set controller position combines in select boxes
		var controllers = json.controllers.split("|");
		var positions = new Array("LC1","LC2","LC3","LC4","LC5","GCN","GCC","GCS","GM","CD1","CD2","FD","N","S","I","P","F","X","G","Q","O","V","A","H","D","L","Y","M","W","Z","R");
		//var controllerPos = "";
		for(var x=0; x<controllers.length;x++) {
			//controllerPos += "Position: " + positions[x] + " Controller selected: " + controllers[x] + "\n";
			changes = changeDetection(init,changes,document.getElementById(positions[x]).value,controllers[x],positions[x]);
			selectOption(positions[x],controllers[x]);
		}
		//alert(changes);
		// Set text fields
		changes = changeDetection(init,changes,document.getElementById("PIREP_info").innerHTML.replace(/(\r\n|\n|\r)/gm,""),json.pirep.replace(/(\r\n|\n|\r)/gm,""),"PIREP_info");
		//alert("Current: \"" + document.getElementById("PIREP_info").innerHTML + "\"\nUpdate: \"" + json.pirep + "\"");
		document.getElementById("PIREP_info").innerHTML = json.pirep; // Display-only (entries auto-timeout)
		changes = changeDetection(init,changes,document.getElementById("TMU_info").innerHTML,json.tmu,"TMU_info");
		document.getElementById("TMU_info").innerHTML = json.tmu; // Display
		if(!$('#TMU').is(':visible')) { // This conditional prevents the refresh script from updating data entry fields when a modal is in use
			document.getElementById("TMU_text").innerHTML = json.tmu; // Data entry
		}

		document.getElementById("A80_CIC_info").innerHTML = json.a80cic;
		if(!$('#CIC').is(':visible')) { // This conditional prevents the refresh script from updating data entry fields when a modal is in use
			document.getElementById("A80_CIC_text").value = json.a80cic; // Data entry
		}		
		
		changes = changeDetection(init,changes,document.getElementById("TRIPS_info").innerHTML,json.trips['raw'],"TRIPS_info");
		document.getElementById("TRIPS_info").innerHTML = json.trips['raw']; // Display
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
		changes = changeDetection(init,changes,document.getElementById("AFLD_info").innerHTML,json.config['raw'],"AFLD_info");
		document.getElementById("AFLD_info").innerHTML = json.config['raw']; // Display
		changes = changeDetection(init,changes,document.getElementById("CIC_info").innerHTML,json.cic,"CIC_info");
		//alert("CIC Info\nIn DOM: \"" + document.getElementById("CIC_info").innerHTML + "\"\nFrom file: \"" + json.cic.replace(/(\n)/gm,"") + "\"");
		document.getElementById("CIC_info").innerHTML = json.cic; // Display
		//document.getElementById("CIC_text").innerHTML = json.cic; // Data entry
		if(!$('#AFLD').is(':visible')) { // This conditional prevents the refresh script from updating data entry fields when a modal is in use
			document.getElementById("flow").value = json.airfield_data['KATL']['traffic_flow'].trim();
			setActiveRunways(document.getElementById("flow"));
			for (var i = 0; i < document.getElementById("arr_rwy").options.length; i++) {
				document.getElementById("arr_rwy").options[i].selected = json.airfield_data['KATL']['apch_rwys'].indexOf(document.getElementById("arr_rwy").options[i].value) >= 0;
			}
			for (var i = 0; i < document.getElementById("dep_rwy").options.length; i++) {
				document.getElementById("dep_rwy").options[i].selected = json.airfield_data['KATL']['dep_rwys'].indexOf(document.getElementById("dep_rwy").options[i].value) >= 0;
			}
			document.getElementById("fta").checked = json.trips['FTA']; // Data entry
			document.getElementById("ftd").checked = json.trips['FTD']; // Data entry
			document.getElementById("ninelm2").checked = json.config['9L@M2']; // Data entry
			document.getElementById("lahso").checked = json.config['LAHSO']; // Data entry
			document.getElementById("CIC_text").innerHTML = json.cic; // Data entry
			document.getElementById("AutoIDS").checked = json.config['AUTO']; // Data entry
		}
		if(!json.config['AUTO']) {
			document.getElementById("flow").disabled = false;
			document.getElementById("arr_rwy").disabled = false;
			document.getElementById("dep_rwy").disabled = false;
		}
		//alert(json.gates);
		if(json.gates.length == 3) {
			changes = changeDetection(init,changes,document.getElementById("dep_gate_n").value,json.gates[0],"dep_gate_n");
			changes = changeDetection(init,changes,document.getElementById("dep_gate_s").value,json.gates[1],"dep_gate_s");
			changes = changeDetection(init,changes,document.getElementById("dep_gate_i").value,json.gates[2],"dep_gate_i");
			document.getElementById("dep_gate_n").value = json.gates[0];	
			document.getElementById("dep_gate_s").value = json.gates[1];
			document.getElementById("dep_gate_i").value = json.gates[2];
			if(!$('#DepartureGates').is(':visible')) { // This conditional prevents the refresh script from updating data entry fields when a modal is in use
				document.getElementById("depGateN").value = json.gates[0];
				document.getElementById("depGateS").value = json.gates[1];
				document.getElementById("depGateI").value = json.gates[2];
			}	
		}

		// Set A80 satellite and outer field info
		var underlying_fields = new Array("KPDK","KFTY","KMGE","KRYY","KLZU","KMCN","KWRB","KAHN","KCSG"); // I intentionally left LSF out... we never use it
		//var underlying_fields = new Array("KPDK"); // For testing only.. real world use the line above to get all of the satellites
		for(var y=0;y<underlying_fields.length;y++) {
			var open_closed = "CLOSED";
			if(underlying_fields[y] in json.airfield_data) {
			document.getElementById(underlying_fields[y] + "_atis_code").innerHTML = json.airfield_data[underlying_fields[y]].atis_code;
			if(json.airfield_data[underlying_fields[y]].atis_code != "--") {
				open_closed = "OPEN";
			}
			document.getElementById(underlying_fields[y] + "_open_closed").innerHTML = open_closed;
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
			if(json.override.hasOwnProperty(afld)) { // An override exists, so display it
				active_apch_rwys = json.override[afld];
				override = json.override[afld];
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
		
		// Set MULTI-IDS fields
		//var multi_disp_str = '<div class=\"landing_menu\"><a onclick=\"returnToLanding(\'multi_ids\');\"><i class=\"fas fa-bars\"></i></a></div>';
		var multi_disp_str = '';
		//alert("KATL approach type: " + json.airfield_data['KATL']['apch_rwys'].join(", "));
		//alert("Resetting Multi-IDS airfields..." + JSON.stringify(json.template));
		//var airfield_listing = "";
		//for(afld in json.airfield_data.template) {
		if(json.template === null) { // Refresh isn't finished... show a loading message
			multi_disp_str = "<div class=\"row\"><div class=\"col-lg\"><h3>Multi-IDS display is loading... please wait</h3></div></div>";
		}
		else {
		var defaultAirfieldNoChange = "alert('Configuration for this airfield must be set through the local vIDS display by the tower CIC.');";
		for(afld in json.airfield_data) {
			afld = afld.toUpperCase();
			if(afld == defaultAirfield) {
				multi_disp_str += "<div class=\"row\" onclick=\"" + defaultAirfieldNoChange + "\">";
			}
			else {
				multi_disp_str += "<div class=\"row\" onclick=\"airfieldConfig('" + afld + "');\">";
			}
			multi_disp_str += "<div class=\"col-lg-1\"><div class=\"vert_id\">" + json.airfield_data[afld].icao_id.substr(1,1).toUpperCase() + "</div><div class=\"vert_id\">" + json.airfield_data[afld].icao_id.substr(2,1).toUpperCase() + "</div><div class=\"vert_id\">" + json.airfield_data[afld].icao_id.substr(3,1).toUpperCase() + "</div></div>";
			multi_disp_str += "<div class=\"col-lg-1 atis_code_m\">" + json.airfield_data[afld].atis_code + "</div>";
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
			/*
			else if((json.airfield_data[afld]['apch_rwys'].length > 0)&&(json.airfield_data[afld].apch_type != "")) { // We need to combine the runway and approach types into a string
				for(var x=0;x<json.airfield_data[afld]['apch_type'].length;x++) {
					if(active_rwy_apch.length > 0) {
						active_rwy_apch += ", ";
					}
					active_rwy_apch += json.airfield_data[afld]['apch_rwys'][x] + " " + json.airfield_data[afld]['apch_type'];
				}
			}
			*/
			else {
				//active_rwy_apch = json.airfield_data[afld].apch_rwys.join(", ");
			}
			multi_disp_str += "<div class=\"col-lg-2 arrival_info\"><div class=\"apch_type\">" + active_rwy_apch + "</div><div></div>";
			multi_disp_str += "<div class=\"wx\">" + json.airfield_data[afld].winds + "&nbsp;&nbsp;&nbsp;" + json.airfield_data[afld].altimeter + "</div></div>";
			multi_disp_str += "<div class=\"col-lg-5 metar_m\">" + json.airfield_data[afld].metar + "</div>";
			multi_disp_str += "<div class=\"col-lg-3 metar_m\">RY RVR<div class=\"rvr\">";
			for(var x=0; x<json.airfield_data[afld].rvr_display.length; x++) {
				multi_disp_str += json.airfield_data[afld].rvr_display[x] + "<br/>";
			}
		multi_disp_str += "	<input type=\"hidden\" id=\"" + afld + "_override\" value=\"" + override + "\" /></div></div></div>";
		//airfield_listing += json.airfield_data[afld].icao_id.toUpperCase() + ", ";
		}
		}
		//alert(airfield_listing);
		document.getElementById('multi_ids_data').innerHTML = multi_disp_str;
		if(changes) {
			document.getElementById("acknowledge").style.visibility = "visible";
		}
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
			document.getElementById("dep_gate_n").value = document.getElementById("depGateN").value;
			document.getElementById("dep_gate_s").value = document.getElementById("depGateS").value;
			document.getElementById("dep_gate_i").value = document.getElementById("depGateI").value;
			var gateConfig = "N:" + document.getElementById("depGateN").value + "\n";
			gateConfig += "S:" + document.getElementById("depGateS").value + "\n";
			gateConfig += "I:" + document.getElementById("depGateI").value;
			saveConfiguration('gates',gateConfig);
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
			var gates = new Array("n1","n2","w2","w1","s2","s1","e1","e2");
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
	
	function checkboxToName(el) {
		if(el.type && el.type === 'checkbox') {
			if(el.checked) {
				return el.name;
			}
		}
		return "";
	}
	
	function valueToCheckbox(val) {
		if(val.length > 0) {
			return true;
		}
		return false;
	}
	
	function selectOption(id, selectValue) {  // Helper function for dealing with select options
		
		let element = document.getElementById(id);
		element.value = selectValue;
	}
	
	function changeDetection(init,ch,val1,val2,el) { // Change detection and alerting system
		if(!init) { // There is no change detection when the IDS is initializing
			if(val1 != val2) {
				addClass = "change";
				//if(document.getElementById(el).nodeName == "SELECT") {
				//	addClass = "";
				//}
				document.getElementById(el).classList.add(addClass);
				return true;
			}
			else {
				return ch;
			}
		}
		else {
			return ch;
		}
	}
	
	function acknowledgeChanges() { // Handles user acknowledgement of changes
		$('.change').removeClass('change');
		document.getElementById("acknowledge").style.visibility = "hidden";
	}
	
	// Make acknowledge button flash
	var backgroundInterval = setInterval(function(){
		$("#acknowledge").toggleClass("backgroundRed");
	},1500)
	
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
		xhttp.open("GET", "ajax_handler.php?type=" + type + "&payload=" + encodeURIComponent(payload), true);
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
	}
	
	function clearStyle(x) { // Removes classes applied to an element
		x.className = '';
	}
	
	// TODO: Remove this plugin code and put it in a seperate JS file - it is not native to this project
	// PDF in modal plugin
	// Removed 6/14/2021 - no longer needed, using embed instead
/*
(function(a){a.createModal=function(b){defaults={title:"",message:"Your Message Goes Here!",closeButton:true,scrollable:false};var b=a.extend({},defaults,b);var c=(b.scrollable===true)?'style="max-height: 420px;overflow-y: auto;"':"";html='<div class="modal fade" id="myModal">';html+='<div class="modal-dialog">';html+='<div class="modal-content">';html+='<div class="modal-header">';html+='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';if(b.title.length>0){html+='<h4 class="modal-title">'+b.title+"</h4>"}html+="</div>";html+='<div class="modal-body" '+c+">";html+=b.message;html+="</div>";html+='<div class="modal-footer">';if(b.closeButton===true){html+='<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'}html+="</div>";html+="</div>";html+="</div>";html+="</div>";a("body").prepend(html);a("#myModal").modal().on("hidden.bs.modal",function(){a(this).remove()})}})(jQuery);


$(function(){    
    $('.view-pdf').on('click',function(){
        var pdf_link = $(this).attr('href');
        //var iframe = '<div class="iframe-container"><iframe src="'+pdf_link+'"></iframe></div>'
        //var iframe = '<object data="'+pdf_link+'" type="application/pdf"><embed src="'+pdf_link+'" type="application/pdf" /></object>'        
        var iframe = '<object type="application/pdf" data="'+pdf_link+'" width="100%" height="500">No Support</object>'
        $.createModal({
            title: $(this).attr('title'),
            message: iframe,
            closeButton:true,
            scrollable:false
        });
        return false;        
    });    
})
*/
function getSelectValues(select) { // Helper function for multi-select fields
  var result = [];
  var options = select && select.options;
  var opt;

  for (var i=0, iLen=options.length; i<iLen; i++) {
    opt = options[i];

    if (opt.selected) {
      result.push(opt.value || opt.text);
    }
  }
  return result;
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
					document.getElementById("bug_report_name").value = "";
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