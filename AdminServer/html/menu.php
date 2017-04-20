<?php
  session_start();
  extract($_COOKIE);
  if ($_COOKIE["sessionid"]==NULL)
  {
    header("location:./login2.html");
  }
  //print $sessionid;
  print_r($_COOKIE);
  print $_COOKIE["sessionid"];
?>

<!DOCTYPE html>
<html>
<body>
<center>
<p>Click a building to view floor plan:</p>
<form action="logout.php">
  <input type="submit" value="Logout">
</form>
<img src="https://bulletin.hofstra.edu/mime/media/54/2581/2011_map.jpg" alt="Hofstra" usemap="#Map" />
<map name="Map" id="Map">
    <area alt="Adams Hall" title="" href="/SystemSynq/AdminServer/html/adams/adamsfirst.php" shape="poly" coords="241,1001,287,993,291,1011,247,1026" />
</map>
</center>
</body>
</html>

