<?php
/* Webbutveckling för mobila enheter, DT148G */

// Startar session
session_start();
// Webbplatsens titel
$site_title = "CleanApp";
// Webbplatsens avdelare
$divider = " | ";
//Funktion som hämtar sökväg
function getPath() {
    $path = $_SERVER['PHP_SELF'];
    return $path;
}
// Aktiverar autoload för att snabba upp registrering av klasser
spl_autoload_register(function ($classObject) {
    include __DIR__.'/class/' . $classObject . '.class.php';
});
// Skapar objekt
$company = new Company();
$room = new Room();
$user = new User();
$image = new Image();
// Om användaren är inloggad hämtas dess admin-status samt dagens datum och tid
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $admin = $user->getAdmin($user_id);
	$company_id = $user->getCompanyId($user_id);
	$company_name = $company->getName($company_id);
    $date = date('Y-m-d');
    $time = date('H:i:s');
}