<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Uppdaterar lösenord
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['userId']) && isset($_POST['password'])) {
    $userId = $_POST['userId'];
    $password = $_POST['password'];
    // Uppdaterar användarens lösenord
    $result = $user->changePassword($userId, $password);
    // Om lösenordet kunde uppdateras returneras true
    if ($result == true) {
        echo true;
    }
    // Om lösenordet inte kunde uppdateras returneras felmeddelande
    else {
        echo "Ditt lösenord kunde inte ändras";
    }
}