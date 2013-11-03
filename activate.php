<?php

include('./core/init.inc.php');

activateAccount($_GET['code']);

header('Location: index.php?activated=1');

?>

