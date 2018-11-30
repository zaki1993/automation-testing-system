<!DOCTYPE html>
<html>
<head>
	<title>Виж домашно</title>
</head>
<body>
	<?php

		function checkPageForErrors() {
			if(!isset($_GET['page'])) {
				renderError();
			}
		}

		function getHomeworkId() {
			if (isset($_GET['id'])) {
				return $_GET['id'];
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
			$homeworkDao=new Dao();
			$homeworks=$homeworkDao->executeSelect('Homework', NULL, NULL);
			echo '<div class="all-homeworks-content">';
			echo '<h2>Достъпни домашни работи</h2>';
			echo '<table>';
			echo '<tr>
					<th>Номер</th>
				    <th>Заглавие</th>
				    <th>Начална дата</th>
				    <th>Крайна дата</th>
				  </tr>';
			foreach($homeworks as $homework) {
				$homeworkId=$homework['folder'];
				$homeworkName=$homework['title'];
				$id=$homework['id'];
				$startDate=$homework['start_date'];
				$endDate=$homework['end_date'];
				echo '<tr>';
				echo "<td>${id}</td>";
				echo "<td><a href=\"?page=homework/view&id=${homeworkId}\"><b>${homeworkName}</b></a></td>";
				echo "<td>${startDate}</td>";
				echo "<td>${endDate}</td>";
				echo '</tr>';
			}
			echo '</table>';
			echo '</div>';
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

		# Initial page validation
		checkPageForErrors();
		$homeworkId=getHomeworkId();
		if ($homeworkId!=NULL) {
			if (!file_exists("view/homeworks/" . $homeworkId)) {
				renderError();
			}
		}
	?>	
	<?php getHomeworkAssignment($homeworkId); ?>
</body>
</html>