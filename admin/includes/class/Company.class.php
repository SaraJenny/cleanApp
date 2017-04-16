<?php
/* Sara Petersson - Webbutveckling för mobila enheter, DT148G
Skapar objekt som hämtar och sparar information om företag */

	class Company {

		public function __construct() {
			$this->db = new Database();
		}

        // Hämtar företags-id
        public function getId($company_name) {
			$company_name = $this->db->sanitize($company_name);
			$result = $this->db->select("SELECT company_id FROM company WHERE company_name = '$company_name'");
			return $result;
        }

        // Hämtar företagsnamn
        public function getName($company_id) {
			$result = $this->db->select("SELECT company_name FROM company WHERE company_id = $company_id");
			foreach ($result as $key) {
			 	$company_name = $key['company_name'];
			}
			return $company_name;
        }

        // Hämtar företagsnamn
        public function getCompanyName($userId) {
        	$result = $this->db->select("SELECT company_name FROM user, company
        								 WHERE user_id = $userId
        								 AND user.company_id = company.company_id");
        	if ($result) {
	        	foreach ($result as $key) {
    	    		$companyName = $key['company_name'];
        		}
        		return $companyName;
        	}
        }

		/* Saniterar företagsnamnet och lägger till det i databasen.
		Funktionen returnerar det nyinlagda företagets id */
		public function addCompany($company_name) {
			$company_name = $this->db->sanitize($company_name);
			$result = $this->db->query("INSERT INTO company (company_name) VALUES ('$company_name')");
			$company_id = $this->db->getId();
			return $company_id;
		}
	}