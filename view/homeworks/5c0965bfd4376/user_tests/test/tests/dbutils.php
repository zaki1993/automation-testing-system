<?php
	class DBUtils {

		private $conn;

		public function __construct() {
			$this->conn=new PDO('mysql:host=localhost;dbname=webtechnologies', 'root', 'root');

			// sets the encoding to utf8
			$charsetQuery="SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
			$this->conn->query($charsetQuery);
		}

		public function getCourses() {

			$query="select course_name, lecturer, description, type, class, programm from Courses;";
			$stmt=$this->conn->query($query) or die('Failed to retrieve courses');
			return $stmt->fetchAll();
		}

		public function getCourseComments($course) {

			$query="select content, user, comment_date from Comments where id in (select comment_id from CoursesComments where course_name=?);";
			$stmt=$this->conn->prepare($query);
			$stmt->execute([$course->getCourseName()]) or die('Failed to retrieve course comments');
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		public function insertCourseComment($course, $comment) {

			$id=$this->insertComment($comment);
			$query="insert into CoursesComments(course_name, comment_id) values(?, ?);";
			$stmt=$this->conn->prepare($query)->
							   execute([$course->getCourseName(), $id]) 
							   or die('Failed to insert comment');

		}

		private function insertComment($comment) {

			$query="insert into Comments(content, user, comment_date) values(?, ?, ?);";
			$stmt=$this->conn->prepare($query)->
			 				   execute([$comment, 'Anonymous', date('Y.m.d')]) 
			 				   or die('Failed to insert comment');
			return $this->conn->lastInsertId();
		}
	}
?>