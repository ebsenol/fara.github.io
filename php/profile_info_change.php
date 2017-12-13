	<?php
	include_once 'dbconnect.php';
	session_start();
	ob_start();
	
	//set to 1 if user logged in, 0 if guest mode
	$usermode = 1;

	if ( !isset($_SESSION['user']))
	{
		//guest mode
		$usermode = 0;
		$username = "";
	}
	else{
		$username = $_SESSION['user'];	
	}
	$username ="berku";
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
				<li onclick="addCategory()" class ="active"><a href="homepage.php"><b>+</b> Add Category</a></li>
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
					echo "<form action='update_info.php?username=".$username."&email=".$category."&topic=".$topic."' method='post' onsubmit='return isValid()'>";
				}
			?>
				Change password: <br>
				<div class="form-group" method="post" >
					<label for="usr">old password</label>
					<input type="text" id="old-password" class="form-control" >
			  	</div>
			  	<div class="form-group" method="post" >
					<label for="usr">new password</label>
					<input type="text" id="new-password" class="form-control" >
			  	</div>

			  	Change email: <br>
				<div class="form-group">
					<label for="pwd">old email</label>
					<input type="text" id="old-email" class="form-control" >
			  	</div>
			  	<div class="form-group">
					<label for="pwd">new email</label>
					<input type="text" id="new-email" class="form-control" >
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
			$('#vote1').upvote();
			$('#vote2').upvote();
			$('#vote3').upvote();

		</script>
		<script>
			function isValid(){
				var title = document.getElementById("title").value;
				var link = document.getElementById("link").value;
				return true;
				// if( title == "" && link == ""){
				// 	alert("Fill in the spaces!");
				// return false;
				// }  
				// else if( title == ""){
				// 	alert("Please enter a title!");
				// return false;
				// }
				// else if( url == ""){
				// 	alert("Please enter a url!");
				// return false;
				// }
				// return true;
			}
		</script>

	</body>
</html>
<?php ob_end_flush(); ?>
