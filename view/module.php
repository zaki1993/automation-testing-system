<?php

	class Module {

		private $name;
		private $executables;

		public function __construct($moduleXml) {

			$this->__init();
			$this->name=$moduleXml->Name;
			foreach($moduleXml->Executables->Executable as $executableXml) {
				$executable=new Executable($executableXml);
				array_push($this->executables, $executable);
			}
		}

		private function __init() {

			require_once "executable.php";
			$this->executables=array();
		}

		public function getName() {
			return $this->name;
		}

		public function getExecutables() {
			return $this->executables;
		}
	}

?>