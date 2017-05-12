<?php
/**
   This file displays Windows Event log for a computer. It also allows users
   to query the Windows Event log for more specific events.

   @author Mazharul Onim
 */

    include "../config.php";

    if (empty($_GET) || (!$_GET['events'] && !$_GET['query'])) {
        header('Location: ./203.php');
        die();
    }

    if (isset($_GET['events'])) {
        $comp_id = htmlspecialchars($_GET['events']);
        if (!is_numeric($comp_id)) {
            header('Location: ./203.php');
            die();
        }

        //query button is pressed:
        if (isset($_POST['query'])) {
            //first get the IP address of the computer:
            $ip_query_str = "SELECT ip FROM status WHERE id='".$comp_id."'";
            $ip_query = mysql_query($ip_query_str);
            $row = mysql_fetch_assoc($ip_query);
            $ip = $row['ip'];

            //send GET request to the remote computer:
            $request_str = "http://".$ip."/?refresh=events";
            $request_str .= "&logfile=".$_POST['logfile'];
            if (isset($_POST['evttype'])) {
                $request_str .= "&evttype=".$_POST['evttype'];
            }
            if (isset($_POST['eventid'])) {
                $request_str .= "&eventid=".$_POST['eventid'];
            }
            $response = file_get_contents($request_str);
            if (!$response) {
                $query_msg = "<b style='color:red;'>Query failed: Invalid query input.</b>";
            }
            else {
                $response = json_decode($response, true);
                $query_msg = "<b style='color:green;'>Query successful: ".$response["success"]."</b>";
            }
        } // isset($_POST['query'])

        $filename = "./../../event-uploads/events-".$comp_id.".txt";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Windows Event Viewer Query</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <script>
            function toggleDisplay(ele) {
                if (ele.innerHTML == "[more]") {
                    ele.innerHTML = "[less]";
                    ele.previousElementSibling.style.display = "";
                }
                else {
                    ele.innerHTML = "[more]";
                    ele.previousElementSibling.style.display = "none";
                }
            }
        </script>
    </head>
    <body>
        <h2 style='text-align:center;'>Windows Event Viewer Query</h2>
        <p>
            <em>Use the form below to query the Windows Event Viewer.</em>
        </p>
        <form action="./203-adv.php?events=<?php echo $comp_id; ?>" method="post" id="events-form">
            <div class="event-option">
                <label>LogFile:</label>
                <select name="logfile">
                    <option selected>System</option>
                    <option>Application</option>
                    <option>Security</option>
                </select>
            </div>
            <div class="event-option">
                <label>Type:</label>
                <select name="evttype">
                    <option disabled selected>Choose here</option>
                    <option>Information</option>
                    <option>Warning</option>
                    <option>Error</option>
                </select>
            </div>
            <div class="event-option">
                <label>EventID:</label>
                <input type="text" name="eventid" />
            </div>
            <div class="event-option">
                <button type="submit" name="query" value="<?php echo $comp_id; ?>">Query</button>
            </div>
        </form>
        <p>
            <?php echo $query_msg; ?>
        </p>
        <hr />

<?php
    //isset($_GET['events']):
        if (file_exists($filename)) {
            $file = file("./../../event-uploads/events-".$comp_id.".txt");
            table_header();
            print_next_rows();
            table_footer();
        } else {
            echo "<b>No Events found for this computer.</b>";
        }
    } // isset($_GET['events'])

    function table_header() {
        global $file;
        $len = count($file);
        $num_events = $len - 3;
        $page = $_GET['page'];
        if (!$page) $page = 1;

        $last_updated = $file[0];
        $query_str = $file[1];
        $table_headers = explode(',', $file[2]);

        echo "<p><u>Last updated</u>: ".$last_updated."</p>";
        echo "<p><u>Query</u>: <b>".$query_str."</b></p>";
        echo "<p style='color:#808080;'>Showing ".(1+($page-1)*25)." to ".
              min($num_events, $page*25)." of ".$num_events." events</p>";
?>
    <table id="events-table">
        <tr>
            <th id="logfile-col"><?php echo $table_headers[0]; ?></th>
            <th id="level-col"><?php echo $table_headers[1]; ?></th>
            <th id="time-col"><?php echo $table_headers[2]; ?></th>
            <th id="eventid-col"><?php echo $table_headers[3]; ?></th>
            <th id="message-col"><?php echo $table_headers[4]; ?></th>
        </tr>
<?php
    } // table_header()

    function table_footer() {
        global $file;
        global $comp_id;

        $num_events = count($file)-3;
        $page = $_GET['page'];
        if (!$page) $page = 1;
        $prev_link = $_SERVER['PHP_SELF']."?events=".$comp_id."&page=".($page-1);
        $next_link = $_SERVER['PHP_SELF']."?events=".$comp_id."&page=".($page+1);
?>
        </table>
        <p style="text-align:center;">
<?php
        if ($_GET['page'] > 1) {
?>
            <a href="<?php echo $prev_link; ?>">Previous</a>  
<?php
        } // display 'previous' link
        if ($num_events > (25*$page)) {
?>
            <a href="<?php echo $next_link; ?>">Next</a>
<?php
        } // display 'next' link
?>
        </p>
<?php
    } // table_footer()

    function print_next_rows() {
        global $file;
        $len = count($file);

        $page = $_GET['page'];
        if (!$page) $page = 1;
        $start = 3+25*($page-1);
        for ($i = $start; $i < min($len, $start+25); $i++) {
            $row = explode(',', $file[$i]);
            $message = $row[4];
            $message = str_replace("{newline}", "<br>", $message);
?>
        <tr>
            <td id="logfile-col"><?php echo $row[0]; ?></td>
            <td id="level-col"><?php echo $row[1]; ?></td>
            <td id="time-col"><?php echo $row[2]; ?></td>
            <td id="eventid-col"><?php echo $row[3]; ?></td>
<?php
            if (strlen($message) > 70) {
            //display part of the message with a 'more' link
?>
            <td id="message-col"><?php echo substr($message,0,70)."<span style='display:none;'>".substr($message,70)."</span> 
                     <span onclick=toggleDisplay(this) class='show-more'>[more]</span>"; ?></td>
<?php
            } else {
            //display the entire message at once
?>
            <td id="message-col"><?php echo $message; ?></td>
<?php
            } // put message in table
?>
        </tr>
<?php
        } // for loop
    } //print_next_rows
?>
    </body>
</html>

