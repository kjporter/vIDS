<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: index.php
		Function: Website index file - contains baseline HTML template for site
		Created: 4/1/21
		Edited: 
		
		Changes: 
		
		VATSIM Data Provider: http://status.vatsim.net/
	*/

	include_once "vars/config.php";
	include_once "common.php";
	include_once "user_authentication.php";

	//Init and run front-end security via VATSIM Connect SSO
	$auth = new Security(fetch_my_url(),$sso_variables);
	if(is_array($auth->fetch_endpoint())) {
		extract($auth->fetch_endpoint()); // Return SSO variables to be used by login button
	}
	$auth->init_sso(); // Attempt to init the sign on sequence
	if(is_array($auth->fetch_params())) {
		extract($auth->fetch_params(),EXTR_OVERWRITE); // Return authentication parameters
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>vIDS</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link data-cfasync="false" rel="stylesheet" href="<?php echo auto_version($documentRoot . 'ids.css'); ?>">
	<link rel="shortcut icon" href="img/favicon.ico" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	<!-- TODO: Add support for bootstrap 5.x and remove bootstrap dependencies 3.X from project -->
<!--	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
-->	
	<script src="https://kit.fontawesome.com/9bd47a7738.js" crossorigin="anonymous"></script> <!-- used for glyph icons in tower IDS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- used for glyph icons in tower IDS -->
	<script data-cfasync="false"><?php echo js_globals(); ?></script>
	<script data-cfasync="false" src="<?php echo auto_version($documentRoot . 'js/ids.js'); ?>"></script>
</head>
<?php
/*
	// Picks a random image from the $imagesDir to display in the landing page background -- moved to common.css
	$imagesDir = 'img/bg/';
	$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
	$randomImage = $images[array_rand($images)];
*/
?>
<body style="background-image: url('<?php echo $randomImage; ?>')" onload="refreshData(true);">  <!-- refreshData call initializes the display data -->
	<input type="hidden" id="cid" value="<?php echo $vatsim_cid; ?>" />
	<input type="hidden" id="ad" value="<?php echo is_sysad($vatsim_cid,$artcc_staff,$sso_endpoint); ?>" />
	<input type="hidden" id="bgimg" value="<?php echo $randomImage; ?>" />
	<div id="container">
		<div id="alerts" class="container fixed-top">  <!-- alert box displays authentication info -->
			<div class="row no_border" style="padding: 5px; visibility:hidden">
				<div id="alert" class="col <?php echo $alert_style; ?>">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<span id="alert_text"><?php echo $alert_text; ?></span>
				</div>
			</div>
		</div>

<?php 
// Contains UI definition for local and A80 display
include "ids_grid_template.php"; 
?>

	<!-- MULTI (TRACON/ARTCC) IDS DISPLAY -->
	<div id="multi_ids" class="container-fluid" style="display:none;">
		<div class="landing_menu">
		<div class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fas fa-bars"></i></a>
			<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dLabel">
				<li><a href="#" onclick="returnToLanding('multi_ids');">Return to menu</a></li>
				<?php if(BUG_REPORTING) {
					print "<li><a href=\"#BUG\" data-toggle=\"modal\">Report a bug</li></li>";
				}?>
			<!--	<li><a href="#BUG" data-toggle="modal">Report a bug</a></li> -->
				<li><a href="#mHELP" data-toggle="modal">Help</a></li>
				<div class="dropdown-divider"></div>
				<li><a href="#" onclick="launchMulti();">Select template</a></li>
				<li id="templateDeleteMenu" class="dropdown-item disabled"><a href="#" onclick="removeTemplate();">Remove this template</a></li>
				<li role="separator" class="divider dropdown-divider"></li>
				<h6 class="dropdown-header">Controller tools</h6>
				<li><a href="#WX" data-toggle="modal">Weather</a></li>
				<li><a href="#PIREP" data-toggle="modal">PIREP</a></li>
				<li><a href="#ACFT" data-toggle="modal">A/C Types</a></li>
				<li><a href="#aRELIEF" data-toggle="modal">Briefing</a></li>
				<li><a href="#EMER" data-toggle="modal">Emergency</a></li>
				<li role="separator" class="divider dropdown-divider"></li>
				<h6 class="dropdown-header">A80 top-down</h6>
				<li><a href="#RECAT" data-toggle="modal">RECAT</a></li>
				<li><a href="#aFREQS" data-toggle="modal">FREQs</a></li>
				<li><a href="#aARSPC" data-toggle="modal">Airspace</a></li>
				<li><a href="#ROTG" data-toggle="modal">ROTG</a></li>
				<li><a href="#aSOP" data-toggle="modal">SOP</a></li>				
				<li><a href="#aLOA" data-toggle="modal">LOA</a></li>	
			</ul>
		</div>
		</div>
		<div id="multi_ids_data"></div> <!-- JS dumps the display data here -->
	</div>
	
	<!-- LANDING MENU -->
	<div id="landing_hdr" class="container-fluid">
	<div class="row" style="padding-left: 5px; border:0px;"><h3 class="landing_header_text"><?php echo FACILITY_ID; ?> vIDS - Virtual Information Display System</h3></div>
	<?php
	if($valid_auth) {
		print "<div class=\"row\" style=\"padding-left: 25px; border:0px;\"><h4 class=\"landing_header_text\">Hello, $full_name ($user_rating)</h4></div>";
	}
	?>
	</div>
	<div id="landing" class="container container-table">
		<div class="row vertical-center-row" style="border:0px">
			<div class="col-md-6 col-md-offset-3" border:0px">
				<div class="row" style="border:0px; text-align:center">
					
				</div>
<?php 
// Displays the login prompt if user is not authenticated and the landing menu if user is authenticated
if(!$valid_auth) {
	$url = $redirect_uri . "&response_type=code&scope=full_name vatsim_details";
	print "	<div id=\"auth\" class=\"row\" style=\"border:0px\">
			<div class=\"col menu_button\"><br/>
			<a href=\"$sso_endpoint/oauth/authorize?client_id=$client_id&redirect_uri=$url\" class=\"btn btn-lg btn-primary\"><i class=\"fas fa-sign-in-alt fa-lg\"></i><br/>Login</a>
			</div>
			</div>
			<div id=\"pptc\" class=\"navbar navbar-default navbar-fixed-bottom\">
				<div class=\"container\">
					<a href=\"#PRIVACY\" data-toggle=\"modal\">Privacy Policy, Terms and Conditions</a>
				</div>
			</div>";
}
else {
	print "	<div id=\"menu\" class=\"row\" style=\"border:0px\">
			<div class=\"col-lg-6 menu_button\"><br/>
			<a onclick=\"showLocalIDS('local');\" class=\"btn btn-lg btn-block btn-primary\"><i class=\"fas fa-plane-departure fa-lg\"></i><br/>Tower<br/>IDS</a><br/>
			<a onclick=\"showLocalIDS('a80');\" class=\"btn btn-lg btn-block btn-primary\"><i class=\"fas fa-layer-group fa-lg\"></i><br/>A80 Atlanta<br/>Large TRACON IDS</a><br/>";
	if (is_sysad($vatsim_cid,$artcc_staff,$sso_endpoint)) { print "<a onclick=\"modAccessList('black','fetch'); modAccessList('white','fetch');\" href=\"#ADMIN\" class=\"btn btn-lg btn-block btn-primary\" data-toggle=\"modal\" data-bs-toggle=\"modal\"><i class=\"fas fa-user-tie fa-lg\"></i><br/>System<br/>Administration</a><br/>"; }
	print "	</div>
			<div class=\"col-lg-6 menu_button\"><br/>
			<a onclick=\"launchMulti();\" class=\"btn btn-lg btn-block btn-primary\"><i class=\"fas fa-compress-arrows-alt fa-lg\"></i><br/>Multi-Airfield<br/>IDS</a><br/>";
	if (TRAFFIC_MANAGEMENT) {
		print "	<a href=\"traffic.php\" target=\"_blank\" class=\"btn btn-lg btn-block btn-primary\"><i class=\"fas fa-traffic-light fa-lg\"></i><br/>Traffic Management<br/>Tools</a><br/>";
	}
	print " <a onclick=\"showAboutHelp();\" class=\"btn btn-lg btn-block btn-primary\"><i class=\"far fa-life-ring fa-lg\"></i><br/>Help<br/>& About</a><br/>
			<br/>
			</div>
			</div>";
} 
?>
			</div>
		</div>
	</div>
	
<?php 
// Contains definitions for all of the modal boxes
include "modal.php";
?>

</div>
<?php
if(DEBUG) {
	print "<div id=\"json_test_dump\"></div>";
}
?>
	</body>
</html>