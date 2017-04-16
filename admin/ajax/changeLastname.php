<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Uppdaterar efternamn
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['userId']) && isset($_POST['lastname'])) {
    $userId = $_POST['userId'];
    $lastname = $_POST['lastname'];
    // Uppdaterar användarens efternamn
    $result = $user->updateLastname($userId, $lastname);
    // Om användaren kunde uppdateras returneras true
    if ($result == true) {
        echo true;
    }
    // Om användaren inte kunde uppdateras returneras felmeddelande
    else {
        echo "Tyvärr, namnet kunde inte sparas";
    }
}