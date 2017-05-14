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
<form action="./settings.php">
  <input type="submit" value = "Settings">
</form>
<form action="../logout.php">
  <input type="submit" value="Logout">
</form>
</center>
</html>

<html>
<head>
<style>

table{
    border-collapse: collapse;
    width: 100%;
}
th,td {
    text-align: left;
    padding: 8px;
}
tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #4CAF50;
    color: white;
}


#CenterTableText {
  text-align:center;
}
</style>
</head>
<body>
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

function date_difference($first,$second)
{
    $difference = $first->diff($second);
    return $difference->format("%D days, %H:%I:%S");
}


  // processes?=ID
  if (isset($_GET['processes'])){
    $process_id = $_GET['processes'];
    $connection = mysql_connect('10.22.12.139','root','systemsynq17');
    mysql_select_db('systemsynq');
    echo "<table>";
    echo "<tr>";
    echo "<th>Processes</th>";
    echo "<th>Total Number of Processes</th>";
    echo "<th>Average Number of Processes</th>";
    echo "</tr>";
    //echo "<left>";
    $query = "SELECT * FROM running WHERE comp_id='".$process_id."' ORDER BY row_id desc LIMIT 1";
    $result = mysql_query_or_die($query);
    while($row = mysql_fetch_array($result))
    {
       $process_array = explode(',',$row['process']);
       echo "<td>";
       $number_of_processes = 0;
       foreach($process_array as $key=> $value)
       {
           echo $value;
           $number_of_processes += 1;
           echo "<br>";
       }
    }
    $query = "SELECT * FROM running";
    $result = mysql_query_or_die($query);
    $total_computers = 0;
    $total_processes = 0;
    $total_uptime = 0;
    while ($row = mysql_fetch_array($result))
    {
       $process_array = explode(',',$row['process']);
       foreach($process_array as $key=> $value)
       {
           //$total_computers += 1;
           $total_processes += 1;
       }
       $total_computers += 1;
       $first = new DateTime("now");
       $second = new DateTime($row['last_bootup']);
       $difference = $first->diff($second);
       $total_uptime += $difference;
       //echo $difference->format("%D days, %H:%I:%S") . "</td>";
    }
    $processes_average = ($total_processes/$total_computers);
    $uptime_average = ($total_uptime/$total_computers);
    //echo "</left>";
    echo "<td>".$number_of_processes."</td>";
    echo "<td>".$processes_average."</td>";
    echo "</table>";
    //echo "<br><br>";
    echo "<center>";
    //echo "Total number of processes: " . $number_of_processes . "<br>";
    echo "</center>";
    echo "<hr><br><center><img src='https://i.imgur.com/Ribeov8.png'></center></br></hr>";
    die();
  }
  // notifications?=ID
  if (isset($_GET['notifications'])) {
    $notif_id = $_GET['notifications'];
    $connection = mysql_connect('10.22.12.139','root','systemsynq17');
    mysql_select_db('systemsynq');
    $query = "SELECT DISTINCT id, alert, alert_time FROM alerts WHERE ID='" . $notif_id."'ORDER BY alert_time DESC";
    $result = mysql_query_or_die($query);
    echo "<table>";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>Notifications</th>";
    echo "<th>Alert Time</th>";
    echo "</tr>";
    while($row = mysql_fetch_array($result))
    {
      echo "<tr><td>Adams-20300".$row['id']."</td>";
      $notification_array = explode(',',$row['alert']);
      //print_r($notification_array);
      echo "<td>";
      foreach ($notification_array as $item)
      {
          echo $item;
          echo "<br>";
      }
      echo "</td>";
      echo "<td>".$row['alert_time']."</td></tr>";
    }
    echo "</table>";
    die(); 
  } 

  // refresh the database by sending a GET request to the agent computer
  if (isset($_POST['refresh'])) {
    include "../config.php";
    $comp_id = $_GET['comp'];
    $ip_query_str = "SELECT ip FROM status WHERE id='".$comp_id."'";
    $ip_query = mysql_query($ip_query_str);
    $row = mysql_fetch_assoc($ip_query);
    $ip = $row['ip'];

    //send GET request to the remote computer:
    $request_str = "http://".$ip."/?refresh=database";
    $response = file_get_contents($request_str);
    if (!$response) {
        $message = "<b style='color:red;'>Refresh failed.</b>";
    }
    else {
        $response = json_decode($response, true);
        $message = "<b style='color:green;'>Refresh successful ".$response["success"].".</b>";
    }
    
  }

  // comp?=ID
  if (isset($_GET['comp'])) {
    $comp_id = $_GET['comp'];
    echo "<center><form action='' method='post'><input type='submit' name='refresh' value='Refresh'></form></center>";
    echo "<center>".$message."</center><br>";
    $connection = mysql_connect('10.22.12.139','root','systemsynq17');
    mysql_select_db('systemsynq');
    echo "<center>
    <body>
    <table border='1'>
    <div id = CenterTableText>
    <tr>
    <th>Name</th>
    <th>OS</th>
    <th>Architecture</th>
    <th>Mac Address</th>
    <th>Cores</th>
    <th>Clock Speed</th>
    <th>Total RAM</th>
    <th>Total DISK</th>
    </tr>";
    $noteconnection = mysql_connect('10.22.12.139','root','systemsynq17');
    mysql_select_db('systemsynq');
    $query = "SELECT * FROM information WHERE ID='".$comp_id."'";
    $result = mysql_query_or_die($query);
    while($row = mysql_fetch_array($result))
    {
       echo "<tr>";
       echo "<td>" . $row['name']. "</td>";
       echo "<td>" . $row['os']. "</td>";
       echo "<td>" . $row['arch']."</td>";
       echo "<td>" . $row['mac']. "</td>";
       echo "<td>" . $row['cores']. "</td>";
       echo "<td>" . $row['clock_speed']." MHz</td>";
       echo "<td>" . $row['ram_total']. "</td>";
       echo "<td>" . $row['disk_total']. "</td>";
    }
    echo "</table>";
    echo "</div>";
    echo "</body>";
    echo "</center>";
    echo "<center>
    <table border='1'>
    <tr>
    <th>CPU Usage</th>
    <th>Available RAM</th>
    <th>Free DISK</th>
    <th>Processes</th>
    <th>Users Logged In</th>
    </tr>";
    $query = "SELECT * FROM running WHERE comp_id='".$comp_id."' ORDER BY row_id desc LIMIT 1";
    $result = mysql_query_or_die($query);
    while($row = mysql_fetch_array($result))
    {
       echo "<tr>";
       echo "<td>" . $row['cpu_usage']. "</td>";
       echo "<td>" . $row['ram_free']."</td>";
       echo "<td>" . $row['disk_free']. "</td>";
       $process_array = explode(',',$row['process']);
       echo "<td>";
       $counter = 0;
       foreach($process_array as $key=> $value)
       {
           echo $value;
           echo "<br>";
	   $counter += 1;
           if ($counter >=2)
           {
               break;
           }
       }
       echo "<a href='203.php?processes=".$comp_id."'>Click here to view all processes</a>";

       echo "<td>" . $row['users_logged']."</td>";
    }
    echo "</table>";
    echo "</div>";
    echo "</center>";


    echo "<center>
    <table border='1'>
    <tr>
    <th>IP Address</th>
    <th>Last Shutdown</th>
    <th>Last Boot</th>
    <th>Uptime</th>
    </tr>";

    $query = "SELECT * FROM status WHERE id='".$comp_id."'";
    $result = mysql_query_or_die($query);
    while($row = mysql_fetch_array($result))
    {
       echo "<tr>";
       echo "<td>" . $row['ip']. "</td>";
       echo "<td>" . $row['last_shutdown']. "</td>";
       echo "<td>" . $row['last_bootup']."</td>";
       #echo "<td>" . date_difference("now",$row['last_bootup'])."</td>";
       echo "<td>";
       $first = new DateTime("now");
       $second = new DateTime($row['last_bootup']);
       $difference = $first->diff($second);
       echo $difference->format("%D days, %H:%I:%S") . "</td>";
    }
    echo "</table>";
    echo "</div>";
    echo "<a href='./203-adv.php?events=".$comp_id."'><font style='impact' size='4'>Event Viewer</font></a>";
    echo "&nbsp&nbsp&nbsp";
    echo "<a href='./203-adv.php?runc=".$comp_id."'><font style='impact' size='4'>Command Prompt</font></a>";
    echo "<br><hr>";
    echo "<img src='https://i.imgur.com/Ribeov8.png' />";
    echo "</center>";
    die();
  }
  echo "<center>
  <table width='100%' border='1'>
  <tr>
  <th>Name</th>
  <th>Alert</th>
  <th>Priority</th>
  <th>On/Off</th>
  </tr>";
  $noteconnection = mysql_connect('10.22.12.139','root','systemsynq17');
  mysql_select_db('systemsynq');
  $query = "SELECT DISTINCT name,id,priority,on_off FROM alerts";
  $result = mysql_query_or_die($query);
  while($row = mysql_fetch_assoc($result))
  {
     $id = $row['id'];
     echo "<tr>";
     //echo "<td>".$row['name']."</td>";
     echo "<td><a href='203.php?comp=".$row['id']."'>".$row['name']."</a></td>";
     echo "<td>";
     $alert_query = "SELECT DISTINCT alert FROM alerts WHERE id ='".$id."'";
     $alert_result = mysql_query($alert_query);
     while($alert_row = mysql_fetch_array($alert_result))
     {
          echo "<font style='color:#FF0000';>".$alert_row['alert']."</font><br>";
     }
     echo "<a href='203.php?notifications=".$row['id']."'>Click here to view all alerts</a>";
     echo "</td>";
     echo "<td>".$row['priority']."</td>";
     //echo "<td>".$row['on_off']." ";
     if ($row['on_off'] == 'Y')
     {
          echo "<td><img src='http://i.imgur.com/EPBh97O.png' style='width:25px;height:25px;'></td>";
     }
     else 
     {
          echo "<td><img src='http://i.imgur.com/HsgUVy3.png' style='width:25px;height:25px;'></td>";
     }
  }
  echo "</table>";
  echo "</center>";
  mysql_close($noteconnection);
?>
</body>
</html>

