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
    <th>ID</th>
    <th>CPU Cores</th>
    <th>Clock Speed</th>
    <th>CPU Temperature</th>
    <th>CPU Usage</th>
    <th>RAM Total</th>
    <th>RAM Free</th>
    <th>DISK Total</th>
    <th>DISK Free</th>
    <th>On or Off</th>
  </tr>
  <tr>
    <td>200-001</td>
    <td>4</td>
    <td>3.2GHz</td>
    <td>45C</td>
    <td>25%</td>
    <td>8096MB</td>
    <td>6080MB</td>
    <td>1024GB</td>
    <td>512GB</td>
    <td>On</td>
  </tr>
  <tr>
    <th>Uptime</th>
    <th>Recent Shutdown</th>
    <th>Recent Logins</th>
    <th>USB Devices</th>
    <th>Logged in?</th>
    <th>Command</th>
    <th>Time of Issue</th>
    <th>Processes Total</th>
    <th>Processes Unusual</th>
  </tr>
  <tr>
    <td>500 Minutes</td>
    <td>2017-02-10</td>
    <td>2017-02-10</td>
    <td>Mouse/Keyboard</td>
    <td>User Logged in</td>
    <td>Remote Shutdown</td>
    <td>12:34</td>
    <td>18</td>
    <td>malware.exe</td>
  </tr>
  
</table>
</center>
</body>
</html>

