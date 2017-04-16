			</section><!-- /#mainContent -->
				<!-- Sidfot -->
				<footer>
<?php
				// Kollar om sessionsvariabel är satt och skriver i så fall ut innehåll i footern
				if (isset($_SESSION["user_id"])) {
?>
					<p><a href='logout.php'>Logga ut</a></p>
<?php
				}
?>
					<p id="author">Sara Petersson <span class="divider">|</span> Webbutveckling för mobila enheter</p>
				</footer><!-- Sidfot slut -->
		<!-- jQuery -->
		<script src="http://code.jquery.com/jquery-2.2.0.min.js"></script>
        <!-- JS-fil -->
        <script src="js/index.js"></script>
	</body>
</html>