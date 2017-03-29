<?php
 	include("dbaccess.php");
  	$database = mysql_connect("localhost","root", "goodyear"); // DB connection
  	extract($_POST);
  	$query = "SELECT user FROM users WHERE user='$usr' AND pwd='$pwd'";
  	$result = query($query);
  	if($row=mysql_fetch_row($result)!=false)
	{
		$sessionid = rand();
		$str = "INSERT INTO  sessions(sid,user) VALUES ('". $sessionid."','".$usr."')";
		$result = query($str);
		setcookie("sessionid",$sessionid, time()+60*60*2); // Unique cookie sessionID
		setcookie("usr",$usr, time()+60*60*2); // Generates unique cookie for the user
		print("$sessionid");
	}
	else
	{
	print("0");
	}
  
?>

