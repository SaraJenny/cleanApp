<?php
/* Sara Petersson - Webbutveckling för mobila enheter, DT148G
Skapar objekt som hämtar och sparar information om rum */

	class Room {

		public function __construct() {
			$this->db = new Database();
		}

		// Hämtar alla rum
		public function getRooms($company_id) {
			$result = $this->db->select("SELECT room.*, firstname, lastname FROM room, user
										 WHERE room.company_id = $company_id
										 AND room.user_id = user.user_id
										 ORDER BY room_name");
			return $result;
		}

		// Hämtar alla rum
		public function getAllRooms($userId) {
			$result = $this->db->select("SELECT room.* FROM room, user
										 WHERE user.user_id = $userId
										 AND user.company_id = room.company_id
										 ORDER BY room_name");
			if ($result) {
				return $result;
			}			
		}

		// Hämtar info om ett rum
		public function getRoom($roomId) {
			$result = $this->db->select("SELECT * FROM room WHERE room_id = $roomId");
			return $result;
		}

		// Hämtar företags-id
		public function getCompanyId($room_id) {
			$result = $this->db->select("SELECT company_id FROM room WHERE room_id = $room_id");
			foreach ($result as $key) {
				$company_id = $key['company_id'];
			}
			return $company_id;
		}

		// Hämtar antalet rum som ska städas
		public function getToDoNumber($userId) {
		    $result = $this->db->select("SELECT COUNT(*) FROM room, user
		    							 WHERE user.user_id = $userId
		    							 AND user.company_id = room.company_id
		    							 AND status = 0");
			foreach ($result as $key) {
				$number = $key['COUNT(*)'];
			}
			return $number;
		}

		// Saniterar information och lägger till rum i databasen.
		public function addRoom($roomName, $desc, $company_id, $user_id, $status, $now) {
			$roomName = strip_tags($roomName);
			$roomName = $this->db->sanitize($roomName);
			$desc = strip_tags($desc, "<p>, <em>, <strong>, <ol>, <ul>, <li>, <a>, <br>");
			$desc = $this->db->sanitize($desc);
			$result = $this->db->query("INSERT INTO room (room_name, description, company_id, user_id, status, datetime) 
										VALUES ('$roomName', '$desc', $company_id, $user_id, $status, '$now')");
			if ($result) {
				return true;
			}
			else {
				return false;
			}
		}

		// Uppdaterar ett rums information
		public function updateRoom($roomName, $desc, $status, $now, $roomId) {
			$roomName = strip_tags($roomName);
			$roomName = $this->db->sanitize($roomName);
			$desc = strip_tags($desc, "<p>, <em>, <strong>, <ol>, <ul>, <li>, <a>, <br>");
			$desc = $this->db->sanitize($desc);
			$result = $this->db->query("UPDATE room SET room_name = '$roomName', description = '$desc', status = $status, datetime = '$now'
										WHERE room_id = $roomId");
			if ($result) {
				return true;
			}
		}

		// Ändrar ett rums staus, samt vem som gjorde ändringen och när
		public function changeStatus($roomId, $userId, $status, $now) {
			$result = $this->db->query("UPDATE room SET status = $status, user_id = $userId, datetime = '$now' WHERE room_id = $roomId");
			if ($result) {
				return true;
			}
		}

		// Raderar valt rum
		public function deleteRoom($roomId) {
			$result = $this->db->query("DELETE FROM room WHERE room_id = $roomId");
			if ($result) {
				return true;
			}
			else {
				return false;
			}
		}
	}