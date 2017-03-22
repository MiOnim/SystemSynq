<?php
  session_start();
  extract($_COOKIE);
  if ($sessionid==NULL) 
  {
    header("location:./login.php");
  }
  print $sessionid;
?>
<html>
<center>
<style>
form{ display: inline-block; }
</style>
<form action="./adamsbasement.php">
    <input type="submit" value="Basement">
</form>
<form action="./adamssecond.php">
    <input type="submit" value="2nd Floor">
</form>
<p></p>
<img src="https://i.imgur.com/Nwbnfri.png" alt="Adams Hall" usemap="#Map" />
<map name="Map" id="Map">
    <area alt="Room 200" title="" href="200.php" shape="poly" coords="77,90,202,90,201,215,77,215" />

	<area alt="Room 203" title="" href="203.php" shape="poly" coords="203,89,456,88,455,215,202,216" />
</map>
</center>
</html>
