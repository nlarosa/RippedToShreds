<?php

include('./core/init.inc.php');

if( $_SESSION['id'] != -1 )
{
	cancelWorkout( $_SESSION['id'], $_SESSION['exercises'] );
	$_SESSION['id'] = -1;
	$_SESSION['name'] = '';
	unset( $_SESSION['exercises'] );
}

session_start();
$_SESSION = array();
session_destroy();
header('Location: index.php');

?>

