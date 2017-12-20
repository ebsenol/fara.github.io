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
	
	$temp = $_GET["category"];
	if (strlen($temp) > 0){
		$category = $temp;	
		$_SESSION['category'] = $category; 
	}
	$sql =  "SELECT * " .	
			"FROM Post AS P, Content AS C, Category_Topic AS CT  ".
			"WHERE P.cont_id = C.cont_id AND P.belongs = CT.topic_name AND CT.category_name = '".$category."'".
			"ORDER BY P.post_title 	".
			"LIMIT 10;";
	$result = mysqli_query($db, $sql);
	$res_array = array();
	if( $result->num_rows > 0)
		while($row = mysqli_fetch_array($result))
			array_push($res_array, $row);
	echo "<h2 align='center'><b> ".$category.": </b></h2>";
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
	$from = "view_category.php?category=".$category."";
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
  		if ($usermode == 1 && strlen($username) > 0){
  			if ($upCountFromUser > 0)
  				echo "<a class='upvote upvote-on' href='add_vote.php?username=". $username ."&contid=".$currentContentID."&vote=neutral&from=".$from."'b></a> ";
  			else{
  				if ($downCountFromUser > 0 )
  					echo "<a class='upvote' href='add_vote.php?username=". $username ."&contid=".$currentContentID."&vote=downtoup&from=".$from."'></a> ";
  				else
  					echo "<a class='upvote' href='add_vote.php?username=". $username ."&contid=".$currentContentID."&vote=up&from=".$from."'></a> ";
  			}
  		}
  		else{
  			echo "<a class='upvote'></a>"; 
  		}	
  			
  		echo "<span class='count'>".$voteCount."</span> ";
  		if ($usermode == 1 && strlen($username) > 0){
  			if ($downCountFromUser > 0 )
  				echo "<a class='downvote downvote-on' href='add_vote.php?username=". $username ."&contid=".$currentContentID."&vote=neutral&from=".$from."'></a> ";
  			else
  				if ($upCountFromUser > 0)
					echo "<a class='downvote' href='add_vote.php?username=". $username ."&contid=".$currentContentID."&vote=uptodown&from=".$from."'></a> ";
  		
  				else
  					echo "<a class='downvote' href='add_vote.php?username=". $username ."&contid=".$currentContentID."&vote=down&from=".$from."'></a> ";
  		}
  		else{
  			echo "<a class='downvote'></a>"; 
  		}	
  			
  		echo "</div>";
		//activate vote button
		//echo "<script type='text/javascript'> $('#vote".$voteIdCount."').upvote(); </script>"; 	
		echo "<td  width='60%'  style='padding: 10px'>".
		"<a href='viewcontent.php?id=". $currentContentID ."'>" .$req['post_title']. " </a></td>";	
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
	if( isset($_POST['btn-addtopic']) ) {
		$topic = $_POST['topic'];
		$category = $_SESSION['category'];
		$sql = "INSERT INTO topic VALUES ('".$topic."');";
		$res = mysqli_query($db,$sql);
		$sql = "INSERT INTO Category_Topic VALUES ('".$category."','".$topic."');";
		$res = mysqli_query($db,$sql);
		header("location: view_category.php?category=".$category."");
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
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Topics
						<span class="caret"></span></a>
						<ul class="dropdown-menu">
						 <?php
							 $sql =  "SELECT topic_name " .
									 "FROM Category_Topic ".
									 "WHERE category_name = '".$category."';";
				
							$result = mysqli_query($db, $sql);
							$res_array = array();
							if( $result->num_rows > 0)
								while($row = mysqli_fetch_array($result))
									array_push($res_array, $row);
								
							foreach($res_array as $req)
							{
								$topicName = $req['topic_name'];
								echo "<li><a href='view_topic.php?topic=".$topicName."'>". ($topicName) . "</a></li>";
         					}
						 ?>
						</ul>
				</li>
				  <!-- Initialize vote buttons -->
			<?php 
				if ($usermode == 1 && strlen($username) > 0)
				echo "<li><a data-toggle='modal' data-target='#addTopicModal'><span class='glyphicon'></span><b>+</b> Add Topic</a>";
			?>
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
				<li> <p class="navbar-text"> <?php if ($usermode == 1 && strlen($username) > 0) echo "Logged in as ".$username.""; else echo "Guest"; ?>  </p></li>
				<?php if ($usermode == 1 && strlen($username) > 0) echo "<li><a href='logout.php'>Log out</a></li>"; else echo "<li><a href='login.php'>Log in</a></li>"; ?>
				
		     </ul>
			</div>
		</div>
	</nav>

 	<div id="addTopicModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"> &times;</button>
                <h4>Add Topic</h4>
            </div>
            <div class="modal-body">
                    <form  class="form-group" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">

                       <label class="text" for="topic"></label><input type="text" class="form-control input-sm" placeholder="Topic" id="topic" name="topic">
                       <button type="submit" class="btn btn-info btn-xs" name="btn-addtopic">Add</button>
                       <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Cancel</button> 
                       </div>
               
                    </form>
            </div>
		 </div>
	 </div>
	

		
	


		</body>
</html>
<?php ob_end_flush(); ?>