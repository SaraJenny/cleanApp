<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Raderar rum
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['roomId'])) {
    $roomId = $_POST['roomId'];
    // Hämta rummets bild
    $roomImage = $image->getImage($roomId);
    // Om rummet har en bild så raderas den
    if ($roomImage != NULL) {
        $url = $image->deleteImage($roomId);
        // Om raderingen lyckades raderas bilden från uploads-mappen
        if ($url != false) {
            unlink("../img/uploads/" . $url);
            unlink("../img/uploads/thumb_" . $url);
            unlink("../img/uploads/mini_" . $url);
        }
    }
    // Raderar rummet
    $delete = $room->deleteRoom($roomId);
    // Om rummet kunde raderas uppdateras sektionen med alla användare
    if ($delete == true) {
        // Hämtar alla användare
        $allRooms = $room->getRooms($company_id);
        echo "<h3>Städningsstatus</h3>";
        // Kollar rummets status
        foreach ($allRooms as $key) {
            if ($key['status'] == 0) {
                $status = "<span class='notClean'>Ej städat</span>";
                $check = 0;
            }
            else {
                $status = "<span class='clean'>Städat</span>";
                $check = 1;
            }
?>
            <div class="roomInfo">
                <div class="info">
                    <h4><?php echo $key['room_name']; ?></h4>
                    <p>Status: <?php echo $status; ?></p>
<?php
                    // Om rummet är städat anges vem som städade och när
                    if ($check == 1) {
                        echo "<p>Utförd av: " . $key['firstname'] . " " . $key['lastname'] . "</p>";
                        echo $key['datetime'];
                    }
?>
                </div>
                <div class="change">
                    <a href="room.php?id=<?php echo $key['room_id']; ?>">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                    <a href="#" class="delete" id="<?php echo $key['room_id']; ?>">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
<?php
        }
    }
    // Om rummet inte kunde raderas returneras false
    else {
        echo false;
    }
}