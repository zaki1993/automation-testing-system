<?php

	class Executable {

		private $compiler;
		private $runtime;

		public function __construct($executableXml) {
			$this->__init();
			$this->compiler=new Runnable($executableXml->Compiler);
			$this->runtime=new Runnable($executableXml->Runtime);
		}

		private function __init() {
			require_once "runnable.php";
		}

		public function getCompiler() {
			return $this->compiler;
		}

		public function getRuntime() {
			return $this->compiler;
		}
	}
?>