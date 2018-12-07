<?php
	function getAllUsers() {
		$users=readUsers();
		$usersData=buildUsersData($users);
		displayUserData($usersData);
	}

	function readUsers() {
		$usersDao=new Dao();
		return $usersDao->executeQuery('SELECT u.user_name, u.faculty_number, ur.role_name FROM User u INNER JOIN User_Roles ur ON u.user_name = ur.user_name;', NULL);
	}

	function buildUsersData($users) {
		$result=array();
		foreach ($users as $user) {
			$userData=new UserData($user);
			array_push($result, $userData);
		}
		return $result;
	}

	function displayUserData($usersData) {
		echo '<div class="all-users">';
		echo '<table>';
		echo '<tr>
				<th>Номер</th>
			    <th>Потребителско име</th>
			    <th>Факултетен номер</th>
			    <th>Роля</th>
			  </tr>';
		foreach ($usersData as $number => $userData) {
			echo '<tr>';
			echo '<td>' . $number . '</td>';
			echo '<td>' . $userData->getUsername() . '</td>';
			echo '<td>' . $userData->getFacultyNumber() . '</td>';
			echo '<td>' . $userData->getRole() . '</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
	}

	function render() {
		validatePermission();
		getAllUsers();
	}
?>	