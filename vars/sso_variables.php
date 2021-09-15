<?php
// SSO Variables for vIDS
// This is an example of how to load the sso_variables array. You'll need to register and use your own client ids, client secrets, redirects, and endpoints.

$sso_variables = array();

// [0] For use on AAA's local XAMPP
$client_id = "aaa";
$client_secret = "aaa";
$redirect_uri = "http://aaa";
$sso_endpoint = "https://auth-dev.vatsim.net";
$sso_variables[] = array('client_id'=>$client_id,'client_secret'=>$client_secret,'redirect_uri'=>$redirect_uri,'sso_endpoint'=>$sso_endpoint);
// [1] For use on BBB's server
$client_id = "bbb";
$client_secret = "bbb";
$redirect_uri = "https://bbb";
$sso_endpoint = "https://auth-dev.vatsim.net";
$sso_variables[] = array('client_id'=>$client_id,'client_secret'=>$client_secret,'redirect_uri'=>$redirect_uri,'sso_endpoint'=>$sso_endpoint);
// [2] For use on live CCC ARTCC server 
$client_id = "ccc";
$client_secret = "ccc";
$redirect_uri = "https://ccc";
$sso_endpoint = "https://auth.vatsim.net";
$sso_variables[] = array('client_id'=>$client_id,'client_secret'=>$client_secret,'redirect_uri'=>$redirect_uri,'sso_endpoint'=>$sso_endpoint);
?>