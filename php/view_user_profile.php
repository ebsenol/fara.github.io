<?php
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
	foreach($res_array as $req)
	{
		echo "<tr>";

		$voteIdCount++;
		$sql =  "SELECT ".
					"(SELECT count(*) " .
					"FROM Vote  ".
					"WHERE vote = true AND cont_id = " .$req['cont_id']. " ) - ".
					"(SELECT count(*) " .
					"FROM Vote  ".
					"WHERE vote != true AND cont_id = " .$req['cont_id']. " ) AS dif;";

		$result = mysqli_query($db, $sql);
		$res_arr =  mysqli_fetch_array($result);
		$vote = $res_arr['dif'];
		// echo $result['dif'];

      	echo "<td  width='10%' style='padding:0px''> ".
      			"<div id='vote".$voteIdCount."' class = 'upvote upvote-programmers' > ".
      				"<a class='upvote'></a> ".
      				"<span class='count'>".$vote."</span> ".
      				"<a class='downvote'></a> ".
      			"</div>";

		echo "<td  width='60%'  style='padding: 10px'>".
		"<a href='viewcontent.php?id=". $req['cont_id'] ."'>" .$req['post_title']. " </a></td>";	
		echo "<td  width='6%' align = 'center' style='padding: 10px'>". ($req['timestamp']) . "</td>";
		echo "<td  width='8%' align = 'center' style='padding: 10px'>". ($req['category_name']) . "</td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'>". ($req['belongs']) . "</td>";
		echo "<td   width='8%' align = 'center' style='padding: 10px'>". ($req['username']) . "</td>";
		echo "</tr>";
	}
	echo"</tbody>";
	echo '</table></p></br></br>';
?>