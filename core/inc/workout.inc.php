<?php

function validDate( $date )
{
	$fields = explode( '/', $date );

	print_r( $fields );

	return checkdate( $fields[0], $fields[1], $fields[2] );
}

// creates a new workout tuple and return the workout ID
function createWorkoutID( $email, $name )
{
	global $DB;

	$query = "SELECT ID
		FROM Workout";
	$parsed = oci_parse( $DB, $query );
	oci_execute( $parsed );

	$count = 0;

	while( $row = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$count++;
	}

	print_r( $count );

	$query = "INSERT INTO Workout( ID, NAME, CREATOR_EMAIL )
		VALUES ( :x, :y, :z )";
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":x", $count );
	oci_bind_by_name( $parsed, ":y", $name );
	oci_bind_by_name( $parsed, ":z", $email );

	if( oci_execute( $parsed ) )
	{
		$_SESSION['id'] = $count;	// hold onto workout_ID
		$_SESSION['name'] = $name;	// and workout name
	
		return true;
	}

	return false;			// this is the new Workout ID
}

// assoicate a workout with a specific date
function scheduleWorkout( $workout_ID, $email, $workout_date )
{
	global $DB;

	print_r( $workout_date );
	print_r( $workout_ID );
	print_r( $email );

	$query = "INSERT INTO ScheduledWorkout( WORKOUT_ID, EMAIL, WORKOUT_DATE )
		VALUES ( :x, :y, TO_DATE(:z, 'MM/DD/YYYY') )";
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":x", $workout_ID );
	oci_bind_by_name( $parsed, ":y", $email );
	oci_bind_by_name( $parsed, ":z", $workout_date );
	
	return oci_execute( $parsed );
}	

// creates a new exercise for the given workout
function createExercise( $distance, $reps, $weight, $time, $notes, $type, $sets )
{
	global $DB;

	$query = "SELECT ID
		FROM Exercise";
	$parsed = oci_parse( $DB, $query );
	oci_execute( $parsed );
	
	$count = 0;

	while( $row = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$count++;
	}

	$query = "INSERT INTO Exercise( ID, DISTANCE, REPS, WEIGHT, TIME, NOTES, TYPE_ID )
		VALUES ( :a, :b, :c, :d, :e, :f, :g )";
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":a", $count );
	oci_bind_by_name( $parsed, ":b", $distance );
	oci_bind_by_name( $parsed, ":c", $reps );
	oci_bind_by_name( $parsed, ":d", $weight );
	oci_bind_by_name( $parsed, ":e", $time );
	oci_bind_by_name( $parsed, ":f", $notes );
	oci_bind_by_name( $parsed, ":g", $type );

	if( oci_execute( $parsed ) )
	{
		$exercise = array();

		$exercise['id'] = $count;
		$exercise['sets'] = $sets;
	
		$_SESSION['exercises'][] = $exercise;
	
		return true;
	}
	
	return false;
}

// creates a new exercise type
function createExerciseType( $name, $type, $calPerHour, $calPerDistance, $calPerPound, $muscleGroup )
{
	global $DB;

	$query = "SELECT ID
		FROM ExerciseType";
	$parsed = oci_parse( $DB, $query );
	
	$count = 0;

	while( $row = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$count++;
	}

	$query = "INSERT INTO ExerciseType( ID, NAME, TYPE, CALPERHOUR, CALPERPOUND, MUSCLEGROUP )
		VALUES ( :a, :b, :c, :d, :e, :f )";
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":a", $name );
	oci_bind_by_name( $parsed, ":b", $type );
	oci_bind_by_name( $parsed, ":c", $calPerHour );
	oci_bind_by_name( $parsed, ":d", $calPerDistance );
	oci_bind_by_name( $parsed, ":e", $calPerPound );
	oci_bind_by_name( $parsed, ":f", $muscleGroup );

	oci_execute( $parsed );
}

// modify an exercise for the current workout
function modifyExercise( $ID, $distance, $reps, $weight, $time, $notes, $type, $sets )
{
	global $DB;

	$query = "UPDATE Exercise
		SET DISTANCE = :a,REPS = :b,WEIGHT = :c,TIME = :d,NOTES = :e,TYPE = :f
		WHERE ID = :x";
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":x", $ID );
	oci_bind_by_name( $parsed, ":a", $distance );
	oci_bind_by_name( $parsed, ":b", $reps );
	oci_bind_by_name( $parsed, ":c", $weight );
	oci_bind_by_name( $parsed, ":d", $time );
	oci_bind_by_name( $parsed, ":e", $notes );
	oci_bind_by_name( $parsed, ":f", $type );
	oci_execute( $parsed );

	foreach( $_SESSION['exercises'] as $exercise )
	{
		if( $exercise['id'] = $ID )
		{
			$exercise['sets'] = $sets;
			break;
		}
	}
}

