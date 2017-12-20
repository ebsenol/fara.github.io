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
	$cid = $_GET["id"];
	$_SESSION['cid'] = $cid; 
	$sql =  "SELECT * " .
			"FROM Post AS P, Content AS C, Category_Topic AS CT  ".
			"WHERE P.cont_id = C.cont_id AND C.cont_id = ".$cid." AND CT.topic_name = P.belongs; ";
	
	$result = mysqli_query($db, $sql);
	$res_array = array();
	if( $result->num_rows > 0)
		while($row = mysqli_fetch_array($result))
			array_push($res_array, $row);
	echo "</br>";
	// echo "<br>";
	// echo "</br>";
	echo "<table class='table table-striped' style='width:95%'; align = 'center';  align='center' cellpadding='10'>";
	echo "<thead class='thead-inverse'>";
	echo 	"<th style='padding: 10px' > Votes</th>".
			"<th style='padding: 10px' > Title</th>".
			"<th style='padding: 10px' > </th>".
		 "</tr>".
		 "</thead>";
	echo "<tbody>";
	$voteIdCount = 0;
	$from = "homepage.php";
	$_SESSION['username'] = $username; //start session
	$req = $res_array[0];
		echo "<tr>";
		$voteIdCount++;
		$currentContentID = $req['cont_id'];
		$sql =  "SELECT ".
					"(SELECT count(*) " .
					"FROM Vote  ".
					"WHERE vote = 1 AND cont_id = " .$currentContentID. " ) - ".
					"(SELECT count(*) " .
					"FROM Vote  ".
					"WHERE vote = 0 AND cont_id = " .$currentContentID. " ) AS dif;";
		$result = mysqli_query($db, $sql);
		$res_arr =  mysqli_fetch_array($result);
		$voteCount = $res_arr['dif'];
		$upCountFromUser = 0;
		$downCountFromUser = 0;
		$sql2 = "SELECT ". 
				"(SELECT count(*) " .
				"FROM Vote  ".
				"WHERE vote = 1 AND username = '".$username."' AND cont_id = ".$currentContentID." ) AS up;";
		$result2 = mysqli_query($db, $sql2);
		if ($result2){
			$res_arr2 =  mysqli_fetch_array($result2);
			$upCountFromUser = $res_arr2['up'];
		}
		$sql3 =  "SELECT (". 
				"SELECT count(*) AS down " .
				"FROM Vote  ".
				"WHERE vote = 0 AND username = '".$username."' AND cont_id = ".$currentContentID." ) AS down;";
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
		echo "<td  width='10%'  style='padding: 0px'>".
		"<a href='viewcontent.php?id=". $currentContentID ."'>" .$req['post_title']. " </a></td>";	
		echo "<td  width='6%' align = 'center' style='padding: 10px'></td>";
		echo "<td  width='8%' align = 'center' style='padding: 10px'>".
		"<a href='view_category.php?category=". $req['category_name'] ."'></td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'>".
		"<a href='view_topic.php?topic=". $req['belongs'] ."'></td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'></td>";
		echo "<\tr>";
		echo "<tr>";
		echo "<\tr>";	
		echo "<tr>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'>	</td>";
		echo "<td   width='10%' align = 'center' style='padding: 20px'>". ($req['content']) . "</td>";
		echo "<td  width='6%' align = 'center' style='padding: 10px'></td>";
		echo "<td  width='8%' align = 'center' style='padding: 10px'>".
		"<a href='view_category.php?category=". $req['category_name'] ."'></td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'>".
		"<a href='view_topic.php?topic=". $req['belongs'] ."'></td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'></td>";		
		echo "</tr>";
		
		"<thead class='thead-inverse'>";
	echo 	"<th style='padding: 10px' ></th>".
			"<th style='padding: 10px' ></th>".
			"<th style='padding: 10px'> posted </th>".
			"<th style='padding: 10px'> category</th>".
			"<th style='padding: 10px'> topic</th>".
			"<th style='padding: 10px'> posted by</th>".
		 "</tr>".
		 "</thead>";
		 echo "<tr>";
		echo "<\tr>";
		echo "<tr>";
		echo "<td  width='6%' align = 'center' style='padding: 10px'></td>";
		echo "<td  width='60%' align = 'center' style='padding: 10px'></td>";
		echo "<td  width='6%' align = 'center' style='padding: 10px'>". ($req['timestamp']) . "</td>";
		echo "<td  width='8%' align = 'center' style='padding: 10px'>".
		"<a href='view_category.php?category=". $req['category_name'] ."'>". ($req['category_name']) . "</td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'>".
		"<a href='view_topic.php?topic=". $req['belongs'] ."'>". ($req['belongs']) . "</td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'>". ($req['username']) . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<\tr>";
		
	echo"</tbody>";
	echo '</table></p>';
	//ShowReply();
	echo "<form action='' method='post' align = 'right' value = 'Comment' style='padding-right: 30px'>".
	"<p><input type='submit' value='delete' align = 'right' name = 'btn-delete-post'></p>".
	"</form>";
	echo "<form action='' method='post' align = 'right' value = 'Comment' style='padding-right: 30px'>".
	"<p><input type='text' name='comment'  placeholder = 'Leave your comment'/>".
	"<p><input type='submit' value='submit' align = 'right' name = 'btn-comment'></p>".
	"</form>";
	
	
	#echo "<h4 width = '10%' style ='padding-left: 90px'>Comments<h4>";
	
	echo "</br>";
	echo "<table class='table table-striped' style='width:95%'; align = 'center';  align='center' cellpadding='10'>";
	echo "<thead class='thead-inverse'>";
	echo 	"<th style='padding: 10px' >votes</th>".
			"<th style='padding: 10px' >Comments</th>".
			"<th style='padding: 10px' > </th>".
		 "</tr>".
		 "</thead>";
	echo "<tbody>";
	////////////////////////////////////////////////
	$sql4 =  "SELECT * " .
			"FROM Comment AS C1, content AS C2  ".
			"WHERE C1.cont_id = C2.cont_id AND C1.dst_cont_id =".$cid.";";
	
	$result4 = mysqli_query($db, $sql4);
	$res_array4 = array();
	if( $result4->num_rows > 0)
		while($row = mysqli_fetch_array($result4))
			array_push($res_array4, $row);
	
	echo "<tbody>";
	
	$from = "homepage.php";
	$_SESSION['username'] = $username; //start session
	foreach($res_array4 as $req)
	{
		echo "<tr>";
		$voteIdCount++;
		$currentContentID = $req['cont_id'];
		$sql =  "SELECT ".
					"(SELECT count(*) " .
					"FROM vote  ".
					"WHERE vote = true AND cont_id = " .$currentContentID. " ) - ".
					"(SELECT count(*) " .
					"FROM Vote  ".
					"WHERE vote = false AND cont_id = " .$currentContentID. " ) AS dif;";
		$result = mysqli_query($db, $sql);
		$res_arr =  mysqli_fetch_array($result);
		$voteCount = $res_arr['dif'];
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
  			else {
  				echo "<a class='upvote'></a> ";
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
  			echo "<a class='downvote'></a> ";
		}
  		echo "</div>";
		//activate vote button
		//echo "<script type='text/javascript'> $('#vote".$voteIdCount."').upvote(); </script>"; 	
		echo "<td  width='60%'  style='padding: 10px'>".$req['content']. "</td>";	
		echo "<td  width='6%' align = 'center' style='padding: 10px'>". ($req['timestamp']) . "</td>";
		echo "<td  width='8%' align = 'center' style='padding: 10px'></td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'></td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'>". ($req['username']) . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td  width='60%'  style='padding: 10px'></td>";	
		echo "<td  width='6%' align = 'center' style='padding: 10px'></td>";
		echo "<td  width='8%' align = 'center' style='padding: 10px'></td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'></td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'></td>";
		echo "<td>";
		//ShowReply();
		echo "<form action='' method='post' align = 'right' value = 'Comment'>".
		"<p><input type='text' name='comment'  placeholder = 'Leave your comment'></p>".
		"<p><input type='submit' value='submit' align = 'right' name = 'btn-comment'></p>".
		"</form>";
		echo "</td>";
		echo "</tr>";
	}
	echo"</tbody>";
	echo '</table></p></br></br>';
	if( isset($_POST['btn-delete-post']) ) {
		$sql = "DELETE FROM Content WHERE cont_id = ".$cid.";";
		$res = mysqli_query($db,$sql);
		$sql = "DELETE FROM Post WHERE cont_id = ".$cid.";";
		$res = mysqli_query($db,$sql);
		$sql = "DELETE FROM Comment WHERE parent_post = ".$cid.";";
		$res = mysqli_query($db,$sql);
		header("location: homepage.php");
	}
	if( isset($_POST['btn-addcategory']) ) {
		$category = $_POST['category'];
		$sql = "INSERT INTO category VALUES ('".$category."');";
		$res = mysqli_query($db,$sql);
		//header("location: homepage.php");
	}
	if( isset($_POST['btn-comment']) ) {
		$comment = $_POST['comment'];
		echo "ololo";
		echo $comment;
		$sql = "INSERT INTO Content VALUES (NULL, now(), '".$comment."', 'comment', '".$username."', 0);";
		$res = mysqli_query($db,$sql);
		$sql = "INSERT INTO Comment VALUES (LAST_INSERT_ID(), '".$username."',".$cid.",".$cid.");";
		$res = mysqli_query($db,$sql);
	}
	/////////////////////////////////////////////////////////
	
	
	function Apply(){
	    $sql = "INSERT INTO apply VALUES ('".$_SESSION['userName']."', '".$_POST['application']."')";
	    if(mysqli_query($connection, $sql)){
	        $_SESSION['course'] = $_POST['application'];
	        mysqli_close($connection);
	        header("location: congrats.php");
	    } else{
	        $_SESSION['course'] = 'none';
	        mysqli_close($connection);
	        header("location: congrats.php");
	    }
	}
	function ShowReply(){
// 		echo"<div data-role='content' class='ui-content' align = 'right' >".
//    	 	"<a href='#myPopup' data-rel='popup' class='ui-btn ui-btn-inline ui-corner-all ui-btn-icon-left'>Reply</a>".
//   	  "<div data-role='popup' id='myPopup' class='ui-content' style='min-width:250px;'>".
//       "<form method='post' action='Comment()'>".
//         "<div>".
//           "<p4>Leave your comment</p4>".
//           "<label for='usrnm' class='ui-hidden-accessible'>Text</label>".
//           "<input type='text' name='user' id='usrnm' placeholder='Text'>".
//           "<input type='submit' data-inline='true' value='Submit'>".
//        "</div>".
//       "</form>".
//     "</div>".
//   "</div>";
		echo "<form action='' method='post'>".
		"<p>Leave comment: <input type='text' name='comment' /><br />";
	}
	// function Comment(){
	// 	$sql = "INSERT INTO apply VALUES ('".$_SESSION['userName']."', '".$_POST['application']."')";
	//     if(mysqli_query($connection, $sql)){
	//         $_SESSION['course'] = $_POST['application'];
	//     } else{
	//         $_SESSION['course'] = 'none';
	//     }
	// }
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
<!-- <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> -->
	
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
   
   <!-- Initialize vote buttons -->
	<?php 
		$counter = 0;
		while ($counter <= $voteIdCount){
			$counter++;
			echo "<script type='text/javascript'> $('#vote".$counter."').upvote();</script>";
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
				<li
					<form class="navbar-form navbar-left" role="search">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Search">
					</div>
					<button type="submit"  class="btn btn-default">
						<span class="glyphicon glyphicon-search"></span>
					</button>
					</form>
				</li>
				<li> <p class="navbar-text"> <?php if ($usermode == 1) echo "Logged in as ".$username.""; else echo "Guest"; ?>  </p></li>
				<?php if ($usermode == 1) echo "<li><a href='logout.php'>Log out</a></li>"; else echo "<li><a href='login.php'>Log in</a></li>"; ?>
				

		     </ul>
			</div>
		</div>
	</nav>
	
	<?php
		echo "<script> function addCategory() { ";
			
		$sql =  "SELECT category_name " .
			"FROM Category_Topic  ".
			"WHERE topic_name = '".$topic."'";
		$result = mysqli_query($db, $sql);	
		$category =implode(" ",mysqli_fetch_assoc($result));
		echo "} </script>";
	?>
			
		</body>
</html>
<?php ob_end_flush(); ?>