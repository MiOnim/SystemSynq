<?php
/**
 * This file sends periodic heartbeat checks to all the computers registered
 * in the database.
 *
 * This file cannot be read by others. It can only run by 'root'
 *
 * @author Mazharul Onim
 */

    include "config.php";

    // get IP of the computers:
    $query = "SELECT id,ip FROM status";
    $res = mysql_query($query);
    $ips = array();
    while ($row = mysql_fetch_assoc($res)) {
        $ips[$row['id']] = $row['ip'];
    }

    //it should keep on checking the status of the computers:
    while (true) {
        foreach ($ips as $id=>$ip) {
            system("ping -c2 -W1 ".$ip." > /dev/null", $retval);
            //$retval stores the status code of the system call
            //$ertval = 0 if host is up, and a non-zero number if host is down
            $on_off = $retval ? 'N' : 'Y';
            update_database($id, $on_off);
        }
    }

    function update_database($id, $on_off) {
        $query = "UPDATE alerts SET on_off='".$on_off."' WHERE id='".$id."'";
        $res = mysql_query($query);
        if (!$res) {
            die("Query: '".$query."' failed. ".mysql_error());
        }
    }
?>

