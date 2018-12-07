<?php

	# class that will be used to run java tests
	class TestRunner extends BaseTestRunner {
		private $shell;
		private $jdk;
		private $jre;

		public function run() {
			$this->shell=new Shell();
			$this->__initJava();
			$this->compile();
			return $this->runTests();
		}

		private function __initJava() {
			$configuration=$_SESSION['configuration'];
			$modules=$configuration->getModuleByName('java');
			$executables=$modules->getExecutables();
			$this->jdk=$executables[0];
			$this->jre=$executables[1];
		}

		# function used to compile java files
		private function compile() {

			$userTests=$this->userTests;
			# get java compiler path
			$jdkPath=$this->jdk->getPath(); 

			$resultMsg=$this->shell->execute("cd ${userTests} && ${jdkPath} -cp junit-4.1.jar *.java");
			if (strlen($resultMsg)>0) {
				throw new Exception($resultMsg);
			}		
		}

		private function runTests() {

			$userTests=$this->userTests;
			$jrePath=$this->jre->getPath();
			$resultMsg=$this->shell->execute("cd ${userTests} && ${jrePath} -cp junit-4.1.jar:. org.junit.runner.JUnitCore TestCalculator");
			echo "<div id=\"tests-block\"><pre>${resultMsg}</pre></div>";
			return $this->numberOfSuccessfullTests($resultMsg);
		}

		private function numberOfSuccessfullTests($result) {
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