<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Denna sida loggar ut användare
*/
// Startar session
session_start();
// Förstör session
session_destroy();
// Skicka till inloggningssidan
header("Location: login.php");
exit;