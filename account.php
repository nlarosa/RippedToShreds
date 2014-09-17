<?php

include('./core/init.inc.php');

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

	<style type="text/css">
		@import "./stylesheets/docs.css";
		@import "./stylesheets/foundation.css";

		body
                {
			background: url(./images/flag.jpg) no-repeat center center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}
		tr.spaceUnder > td
		{
			padding-bottom: 1em;
		}
	</style>
	<script type="text/javascript" language="javascript" src="./javascripts/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" language="javascript" src="./javascripts/modernizr.foundation.js"></script>
	<script type="text/javascript" language="javascript" src="./javascripts/foundation.min.js"></script>

	<script>
		$(document).foundation();
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
				<dd class="active"><a href="calendar.php">Calendar</a></dd>
				<dd><a href="createWorkout.php">Create Workout</a></dd>
				<dd><a href="yourWorkouts.php">Your Workouts</a></dd>
				<dd><a href="searchWorkouts.php">Find Workouts</a></dd>
				<dd><a href="searchAccounts.php">Find Others</a></dd>
				<dd><a href="logout.php">Logout</a>
			</dl>
			<ul class="tabs-content">
				<li class="active" id="Tab">
					<div class="row">
						<div class="twelve columns" align="center"> 
							<?php
							// Calendar code courtesy of phpjabbers.com

							$monthNames = Array("January", "February", "March", "April", "May", "June", "July", 
									"August", "September", "October", "November", "December");

							if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("n");
							if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");

							$cMonth = $_REQUEST["month"];
							$cYear = $_REQUEST["year"];

							$prevYear = $cYear;
							$nextYear = $cYear;
							$prevMonth = $cMonth-1;
							$nextMonth = $cMonth+1;

							if ($prevMonth == 0 ) {
								$prevMonth = 12;
								$prevYear = $cYear - 1;
							}
							if ($nextMonth == 13 ) {
								$nextMonth = 1;
								$nextYear = $cYear + 1;
							}
							?>

<br>
<button class="button" width="50%" style="float:left"><a href="<?php echo "calendar.php?month=". $prevMonth . "&year=" . $prevYear; ?>" style="color:#FFFFFF">Prev Month</a></button>
<button class="button" width="50%" style="float:right"><a href="<?php echo "calendar.php?month=". $nextMonth . "&year=" . $nextYear; ?>" style="color:#FFFFFF">Next Month</a></button>
<br>

</td>
</tr>
<tr>
<td align="center">
<table width="100%" border="0" cellpadding="2" cellspacing="2">
<tr>
<td colspan="7" bgcolor="#2BA6CB" style="color:#FFFFFF; text-align:center"><strong><big><?php echo $monthNames[$cMonth-1].' '.$cYear; ?></big></strong></td>
</tr>
<tr><td></td></tr>
<tr>
<td align="center" bgcolor="#2BA6CB" style="color:#FFFFFF">Sunday</td>
<td align="center" bgcolor="#2BA6CB" style="color:#FFFFFF">Monday</td>
<td align="center" bgcolor="#2BA6CB" style="color:#FFFFFF">Tuesday</td>
<td align="center" bgcolor="#2BA6CB" style="color:#FFFFFF">Wednesday</td>
<td align="center" bgcolor="#2BA6CB" style="color:#FFFFFF">Tuesday</td>
<td align="center" bgcolor="#2BA6CB" style="color:#FFFFFF">Friday</td>
<td align="center" bgcolor="#2BA6CB" style="color:#FFFFFF">Saturday</td>
</tr>

<?php 
$timeStamp = mktime(0,0,0,$cMonth,1,$cYear);
$maxDay = date("t",$timeStamp);
$thisMonth = getdate ($timeStamp);
$startDay = $thisMonth['wday'];
$workouts = getWorkouts( 'rwheeler@nd.edu', $cMonth, $cYear );
for ($i=0; $i<($maxDay+$startDay); $i++) {
	if(($i % 7) == 0 ) echo "<tr>\n";
	if($i < $startDay) echo "<td></td>\n";
	else
	{
		$cDay = $i - $startDay + 1;
		$hasWorkouts = 0;
		if( count( $workouts[$cDay] ) > 0 )
		{
			$hasWorkouts = 1;
			$color = "#CC0000";	// link color indicates scheduled workouts
		}
		else
		{
			$color = "#2BA6CB";
		}

		echo "<td align='center' valign='middle' height='20px'>\n
		<a href='#' data-dropdown='contentDrop$cDay' style='color:$color'>$cDay</a>\n
		<div id='contentDrop$cDay' class='f-dropdown content small' data-dropdown-content>\n";
		foreach( $workouts[$cDay] as $workoutID )
		{
			echo "<p>$workoutID</p>\n";
		}
		echo "<a href='createWorkouts.php'>Schedule Workout</a>\n";
		echo "</div>\n</td>";
	}
	if(($i % 7) == 6 ) echo "</tr>\n";
}

print_r( $workouts );
?>

</table>
</td>
</tr>
</table>

						</div>	
					</div>
				</li>	
			</ul>
		</div>
		<hr />
	</div>

	<footer align="center">
		Copyright 2013 - The Fireballs
	</footer>

	<script type="text/javascript" language="javascript" src="./javascripts/all.js"></script>	

	<script>
		$(document).foundation().foundation('joyride', 'start');
	</script>
</body>

</html>

