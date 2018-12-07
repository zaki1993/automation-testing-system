<?php
	class Course {

		private $courseName;
		private $lecturer;
		private $description;
		private $type;
		private $class;
		private $programm;

		function __construct($course) {
			$this->__init($course);
		}

		private function __init($course) {
			
			$this->courseName=$course['course_name'];
			$this->lecturer=$course['lecturer'];
			$this->description=$course['description'];
			$this->type=$course['type'];
			$this->class=$course['class'];
			$this->programm=$course['programm'];
		}


		function getCourseName() {
			return $this->courseName;
		}

		function getLecturer() {
			return $this->lecturer;
		}

		function getDescription() {
			return $this->description;
		}

		function getType() {
			return $this->type;
		}

		function getClass() {
			return $this->class;
		}

		function getProgramm() {
			return $this->programm;
		}

		function displayCourse() {
			echo $this->getCourseName() . " " .
			     $this->getLecturer() . " " .
			     $this->getDescription();
		}

		function equals($course) {
			return $course->getCourseName() === $this->getCourseName();
		}
	}

	function fetchCourses() {

		include_once 'dbutils.php';

		$dbUtils=new DBUtils();
		$courses=$dbUtils->getCourses();

		$result=array();

		foreach ($courses as $course) {
			array_push($result, new Course($course));
		}

		return $result;
	}
?>