<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Hämta antal ostädade rum
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['userId'])) {
	$userId = $_POST['userId'];
	// Hämta antalet rum som ska städas
	$result = $room->getToDoNumber($userId);
	if ($result) {
		echo $result;
	}
	else {
		echo false;
	}
}