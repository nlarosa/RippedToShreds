<?php

include('./core/init.inc.php');

$users = fetchUsers();

?>

<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/favicon.ico" />
		
		<title>DataTables example</title>
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
			<div class="large-12 columns">
				<h1>DataTables styled by Foundation CSS</h1>
				
				<h2>Preamble</h2>
				<p>DataTables has most features enabled by default, so all you need to do to use it with one of your own tables is to call the construction function (as shown below).</p>
				
				<h2>Live example</h2>
				<div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
	<thead>
		<tr>
			<th>Rendering engine</th>
			<th>Browser</th>
			<th>Platform(s)</th>
			<th>Engine version</th>
			<th>CSS grade</th>
			<th>Random</th>
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
				<div class="spacer"></div>
			</div>
		</div>
			
	</body>
</html>
