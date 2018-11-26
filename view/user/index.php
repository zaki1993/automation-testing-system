<?php

	require_once 'user.php';

	function validateUser() {
		session_start();
		if (!isset($_SESSION["__userData"]) || $_SESSION["__userData"]==NULL) {
			echo '<script type="text/javascript">
	                window.location = "./login"
	             </script>';
		}
	}

	function renderPage($pageId) {
		$file='pages/' . $pageId . ".php";
		if (!file_exists($file)) {
		  $error404=__DIR__ . "/../error_404.png";
		  echo "<html><body><img src=\"{$error404}\"></img></body></html>";
		}
		else {
		  require_once($file); 
		}
	}

	validateUser();
	require_once "navbar.php";

	if (isset($_GET['page'])) {
		renderPage($_GET['page']);
	} else {
		renderPage('homeworks'); // Default page
	}

?>