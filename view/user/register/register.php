<?php
	class Registrator {

		public function __construct() {
			$this->__init();
		}

		private function __init() {
			require_once "../../../model/dao.php";
		}

		public function register($username, $facultyNumber, $password, $confirmPassword) {

			$isSamePass=$this->validatePassword($password, $confirmPassword);
			if($isSamePass) {
				$this->insertUser($username, $facultyNumber, password_hash($password, PASSWORD_DEFAULT));
			}
		}

		private function validatePassword($password, $confirmPassword) {
			return $password===$confirmPassword;
		}

		private function insertUser($username, $facultyNumber, $password) {
							echo "INSERT INTO User VALUES(${username}, ${facultyNumber}, ${password});";

			$dbConn=new Dao();
			$dbConn->executeInsert('INSERT INTO User VALUES(?, ?, ?);', [$username, $facultyNumber, $password]);
			$dbConn->executeInsert('INSERT INTO User_Roles VALUES(?, ?);', [$username, "user"]);
		}

		public function getUser() {
			return $this->userData;
		}
	}
?>