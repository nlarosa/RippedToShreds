<?php

include('./core/init.inc.php');

//print_r( $_SESSION );

// function isDuplicate()

$errors = array();

if( isset($_POST['workoutName']) && $_SESSION['id'] == -1 && $_POST['sub'] == 'Submit Exercise' )		// first exercise creation
{
		if( empty($_POST['workoutName']) )
		{
				$errors[] = 'Please provide a workout name.';
		}
		else
		{	
				if( createWorkoutID( $_SESSION['user'], $_POST['workoutName'] ) ) {}
				else
				{
						$errors[] = 'Error creating the workout instance.';
				}
		}
}
elseif( isset($_POST['workoutName']) && $_SESSION['id'] != -1 && $_POST['sub'] != 'Clear Workout' )		// submit exercise or workout
{
		if( empty($_POST['workoutName']) )
		{
				$errors[] = 'Please provide a workout name.';
		}
		elseif( strcmp( $_POST['workoutName'], $_SESSION['name'] ) != 0 ) 	// changed workout name
		{
				editWorkoutName( $_POST['workoutName'], $_SESSION['id'] );
				$_SESSION['name'] = $_POST['workoutName'];
		}
}

if( isset($_POST['workoutName']) )							// form submitted
{
		if( !validDate( $_POST['inDate'] ) )
		{
				$errors[] = 'Please provide a valid date.';
		}

		if( $_POST['sub'] == 'Submit Workout' )		// submit workout 
		{
				if( count($_SESSION['exercises']) > 0 )						// only if there is more than one workout
				{
						//	print_r( $_SESSION['id'] );	
						if( scheduleWorkout( $_SESSION['id'], $_SESSION['user'], $_POST['inDate'] ) != 0 )
						{
								connectExercises( $_SESSION['id'], $_SESSION['exercises'] );
								$_SESSION['id'] = -1;
								$_SESSION['name'] = '';
								unset( $_SESSION['exercises'] );
								header('Location: calendar.php');				// workout was successfully scheduled
								die();
						}
						else
						{
								$errors[] = 'Workout submit failed.';
						}
				}
		}
		elseif( $_POST['sub'] == 'Clear Workout' )						// clear the workout information
		{
				cancelWorkout( $_SESSION['id'], $_SESSION['exercises'] );
				$_SESSION['id'] = -1;
				$_SESSION['name'] = '';
				unset( $_SESSION['exercises'] );
				header('Location: createWorkout.php');
				die();
		}

		if( empty($_POST['workoutName']) && empty($_POST['inDate']) && ( $_POST['sub'] == 'Submit Workout' || $_POST['sub'] == 'Submit Exercise' )  )
		{
				$errors[] = 'Please specify a workout name and date.';
		}

		if( !empty($_POST['weight']) && !empty($_POST['reps']) && !empty($_POST['sets']) && !empty($_POST['eType']) )			// submit a lift
		{
				//print_r( "conditional 1");
				if( createExercise( null, $_POST['reps'], $_POST['weight'], null, null, $_POST['eType'], $_POST['sets'] ) ) {}
				else
				{
						$errors[] = 'Please fill in table with appropriate data.';
				}
		}
		elseif( ( !empty($_POST['time']) || !empty($_POST['distance']) ) && !empty($_POST['sets']) && !empty($_POST['eType']) )		// submit cardio
		{
				//print_r( "conditional 2");
				if( createExercise( $_POST['distance'], null, null, $_POST['time'], null, $_POST['eType'], $_POST['sets'] ) ) {}
				else
				{
						$errors[] = 'Please fill in table with appropriate data.';
				}
		}
		elseif( ( !empty($_POST['reps']) || !empty($_POST['time']) ) && !empty($_POST['sets']) && !empty($_POST['eType']) )		// submit calisthenics
		{
				//print_r( "conditional 3");
				if( createExercise( null, $_POST['reps'], null, $_POST['time'], null, $_POST['eType'], $_POST['sets'] ) ) {}
				else
				{
						$errors[] = 'Please fill in table with appropriate data.';
				}
		}
		elseif( !empty($_POST['name']) && ( !empty($_POST['weight']) || !empty($_POST['reps']) || !empty($_POST['time']) || !empty($_POST['distance']) ) && !empty($_POST['sets']) && !empty($_POST['eType']) )	// submit other
		{
				//print_r( "conditional 4");
				//createExerciseType( $_POST['name'], 'Other', null, null, null, null );
				if( createExercise( $_POST['distance'], $_POST['reps'], $_POST['weight'], $_POST['time'], null, $_POST['eType'], $_POST['sets'] ) ) {}
				else
				{
						$errors[] = 'Please fill in table with appropriate data.';
				}
		}
		elseif( $_POST['sub'] == 'Submit Exercise' )		// error
		{
				//print_r( "conditional 5");
				$errors[] = 'Please fill in the necessary exercise fields and select your exercise type.';
		}

		//	echo '<script type="text/javascript"> location.reload(); </script>';
}

