<script type="text/javascript">
	function selectModule(obj) {

		// get all the modules as array
		var moduleObjs=document.getElementsByClassName("module");

		// remove the class selectedModule for all the objects
		for (var i = 0; i < moduleObjs.length; i++) {
			moduleObjs[i].classList.remove("selectedModule");
		}

		// add the class only for this object
		obj.classList.add("selectedModule");

		// get module name
		// this object is represnting the module name
		// types of obj are span or input, for editing and read only
		var moduleNameObj=obj.children[0].children[0]; // NEVER DO THIS
		var moduleName;
		if (moduleNameObj.constructor.name==="HTMLSpanElement") {
			moduleName=moduleNameObj.innerHTML;
		} else {
			moduleNameObj=obj.children[0].children[2]; // NEVER DO THIS
			moduleName=moduleNameObj.value;
		}

		// put the selected module into the session
		// HACK: put module properties in array, because the object is undefined
		// if we put it in the session and get it later

		// convert the class list to array
		var classList = [];
		// iterate backwards ensuring that length is an UInt32
		for (var i = obj.classList.length >>> 0; i--;) { 
			classList[i] = obj.classList[i];
		}
		var moduleProperties=[moduleName, classList];
		sessionStorage.setItem("__selectedModuleProperties", JSON.stringify(moduleProperties));
	}

	window.onload=function() {
		
		// get all the modules as array
		var configForm=document.getElementById("config-form");

		configForm.onsubmit=function() {
			if (confirm("Сигурен ли си?")) {
				// get selected module
				var selectedModuleProperties=JSON.parse(sessionStorage.getItem("__selectedModuleProperties"));

				var moduleInput=document.getElementById("module-name");
				// first element of the properties is the name
				var selectedModuleName=selectedModuleProperties[0]; 
				moduleInput.value=selectedModuleName;

				return selectedModuleProperties[1].includes("module");	
			}
		
			return false;
		}

		var cancelForm=document.getElementById("cancel-form");
		if (cancelForm!==null) {
			cancelForm.onclick=function() {
				window.location = "?page=admin/config";
			}
		}
	}
</script>
<?php
	function renderConfigurationPanel() {
		if (isset($_SESSION['configuration'])) {
			$configuration=$_SESSION['configuration'];
			$modules=$configuration->getModules();

			$action=NULL;
			$moduleName=NULL;
			if (isset($_POST['config-action']) && isset($_POST['moduleName'])) {
				$action=$_POST['config-action'];
				$moduleName=$_POST['moduleName'];
			}

			foreach ($modules as $module) {
				if ($action!=NULL && $action==="edit" && $moduleName!=NULL && $module->getName()===$moduleName) {
					echo '<div class="module selectedModule" onclick="selectModule(this)">';
					echo '<form action="" method="POST">';
					echo '<input type="hidden" name="edit-form" value="true" required/>';
					echo '<input type="hidden" name="config-action" value="edit" required/>';
					echo '<input name="moduleName" type="hidden" value="' . $module->getName() . '" required/>';
					echo "<p>Име на модула: <span>" . $module->getName() . "</span></p>";
					$executables=$module->getExecutables();
					foreach ($executables as $key=>$executable) {
						echo '<p>Изпълнимо: </p>';
						echo '<label for="version-' . $key . '">Версия: </label>';
						echo '<input name="version-' . $key . '" type="text" value="' . $executable->getVersion() . '"/>';
						echo '<label for="path-' . $key . '">Път: </label>';
						echo '<input name="path-' . $key . '" type="text" value="' . $executable->getPath() . '"/>';
					}
					echo '<input type="submit" value="Запази"/>';
					echo '<input id="cancel-edit-form" type="submit" value="Откажи"/>';
					echo '</form>';
					echo '</div>';
				} else {
					echo '<div class="module" onclick="selectModule(this)">';
					echo "<p>Име на модула: <span>" . $module->getName() . "</span></p>";
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
			if ($action!=NULL && $action==="add") {
				echo '<div class="module selectedModule" onclick="selectModule(this)">';
				echo '<form action="" method="POST">';
				echo '<input type="hidden" name="add-form" value="true" required/>';
				echo '<input type="hidden" name="config-action" value="add" required/>';
				echo '<label for="moduleName">Име на модула:</label>';
				echo '<input name="moduleName" type="text" value="" placeholder="Име на модула" required/>';
				echo '<p>Изпълнимо: </p>';
				echo '<label for="version-0">Версия: </label>';
				echo '<input name="version-0" type="text" value="" placeholder="Версия" required/>';
				echo '<label for="path-0">Път: </label>';
				echo '<input name="path-0" type="text" value="" placeholder="Път" required/>';
				echo '<p>Изпълнимо: </p>';
				echo '<label for="version-1">Версия: </label>';
				echo '<input name="version-1" type="text" value="" placeholder="Версия"/>';
				echo '<label for="path-1">Път: </label>';
				echo '<input name="path-1" type="text" value="" placeholder="Път"/>';
				echo '<input type="submit" value="Запази"/>';
				echo '<input id="cancel-form" type="submit" value="Откажи"/>';
				echo '</form>';
				echo '</div>';
			}
		}
	}

	function renderConfigNavbar() {
		echo '<div class="config-navbar">
				<form method="POST" action="" id="config-form"> 
					<input type="hidden" name="moduleName" id="module-name"/>
					<input type="submit" name="config-action" value="add" class="add"/>
					<input type="submit" name="config-action" value="remove" class="remove"/>
					<input type="submit" name="config-action" value="edit" class="edit"/>
				</form>
			  </div>';
	}

	function addConfiguration() {
		if (isset($_POST['add-form']) && $_POST['add-form'] === 'true') {
			if (isset($_SESSION['configuration'])) {
				$configuration=$_SESSION['configuration'];
				$configuration->addModuleFromProperties($_POST);
				refreshPage();
			}
		}
	}

	function removeConfiguration($moduleName) {
        if (isset($_POST['moduleName']) && isset($_SESSION['configuration'])) {
			$configuration=$_SESSION['configuration'];
			$configuration->deleteModuleByName($moduleName);
			refreshPage();
		}
	}

	function editConfiguration($moduleName) {

		if (isset($_POST['moduleName']) && isset($_POST['edit-form']) && $_POST['edit-form'] === 'true') {
			if (isset($_SESSION['configuration'])) {
				$configuration=$_SESSION['configuration'];
				$configuration->editModuleByName($_POST['moduleName'], $_POST);
				refreshPage();
			}
		}
	}

	function listenForConfigAction() {
		if(isset($_POST['config-action'])) {
			$action=$_POST['config-action'];
			$moduleName=$_POST['moduleName'];
			switch($action) {
				case "add": 
					addConfiguration();
					break;
				case "remove":
					removeConfiguration($moduleName);
					break;
				case "edit":
					editConfiguration($moduleName);
					break;
			}
		}
	}

	function render() {
		validatePermission();
		listenForConfigAction();
		renderConfigNavbar();
		renderConfigurationPanel();
	}
?>