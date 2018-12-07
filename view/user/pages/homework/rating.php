<?php
	function renderRatings() {
		$homework=$_SESSION['homework'];

		if($homework!=NULL) {
			echo "<table>
			      	<thead>
				      <tr>
				      	<th>Място</th>
					    <th>Потребител</th>
					    <th>Точки</th>
					  </tr>
					</thead>
					<tbody>";

			$homeworkUserDao=new Dao();
			$userScores=$homeworkUserDao->executeQuery('SELECT user_name, score FROM User_Homework WHERE folder=? ORDER BY score desc;', [$_GET['id']]);

			foreach($userScores as $key=>$userScore) {
				$username=$userScore['user_name'];
				$score=$userScore['score'];
				$number=$key+1;
				echo "<tr>
					      <td>${number}</td>
						  <td>${username}</td>
						  <td>${score}</td>
					  </tr>";
			}
			echo "</tbody></table>";
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