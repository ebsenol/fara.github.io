<script>
function checkform(form) {
    var inputs = form.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
        if(inputs[i].hasAttribute("required")){
            if(inputs[i].value == ""){
                alert("Please fill all required fields");
                return false;
            }
        }
    }
    return true;
}
</script>
<?php
	include_once 'dbconnect.php';
	session_start();
	ob_start();
	$username = $_SESSION['username'];		
	$topic = $_SESSION["topic"];
	$category = $_SESSION['category'];
	if( isset($_POST['btn-post']) ) { 
		$title = $_POST['title'];
		$text = $_POST['text'];
		$sql = "INSERT INTO Content VALUES (NULL, now(), '".$text."', 'post', '".$username."', 0);";
		$res = mysqli_query($db,$sql);
		$sql = "INSERT INTO Post VALUES (LAST_INSERT_ID(), '".$title."', 'text', '".$topic."');";
		$res = mysqli_query($db,$sql);
		header("location: view_topic.php?topic=".$topic."");
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

	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
	<div class="container">
	  <?php
	  	echo "<a href='view_topic.php?topic=".$topic."' class='btn btn-info'> Go back</a>";
	  	echo "<br>";
		echo "<h2>New post</h2>";
	  
	  	echo "<p><i>Posting to ".$_GET["category"]." / ".$topic."</i></p>";
	  ?>
	  <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off" onsubmit="return isValid()">

	    <div class="form-group" >
	      <label for="title">Title:</label>
	      <input type="text" name="title" id ="title" class="form-control">
	    </div>
	    <div class="form-group">
	      <label for="text">Text:</label>
	       <textarea class="form-control" name="text" rows="5" id="text"></textarea>
	    </div>

		<div class="form-group">
			<button type="post" class="btn btn-primary center-block"  name="btn-post">Post</button>
		</div>		

	  </form>

	</div>
	
	<script>
		function isValid(){
			var title = document.getElementById("title").value;
			var text = document.getElementById("text").value;
			
			if( title == "" && text == ""){
				alert("Fill in the spaces!");
				return false;
			}	
			else if( title == ""){
				alert("Please enter a title!");
				return false;
			}
			else if( text == ""){
				alert("Please enter a text!");
				return false;
			}
			else if (title.length >= 50){
				alert("Title can be at most 50 chars. Please make it shorter.");
				return false;
			}
			else if( text.length >= 800){
				alert("Text can be at most 800 chars. Please make it shorter.");
				return false;
			}
			return true;
			
		}
		
	</script>
</body>
</html>
<?php ob_end_flush(); ?>