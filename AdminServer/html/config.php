<?php
    $host = "localhost";
    $username = "root";
    $password = "systemsynq17";
    $db = "systemsynq";
    
    $link = mysql_connect($host, $username, $password);
    if (!$link) {
        die("Could not connect to server. MySQL error: ".mysql_error($link));
    }
    $select_db = mysql_select_db($db);
    if (!$select_db) {
        die("Could not open database. MySQL error: ".mysql_error($link));
    }

    define('sql_error_reporting', true);
?>

