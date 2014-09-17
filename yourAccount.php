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

if( isset($_SESSION['user']) && ( !empty($_POST['weight']) || !empty($_POST['height']) || !empty($_POST['age']) || !empty($_POST['dailyCalories'])) )
{
		updateWeight( $_SESSION['user'], $_POST['weight'] );
		updateHeight( $_POST['height'] );
		updateAge( $_POST['age'] );
		updateCalories( $_POST['dailyCalories'] );
		header("Location: yourAccount.php");
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
				<dd class="active"><a href="yourAccount.php">Your Account</a></dd>
				<dd><a href="searchWorkouts.php">Find Workouts</a></dd>
				<dd><a href="community.php">Community</a></dd>
				<dd><a href="logout.php">Logout</a>
			</dl>
			<ul class="tabs-content">
				<li class="active" id="Tab">
					<div class="row">
						<dl class="tabs pill">
							<dd class="active"><a href="yourAccount.php">Personal Details</a></dd>
							<dd><a href="yourWorkouts.php">Your Workouts</a></dd>
						</dl>
						<ul class="tabs-content">
							<li class="active" id="Tab">
								<div class="row">

									<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
										<thead>
											<tr>
												<th>Name</th>
												<th>Age (years)</th>
												<th>Height (inches)</th>
												<th>Weight (lbs)</th>
												<th>Calories/day</th>
											</tr>
										</thead>

										<tbody>
											<img src="http://i904.photobucket.com/albums/ac249/azebrow1/ScreenShot2013-12-03at54047PM_zpsab0c2e6f.png"/>

<?php
foreach( $users as $user )
{
		if ($user["EMAIL"] == $_SESSION['user']){
				echo "\t<tr>\n";
				echo "\t<td>{$user['FIRSTNAME']} {$user['LASTNAME']}</td>\n";
				echo "\t<td>{$user['AGE']}</td>\n";
				echo "\t<td>{$user['HEIGHT']}</td>\n";
				echo "\t<td>{$user['WEIGHT']}</td>\n";
				echo "\t<td>{$user['DAILYCALORIES']}</td>\n";
				echo "\t</tr>\n";
		}
}
?>

										</tbody>
									</table>
								</div>

								 <form method="POST">
									<div class="row">
													<br> 
								<div class="six columns">
																<div class="row">
																	<div class="four columns">      
																				<label class="left inline">New Weight</label>
																	</div>
																<div class="eight columns">
																				<input type="text" name="weight" />
																		</div>
								</div>
								<div class="row"> 
									<div class="four columns">
																			<label class="left inline">New Height</label>
																</div>
											<div class="eight columns">
												<input type="text" name="height" />       
									</div>
								</div>
								<div class="row">	
																	<div class="four columns">
												<label class="left inline">New Age</label>
																		</div>
																		<div class="eight columns">
																				<input type="text" name="age" />
																		</div>
								</div>
								<div class="row">
																	<div class="four columns">
											<label class="left inline">New Calories/day</label>
																		</div>
																		<div class="eight columns">
																				<input type="text" name="dailyCalories" />
										<button type="submit" class="button">Update!</button>
																		</div>
															   </div> 
														</div>
							<div class="six columns">
														</div>
												</div>
												</form>


									</div>
								</li>
							</ul>
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
