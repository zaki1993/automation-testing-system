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

				$this->copyTestFiles();
			}

			private function copyTestFiles() {
				$shell=new Shell();
				$shell->execute("cp " . $this->hwTests . "/* " . $this->userTests);
				$shell->execute("cp view/user/pages/homework/tests/java/resources/* " . $this->userTests);
			}
		}
	?>
</body>
</html>