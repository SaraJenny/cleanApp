<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
/*
* Sara Petersson - Webbutveckling för mobila enheter, DT148G
* Hämta information om användare
*/

// Läser in config-filen
include("../includes/config.php");

// Kollar att alla parametrar har satts
if(isset($_POST['userId'])) {
	$userId = $_POST['userId'];
    // Hämtar information om användare
    $result = $user->getUserInfo($userId);
    if ($result) {
        foreach ($result as $key) {
?>
            <div class="content-block-title">Min profil</div>
			<div class="list-block">
			  <ul>
			    <li>
			      <div id="firstnameBox" class="item-content">
			        <div class="item-media"><i class="icon icon-form-name"></i></div>
			        <div class="item-inner">
			          <div class="item-input">
			            <input type="text" placeholder="Förnamn" name="firstname" id="firstname" value="<?php echo $key['firstname']; ?>">
			          </div>
			        </div>
			      </div>
			    </li>
			    <li>
			      <div id="lastnameBox" class="item-content">
			        <div class="item-media"><i class="icon icon-form-name"></i></div>
			        <div class="item-inner">
			          <div class="item-input">
			            <input type="text" placeholder="Efternamn" name="lastname" id="lastname" value="<?php echo $key['lastname']; ?>">
			          </div>
			        </div>
			      </div>
			    </li>
			    <li>
			      <div id="emailBox" class="item-content">
			        <div class="item-media"><i class="icon icon-form-email"></i></div>
			        <div class="item-inner">
			          <div class="item-input">
			            <input type="email" placeholder="E-post" name="newEmail" id="newEmail" value="<?php echo $key['email']; ?>">
			          </div>
			        </div>
			      </div>
			    </li>
			  </ul>
			</div>
			<div class="content-block-title">Byt lösenord</div>
			<div class="list-block">
			  <ul>
			    <li>
			      <div id="passwordBox" class="item-content">
			        <div class="item-media"><i class="icon icon-form-password"></i></div>
			        <div class="item-inner">
			          <div class="item-input">
			            <input type="password" placeholder="Nytt lösenord" name="newPassword" id="newPassword">
			              <a href="#" id="passwordButton" class="button color-cyan">OK</a>
			          </div>
			        </div>
			      </div>
			    </li>
			  </ul>
			</div>
<?php
        }
    }
    else {
        echo false;
    }
}