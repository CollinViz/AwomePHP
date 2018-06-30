<?php
#TYPE_OS = WIN LINUX
$TYPE_OS='WIN';
$PATH_DM =";";$PATH_FD="\\\\";if($TYPE_OS=='WIN'){	$PATH_DM =";";	$PATH_FD="\\\\";}else{	$PATH_DM =":";	$PATH_FD="//";}
session_start();
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

	exit(0);
}
$PAGE_NAME=""; 
 
$hostname_conEber = "127.0.0.1";
$database_conEber = "awome";
$username_conEber = "awome";
$password_conEber = "awome";
 
$concustomercontrol = new mysqli($hostname_conEber, $username_conEber, $password_conEber, $database_conEber);
error_reporting(E_ALL);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}   

?>