<?php
	function renderConfiguration() {
		if (isset($_SESSION['configuration'])) {
			$configuration=$_SESSION['configuration'];
			$modules=$configuration->getModules();

			foreach ($modules as $module) {
				echo "<div class=\"module\">";
				echo "<p>Име на модула: " . $module->getName() . "</p>";
				$executables=$module->getExecutables();
				foreach ($executables as $executable) {
					echo "<p>Изпълнимо: ";
					echo "Версия: " . $executable->getVersion();
					echo ", Път: " . $executable->getPath();
					echo "</p>";
				}
				echo "</div>";
			}
		}
	}

	function render() {
		validatePermission();
		renderConfiguration();
	}
?>