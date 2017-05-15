<?php
/**
 * This file displays Windows Event log for a computer. It also allows users
 * to query the Windows Event log for more specific events.
 *
 * @author Mazharul Onim
 */

    include "../config.php";

    /// Needs Updating:
    session_start();
    extract($_COOKIE);
    if ($sessionid==NULL) {
        header("location:../login2.html");
        die();
    }
    ///

    function page_header($title) {
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
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
        <div id="menu-buttons">
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
        </div>
<?php
    } // page_header()

    if (empty($_GET) || (!$_GET['events'] && !$_GET['runc'])) {
        header('Location: ./203.php');
        die();
    }

    else if (isset($_GET['events'])) {
        $comp_id = (int) $_GET['events'];
        if ($comp_id == 0) {   // not a valid comp_id
            header('Location: ./203.php');
            die();
        }

        //query button is pressed:
        if (isset($_POST['query'])) {
            //first get the IP address of the computer:
            $ip = get_ip($comp_id);

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
        page_header("Windows Event Viewer Query");
?>
        <h2 style='text-align:center;'>Windows Event Viewer Query</h2>
        <p>
            <em>Use the form below to query the Windows Event Viewer.</em>
        </p>
        <form action="./203-adv.php?events=<?php echo $comp_id; ?>" method="post" id="events-form">
            <div class="form-part">
                <label>LogFile:</label>
                <select name="logfile">
                    <option selected>System</option>
                    <option>Application</option>
                    <option>Security</option>
                </select>
            </div>
            <div class="form-part">
                <label>Type:</label>
                <select name="evttype">
                    <option disabled selected>Choose here</option>
                    <option>Information</option>
                    <option>Warning</option>
                    <option>Error</option>
                </select>
            </div>
            <div class="form-part">
                <label>EventID:</label>
                <input type="text" name="eventid" />
            </div>
            <div class="form-part">
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

    else if (isset($_GET['runc'])) {
        $comp_id = (int) $_GET['runc'];
        if ($comp_id == 0) {   // not a valid comp_id
            header('Location: ./203.php');
            die();
        }

        //Run button is pressed:
        if (isset($_POST['run'])) {
            //first get the IP address of the computer:
            $ip = get_ip($comp_id);

            //send GET request to the remote computer:
            $cmd = $_POST['command'];
            $request_str = "http://".$ip."/?cmd=".urlencode($cmd);
            $response = file_get_contents($request_str);
            if (!$response) {
                $query_msg = "<b style='color:red;'>Command failed: Unexpected error occurred</b>";
            }
            else {
                $response = json_decode($response, true);
                $cmd_output = $response["success"];
                $cmd_output = str_replace("{newline}", "<br>", $cmd_output);
                $cmd_output = trim($cmd_output, "<br>");  //remove any leading whitespace
            }
        } // isset($_POST['run'])

        page_header("Windows Command Prompt");
?>
        <h2 style='text-align:center;'>Remote Windows Command Prompt</h2>
        <div class="left-side">
            <p>
                <em>Use the form below to run a command in the remote computer</em><br>
            </p>
            <p>
                <b>Only the commands listed in right can be used.</b>
            </p>
            <form action="./203-adv.php?runc=<?php echo $comp_id; ?>" method="post" id="events-form">
                <div class="form-part">
                    <label>Command:</label>
                    <input type="text" name="command" required />
                </div>
                <div class="form-part">
                    <button type="submit" name="run" value="<?php echo $comp_id; ?>">Run</button>
                </div>
            </form>
            <p>
                <?php echo $query_msg; ?>
            </p>
            <hr />
            <p>
                <label class="output-label">Output:</label>
                <div class="command-output">
                    <?php echo $cmd_output; ?>
                </div>
            </p>
        </div>
        <div class="right-side">
            <p><b>Short description of the available commands:</b></p>
            <p><em>cd</em>
                   - changes directories
            </p>
            <p><em>comp</em>
                   - compares files
            </p>
            <p><em>copy</em>
                   - copy one or more files to an alternate location
            </p>
            <p><em>date</em>
                   - view or change systems date
            </p>
            <p><em>dir</em>
                   - list the contents of one or more directory
            </p>
            <p><em>echo</em>
                   - displays messages and enables and disables echo
            </p>
            <p><em>find</em>
                   - search for text within a file
            </p>
            <p><em>findstr</em>
                   - searches for a string of text within a file
            </p>
            <p><em>hostname</em>
                   - displays the host name portion of the full computer name
            </p>
            <p><em>ipconfig</em>
                   - network command to view network adapter settings and assigned values
            </p>
            <p><em>mkdir</em>
                   - command to create a new directory
            </p>
            <p><em>more</em>
                   - display one page at a time
            </p>
            <p><em>nslookup</em>
                   - look up an IP address of a domain or host on a network
            </p>
            <p><em>ping</em>
                   - test and send information to another network computer or network device
            </p>
            <p><em>robocopy</em>
                   - a robust file copy command for the Windows command line
            </p>
            <p><em>time</em>
                   - view or modify the system time
            </p>
            <p><em>tracert</em>
                   - visually view a network packets route across a network
            </p>
            <p><em>wmic</em>
                   - Windows Management Instrumentation command line
            </p>
        </div>

<?php
    } // isset($_GET['runc'])

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

    function get_ip($comp_id) {
        $ip_query_str = "SELECT ip FROM status WHERE id='".$comp_id."'";
        $ip_query = mysql_query($ip_query_str);
        $row = mysql_fetch_assoc($ip_query);
        return $row['ip'];
    } //get_ip()

?>
    </body>
</html>

