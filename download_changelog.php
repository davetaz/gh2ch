<?php
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	$file = $_GET["changelog"];
	if (file_exists($file)) {
		header("Content-type: text/plain");
		header('Content-Disposition: attachment; filename="changelog"');
		$handle = fopen($file,"r");
		while(!feof($handle)) {
			echo fgets($handle);
		}
		fclose($handle);
		unlink($file);
	} else {
		header("HTTP/1.1 400 Bad Request");
	}
?>
