<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Lista med alla företagets rum med status och möjlighet att redigera och radera samt lägga till rum
*/

// Undersidans titel
$page_title = "Städningsstatus";
// Hämtar in headern
include("includes/header.php");

$roomName = $roomNameErr = $desc = '';

/*--------------------- För användare som inaktiverat javascript ---------------------*/
// Om användaren tryckt på "Lägg till" valideras formulär
if (isset($_POST["no-js-addRoomButton"])) {
    // Kollar om rumnsnamn fyllts i
    if (empty($_POST['roomName'])) {
        // Felmeddelande
        $roomNameErr = "Du måste fylla i rummets namn";
    }
    else {
        $roomName = $_POST['roomName'];
    }
    $desc = $_POST['desc'];
    // Om inga felmeddelanden är satta sparas rummet i databasen
    if ($roomNameErr == '') {
    	$status = 0;
    	$now = $date . " " . $time;
        $result = $room->addRoom($roomName, $desc, $company_id, $user_id, $status, $now);
        if ($result == true) {
        	unset($_GET["add"]);
        }
        // Om rummet inte kunde sparas i databasen
        else {
            $roomNameErr = "Tyvärr, rummet kunde inte sparas i databasen.";
        }
    }
}
// Om användaren har tryckt på radera, så raderas rummet och dess bild från databasen
if (isset($_GET["del"])) {
	$roomId = $_GET["del"];
    // Hämta rummets bild
    $roomImage = $image->getImage($roomId);
    // Om rummet har en bild så raderas den
    if ($roomImage != NULL) {
        $url = $image->deleteImage($roomId);
        // Om raderingen lyckades raderas bilden från uploads-mappen
        if ($url != false) {
            unlink("img/uploads/" . $url);
            unlink("img/uploads/thumb_" . $url);
            unlink("img/uploads/mini_" . $url);
        }
    }
    // Radera rummet
	$delete = $room->deleteRoom($roomId);
	if ($delete == true) {
		// Inaktivera knapptryckning
        unset($_GET["del"]);
	}
}

/*------------------------------------------------------------------------------------*/

// Om sessionsvariabel med användarid är satt skrivs sidan ut
if (isset($_SESSION["user_id"])) {
	$rooms = $room->getRooms($company_id);
?>
	<div class="mainContainer">
		<h2><?php echo $company_name ?></h2>
			<a href="index.php?add=true" id="addRoomFormButton" class="button">Lägg till rum</a>
			<!-- Formulär -->
<?php
			/* För användare som avaktiverat JavaScript samt använder en mindre skärm */
			if (isset($_GET["add"]) && $_GET["add"] == "true") {
?>
				<form method="post">
					<label for="roomName">Rumsnamn</label>
	                <input placeholder="Rumsnamn" type="text" name="roomName" value="<?php echo $roomName; ?>">
	                <p class="errorMessageBox"><?php echo $roomNameErr; ?></p>

					<label for="desc">Beskrivning</label>
					<textarea name="desc" id="desc"><?php echo $desc; ?></textarea>

	                <input id="no-js-addRoomButton" name="no-js-addRoomButton" type="submit" class="button" value="Lägg till rum">
				</form>
<?php
			}
			else {
?>
				<section id="addRoom">
					<h3>Lägg till rum</h3>
					<!-- För användare som avaktiverat JavaScript samt använder en större skärm -->
					<form id="no-js-form" method="post">
						<label for="roomName">Rumsnamn</label>
		                <input placeholder="Rumsnamn" type="text" name="roomName" value="<?php echo $roomName; ?>">
		                <p class="errorMessageBox"><?php echo $roomNameErr; ?></p>

						<label for="desc">Beskrivning</label>
						<textarea name="desc" id="desc"><?php echo $desc; ?></textarea>

		                <input id="no-js-addRoomButton" name="no-js-addRoomButton" type="submit" class="button" value="Lägg till rum">
					</form>
					<!-- Lägg till formulär (med JS) -->
					<form id="addRoomForm" method="post">
						<label for="roomName">Rumsnamn</label>
		                <input placeholder="Rumsnamn" type="text" name="roomName" id="roomName" value="<?php echo $roomName; ?>">

						<label for="editor">Beskrivning</label>
						<textarea name="editor" id="editor"><?php echo $desc; ?></textarea>
		            	<script>CKEDITOR.replace('editor');</script>

		                <input id="addRoomButton" name="addRoomButton" type="submit" class="button" value="Lägg till rum">
					</form>
				</section><!-- /#addRoom -->
<?php
			}
			//Skriver ut alla rum
?>
			<section id="allRooms">
				<h3>Städningsstatus</h3>
<?php
				if ($rooms != NULL) {
					foreach ($rooms as $key) {
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
								<a href="room.php?id=<?php echo $key['room_id']; ?>" class="no-js">Redigera</a>
								<a href="index.php?del=<?php echo $key['room_id']; ?>" class="no-js delete">Radera</a>
							</div>
						</div>
<?php
					}
				}
				else {
?>
					<p class="center">Inga rum inlagda</p>
<?php
				}
?>
			</section><!-- /#allRooms -->
		</div><!-- /#mainContainer -->
<?php
}
// Om användaren inte är inloggad skickas denna till inloggningssidan
else {
    header("Location: login.php");
    exit;
}
include("includes/footer.php");