<?php
/*
various file functions.
*/

	function writeContent($filename, $content) {
		$file = fopen($filename, "w");
		fwrite($file, $content);
		fclose($file);
	}

	// loads the content from a file
	function loadContent($filename) {
		$content = "";
		$file = @fopen($filename, "r");
		$content = @fread($file, @filesize($filename));
		@fclose($file);
		return $content;
	}

?>