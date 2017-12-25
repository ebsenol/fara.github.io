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
		  <a class="navbar-brand" href="homepage.php">Fara</a>
		 </div>

		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class ="active"><a href="homepage.php">Home</a></li>
				<li <input type="text" name="search" placeholder="Search.."> </li> 
				</ul>
			 <ul class="nav navbar-nav navbar-right">
					<li>

					<form class="navbar-form navbar-left" role="search" action = 'search.php'>
					<div class="form-group">
						<input type="text" class="form-control" name = "Search" placeholder="Search">
					</div>
					<button type="submit" class="btn btn-default" action =>
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
		  <h3>Profile</h3>
		  <ul class="list-inline">

			
			<?php
				$sql =  "SELECT email_address, password " .
						"FROM User as U ".
						"WHERE U.username= '" . $username . "' ;";
				$result = mysqli_query($db, $sql);
				$res_array = array();
				if( $result->num_rows > 0)
					while($row = mysqli_fetch_array($result))
						array_push($res_array, $row);
				foreach($res_array as $req)
				{
					$email = $req['email_address'];
					$pw = $req['password'];
					echo "<form action='update_info.php?username=".$username."&email_address=".$email."&password=".$pw."' method='POST' type='hidden' onsubmit=\"return isValid('$email', '$pw')\">";
				}
			?>
				Change password: <br>
				<div class="form-group" method="post" >
					<label for="usr">old password</label>
					<input type="text" name="old_password" id="old_password" class="form-control" >
			  	</div>
			  	<div class="form-group" method="post" >
					<label for="usr">new password</label>
					<input type="text" name="new_password" id="new_password" class="form-control" >
			  	</div>

			  	Change email: <br>
				<div class="form-group" method="post">
					<label for="pwd">old email</label>
					<input type="text" name="old_email" id="old_email" class="form-control" >
			  	</div>
			  	<div class="form-group" method="post">
					<label for="pwd">new email</label>
					<input type="text" name="new_email" id="new_email" class="form-control" >
			  	</div>

				<div class="container"> 
			  		<input id="Submit" value="Submit" type="submit" style="margin-right: 30px">                
				</div>
			</form>

		  </ul>
		</div>
	
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
			function isValid(email, pw){
				var old_email = document.getElementById("old_email").value;
				var old_password = document.getElementById("old_password").value;
				var new_email = document.getElementById("new_email").value;
				var new_password = document.getElementById("new_password").value;
				// only changes email 
				if((old_email != "" && old_email != email) || (old_password != "" && old_password!= pw)){
					alert("Your old email or password is incorrect!");
					return false;
				}
				if(email == old_email && old_password == ""){
					if(new_email == ""){
						alert("You should add a new email");
						return false;
					}else{
						if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(new_email)){
							return true;
						}else{
							alert("You have entered an invalid email address!");
							return false;	
						}
					}
				}
				// only changes password
				else if(old_email == "" && old_password == pw){
					if(new_password == ""){
						alert("You should add a new password!");
						return false;
					}else{
						return true;
					}
				}
				// changes both 
				else if(email == old_email && pw == old_password && (new_email == "" || new_password == "")){
					return true;
				}else{
					alert("Fill in your new email and password!");
					return false;
				}
			}
		</script>

	</body>
</html>
<?php ob_end_flush(); ?>