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

// Calendar code courtesy of phpjabbers.com

$monthNames = Array("January", "February", "March", "April", "May", "June", "July", 
			"August", "September", "October", "November", "December");

if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("n");
if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");

$cMonth = $_REQUEST["month"];
$cYear = $_REQUEST["year"];

$workouts = getWorkouts( $_SESSION['user'], $cMonth, $cYear );
//print_r( $workouts );

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
		$(document).foundation();
	</script>	
</head>

<body>
	<div class="row" style="background-color:#FFFFFF"> 
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

	<div class="row" style="background-color:#FFFFFF">
		<div class="twelve columns">
			<dl class="tabs">
				<dd class="active"><a href="calendar.php">Calendar</a></dd>
				<dd><a href="createWorkout.php">Create Workout</a></dd>
				<dd><a href="yourAccount.php">Your Account</a></dd>
				<dd><a href="searchWorkouts.php">Find Workouts</a></dd>
				<dd><a href="community.php">Community</a></dd>
				<dd><a href="logout.php">Logout</a>
			</dl>
			<ul class="tabs-content">
				<li class="active" id="Tab">
					<div class="row">
						<div class="twelve columns" align="center">
<?php
$prevGet = "calendar.php?month=". $prevMonth . "&year=" . $prevYear;
$nextGet = "calendar.php?month=". $nextMonth . "&year=" . $nextYear;

if( isset( $_GET['id'] ) )
{
	$name = fetchWorkoutName( $_GET['id'] );
	$name = $name[ $_GET['id'] ];
	echo "<p>Select your date to schedule \"$name\"</p>\n";
	
	$prevGet = $prevGet . "&id=" . $_GET['id'];
	$nextGet = $nextGet . "&id=" . $_GET['id'];
}
?> 
<button class="button" width="50%" style="float:left"><a href="<?php echo $prevGet ?>" style="color:#FFFFFF">Prev Month</a></button>
<button class="button" width="50%" style="float:right"><a href="<?php echo $nextGet ?>" style="color:#FFFFFF">Next Month</a></button>
<br><br><br>

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
		<a href='' data-dropdown='drop$cDay' style='color:$color'>$cDay</a>\n
		<ul id='drop$cDay' class='f-dropdown' data-dropdown-content>\n";
		foreach( $workouts[$cDay] as $workoutID )
		{
			$workoutName = fetchWorkoutName( $workoutID );
			$workoutName = $workoutName[$workoutID];

			echo "<li><a href='workout.php?id=$workoutID'>$workoutName</a></li>\n";
		}
		echo "<li><a href='searchWorkouts.php?month=$cMonth&day=$cDay&year=$cYear'><strong>Schedule Existent Workout</strong></a></li>";
		echo "<li><a href='createWorkout.php?month=$cMonth&day=$cDay&year=$cYear'><strong>Schedule New Workout<strong></a></li>\n";
		
		if( isset( $_GET['id'] ) )	// user has come to schedule workout
		{
			//$name = fetchWorkoutName( $_GET['id'] );
			//$name = $name[ $_GET['id'] ];
			echo "<li><a href='scheduleWorkout.php?id={$_GET['id']}&month=$cMonth&day=$cDay&year=$cYear'><strong>Schedule \"$name\" Here</strong></a></li>\n";
		}

		echo "</ul>\n</td>";
	}
	if(($i % 7) == 6 ) echo "</tr>\n";
}
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

			<div id="fb-root"></div>
                                <script>
                                        window.fbAsyncInit = function() {
                                                // init the FB JS SDK
                                                FB.init({
                                                        appId      : '550276045049462',                        // App ID from the app dashboard
                                                        status     : true,                                 // Check Facebook Login status
                                                        xfbml      : true                                  // Look for social plugins on the page
                                                });

                                                // Additional initialization code such as adding Event Listeners goes here
                                        };
                                        // Load the SDK asynchronously
                                        (function(){
                                                // If we've already installed the SDK, we're done
                                                if (document.getElementById('facebook-jssdk')) {return;}
                                                // Get the first script element, which we'll use to find the parent node
                                                var firstScriptElement = document.getElementsByTagName('script')[0];

                                                // Create a new script element and set its id
                                                var facebookJS = document.createElement('script');
                                                facebookJS.id = 'facebook-jssdk';

                                                // Set the new script's source to the source of the Facebook JS SDK
                                                facebookJS.src = '//connect.facebook.net/en_US/all.js';

                                                // Insert the Facebook JS SDK into the DOM
                                                firstScriptElement.parentNode.insertBefore(facebookJS, firstScriptElement);
                                        }());
                                </script>
				<div class="row">
					<div class="one column"></div>
                                	<div class="fb-like" data-href="http://facebook.com/RippedToShredsCommunity" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>
                                </div>
		<hr />

	<footer align="center">
		Copyright 2013 - The Fireballs
	</footer>

	<br><br>	
	</div>

	<script type="text/javascript" language="javascript" src="./javascripts/all.js"></script>	

	<script>
		$(document).foundation().foundation('joyride', 'start');
	</script>
</body>

</html>

