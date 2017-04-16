<?php
/* Sara Petersson - Webbutveckling för mobila enheter, DT148G
Skapar objekt som hämtar och sparar bilder */

	class Image {

		public function __construct() {
			$this->db = new Database();
		}

		// Skapar ett unikt filnamn genom att loopa fram en siffra tills filnamnet blir unikt
		public function file_newname($path, $filename) {
			// Kolla om en punkt hittas i filnamnet och spara vilken position den har i strängen
		    if ($position = strrpos($filename, '.')) {
		    	// Spara filnamn
				$name = substr($filename, 0, $position);
				// Spara filtyp
				$ext = substr($filename, $position);
		    }
		    // Om ingen punkt hittades, spara filnamn
		    else {
		        $name = $filename;
		    }
		    // Spara filens sökväg
		    $newpath = $path . '/' . $filename;
		    $newname = $filename;
		    $counter = 0;
		    // Loopa fram ett unikt namn
		    while (file_exists($newpath)) {
				$newname = $name . '_' . $counter . $ext;
				$newpath = $path.'/'.$newname;
				$counter++;
		    }
		    return $newname;
		}

		// Lägger till en profilbild och skapar en tumnagelbild och en miniatyrbild av den uppladdade bilden
		public function addImage($url, $roomId) {
			$url = $this->db->sanitize($url);
			$result = $this->db->query("INSERT INTO image (imageUrl, room_id) VALUES ('$url', $roomId)");
            // Om bilden lagts till skapas tumbild
            if ($result) {
            	// Skapar variabel för miniatyrbildernas namn
	            $thumbnail = "thumb_" . $url;
	            $mini = "mini_" . $url;
	            // Maximal storlek i höjd och bredd för miniatyrer
	            $width_thumbnail = 290;
	            $height_thumbnail = 550;
	            $width_mini = 29;
	            $height_mini = 29;

	            /* THUMB */
	            // Läser in originalstorleken på den uppladdade bilden, och sparar den i variabler
	            list($width_thumbnail_orig, $height_thumbnail_orig) = getimagesize('../img/uploads/' . $url);
	            // Räknar ut förhållandet mellan höjd och bredd, för att få samma förhållanden på miniatyren
	            $ratio_orig = $width_thumbnail_orig / $height_thumbnail_orig;                                      
	            // Räknar ut storlek på miniatyr
	            if ($width_thumbnail / $height_thumbnail > $ratio_orig) {
	                $width_thumbnail = $height_thumbnail * $ratio_orig;
	                $height_thumbnail = $width_thumbnail / $ratio_orig;
	            }
	            else {
	                $height_thumbnail = $width_thumbnail / $ratio_orig;
	                $width_thumbnail = $height_thumbnail * $ratio_orig;
	            }
	            //Skapar en ny miniatyrbild med rätt storlek
	            $image_p = imagecreatetruecolor($width_thumbnail, $height_thumbnail);
	            $image_j = imagecreatefromjpeg('../img/uploads/' . $url);
	            imagecopyresampled($image_p, $image_j, 0, 0, 0, 0, $width_thumbnail, $height_thumbnail, $width_thumbnail_orig, $height_thumbnail_orig);
	            //Sparar miniatyr
	            imagejpeg($image_p, '../img/uploads/' . $thumbnail);

	            /* MINI */
	            // Räknar ut storlek på miniatyr
	            if ($width_mini / $height_mini > $ratio_orig) {
	                $width_mini = $height_mini * $ratio_orig;
	                $height_mini = $width_mini / $ratio_orig;
	            }
	            else {
	                $height_mini = $width_mini / $ratio_orig;
	                $width_mini = $height_mini * $ratio_orig;
	            }
	            //Skapar en ny miniatyrbild med rätt storlek
	            $image_m = imagecreatetruecolor($width_mini, $height_mini);
	            $image_jp = imagecreatefromjpeg('../img/uploads/' . $url);
	            imagecopyresampled($image_m, $image_jp, 0, 0, 0, 0, $width_mini, $height_mini, $width_thumbnail_orig, $height_thumbnail_orig);
				//Sparar miniatyr
	            imagejpeg($image_m, '../img/uploads/' . $mini);

	            return true;
            }
			else {
				return false;
			}
		}

		// Hämtar rummets bild
		public function getImage($roomId) {
			$result = $this->db->select("SELECT imageUrl FROM image WHERE room_id = $roomId");
			if ($result) {
				foreach ($result as $key) {
					$url = $key['imageUrl'];
				}
				return $url;
			}
		}

		// Raderar rummets bild
		public function deleteImage($roomId) {
			// Hämtar bild (om rummet har en bild)
			$result = $this->db->select("SELECT imageUrl FROM image WHERE room_id = $roomId");
			if ($result) {
				foreach ($result as $key) {
					$url = $key['imageUrl'];
				}
				// Raderar bild
				$delete = $this->db->query("DELETE FROM image WHERE room_id = $roomId");
				// Returnerar url om raderingen lyckades, för att kunna radera bilderna från uploads-mappen
				if ($result) {
					return $url;
				}
				else {
					return false;
				}
			}
		}
	}