<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Raderar ett rums bild
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['roomId'])) {
    $roomId = $_POST['roomId'];
    // Raderar bild
    $url = $image->deleteImage($roomId);
    // Om raderingen lyckades raderas bilder från uploads-mappen och returneras true
    if ($url != false) {
        unlink("../img/uploads/" . $url);
        unlink("../img/uploads/thumb_" . $url);
        unlink("../img/uploads/mini_" . $url);
        echo true;
    }
    // Om rummet inte kunde raderas returneras false
    else {
        echo false;
    }
}