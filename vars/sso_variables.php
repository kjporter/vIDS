<?php
// SSO Variables for vIDS
// ** DO NOT UPLOAD TO GITHUB **

$sso_variables = array();

// [0] For use on Kyle's local XAMPP
$client_id = "219";
$client_secret = "Vid1KsU7HoFrDkupXXRvdAsWpf05LbhmYpDRUcKh";
$redirect_uri = "http://127.0.0.1/ids";
$sso_endpoint = "https://auth-dev.vatsim.net";
$sso_variables[] = array('client_id'=>$client_id,'client_secret'=>$client_secret,'redirect_uri'=>$redirect_uri,'sso_endpoint'=>$sso_endpoint);
// [1] For use on Kyle's server
$client_id = "175";
$client_secret = "ixw9I1haGipndSO2Q4Qwh7nBuCQKog2XcI5uRjjw";
$redirect_uri = "https://kplink.net/ids";
$sso_endpoint = "https://auth-dev.vatsim.net";
$sso_variables[] = array('client_id'=>$client_id,'client_secret'=>$client_secret,'redirect_uri'=>$redirect_uri,'sso_endpoint'=>$sso_endpoint);
// [2] For use on live ZTL ARTCC server 
$client_id = "857";
$client_secret = "1wa6l0py6ERX9HGbIOBypi8xh9duIDnMLEX5Ysij";
$redirect_uri = "https://ids.ztlartcc.org";
$sso_endpoint = "https://auth.vatsim.net";
$sso_variables[] = array('client_id'=>$client_id,'client_secret'=>$client_secret,'redirect_uri'=>$redirect_uri,'sso_endpoint'=>$sso_endpoint);
?>