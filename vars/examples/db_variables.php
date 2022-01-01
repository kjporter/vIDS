<?php
// DB Variables for vIDS
// This is an example of how to load the sso_variables array. You'll need to register and use your own client ids, client secrets, redirects, and endpoints.

$db_variables = array();

// [0] For use on AAA's local XAMPP
$server_host = "localhost";
$username = "user";
$password = "pass";
$database_name = "db";
$site_host = "http://127.0.0.1/ids";
$db_variables[] = array('server_host'=>$server_host,'username'=>$username,'password'=>$password,'database_name'=>$database_name,'site_host'=>$site_host);
// [1] For use on BBB's server
$server_host = "localhost";
$username = "user";
$password = "pass";
$database_name = "db";
$site_host = "https://example.net/ids";
$db_variables[] = array('server_host'=>$server_host,'username'=>$username,'password'=>$password,'database_name'=>$database_name,'site_host'=>$site_host);
// [2] For use on live CCC ARTCC server 
$server_host = "";
$username = "";
$password = "";
$database_name = "db";
$site_host = "https://ids.example.org";
$db_variables[] = array('server_host'=>$server_host,'username'=>$username,'password'=>$password,'database_name'=>$database_name,'site_host'=>$site_host);
?>