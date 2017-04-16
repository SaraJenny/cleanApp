<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Lista med företagets användare
*/

// Undersidans titel
$page_title = "Användare";
// Hämtar in headern
include("includes/header.php");

// Skapar tomma variabler
$firstname = $lastname = $email = $password = $passwordCheck = $firstnameErr = $lastnameErr = $emailErr = $passwordErr = $passwordCheckErr = $updateForm = $message = '';

/*--------------------- För användare som inaktiverat javascript ---------------------*/
// Om användaren tryckt på "Lägg till" valideras formulär
if (isset($_POST["no-js-addUserButton"])) {
    if (empty($_POST['firstname'])) {
        // Felmeddelande
        $firstnameErr = "Du måste fylla i ett förnamn";
    }
    else {
        $firstname = $_POST['firstname'];
    }
    if (empty($_POST['lastname'])) {
        // Felmeddelande
        $lastnameErr = "Du måste fylla i ett efternamn";
    }
    else {
        $lastname = $_POST['lastname'];
    }
    // Kollar om e-post fyllts i
    if (empty($_POST['email'])) {
        // Felmeddelande
        $emailErr = "Du måste fylla i en e-post";
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
        $passwordErr = "Lösenordet måste bestå av minst 6 tecken";
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
    if ($firstnameErr == '' && $lastnameErr == '' && $emailErr == '' && $passwordErr == '' && $passwordCheckErr == '') {
        // Kontrollerar om e-posten redan existerar i databasen
        $userInfo = $user->controlUser($email);
        // Sparar användaren i databasen
        if ($userInfo == NULL) {
		    if (isset($_POST['admin'])) {
		    	$userAdmin = 1;
		    }
		    else {
		    	$userAdmin = 0;
		    }
            $result = $user->addUser($firstname, $lastname, $email, $password, $userAdmin, $company_id);
            if ($result) {
            	// Inaktivera knapptryckning
		        unset($_GET["add"]);
            }
        }
        // Om e-posten redan existerar i databasen skrivs felmeddelande ut
        else {
            $emailErr = "Den angivna e-posten har redan ett konto kopplat till sig";
        }
    }
}
// Om användaren har tryckt på "Uppdatera"
if (isset($_GET["id"])) {
	$editId = $_GET["id"];
}

// Om användaren har tryckt på radera, så raderas användaren från databasen
if (isset($_GET["del"])) {
	$deleteUser = $_GET["del"];
    if ($deleteUser == $user_id) {
        $message = "Du kan inte radera ditt eget konto";
    }
    else {
        $delete = $user->deleteUser($deleteUser, $user_id);
        if ($delete == true) {
            // Inaktivera knapptryckning
            unset($_GET["del"]);
        }
    }
}

/*------------------------------------------------------------------------------------*/

// Om sessionsvariabel med användarid är satt skrivs sidan ut
if (isset($_SESSION["user_id"])) {
?>
    <div class="mainContainer">
    	<h2><?php echo $company_name ?></h2>
        <a href="users.php?add=true" id="addUserFormButton" class="button">Lägg till användare</a>
<?php
	   $users = $user->getUsers($company_id);

/*--------------------- För användare som inaktiverat javascript ---------------------*/
        if (isset($_GET["add"]) && $_GET["add"] == "true") {
?>
            <form method="post">
            	<label for="firstname">Förnamn</label>
                <input placeholder="Förnamn" type="text" name="firstname" value="<?php echo $firstname; ?>">
                <p class="errorMessageBox"><?php echo $firstnameErr; ?></p>

            	<label for="lastname">Efternamn</label>
                <input placeholder="Efternamn" type="text" name="lastname" value="<?php echo $lastname; ?>">
                <p class="errorMessageBox"><?php echo $lastnameErr; ?></p>

            	<label for="email">E-post</label>
                <input placeholder="E-post" type="email" name="email" value="<?php echo $email; ?>">
                <p class="errorMessageBox"><?php echo $emailErr; ?></p>

                <label for="password">Lösenord</label>
                <input placeholder="Lösenord" type="password" name="password" value="<?php echo $password; ?>">
                <p class="errorMessageBox"><?php echo $passwordErr; ?></p>

                <label for="passwordCheck">Upprepa lösenord</label>
                <input placeholder="Lösenord" type="password" name="passwordCheck" value="<?php echo $passwordCheck; ?>">
                <p class="errorMessageBox"><?php echo $passwordCheckErr; ?></p>

            	<label for="admin">Administratör</label>
                <input class="checkbox" type="checkbox" name="admin" value="admin">

                <input id="no-js-addUserButton" name="no-js-addUserButton" type="submit" class="button" value="Lägg till">
            </form>
<?php
	   }
/*------------------------------------------------------------------------------------*/
	   else {
?>
    	<section id="addUser">
            <h3>Lägg till användare</h3>
            <!-- För användare som avaktiverat JavaScript samt använder en större skärm -->
            <form method="post" id="no-js-form">
                <label for="firstname">Förnamn</label>
                <input placeholder="Förnamn" type="text" name="firstname" value="<?php echo $firstname; ?>">
                <p class="errorMessageBox"><?php echo $firstnameErr; ?></p>

                <label for="lastname">Efternamn</label>
                <input placeholder="Efternamn" type="text" name="lastname" value="<?php echo $lastname; ?>">
                <p class="errorMessageBox"><?php echo $lastnameErr; ?></p>

                <label for="email">E-post</label>
                <input placeholder="E-post" type="email" name="email" value="<?php echo $email; ?>">
                <p class="errorMessageBox"><?php echo $emailErr; ?></p>

                <label for="password">Lösenord</label>
                <input placeholder="Lösenord" type="password" name="password" value="<?php echo $password; ?>">
                <p class="errorMessageBox"><?php echo $passwordErr; ?></p>

                <label for="passwordCheck">Upprepa lösenord</label>
                <input placeholder="Lösenord" type="password" name="passwordCheck" value="<?php echo $passwordCheck; ?>">
                <p class="errorMessageBox"><?php echo $passwordCheckErr; ?></p>

                <label for="admin">Administratör</label>
                <input class="checkbox" type="checkbox" name="admin" value="admin">

                <input id="no-js-addUserButton" name="no-js-addUserButton" type="submit" class="button" value="Lägg till">
            </form>
            <!-- - -->
    		<form id="addUserForm" method="post">
    			<label for="firstname">Förnamn</label>
                <input placeholder="Förnamn" type="text" name="firstname" id="firstname" value="<?php echo $firstname; ?>">

    			<label for="lastname">Efternamn</label>
                <input placeholder="Efternamn" type="text" name="lastname" id="lastname" value="<?php echo $lastname; ?>">

    			<label for="email">E-post</label>
                <input placeholder="E-post" type="email" name="email" id="email" value="<?php echo $email; ?>">

                <label for="password">Lösenord</label>
                <input placeholder="Lösenord" type="password" name="password" id="password" value="<?php echo $password; ?>">

                <label for="passwordCheck">Upprepa lösenord</label>
                <input placeholder="Lösenord" type="password" name="passwordCheck" id="passwordCheck" value="<?php echo $passwordCheck; ?>">

    			<label for="admin" class="statusLabel">Administratör</label>
                <input class="checkbox" type="checkbox" name="admin" value="admin" id="admin">

                <input id="addUserButton" name="addUserButton" type="submit" class="button" value="Lägg till">
    		</form>
    	</section>
<?php
	   }
       // Skriver ut alla användare
?>
    	<section id="allUsers">
            <p class="errorMessageBox"><?php echo $message; ?></p>
    		<h3>Administratörer</h3>
<?php
    		foreach ($users as $key) {
    			if ($key['admin'] == 1) {
?>
    				<div class="userInfo">
    					<p><?php echo $key['firstname'] . " " . $key['lastname']; ?></p>
                        <a href="update.php?comp=<?php echo $company_id; ?>&id=<?php echo $key['user_id']; ?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="delete" id="<?php echo $key['user_id']; ?>">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                        <a href="update.php?comp=<?php echo $company_id; ?>&id=<?php echo $key['user_id']; ?>" class="no-js">Redigera</a>
                        <a href="users.php?del=<?php echo $key['user_id']; ?>" class="no-js delete no-js-delete">Radera</a>
    				</div>
<?php
    			}
    		}
?>
            <h3>Personal</h3>
<?php
    		foreach ($users as $key) {
    			if ($key['admin'] == 0) {
?>
    				<div class="userInfo">
    					<p><?php echo $key['firstname'] . " " . $key['lastname']; ?></p>
                        <a href="update.php?comp=<?php echo $company_id; ?>&id=<?php echo $key['user_id']; ?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="delete" id="<?php echo $key['user_id']; ?>">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                        <a href="update.php?comp=<?php echo $company_id; ?>&id=<?php echo $key['user_id']; ?>" class="no-js">Redigera</a>
                        <a href="users.php?del=<?php echo $key['user_id']; ?>" class="no-js delete no-js-delete">Radera</a>
    				</div>
<?php				
    			}
    		}
?>
	   </section>
    </div>
<?php
}
// Om användaren inte är inloggad skickas denna till inloggningssidan
else {
    header("Location: index.php");
    exit;
}
include("includes/footer.php");