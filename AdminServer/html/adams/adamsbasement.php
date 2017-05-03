<?php
  session_start();
  extract($_COOKIE);
  if ($sessionid==NULL) 
  {
    header("location:./login2.html");
  }
  //print $sessionid;
?>
<html>
<center>
<style>
form{ display: inline-block; }
</style>
<form action="../menu.php">
    <input type="submit" value="Home">
</form>
<form action="./adamsfirst.php">
    <input type="submit" value="1st Floor">
</form>
<form action="./adamssecond.php">
    <input type="submit" value="2nd Floor">
</form>
<form action="../logout.php">
    <input type="submit" value="Logout">
</form>
<p></p>
<img src="https://i.imgur.com/G54Zos7.png" alt="Adams Hall" usemap="#Map" />
<map name="Map" id="Map">
    <area alt="Room 200" title="" href="200.php" shape="poly" coords="77,90,202,90,201,215,77,215" />

	<area alt="Room 203" title="" href="203.php" shape="poly" coords="203,89,456,88,455,215,202,216" />
</map>
</center>
</html>
