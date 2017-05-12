<?php
  session_start();
  $database = mysql_connect("10.22.12.139","root","systemsynq17");
  mysql_select_db('systemsynq');
  extract($_POST);
  $query = "UPDATE settings SET min_free_disk='".$disk_space."', min_free_ram='".$free_ram."', max_process='".$total_processes."'";
  $result = mysql_query($query);
  header("location:settings.php");
?>
