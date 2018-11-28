<!DOCTYPE html>
<html>
<head>
	<title>Качи домашно</title>
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
		function validatePermission() {
			$userRole=getRole($_SESSION);
			if ($userRole!=="admin") {
				renderError();
			}
		}

		function validateExtension($extension, $extensions) {
			if ($extensions!=NULL) {
				if (!in_array($extension, $extensions)) {
					throw new Exception("Extension " . $extension . " is not in the allowed file extensions..!");
				}
			}
		}

		# third param is list of extensions which are allowed
		# if NULL is provided no checks are done
		function uploadFile($fileName, $homeworkId, $extensions) {
			$tmpFile = $_FILES[$fileName]['tmp_name'];
			if (!file_exists("view/homeworks/" . $homeworkId)) {
				mkdir("view/homeworks/" . $homeworkId);
			}
			$fileSplitted = explode('.', $_FILES[$fileName]['name']);
			$extension = end($fileSplitted);	
			# this checks if the file has no extension
			# if the file has no extension then by default take the txt one		
			if(count($fileSplitted) == 1) {
				$extension="txt";
			}
			validateExtension($extension, $extensions);
			$newFile = "view/homeworks/" . $homeworkId . "/assignment." . $extension;
			$result = move_uploaded_file($tmpFile, $newFile);
		}

		function insertHomeworkInDB($homeworkId, $startDate, $endDate) {
			$homeworkDao=new Dao();
			$homeworkDao->executeInsert('INSERT INTO Homework(folder, start_date, end_date) VALUES(?, ?, ?);', [$homeworkId, $startDate, $endDate]);
		}

		function checkForHomeworkUpload() {
			if (isset($_FILES['homework-assignment'])) {
				$homeworkId=uniqid();
				try {
					uploadFile('homework-assignment', $homeworkId, ["txt"]);

					if (isset($_FILES['homework-tests'])) {
						uploadFile('homework-tests', $homeworkId, NULL);
					}

					$startDate=$_POST['homework-start-date'];
					$endDate=$_POST['homework-end-date'];

					insertHomeworkInDB($homeworkId, $startDate, $endDate);

			        echo "<script type=\"text/javascript\">
					        window.location = \"./?page=homework/view&id=${homeworkId}\"
					     </script>";
				} catch (Exception $e) {
					echo '<div id="upload-failed">';
					echo '<h2>' . $e->getMessage() . '</h2>';
					echo '</div>';
				}
			}
		}

		validatePermission();
		checkForHomeworkUpload();		
	?>
	<div class="upload-homework">
		<form action="" method="POST" enctype="multipart/form-data">
			<label for="homework-assignmentm"><b>Уловие: </b></label>
			<input type="file" name="homework-assignment" required/>

			<label for="homework-tests"><b>Тестове: </b></label>
			<input type="file" name="homework-tests"/>

			<label for="homework-start-date"><b>Начална дата: </b></label>
			<input type="date" name="homework-start-date" required/>

			<label for="homework-end-date"><b>Крайна дата: </b></label>
			<input type="date" name="homework-end-date" required/>

			<input type="submit" name="upload-homework" value="Качи домашно">
		</form>
	</div>
</body>
</html>