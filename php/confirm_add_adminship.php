<?php
	include_once 'dbconnect.php';
	session_start();
	ob_start();
	
	$username = $_GET["username"];
	$admin_token = $_GET["admin_token"];


    $sql_user = "SELECT username, password, email_address, joined_date ".
    			"FROM User ".
    			"WHERE username = '".$username."' ;";

    $result = mysqli_query($db, $sql_user);
	$res_array = array();	
	if( $result->num_rows == 1){
		while($row = mysqli_fetch_array($result))
			array_push($res_array, $row);
		foreach($res_array as $req)
		{	
			$email = $req['email_address'];
			$pass = $req['password'];
			$joined_= $req['joined_date']; 
			$sql =  "CALL register_admin ('".$username."', '".$pass."', '".$email."', '".$joined_."', '".$admin_token."'); ";

			$result2 = mysqli_query($db, $sql);
			$res_array2 = array();
			if( $result2 )
				echo "Suceess";
			else
				echo "There was an error"; // TODO : add link script that goes back to profile.
			header("Location: homepage.php"); /* Redirect browser */
		}
	}
	exit();
?>
