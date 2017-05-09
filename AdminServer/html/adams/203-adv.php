<?php
    if (empty($_GET) || is_null($_GET['events'])) {
        header('Location: ../menu.php');
    }
?>
<!DOCTYPE html>
<html>
    <head>
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
        <form action="" method="GET" id="events-form">
            <div class="event-option">
                <label>LogFile:</label>
                <select>
                    <option selected>System</option>
                    <option>Application</option>
                    <option>Security</option>
                </select>
            </div>
            <div class="event-option">
                <label>Type:</label>
                <select>
                    <option disabled selected>Choose here</option>
                    <option>Information</option>
                    <option>Warning</option>
                    <option>Error</option>
                </select>
            </div>
            <div class="event-option">
                <label>EventID:</label>
                <input type="text" />
            </div>
            <div class="event-option">
                <input type="submit" />
            </div>
        </form>
        <hr />

<?php
    function table_header() {
        global $file;
        $last_updated = $file[0];
        $query_str = $file[1];
        $table_headers = explode(',', $file[2]);

        echo "<p><u>Last updated</u>: ".$last_updated."</p>";
        echo "<p><u>Query</u>: <b>".$query_str."</b></p>";
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
        $id = $_GET['events'];
        $page = $_GET['page'];
        if (!$page) $page = 1;
        $prev_link = $_SERVER['PHP_SELF']."?events=".$id."&page=".($page-1);
        $next_link = $_SERVER['PHP_SELF']."?events=".$id."&page=".($page+1);
        #$previous_link = "./test.php?events=1&page=".($page-1);
        #$next_link = "./test.php?events=1&page=".($page+1);
?>
        </table>
        <p style="text-align:center;">
<?php
        if ($_GET['page'] > 1) {
?>
            <a href="<?php echo $prev_link; ?>">Previous</a>  
<?php
        }
?>
            <a href="<?php echo $next_link; ?>">Next</a>
        </p>
<?php
    } // table_footer()

    function print_next_rows() {
        global $file;
        $page = $_GET['page'];
        if (!$page) $page = 1;
        $start = 3+25*($page-1);
        for ($i = $start; $i < $start+25; $i++) {
            $row = explode(',', $file[$i]);
            $message = $row[4];
            $message = str_replace("{newline}", "<br>", $message);
?>
        <tr>
            <td id="logfile-col"><?php echo $row[0]; ?></td>
            <td id="level-col"><?php echo $row[1]; ?></td>
            <td id="time-col"><?php echo $row[2]; ?></td>
            <td id="eventid-col"><?php echo $row[3]; ?></td>
            <td id="message-col"><?php echo substr($message,0,70)."<span style='display:none;'>".substr($message,70)."</span> <span onclick=toggleDisplay(this) class='show-more'>[more]</span>"; ?></td>
        </tr>
<?php
        }
    } //print_next_rows

    if (isset($_GET['events'])) {
        $comp_id = $_GET['events'];
        $filename = "./../../event-uploads/events-".$comp_id.".txt";
        if (file_exists($filename)) {
            $file = file("./../../event-uploads/events-".$comp_id.".txt");
            table_header();
            print_next_rows();
            table_footer();
        } else {
            echo "<b>No Events found for this computer.</b>";
        }
    }
?>
    </body>
</html>

