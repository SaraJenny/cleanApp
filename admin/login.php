<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* På denna sida loggar användare in
*/
// Undersidans titel
$page_title = "Logga in";
// Hämtar in headern
include("includes/header.php");

// Skapar tomma variabler
$email = $password = $emailErr = $passwordErr = '';

/*--------------------- För användare som inaktiverat javascript ---------------------*/
// Om användaren tryckt på "Logga in" valideras formulär
if (isset($_POST["loginButton"])) {
    // Kollar om e-post fyllts i
    if (empty($_POST['email'])) {
        // Felmeddelande
        $emailErr = "Du måste fylla i din e-post";
    }
    else {
        $email = $_POST['email'];
    }
    // Kollar om lösenord fyllts i
    if (empty($_POST['password'])) {
        // Felmeddelande
        $passwordErr = "Du måste fylla i ett lösenord";
    }
    else {
        $password = $_POST['password'];
    }
    // Om inga felmeddelanden är satta försöker registreringen göras
    if ($emailErr == '' && $passwordErr == '') {
        // Kontrollerar att e-posten finns registrerad i databasen
        $userInfo = $user->controlUser($email);
        if ($userInfo == NULL) {
            $emailErr = "Du har angett en oregistrerad e-postadress";
        }
        // Om e-posten finns registrerad kontrolleras lösenordet
        else {
            foreach ($userInfo as $key) {
                $user_id = $key['user_id'];
                $stored_password = $key['password'];
            }
            $result = $user->controlPassword($email, $password, $stored_password);
            // Stämmer lösenordet kontrolleras att användaren har administrationsrättigheter
            if ($result == true) {
                $userAdmin = $user->getAdmin($user_id);
                if ($userAdmin == 1) {
                    $_SESSION["user_id"] = $user_id;
                }
                else {
                    $passwordErr = "Tyvärr, denna webbplats är endast tillgänglig för administratörer";
                }
            }
            // Om lösenordet inte stämmer skrivs ett felmeddelande ut
            else {
                $passwordErr = "Felaktigt lösenord";
            }
        }
    }
}
/*------------------------------------------------------------------------------------*/


// Kollar om sessionsvariabel är satt och skickar i så fall användaren till index.php
if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}
// Om inte sessionsvariabel är satt skrivs sidan ut
else {
?>
    <div id="loginContent">
        <div class="container">
            <h2 class="hidden">Logga in</h2>
            <!-- Inloggningsformulär -->
            <form id="loginForm" method="post">
                <div id="loginContainer">
                    <div id="emailContainer">
                        <label for="email">E-post</label>
                        <input placeholder="E-post" type="email" name="email" id="email" value="<?php echo $email; ?>">
                    </div>
                    <p class="errorMessageBox"><?php echo $emailErr; ?></p>
                    <label for="password">Lösenord</label>
                    <input placeholder="Lösenord" type="password" name="password" id="password" value="<?php echo $password; ?>">
                    <p class="errorMessageBox"><?php echo $passwordErr; ?></p>
                    <input id="loginButton" name="loginButton" type="submit" class="button" value="Logga in">
                    </div>
                </form><!-- /Inloggningsformulär -->
                <a id="register" href="register.php">Registrera företagskonto här</a>
        </div><!-- /#container -->
    </div><!-- /#loginContent -->
<?php
}
include("includes/footer.php");