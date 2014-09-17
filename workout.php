<?php

include('./core/init.inc.php');

if( $_SESSION['id'] != -1 )
{
	cancelWorkout( $_SESSION['id'], $_SESSION['exercises'] );
	$_SESSION['id'] = -1;
	$_SESSION['name'] = '';
	unset( $_SESSION['exercises'] );
}

if( !isset( $_GET['id'] ) )
{
	header('Location:calendar.php');
}

$workout_ID = $_GET['id'];

$exercises = fetchExercises( $workout_ID );

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
		
		tr.hover td
		{
			cursor:default;
			background-color:#CCCCFF;
		}
	</style>

	<script type="text/javascript" language="javascript" src="./javascripts/jquery-1.8.2.min.js"></script>
	<link el="stylesheet" href="./stylesheets/foundation.css">
	<script type="text/javascript" language="javascript" src="./javascripts/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="./javascripts/dataTables.foundation.js"></script>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			$('#workoutTable').dataTable();
		} );

		$(document).on({
			mouseenter: function () {
				trIndex = $(this).index()+1;
				$("table.dataTable").each(function(index) {
				$(this).find("tr:eq("+trIndex+")").addClass("hover")
				});
			},
			mouseleave: function () {
				trIndex = $(this).index()+1;
				$("table.dataTable").each(function(index) {
				$(this).find("tr:eq("+trIndex+")").removeClass("hover")
				});
			}
		}, ".dataTables_wrapper tr");
		
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
				<dd class="active"><a href="searchWorkouts.php">Find Workouts</a></dd>
				<dd><a href="community.php">Community</a></dd>
				<dd><a href="logout.php">Logout</a>
			</dl>
			<ul class="tabs-content">
				<li class="active" id="Tab">
					<div class="row">
						<div class="twelve columns" align="center"> 
							<table>
								<?php
									$name = fetchWorkoutName( $_GET['id'] );
									echo "<p><strong>{$name[$_GET['id']]}</strong></p>";
								?>

									<thead>
										<tr>
											<th>Exercise</th>
											<th>Type</th>
											<th>Reps (per set)</th>
											<th>Weight (lbs)</th>
											<th>Time (sec)</th>
											<th>Distance (m)</th>
											<th>Sets</th>
										</tr>
									</thead>
									<tbody>
									<?php

									$exerciseTypes = fetchAllExerciseTypes();

									foreach( $exercises as $workoutExercise )
									{
										$exercise = fetchExercise( $workoutExercise['EXERCISE_ID'] );
										$numSets = fetchExerciseSets( $_GET['id'], $workoutExercise['EXERCISE_ID'] );

										//print_r( $sessionExercise[0]['id'] );
										//print_r( "\n" );

										$name = $exerciseTypes[ $exercise[0]['TYPE_ID'] ]['NAME'];
										$type = $exerciseTypes[ $exercise[0]['TYPE_ID'] ]['TYPE'];
										$sets = $numSets[ $workoutExercise['EXERCISE_ID'] ];									
										//print_r( $name );
										//print_r( $type );

										echo "<tr>\n";
										echo "<td>{$name}</td>";
										echo "<td>{$type}</td>";
										echo "<td>{$exercise[0]['REPS']}</td>";
										echo "<td>{$exercise[0]['WEIGHT']}</td>";
										echo "<td>{$exercise[0]['TIME']}</td>";
										echo "<td>{$exercise[0]['DISTANCE']}</td>";
										echo "<td>{$sets}</td>";
										echo "</tr>\n";
									}
									?>
									</tbody>
								</table>
							<?php
							if( isset( $_GET['day'] ) && isset( $_GET['month'] ) && isset( $_GET['year'] ) )
							{
								echo "<a href='scheduleWorkout.php?id={$_GET['id']}&month={$_GET['month']}&day={$_GET['day']}&year={$_GET['year']}' class='button'>Schedule for {$_GET['month']}/{$_GET['day']}/{$_GET['year']}</a>\n";
								echo "<a href='calendar.php?id={$_GET['id']}' class='button'>Schedule for different date</a>\n";
							}
							else
							{
								echo "<a href='calendar.php?id={$_GET['id']}' class='button'>Schedule this workout!</a>\n";
							}	
							?>
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
