<?php
	include_once 'dbconnect.php';
	session_start();
	ob_start();
	$user = $_SESSION['username'];	

	$res=mysqli_query($db,"SELECT * FROM follows where following = '".$user."'" );
	$res_array = array();
	
	//$count = mysqli_num_rows($res);
	if($res->num_rows >0)
		while($row = mysqli_fetch_array($res))
			array_push($res_array, $row);
		
		foreach($res_array as $following){
			$foll = $following['follower'];
			echo $foll."<br>";
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

    <title>Followers</title>
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
         <a class="navbar-brand" href="homepage.php">Fara</a>
     </div>

     <div id="navbar" class="navbar-collapse collapse">
       <ul class="nav navbar-nav">
         <li><a href="about.php">About</a></li>
     </ul>
 </div>
</div>
</nav>
	
</body>
</html>
<?php ob_end_flush(); ?>