//print_r( $_POST );

?>

<!DOCTYPE html>

<head>
	<meta charset="utf-8" />
	<!-- Set the viewport width to device width for mobile -->

	<title>Ripped to Shreds</title>

	<link rel="icon" type="img/ico" href="favicon.ico">

	<style type="text/css">
		@import "./stylesheets/docs.css";
		@import "./stylesheets/foundation.css";

		.tabs dd a, .tabs li a { color: #000000; }

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
	<script type="text/javascript" language="javascript" src="./javascripts/modernizr.foundation.js"></script>
	<script type="text/javascript" language="javascript" src="./javascripts/foundation.min.js"></script>

<script>
//$(document).foundation();

$(document).ready( function() {

		$(".liftFields").hide();
		$("#liftExercises").hide();

		$(".cardioFields").hide();
		$("#cardioExercises").hide();

		$(".calisFields").hide();
		$("#calisExercises").hide();

		$(".otherFields").hide();
		$("#otherExercises").hide();

		$("#subExercise").hide();

		$("#lift").click( function(){
				$('.fields').hide();
				$('.liftFields').show();
				$('#liftExercises').show();
				$('#subExercise').show();
		});

		$("#cardio").click( function(){
				$('.fields').hide();
				$('.cardioFields').show();
				$('#cardioExercises').show();
				$('#subExercise').show();
		});

		$("#calis").click( function(){
				$('.fields').hide();
				$('.calisFields').show();
				$('#calisExercises').show();
				$('#subExercise').show();
		});	

		$("#other").click( function(){
				$('.fields').hide();
				$('.otherFields').show();
				$('#otherExercises').show();
				$('#subExercise').show();
		});
});	

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
				<dd class="active"><a href="createWorkout.php">Create Workout</a></dd>
				<dd><a href="yourAccount.php">Your Account</a></dd>
				<dd><a href="searchWorkouts.php">Find Workouts</a></dd>
				<dd><a href="community.php">Community</a></dd>
				<dd><a href="logout.php">Logout</a>
			</dl>
			<ul class="tabs-content">
				<li class="active" id="Tab">
					<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="topForm" enctype="multipart/form-data">
						<div class="row">
							<div class="six columns"> 
								<div class="row">
									<div class="large-12 columns">
										<label>Workout Name</label>
										<input type="text" name="workoutName" placeholder="Enter workout name" value="<?php echo htmlentities($_POST['workoutName']);?>" />
									</div>
								</div>
								<div class="row">
									<div class="large-12 columns">
										<label>Workout Date</label>
<?php 
if( isset( $_GET['date'] ) )
{
		$inDate = $_GET['date'];
}
else
{
		$inDate = $_POST['inDate'];
}
?>	
										<input type="text" name="inDate" placeholder="MM/DD/YYYY" value="<?php echo htmlentities($inDate);?>" />
									</div>
								</div>
								<br>
								<div class="row">
									<div class="large-12 columns">
										<a href="" class="button" data-dropdown="drop">Exercise Type &raquo;</a>
										<ul id="drop" class="f-dropdown medium" data-dropdown-content>
<?php
echo "<li><a id='lift'>Lift</a></li>";
echo "<li><a id='cardio'>Cardio</a></li>"; 
echo "<li><a id='calis'>Calisthenics</a></li>";
echo "<li><a id='other'>Other</a></li>";
?>
										</ul>
										<br><br>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="twelve columns">
<?php
if( count( $_SESSION['exercises'] ) > 0 )	// exercises have been submitted
{
?>
								<table>
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

		foreach( $_SESSION['exercises'] as $sessionExercise )
		{
				$exercise = fetchExercise( $sessionExercise['id'] );
				//print_r( $sessionExercise[0]['id'] );
				//print_r( "\n" );

				$name = $exerciseTypes[ $exercise[0]['TYPE_ID'] ]['NAME'];
				$type = $exerciseTypes[ $exercise[0]['TYPE_ID'] ]['TYPE'];

				//print_r( $name );
				//print_r( $type );

				echo "<tr>\n";
				echo "<td>{$name}</td>";
				echo "<td>{$type}</td>";
				echo "<td>{$exercise[0]['REPS']}</td>";
				echo "<td>{$exercise[0]['WEIGHT']}</td>";
				echo "<td>{$exercise[0]['TIME']}</td>";
				echo "<td>{$exercise[0]['DISTANCE']}</td>";
				echo "<td>{$sessionExercise['sets']}</td>";
				echo "</tr>\n";
		}
?>
									</tbody>
								</table>
<?php
}
?>
							</div>
						</div>
						<div class="row">
							<div class="six columns">
								<div class="row">
									<div class="row fields otherFields">
										<div class="large-12 columns">
											<label>Exercise Name</label>
											<input type="text" placeholder="Enter excercise name" name="name" />
										</div>
									</div>
									<div class="row fields otherFields liftFields">
										<div class="large-12 columns">
											<label>Weight (lbs)</label>
											<input type="text" placeholder="Enter lift weight" name="weight"/>
										</div>
									</div>
									<div class="row fields otherFields liftFields calisFields">
										<div class="large-12 columns">
											<label>Repetitions</label>
											<input type="text" placeholder="Enter repetitions" name="reps"/>
										</div>
									</div>
									<div class="row fields otherFields cardioFields calisFields">
										<div class="large-12 columns">
											<label>Time (sec)</label>
											<input type="text" placeholder="Enter amount of time" name="time"/>
										</div>
									</div>
									<div class="row fields otherFields cardioFields">
										<div class="large-12 columns">
											<label>Distance (m)</label>
											<input type="text" placeholder="Enter distance" name="distance"/>
										</div>
									</div>
									<div class="row fields otherFields liftFields cardioFields calisFields">
										<div class="large-12 columns">
											<label>Sets</label>
											<input type="text" placeholder="Enter sets" name="sets"/>
										</div>
									</div>
								</div>
								<div class="row">
									<br>
									<div class="large-12 columns">
										<input class="button" type="submit" value="Submit Exercise" name="sub" id="subExercise" />
<?php
if( count( $_SESSION['exercises'] ) > 0 )
{
?>
										<input class="button" type="submit" value="Submit Workout" name="sub" />
										<input class="button" type="submit" value="Clear Workout" name="sub" />
<?php
}
if( !empty($errors) )
{
?>
																<a href="" class="button alert" name="getErrors" data-reveal-id="errorModal">Errors</a>
<?php
}
?>	
									</div>
								</div>
							</div>
							<div class="six columns fields" id="liftExercises">
<?php
$exerciseTypes = fetchExerciseTypes( 'lift' );									
foreach( $exerciseTypes as $type )
{
		echo "<input type='radio' name='eType' value='{$type['ID']}' id='type{$type['ID']}'/><label for='type{$type['ID']}'>{$type['NAME']}</label>\n";
}	
?>
							</div>
							<div class="six columns fields" id="cardioExercises">
<?php
$exerciseTypes = fetchExerciseTypes( 'cardio' );									
foreach( $exerciseTypes as $type )
{
		echo "<input type='radio' name='eType' value='{$type['ID']}' id='type{$type['ID']}'/><label for='type{$type['ID']}'>{$type['NAME']}</label>\n";
}
?>
							</div>
							<div class="six columns fields" id="calisExercises">
<?php
$exerciseTypes = fetchExerciseTypes( 'calisthenics' );									
foreach( $exerciseTypes as $type )
{
		echo "<input type='radio' name='eType' value='{$type['ID']}' id='type{$type['ID']}'/><label for='type{$type['ID']}'>{$type['NAME']}</label>\n";
}
?>
							</div>
							<div class="six columns fields" id="otherExercises">
<?php
$exerciseTypes = fetchExerciseTypes( 'other' );									
foreach( $exerciseTypes as $type )
{
		echo "<input type='radio' name='eType' value='{$type['ID']}' id='type{$type['ID']}'/><label for='type{$type['ID']}'>{$type['NAME']}</label>\n";
}
?>
							</div>
						</div>
						<div class="row"></div>	
					</form>
				</li>	
			</ul>
		</div>	
	<hr />

	<footer align="center">
		Copyright 2013 - The Fireballs
	</footer>

		<br><br>
	</div>

	<div id="errorModal" class="reveal-modal [expand, xlarge, large, medium, small]">
					   <h5>Exercise Errors</h5>
					   <ul style="list-style-type:none; color:red; text-align:left;">
<?php
foreach( $errors as $error )
{
		echo "<li>{$error}</li>";
}
?>
						</ul>
					<a class="close-reveal-modal">&#215;</a>
			</div>

	<script type="text/javascript" language="javascript" src="./javascripts/all.js"></script>	

<script>
$(document).foundation().foundation('joyride', 'start');
</script>
</body>

</html>

