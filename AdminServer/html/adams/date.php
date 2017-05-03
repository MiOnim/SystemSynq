<?php
	function date_difference($first_date, $second_date)
	{
		$difference = $first_date->diff($second_date);
		print $difference->format("%H:%I:%S");
	}
        //print "this is now: ";
	//echo strtotime("now"), "\n"; // now
	//$first = strtotime("now");
        //print "this is 2017/04/22 06:14:38: ";
	//echo strtotime("2017/04/22 06:14:38"), "\n"; // test
	//$second = strtotime("2017/04/22 06:14:38");
	$first = new DateTime("2015/03/21 06:14:38");
	$second = new DateTime("now");
	$difference = $first->diff($second);
	print $difference->format ("%Y years, %M months, %D days. %H:%I:%S");
	//$difference = $first->diff($second);
	//echo $difference->y . $difference->m . $difference->d . $difference->h;
	//echo $first;
	//$difference = $first - $second;
	//print "this is the difference in time stamp: ";
	//echo $difference;
	//echo date('m/d/Y',$difference);
?>
