<!DOCTYPE html>
<html>
<head>
	<title>Automation Testing System</title>
</head>
<body>
	<h1>Welcome</h1>
	<?php
		require 'model/dao.php';

		$dao = new Dao();

		foreach ($dao->executeSelect('User', NULL, NULL) as $user) {
			echo $user['user_name'];
		}
	?>
</body>
</html>