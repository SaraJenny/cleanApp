<?php
/* Sara Petersson - Webbutveckling för mobila enheter, DT148G
Skapar objekt som hämtar och sparar information om användare */

	class User {

		public function __construct() {
			$this->db = new Database();
		}

        // Hämtar ett företags alla användare
        public function getUsers($companyId) {
			$result = $this->db->select("SELECT * FROM user WHERE company_id = $companyId");
			return $result;
        }

        // Saniterar och kontrollerar om användare finns i databasen
        public function controlUser($email) {
			$email = $this->db->sanitize($email);
			$result = $this->db->select("SELECT * FROM user WHERE email = '$email'");
			return $result;
        }

        // Saniterar och jämför det inskrivna lösenordet med det som är sparat i databasen
        public function controlPassword($email, $password, $stored_password) {
	        $password = $this->db->sanitize($password);
            // Kollar om funktionen hash_equals finns, och jämför i så fall det inskrivna lösenordet med det lagrade
            if (function_exists('hash_equals')) {
		        if (hash_equals($stored_password, crypt($password, $stored_password))) {
		        	return true;
		        }
		        else {
		        	return false;
		        }
            }
            // Om funktionen inte existerar, sker fortfarande en jämförelse mellan lösenorden, men på annat vis
            else {
                if ($stored_password == crypt($password, $stored_password)) {
		        	return true;
		        }
		        else {
		        	return false;
		        }
            }
        }

		/* Saniterar användarinformation, krypterar lösenordet och lägger till användare i databasen.
		Funktionen returnerar den nyinlagda användarens userId */
		public function addUser($firstname, $lastname, $email, $password, $admin, $company_id) {
			$firstname = $this->db->sanitize($firstname);
			$lastname = $this->db->sanitize($lastname);
			$email = $this->db->sanitize($email);
			$password = $this->db->sanitize($password);
			$secure_password = $this->db->securePassword($password);
			$result = $this->db->query("INSERT INTO user (email, password, firstname, lastname, admin, company_id) 
										VALUES ('$email', '$secure_password', '$firstname', '$lastname', $admin, $company_id)");
			$userId = $this->db->getId();
			return $userId;
		}

		// Saniterar användarinformation och uppdaterar användare i databasen
		public function updateUser($firstname, $lastname, $email, $userAdmin, $userId) {
			$firstname = $this->db->sanitize($firstname);
			$lastname = $this->db->sanitize($lastname);
			$email = $this->db->sanitize($email);
			$result = $this->db->query("UPDATE user SET firstname = '$firstname', lastname = '$lastname', email = '$email', admin = $userAdmin
										WHERE user_id = $userId");
			if ($result) {
				return true;
			}
			else {
				return false;
			}
		}

		// Saniterar och uppdaterar förnamnet
		public function updateFirstname($userId, $firstname) {
			$firstname = $this->db->sanitize($firstname);
			$result = $this->db->query("UPDATE user SET firstname = '$firstname' WHERE user_id = $userId");
			if ($result) {
				return true;
			}
			else {
				return false;
			}
		}

		// Saniterar och uppdaterar efternamnet
		public function updateLastname($userId, $lastname) {
			$lastname = $this->db->sanitize($lastname);
			$result = $this->db->query("UPDATE user SET lastname = '$lastname' WHERE user_id = $userId");
			if ($result) {
				return true;
			}
			else {
				return false;
			}
		}

		// Saniterar och uppdaterar e-posten
		public function updateEmail($userId, $email) {
			$email = $this->db->sanitize($email);
			$result = $this->db->query("UPDATE user SET email = '$email' WHERE user_id = $userId");
			if ($result) {
				return true;
			}
			else {
				return false;
			}
		}

		// Hämtar information om vald användare
		public function getUserInfo($userId) {
			$user = $this->db->select("SELECT * FROM user WHERE user_id = $userId");
			return $user;
		}

		// Hämtar administrationsrättigheter
		public function getAdmin($user_id) {
			$result = $this->db->select("SELECT admin FROM user WHERE user_id = $user_id");
			foreach ($result as $key) {
				$admin = $key['admin'];
			}
			return $admin;
		}

		// Hämtar företags-id för en användare
		public function getCompanyId($user_id) {
			$result = $this->db->select("SELECT company_id FROM user WHERE user_id = $user_id");
			foreach ($result as $key) {
				$company_id = $key['company_id'];
			}
			return $company_id;
		}

		// Uppdaterar en användares lösenord, efter att det har saniterats och hashats
		public function changePassword($userId, $password) {
			$password = $this->db->sanitize($password);
			$secure_password = $this->db->securePassword($password);
			$result = $this->db->query("UPDATE user SET password = '$secure_password' WHERE user_id = $userId");
			if ($result) {
				return true;
			}
			else {
				return false;
			}
		}

		/* Kollar om användaren som ska raderas är den senaste som uppdaterat ett rum,
		i så fall ändras detta till att vara den som raderar användaren som står där i stället.
		Därefter raderas användaren. */
		public function deleteUser($deleteUser, $userId) {
			$rooms = $this->db->select("SELECT room_id FROM room WHERE user_id = $deleteUser");
			if ($rooms != NULL) {
				foreach ($rooms as $key) {
					$roomId = $key['room_id'];
					$result = $this->db->query("UPDATE room SET user_id = $userId WHERE room_id = $roomId");
				}
			}
			$result = $this->db->query("DELETE FROM user WHERE user_id = $deleteUser");
			if ($result) {
				return true;
			}
		}
	}