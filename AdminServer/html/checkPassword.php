<?php
 echo "hi ";
 //echo $_POST["uname"];
 	include("dbaccess.php");
  	$database = mysql_connect("localhost","root", "systemsynq"); // DB connection
  	extract($_POST);
	echo $uname;
	echo $pwd;
  	$query = "SELECT user FROM users WHERE user='$uname' AND password='$pwd'";
  	$result = query($query);
  	if($row=mysql_fetch_row($result)!=false)
	{
		$sessionid = rand();
		$str = "INSERT INTO  sessions(sid,user) VALUES ('". $sessionid."','".$uname."')";
		$result = query($str);
		setcookie("sessionid",$sessionid, time()+60*60*2); // Unique cookie sessionID
		setcookie("usr",$uname, time()+60*60*2); // Generates unique cookie for the user
		//print("$sessionid");
		header("location:./menu.php");
	}
	else
	{
	print("Wront password");
	header("location:./login2.html");
	}
  
?>

