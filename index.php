<!DOCTYPE html>
<html>
<head>
	<title>Начална страница</title>
	<link rel="stylesheet" type="text/css" href="view/style/home.css">
</head>
<body>
	<script type="text/javascript">
	    // close the div in 5 secs
	    window.setTimeout("closeDiv();", 5000);

	    function closeDiv(){
	    	document.getElementById("upload-failed").style.display=" none";
	    }
	</script>

	<?php
		require_once 'view/conf/config.php';
		require_once 'view/user/user.php';
		require_once 'model/dao.php';
		require_once 'view/conf/shell.php';

		# third param is list of extensions which are allowed
		# if NULL is provided no checks are done
		function uploadHomeworkFile($fileName, $path, $sytemFileName, $homeworkId, $extensions) {
			$tmpFile = $_FILES[$fileName]['tmp_name'];
			
			mkdirWithCheck("view/homeworks/${homeworkId}");

			$fileSplitted = explode('.', $_FILES[$fileName]['name']);
			$extension = end($fileSplitted);	
			validateExtension($extension, $extensions);

			mkdirWithCheck("view/homeworks/${homeworkId}${path}");
			
			$newFile = "view/homeworks/${homeworkId}${path}${sytemFileName}.${extension}";
			return move_uploaded_file($tmpFile, $newFile);
		}

		function validatePermission() {
			$userRole=getRole($_SESSION);
			if ($userRole!=="admin") {
				throw new Exception("Access is denied..!");
			}
		}

		function validateExtension($extension, $extensions) {
			if ($extensions!=NULL) {
				if (!in_array($extension, $extensions)) {
					throw new Exception("Extension " . $extension . " is not in the allowed file extensions..!");
				}
			}
		}

		function validateUser() {
			session_start();
			if (!isset($_SESSION["__userData"]) || $_SESSION["__userData"]==NULL) {
				echo '<script type="text/javascript">
		                window.location = "./view/user/login"
		             </script>';
			}
		}

		# loop all the parent directories and create them if they are not existing
		function mkdirWithCheck($dir) {
			if (!file_exists($dir)) {
				$parents=explode("/", $dir);
				$catalog="";
				foreach ($parents as $parent) {
					$catalog.=$parent . "/";
					if (!file_exists($catalog)) {
						mkdir($catalog);
					}
				}
			}
		}

		function getErrorBlock($msg) {
			return "<div id=\"upload-failed\">
				   <h2><pre>${msg}</pre></h2>
				   </div>";
		}

		function renderError() {
			$error404="view/error.php";
			require_once($error404);
			displayError("view");
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
			  	echo "<div class=\"main-container\">";
				render();
				echo "</div>";
			}
		}

		function getRole($session) {
		    $userData=$session['__userData'];	
		    return $userData->getRole();
		}

		function getProgrammingLanguages() {
			$prLangDao=new Dao();
			$prLangs=$prLangDao->executeSelect('Language', NULL, NULL);
			return $prLangs;
		}

		function cacheConfiguration() {
			$configuration=new Configuration();
			$_SESSION['configuration']=$configuration;
		}
	?>

	<?php
		# Before all execution
		validateUser();
	?>
	<div class="container">
		<div class="navbar">
			<?php require_once "view/user/navbar.php"; ?>
		</div>
		<div class="main">
			<?php
				cacheConfiguration();
				try {
					if (isset($_GET['page'])) {
						renderPage($_GET['page']);
					} else {
						# if there is no page property but there are no other properties then forward
						# to homework/view page
						echo '<script type="text/javascript">
				                window.location = "./?page=homework/view";
				             </script>';
					}
				} catch (Exception $e) {
					renderError();
				}
			?>
		</div>
	</div>
</body>
</html>