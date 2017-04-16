<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Lägger till ny användare
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['company_name']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['password'])) {
    $company_name = $_POST['company_name'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $admin = 1;
    // Kontrollerar om företagsnamnet redan existerar i databasen
    $companyInfo = $company->getId($company_name);
    // Om företagsnamnet inte finns registrerat
    if ($companyInfo == NULL) {
        // Kontrollerar om e-posten redan existerar i databasen
        $userInfo = $user->controlUser($email);
        // Om e-posten inte redan finns i databasen
        if ($userInfo == NULL) {
            // Lägger till företaget
            $company_id = $company->addCompany($company_name);
            // Lägger till användaren
            $user_id = $user->addUser($firstname, $lastname, $email, $password, $admin, $company_id);
            // Sätter användar-id som sessionsvariabel
            $_SESSION['user_id'] = $user_id;
            echo true;
        }
        // Om e-posten redan finns i databasen
        else {
            echo "Den angivna e-posten har redan ett konto kopplat till sig";
        }
    }
    // Om företagsnamnet redan existerar i databasen skrivs felmeddelande ut
    else {
        echo false;
    }
}