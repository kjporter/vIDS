	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ids_grid.js
		Function: Javscript methods supporting grid view (local and large TRACON)
		Created: 9/17/21 (broken out from ids.js)
		Edited: 
		
		Changes: 
	
	*/
	
	function showLocalIDS(template) { // Makes the local display visible
		loadingNotice();
		document.getElementById("display_template").value = template;
		var other = "";
		if(template == "local") {
			other = "a80";
		}
		else {
			other = "local";
		}
		document.body.style.backgroundColor = "black";
		document.body.style.backgroundImage = null;
		document.getElementById("landing_hdr").style.display = "none";
		document.getElementById("landing").style.display = "none";
		document.getElementById("local_ids").style.display = "block";
		$('.template_' + template).show();
		$('.template_' + other).hide();
		if(template == 'a80') { // Because Joe wants it this way... note
			var temp = document.getElementById('grid5x1').innerHTML;
			document.getElementById('grid5x1').innerHTML = document.getElementById('grid5x2').innerHTML;
			document.getElementById('grid5x2').innerHTML = temp;
		}
		else {
			if(document.getElementById('grid5x2').innerHTML.includes('Outer')) { // User was viewing A80 and has switched to local, so revert display
				var temp = document.getElementById('grid5x1').innerHTML;
				document.getElementById('grid5x1').innerHTML = document.getElementById('grid5x2').innerHTML;
				document.getElementById('grid5x2').innerHTML = temp;				
			}
		}
		setDynamicMargin();
		acknowledgeChanges(); // Clear change acknowledge (if user was monitoring multi and decided to switch displays)
	}
	
function saveControllerEdit() {
	var editFields = document.getElementsByClassName("controllerEdit");
	var dispFields = document.getElementsByClassName("controllerDisplay");
	if (document.getElementById("controller_edit_active").checked) { // Enter controller edit mode
		for(var x=0;x<editFields.length;x++) {
			editFields[x].classList.add('showControl');
			editFields[x].classList.remove('hideControl');
			dispFields[x].classList.add('hideControl');
			dispFields[x].classList.remove('showControl');

		}
	}
	else { // Enter controller display mode
		for(var x=0;x<editFields.length;x++) {
			editFields[x].classList.add('hideControl');
			editFields[x].classList.remove('showControl');
			dispFields[x].classList.add('showControl');
			dispFields[x].classList.remove('hideControl');

		}
	}
	$('#ControllerEdit').modal('toggle');
}

function setDynamicMargin() {
	var buttonHeight = $("#buttons").height();     
     $(".dynMargin").css('margin-bottom',buttonHeight); 
}

function linkify(str) { // Not being used, but can turn links in text into a clickable link
	var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
	str = str.replace(exp,"<a href=\"$1\" target=\"_blank\">$1</a>"); 
	return str;
}

function unlinkify(str) {
	//return $('#' + id + ' a').contents().unwrap();
	let tmp = document.createElement("DIV");
	tmp.innerHTML = str;
	return tmp.textContent || tmp.innerText || "";
}

function checkDST() {
Date.prototype.stdTimezoneOffset = function () {
    var jan = new Date(this.getFullYear(), 0, 1);
    var jul = new Date(this.getFullYear(), 6, 1);
    return Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
}

Date.prototype.isDstObserved = function () {
    return this.getTimezoneOffset() < this.stdTimezoneOffset();
}

var today = new Date();
if (today.isDstObserved()) { 
    return true;
}
else return false;	
}

	function updateCtrlPos() { // Saves controller position/configuration data
		// TODO: Make the positions array global so it can be used by multiple functions and not duplicated
		//var positions = new Array("LC-1","LC-2","LC-3","LC-4","LC-5","GC-N","GC-C","GC-S","GM","CD-1","CD-2","FD","N","S","I","P","F","X","G","Q","O","V","A","H","D","L","Y","M","W","Z","R","E","3E");
		var controllers = "";
		for(var x=0;x<positions.length;x++) {
			if(controllers.length > 0)
				controllers += "|";
			controllers += document.getElementById(positions[x]).value;
		}
		saveConfiguration('controllers',controllers);
	}
	
	function acknowledgeAlert(el) { // Clears change notification upon user ack
		el.classList.remove("alert");
		el.classList.remove("alert-danger");
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