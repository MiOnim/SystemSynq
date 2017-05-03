<?php
  session_start();
  echo "hello ";
  include("dbaccess.php");
  $database = mysql_connect("localhost","root","systemsynq17");
  extract($_POST);
  echo $uname;
  echo $pwd;
  $query = "SELECT * FROM users WHERE user = '$uname'";
  $result = query($query);
  if (1<2)
  {
    $row = mysql_fetch_array($result);
    if (password_verify($pwd,$row['password']))
    {
       $sessionid = rand();
       $str = "INSERT INTO sessions(sid,user) VALUES ('".$sessionid."','".$uname."')";
       $result = query($str);
       setcookie("sessionid",$sessionid,time()+60*60*2);
       setcookie("usr",$uname,time()+60*60*2);
       $_SESSION["sessionid"] = $sessionid;
       header("location:./menu.php"); 
    }
    else
    {
       header("location:./login2.html");
    }
  }
?>
