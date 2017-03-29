<?php
  session_start();
  extract($_COOKIE);
  if ($sessionid==NULL) 
  {
    header("location:./login.php");
  }
 // print $sessionid;
?>

<!DOCTYPE html>
<html>
<body>
<center>
<p>Click a building to view floor plan:</p>

<img src="https://bulletin.hofstra.edu/mime/media/54/2581/2011_map.jpg" alt="Hofstra" usemap="#Map" />
<map name="Map" id="Map">
    <area alt="Adams Hall" title="" href="adamsfirst.php" shape="poly" coords="241,1001,287,993,291,1011,247,1026" />
</map>
</map>
</center>
</body>
</html>

