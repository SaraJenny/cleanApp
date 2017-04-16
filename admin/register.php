<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* På denna sida registrerar sig nya användare
*/
// Undersidans titel
$page_title = "Registrering";
// Hämtar in headern
include("includes/header.php");

// Skapar tomma variabler
$company_name = $firstname = $lastname = $email = $password = $passwordCheck = $companyErr = $firstnameErr = $lastnameErr = $emailErr = $passwordErr = $passwordCheckErr = $message = '';

/*--------------------- För användare som inaktiverat javascript ---------------------*/
// Om användaren tryckt på "Skapa konto" valideras formulär
if (isset($_POST["registerButton"])) {
    // Kollar om företagsnamn fyllts i
    if (empty($_POST['company'])) {
        // Felmeddelande
        $companyErr = "Du måste fylla i företagsnamn";
    }
    else {
        $company_name = $_POST['company'];
    }
    if (empty($_POST['firstname'])) {
        // Felmeddelande
        $firstnameErr = "Du måste fylla i ditt förnamn";
    }
    else {
        $firstname = $_POST['firstname'];
    }
    if (empty($_POST['lastname'])) {
        // Felmeddelande
        $lastnameErr = "Du måste fylla i ditt efternamn";
    }
    else {
        $lastname = $_POST['lastname'];
    }
    // Kollar om e-post fyllts i
    if (empty($_POST['email'])) {
        // Felmeddelande
        $emailErr = "Du måste fylla i din e-post";
    }
    else {
        $email = $_POST['email'];
        // Kollar om e-mailaddressen inte är välformulerad
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Felmeddelande
            $emailErr = "Ogiltigt epost-format";
        }
    }
    // Kollar om lösenord fyllts i
    if (empty($_POST['password'])) {
        // Felmeddelande
        $passwordErr = "Du måste fylla i ett lösenord";
    }
    else if (mb_strlen($_POST['password'], "UTF-8") < 6) {
        // Felmeddelande
        $passwordErr = "Ditt lösenord måste bestå av minst 6 tecken";
    }
    else {
        $password = $_POST['password'];
    }
    // Kollar om det andra lösenordsfältet fyllts i och överensstämmer
    if (empty($_POST['passwordCheck'])) {
        // Felmeddelande
        $passwordCheckErr = "Du måste fylla i ett lösenord";
    }
    else if ($_POST['passwordCheck'] != $_POST['password']) {
        //Felmeddelande
        $passwordCheckErr = "Lösenordsfälten överensstämmer inte";
    }
    else {
        $passwordCheck = $_POST['passwordCheck'];
    }
    // Om inga felmeddelanden är satta försöker registreringen göras
    if ($companyErr == '' && $firstnameErr == '' && $lastnameErr == '' && $emailErr == '' && $passwordErr == '' && $passwordCheckErr == '') {
        // Kontrollerar om företagsnamnet redan existerar i databasen
        $companyInfo = $company->getId($company_name);
        if ($companyInfo == NULL) {
            // Kontrollerar om e-posten redan existerar i databasen
            $userInfo = $user->controlUser($email);
            // Sparar företagsnamnet och användaren i databasen
            if ($userInfo == NULL) {
                // Kontrollerar om e-posten redan existerar i databasen
                $userInfo = $user->controlUser($email);
                if ($userInfo == NULL) {
                    $company_id = $company->addCompany($company_name);
                    $admin = 1;
                    $user_id = $user->addUser($firstname, $lastname, $email, $password, $admin, $company_id);
                    // Sätter användar-id som sessionsvariabel
                    $_SESSION['user_id'] = $user_id;
                }
                else {
                    echo "Den angivna e-posten har redan ett konto kopplat till sig";
                }
            }
            // Om e-posten redan existerar i databasen skrivs felmeddelande ut
            else {
                $emailErr = "Den angivna e-posten har redan ett konto kopplat till sig";
            }
        }
        // Om företagsnamnet redan existerar i databasen skrivs felmeddelande ut
        else {
            $companyErr = "Det angivna företagsnamnet har redan ett konto kopplat till sig";
        }
    }
}
/*------------------------------------------------------------------------------------*/



// Kollar om personen är inloggad och skickar till index.php
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
// Om personen inte är inloggad skrivs sidan ut
else {
?>
    <div class="mainContainer">
        <h2>Registrera företagskonto</h2>
        <!-- Registreringsformulär -->
        <form class="registerForm" method="post">
            <label for="firstname">Företag</label>
            <input placeholder="Företag" type="text" name="company" id="company" value="<?php echo $company_name; ?>">
            <p class="errorMessageBox"><?php echo $companyErr; ?></p>

            <label for="firstname">Förnamn</label>
            <input placeholder="Förnamn" type="text" name="firstname" id="firstname" value="<?php echo $firstname; ?>">
            <p class="errorMessageBox"><?php echo $firstnameErr; ?></p>

            <label for="lastname">Efternamn</label>
            <input placeholder="Efternamn" type="text" name="lastname" id="lastname" value="<?php echo $lastname; ?>">
            <p class="errorMessageBox"><?php echo $lastnameErr; ?></p>

            <label for="email">E-post</label>
            <input placeholder="E-post" type="email" name="email" id="email" value="<?php echo $email; ?>">
            <p class="errorMessageBox"><?php echo $emailErr; ?></p>

            <label for="password">Lösenord</label>
            <input placeholder="Lösenord" type="password" name="password" id="password" value="<?php echo $password; ?>">
            <p class="errorMessageBox"><?php echo $passwordErr; ?></p>

            <label for="passwordCheck">Upprepa lösenord</label>
            <input placeholder="Lösenord" type="password" name="passwordCheck" id="passwordCheck" value="<?php echo $passwordCheck; ?>">
            <p class="errorMessageBox"><?php echo $passwordCheckErr; ?></p>
            
            <input id="registerButton" name="registerButton" type="submit" class="button" value="Skapa konto">
        </form><!-- /Registreringsformulär -->
    </div>
<?php
}
include("includes/footer.php");