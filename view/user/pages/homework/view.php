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
			$dbResult=$homeworkDao->executeQuery('SELECT hw.title, hw.start_date, hw.end_date, hwl.name as language FROM Homework hw INNER JOIN Homework_Language hwl ON hw.folder = hwl.folder WHERE hw.folder=?;', [$homeworkId]);
			
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
						<label for="homework-submition"><b>Качи тестове: </b></label>
						<input id="hw-user-tests" type="file" name="homework-submition" required/>
						<input type="submit" value="Качи тестове" id="submit-tests"/>
					</form>
				  </div>';
		}

		function renderAllHomeworks() {
			$homeworkDao=new Dao();
			$homeworks=$homeworkDao->executeQuery('SELECT hw.*, hwl.name as language FROM Homework hw INNER JOIN Homework_Language hwl ON hw.folder = hwl.folder;', NULL);

			echo '<h2>Достъпни домашни работи</h2>';
			echo '<table>';
			echo '<tr>
					<th>Номер</th>
				    <th>Заглавие</th>
				    <th>Начална дата</th>
				    <th>Крайна дата</th>
				    <th>Програмен език</th>
				  </tr>';
			foreach($homeworks as $key=>$homework) {
				$homeworkId=$homework['folder'];
				$homeworkName=$homework['title'];
				$startDate=$homework['start_date'];
				$endDate=$homework['end_date'];
				$language=$homework['language'];
				echo '<tr>';
				echo "<td>${key}</td>";
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
					echo "<div class=\"homeworks-page\">";
					renderHomework($assignment);
					checkForUpload($homeworkId);
					echo "</div>";
				} else {
					throw new Exception("");
				}
			} else if($homeworkId!=NULL) {
				throw new Exception("");
			} else {
				echo "<div class=\"homeworks-page\">";
				renderAllHomeworks();
				echo "</div>";
			}
		}

		function runTests($path, $homeworkId) {

			$homework=$_SESSION['homework'];
			$language=$homework->getLanguage();
			require_once "tests/base_tests_runner.php";
			require_once "tests/${language}/tests_runner.php";
			$testsRunner=new TestRunner("view/homeworks/${homeworkId}/hw_tests/tests", $path . "/tests");
			return $testsRunner->run();
		}

		function uploadSolution($extension, $homeworkId) {
			validateExtension($extension, ["zip"]);
			# get the current logged in username and add the tests to his own folder
			$username=$_SESSION['__userData']->getUsername();

			try {
				uploadHomeworkFile('homework-submition', "/user_tests/${username}/", 'tests', $homeworkId, ["zip"]);

				$path="view/homeworks/${homeworkId}/user_tests/${username}";

				$shell=new Shell();	

				# delete everything in the user_tests folder
				# in case that the folder was already there
				# meaning that the user wants to execute new tests
				$shell->execute("rm ${path}/tests/* -rd");

				# unzip tests.zip file
				$shell->execute("unzip ${path}/tests.zip -d ${path}/tests");
				# remove tests.zip file
				$shell->execute("rm ${path}/tests.zip");
				$successTests=runTests($path, $homeworkId);
				insertUserTestResults($username, $homeworkId, $successTests);
			} catch (Exception $e) {
				echo getErrorBlock($e->getMessage());
			}
		}

		function insertUserTestResults($username, $homeworkId, $successTests) {
			$userTestsDao=new Dao();
			$userCurrentTests=$userTestsDao->executeQuery("SELECT uh.score FROM User_Homework uh WHERE uh.user_name=? AND uh.folder=?;", [$username, $homeworkId]);

			$score;
			if (count($userCurrentTests) > 0) {
				$score=$userCurrentTests[0]['score'];
			} else {
				$score=-1;
			}
			if ($score==-1 && $score < $successTests) {
				$userTestsDao->executeInsert("INSERT INTO User_Homework(folder, user_name, score) VALUES(?, ?, ?);", [$homeworkId, $username, $successTests]);
			} else if ($score!=-1 && $score < $successTests) {
				$userTestsDao->executeQuery("UPDATE User_Homework SET score=? WHERE folder=? AND user_name=?;", [$successTests, $homeworkId, $username]);
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
							echo getErrorBlock($e->getMessage());
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
	
	<?php 
		try {	
			$homeworkId=getHomeworkId();
			renderPageContent($homeworkId);
		} catch(Exception $e) {
			renderError();
		}
	?>
</body>
</html>