	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: helpers.js
		Function: Javscript helper methods
		Created: 9/17/21 (broken out from ids.js)
		Edited: 
		
		Changes: 
	
	*/

	function loadingNotice() { // Moves progress bar in loading notice
		// Initialize progress bar to zero
		document.getElementById('loadingProgress').setAttribute("style","width:0%");
		document.getElementById('loadingProgress').setAttribute("aria-valuenow",0);
		var loadtime = 15; // Seconds
		document.getElementById('loading').className = "showLoad";
		var seconds = 0;
		var t = setInterval(function() {
			if(document.getElementById('loading').className == "hideLoad") {
				clearInterval(t);
			}
			seconds += 0.25;
			var percentage = (seconds/loadtime) * 100;
				document.getElementById('loadingProgress').setAttribute("style","width:" + percentage + "%");
				document.getElementById('loadingProgress').setAttribute("aria-valuenow",percentage);
				//$('#loadingProgress').css('width', percentage+'%').attr('aria-valuenow', percentage);
			if(seconds == loadtime) {
				clearInterval(t);
			}
		},250);
	}
	
	function generateDropdown(afldId,defAfld) {
		var configAfld = "airfieldConfig('" + afldId + "')";
		if(afldId == defAfld) {
			configAfld = "alert('Configuration for this airfield must be set through the local vIDS display by the tower CIC.');";
		}
		var str = "<div class=\"dropdown noclear\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"fas fa-caret-square-down\"></i></a><ul class=\"dropdown-menu\" role=\"menu\"  aria-labelledby=\"dLabel\"><li class=\"dropdown-header\">" + afldId + "</li><li class=\"divider\"></li><li><a href=\"#\" onclick=\"" + configAfld + "\">Airfield Config</a></li><li><a href=\"#PROC\" onclick=\"loadProc('" + afldId + "');\" data-toggle=\"modal\">Instrument Procedures</a></li></ul></div>";
		return str;
	}

// Drag events handle drag & drop of IDS airfield content boxes - source from syntaxxx.com (https://syntaxxx.com/rearranging-web-page-items-with-html5-drag-and-drop/)
function dragStarted(evt) {
	//Start drag
	source = evt.target;
	//Set data
	evt.dataTransfer.setData("text/plain", evt.target.innerHTML);
	//Specify allowed transfer
	evt.dataTransfer.effectAllowed = "move";
}

function draggingOver(evt) {
	//Drag over
	evt.preventDefault();
	//Specify operation
	evt.dataTransfer.dropEffect = "move";
}

function dropped(evt) {
	//Drop
	evt.preventDefault();
	evt.stopPropagation();
	//alert(evt.target.closest("div.moveable").tagName + ' ' + evt.target.closest("div.moveable").classList + ' dump: ' + evt.dataTransfer.getData("text/plain"));
	//alert(source.closest("div.moveable").tagName + ' ' + source.closest("div.moveable").classList + ' dump: ' + evt.target.closest("div.moveable").innerHTML);

	//Update text in dragged item
	//source.innerHTML = evt.target.innerHTML;
	source.closest("div.moveable").innerHTML = evt.target.closest("div.moveable").innerHTML;
	//Update text in drop target
	//evt.target.innerHTML = evt.dataTransfer.getData("text/plain");
	evt.target.closest("div.moveable").innerHTML = evt.dataTransfer.getData("text/plain")
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

function browser_detection() { // Provides alerting functions for antique browsers and mobile devices that may be incompatible
	// From https://stackoverflow.com/questions/9847580/how-to-detect-safari-chrome-ie-firefox-and-opera-browser
	// Internet Explorer 6-11
	var isIE = ((navigator.userAgent.indexOf("MSIE") != -1 ) || (!!document.documentMode == true ));
	// From https://stackoverflow.com/questions/11381673/detecting-a-mobile-browser
	var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
	var alertString = new Array();
	if(isIE) {
		alertString.push("Your browser may be incompatible with some of the features of this site. Please consider upgrading to a modern browser.");
	}
	if(isMobile) {
		alertString.push("This site was not designed to work with mobile devices. Please consider accessing this site from a computer or tablet.");
	}
	if(alertString.length > 0) {
		alert(alertString.join("\n"));
	}
}