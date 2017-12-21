<?php
	include_once 'dbconnect.php';
	session_start();
	ob_start();
	$click = false;
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
				<li><a href="logout.php">Log out</a></li>

		     </ul>
			 
		</div>
		</div>
		
	</nav>
	
	
	
	


	
	
	
	
		<div class="container">
		  <h3>Profile</h3>
		  <ul class="list-inline">

		    <!-- <a href='viewcontent.php?id=". $req['cont_id'] ."'>" .$req['post_title']. " </a> -->
		    <!-- deafult is user profile -->
			
		    <?php
				echo "<li><a href='view_user_posts.php?username=".$username."'>posts</a></li> ";
				echo "<li><a href='view_user_comments.php?username=".$username."'>comments</a></li> ";
				echo "<li><a href='view_user_votes.php?username=".$username."'>votes</a></li> ";
 				echo "<br>";
 				/*
			    <li><a href="view_user_posts.php">posts</a></li>
			    <li><a href="view_user_comments.php">comments</a></li>
			    <li><a href="view_user_votes.php">votes</a></li>
			    <li><a href="#">subscribed_topics_maybe</a></li>
			    <br>*/
		    	$sql =  "SELECT username, email_address " .
						 "FROM User " .
						 "WHERE username = '".$username."';";
			
				$result = mysqli_query($db, $sql);
				$res_array = array();
				if( $result->num_rows > 0)
					while($row = mysqli_fetch_array($result))
						array_push($res_array, $row);

			?>
		  </ul>
		</div>
	
	
		<div class="container ">
    <div class = "row">
     
                <div class="form-group">
                    
					<button type="submit" class="btn btn-primary "  name="btn-login" }>Follow</button>
			
				</div>
                <div class="form-group">
					<button type="submit" class="btn btn-primary" name="btn-login" onclick="window.location.href = 'message.php'" }>Send Message</button
				</div>
			
            </form>
        </div>
    </div>
	

	</body>
</html>
<?php ob_end_flush(); ?>