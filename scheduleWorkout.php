<?php

include('./core/init.inc.php');

if( isset( $_GET['id'] ) && isset( $_GET['month'] ) && isset( $_GET['day'] ) && isset( $_GET['year'] ))
{
	$date = $_GET['month'] . '/' . $_GET['day'] . '/' . $_GET['year'];

	scheduleWorkout( $_GET['id'], $_SESSION['user'], $date );
	header("Location:calendar.php?month={$_GET['month']}&year={$_GET['year']}");
}
else
{
	header("Location:calendar.php");
}

?>
