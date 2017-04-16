<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Hämta företagets namn
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['userId'])) {
	$userId = $_POST['userId'];
    // Hämtar företagsnamnet
    $result = $company->getCompanyName($userId);
    if ($result) {
        echo $result;
    }
    else {
        echo false;
    }
}