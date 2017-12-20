<?php
	include_once 'dbconnect.php';
	session_start();
	ob_start();
	
	$username = $_GET["username"];

    $sql_user = "SELECT username ".
    			"FROM Admin ".
    			"WHERE username = '".$username."' ;";

    $result = mysqli_query($db, $sql_user);
	$res_array = array();	
	if( $result->num_rows == 1){
		while($row = mysqli_fetch_array($result))
			array_push($res_array, $row);
		foreach($res_array as $req)
		{	
			$sql =  "DELETE FROM Admin WHERE username = '".$username."' ;";

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
