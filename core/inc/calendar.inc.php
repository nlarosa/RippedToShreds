<?php

// return an array of pairs: (day, workoutID) for the given month and year
function getWorkouts( $email, $month, $year )
{
	global $DB;

	$query = "SELECT EXTRACT(day FROM s.WORKOUT_DATE) as day, w.ID as workout
		FROM ScheduledWorkout s, Workout w
		WHERE s.WORKOUT_ID = w.ID
		AND s.EMAIL = :x
		AND s.WORKOUT_DATE >= TO_DATE(:y, 'MM/DD/YYYY') 
		AND s.WORKOUT_DATE <= TO_DATE(:z, 'MM/DD/YYYY')";			// search for workouts happening in the days of this month
	$parsed = oci_parse( $DB, $query );
	oci_bind_by_name( $parsed, ":x", $email );
	
	if( $month < 10 )
	{
		$startDate = "0{$month}/01/{$year}";
		$endDate = "0{$month}/31/{$year}";
	}
	else
	{
		$startDate = "{$month}/01/{$year}";
		$endDate = "{$month}/31/{$year}";
	}

	oci_bind_by_name( $parsed, ":y", $startDate );
	oci_bind_by_name( $parsed, ":z", $endDate );
		
	oci_execute( $parsed );

	$workouts = array();

	while( $tuple = oci_fetch_array( $parsed, OCI_ASSOC ) )
	{
		$workouts[$tuple['DAY']][] = $tuple['WORKOUT'];
	}

	return $workouts;
}

?>
