<?php

	# class that will be used to run java tests
	class TestRunner extends BaseTestRunner {
		private $shell;
		private $jdk;
		private $jre;

		public function run($userInput) {
			$this->shell=new Shell();
			$this->__initJava();
			$this->compile($userInput);
			return $this->runTests($userInput);
		}

		private function __initJava() {
			$configuration=$_SESSION['configuration'];
			$modules=$configuration->getModuleByName('java');
			$executables=$modules->getExecutables();
			$this->jdk=$executables[0];
			$this->jre=$executables[1];
		}

		private function compile($userInput) {
			if ($userInput!=NULL) {
				$this->compileWithUserInput();
			} else {
				$this->compileWithJUnit();
			}
		}

		# function used to compile java files
		private function compileWithJUnit() {

			$userTests=$this->userTests;
			# get java compiler path
			$jdkPath=$this->jdk->getPath(); 

			$resultMsg=$this->shell->execute("cd ${userTests} && ${jdkPath} -cp junit-4.1.jar *.java");
			if (strlen($resultMsg)>0) {
				throw new Exception($resultMsg);
			}		
		}

		# function used to compile java files
		private function compileWithUserInput() {

			$userTests=$this->userTests;
			# get java compiler path
			$jdkPath=$this->jdk->getPath(); 

			$resultMsg=$this->shell->execute("cd ${userTests} && ${jdkPath} *.java");
			if (strlen($resultMsg)>0) {
				throw new Exception($resultMsg);
			}		
		}

		private function runTests($userInput) {

			$userTests=$this->userTests;
			$jrePath=$this->jre->getPath();

			$resultMsg=NULL;
			if ($userInput==NULL) {
				// execute JUnit tests
				$resultMsg=$this->runJUnit($userTests, $jrePath);
			} else {
				$resultMsg=$this->runTestsAgainstUserInput($userTests, $jrePath, $userInput);
			}

			echo "<div id=\"tests-block\"><pre>${resultMsg}</pre></div>";
			return $this->numberOfSuccessfullTests($resultMsg, $userInput);
		}

		// TODO search for main method
		private function runTestsAgainstUserInput($userTests, $jrePath, $userInput) {
			return $this->shell->execute("cd ${userTests} && echo ${userInput} | ${jrePath} TestCalculatorUserInput");
		}

		// TODO search for main method
		private function runJUnit($userTests, $jrePath) {
			return $this->shell->execute("cd ${userTests} && ${jrePath} -cp junit-4.1.jar:. org.junit.runner.JUnitCore TestCalculator");
		}

		private function numberOfSuccessfullTests($result, $userInput) {
			if ($userInput!=NULL) {
				return 0; // user input tests give 0 score points
			}
			try {
				if (strpos($result, "Tests run:")!=false) {
					$testResult=substr($result, strpos($result, "Tests run:"));
					$testParts=explode(",", $testResult);
					$totalCount=explode(":", $testParts[0])[1];
					$failCount=explode(":", $testParts[1])[1];
					return $totalCount - $failCount;
				} else {
					$testResult=substr($result, strpos($result, "OK (") + 4);
					$totalCount=explode(" ", $testResult)[0];
					return $totalCount;
				}
			} catch (Exception $e) {
				return 0;
			}
		}
	}
?>