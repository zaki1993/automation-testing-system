<?php
	
	class Runnable {

		private $version;
		private $path;

		public function __construct($runnableXml) {
			$this->version=(string)$runnableXml->Version;
			$this->path=(string)$runnableXml->Path;
		}

		public function getVersion() {
			return $this->version;
		}

		public function getPath() {
			return $this->path;
		}
	}
?>