<?php
  session_start();
  extract($_COOKIE);
  if ($sessionid==NULL) 
  {
    header("location:./login.php");
  }
  print $sessionid;

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
    $query = "SELECT * FROM HardwareSoftware WHERE id='".$comp_id."'";
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
?>


<html>
<head>
<style>
table, th, td {
    border: 1px solid black;
}
</style>
</head>
<body>
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
    <td href="200.php?comp=2">200-002</td>
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
    <td>200-006</td>
    <td>5 hours</td>
    <td>Unusual process</td>
  </tr>
</table>
</center>
</body>
</html>

