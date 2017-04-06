<?php
	include("dbaccess.php");
	$database = mysql_connect("localhost","root", "systemsynq17"); // DB connection
	extract($_POST);
	//extract($_COOKIE);
        echo $username;
        echo $password;
	$hash = password_hash($password,PASSWORD_DEFAULT);
	$query = "INSERT INTO users(user,password,privileges) VALUES ('".$username."', '".$hash."','".$username."')";
	$result = query($query);
	if($row=mysql_fetch_row($result)!=false)
	{
		header("location:./menu.php");
	}
	else
	{
		header("location:./test.php");
	}        
?>
