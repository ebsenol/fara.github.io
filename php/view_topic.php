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
		$username = -1;
	}
	else{
		$username = $_SESSION['username'];
	}
	$topic = $_GET["topic"];
	$_SESSION['topic'] = $topic; 
	$sql =  "SELECT category_name " .
			"FROM Category_Topic  ".
			"WHERE topic_name = '".$topic."'";
		
	$result = mysqli_query($db, $sql);	
	$category =implode(" ",mysqli_fetch_assoc($result));

	// used to indicate view that user chose
	$view ="all";
	if (isset($_GET['view']))
		$view = $_GET['view'];
	// used to indicate which page is the user on
	$page =1;
	if (isset($_GET['page']))
		$page = $_GET['page'];
	// used to indicate how many page user chooses to see
	$pageview = 10;
	if (isset($_GET['pageview']))
		$pageview = $_GET['pageview'];

	$limitbegin = ($page - 1) * $pageview;
	
	$sql =  "SELECT * FROM homepage_view WHERE belongs = '".$topic."' ";
	if ($view == "week")
		$sql =  "SELECT * FROM homepage_view WHERE timestamp > now() - INTERVAL 1 WEEK AND belongs = '".$topic."' ";
	else if ($view == "today")
		$sql =  "SELECT * FROM homepage_view WHERE timestamp > now() - INTERVAL 1 DAY AND belongs = '".$topic."' ";

	$countersql =  "SELECT count(*) as count FROM ".
					"( ".$sql.") as C;";

	// add page constraints in the query:
	$sql = "".$sql." LIMIT ".$limitbegin.",".$pageview." ;";
	$result = mysqli_query($db, $countersql);
	$res_arr =  mysqli_fetch_array($result);
	$totalPostCount = $res_arr['count'];

	$result = mysqli_query($db, $sql);
	$res_array = array();
	if( $result->num_rows > 0)
		while($row = mysqli_fetch_array($result))
			array_push($res_array, $row);
	echo "<h2 align='center'><b>".$category."/".$topic.": </b></h2>";
	echo "</br>";
	echo "<table class='table table-striped' style='width:95%'; align = 'center';  align='center' cellpadding='10'>";
	echo "<thead class='thead-inverse'>";
	echo 	"<th style='padding: 10px' > Votes</th>".
			"<th style='padding: 10px' > Title</th>".
			"<th style='padding: 10px'> Date </th>".
			"<th style='padding: 10px'> Category</th>".
			"<th style='padding: 10px'> Topic</th>".
			"<th style='padding: 10px'> User</th>".
		 "</tr>".
		 "</thead>";
	echo "<tbody>";
	$voteIdCount = 0;
	echo "<a style='margin-left: 200px;'href='view_topic.php?topic=".$topic."&view=today&pageview=".$pageview."&page=".$page."'>Top posts of today</a>"; 
	echo "<a style='margin-left: 200px;'href='view_topic.php?topic=".$topic."&view=week&pageview=".$pageview."&page=".$page."'>Top posts of all week</a>"; 
	echo "<a style='margin-left: 200px;'href='view_topic.php?topic=".$topic."&view=all&pageview=".$pageview."&page=".$page."'>Top posts of all</a>"; 


	$from = "view_topic.php?topic=".$topic."";
	foreach($res_array as $req)
	{
		echo "<tr>";
		$voteIdCount++;
		$currentContentID = $req['cont_id'];
		$sql =  "SELECT net_vote ".
					"FROM Content  ".
					"WHERE cont_id = " .$currentContentID. "; ";
		
		$result = mysqli_query($db, $sql);
		$res_arr =  mysqli_fetch_array($result);
		$voteCount = $res_arr['net_vote'];
		$upCountFromUser = 0;
		$downCountFromUser = 0;
		$sql2 = "SELECT ". 
				"(SELECT count(*) " .
				"FROM vote  ".
				"WHERE vote = true AND username = '".$username."' AND cont_id = ".$currentContentID." ) AS up;";
		$result2 = mysqli_query($db, $sql2);
		if ($result2){
			$res_arr2 =  mysqli_fetch_array($result2);
			$upCountFromUser = $res_arr2['up'];
		}
		$sql3 =  "SELECT (". 
				"SELECT count(*) AS down " .
				"FROM vote  ".
				"WHERE vote = false AND username = '".$username."' AND cont_id = ".$currentContentID." ) AS down;";
		$result3 = mysqli_query($db, $sql3);
		if ($result3){
			$res_arr3 =  mysqli_fetch_array($result3);
			$downCountFromUser = $res_arr3['down'];
		}
      	echo "<td  width='10%' style='padding:0px''> ".
  			"<div id='vote".$voteIdCount."' class = 'upvote upvote-programmers' > ";
  			if ($upCountFromUser > 0)
  				echo "<a class='upvote upvote-on' href='add_vote.php?username=". $username ."&contid=".$currentContentID."&vote=neutral&from=".$from."'b></a> ";
  			else{
  				if ($downCountFromUser > 0 )
  					echo "<a class='upvote' href='add_vote.php?username=". $username ."&contid=".$currentContentID."&vote=downtoup&from=".$from."'></a> ";
  				else
  					echo "<a class='upvote' href='add_vote.php?username=". $username ."&contid=".$currentContentID."&vote=up&from=".$from."'></a> ";
  			}
  			
  		echo "<span class='count'>".$voteCount."</span> ";
  			
  			if ($downCountFromUser > 0 )
  				echo "<a class='downvote downvote-on' href='add_vote.php?username=". $username ."&contid=".$currentContentID."&vote=neutral&from=".$from."'></a> ";
  			else
  				if ($upCountFromUser > 0)
					echo "<a class='downvote' href='add_vote.php?username=". $username ."&contid=".$currentContentID."&vote=uptodown&from=".$from."'></a> ";
  		
  				else
  					echo "<a class='downvote' href='add_vote.php?username=". $username ."&contid=".$currentContentID."&vote=down&from=".$from."'></a> ";
  		echo "</div>";
		//activate vote button
		//echo "<script type='text/javascript'> $('#vote".$voteIdCount."').upvote(); </script>"; 	
		if ($req['post_type']=='link'){
			echo "<td  width='60%'  style='padding: 10px'>".
			"<a href='https://". $req['content'] ."'>" .$req['post_title']. " </a>";
			echo "<a href='viewcontent.php?id=". $currentContentID ."'><b><i>comments</i></b> </a>";	
		}	
		else{
			echo "<td  width='60%'  style='padding: 10px'>".
			"<a href='viewcontent.php?id=". $currentContentID ."'>" .$req['post_title']. " </a>;";	
		}
		echo "</td>";
		echo "<td  width='6%' align = 'center' style='padding: 10px'>". ($req['timestamp']) . "</td>";
		echo "<td  width='8%' align = 'center' style='padding: 10px'>".
		"<a href='view_category.php?category=". $req['category_name'] ."'>". ($req['category_name']) . "</td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'>".
		"<a href='view_topic.php?topic=". $req['belongs'] ."'>". ($req['belongs']) . "</td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'>". ($req['username']) . "</td>";
		echo "</tr>";
	}
	echo"</tbody>";
	echo '</table></p></br></br>';


	echo "<a style='margin-left: 100px;".
		"'href='view_topic.php?topic=".$topic."&view=".$view."&page=1&pageview=10'>Show 10 per page</a>"; 
	echo "<a style='margin-left: 100px;".
		"'href='view_topic.php?topic=".$topic."&view=".$view."&page=1&pageview=25'>Show 25 per page</a>"; 
	echo "<a style='margin-left: 100px;".
		"'href='view_topic.php?topic=".$topic."&view=".$view."&page=1&pageview=50'>Show 50 per page</a>"; 
	echo "<br><br/>\n";

	$pageCounter = 1;
	$pageMax = $totalPostCount / $pageview + 1;
	echo "<h5 style='margin-left: 100px;'><b> Pages: </b></h5>";

	while ($pageCounter < $pageMax){
		echo "<a style='margin-left: 100px;'href='view_topic.php?topic=".$topic."".
				"&view=".$view."&page=".$pageCounter."&pageview=".$pageCounter."'>".$pageCounter."</a>"; 
		$pageCounter++;
	}
	echo "<br><br/>\n";

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
	<title>reditula</title>

