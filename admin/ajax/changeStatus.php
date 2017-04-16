<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Ändra status på ett rum
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['userId']) && isset($_POST['roomId']) && isset($_POST['status'])) {
	$userId = $_POST['userId'];
	$roomId = $_POST['roomId'];
    $status = $_POST['status'];
	$now = date('Y-m-d H:i:s');
    // Uppdaterar rummets status
    $result = $room->changeStatus($roomId, $userId, $status, $now);
    if ($result) {
    	echo true;
    }
    else {
        echo false;
    }
}