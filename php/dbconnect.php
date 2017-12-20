<?php
/**
 * Created by IntelliJ IDEA.
 * User: berker
 * Date: 09-Dec-17
 * Time: 6:32 PM
 */
	$database_username = "root";
	$database_password = "figalA!1";
	$database = "cs353db";
	$db = mysqli_connect("localhost", $database_username, $database_password, $database)
    or die("ERROR CONNECTING TO DATABASE!");
?>
