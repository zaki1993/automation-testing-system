<!DOCTYPE html>
<html>
<head>
	<title>TestRunner</title>
</head>
<body>
	<?php
		class BaseTestRunner {

			protected $hwTests;
			protected $userTests;

			function __construct($hwTests, $userTests) {
				$this->hwTests=$hwTests;
				$this->userTests=$userTests;
			}
		}
	?>
</body>
</html>