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
		$username = -1;
	}
	else{
		$username = $_SESSION['user'];
		
	}
	
	$sql =  "SELECT * " .
			"FROM Post AS P, Content AS C, Category_Topic AS CT  ".
			"WHERE P.cont_id = C.cont_id AND P.belongs = CT.topic_name ".
			"ORDER BY P.post_title 	".
			"LIMIT 10;";
	
	$result = mysqli_query($db, $sql);
	$res_array = array();

	if( $result->num_rows > 0)
		while($row = mysqli_fetch_array($result))
			array_push($res_array, $row);

	echo "<h2 align='center'><b> Most recent posts: </b></h2>";
	echo "</br>";
	echo "<table class='table table-striped' style='width:95%'; align = 'center';  align='center' cellpadding='10'>";
	echo "<thead class='thead-inverse'>";
	echo 	"<th style='padding: 10px' > Title</th>".
			"<th style='padding: 10px'> Date </th>".
			"<th style='padding: 10px'> Category</th>".
			"<th style='padding: 10px'> Topic</th>".
			"<th style='padding: 10px'> User</th>".

		 "</tr>".
		 "</thead>";
	echo "<tbody>";
	foreach($res_array as $req)
	{
		echo "<tr>";
		echo "<td  width='70%' style='padding: 10px'>".
		"<a href='viewcontent.php?id=". $req['cont_id'] ."'>" .$req['post_title']. " </a></td>";
		
				//	"<a href='profile.php?account_id=".$req['account_ID']. "'>" .$req['name']. " " .$req['surname']. "</a></td>";
					
					
		echo "<td  width='6%' align = 'center' style='padding: 10px'>". ($req['timestamp']) . "</td>";
		echo "<td  width='8%' align = 'center' style='padding: 10px'>". ($req['category_name']) . "</td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'>". ($req['belongs']) . "</td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'>". ($req['username']) . "</td>";
		echo "</tr>";
	}
	echo"</tbody>";
	echo '</table></p></br></br>';

?>
	
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
	<title>CnC</title>

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
         <a class="navbar-brand" href="index.php">reditulla</a>
		</div>

		<div id="navbar" class="navbar-collapse collapse">
		<ul class="nav navbar-nav">
			<li class ="active"><a href="index.php">Home</a></li>
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
							echo "<li><a href='#'>". ($req['name']) . "</a></li>";

						}
					 ?>
        </ul>
      </li>
	  <ul class="dropdown-menu">
    <li><a href="#">HTML</a></li>
    <li><a href="#">CSS</a></li>
    <li><a href="#">JavaScript</a></li>
  </ul>


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
		<li> <p class="navbar-text"> Logged in as <?php echo "berku" ?>,  </p></li>
		<li><a href="logout.php">Log out</a></li>

     </ul>
</div>
</div>
</nav>

	 <div id="demo1" class="upvote upvote-serverfault">
		<a class="upvote"></a>
		<span class="count">6</span>
		<a class="downvote"></a>
		</div> 
		
		<script> 
			$('#demo1').click(upvote({count: 5, upvoted: 1}));
		</script>
		
		</body>
</html>
<?php ob_end_flush(); ?>
