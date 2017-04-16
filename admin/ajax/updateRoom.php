<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Uppdaterar information om rum
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['roomId']) && isset($_POST['roomName']) && isset($_POST['desc']) && isset($_POST['status'])) {
    $roomId = $_POST['roomId'];
    $roomName = $_POST['roomName'];
    $desc = $_POST['desc'];
    $status = $_POST['status'];
    $now = $date . " " . $time;
    // Uppdaterar rummet
    $result = $room->updateRoom($roomName, $desc, $status, $now, $roomId);
    // Om rummet kunde uppdateras returneras true
    if ($result == true) {
        echo true;
    }
    // Om rummet inte kunde uppdateras returneras false
    else {
        echo false;
    }
}