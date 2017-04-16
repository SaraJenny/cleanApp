<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Uppdaterar e-post
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['userId']) && isset($_POST['email'])) {
    $userId = $_POST['userId'];
    $email = $_POST['email'];
    // Kontrollerar om e-posten redan existerar i databasen
    $userInfo = $user->controlUser($email);
    foreach ($userInfo as $key) {
        $dbUserId = $key['user_id'];
    }
    // Kollar att e-posten inte redan finns i databasen alternativt att det är den aktuella användarens e-post
    if ($userInfo == NULL || $dbUserId == $userId) {
        // Uppdaterar användarens e-post
        $result = $user->updateEmail($userId, $email);
        // Om användaren kunde uppdateras returneras true
        if ($result == true) {
            echo true;
        }
        // Om användaren inte kunde uppdateras returneras felmeddelande
        else {
            echo 'Tyvärr, e-posten kunde inte sparas';
        }
    }
    // Om annan användare har e-posten
    else {
        echo "Den angivna e-posten har redan ett konto kopplat till sig";
    }
}