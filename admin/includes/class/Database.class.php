<?php
/* Webbutveckling för mobila enheter, DT148G
Ansluter till databasen och skapar olika funktioner för att fråga databasen.

Kod skapad delvis med hjälp av denna artikel: https://www.binpress.com/tutorial/using-php-with-mysql-the-right-way/17 */

	class Database {
	    // Databasanslutning
	    protected static $connection;

	    // Ansluter till databasen
	    public function connect() {    
	        // Försöker ansluta till databasen
	        if(!isset(self::$connection)) {
	        	// Spara konfigurationen i en array
	            $config = parse_ini_file('ini/config.ini');
	            self::$connection = new mysqli($config['database'], $config['username'], $config['password'], $config['dbname']);
	        }

	        // Om anslutningen misslyckas
	        if(self::$connection === false) {
	            echo "<p>Fel med anslutningen, kontakta <a href='mailto:sara@doggie-zen.se'>administratör</a>.</p>";
	            return false;
	        }
	        return self::$connection;
	    }

	    // Ställ fråga till databasen
	    public function query($query) {
	        // Anslut till databasen
	        $connection = $this->connect();
	        // Ställ fråga till databasen
	        $result = $connection->query($query);
	        // Om lyckas
	        if ($result) {
		        //Returnera resultat
		        return $result;
	    	}
	    	// Annars, hämta felmeddelande
	    	else {
	    		$this->error();
	    	}
	    }

	    // Hämta rader från databasen (SELECT)
	    public function select($query) {
	    	// Skapa array
	        $rows = array();
	        // Ställ fråga
	        $result = $this->query($query);
	        // Om inget resultat
	        if ($result === false) {
	            return false;
	        }
	        // Loopa igenom resultatet
	        while ($row = $result->fetch_assoc()) {
	        	// Spara i array
	            $rows[] = $row;
	        }
	        // Returnera array
	        return $rows;
	    }

	    // Hämta senaste id
	    public function getId() {
	        // Anslut till databasen
	        $connection = $this->connect();
	    	$id = $connection->insert_id;
	    	return $id;
	    }

	    // Hämta det senaste felet från databasen
	    public function error() {
	        // Anslut till databasen
	        $connection = $this->connect();
	        // Skriv ut felmeddelande
	        echo $connection->error;
	    }

	    // Saniterar formulärdata
	    public function sanitize($value) {
			// Tar bort mellanslag före och efter
			$value = trim($value);
			// Tar bort backslashes
			$value = stripslashes($value);
			// Omvandlar speciella tecken till HTML-entiteter
			$value = htmlspecialchars($value);
	        $connection = $this->connect();
	        // Returnera variabel som saniterats med real_escape_string()
	        return $connection->real_escape_string($value);
	    }

		// Saltar och hashar lösenord
		public function securePassword($password) {
			// Skapa salt med två funktioner som skapar randomiserade nummer
			$salt = "uhd_f3847rnhfuy#egug-ew47sfdg3" . substr(microtime(), 2, 4) . "8ji#dDfi83hHF)fj-#fj9FSufjh" . mt_rand(100, 999999);
			$secure_password = crypt($password, $salt);
			return $secure_password;
		}
	}