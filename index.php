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

		function validatePage($pageId) {
			$file='view/user/pages/' . $pageId . ".php";
			return strpos($pageId, ".") !== false || !file_exists($file);
		}

		function renderPage($pageId) {
			
			if (validatePage($pageId)) {
				renderError();
			} else {
			  	require_once('view/user/pages/' . $pageId . '.php'); 
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
					# if there is no page property but there are no other properties then forward
					# to homework/view page
					echo '<script type="text/javascript">
			                window.location = "./?page=homework/view";
			             </script>';
				}
			?>
		</div>
	</div>
</body>
</html>