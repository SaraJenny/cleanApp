<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Hämta information om ostädade rum
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['userId'])) {
	$userId = $_POST['userId'];
    // Hämtar ostädade rum
    $result = $room->getAllRooms($userId);
    if ($result) {
?>
		<div class="content-block-title">Att göra</div>
		<div class="list-block">
		  <ul>
<?php
        foreach ($result as $key) {
        	// Hämta rummets bild
        	$roomImage = $image->getImage($key['room_id']);
        	// Avkodar html-entiteter så att de skrivs ut korrekt
			$desc = html_entity_decode($key['description']);
?>
				<li class="swipeout
<?php
				// Om rummet är städat sätts klassen hide, så att det inte syns i listan
				if ($key['status'] == 1) {
					echo " hide";
				}
?>
				" id="room_<?php echo $key['room_id']; ?>">
					<a href="#" data-picker=".picker-<?php echo $key['room_id']; ?>" class="item-link swipeout-content item-content open-picker">
						<div class="item-media roomImage">
<?php
						// Om bild finns för rummet skrivs den ut
						if ($roomImage != NULL) {
							echo '<img src="http://localhost:1337/webbutveckling/dt148g/projekt/img/uploads/mini_' . $roomImage . '" alt="">';
						}
?>
						</div>
						<div class="item-inner">
							<div class="item-title"><?php echo $key['room_name']; ?></div>
						</div>
					</a>
					<div class="swipeout-actions-left">
						<a href="#" class="action1" id="swipe_<?php echo $key['room_id']; ?>">Klar</a>
					</div>

			    </li>
				<!-- Picker -->
				<div class="picker-modal picker-<?php echo $key['room_id']; ?>">
					<div class="toolbar">
						<div class="toolbar-inner">
							<div class="left"></div>
							<div class="right"><a href="#" class="close-picker">X</a></div>
						</div>
					</div>
					<div class="picker-modal-inner">
						<div class="content-block">
<?php
							// Om bild finns för rummet skrivs den ut
				        	if ($roomImage != NULL) {
				        		echo '<img src="http://localhost:1337/webbutveckling/dt148g/projekt/img/uploads/thumb_' . $roomImage . '" alt="" class="roomInfoImage">';
				        	}
?>
							<h4><?php echo $key['room_name']; ?></h4>
							<div><?php echo $desc; ?></div>
							<a href="#" class="button button-big button-fill button-raised color-cyan changeStatus" id="<?php echo $key['room_id']; ?>">Markera som städat</a>
						</div>
					</div>
				</div>
<?php
        }
?>
			  </ul>
			</div>
<?php
    }
    else {
        echo false;
    }
}