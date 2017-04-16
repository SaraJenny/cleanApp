<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Lägger till bild
*/
header('Content-Type: application/json');

// Läser in config-filen
include("../includes/config.php");

$uploaded = array();

if(!empty($_FILES['file']['name'])) {
	// Hämtar och saniterar bildnamnet
	$name = basename($_FILES['file']['name'][0]);
	// Ersätter ev. mellanslag i filnamnet
	$name = str_replace(" ","-", $name);
	// Kollar om filnamnet redan existerar
	if (file_exists("../img/uploads/" . $name)) {
       	// Skapa ett unikt filnamn
		$name = $image->file_newname('../img/uploads', $_FILES["file"]["name"][0]);
	}
	// Om filen kan flyttas till slutmappen, sparas namn och sökväg
	if(move_uploaded_file($_FILES['file']['tmp_name'][0], '../img/uploads/' . $name)) {
		$uploaded[] = array(
			'name' => $name,
			'file' => 'img/uploads/thumb_' . $name
		);
		$roomId = $_GET["id"];
		// Kollar om rummet redan har en bild uppladdad
        $photo = $image->getImage($roomId);
        // Om bild redan fanns, raderas den
        if ($photo != NULL) {
        	$url = $image->deleteImage($roomId);
        	if ($url != false) {
        		// Raderar bilder från uploads-mappen
		        unlink("../img/uploads/" . $url);
		        unlink("../img/uploads/thumb_" . $url);
		        unlink("../img/uploads/mini_" . $url);
        	}
        }
		// Lägger till bilden i databasen
        $result = $image->addImage($name, $roomId);
        if ($result) {
            echo json_encode($uploaded);
        }
	}
}