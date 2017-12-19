<?php
	include_once 'dbconnect.php';
	session_start();
	ob_start();
	
	$username = $_GET["username"];
	$email_address = $_GET["email_address"];
	$password = $_GET["password"];
	$old_email = $_POST["old_email"];
	$new_email = $_POST["new_email"];
	$old_password = $_POST["old_password"];
	$new_password = $_POST["new_password"];
	echo $old_password;
	echo $new_password;
	$sql =  "SELECT * " .
			"FROM User as U ".
			"WHERE U.username = '".$username."' AND U.email_address = '".$email_address."' AND U.password = '".$password."' ;";
	
	$result = mysqli_query($db, $sql);
	$res_array = array();
	if( $result->num_rows > 0)
		while($row = mysqli_fetch_array($result)){
			if (empty($old_email) && !empty($old_password)) {
				$sql =  "UPDATE User " .
					"SET password = '".$new_password."' ".
					"WHERE username = '".$username."' ;";
			}else if(empty($old_password) && !empty($old_email)) {
				$sql =  "UPDATE User " .
					"SET email_address = '".$new_email."' ".
					"WHERE username = '".$username."' ;";
			}
			else{
				$sql =  "UPDATE User " .
					"SET password = '".$new_password."', email_address = '".$new_email."' ".
					"WHERE username = '".$username."' ;";
			}	
			$result = mysqli_query($db, $sql);
			header("location: homepage.php");
		}
	else
		echo "Information is not correct"; // TODO : add link script that goes back to profile.
?>