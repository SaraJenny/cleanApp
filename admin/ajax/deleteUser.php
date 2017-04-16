<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Raderar användare
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['deleteId'])) {
    $deleteId = $_POST['deleteId'];
    if ($deleteId == $user_id) {
        echo true;
    }
    else {
        // Raderar användaren
        $delete = $user->deleteUser($deleteId, $user_id);
        // Om användaren kunde raderas uppdateras sektionen med alla användare
        if ($delete == true) {
            // Hämtar alla användare
            $users = $user->getUsers($company_id);
?>
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
                    </div>
<?php               
                }
            }
        }
        // Om rummet inte kunde raderas returneras false
        else {
            echo false;
        }
    }
}