<?php

include('./core/init.inc.php');

connectExercises( $_SESSION['id'], $_SESSION['exercises'] );

$_SESSION['id'] = -1;

?> 
