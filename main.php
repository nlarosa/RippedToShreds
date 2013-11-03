<?php

include('./core/init.inc.php');

$users = fetchUsers();

if( isset($_POST['email']) && isset($_POST['weight']) )
{
	updateWeight( $_POST['email'], $_POST['weight'] );
	header("Location: main.php");
	die();
}

?>

<!DOCTYPE html>

<head>
	<meta charset="utf-8" />
	<!-- Set the viewport width to device width for mobile -->

	<title>Ripped to Shreds</title>
	
	<link rel="icon" type="img/ico" href="favicon.ico">

	<style type="text/css">
		@import "./stylesheets/foundation.min.css";
		@import "./stylesheets/dataTables.foundation.css";
	</style>
	
	<script type="text/javascript" language="javascript" src="./javascripts/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" language="javascript" src="./javascripts/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="./javascripts/dataTables.foundation.js"></script>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			$('#example').dataTable();
		} );
	</script>

</head>

<body>
	<div class="row"> 
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

	<div class="row">
		<div class="twelve columns">
			<dl class="tabs">
				<dd><a href="#calendar">Calendar</a></dd>
				<dd><a href="#workouts">Your Workouts</a></dd>
				<dd class="active"><a href="#accounts">Find Others</a></dd>
				<dd><a href="logout.php">Logout</a>
			</dl>
			<ul class="tabs-content">
				<li id="calendarTab">
				</li>	
				<li id="workoutsTab">
				</li>
				<li class="active" id="accountsTab">
					<div class="row">
						<div class="large-12 columns" align="center"> 
							<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
							<thead>
								<tr>
									<th>Email</th>
									<th>Name</th>
									<th>Age (years)</th>
									<th>Height (inches)</th>
									<th>Weight (lbs)</th>	
									<th>Workouts</th>
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
									echo "\t<td><a href=\"workouts.php?user={$user['EMAIL']}\">Link</a></td>\n";
									echo "\t</tr>\n";
								}
							?>
							
							</tbody>	
							</table>			
						</div>
						<form method="POST">
						<div class="row">
							<div class="one column">
							</div>
							<div class="six columns">
								<div class="row">
									<div class="three columns">
										<label class="right inline">Email</label>
									</div>
									<div class="nine columns">
										<input type="text" name="email"> 
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="one column">
							</div>
							<div class="six columns">
								<div class="row">
									<div class="three columns">
										<label class="right inline">New Weight</label>
									</div>
									<div class="nine columns">
										<input type="text" name="weight" />
										<button type="submit" class="button">Update Weight</button>
									</div>
								</div>
							</div>
						</div>
						</form>	
					</div>
				</li>	
			</ul>
		</div>
		<hr />
	</div>

	<!-- Included JS Files (Compressed) -->
	<script src="javascripts/jquery.js"></script>
	<script src="javascripts/foundation.min.js"></script>
  
	<!-- Initialize JS Plugins -->
	<script src="javascripts/app.js"></script>

	<script>
		$(window).load(function(){
		$("#featured").orbit();
		});
	</script> 

	<footer align="center">
		Copyright 2013 - The Fireballs
	</footer>

</body>

</html>
