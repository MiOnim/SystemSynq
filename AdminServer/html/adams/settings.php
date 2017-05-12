<html>
<body>
<center>
<h1> Configure Alert Settings </h1>
<?php
    $connection = mysql_connect('10.22.12.139','root','systemsynq17');
    mysql_select_db('systemsynq');
    $query = "SELECT * FROM settings";
    //echo "<h2>".$query."</h2>";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result)
    ?>
        <form action="./update_settings.php" method="post">
            <label><b>Disk Space < </b></label>
            <input type="text" placeholder="<?php echo $row['min_free_disk'];?>" name="disk_space">
          <br>
            <label><b>Free RAM < </b></label>
            <input type="text" placeholder="<?php echo $row['min_free_ram'];?>" name="free_ram">
          <br>    
            <label><b>Total Processes > </b></label>
            <input type="text" placeholder="<?php echo $row['max_process'];?>" name="total_processes">
          <br>
          <button type="submit">Update</button>
       </form>
    <?php
    die();
    ?>
</center>
</body>
</html>
