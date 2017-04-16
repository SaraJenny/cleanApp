<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Logga in användare
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['email']) && isset($_POST['password'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
    // Hämtar uppgifter om användaren
    $userInfo = $user->controlUser($email);
    // Om inga användaruppgifter hittas skrivs ett felmeddelande ut
    if ($userInfo == NULL) {
        echo false;
    }
    // Om användaruppgifter hittats kontrolleras att lösenordet stämmer
    else {
        foreach ($userInfo as $key) {
            $user_id = $key['user_id'];
            $stored_password = $key['password'];
        }
        $result = $user->controlPassword($email, $password, $stored_password);
        // Stämmer lösenordet returneras användarens id
        if ($result == true) {
            echo $user_id;
        }
        // Om lösenordet inte stämmer skrivs ett felmeddelande ut
        else {
            echo false;
        }
    }
}