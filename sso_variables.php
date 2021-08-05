<?php
// SSO Variables for vIDS
// ** DO NOT UPLOAD TO GITHUB **

$sso_variables = array();

// [0] For use on Kyle's local XAMPP
$client_id = "153";
$client_secret = "YChl2khdeVii0D9nTdJTmFxApHiSkZGMwHxsmBFY";
$redirect_uri = "http://127.0.0.1/ids";
$sso_endpoint = "https://auth-dev.vatsim.net";
$sso_variables[] = array($client_id,$client_secret,$redirect_uri,$sso_endpoint);
// [1] For use on Kyle's server
$client_id = "175";
$client_secret = "ixw9I1haGipndSO2Q4Qwh7nBuCQKog2XcI5uRjjw";
$redirect_uri = "https://kplink.net/ids";
$sso_endpoint = "https://auth-dev.vatsim.net";
$sso_variables[] = array($client_id,$client_secret,$redirect_uri,$sso_endpoint);
// [2] For use on live ZTL ARTCC server 
$client_id = "840";
$client_secret = "xbs6t8Kvoq0eT1vJcY8ex1uS0GgS9QopH3u5Zhy9";
$redirect_uri = "https://www.ztlartcc.org/ids";
$sso_endpoint = "https://auth.vatsim.net";
$sso_variables[] = array($client_id,$client_secret,$redirect_uri,$sso_endpoint);
?>