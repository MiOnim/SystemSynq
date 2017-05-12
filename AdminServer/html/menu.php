<?php
  session_start();
  extract($_COOKIE);
  if ($_COOKIE["sessionid"]==NULL)
  {
    header("location:./login2.html");
  }
?>

<!DOCTYPE html>
<html>
<style>
h1 {
	font-family: Monospace;
}
</style>
<center>
<h1>Click a building to view its floor plan:</h1>
<form action="logout.php">
  <input type="submit" value="Logout">
</form>
<img src="https://bulletin.hofstra.edu/mime/media/54/2581/2011_map.jpg" alt="Hofstra" usemap="#Map" />
<map name="Map" id="Map">
    <area alt="Adams Hall" title="" href="./adams/adamsfirst.php" shape="poly" coords="241,1001,287,993,291,1011,247,1026" />
</map>
</center>
</html>

