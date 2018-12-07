<?php
	
	# class that will be used to run php tests
	class TestRunner extends BaseTestRunner {
		private $shell;
		private $jdk;
		private $jre;

		public function run() {
			$this->shell=new Shell();
			$this->__initPhp();
		}

		private function __initPhp() {
			$configuration=$_SESSION['configuration'];
			$modules=$configuration->getModuleByName('php');
			# assume that we have one defined executable
			// TODO chose from more than one executable
			$executable=$modules->getExecutables()[0];
			$this->jdk=$executable->getCompiler();
			$this->jre=$executable->getRuntime();
		}
	}
?>