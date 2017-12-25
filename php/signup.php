<?php
include_once 'dbconnect.php';
session_start();
unset($_SESSION['user']);
ob_start();

$error = false;
if( isset($_POST['btn-signup']) ) { 
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$username = mysqli_real_escape_string($db,$_POST['username']);
	$password = mysqli_real_escape_string($db,$_POST['password']);
	
	if (!$error){
		$res=mysqli_query($db, "SELECT * FROM User WHERE email_address = '$email'");
		echo $count = mysqli_num_rows($res);
		if($count !=0){
			$errMSG = "Account already exists!";
		}
		else{
			//insert user to the database
			$res = mysqli_query($db, "Insert INTO User Values ('$username' , '$password', '$email', now() );");
			$id = mysqli_insert_id($db);
			header("location: login.php");
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

	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	
	<title>Signup</title>
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body style="padding-top: 65px;">


	<nav class="navbar navbar-inverse navbar-fixed-top">
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
        </ul>
		</div>
	</nav>



	<!--Sign up form-->
	<div class="container col-md-4 col-md-offset-5">
		<h3 style="margin-left:25px;" ><strong> Join now!  </strong></h3><br>
		<div class="container col-md-7">
			<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
				<div class="form-group">
					<label class="sr-only" for="email">Email address</label>
					<input type="email" class="form-control" name = "email" id="email" placeholder="Email" required="true">
				</div>
				<div class="form-group">
					<label class="sr-only" for="username">Username</label>
					<input type="text" class="form-control" name = "username" id="username" placeholder="Username" required="true">
				</div>
	    
				<div class="form-group">
					<div class="form-group">
						<label class="sr-only" for="password">Password</label>
						<input type="password" class="form-control" name="password" id="password" placeholder="Password" required="true">
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary center-block"  name="btn-signup" if($errMSG) {onclick="window.location.href = 'login.php'" }>Sign up</button>
					</div>
				</form>

				<!-- Error Handling HTML -->
       <div class = "row">

        <?php
        if ( isset($errMSG) ) {
            ?>
            <div class="form-group">
               <div class="alert alert-danger">
                <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
            </div>
        </div>
        <?php
    }
    ?>
        </div>
				<!--log in-->
				<h4 style="margin-left: 5px;" <a href="login.php">  Already a User? </a> </strong></h4><br>
				<div>
					<button class="btn btn-primary center-block" onclick="window.location.href = 'login.php' ">Log in</button>
				</div>	
			</div>


		</div>
</body>
</html>
<?php ob_end_flush(); ?>