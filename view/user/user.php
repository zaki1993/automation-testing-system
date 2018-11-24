<?php
	class UserData {
		private $username;
		private $faculcyNumber;
		private $role;
		private $isAuthenticated;

		public function __construct($userDataRow, $password) {
			if ($userDataRow!=NULL) {
				$isAuthenticated=$this->__login($password, $userDataRow["password"]);
				if($isAuthenticated) {
					$this->username=$userDataRow["user_name"];
					$this->faculcyNumber=$userDataRow["faculty_number"];
					$this->role=$userDataRow["role_name"];
					$this->isAuthenticated=true;
				} else {
					$this->isAuthenticated=false;
				}
			}
		}

		private function __login($candidatePass, $pass) {
			return password_verify($candidatePass, $pass);
		}

		public function getUsername() {
			return $this->username;
		}

		public function getFaculcyNumber() {
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