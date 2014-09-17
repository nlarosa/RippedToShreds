<?php

session_start();

// pages that are accessible if not logged in
$exceptions = array('index');

$page = substr(end(explode('/', $_SERVER['SCRIPT_NAME'])), 0, -4);	// get current page

if( in_array($page, $exceptions) === false ) 
{
	if( isset($_SESSION['user']) === false ) 			// user not logged in
	{
		//header('Location: index.php');
		//die();
	}
}

$DB = oci_connect('nlarosa', 'Nick1642!', '//localhost/curt');

$path = dirname(__FILE__);						// actual file
include("{$path}/inc/account.inc.php");
include("{$path}/inc/calendar.inc.php");
include("{$path}/inc/workout.inc.php");

?>
