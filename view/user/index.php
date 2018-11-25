<?php
	
	function validateUser() {
		session_start();
		if (!isset($_SESSION["__userData"]) || $_SESSION["__userData"]==NULL) {
			echo '<script type="text/javascript">
	                window.location = "./login"
	             </script>';
		}
	}

	validateUser();

	require_once "main/navbar.php";

?>