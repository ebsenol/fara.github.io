<?php
include_once 'dbconnect.php';
session_start();
ob_start();
$text = $_GET["Search"];
 $_SESSION['text'] = $text;
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
echo "<h2 align='center'><b> Search Result: </b></h2>";
///////////////////////////////////////////////////////////////////////////////////
$sql =  "SELECT * " .
        "FROM User WHERE username like '%".$text."%'";

$result = mysqli_query($db, $sql);
$res_array = array();
if( $result->num_rows > 0){
    if( $result->num_rows > 0)
        while($row = mysqli_fetch_array($result))
            array_push($res_array, $row);
    echo "<h4 align='left' style='padding-left: 30px'><b> Users: </b></h4>";
    echo "</br>";
    echo "<table class='table table-striped' style='width:95%'; align = 'center';  align='center' cellpadding='10'>";
    foreach($res_array as $req)
    {
        
    echo "<tbody>";
    echo "<td  width='60%'  style='padding: 10px'>".
        "<a href='view_user.php?user=". $req['username'] ."'>" .$req['username']. " </a></td>";	
    // echo "</tr>";
    }
    echo"</tbody>";
    echo '</table></p></br></br>';
}
//////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
$sql =  "SELECT * " .
        "FROM Category WHERE name like '%".$text."%'";

$result = mysqli_query($db, $sql);
$res_array = array();
if( $result->num_rows > 0){
    if( $result->num_rows > 0)
        while($row = mysqli_fetch_array($result))
            array_push($res_array, $row);
    echo "<h4 align='left' style='padding-left: 30px'><b> Categories: </b></h4>";
    echo "</br>";
    echo "<table class='table table-striped' style='width:95%'; align = 'center';  align='center' cellpadding='10'>";
    foreach($res_array as $req)
    {
        
    echo "<tbody>";
    echo "<td  width='60%'  style='padding: 10px'>".
        "<a href='view_category.php?category=". $req['name'] ."'>" .$req['name']. " </a></td>";	
    // echo "</tr>";
    }
    echo"</tbody>";
    echo '</table></p></br></br>';
}
//////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////
$sql =  "SELECT * " .
        "FROM Topic WHERE topic_name like '%".$text."%'";

$result = mysqli_query($db, $sql);
$res_array = array();
if( $result->num_rows > 0){
    if( $result->num_rows > 0)
        while($row = mysqli_fetch_array($result))
            array_push($res_array, $row);
    echo "<h4 align='left' style='padding-left: 30px'><b> Topics: </b></h4>";
    echo "</br>";
    echo "<table class='table table-striped' style='width:95%'; align = 'center';  align='center' cellpadding='10'>";
    foreach($res_array as $req)
    {
        
    echo "<tbody>";
    echo "<td  width='60%'  style='padding: 10px'>".
        "<a href='view_topic.php?topic=". $req['topic_name'] ."'>" .$req['topic_name']. " </a></td>";	
    // echo "</tr>";
    }
    echo"</tbody>";
    echo '</table></p></br></br>';
}
//////////////////////////////////////////////////////////////////////////////////////

$sql =  "SELECT * " .
        "FROM post AS P, content AS C, category_topic AS CT  ".
        "WHERE P.cont_id = C.cont_id AND P.belongs = CT.topic_name AND ".
        "(C.content like '%".$text."%' OR P.post_title like '%".$text."%') ".
        "ORDER BY P.post_title;";

$result = mysqli_query($db, $sql);
$res_array = array();
$voteIdCount = 0;
if( $result->num_rows > 0){
    if( $result->num_rows > 0)
        while($row = mysqli_fetch_array($result))
            array_push($res_array, $row);

            
    
    echo "<h4 align='left' style='padding-left: 30px'><b> Posts: </b></h4>";
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
    $from = "homepage.php";
    $_SESSION['username'] = $username; //start session

    foreach($res_array as $req)
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
            
        // get minutes
        $postdate = new DateTime($req['timestamp']);
        $now = new DateTime();
        // timezone problem
        $now = $now->modify('+2 hour');
        $agoDate = date_diff($now, $postdate);
        $ago = "";

        if ($agoDate->d > 0){
            $ago = "".$agoDate->d." days";
        }
        else if ($agoDate->h > 0){
            $ago = "".$agoDate->h." hours";
        }
        else if ($agoDate->i > 0){
            $ago = "".$agoDate->i." minutes";
        }
        else if ($agoDate->s > 0){
            $ago = "".$agoDate->s." seconds";
        }
        echo "<td  width='60%'  style='padding: 10px'>".
        "<a href='viewcontent.php?id=". $currentContentID ."'>" .$req['post_title']. " </a></td>";	
        echo "<td  width='6%' align = 'center' style='padding: 10px'>".$ago."</td>";
        echo "<td  width='8%' align = 'center' style='padding: 10px'>".
        "<a href='view_category.php?category=". $req['category_name'] ."'>". ($req['category_name']) . "</td>";
        echo "<td   width='8%' align = 'center' style='padding: 10px'>".
        "<a href='view_topic.php?topic=". $req['belongs'] ."'>". ($req['belongs']) . "</td>";
        echo "<td   width='8%' align = 'center' style='padding: 10px'>". ($req['username']) . "</td>";
        echo "</tr>";
    }
    echo"</tbody>";
    echo '</table></p></br></br>';
}

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
function Search(){
    //	header("location: login.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    Search();
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
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<link rel="stylesheet" href="/css/upvote/jquery.upvote.css" type="text/css" media="screen">
	<script type="text/javascript" src="/css/upvote/jquery.upvote.js"></script>

	<script type="text/javascript" src="/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
	<title>Fara</title><link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

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
         <a class="navbar-brand" href="homepage.php">Fara</a>
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
				<?php 
				if ($usermode == 1 && strlen($username) > 0)
				echo "<li><a data-toggle='modal' data-target='#addCategoryModal'><span class='glyphicon'></span><b>+</b> Add Category</a>";
				?>
				</li>
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


    <div id="addCategoryModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"> &times;</button>
                <h4>Add Category</h4>
            </div>
            <div class="modal-body">
                    <form  class="form-group" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">

                       <label class="text" for="category"></label><input type="text" class="form-control input-sm" placeholder="Category" id="category" name="category">
                       <button type="submit" class="btn btn-info btn-xs" name="btn-addcategory">Add</button>
                       <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Cancel</button> 
                       </div>
               
                       

                    </form>
            </div>
		 </div>
	 </div>
	
	</body>
</html>
<?php ob_end_flush(); ?>