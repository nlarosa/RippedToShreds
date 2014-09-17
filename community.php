<?php

include('./core/init.inc.php');

if( $_SESSION['id'] != -1 )
{
	cancelWorkout( $_SESSION['id'], $_SESSION['exercises'] );
	$_SESSION['id'] = -1;
	$_SESSION['name'] = '';
	unset( $_SESSION['exercises'] );
}

$users = fetchUsers();

if( isset($_POST['email']) && isset($_POST['weight']) )
{
	updateWeight( $_POST['email'], $_POST['weight'] );
	header("Location: search.php");
	die();
}

?>

<!DOCTYPE html>

<head>
	<meta charset="utf-8" />
	<!-- Set the viewport width to device width for mobile -->

	<title>Ripped to Shreds</title>
	
	<link rel="icon" type="img/ico" href="favicon.ico">

		<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			$('#accountTable').dataTable();
		} );
	</script>

	<style type="text/css">
		@import "./stylesheets/docs.css";
		@import "./stylesheets/foundation.min.css";
		@import "./stylesheets/dataTables.foundation.css";
		body
                {
                        background: url(./images/america.jpg) no-repeat center center fixed;
                        -webkit-background-size: cover;
                        -moz-background-size: cover;
                        -o-background-size: cover;
                        background-size: cover;
                }
	</style>

	<script type="text/javascript" language="javascript" src="./javascripts/jquery-1.8.2.min.js"></script>
	<link el="stylesheet" href="./stylesheets/foundation.css">
	<script type="text/javascript" language="javascript" src="./javascripts/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="./javascripts/dataTables.foundation.js"></script>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			$('#accountTable').dataTable();
		} );
	</script>
</head>

<body>
	<div class="row" style="background-color:white"> 
		<div class="one column">
		</div>	
		<div class="five columns" align="center" style="background-color:#CCCCFF; margin:2.5% 0%;">
			<h3>Ripped to Shreds</h3>
		</div>
		<div class="five columns" align="center" style="background-color:#FFCCCC; margin:2.5% 0%;">
			<h3>Workout Management</h3>
		</div>
		<div class="one column">
		</div>
		<hr />
	</div>

	<div class="row" style="background-color:white">
		<div class="twelve columns">
			<dl class="tabs">
				<dd><a href="calendar.php">Calendar</a></dd>
				<dd><a href="createWorkout.php">Create Workout</a></dd>
				<dd><a href="yourAccount.php">Your Account</a></dd>	
				<dd><a href="searchWorkouts.php">Find Workouts</a></dd>
				<dd class="active"><a href="community.php">Community</a></dd>
				<dd><a href="logout.php">Logout</a>
			</dl>
			<ul class="tabs-content">
				<li class="active" id="Tab">
					<div class="row">
						<div class="twelve columns" align="center"> 
							<table cellpadding="0" cellspacing="0" border="0" class="display" id="accountTable" width="100%">
								<thead>
									<tr>
										<th>Email</th>
										<th>Name</th>
										<th>Age</th>
										<th>Height</th>
										<th>Weight</th>
									</tr>
								</thead>
								<tbody>
								<?php
								foreach( $users as $user )
								{
									echo "\t<tr>\n";
									echo "\t<td>{$user['EMAIL']}</td>\n";
									echo "\t<td>{$user['LASTNAME']}, {$user['FIRSTNAME']}</td>\n";
									echo "\t<td>{$user['AGE']}</td>\n";
									echo "\t<td>{$user['HEIGHT']}</td>\n";
									echo "\t<td>{$user['WEIGHT']}</td>\n";
									echo "\t</tr>\n";
								}
								?>
								</tbody>
							</table>
						</div>
					</div>
				</li>	
			</ul>
		</div>
		<hr />

	<footer align="center">
		Copyright 2013 - The Fireballs
	</footer>

	<br><br>
	</div>
	
</body>

</html>
