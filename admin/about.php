<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Information om CleanApp
*/

// Undersidans titel
$page_title = "Om CleanApp";
// Hämtar in headern
include("includes/header.php");


/*------------------------------------------------------------------------------------*/

// Om sessionsvariabel med användarid är satt skrivs sidan ut
if (isset($_SESSION["user_id"])) {
?>
	<section class="box">
		<h2>Om CleanApp</h2>
		<p>CleanApp är appen för företaget som behöver en inrapporteringstjänst för städning. CleanApp kommer både med ett administrationsgränssnitt och en mobilapplikation.</p>
		<h3>CleanApp admin</h3>
		<ul>
			<li>Registrera företagskonto</li>
			<li>Lägga till rum med möjlighet att ange specifika instruktioner för varje rum</li>
			<li>Redigera information om rum</li>
			<li>Lägga till bild på rum</li>
			<li>Radera rum</li>
			<li>Se om ett rum är städat, och i så fall när och av vem</li>
			<li>Ändra ett rums städat från städat/ostädat till ostädat/städat</li>
			<li>Lägga till användare</li>
			<li>Tilldela användare administratörsrättigheter</li>
			<li>Redigera användare</li>
			<li>Radera användare</li>
		</ul>
		<h3>CleanApp mobilapplikation</h3>
		<ul>
			<li>Se lista med ostädade rum</li>
			<li>Markera rum som städade/ostädade</li>
			<li>Se information om rum (t.ex. instruktioner och bild)</li>
			<li>Se lista med alla rum</li>
			<li>Se och redigera sina egna användaruppgifter</li>
		</ul>
	</section>
<?php
}
// Om användaren inte är inloggad skickas denna till inloggningssidan
else {
    header("Location: login.php");
    exit;
}
include("includes/footer.php");