<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Lägger till ny användare
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['admin'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userAdmin = $_POST['admin'];
    // Kontrollerar om e-posten redan existerar i databasen
    $userInfo = $user->controlUser($email);
    // Om e-posten inte existerar i databasen
    if ($userInfo == NULL) {
        // Lägger till användaren
        $result = $user->addUser($firstname, $lastname, $email, $password, $userAdmin, $company_id);
        // Hämtar alla användare i företaget och skriver ut
        if ($result) {
            $users = $user->getUsers($company_id);
?>
            <h3>Administratörer</h3>
<?php
            foreach ($users as $key) {
                // Skriver ut administratörer
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
                    </div>
<?php
                }
            }
?>
            <h3>Personal</h3>
<?php
            foreach ($users as $key) {
                // Skriver ut personal
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
                    </div>
<?php               
                }
            }
        }
    }
    // Om e-posten redan existerar i databasen returneras false
    else {
        echo false;
    }
}