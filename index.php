<!DOCTYPE html>
<html>
<head>
	<title>Начална страница</title>
	<link rel="stylesheet" type="text/css" href="view/style/home.css">
</head>
<body>
	<?php
		require_once 'view/user/user.php';
		require_once 'model/dao.php';

		function validateUser() {
			session_start();
			if (!isset($_SESSION["__userData"]) || $_SESSION["__userData"]==NULL) {
				echo '<script type="text/javascript">
		                window.location = "./view/user/login"
		             </script>';
			}
		}

		function renderError() {
			$error404="view/error.php";
			require_once($error404);
			displayError("view/");
		}

		function renderPage($pageId) {
			$file='view/user/pages/' . $pageId . ".php";
			if (strpos($pageId, ".") !== false || !file_exists($file)) {
				renderError();
			} else {
			  	require_once($file); 
			}
		}

		function getRole($session) {
		    $userData=$session['__userData'];	
		    return $userData->getRole();
		}

		validateUser();

	?>

	<div class="container">
		<div class="navbar">
			<?php require_once "view/user/navbar.php"; ?>
		</div>
		<div class="main">
			<?php
				if (isset($_GET['page'])) {
					renderPage($_GET['page']);
				} else {
					renderPage('homework/view'); // Default page
				}
			?>
		</div>
	</div>
</body>
</html>