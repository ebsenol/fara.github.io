<?php
	include_once 'dbconnect.php';
	session_start();
	ob_start();
	$username = $_GET["username"];
	$contid = $_GET["contid"];
	$vote = $_GET["vote"];
	$from = $_GET["from"];
	if ( !isset($_SESSION['username'])){
		$username =$_SESSION['username'];	

	}
	if ($vote == "up"){
		$sql = "INSERT INTO Vote VALUES ('".$username."',".$contid.", True);";
	}
	else if( $vote == "down"){
		$sql = "INSERT INTO Vote VALUES ('".$username."',".$contid.", False);";

	}
	else if($vote == "neutral"){
		$sql = "DELETE FROM Vote WHERE username ='".$username."' AND cont_id= ".$contid.";";

	}
	else if($vote == "uptodown"){
		$sql = "UPDATE Vote SET vote = False  WHERE username ='".$username."' AND cont_id= ".$contid.";";
	}
	else if($vote == "downtoup"){
		
		$sql = "UPDATE Vote SET vote = True  WHERE username ='".$username."' AND cont_id= ".$contid.";";
	}

	$result = mysqli_query($db, $sql);
	$_SESSION['username'] = $username; //start session

	goBack($from);

	function goBack( $direction)
	{
		echo "<script type='text/javascript'>";
		echo 'window.location.href="' .$direction. '";';
		echo "</script>";
		die;
	}

?>