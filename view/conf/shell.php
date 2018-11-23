<?php
	
	class Shell {
		public function execute($command) {
			return shell_exec($command. " 2>&1"); # 2>&1 is used to forward the errors to the output
		}
	}
?>