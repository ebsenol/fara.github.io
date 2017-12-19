<?php
	include_once 'dbconnect.php';
	session_start();
	ob_start();
	
	$username = $_POST["username"];

	$old_email = $_POST["old-email"];
	$new_email = $_POST["new-email"];

	$old_password = $_POST["old-password"];
	$new_password = $_POST["old-password"];

	$sql =  "SELECT * " .
			"FROM User as U ".
			"WHERE U.username = '".$username."' AND U.email_address = '".$email."' AND U.password = '".$old_password."' ;";
	
	$result = mysqli_query($db, $sql);
	$res_array = array();

	if( $result->num_rows > 0)
		while($row = mysqli_fetch_array($result)){
			// array_push($res_array, $row);

			// UPDATE `User` SET `username`=[value-1],`password`=[value-2],`email_address`=[value-3],`joined_date`=[value-4] WHERE 1

			$sql =  "UPDATE User " .
					"SET password = '".$new_password."', email_address = '".$new_email."' ".
					"WHERE username = '".$username."' ;";
					
			
			$result = mysqli_query($db, $sql);
		}
	else
		echo "Information is not correct"; // TODO : add link script that goes back to profile.
?>
