<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>Homework CSS</title>
</head>
<body>
	<?php
		include_once 'courses.php';
		include_once 'comments.php';

		$courses=fetchCourses(); // fetch all courses from db 

		$selectedCourse=getSelectedCourse($courses);

		function displayCoursesNames($selectedCourse, $courses) {

			$result="";

			foreach ($courses as $course) {
				$result .= "<a href=\"?course=" . $course->getCourseName() . "\"" . ($course->equals($selectedCourse) ? " class=\"selected\">" : ">") . $course->getCourseName() . "</a><hr>";
			}

			echo $result;
		}

		function displayCourseContent($course) {

			$result="<p class=\"title\"><b>" . $course->getCourseName() . "</b></p>";
			$result.="<hr>";
			$result.="<p><h2>" . $course->getLecturer() . "</h2></p>";
			$result.="<p>" . $course->getDescription() . "</p>";
			$result.="<p><i>Тип: </i>" . $course->getType() . "</p>";

			$class = $course->getClass();

			$result.="<p><i>Курс: </i>" . $class . "<sup>" . ($class == 1 ? "ви" : $class == 2 ? "ри" : "ти") . "</sup></p>";
			$result.="<p><i>Програма: </i>" . $course->getProgramm() . "</p>";

			// display the content
			echo $result;
		}

		function findCourseByName($courses, $courseName) {

			$result=NULL;

			foreach ($courses as $course) {
				if ($course->getCourseName() === $courseName) {
					$result=$course;
					break;
				}
			}

			return $result;
		}

		function getSelectedCourse($courses) {

			$course=NULL;
			// check if we have attribute course in the get params
			if(isset($_GET['course'])) {
				$courseName=$_GET['course'];
				//if yes then try getting the course by course name
				$course=findCourseByName($courses, $courseName);
			}
			// if there is no such course then try getting the first one, if there are no courses it will return NULL
			if ($course == NULL) {
				$course=isset($courses) ? $courses[0] : NULL;
			}
			return $course;	
		}

		function displayComments($comments) {

			$result="";

			if (isset($comments) && $comments != NULL) {
				foreach ($comments as $comment) {
					$result.="<p><a href=\"users?user=" . $comment['user'] . "\">" . $comment['user'] . "</a> " . $comment['comment_date'] . "</p>";
					$result.=$comment['content'];
					$result.="<hr>";
				}
			}

			// display comments
			echo $result;
		}

		if (isset($_POST['comment-content'])) {
			if ($_POST['comment-content'] != "") {
				insertComment($selectedCourse, $_POST['comment-content']);
			}
		}
	?>
	<div id="container">
		<div class="header">
			<nav class="topnav">
				<form action="login" method="POST">
					<input class="btn" type="submit" name="login-button" value="Вход"/>
				</form>
				<form action="register" method="POST">
					<input class="btn" type="submit" name="register-button" value="Регистрация"/>
				</form>
			</nav>
		</div>
		<div class="body">
			<div class="body-left">
				<nav class="sidenav">
					<?php
						displayCoursesNames($selectedCourse, $courses);
					?>
				</nav>
			</div>
			<div class="body-center">
				<?php
					if ($selectedCourse != NULL) {
						displayCourseContent($selectedCourse);
					}
				?>
				<form <?php "action=\"?course=" . $selectedCourse->getCourseName() . "\""; ?> method="POST">
					<textarea name="comment-content" onkeyup="checkContent()" class="comment-text" placeholder="Добавяне на коментар"></textarea>
					<input disabled="true" id="cancel-comment" class="btn" type="reset" value="Отказ"/>
					<input class="btn" type="submit" name="post-comment" value="Публикуване"/>
				</form>
				<p>
					<b>Коментари</b>
				</p>
				<?php
					displayComments(getCourseComments($selectedCourse));
				?>
			</div>
		</div>
	</div>
	<script type="text/javascript">

		function checkContent() {
			if (document.getElementsByClassName("comment-text")[0].value.length > 0) {
				document.getElementById("cancel-comment").disabled = false;
			} else {
				document.getElementById("cancel-comment").disabled = true;
			}
		}
	</script>
</body>
</html>