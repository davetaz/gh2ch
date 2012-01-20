<?php

	if ($_GET["repo"]) {
		header("Status: 303 See Other");
		header("Location: get_changelog.php?". $_SERVER['QUERY_STRING']);
	}

?>
<html>
<head>
	<title>GitHub Commits to Debian Changelog</title>
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
</head>
<body>
<div class="content" align="center">
	<a class="help" onclick="showHelp();"><img class="help" src="img/help.gif"/></a>
	<img class="github-icon" src="img/github.jpg"/>
	<img class="arrow" src="img/arrow.png"/>
	<img class="changelog-icon" src="img/debian.png"/>
	<div class="title">
		GitHub Commits to Debian Changelog
	</div>
	<div class="API">
		API: ?repo=<i>http://repo_url.com</i>&amp;software=<i>software name</i>&amp;distribution=<i>distribution</i>&amp;urgency=<i>urgency</i>
	</div>
	<form action="javascript: getChangelog(repo,software,distribution,urgency);">
	<div class="repo">
		<input class="repo-text auto-hint" id="repo" name="repo" type="text" title="repo e.g. https://github.com/openplanets/fido"/>
	</div>
	<div class="software-name" align="right">
		Software Name: 
		<span class="text-span">
			<input class="software-text suggested" name="software" type="text"/>
		</span>
	</div>
	<div class="debian-bits" align="center">
		<div class="distribution">
			Distribution: 
			<span class="text-span">
				<input class="distribution-text auto-hint" name="distribution" type="text" title="i.e. stable"/>
			</span>
		</div>
		<div class="urgency">
			Urgency: 
			<span class="text-span">
				<select class="urgency-select" name="urgency">
					<option name="low" value="low">Low</option>
					<option name="medium" value="medium">Medium</option>
					<option name="high" value="high">High</option>
					<option name="emergency" value="emergency">Emergency</option>
					<option name="critical" value="critical">Critial</option>
				</select>
			</span>
		</div>
	</div>
	<div class="submit" id="submit">
		<input class="submit-button" type="submit" name="submit" value="Get ChangeLog!"/>
	</div>
	</form>
</div>
<div class="footer" align="center">
	<div class="feedback">
		Feedback: d a v e t a z [ at ] g m a i l . c o m
	</div>
	<div class="copyright" align="right">
		&copy; David Tarrant, University of Southampton <?php echo date("Y") ?>
	</div>
</div>
<script type="text/javascript">
	var repo;
	var software;
	var distribution;
	var urgency;

	$("form").submit(function() {
		repo = $("input:text[name=repo]").val();
		software = $("input:text[name=software]").val();
		distribution = $("input:text[name=distribution]").val();
		urgency = $("select[name=urgency]").val();
		if (repo == "" || repo.indexOf(" ") > -1) {
			alert("Please enter a valid repository location");
			return false;
		}
		if (software == "") {
			alert("Please enter a valid software name");
			return false;
		}
		if (repo.substring(repo.length-1,repo.length) == "/") {
			repo = repo.substring(0,repo.length-1);
			document.getElementById("repo").value = repo;
		}
		document.getElementById("submit").innerHTML = "<img width='26px' src='img/ajax-loader.gif' style='margin-top: 6px;'/>";
		return true;
	});

	$(document).keyup(function(e) {
  		if (e.keyCode == 27) { hideHelp(); }
	});
</script>
</body>
</html>
