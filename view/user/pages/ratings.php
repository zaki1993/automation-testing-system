<?php

	function getRatingsChooseList() {
		echo "<div class=\"ratings-list\">
			    <form method=\"POST\" action=\"./?page=ratings\">
				  	<select name=\"rating-type\">
				  		<option value=\"0\">Класиране по ден</option>
				  		<option value=\"1\">Класиране по седмица</option>
				  		<option value=\"2\">Класиране по задание</option>
				  		<option value=\"3\">Общо класиране</option>
				  	</select>
				  	<input type=\"date\" name=\"rating-calendar\"/>
				  	<input type=\"submit\" value=\"Покажи класиране\"/>
				</form>
			  </div>";
	}

	function rangeWeek ($datestr) {
		date_default_timezone_set (date_default_timezone_get());
		$dt = strtotime ($datestr);
		return array (
			"start" => date ('N', $dt) == 1 ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('last monday', $dt)),
			"end" => date('N', $dt) == 7 ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('next sunday', $dt))
		);
	 }

	function renderDayRatings() {
		if(isset($_POST['rating-calendar']) && $_POST['rating-calendar']!=='') {
			$dayDao=new Dao();
			$resultSet=$dayDao->executeQuery("SELECT * FROM (SELECT user_name, sum(score) as score, last_test_date FROM User_Homework WHERE last_test_date=? GROUP BY user_name) AS User_Homework_Day ORDER BY score DESC;", [$_POST['rating-calendar']]);

			$result=  "<table>
					      <tr>
					      	<th>Място</th>
						    <th>Потребител</th>
						    <th>Точки</th>
						    <th>Дата</th>
						  </tr>";

			foreach ($resultSet as $key=>$rSet) {
				$number=$key+1;
				$username=$rSet['user_name'];
				$score=$rSet['score'];
				$lastTestDate=$rSet['last_test_date'];
				$result.="<tr>
							<td>${number}</td>
							<td>${username}</td>
							<td>${score}</td>
							<td>${lastTestDate}</td>
						  </tr>";
			}

			$result.="</table>";
			return $result;
		}
	}

	function renderWeekRatings() {
		if(isset($_POST['rating-calendar']) && $_POST['rating-calendar']!=='') {
			$weekRange=rangeWeek($_POST['rating-calendar']);
			$beginWeek=$weekRange['start'];
			$endWeek=$weekRange['end'];
			$weekDao=new Dao();
			$resultSet=$weekDao->executeQuery("SELECT * FROM (SELECT user_name, sum(score) as score FROM User_Homework WHERE last_test_date BETWEEN ? AND ? GROUP BY user_name) AS User_Homework_Day ORDER BY score DESC;", [$beginWeek, $endWeek]);

			$result=  "<table>
					      <tr>
					      	<th>Място</th>
						    <th>Потребител</th>
						    <th>Точки</th>
						    <th>Седмица</th>
						  </tr>";

			foreach ($resultSet as $key=>$rSet) {
				$number=$key+1;
				$username=$rSet['user_name'];
				$score=$rSet['score'];
				$result.="<tr>
							<td>${number}</td>
							<td>${username}</td>
							<td>${score}</td>
							<td>От: ${beginWeek},  До: ${endWeek}</td>
						  </tr>";
			}

			$result.="</table>";
			return $result;
		}
	}

	function renderHomeworkRatings() {
		return '<script type="text/javascript">
			   		window.location = "./?page=homework/view";
			   </script>';
	}

	function renderAllTimeRatings() {
		$allTimeDao=new Dao();
		$resultSet=$allTimeDao->executeQuery("SELECT * FROM (SELECT user_name, sum(score) as score FROM User_Homework GROUP BY user_name) as User_Homework_Alltime ORDER BY score DESC;", NULL);

		$result=  "<table>
					      <tr>
					      	<th>Място</th>
						    <th>Потребител</th>
						    <th>Точки</th>
						  </tr>";

			foreach ($resultSet as $key=>$rSet) {
				$number=$key+1;
				$username=$rSet['user_name'];
				$score=$rSet['score'];
				$result.="<tr>
							<td>${number}</td>
							<td>${username}</td>
							<td>${score}</td>
						  </tr>";
			}

			$result.="</table>";
			return $result;
	}

	function listenForRatingSelection() {
		if (isset($_POST['rating-type'])) {
			$ratingType=$_POST['rating-type'];
			switch($ratingType) {
				case 0: echo renderDayRatings(); break;
				case 1: echo renderWeekRatings(); break;
				case 3: echo renderAllTimeRatings(); break;
				case 2:
				default: echo renderHomeworkRatings(); break;
			}
		}
	}

	function render() {
		echo getRatingsChooseList();
		try {
			listenForRatingSelection();
		} catch (Exception $e) {
			echo getErrorBlock($e->getMessage());
		}
	}
?>