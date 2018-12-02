<!DOCTYPE html>
<html>
<head>
	<title>Виж домашно</title>
</head>
<body>
	<script type="text/javascript">

	/* Add onchange listeners to the file inputs in order to set the maximum size
	   allowed for file uploading. Be sure to set the max file size according to the
	   maximum file upload size in the php.ini file.
	*/
		window.onload = function() {

			var hwUserTests = document.getElementById("hw-user-tests");

			hwUserTests.onchange = function() {
			    if(this.files[0].size > 2 * 1024 * 1024){
			       alert("File size is too big..! Upload file up to 2 MB.");
			       this.value = "";
			    };
			};
		};
	</script>
	<?php
		class Homework {

			private $name;
			private $startDate;
			private $endDate;
			private $language;

			function __construct($dbRow) {
				$this->name=$dbRow['title'];
				$this->startDate=$dbRow['start_date'];
				$this->endDate=$dbRow['end_date'];
				$this->language=$dbRow['language'];
			}

			function getName() {
				return $this->name;
			}

			function getStartDate() {
				return $this->startDate;
			}

			function getEndDate() {
				return $this->endDate;
			}

			function getLanguage() {
				return $this->language;
			}
		}

		function checkPageForErrors() {
			if(!isset($_GET['page'])) {
				renderError();
			}
		}

		function getHomeworkId() {
			if (isset($_GET['id'])) {
				return $_GET['id'];
			}
		}

		function checkHomeworkDBRecord($homeworkId) {
			$homeworkDao=new Dao();
			$dbResult=$homeworkDao->executeQuery('SELECT hw.id, hw.title, hw.start_date, hw.end_date, hwl.name as language FROM Homework hw INNER JOIN Homework_Language hwl ON hw.id = hwl.id WHERE hw.folder=?;', [$homeworkId]);
			
			$result=count($dbResult) > 0;

			# if there is any row then store the first one in the session
			# this might cause problems later if we have so many homeworks that the uniqueid function is 
			# starting to generate already existing ids
			if ($result) {
				# do this as we get warning 'Only variables should be passed by reference'
				$reversedDbResult=array_reverse($dbResult);
				$firstDbRow=array_pop($reversedDbResult);
				$homework=new Homework($firstDbRow);
				$_SESSION['homework']=$homework;
			}

			return $result;
		}

		function renderHomework($assignment) {

			$homework=$_SESSION['homework'];
			echo '<div class="assignment-content">'
					. file_get_contents($assignment) . 
				  '</div>';
			echo "<div class=\"homework-info\">";
			echo "<p><h4>Име:  </h4>" . $homework->getName() . "</p>"; 
			echo "<p><h4>Начало:  </h4>" . $homework->getStartDate() . "</p>";
			echo "<p><h4>Край:  </h4>" . $homework->getEndDate() . "</p>";
			echo "<p><h4>Програмен език:  </h4>" . $homework->getLanguage() . "</p>";
			echo "</div>";

			echo '<div>
				 	<form method="POST" action="" enctype="multipart/form-data">
						<label for="homework-submition"><b>Качи домашно: </b></label>
						<input id="hw-user-tests" type="file" name="homework-submition" required/>
						<input type="submit" value="Качи домашно"/>
					</form>
				  </div>';
		}

		function renderAllHomeworks() {
			$homeworkDao=new Dao();
			$homeworks=$homeworkDao->executeQuery('SELECT hw.*, hwl.name as language FROM Homework hw INNER JOIN Homework_Language hwl ON hw.id = hwl.id;', NULL);

			echo '<h2>Достъпни домашни работи</h2>';
			echo '<table>';
			echo '<tr>
					<th>Номер</th>
				    <th>Заглавие</th>
				    <th>Начална дата</th>
				    <th>Крайна дата</th>
				    <th>Програмен език</th>
				  </tr>';
			foreach($homeworks as $homework) {
				$homeworkId=$homework['folder'];
				$homeworkName=$homework['title'];
				$id=$homework['id'];
				$startDate=$homework['start_date'];
				$endDate=$homework['end_date'];
				$language=$homework['language'];
				echo '<tr>';
				echo "<td>${id}</td>";
				echo "<td><a href=\"?page=homework/view&id=${homeworkId}\"><b>${homeworkName}</b></a></td>";
				echo "<td>${startDate}</td>";
				echo "<td>${endDate}</td>";
				echo "<td>${language}</td>";
				echo '</tr>';
			}
			echo '</table>';
		}

		function renderPageContent($homeworkId) {
			$files = glob("view/homeworks/${homeworkId}/assignment.*");
			if(count($files) > 0) {
				$assignment=$files[0];
				if (checkHomeworkDBRecord($homeworkId)) {
					renderHomework($assignment);
				} else {
					renderError();
				}
			} else {
				renderAllHomeworks();
			}
		}

		function runTests($path, $homeworkId) {

			$homework=$_SESSION['homework'];
			$language=$homework->getLanguage();
			require_once "tests/base_tests_runner.php";
			require_once "tests/${language}/tests_runner.php";
			$testsRunner=new TestRunner("view/homeworks/${homeworkId}/hw_tests/tests", $path . "/tests");
			$testsRunner->run();
		}

		function uploadSolution($extension, $homeworkId) {
			validateExtension($extension, ["zip"]);
			# get the current logged in username and add the tests to his own folder
			$username=$_SESSION['__userData']->getUsername();

			try {
				uploadHomeworkFile('homework-submition', "/user_tests/${username}/", 'tests', $homeworkId, ["zip"]);
				$shell=new Shell();
				# unzip tests.zip file
				$path="view/homeworks/${homeworkId}/user_tests/${username}";
				$shell->execute("unzip ${path}/tests.zip -d ${path}/tests");
				# remove tests.zip file
				$shell->execute("rm ${path}/tests.zip");
				runTests($path, $homeworkId);
			} catch (Exception $e) {
				echo '<div id="upload-failed">';
				echo '<h2>' . $e->getMessage() . '</h2>';
				echo '</div>';
			}
		}

		function checkForUpload($homeworkId) {
			if ($homeworkId!=NULL) {
				if (!file_exists("view/homeworks/" . $homeworkId)) {
					renderError();
				} else {
					# check if the user has uploaded homework for testing
					if(isset($_FILES['homework-submition']) && $_FILES['homework-submition']['size'] > 0) {
						$fileParts = explode('.', $_FILES['homework-submition']['name']);
						$extension = end($fileParts);
						# process only if the file has .zip extension
						try {
							uploadSolution($extension, $homeworkId);
						} catch (Exception $e) {
							echo '<div id="upload-failed">';
							echo '<h2>' . $e->getMessage() . '</h2>';
							echo '</div>';
						}
					}
				}
			}
		}
	?>	


	<?php 
		# Before all execution
		checkPageForErrors();
	?>
	
	<div class="homeworks-page">
		<?php 
			$homeworkId=getHomeworkId();
			renderPageContent($homeworkId);
			checkForUpload($homeworkId);
		?>
	</div>
</body>
</html>