// modify the workout name if it has been submitted again
function editWorkoutName( $newName, $workout_ID )
{
	global $DB;

	$query = "UPDATE Workout
		SET NAME = :x 
		WHERE ID = :y";
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":x", $newName );
	oci_bind_by_name( $parsed, ":y", $workout_ID );
	oci_execute( $parsed );
}

// return the workout name
function fetchWorkoutName( $workout_ID )
{
	global $DB;

	$names = array();

	$query = "SELECT NAME
		FROM Workout 
		WHERE ID = :x";
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":x", $workout_ID );
	oci_execute( $parsed );

	while( $tuple = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$names[$workout_ID] = $tuple['NAME'];
	}

	return $names;
}

// return the workout sets
function fetchExerciseSets( $workout_ID, $exercise_ID )
{
	global $DB;

	$sets = array();

	$query = "SELECT SETS
		FROM WorkoutExercise 
		WHERE WORKOUT_ID = :x AND EXERCISE_ID = :y";
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":x", $workout_ID );
	oci_bind_by_name( $parsed, ":y", $exercise_ID );
	oci_execute( $parsed );

	while( $tuple = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$sets[$exercise_ID] = $tuple['SETS'];
	}

	return $sets;
}
// return an array containing exercise information
function fetchExercises( $workout_ID )
{
	global $DB;

	$exercises = array();

	$query = "SELECT * 
		FROM WorkoutExercise
		WHERE WORKOUT_ID = :x";
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":x", $workout_ID );
	oci_execute( $parsed );

	while( $tuple = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$exercises[] = $tuple;	
	}

	return $exercises;
}

// return an array containing a single exercise information
function fetchExercise( $exercise_ID )
{
	global $DB;
	
	$exercise = array();

	$query = "SELECT *
		FROM Exercise
		WHERE ID = :x";
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":x", $exercise_ID );
	oci_execute( $parsed );

	while( $tuple = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$exercise[] = $tuple;
	}

	return $exercise;
}

// return an array containing exercise types
function fetchExerciseTypes( $type )
{
	global $DB;

	$exerciseTypes = array();
	
	$query = "SELECT *
		FROM ExerciseType
		WHERE UPPER(TYPE) = UPPER(:x)";
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":x", $type );
	oci_execute( $parsed );
	
	while( $tuple = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$exerciseTypes[$tuple['ID']] = $tuple;
	}

	return $exerciseTypes;	
}

// return an array containing exercise types
function fetchAllExerciseTypes( )
{
	global $DB;

	$exerciseTypes = array();
	
	$query = "SELECT *
		FROM ExerciseType";
	$parsed = oci_parse( $DB, $query );
	oci_execute( $parsed );
	
	while( $tuple = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$exerciseTypes[$tuple['ID']] = $tuple;
	}

	return $exerciseTypes;	
}

// connects each workout with exercise
function connectExercises( $workout_ID, $exercises )
{
	global $DB;

	foreach( $exercises as $exercise )
	{
		$query = "INSERT INTO WorkoutExercise( WORKOUT_ID, EXERCISE_ID, SETS )
			VALUES ( :a, :b, :c )";
		$parsed = oci_parse( $DB, $query );
		oci_bind_by_name( $parsed, ":a", $workout_ID );
		oci_bind_by_name( $parsed, ":b", $exercise['id'] );
		oci_bind_by_name( $parsed, ":c", $exercise['sets'] );
		oci_execute( $parsed );
	}
}

// remove workout and exercises if workout cancelled
function cancelWorkout( $workout_ID, $exercises )
{
	global $DB;

	$query = "DELETE FROM WORKOUT
		WHERE ID = :x";		// first delete workout tuple
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":x", $workout_ID );
	oci_execute( $parsed );

	foreach( $exercises as $exercise )
	{
		$query = "DELETE FROM Exercise
			WHERE ID := x";
		$parsed = oci_parse( $DB, $query );
		oci_bind_by_name( $parsed, ":x", $exercise['id'] );
		oci_execute( $parsed );
	}

	$_SESSION['id'] = -1;
}

// return the workout tuples
function fetchWorkouts()
{
	global $DB;

	$workouts = array();

	$query = "SELECT *
		FROM Workout";
	$parsed = oci_parse( $DB, $query );
	oci_execute( $parsed );

	while( $tuple = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$workouts[] = $tuple;
	}

	return $workouts;			// array of workouts
}

// return the workout tuples for a specific user
function fetchWorkoutsByUser( $user )
{
	global $DB;

	$workouts = array();

	$query = "SELECT *
		FROM Workout
		WHERE CREATOR_EMAIL = :x";
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":x", $user );
	oci_execute( $parsed );

	while( $tuple = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$workouts[] = $tuple;
	}

	return $workouts;			// array of workouts
}

?>	
