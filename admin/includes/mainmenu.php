<!-- Webbutveckling för mobila enheter, DT148G -->
		<nav id="mainmenu" class="sidenav js-menu">
            <ul class="active">
<?php
			if (isset($_SESSION['user_id'])) {
				// Hem
				echo "<li><a href='index.php'";
				if (getPath() == "/webbutveckling/dt148g/projekt/index.php") {
					echo " class='current'";
				}
				echo ">Hem</a></li>";
				// Användare
				echo "<li><a href='users.php'";
				if (getPath() == "/webbutveckling/dt148g/projekt/users.php") {
					echo " class='current'";
				}
				echo ">Användare</a></li>";
				// Om CleanApp
				echo "<li><a href='about.php'";
				if (getPath() == "/webbutveckling/dt148g/projekt/about.php") {
					echo " class='current'";
				}
				echo ">Om CleanApp</a></li>";
				// Logga ut
				echo "<li id='logout'><a href='logout.php'>Logga ut</a></li>";
			}
?>
		    </ul>
            
		</nav>