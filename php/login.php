<?php
include_once 'dbconnect.php';
session_start();
ob_start();

$error = false;
if( isset($_POST) & !empty($_POST) ){ 
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	//if password or username is left empty
	if (empty($password) or empty($username)){
		$error = true;
	}
	// if there's no error, continue to login
	if (!$error) {
		//check if pass and user name entered are correct
		$res=mysqli_query($db, "SELECT * FROM User WHERE username = '$username' AND password = '$password'"); 
		$count = mysqli_num_rows($res);
		if($count ==1){
			$_SESSION['username'] = $username; //start session
			header("location: homepage.php");
		}
		else{
			$errMSG = "Password or username you entered is incorrect! Please try again!";
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

    <title>Login</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body style="padding-top: 65px;">
   <!-- Fixed navbar -->
   <nav class="navbar navbar-inverse navbar-fixed-top">
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
        </ul>
     <ul class="nav navbar-nav navbar-right">
         <li  class="active" ><a href="signup.php">Sign up</a></li>

     </ul>
 </div>
</div>
</nav>




<!--Log in Form-->
<div class="container col-md-4 col-md-offset-5">
    <div class = "row">
        <h2 style="margin-left:25px;"><strong>Login</strong></h2><br>
        <div class="container col-md-6">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
                <div class="form-group">
                    <label class="sr-only" for="username">Username</label>
                    <input type="username" class="form-control" name = "username" id="username" placeholder="username" required="true">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="password" required="true">
                </div>
                <div class="form-group">
                    
					<button type="submit" class="btn btn-primary center-block"  name="btn-login" }>Login</button>
			
				</div>
			
            </form>
        </div>
    </div>

	
	
<!-- Error Handling HTML -->
    <div class = "row">
       <div class = "container col-md-6">

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
</div>

</body>
</html>
<?php ob_end_flush(); ?>