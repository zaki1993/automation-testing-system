<?php

	# class that will be used to run java tests
	class TestRunner extends BaseTestRunner {
		private $shell;
		private $jdk;
		private $jre;

		function run() {
			$this->shell=new Shell();
			$this->__initJava();
			$this->compile();
		}

		private function __initJava() {
			$configuration=$_SESSION['configuration'];
			$modules=$configuration->getModuleByName('java');
			# assume that we have one defined executable
			// TODO chose from more than one executable
			$executable=$modules->getExecutables()[0];
			$this->jdk=$executable->getCompiler();
			$this->jre=$executable->getRuntime();
		}

		# function used to compile java files
		private function compile() {

			$userTests=$this->userTests;
			# get java compiler path
			$jdkPath= $this->jdk->getPath();
			$resultMsg=$this->shell->execute("cd ${userTests} && ${jdkPath} *");
			echo $resultMsg;
			if ($resultMsg!=='') {
				echo "error";
				// TODO
			}
		}
	}
?>