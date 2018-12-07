<?php

	class Executable {

		private $version;
		private $path;

		public function __construct($executebleXml) {
			$this->version=(string)$executebleXml->Version;
			$this->path=(string)$executebleXml->Path;
		}

		public function getVersion() {
			return $this->version;
		}

		public function getPath() {
			return $this->path;
		}
	}
?>