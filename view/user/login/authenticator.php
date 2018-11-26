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
			$rawUserData=$dbConn->executeQuery("SELECT u.user_name, u.faculty_number, u.password, ur.role_name FROM User u INNER JOIN User_Roles ur ON u.user_name = ur.user_name WHERE u.user_name = ?;", [$username]);
			$dbPass=NULL;
			if($rawUserData!=NULL && !empty($rawUserData)) {
				$userDataRow=$rawUserData[0];
				$this->userData=new UserData($userDataRow);
				$dbPass=$userDataRow['password'];
			}
			return isset($this->userData) && $this->userData!=NULL && $this->userData->isAuthenticated($password, $dbPass);
		}

		public function getUser() {
			return $this->userData;
		}
	}
?>