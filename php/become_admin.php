<?php
	include_once 'dbconnect.php';
	session_start();
	ob_start();
	
	//set to 1 if user logged in, 0 if guest mode
	$usermode = 1;
	if ( !isset($_SESSION['username']))
	{
		//guest mode
		$usermode = 0;
		$username = "";
	}
	else{
		$username = $_SESSION['username'];	
	}
	
	function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
    	}
    	return $randomString;
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
   <nav id="navbarmain" style = 'background-color:#3F51B5' class="navbar navbar-inverse navbar-fixed-top">
       <div class="container">
         <div class="navbar-header">
           <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
             <span class="sr-only">Toggle navigation</span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
			</button>
          <a class="navbar-brand" href="homepage.php">Fara</a>
		 </div>

		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class ="active"><a href="homepage.php">Home</a></li>
				<li <input type="text" name="search" placeholder="Search.."> </li> 
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Categories
						<span class="caret"></span></a>
						<ul class="dropdown-menu">
						 <?php
							 $sql =  "SELECT name " .
									 "FROM Category;";
						
							$result = mysqli_query($db, $sql);
							$res_array = array();
							if( $result->num_rows > 0)
								while($row = mysqli_fetch_array($result))
									array_push($res_array, $row);
								
							foreach($res_array as $req)
							{
								$catName = $req['name'];
								echo "<li><a href='view_category.php?'>". ($catName) . "</a></li>";
							}
						 ?>
						</ul>
				</li>
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
				<li> <p class="navbar-text"> <?php if ($usermode == 1) echo "Logged in as ".$username.""; else echo "Guest"; ?>  </p></li>
				<li >
					<form action="view_user.php" class="navbar-form navbar-left" role="settings">
					<button role="settings" type="submit"  class="btn btn-default">
				          <span class="glyphicon glyphicon-cog"></span>
					</button>
					</form>
				</li>
				<li><a href="logout.php">Log out</a></li>

		     </ul>
		</div>
		</div>
	</nav>
		<div class="container">
		  <h3>Adminship request</h3>
		  <ul class="list-inline">

		    <?php
		    	// TODO: maybe change the interval here, to be more realistic, but can stay like this for DEMO
		    	$sql = "SELECT username, email_address FROM User WHERE joined_date <= (NOW() - INTERVAL 1 YEAR) AND username = '".$username."';";
			
				$result = mysqli_query($db, $sql);
				$res_array = array();
				$flag = false;
				if( $result->num_rows > 0){
					$flag = true;
					while($row = mysqli_fetch_array($result))
						array_push($res_array, $row);
				}
				else echo "Seems like you can't become admin yet!"; 
				if($flag == true){
					foreach($res_array as $req)
					{	
						$uname = $req['username'];
						$email = $req['email_address'];
						
						// -----------DISCLAIMER ----------------------
						// this only works on berker's laptop so far :D 
						// ---------------------------------------------
						$to = "figalitaho@gmail.com";
						$subject = "Hi!";
						$admin_token =  generateRandomString(8);
						$body = "Congrats on unlocking your admin token! \nYour generated code is " .$admin_token."";
						// echo $body;
						// uncomment to actually send body
						
						$headers = "From: figalitaho@gmail.com\r\n". "X-Mailer: php" . phpversion();
						if (mail($to, $subject, $body, $headers)) {
							echo("<p>Email successfully sent!</p>");
						} else {
							echo("<p>Email delivery failed…</p>");
						}

						// STUB:
						// $to = "figalitaho@gmail.com";
					 //    $subject = "Hi!";
					 //    $body = "Hi,\n\nHow are you?";
					 //    $headers = "From: figalitaho@gmail.com\r\n". "X-Mailer: php" . phpversion();
					 //    if (mail($to, $subject, $body, $headers)) {
					 //      echo("<p>Email successfully sent!</p>");
					 //    } else {
					 //      echo("<p>Email delivery failed…</p>");
					 //    }
						
						echo "<form action='confirm_add_adminship.php?username=".$uname."&admin_token=".$admin_token."' method='POST' type='hidden' onsubmit=\"return isTokenValid('$admin_token')\">";

					}
				}
			?>

			<br>
			Enter token received in your email address: 
			<br>
			  <p>
			  <div class="form-group" method="post" >
				<input class="form-control" type="text" id="token_user" name="token_user">
			  </div>
			  </p>
			  <p>
			  <div class="form-group" method="post" >
				<button type="submit" name="button_add_admin" id="button_change_adminship" class="btn btn-primary" >Submit</button>
			  </div>
			  </p>
		  </ul>
		</div>
		
		<script>
			function isTokenValid(generated_token){
				var user_token = document.getElementById("token_user").value; 
				if(user_token != "" && generated_token != ""){
					if(user_token != generated_token){
						alert("Please recheck the entered code!");
						return false;
					}else{
						alert("Congrats!");
						return true;
					}
				}
			}
		</script>
		<script>
		function addCategory() {
			var person = prompt("Please enter the category you want to create:", "");
			if (person != null) {
				document.getElementById("demo").innerHTML =
				"Hello " + person + "! How are you today?";
			}
		}
		</script>
		<script>
			$('#vote1').upvote();
			$('#vote2').upvote();
			$('#vote3').upvote();
		</script>
		
	</body>
</html>
<?php ob_end_flush(); ?>