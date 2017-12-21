<?php
include_once 'dbconnect.php';
session_start();
ob_start();


$message_id = 0;

if(isset($_POST['dst_name'], $_POST['message'])){
	$errors = array();
		
	if(empty($_POST['dst_name'])){
		$errors[] ='Please enter the username of the person to whom you want to sent the message!';
	}else{
		$dst_name = trim($_POST['dst_name']);
		$res=mysqli_query($db, "SELECT * FROM User WHERE username = '$dst_name'"); 
		$count = mysqli_num_rows($res);
		if($count ==1){//exists
			//$_SESSION['username'] = $username; //start session
			//header("location: homepage.php");
			echo 'username exist';
		}else{//doesnt exists
			$errors[] ='Username you entered does not exist';
		}
	}
	if(empty($_POST['message'])){
		$errors[] ='Message body cannot be empty!';
	}
	if(empty($errors)){
		$rcv_name = $_SESSION['username'];	
		$dst_name = mysqli_real_escape_string($db,$_POST['dst_name']);
		$message = mysqli_real_escape_string($db,$_POST['message']);
		
		
		$sql = "INSERT INTO message VALUES(NULL, '".$rcv_name."', '".$dst_name."', now(), '".$message."');";
		
		echo $sql;
		$res = mysqli_query($db, $sql);
		$id = mysqli_insert_id($db);
		
		
		echo'inside empty errors';
		echo $rcv_name;
		echo $dst_name;
		echo $message_id;
		echo $message;

	}
}
if(isset($errors)){
	if(empty ($errors)){
		echo '<div class="msg success">Your message has been send!';
	}else{
		foreach($errors as $error){
			echo '<div class="msg error">', $error, '</div>';
		}
	}

}
	
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<link rel="stylesheet" href="/css/upvote/jquery.upvote.css" type="text/css" media="screen">
	<script type="text/javascript" src="/css/upvote/jquery.upvote.js"></script>

	<script type="text/javascript" src="/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
	<title>Fara</title>

</head>
<body style="padding-top: 65px;">
   
   <!-- Fixed navbar -->
   <nav id="navbarmain"  class="navbar navbar-inverse navbar-fixed-top">
       <div class="container">
         <div class="navbar-header">
           <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
             <span class="sr-only">Toggle navigation</span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
			</button>
          <a class="navbar-brand" href="index.php">Fara</a>
		 </div>

		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class ="active"><a href="homepage.php">Home</a></li>
				<li <input type="text" name="search" placeholder="Search.."> </li> 

	     	</ul>
		     <ul class="nav navbar-nav navbar-right">
				<li
					<form class="navbar-form navbar-left" role="search">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Search">
					</div>
					<button type="submit" class="btn btn-default">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					</form>
				</li>
				<li><a href="logout.php">Log out</a></li>

		     </ul>
			 
		</div>
		</div>
		
	</nav>
	

			<div class="container ">
    <div class = "row">
	
	
<form action ="" method="post">
	<div>
		<label for="dst_name,">To</label>
		<br>
		<input type="text" name="dst_name" id= "dst_name"/>
	</div>
 <!--  	<div>
		<label for="subject">Subject</label>
		<br>
		<input type="text" name="subject" id= "subject"/>
	
	</div>	-->
	<div>
		<br>
		<textarea name="message" id = "message_id" rows="15" cols= "80"></textarea>
	</div>	
	<div>
		<input type="submit" value="Send"/>
	</div>	
	
</form>

 
	
		
	</body>
</html>
<?php ob_end_flush(); ?>	