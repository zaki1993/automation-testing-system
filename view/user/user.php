<?php
	class UserData {
		private $username;
		private $faculcyNumber;
		private $role;

		public function __construct($userDataRow) {

			if ($userDataRow!=NULL) {
				$this->username=$userDataRow["user_name"];
				$this->faculcyNumber=$userDataRow["faculty_number"];
				$this->role=$userDataRow["role_name"];
				$this->isAuthenticated=true;
			}
		}

		public function __login($candidatePass, $pass) {
			return password_verify($candidatePass, $pass);
		}

		public function getUsername() {
			return $this->username;
		}

		public function getFacultyNumber() {
			return $this->faculcyNumber;
		}

		public function getRole() {
			return $this->role;
		}

		public function isAuthenticated() {
			return $this->isAuthenticated;
		}
	}
?>