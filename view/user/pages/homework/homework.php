<?php
	class Homework {
		private $name;
		private $startDate;
		private $endDate;
		private $language;

		function __construct($dbRow) {
			$this->name=$dbRow['title'];
			$this->startDate=$dbRow['start_date'];
			$this->endDate=$dbRow['end_date'];
			$this->language=$dbRow['language'];
		}

		function getName() {
			return $this->name;
		}

		function getStartDate() {
			return $this->startDate;
		}

		function getEndDate() {
			return $this->endDate;
		}

		function getLanguage() {
			return $this->language;
		}
	}
?>