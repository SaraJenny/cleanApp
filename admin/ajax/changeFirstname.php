<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Uppdaterar förnamn
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['userId']) && isset($_POST['firstname'])) {
    $userId = $_POST['userId'];
    $firstname = $_POST['firstname'];
    // Uppdaterar användarens förnamn
    $result = $user->updateFirstname($userId, $firstname);
    // Om användaren kunde uppdateras returneras true
    if ($result == true) {
        echo true;
    }
    // Om användaren inte kunde uppdateras returneras felmeddelande
    else {
        echo "Tyvärr, namnet kunde inte sparas";
    }
}