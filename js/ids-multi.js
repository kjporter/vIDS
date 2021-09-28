	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: ids_multi.js
		Function: Javscript methods supporting multi view
		Created: 9/17/21 (broken out from ids.js)
		Edited: 
		
		Changes: 
	
	*/

	function showMultiIDS() { // Makes the multi-airfield display visible
		loadingNotice();
		$('#launch_multi').modal('toggle');
		if(document.getElementById("pickMulti").value == '?') {
			$('#multi_template').modal('toggle');
		}
		else {
			refreshData(init=false,template=document.getElementById("pickMulti").value)
			document.body.style.backgroundColor = "black";
			document.body.style.backgroundImage = null;
			document.getElementById("landing_hdr").style.display = "none";
			document.getElementById("landing").style.display = "none";
			//document.getElementById("landing").style.visibility = "hidden";
			document.getElementById("multi_ids").style.display = "block";
			//alert(document.getElementById('templateCreator').value);
			/*
			if((document.getElementById("cid").value == ADMIN)||(document.getElementById("cid").value == document.getElementById('templateCreator').value)) { //**Note: need to add conditional in to allow creator to delete their own templates
				document.getElementById("templateDeleteMenu").classList.remove('disabled');
			}
			else {
				document.getElementById("templateDeleteMenu").classList.add('disabled');
			}
			*/
		}
	}
	
	function launchMulti() { 
		//document.getElementById("pickMulti").value = "X";
		//alert(document.getElementById('pickMulti').selectedIndex);
		$('#launch_multi').modal('toggle');
	}

function removeTemplate(fn=false) { // Remove a multi-IDS template
	if(!document.getElementById("templateDeleteMenu").classList.contains('disabled')) {
	var sel = document.getElementById('pickMulti');
	//alert(sel.selectedIndex);
	document.getElementById('remTemplate').innerHTML = sel.options[sel.selectedIndex].text;
	if(fn) {
		// Delete the template file
		var xhttp;
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				// Template deleted, remove from select list
				//alert('Removing index: ' + sel.selectedIndex);
				sel.remove(sel.selectedIndex);
				returnToLanding('multi_ids');
			}
			else {
			}
		};
		//alert(sel.value);
		xhttp.open("GET", "ajax_handler.php?delete=" + sel.value + "&cid=" + document.getElementById('cid').value, true); 
		xhttp.send();
	}
		$('#TemplateDelete').modal('toggle');
	}
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
				var payload = name + '\n' + document.getElementById('cid').value;
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