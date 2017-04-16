<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Uppdaterar information om användare
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['userId']) && isset($_POST['password'])) {
    $editUser = $_POST['userId'];
    $password = $_POST['password'];
    // Uppdaterar lösenordet i databasen
    $result = $user->changePassword($editUser, $password);
    // Om lösenordet kunde uppdateras returneras true
    if ($result == true) {
        echo true;
    }
    // Om användaren inte kunde uppdateras returneras false
    else {
        echo false;
    }
}