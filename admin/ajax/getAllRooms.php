<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Hämta information om alla rum
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['userId'])) {
	$userId = $_POST['userId'];
    // Hämtar alla rum
    $result = $room->getAllRooms($userId);
?>
	<div class="content-block-title">Alla rum</div>
	<div class="list-block">
		<ul>
<?php
    if ($result) {
        foreach ($result as $key) {
        	// Hämta rummets bild
        	$roomImage = $image->getImage($key['room_id']);
        	// Avkodar html-entiteter så att de skrivs ut korrekt
			$desc = html_entity_decode($key['description']);
			if ($key['status'] == 1) {
				$checked = 'checked="checked"';
				$status = 'Ostädat?';
				$buttonText = 'Markera som ostädat';
			}
			else {
				$checked = '';
				$status = 'Städat?';
				$buttonText = 'Markera som städat';
			}
?>
				<li class="swipeout" id="roomAll_<?php echo $key['room_id']; ?>">
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
						<a href="#" class="action1" id="swipeAll_<?php echo $key['room_id']; ?>"><?php echo $status; ?></a>
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
				        		echo '<img src="http://localhost:1337/webbutveckling/dt148g/projekt/img/uploads/thumb_' . $roomImage . '" alt="">';
				        	}
?>
							<h4><?php echo $key['room_name']; ?></h4>
							<div><?php echo $desc; ?></div>
							<a href="#" class="button button-big button-fill button-raised color-cyan changeStatus" id="button_<?php echo $key['room_id']; ?>"><?php echo $buttonText; ?></a>
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