<!DOCTYPE html>
<html>
<head>
	<title>Начална страница</title>
	<link rel="stylesheet" type="text/css" href="../style/home.css">
</head>
<body>
	<?php
		require_once 'user.php';
		require_once '../../model/dao.php';

		function validateUser() {
			session_start();
			if (!isset($_SESSION["__userData"]) || $_SESSION["__userData"]==NULL) {
				echo '<script type="text/javascript">
		                window.location = "./login"
		             </script>';
			}
		}

		function renderError() {
			$error404="../error.php";
			require_once($error404);
			displayError("../");
		}

		function renderPage($pageId) {
			$file='pages/' . $pageId . ".php";
			if (!file_exists($file)) {
				renderError();
			} else {
			  	require_once($file); 
			}
		}

		validateUser();
	?>

	<div class="container">
		<div class="navbar">
			<?php require_once "navbar.php"; ?>
		</div>
		<div class="main">
			<?php
				if (isset($_GET['page'])) {
					renderPage($_GET['page']);
				} else {
					renderPage('homeworks'); // Default page
				}
			?>
		</div>
	</div>
</body>
</html>