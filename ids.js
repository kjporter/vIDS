	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ids.js
		Function: Javscript backend for IDS site
		Created: 4/1/21
		Edited: 
		
		Changes: 
	
	*/

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
		document.getElementById("landing").style.display = "none";
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
			document.getElementById("landing").style.display = "none";
			document.getElementById("multi_ids").style.display = "block";
		}
	}
	
	function launchMulti() { // Don't remember what this does... is it even used?
		document.getElementById("pickMulti").value = "X";
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
	
	function returnToLanding(closeDiv) { // Hides an active display and returns to the landing page
		document.getElementById(closeDiv).style.display = "none";
		document.getElementById("landing").style.display = "block";
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
/*
		var controllers = document.getElementById("LC1").value + '|';
		controllers += document.getElementById("LC2").value + '|';
		controllers += document.getElementById("LC3").value + '|';
		controllers += document.getElementById("LC4").value + '|';
		controllers += document.getElementById("LC5").value + '|';
		controllers += document.getElementById("GCN").value + '|';
		controllers += document.getElementById("GCC").value + '|';
		controllers += document.getElementById("GCS").value + '|';
		controllers += document.getElementById("GM").value + '|';
		controllers += document.getElementById("CD1").value + '|';
		controllers += document.getElementById("CD2").value + '|';
		controllers += document.getElementById("FD").value + '|';
		controllers += document.getElementById("N").value + '|';
		controllers += document.getElementById("S").value + '|';
		controllers += document.getElementById("I").value + '|';
		controllers += document.getElementById("P").value + '|';
		controllers += document.getElementById("F").value + '|';
		controllers += document.getElementById("X").value + '|';
		controllers += document.getElementById("G").value + '|';
		controllers += document.getElementById("Q").value + '|';
		controllers += document.getElementById("O").value + '|';
		controllers += document.getElementById("V").value + '|';
		controllers += document.getElementById("A").value + '|';
		controllers += document.getElementById("H").value + '|';
		controllers += document.getElementById("D").value + '|';
		controllers += document.getElementById("L").value + '|';
		controllers += document.getElementById("Y").value + '|';
		controllers += document.getElementById("M").value + '|';
		controllers += document.getElementById("W").value + '|';
		controllers += document.getElementById("Z").value + '|';
		controllers += document.getElementById("R").value;
*/
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
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				//document.getElementById('json_test_dump').innerHTML = xhttp.responseText; // Uncomment this line to dump the full JSON into a div
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
		xhttp.open("GET", "ajax_refresh.php?live=" + liveData + "&template=" + template, true); 
		xhttp.send(); 
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
		document.getElementById("TMU_text").innerHTML = json.tmu; // Data entry
		changes = changeDetection(init,changes,document.getElementById("TRIPS_info").innerHTML,json.trips['raw'],"TRIPS_info");
		document.getElementById("TRIPS_info").innerHTML = json.trips['raw']; // Display
		document.getElementById("fta").checked = json.trips['FTA']; // Data entry
		//alert("FTA: " + json.trips['FTA'] + "\nFTD: " + json.trips['FTD']);
		document.getElementById("ftd").checked = json.trips['FTD']; // Data entry
		document.getElementById("ninelm2").checked = json.config['9L@M2']; // Data entry
		document.getElementById("lahso").checked = json.config['LAHSO']; // Data entry
		document.getElementById("AutoIDS").checked = json.config['AUTO']; // Data entry
		if(!json.config['AUTO']) {
			document.getElementById("flow").disabled = false;
			document.getElementById("arr_rwy").disabled = false;
			document.getElementById("dep_rwy").disabled = false;
		}
		/*
		if(json.config['AUTO']) {
			document.getElementById("flow").disabled = true;
			document.getElementById("arr_rwy").disabled = true;
			document.getElementById("dep_rwy").disabled = true;
		}
		*/
		//alert("." + json.airfield_data['KATL']['traffic_flow'].trim() + ".");
		document.getElementById("flow").value = json.airfield_data['KATL']['traffic_flow'].trim();
		setActiveRunways(document.getElementById("flow"));
		for (var i = 0; i < document.getElementById("arr_rwy").options.length; i++) {
			document.getElementById("arr_rwy").options[i].selected = json.airfield_data['KATL']['apch_rwys'].indexOf(document.getElementById("arr_rwy").options[i].value) >= 0;
		}
		for (var i = 0; i < document.getElementById("dep_rwy").options.length; i++) {
			document.getElementById("dep_rwy").options[i].selected = json.airfield_data['KATL']['dep_rwys'].indexOf(document.getElementById("dep_rwy").options[i].value) >= 0;
		}
		//alert("Current: \"" + document.getElementById("AFLD_info").innerHTML + "\"\nUpdate: \"" + json.config['raw'] + "\"");
		changes = changeDetection(init,changes,document.getElementById("AFLD_info").innerHTML,json.config['raw'],"AFLD_info");
		document.getElementById("AFLD_info").innerHTML = json.config['raw']; // Display
		changes = changeDetection(init,changes,document.getElementById("CIC_info").innerHTML,json.cic,"CIC_info");
		//alert("CIC Info\nIn DOM: \"" + document.getElementById("CIC_info").innerHTML + "\"\nFrom file: \"" + json.cic.replace(/(\n)/gm,"") + "\"");
		document.getElementById("CIC_info").innerHTML = json.cic; // Display
		document.getElementById("CIC_text").innerHTML = json.cic; // Data entry

		// Set A80 satellite and outer field info
		var underlying_fields = new Array("KPDK","KFTY","KMGE","KRYY","KLZU","KMCN","KWRB","KAHN","KCSG"); // I intentionally left LSF out... we never use it
		//var underlying_fields = new Array("KPDK"); // For testing only.. real world use the line above to get all of the satellites
		for(var y=0;y<underlying_fields.length;y++) {
			var open_closed = "CLOSED";
			document.getElementById(underlying_fields[y] + "_atis_code").innerHTML = json.airfield_data[underlying_fields[y]].atis_code;
			if(json.airfield_data[underlying_fields[y]].atis_code != "--") {
				open_closed = "OPEN";
			}
			document.getElementById(underlying_fields[y] + "_open_closed").innerHTML = open_closed;
			document.getElementById(underlying_fields[y] + "_metar").innerHTML = json.airfield_data[underlying_fields[y]].metar;
			var active_rwys = "--";
			if(json.airfield_data[underlying_fields[y]].dep_rwys != null) {
				for(var z=0;z<json.airfield_data[underlying_fields[y]].dep_rwys.length;z++) {
					active_rwys += json.airfield_data[underlying_fields[y]].dep_rwys[z] + " ";
				}
			}
			document.getElementById(underlying_fields[y] + "_runway").innerHTML = active_rwys;
		}
		
		// Set TRACON IDS fields
		//var multi_disp_str = '<div class=\"landing_menu\"><a onclick=\"returnToLanding(\'multi_ids\');\"><i class=\"fas fa-bars\"></i></a></div>';
		var multi_disp_str = '';
		
		for(afld in json.airfield_data) {
			multi_disp_str += "<div class=\"row\">";
			multi_disp_str += "<div class=\"col-lg-1\"><div class=\"vert_id\">" + json.airfield_data[afld].icao_id.substr(1,1).toUpperCase() + "</div><div class=\"vert_id\">" + json.airfield_data[afld].icao_id.substr(2,1).toUpperCase() + "</div><div class=\"vert_id\">" + json.airfield_data[afld].icao_id.substr(3,1).toUpperCase() + "</div></div>";
			multi_disp_str += "<div class=\"col-lg-1 atis_code_m\">" + json.airfield_data[afld].atis_code + "</div>";
			multi_disp_str += "<div class=\"col-lg-2 arrival_info\"><div class=\"apch_type\">" + json.airfield_data[afld].apch_type + "</div><div></div>";
			multi_disp_str += "<div class=\"wx\">" + json.airfield_data[afld].winds + "&nbsp;&nbsp;&nbsp;" + json.airfield_data[afld].altimeter + "</div></div>";
			multi_disp_str += "<div class=\"col-lg-5 metar_m\">" + json.airfield_data[afld].metar + "</div>";
			multi_disp_str += "<div class=\"col-lg-3 metar_m\">RY RVR<div class=\"rvr\">";
			for(var x=0; x<json.airfield_data[afld].rvr_display.length; x++) {
				multi_disp_str += json.airfield_data[afld].rvr_display[x] + "<br/>";
			}
		multi_disp_str += "	</div></div></div>";
		}
		document.getElementById('multi_ids_data').innerHTML = multi_disp_str;
		if(changes) {
			document.getElementById("acknowledge").style.visibility = "visible";
		}
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
		xhttp.open("GET", "bug_reporting.php?bug_description=" + document.getElementById("bug_description").value, true); 
		xhttp.send(); 
	}
	
	function showAboutHelp() { // Makes the multi-airfield display visible
		$('#about_help').modal('toggle');
	}
	
	function showBugReportReferal(x) {
		$('#' + x).modal('toggle');
		$('#BUG').modal('toggle');
	}