<?php
	function renderRatings() {
		$homework=$_SESSION['homework'];

		if($homework!=NULL) {
			echo "<table>
				      <tr>
				      	<th>Място</th>
					    <th>Потребител</th>
					    <th>Точки</th>
					    <th>Последна промяна</th>
					  </tr>";

			$homeworkUserDao=new Dao();
			$userScores=$homeworkUserDao->executeQuery('SELECT * FROM User_Homework WHERE folder=? ORDER BY score desc;', [$_GET['id']]);

			foreach($userScores as $key=>$userScore) {
				$username=$userScore['user_name'];
				$score=$userScore['score'];
				$number=$key+1;
				$lastTestDate=$userScore['last_test_date'];
				echo "<tr>
					      <td>${number}</td>
						  <td>${username}</td>
						  <td>${score}</td>
						  <td>${lastTestDate}</td>
					  </tr>";
			}
			echo "</table>";
		} else {
			renderError();
		}
	}

	# check in case that the rating is opened from view page
	if (!function_exists('render')) {
		function render() {
			renderError();
		}
	}
?>