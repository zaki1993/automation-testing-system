<?php
	
	class Shell {
		public function execute($command) {
			if ($command!=NULL) {
				# 2>&1 is used to forward the errors to the output
				return shell_exec($command . " 2>&1");
			}
			return NULL;
		}
	}
?>