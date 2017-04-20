<html>
<center>
<style>
form{ display: inline-block; }
</style>
<form action="../menu.php">
  <input type="submit" value="Home">
</form>
<form action="./<?php print basename(getcwd());?>first.php">
  <input type="submit" value="<?php print ucfirst(basename(getcwd()))?> Hall">
</form>
<form action="../logout.php">
  <input type="submit" value="Logout">
</center>
</html>

<?php
  session_start();
  extract($_COOKIE);
  if ($sessionid==NULL) 
  {
    header("location:../login2.html");
  }
  print $sessionid;
  //print basename(getcwd());

function mysql_query_or_die($query) {
    $result = mysql_query($query);
    if ($result)
        return $result;
    else {
        $err = mysql_error();
        die("<br>{$query}<br>*** {$err} ***<br>");
    }
}

  if (isset($_GET['comp'])) {
    $comp_id = $_GET['comp'];
    $connection = mysql_connect('localhost','root','systemsynq17');
    mysql_select_db('systemsynq');
    $query = "SELECT * FROM HardwareSoftware WHERE ID='".$comp_id."'";
    $result = mysql_query_or_die($query);
    echo("<table>");
    $first_row = true;
    while ($row = mysql_fetch_assoc($result)) {
        if ($first_row) {
            $first_row = false;
            // Output header row from keys.
            echo '<tr>';
            foreach($row as $key => $field) {
                echo '<th>' . htmlspecialchars($key) . '</th>';
            }
            echo '</tr>';
        }
        echo '<tr>';
        foreach($row as $key => $field) {
            echo '<td><a href="203.php?processes='.$comp_id.'">' . (strlen(htmlspecialchars($field))>25 ? substr(htmlspecialchars($field), 0, 25) . "..." : htmlspecialchars($field)) . '</td>';
        }
        echo '</tr>';
    }
    echo("</table>");
    $query = "SELECT * FROM Notifications WHERE ID='".$comp_id."'";
    $result = mysql_query_or_die($query);
    echo("<table>");
    $first_row = true;
    while ($row = mysql_fetch_assoc($result)) {
        if ($first_row) {
            $first_row = false;
            // Output header row from keys.
            echo '<tr>';
            foreach($row as $key => $field) {
                echo '<th>' . htmlspecialchars($key) . '</th>';
            }
            echo '</tr>';
        }
        echo '<tr>';
        foreach($row as $key => $field) {
            echo '<td>' . htmlspecialchars($field) . '</td>';
        }
        echo '</tr>';
    }
    echo("</table>");
    $query = "SELECT * FROM MachineInfo WHERE ID='".$comp_id."'";
    $result = mysql_query_or_die($query);
    echo("<table>");
    $first_row = true;
    while ($row = mysql_fetch_assoc($result)) {
        if ($first_row) {
            $first_row = false;
            // Output header row from keys.
            echo '<tr>';
            foreach($row as $key => $field) {
                echo '<th>' . htmlspecialchars($key) . '</th>';
            }
            echo '</tr>';
        }
        echo '<tr>';
        foreach($row as $key => $field) {
            echo '<td>' . htmlspecialchars($field) . '</td>';
        }
        echo '</tr>';
    }
    echo("</table>");
    die();
  }
  echo "<center>
  <div id = CropLongTexts>
  <table border='1'>
  <tr>
  <th>ID</th>
  <th>Notification</th>
  <th>Uptime</th>
  </tr>";
  $noteconnection = mysql_connect('localhost','root','systemsynq17');
  mysql_select_db('systemsynq');
  $query = "SELECT * FROM Notifications";
  $result = mysql_query_or_die($query);
  while($row = mysql_fetch_array($result))
  {
     echo "<tr>";
     echo "<td><a href='203.php?comp=".$row['ID']."'>".$row['ID']."</a></td>";
     echo "<td>" . (strlen($row['Notifications']) > 25 ? substr($row['Notifications'],0,25)."..." : $row['Notifications']). "</td>";
     echo "<td>" . $row['Uptime']. "</td>";
  }
  echo "</table>";
  echo "</div>";
  echo "</center>";
  mysql_close($noteconnection);
?>


<html>
<head>
<style>
table, th, td {
    border: 1px solid black;
}
#CropLongTexts {
  overflow:hidden;
  white-space:nowrap;
  text-overflow:ellipsis;
  width:500px;
}
</style>
</head>
<body>
<!--
<center>
<table>
  <tr>
    <th>Computer</th>
    <th>Uptime</th>
    <th>Notifications</th>
  </tr>
  <tr>
    <td><a href="203.php?comp=1">203-001</a></td>
    <td>6 hours</td>
    <td>N/A</td>
  </tr>
  <tr>
    <td><a href="200.php?comp=2">200-002</a></td>
    <td>5 hours</td>
    <td>Unusual process</td>
  </tr>
  <tr>
    <td>200-003</td>
    <td>6 hours</td>
    <td>N/A</td>
  </tr>
  <tr>
    <td>200-004</td>
    <td>7 hours</td>
    <td>Unusual process</td>
  </tr>
  <tr>
    <td>200-005</td>
    <td>0.5 hours</td>
    <td>N/A</td>
  </tr>
  <tr>
    <td><a href="203.php?comp=6">200-006</a></td>
    <td>5 hours</td>
    <td>Unusual process</td>
  </tr>
</table>
</center>
-->
</body>
</html>

