<!DOCTYPE html>
<html>
<head>
	<title>Начална страница</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="view/style/home.css">
    <link rel="stylesheet" type="text/css" href="view/style/navbar.css">
</head>
<body>
	<script type="text/javascript">
	    // close the div in 4 secs
	    window.setTimeout("hideErrorPanel();", 4000);

	    function hideErrorPanel(){
	    	var errorPanel=document.getElementById("error-panel");
	    	errorPanel.style.display="none";
	    	// TODO remove the panel as child
	    }

	    function closeDiv(closeButton) {
	    	// hides the div by clicking close button 
	    	// in top right corner
	    	closeButton.parentElement.style.display='none';
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

		function refreshPage() {
			echo '<script type="text/javascript">
					 window.location = ""
				  </script>';
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
			return "<div id=\"error-panel\">
				   <pre>${msg}dfgdfghgfhfksdkfsdkljfklsdjfklsdjfklsdjkfljsklfjsdklfjsdklfjk</pre>
				   </div>";
		}

		function renderError() {
			$error404="view/error.php";
			require_once($error404);
			displayError("view");
		}

		function renderErrorAndLog($msg) {
			logMsg($msg);
			renderError();
		}

		function logMsg($msg) {
			mkdirWithCheck("logs");
			$msgWithDate=date("Y-m-d") . ':' . $msg;
			error_log($msgWithDate, 3, "logs/error.log");
		}

		function validatePage($pageId) {
			$file='view/user/pages/' . $pageId . ".php";
			return strpos($pageId, ".") !== false || !file_exists($file);
		}

		function renderPage($pageId) {
			
			if (validatePage($pageId)) {
				renderError();
			} else {
			  	include('view/user/pages/' . $pageId . '.php');

			  	# each page will have function called render
			  	# which will be invoked from here and the
			  	# main functionality in that particular screen 
			  	# shoud start from there
			  	ob_start();
				render();
				$renderResult=ob_get_contents();
				ob_end_clean();

			  	echo "<div class=\"main-container\">
				      	${renderResult}
				      </div>";
			}
		}

		function validatePermission() {
			$userRole=getRole($_SESSION);
			if ($userRole!=="admin") {
				throw new Exception();
			}
		}
	
		function getRole($session) {
			if (isset($session['__userData'])) {
			    $userData=$session['__userData'];	
			    return $userData->getRole();
			}
			# default user role
			return 'user';
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
					renderErrorAndLog($e->getMessage());
				}
			?>
		</div>
	</div>
</body>
</html>