<?php
	/*
		vIDS (virtual Information Display System) for VATSIM
		
		Filename: bug_reporting.php
		Function: Adds a user reported bug to the bug reporting RSS feed
		Created: 6/10/21
		Edited: 
		
		Changes: 
	*/

date_default_timezone_set("UTC");
include_once "shared_functions.php";

$rss_file = "rss/bug_reports.xml";
$rss_url = fetch_my_url() . "rss";
$xmlstr = null;
if (file_exists($rss_file)) {
	$xmlstr = file_get_contents($rss_file);
}
$bug_report = new SimpleXMLElement($xmlstr);
$new_bug = $bug_report->channel->addChild('item');
$new_bug->addChild('title','vIDS Bug Reported: ' . date("m/d/y H:i:s"));
$new_bug->addChild('link',$rss_url);
$new_bug->addChild('description',$_GET['bug_description']);

file_put_contents($rss_file,$bug_report->asXML());