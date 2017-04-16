<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Redigera användarinformation
*/
// Undersidans titel
$page_title = "Redigera användare";
// Hämtar in headern
include("includes/header.php");

// Skapar tomma variabler
$firstname = $lastname = $email = $password = $passwordCheck = $firstnameErr = $lastnameErr = $emailErr = $passwordErr = $passwordCheckErr = $message = '';

// Kollar att användaren är inloggad
if (isset($_SESSION["user_id"])) {
	
	// Kollar om parametrar är satt för att skriva ut information om en användare
	if (isset($_GET["id"]) && isset($_GET['comp'])) {
		$editUser = $_GET["id"];
		$editCompany = $_GET['comp'];

		/*--------------------- För användare som inaktiverat javascript ---------------------*/

		// Om användaren tryckt på "Uppdatera" valideras formulär
		if (isset($_POST['editUserButton'])) {
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
		    // Om inga felmeddelanden är satta försöker uppdateringen göras
		    if ($firstnameErr == '' && $lastnameErr == '' && $emailErr == '') {
		        // Sparar användaren i databasen
			    if (isset($_POST['admin'])) {
			    	$userAdmin = 1;
			    }
			    else {
			    	$userAdmin = 0;
			    }
			    // Kontrollerar om e-posten redan existerar i databasen
			    $userInfo = $user->controlUser($email);
			    foreach ($userInfo as $key) {
			    	$dbUserId = $key['user_id'];
			    }
			    if ($userInfo == NULL || $dbUserId == $editUser) {
			        $result = $user->updateUser($firstname, $lastname, $email, $userAdmin, $editUser);
			        // Om användaren kunde uppdateras
			        if ($result == true) {
			            $message = "Uppgifterna har nu uppdaterats";
			        }
			        // Om användaren inte kunde uppdateras
			        else {
			            $message = "<span class='errorMessageBox'>Tyvärr, uppgifterna kunde inte uppdateras</span>";
			        }
			    }
			    else {
			    	$emailErr = "Den angivna e-posten har redan ett konto kopplat till sig";
			    }
		    }
		}
		// Om användaren har klickat på "Byt lösenord"
		if (isset($_POST['passwordButton'])) {
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
		    // Om inga felmeddelanden är satta försöker uppdateringen göras
		    if ($passwordErr == '' && $passwordCheckErr == '') {
		        // Sparar lösenordet i databasen
	            $result = $user->changePassword($editUser, $password);
	            if ($result) {
	            	$message = "Lösenordet har nu uppdaterats";
	            }
	            else {
	            	$message = "<span class='errorMessageBox'>Tyvärr, lösenordet kunde inte uppdateras</span>";
	            }
		    }
		}

		/*------------------------------------------------------------------------------------*/

		// Om användaren har behörighet att se aktuell användare skrivs den ut
		if ($editCompany == $company_id) {
			$userInfo = $user->getUserInfo($editUser);
			foreach ($userInfo as $key) {
				$firstname = $key['firstname'];
				$lastname = $key['lastname'];
				$email = $key['email'];
				$userAdmin = $key['admin'];
			}
?>
			<div class="mainContainer">
				<h2><?php echo $company_name ?></h2>
				<h3>Redigera användare</h3>
				<form id="editUserForm" method="post">
					<p class="clean"><?php echo $message; ?></p>
					<label for="firstname">Förnamn</label>
		            <input placeholder="Förnamn" type="text" name="firstname" id="firstname" value="<?php echo $firstname; ?>">
		            <p class="errorMessageBox"><?php echo $firstnameErr; ?></p>

					<label for="lastname">Efternamn</label>
		            <input placeholder="Efternamn" type="text" name="lastname" id="lastname" value="<?php echo $lastname; ?>">
		            <p class="errorMessageBox"><?php echo $lastnameErr; ?></p>

					<label for="email">E-post</label>
		            <input placeholder="E-post" type="email" name="email" id="email" value="<?php echo $email; ?>">
		            <p class="errorMessageBox"><?php echo $emailErr; ?></p>

					<label for="admin" class="statusLabel">Administratör</label>
		            <input class="checkbox" type="checkbox" name="admin" value="admin" id="admin"
<?php
					// Kollar om användaren är administratör
					if ($userAdmin == 1) {
						echo " checked='checked'";
					}
?>
		            >

		            <input id="editUserButton" name="editUserButton" type="submit" class="button" value="Uppdatera">
				</form>
	            <h3>Ändra lösenord</h3>
	            <!-- Lösenordsformulär -->
	            <form id="updatePasswordForm" method="post">              
		            <label for="password">Nytt lösenord</label>
		            <input placeholder="Lösenord" type="password" name="password" id="password" value="<?php echo $password; ?>">
		            <p class="errorMessageBox"><?php echo $passwordErr; ?></p>

		            <label for="passwordCheck">Upprepa lösenord</label>
		            <input placeholder="Lösenord" type="password" name="passwordCheck" id="passwordCheck" value="<?php echo $passwordCheck; ?>">
		            <p class="errorMessageBox"><?php echo $passwordCheckErr; ?></p>

	                <input id="passwordButton" name="passwordButton" type="submit" class="button" value="Ändra lösenord">
	            </form><!-- /Lösenordsformulär -->
	        </div>
<?php
		}
		// Om användaren inte har behörighet att se aktuell användare skickas besökaren tillbaka till users.php
		else {
			header("Location: users.php");
	    	exit;
		}
	}
	// Om inte parametrar är satta skickas besökaren till användarsidan
	else {
	    header("Location: users.php");
	    exit;
	}
}
// Om användaren inte är inloggad skickas denna till inloggningssidan
else {
    header("Location: login.php");
    exit;
}
include("includes/footer.php");