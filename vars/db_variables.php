<?php
// DB Variables for vIDS
// ** DO NOT UPLOAD TO GITHUB **

$db_variables = array();

// [0] For use on Kyle's local XAMPP
$server_host = "localhost";
$username = "vids-user";
$password = "JXQanksaT9rAhLJRh6bLkl6kkLgTom";
$database_name = "vIDS-ZTL";
$site_host = "http://127.0.0.1/ids";
$db_variables[] = array('server_host'=>$server_host,'username'=>$username,'password'=>$password,'database_name'=>$database_name,'site_host'=>$site_host);
// [1] For use on Kyle's server
$server_host = "localhost";
$username = "kjporter_vids";
$password = "4e+6PWX+pGTN";
$database_name = "kjporter_vIDS-ZTL";
$site_host = "https://kplink.net/ids";
$db_variables[] = array('server_host'=>$server_host,'username'=>$username,'password'=>$password,'database_name'=>$database_name,'site_host'=>$site_host);
// [2] For use on live ZTL ARTCC server 
$server_host = "";
$username = "";
$password = "";
$database_name = "vIDS-ZTL";
$site_host = "https://ids.ztlartcc.org";
$db_variables[] = array('server_host'=>$server_host,'username'=>$username,'password'=>$password,'database_name'=>$database_name,'site_host'=>$site_host);
?>