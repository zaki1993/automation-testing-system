<?php
	class Authenticator {

		private $userData;

		public function __construct() {
			$this->__init();
			$this->userData=NULL;
		}

		private function __init() {
			require_once "../../../model/dao.php";
			require_once "../user.php";
		}

		public function login($username, $password) {
			$dbConn=new Dao();
			$rawUserData=$dbConn->executeQuery("SELECT u.user_name, u.faculty_number, u.password, ur.role_name FROM User u, User_Roles ur WHERE u.user_name=?", [$username]);
			if($rawUserData!=NULL && !empty($rawUserData)) {
				$userDataRow=$rawUserData[0];
				$this->userData=new UserData($userDataRow, $password);
			}
			return isset($this->userData) && $this->userData!=NULL && $this->userData->isAuthenticated();
		}

		public function getUser() {
			return $this->userData;
		}
	}
?>