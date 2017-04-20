<?php
  session_start();
  extract($_COOKIE);
  setcookie("sessionid",$sessionid,time()-3600);
  var_dump($_COOKIE);
  header("location:./login2.html");
  die;
?>
