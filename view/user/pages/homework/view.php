<!DOCTYPE html>
<html>
<head>
	<title>Виж домашно</title>
</head>
<body>
	<?php
		function getHomeworkId() {
			if (isset($_GET['id'])) {
				return $_GET['id'];
			}
		}

		$homeworkId=getHomeworkId();
		if ($homeworkId!=NULL) {
			if (!file_exists("view/homeworks/" . $homeworkId)) {
				renderError();
			}
		}

		function renderHomework($assignment) {
			echo '<div class="homework-content">';
			echo '<h1>Условие: </h1>';
			echo '<div class="assignment-content">';
			echo file_get_contents($assignment);
			echo '</div>';
			echo '</div>';
		}

		function renderAllHomeworks() {

		}

		function getHomeworkAssignment($homeworkId) {
			$files = glob("view/homeworks/${homeworkId}/assignment.*");
			if(count($files) > 0) {
				$assignment=$files[0];
				renderHomework($assignment);
			} else {
				renderAllHomeworks();
			}
		}
	?>	
	<?php getHomeworkAssignment($homeworkId); ?>
</body>
</html>