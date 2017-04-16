<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Uppdaterar information om användare
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['userId']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['admin'])) {
    $userId = $_POST['userId'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $userAdmin = $_POST['admin'];
    // Kontrollerar om e-posten redan existerar i databasen
    $userInfo = $user->controlUser($email);
    foreach ($userInfo as $key) {
        $dbUserId = $key['user_id'];
    }
    // Om användarens e-post inte fanns i databasen alt. tillhör den aktuella användaren
    if ($userInfo == NULL || $dbUserId == $userId) {
        // Uppdaterar användaruppgifter
        $result = $user->updateUser($firstname, $lastname, $email, $userAdmin, $userId);
        // Om användaren kunde uppdateras returneras true
        if ($result == true) {
            echo true;
        }
        // Om användaren inte kunde uppdateras returneras felmeddelande
        else {
            echo "Tyvärr, uppgifterna kunde inte uppdateras";
        }
    }
    // Om användarens e-post redan finns i databasen
    else {
        echo "Den angivna e-posten har redan ett konto kopplat till sig";
    }
}