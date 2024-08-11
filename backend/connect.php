<?php
session_start();
// define('ROOT_url', 'http://localhost/adviisory2/patient/');
define('DB_host', 'localhost');
define('DB_user', 'root');
define('DB_pass', '');
define('DB_name', 'billyz_college');

$conn = new mysqli(DB_host, DB_user, DB_pass, DB_name);

if(mysqli_errno($conn)){
	die(mysqli_error($conn));
}

?>