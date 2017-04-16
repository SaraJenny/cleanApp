<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Sida för alla rum - skapas utifrån vilka parametrar som är satta i länken
*/
// Undersidans titel
$page_title = "Redigera rum";
// Hämtar in headern
include("includes/header.php");

$roomName = $roomNameErr = $desc = $errorMessage = $message = '';

// Kollar att användaren är inloggad
if (isset($_SESSION["user_id"])) {
	// Kollar om parametrar är satt för att skriva ut information om ett rum
	if (isset($_GET["id"])) {
		$roomId = $_GET["id"];

		/*--------------------- För användare som inaktiverat javascript ---------------------*/
		// Om användaren tryckt på "Spara ändringar" valideras formulär
		if (isset($_POST["editRoomButton"])) {
		    // Kollar om rumnsnamn fyllts i
		    if (empty($_POST['roomName'])) {
		        // Felmeddelande
		        $roomNameErr = "Du måste fylla i rumments namn";
		    }
		    else {
		        $roomName = $_POST['roomName'];
		    }
		    $desc = $_POST['desc'];
		    if (isset($_POST['status'])) {
		    	$status = 1;
		    }
		    else {
		    	$status = 0;
		    }
		    // Om inga felmeddelanden är satta sparas rummet i databasen
		    if ($roomNameErr == '') {
		    	$now = $date . " " . $time;
		        $result = $room->updateRoom($roomName, $desc, $status, $now, $roomId);
		        // 
		        if ($result == true) {
		        	$message = "Uppgifterna har sparats";
		        }
		        // Om rummet inte kunde sparas i databasen
		        else {
		            $roomNameErr = "Tyvärr, ändringarna kunde inte sparas i databasen.";
		        }
		    }
		}
		// Om användaren tryckt på "Radera", så raderas bilden
		if (isset($_GET["del"])) {
			$url = $image->deleteImage($roomId);
			if ($url != false) {
				// Radera bild & tumbild från mappen uploads
				unlink("img/uploads/" . $url);
				unlink("img/uploads/thumb_" . $url);
				unlink("img/uploads/mini_" . $url);
				// Inaktivera knapptryckning
		        unset($_GET["del"]);
			}
		}

/*------------------------------------------------------------------------------------*/


		// Hämtar rummets företags-id
		$roomCompanyId = $room->getCompanyId($roomId);
		// Om användaren har behörighet att se aktuellt rum skrivs det ut
		if ($roomCompanyId == $company_id) {
			$roomInfo = $room->getRoom($roomId);
			foreach ($roomInfo as $key) {
				$roomName = $key['room_name'];
				$desc = $key['description'];
				$status = $key['status'];
			}
			// Hämtar info om bild
    		$photoURL = $image->getImage($roomId);
?>
			<div class="mainContainer">
				<h2><?php echo $company_name ?></h2>
				<h3>Redigera rum</h3>
				<section class="mainSection">
					<p class="clean"><?php echo $message; ?></p>
					<form id="updateRoomForm" method="post">
						<label for="roomName">Rumsnamn</label>
		                <input placeholder="Rumsnamn" type="text" name="roomName" id="roomName" value="<?php echo $roomName; ?>">
		                <p class="errorMessageBox"><?php echo $roomNameErr; ?></p>

		                <label for="status" class="statusLabel">Städat</label>
		                <input class="checkbox" type="checkbox" name="status" value="status" id="status" 
<?php
						// Kollar om rummet är städat eller inte
						if ($status == 1) {
							echo 'checked="checked"';
						}
?>
		                >

						<label for="desc">Beskrivning</label>
						<textarea name="desc" id="desc"><?php echo $desc; ?></textarea>
		            	<script>CKEDITOR.replace('desc');</script>

		                <input id="editRoomButton" name="editRoomButton" type="submit" class="button" value="Spara ändringar">
					</form>
				</section>
				<aside>
			        <!-- Ladda upp bild -->
			        <div class="dropzone" id="dropzone">
<?php
					// Hämtar vilken typ av fel som är satt
					if (isset($_GET["error"])) {
						$error = $_GET["error"];
						if ($error == "img") {
							$errorMessage = "Du måste välja en bild";
						}
						if ($error == "size") {
							$errorMessage = "Filen är för stor (max 500kB)";
						}
						if ($error == "type") {
							$errorMessage = "Endast jpeg är tillåtet";
						}
					}
			        // Om bild finns skrivs den och en radera-knapp ut
			        if ($photoURL != NULL) {
?>
			            <img src="img/uploads/thumb_<?php echo $photoURL; ?>" alt="">
			            <a id="deleteImageButton" class="delete" href="#">
			            	<i class="fa fa-trash-o" aria-hidden="true"></i>
			            </a>
                    	<a href="room.php?id=<?php echo $roomId; ?>&del" class="no-js delete no-js-delete">Radera</a>
<?php
			        }
?>
			        </div>
			        <form action="ajax/upload.php?id=<?php echo $roomId; ?>" method="post" enctype="multipart/form-data" id="uploadForm">
					    <input type="file" name="fileToUpload" id="fileToUpload">
				        <!-- Felmeddelanden för bilduppladdningen -->
				        <div id="uploads" class="errorMessageBox"><?php echo $errorMessage; ?></div>
					    <input type="submit" value="Ladda upp bild" name="submitImage" id="submitImage" class="button">
					</form>
				</aside>
			</div>
<?php
		}
		// Om användaren inte har behörighet att se aktuellt rum skickas besökaren tillbaka till index.php
		else {
			header("Location: index.php");
	    	exit;
		}
	}
	// Om inte parametrar är satta skickas besökaren till index-sidan
	else {
	    header("Location: index.php");
	    exit;
	}
}
// Om användaren inte är inloggad skickas denna till inloggningssidan
else {
    header("Location: login.php");
    exit;
}
include("includes/footer.php");