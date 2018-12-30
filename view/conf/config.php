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

		public function deleteModuleByName($moduleName) {
			$configXml = new DOMDocument; 
			$configXml->load('view/conf/config.xml');
			$configDocument=$configXml->documentElement;
			$modules=$configDocument->getElementsByTagName("Module");
			$toRemove=NULL;

			foreach ($modules as $module) {
				$name=$module->getElementsByTagName("Name")[0];
				if ($name->nodeValue===$moduleName) {
					$toRemove=$module;
					break;
				}
			}
			if ($toRemove!=NULL) {
				$toRemove->parentNode->removeChild($toRemove);
				$configXml->save("view/conf/config.xml");
			}
		}

		public function editModuleByName($moduleName, $properties) {
			if ($moduleName!=NULL) {
				if (array_key_exists($moduleName, $this->modules)) {
					//$this->deleteModuleByName($moduleName);
					//$this->addModuleFromProperties($properties);
					$this->editModuleFromProperties($moduleName, $properties);
				}
			}
		}

		public function addModuleFromProperties($properties) {
			$configXml=simplexml_load_file('view/conf/config.xml');
			$modules=$configXml->Modules;
			$newModule=$modules->addChild("Module");
			$newModule=$this->createModuleNode($newModule, $properties);

			$configXml->asXML('view/conf/config.xml');
		}

		private function editModuleFromProperties($moduleName, $properties) {
			$configXml = new DOMDocument; 
			$configXml->load('view/conf/config.xml');
			$configDocument=$configXml->documentElement;
			$modules=$configDocument->getElementsByTagName("Module");

			foreach ($modules as $module) {
				$name=$module->getElementsByTagName("Name")[0];
				if ($name->nodeValue===$moduleName) {
					// set new module name
					print_r($properties);

					// set executables
					$executables=$module->getElementsByTagName("Executable");
					$numberExecutables=(count($properties) - 3) / 2;
					if ($numberExecutables > 0) {
						for ($i=0; $i<$numberExecutables; $i++) {
							$executables[$i]->getElementsByTagName("Version")[0]->nodeValue=$properties["version-${i}"];
							$executables[$i]->getElementsByTagName("Path")[0]->nodeValue=$properties["path-${i}"];

						}
					}

					break;
				}
			}
			$configXml->save('view/conf/config.xml');
		}

		private function createModuleNode($moduleNode, $properties) {

			$moduleNode->addChild("Name", $properties['moduleName']);
			$executables=$moduleNode->addChild("Executables");
			$numberExecutables=(count($properties) - 3) / 2;
			if ($numberExecutables > 0) {
				for ($i=0; $i < $numberExecutables; $i++) {
					if ($properties["version-${i}"]!=="" && $properties["path-${i}"]!=="") {
						$executable=$executables->addChild("Executable");
						$executable->addChild("Version", $properties["version-${i}"]);
						$executable->addChild("Path", $properties["path-${i}"]);
					}
				}	
			}
			return $moduleNode;
		}
	}
?>