</head>
<body style="padding-top: 65px;">
  <!-- Initialize vote buttons -->
	<?php 
		if ($usermode == 1 && strlen($username) > 0){
			$counter = 0;
			while ($counter <= $voteIdCount){
				$counter++;
				echo "<script type='text/javascript'> $('#vote".$counter."').upvote();</script>";
			}	
		}
	?>
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
								echo "<li><a href='view_category.php?category=".$catName."'>". ($catName) . "</a></li>";
         					}
						 ?>
						</ul>
				</li>
	     	</ul>
		     <ul class="nav navbar-nav navbar-right">

				<li>
					<form class="navbar-form navbar-left" role="search">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Search">
					</div>
					<button type="submit" class="btn btn-default">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					</form>
				</li>
				<li> <p class="navbar-text"> <?php if ($usermode == 1 && strlen($username) > 0) echo "Logged in as ".$username.""; else echo "Guest"; ?>  </p></li>
				<?php if ($usermode == 1 && strlen($username) > 0) echo "<li><a href='logout.php'>Log out</a></li>"; else echo "<li><a href='login.php'>Log in</a></li>"; ?>
			

		     </ul>
			</div>
		</div>
	</nav>


	<?php
	 if ($usermode == 1 && strlen($username) > 0)
		echo "<div class='container'> ".
		  "<h3>Post</h3> ".
		  "<a href='postapost.php?category=".$category."&topic=".$topic."' style='margin-right: 30px' class='btn btn-info' role='button'>Text post</a> " .
		  "<a href='postalink.php?category=".$category."&topic=".$topic."' style='margin-right: 30px' class='btn btn-info' role='button'>Link post</a> " .
		"</div>";
		echo "<br><br/>\n";echo "<br><br/>\n";echo "<br><br/>\n";

	?>

			
	


		</body>
</html>
<?php ob_end_flush(); ?>