<?php
 	function query($query)
	{
		$database = mysql_connect("localhost","root","systemsynq17");
		if (!$database)
		{
			die("Unable to connect to database! </body></html>");
		}
		if (!mysql_select_db("systemsynq",$database))
		{
			die("Unable to open database! </body></html>");
		}
		if (!$result = mysql_query($query,$database))
		{
			print("<br />".mysql_errno($database)."<br />");
			print("<br />".mysql_error($database)."<br />");
			die("Unable to query database! </body></html>");
		}
		return $result;
	}
?>

