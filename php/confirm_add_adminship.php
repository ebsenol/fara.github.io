<?php
	include_once 'dbconnect.php';
	session_start();
	ob_start();
	
	$username = $_GET["username"];
	$admin_token = $_GET["admin_token"];

	$sql =  "INSERT INTO Admin (username, password, email_address, joined_date, admin_token) " .
			"SELECT username, password, email_address, joined_date, '".$admin_token."' ".
			"FROM User ".
			"WHERE username = '".$username."' ;";
	
	$result = mysqli_query($db, $sql);
	$res_array = array();
	if( $result )
		echo "Suceess";

	else
		echo "There was an error"; // TODO : add link script that goes back to profile.
	
	header("Location: homepage.php"); /* Redirect browser */
	exit();
?>
