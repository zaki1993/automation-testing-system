<?php
	class DBConnection {

		protected $conn;

		public function __construct() {

				$this->conn=new PDO('mysql:host=localhost;dbname=automation_testing_system', 'root', 'root');

				// sets the encoding to utf8
				$charsetQuery="SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

				$this->conn->query($charsetQuery);
			}
	}
?>