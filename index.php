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
	
	include_once "sso_auth.php";
	
	define('DEBUG', false);
	//define('DEFAULT AIRFIELD', 'KATL'); 
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>vIDS</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="ids.css">
	<link rel="shortcut icon" href="img/favicon.ico" />
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<!-- TODO: Add support for bootstrap 4.x and remove bootstrap dependencies 3.X from project -->
	<!--
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous" media="screen">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
	-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="https://kit.fontawesome.com/9bd47a7738.js" crossorigin="anonymous"></script> <!-- used for glyph icons in tower IDS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- used for glyph icons in tower IDS -->
	<!-- This script was buggy, problematic, and not compatible with boostrap 4.X
 	<link href="css/bootstrap-modal-carousel.css" rel="stylesheet" /> 
	<script src="js/bootstrap-modal-carousel.js"/></script>
	-->
	<script src="ids.js"/></script>
</head>
<?php
	$imagesDir = 'img/bg/';
	$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
	$randomImage = $images[array_rand($images)];
?>
<body style="background-image: url('<?php echo $randomImage; ?>')" onload="refreshData(true);">  <!-- refreshData call initializes the display data -->
<input type="hidden" id="bgimg" value="<?php echo $randomImage; ?>" />
<div id="container">
	<div id="alerts" class="container fixed-top">  <!-- alert box displays authentication info -->
		<div class="row no_border" style="padding: 5px; visibility:hidden">
			<div id="alert" class="col <?php echo $alert_style; ?>">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<?php echo $alert_text; ?>
			</div>
		</div>
	</div>
	<form id="configForm" name="configForm" method="get" action="<?php //echo $localPath; ?>">
		<!--<input type="checkbox" id="live" name="live" /> Use live network data (if unchecked, an archived dataset is used ***testing only***)-->
	</form>
<?php
/*
if(!$valid_auth) {
	print "<div id=\"alerts\" class=\"container fixed-top\">  <!-- alert box displays authentication info -->
		<div class=\"row no_border\" style=\"visibility:hidden\">
			<div id=\"alert\" class=\"col $alert_style\">
				<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
					<span aria-hidden=\"true\">&times;</span>
				</button>
				$alert_text
			</div>
		</div>
	</div>";
}
*/
/*	<form id="configForm" name="configForm" method="get" action="<?php //echo $localPath; ?>">
		<!--<input type="checkbox" id="live" name="live" /> Use live network data (if unchecked, an archived dataset is used ***testing only***)-->
	</form>
*/
?>
<?php include "ids_grid_template.php"; ?>

	<!-- MULTI (TRACON/ARTCC) IDS DISPLAY -->
	<div id="multi_ids" class="container-fluid" style="display:none;">
		<div class="landing_menu">
		<div class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fas fa-bars"></i></a>
			<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dLabel">
				<li><a href="#" onclick="returnToLanding('multi_ids');">Return to menu</a></li>
				<li><a href="#" onclick="launchMulti();">Select template</a></li>
				<li><a href="#BUG" data-toggle="modal">Report a bug</a></li>
				<li><a href="#mHELP" data-toggle="modal">Help</a></li>
				<li class="dropdown-item disabled"><a href="#">Remove this template</a></li>
			</ul>
		</div>
		</div>
		<div id="multi_ids_data"></div> <!-- JS dumps the display data here -->
	</div>
	
	<!-- LANDING MENU -->
	<div id="landing_hdr" class="container-fluid">
	<div class="row" style="padding-left: 5px; border:0px;"><h3 contentEditable class="landing_header_text">ZTL vIDS - Virtual Information Display System</h3></div>
	<?php
	if($valid_auth) {
		print "<div class=\"row\" style=\"padding-left: 25px; border:0px;\"><h4 contentEditable class=\"landing_header_text\">Hello, $full_name ($user_rating)</h4></div>";
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
			<a href=\"$sso_endpoint/oauth/authorize?client_id=$client_id&redirect_uri=$url\" class=\"btn btn-lg btn-primary\"><i class=\"fas fa-sign-in-alt fa-lg\"></i><br/>Login</a><br/><br/>
			</div>
			</div>";
}
else {
	print "	<div id=\"menu\" class=\"row\" style=\"border:0px\">
			<div class=\"col-lg-6 menu_button\"><br/>
			<a onclick=\"showLocalIDS('local');\" class=\"btn btn-lg btn-block btn-primary\"><i class=\"fas fa-plane-departure fa-lg\"></i><br/>Tower<br/>IDS</a><br/>
			<a onclick=\"showLocalIDS('a80');\" class=\"btn btn-lg btn-block btn-primary\" data-toggle=\"modal\"><i class=\"fas fa-layer-group fa-lg\"></i><br/>A80 Atlanta<br/>Large TRACON IDS</a><br/><br/>
			</div>
			<div class=\"col-lg-6 menu_button\"><br/>
			<a onclick=\"launchMulti();\" class=\"btn btn-lg btn-block btn-primary\" data-toggle=\"modal\"><i class=\"fas fa-compress-arrows-alt fa-lg\"></i><br/>TRACON/ARTCC<br/>IDS</a><br/>
			<a onclick=\"showAboutHelp();\" class=\"btn btn-lg btn-block btn-primary\" data-toggle=\"modal\"><i class=\"far fa-life-ring fa-lg\"></i><br/>Help<br/>& About</a><br/><br/>
			</div>
			</div>";
}
?>
			</div>
		</div>
	</div>
	
<?php include "modal.php"; ?>

</div>
<?php
if(DEBUG) {
	print "<div id=\"json_test_dump\"></div>";
}
?>
	</body>
</html>