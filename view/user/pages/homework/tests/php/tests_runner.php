<?php
	
	# class that will be used to run php tests
	class TestRunner extends BaseTestRunner {
		private $shell;
		private $php;

		public function run() {
			$this->shell=new Shell();
			$this->__initPhp();
		}

		private function __initPhp() {
			$configuration=$_SESSION['configuration'];
			$modules=$configuration->getModuleByName('php');
			$executables=$modules->getExecutables();
			$this->php=$executables[0];
		}
	}
?>