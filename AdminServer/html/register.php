<?php
	include("dbaccess.php");
	$database = mysql_connect("localhost","root", "goodyear"); // DB connection
	extract($_POST);
	extract($_COOKIE);
        print $usr;
        print $pwd;
	$query = "INSERT INTO users(user,pwd) VALUES ('$usr', '$pwd')";
	query($query);
?>

