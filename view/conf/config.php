<?php

	class Configuration {

		private $modules;

		public function __construct() {
			$this->__init();
			$xml=@simplexml_load_file("view/conf/config.xml") or die("Could not load configuration..!");
			$this->__parseXml($xml);
		}

		private function __init() {
			require_once "view/pojo/module.php";

			$this->modules=array();
		}

		private function __parseXml($xml) {

			foreach ($xml->Modules->Module as $moduleXml) {
				$module=new Module($moduleXml);
				# push with module name for easier looping later
				$this->modules[(string)$module->getName()]=$module;
			}
		}

		public function getModules() {
			return $this->modules;
		}

		public function getModuleByName($name) {
			if (array_key_exists($name, $this->modules)) {
				return $this->modules[$name];
			}
			throw new Exception("Unfortunately " . $name . " is not configured yet!");
		}
	}
?>