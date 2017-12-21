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

	$sql_set_admin = "SELECT * FROM Admin WHERE username = '".$username."' ;";
	$result = mysqli_query($db, $sql_set_admin);
	if($result->num_rows == 1){
		$adminmis= 1;
	}else{
		$adminmis = 0;
	}

	$sql_set_moderator = "SELECT * FROM Moderator WHERE username = '".$username."' AND category_name = '".$category."' ;";
	$result = mysqli_query($db, $sql_set_moderator);
	if($result->num_rows == 1){
		$moderatormus = 1;
	}else{
		$moderatormus = 0;
	}
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

	$sql =  "SELECT * FROM homepage_view WHERE category_name = '".$category."' ";
	if ($view == "week")
		$sql =  "SELECT * FROM homepage_view WHERE timestamp > now() - INTERVAL 1 WEEK AND category_name = '".$category."'";
	else if ($view == "today")
		$sql =  "SELECT * FROM homepage_view WHERE timestamp > now() - INTERVAL 1 DAY AND category_name = '".$category."'";

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
	echo "<a style='margin-left: 200px;'href='view_category.php?category=".$category."&view=today&pageview=".$pageview."&page=".$page."'>Top posts of today</a>"; 
	echo "<a style='margin-left: 200px;'href='view_category.php?category=".$category."&view=week&pageview=".$pageview."&page=".$page."'>Top posts of all week</a>"; 
	echo "<a style='margin-left: 200px;'href='view_category.php?category=".$category."&view=all&pageview=".$pageview."&page=".$page."'>Top posts of all</a>"; 


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

		if ($req['post_type']=='link'){
			echo "<td  width='60%'  style='padding: 10px'>".
			"<a href='https://". $req['content'] ."'>" .$req['post_title']. " </a>";
			echo "<a href='viewcontent.php?id=". $currentContentID ."'>\t\t\t\tcomments </a>";	
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

	if( isset($_POST['btn-moderator-request']) ) {
		$category = $_SESSION['category'];

		// for the user, now check if he/she can be moderator
		// CAN BE A MODERATOR WHEN
		// has more than 3 likes (for now-demo purpose)
		$sql_check_votes = "SELECT SUM(net_vote) as sum_vote FROM Content WHERE username = '".$username."' ";
		$result = mysqli_query($db, $sql_check_votes);
		$row = mysqli_fetch_array($result);
		$sumvote = $row['sum_vote'];
		if($sumvote >= 1){
			// has more than 3 posts
			$sql_check_posts = "SELECT COUNT(*) as c FROM Content WHERE username = '".$username."' AND content_type='post' ;";
			$result = mysqli_query($db, $sql_check_posts);
			$row = mysqli_fetch_array($result);
			$c_stuff= $row['c'];
			
			if($c_stuff >= 1){
				$sqlulla = "SELECT email_address, password, joined_date FROM User WHERE username = '".$username."' ;";
				$r = mysqli_query($db, $sqlulla);
				$requlla = mysqli_fetch_array($r);

				$email = $requlla['email_address'];
				$pass = $requlla['password'];
				$joined_= $requlla['joined_date']; 
				// // register user as moderator
				$sql =  "CALL register_moderator ('".$username."', '".$pass."', '".$email."', '".$joined_."', '".$category."'); ";
				$result2 = mysqli_query($db, $sql);
				if( $result2 ){
					echo "Suceess";
					$_SESSION['moderator'] = 1;
				}
				else
					echo "There was an error"; // TODO : add link script that goes back to profile.
				header("Location: homepage.php"); /* Redirect browser */
			}
		}	
	}

	if( isset($_POST['btn-moderator-deny']) ) {
		$category = $_SESSION['category'];
		// header("location: view_category.php?category=".$category."");
		$sql_user = "SELECT username FROM Moderator WHERE category_name = '".$category."' ;";

	    $result = mysqli_query($db, $sql_user);
		if( $result){
			$sql = "DELETE FROM Moderator WHERE username = '".$username."' ;";
			$res = mysqli_query($db, $sql);
			header("Location: homepage.php"); /* Redirect browser */
		}
	}
	
	if( isset($_POST['btn-admin-delete']) ) {
		$category = $_SESSION['category'];
		$sql = "DELETE FROM Category WHERE name = '".$category."' ;";
		$res = mysqli_query($db, $sql);
		header("Location: homepage.php"); /* Redirect browser */
	}

	echo "<a style='margin-left: 100px;".
		"'href='view_category.php?category=".$category."&view=".$view."&page=1&pageview=10'>Show 10 per page</a>"; 
	echo "<a style='margin-left: 100px;".
		"'href='view_category.php?category=".$category."&view=".$view."&page=1&pageview=25'>Show 25 per page</a>"; 
	echo "<a style='margin-left: 100px;".
		"'href='view_category.php?category=".$category."&view=".$view."&page=1&pageview=50'>Show 50 per page</a>"; 
	echo "<br><br/>\n";

	$pageCounter = 1;
	$pageMax = $totalPostCount / $pageview + 1;
	echo "<h5 style='margin-left: 100px;'><b> Pages: </b></h5>";

	while ($pageCounter < $pageMax){
		echo "<a style='margin-left: 100px;'href='view_category.php?category=".$category."".
				"&view=".$view."&page=".$pageCounter."&pageview=".$pageview."'>".$pageCounter."</a>"; 
		$pageCounter++;
	}
	echo "<br><br/>\n";echo "<br><br/>\n";echo "<br><br/>\n";

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
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
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
				<li >
					<form action="view_user.php" class="navbar-form navbar-left" role="settings">
					<button role="settings" type="submit"  class="btn btn-default">
				          <span class="glyphicon glyphicon-cog"></span>
					</button>
					</form>
				</li>
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

	<?php 
		if ($usermode == 1 && strlen($username) > 0){
			if($moderatormus == 1){
				echo "<form method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."' autocomplete='off'><div class='form-group'> ".
					"<button type='post' class='btn btn-primary center-block'  name='btn-moderator-deny'>Stop being moderator</button></div></form>";
			}else{
				echo "<form method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."' autocomplete='off'><div class='form-group'>".
					"<button type='post' class='btn btn-primary center-block'  name='btn-moderator-request'>Request moderatorship</button></div></form>";
			}
		} // else dont show 

		if ($usermode == 1 && strlen($username) > 0){
			if($adminmis == 1){
				echo "<form method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."' autocomplete='off'><div class='form-group'> ".
					"<button type='post' class='btn btn-primary center-block'  name='btn-admin-delete'>Delete category</button></div></form>";
			}
		} // else dont show 
	?>

		</body>
</html>
<?php ob_end_flush(); ?>