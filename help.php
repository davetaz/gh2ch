<?php
	if (!$_GET["window"]) {
?>
<html>
<head>
	<title>GitHub Commits to Debian Changelog - Help</title>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>
<body>
<div class="content" align="center">
	<img class="github-icon" src="img/github.jpg"/>
	<img class="arrow" src="img/arrow.png"/>
	<img class="changelog-icon" src="img/debian.png"/>
	<div class="title">
		GitHub Commits to Debian Changelog
	</div>
<?php
	} else {
	echo '<div class="helppop" align="center">';
	} 
?>
	<p class="helptext">
	This system works by creating changelogs between <b>tags</b> of a single repository in GitHub. Best results are achieved by tagging your releases with a X.Y.Z version number as per the debian/linux standard.
	</p>
	<p class="helptext">
	The following is a screenshot of how your tags page in github should look:
	</p>
	<img src="img/Github_tags.png" width="540px"/>
<?php 
	if (!$_GET["window"]) {
		echo '</html>';
	} else {
		echo '<div align="center">';
		echo '<div class="submit" id="submit"><input style="text-align: center;" class="submit-button" onclick="hideHelp();" value="Close"/></div>';
		echo '</div></div>';
	}
?>
