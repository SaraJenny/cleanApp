<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Lägger till bild
*/

// Läser in config-filen
include("../includes/config.php");

// Sparar rummets id
$roomId = $_GET["id"];
// Hämtar och saniterar bildnamnet
$name = basename($_FILES['fileToUpload']['name']);
// Ersätter ev. mellanslag i filnamnet
$name = str_replace(" ","-", $name);
// Kollar om filnamnet redan existerar
if (file_exists("../img/uploads/" . $name)) {
   	// Skapa ett unikt filnamn
	$name = $image->file_newname('../img/uploads', $_FILES["fileToUpload"]["name"]);
}
$hasError = false;
// Filtyp
$imageFileType = pathinfo("../img/uploads/" . basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION);
// Kollar om en bild har valts
if(isset($_POST["submitImage"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check == false) {
        $hasError = true;
        header("Location: ../room.php?id=" . $roomId . "&error=img");
        exit;
    }
}
// Kollar filstorlek
if ($_FILES["fileToUpload"]["size"] > 500000) {
    $hasError = true;
    header("Location: ../room.php?id=" . $roomId . "&error=size");
    exit;
}
// Kollar att bilden är en jpeg
if($imageFileType != "jpg" && $imageFileType != "jpeg") {
    $hasError = true;
    header("Location: ../room.php?id=" . $roomId . "&error=type");
    exit;
}
// Kollar att inga felmeddelanden är satta
if ($hasError == false) {
    // Om filen kan flyttas till slutmappen, sparas namn och sökväg
    if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], '../img/uploads/' . $name)) {
    	$uploaded[] = array(
    		'name' => $name,
    		'file' => '../img/uploads/thumb_' . $name
    	);
    	// Kollar om rummet redan har en bild uppladdad
        $photo = $image->getImage($roomId);
        // Om bild redan fanns, raderas den
        if ($photo != NULL) {
        	$result = $image->deleteImage($roomId);
        }
    	// Lägger till bilden i databasen
        $result = $image->addImage($name, $roomId);
        header("Location: ../room.php?id=" . $roomId);
        exit;
    }
}