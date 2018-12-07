<?php
	function insertComment($course, $comment) {

		include_once 'dbutils.php';

		$dbUtils=new DBUtils();
		$dbUtils->insertCourseComment($course, $comment);
	}

	function getCourseComments($course) {

		include_once 'dbutils.php';

		$dbUtils=new DBUtils();
		return $dbUtils->getCourseComments($course);
	}
?>