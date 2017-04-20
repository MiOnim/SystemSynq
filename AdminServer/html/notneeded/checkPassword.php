<?php
 session_start();
 echo "hi ";
 //echo $_POST["uname"];
 	include("dbaccess.php");
  	$database = mysql_connect("localhost","root", "systemsynq17"); // DB connection
  	extract($_POST);
	echo $uname;
	echo $pwd;
        //$hashed_password = password_hash($pwd,PASSWORD_DEFAULT);
  	$query = "SELECT user FROM users WHERE user='$uname' AND password='$pwd'";
  	$result = query($query);
	echo $row['password'];
  	if($row=mysql_fetch_row($result)!=false)
	{
		$sessionid = rand();
		$str = "INSERT INTO sessions(sid,user) VALUES ('". $sessionid."','".$uname."')";
		$result = query($str);
		setcookie("sessionid",$sessionid, time()+60*60*2); // Unique cookie sessionID
		setcookie("usr",$uname, time()+60*60*2); // Generates unique cookie for the user
		$_SESSION["sessionid"] = $sessionid;	
		header("location:./menu.php");
	}
	else
	{
	print("Wrong password");
	header("location:./login2.html");
	}
  
?>

