<?php

	class Settings {
			var $template;
			var $message;
		
			function Settings() {
				
				
			}
			
			function Error(){
				
				$this->template = "../phpfiles/errorDisplay.php";
				require_once($this->template);
				
			}
			function LoadFile() {
				
				require_once($this->template);
			}
			
			function displayMessage($message) {
				
				echo "<table border='0' align='center'><tr>";
				echo "<td>$message</td></tr></table>";
				
				
			}
		
		
	} // End Class


?>