<!-- Add onchange listeners to the file inputs in order to set the maximum size
	 allowed for file uploading. Be sure to set the max file size according to the
	 maximum file upload size in the php.ini file.
-->
<script type="text/javascript">
	window.onload = function() {
		var assignment = document.getElementById("hw-assignment");

		assignment.onchange = function() {
		    if(this.files[0].size > 1 * 1024 * 1024){
		       alert("File size is too big..! Upload file up to 1 MB.");
		       this.value = "";
		    };
		};

		var hwtests = document.getElementById("hw-tests");

		hwtests.onchange = function() {
		    if(this.files[0].size > 2 * 1024 * 1024){
		       alert("File size is too big..! Upload file up to 2 MB.");
		       this.value = "";
		    };
		};
	};
</script>
<?php
	function insertHomeworkInDB($homeworkId, $title, $startDate, $endDate, $language) {
		$homeworkDao=new Dao();
		$homeworkDao->executeInsert('INSERT INTO Homework(folder, title, start_date, end_date) VALUES(?, ?, ?, ?);', [$homeworkId, $title, $startDate, $endDate]);
		$homeworkDao->executeInsert('INSERT INTO Homework_Language(folder, name) VALUES(?, ?);', [$homeworkId, $language]);
	}

	function checkForHomeworkUpload() {
		if (isset($_FILES['homework-assignment'])) {
			$homeworkId=uniqid();
			try {
				uploadHomeworkFile('homework-assignment', '/', 'assignment', $homeworkId, ["txt"]);

				$title=$_POST['homework-title'];
				$startDate=$_POST['homework-start-date'];
				$endDate=$_POST['homework-end-date'];
				$language=$_POST['homework-language'];

				if (isset($_FILES['homework-tests'])) {
					uploadHomeworkFile('homework-tests', '/hw_tests/', 'tests', $homeworkId, ["zip"]);

					# create shell instance in order to execute shell scripts directly
					$shell=new Shell();
					# unzip tests.zip file
					$path="view/homeworks/${homeworkId}/hw_tests";
					$shell->execute("unzip ${path}/tests.zip -d ${path}/tests");
					# remove tests.zip file
					$shell->execute("rm ${path}/tests.zip");
				}

				insertHomeworkInDB($homeworkId, $title, $startDate, $endDate, $language);

		        echo "<script type=\"text/javascript\">
				        window.location = \"./?page=homework/view&id=${homeworkId}\"
				     </script>";
			} catch (Exception $e) {
				echo getErrorBlock($e->getMessage());
			}
		}
	}

	function showProgrammingLanguages() {
		$prLangs=getProgrammingLanguages();
		$result="<select name=\"homework-language\"> required";

		foreach ($prLangs as $lang) {
			$result.="<option>" . $lang['name'] . "</option>";
		}

		$result.="</select>";
		return $result;
	}
	
	function render() {
		validatePermission();
		checkForHomeworkUpload();	
		echo '<div class="upload-homework">
				<form action="" method="POST" enctype="multipart/form-data">
					<label for="homework-assignment"><b>Уловие: </b></label>
					<input id="hw-assignment" type="file" name="homework-assignment" required/>

					<label for="homework-title"><b>Заглавие: </b></label>
					<input type="text" name="homework-title" required/>

					<label for="homework-tests"><b>Тестове: </b></label>
					<input id="hw-tests" type="file" name="homework-tests"/>

					<label for="homework-start-date"><b>Начална дата: </b></label>
					<input type="date" name="homework-start-date" required/>

					<label for="homework-end-date"><b>Крайна дата: </b></label>
					<input type="date" name="homework-end-date" required/>

					<label for="homework-language"><b>Програмен език: </b></label>'
					. showProgrammingLanguages() .
					'<input type="submit" name="upload-homework" value="Качи домашно">
				</form>
			</div>';
	}
?>