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

		require "view/conf/config.php";
		$config=new Configuration();
		$java=$config->getModuleByName("Java");
		$executable=$java->getExecutables()[0];
		$path=$executable->getCompiler()->getPath();

		require "view/conf/shell.php";
		$shell=new Shell();
		$command=$path . " Test.java";
		echo "Executing command: " . $command;	
		echo $shell->execute($command);
	?>
</body>
</html>