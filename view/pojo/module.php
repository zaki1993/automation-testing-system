<?php

	class Module {

		private $name;
		private $executables;

		public function __construct($moduleXml) {
			$this->__init();
			$this->name=(string)$moduleXml->Name;
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

		public function setName($newName) {
			$this->name=$newName;
		}

		public function setExecutables($newExecutables) {
			$this->executables=$newExecutables;
		}
	}

?>