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

function mysql_query_or_die($query) {
    $result = mysql_query($query);
    if ($result)
        return $result;
    else {
        $err = mysql_error();
        die("<br>{$query}<br>*** {$err} ***<br>");
    }
}
  // processes?=ID
  if (isset($_GET['processes'])){
    $process_id = $_GET['processes'];
    $connection = mysql_connect('localhost','root','systemsynq17');
    mysql_select_db('systemsynq');
    echo "<left>";
    $query = "SELECT * FROM HardwareSoftware WHERE ID='".$process_id."'";
    $result = mysql_query_or_die($query);
    echo "<b>Name: </b>";
    while($row = mysql_fetch_array($result))
    {
       echo $row['Name'];
       echo "<br><br><br>";
       echo "<b>Processes: </b>";
       echo "<br>";
       $process_array = explode(',',$row['Processes_Unusual']);
       echo "<td>";
       $number_of_processes = 0;
       foreach($process_array as $key=> $value)
       {
           echo $value;
           $number_of_processes += 1;
           echo "<br>";
       }
    }
    // Average Uptime
    $query = "SELECT * FROM Notifications";
    $result = mysql_query_or_die($query);
    $total_uptime = 0;
    $total_computers = 0;
    while($row = mysql_fetch_array($result))
    {
        $total_uptime += $row['Uptime'];
        $total_computers += 1;
    }
    $average_uptime = ($total_uptime/$total_computers);
    echo "</left>";
    echo "<br><br>";
    echo "<center>";
    echo "Total number of processes: " . $number_of_processes . "<br>";
    echo "Average uptime in this room: ". round($average_uptime,2) . " hours";
    echo "</center>";
    die();
  }
  // notifications?=ID
  if (isset($_GET['notifications'])) {
    $notif_id = $_GET['notifications'];
    $connection = mysql_connect('localhost','root','systemsynq17');
    mysql_select_db('systemsynq');
    $query = "SELECT * FROM Notifications WHERE ID='" . $notif_id."'";
    $result = mysql_query_or_die($query);
    while($row = mysql_fetch_array($result))
    {
      echo "<b>Name: </b><br>";
      echo "Adams-20300".$row['ID']."<br><br>";
      echo "<b>Notifications: </b><br>";
      $notification_array = explode(',',$row['Notifications']);
      //print_r($notification_array);
      echo "<td>";
      foreach ($notification_array as $item)
      {
          echo $item;
          echo "<br>";
      }
    } 
    die(); 
  }

  // comp?=ID
  if (isset($_GET['comp'])) {
    $comp_id = $_GET['comp'];
    $connection = mysql_connect('localhost','root','systemsynq17');
    mysql_select_db('systemsynq');
    echo "<center>
    <table border='1'>
    <div id = CenterTableText>
    <tr>
    <th>Name</th>
    <th>CPU Usage</th>
    <th>Free RAM</th>
    <th>Free DISK</th>
    <th>Total Processes</th>
    <th>All Processes</th>
    </tr>";
    $noteconnection = mysql_connect('localhost','root','systemsynq17');
    mysql_select_db('systemsynq');
    $query = "SELECT * FROM HardwareSoftware WHERE ID='".$comp_id."'";
    $result = mysql_query_or_die($query);
    while($row = mysql_fetch_array($result))
    {
       echo "<tr>";
       echo "<td>" . $row['Name']. "</td>";
       echo "<td>" . $row['CPU_Usage']. "</td>";
       echo "<td>" . $row['RAM_Free']."</td>";
       echo "<td>" . $row['DISK_Free']. "</td>";
       echo "<td>" . $row['Processes_Total']. "</td>";
       $process_array = explode(',',$row['Processes_Unusual']);
       echo "<td>";
       foreach($process_array as $key=> $value)
       {
           echo $value;
           echo "<br>";
       }
       echo "<a href='203.php?processes=".$comp_id."'>Click here to view all processes</a>";
 
       echo "</td>";
    }
    echo "</table>";
    echo "</div>";
    echo "</center>";
    echo "<center>
    <table border='1'>
    <tr>
    <th>Name</th>
    <th>CPU Cores</th>
    <th>Total RAM</th>
    <th>Total DISK</th>
    <th>On or Off</th>
    </tr>";

    $query = "SELECT * FROM MachineInfo WHERE ID='".$comp_id."'";
    $result = mysql_query_or_die($query);
    while($row = mysql_fetch_array($result))
    {
       echo "<tr>";
       echo "<td>" . $row['Name']. "</td>";
       echo "<td>" . $row['CPU_Cores']. "</td>";
       echo "<td>" . $row['RAM_Total']."</td>";
       echo "<td>" . $row['DISK_Total']. "</td>";
       echo "<td>" . $row['On_Off_Flag']. "</td>";
    }
    echo "</table>";
    echo "</div>";
    echo "</center>";
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
     $notif_array = explode(',',$row['Notifications']);
     echo "<td>";
     if (count($notif_array) >= 2)
     {
         foreach(array_slice($notif_array,0,2) as $item)
         {
             echo $item."<br>";
         }
         echo "<a href='203.php?notifications=".$row['ID']."'>Click to view all notifications</a></td>";
     }
     else 
     {
         echo $row['Notifications'];
     }
     //echo "<td>" . (strlen($row['Notifications']) > 25 ? substr($row['Notifications'],0,25)."..." : $row['Notifications']) . "<br>"; 
     //echo "<a href='203.php?notifications=".$row['ID']."'>Click to view all notifications</a>";
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
    text-align:center;
}
#CropLongTexts {
  overflow:hidden;
  white-space:nowrap;
  text-overflow:ellipsis;
  width:500px;
}

#CenterTableText {
  text-align:center;
}
</style>
</head>
<body>
</body>
</html>

