<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Lägger till nytt rum
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['roomName']) && isset($_POST['desc'])) {
    $roomName = $_POST['roomName'];
    $desc = $_POST['desc'];
    $status = 0;
    $now = $date . " " . $time;
    // Lägger till rummet
    $result = $room->addRoom($roomName, $desc, $company_id, $user_id, $status, $now);
    // Om rummet kunde läggas till uppdateras sektionen med alla rum, så att det nya rummet syns
    if ($result == true) {
        $allRooms = $room->getRooms($company_id);
        echo "<h3>Städningsstatus</h3>";
        foreach ($allRooms as $key) {
            // Kollar rummets status
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
    // Om rummet inte kunde läggas till returneras false
    else {
        echo false;
    }
}