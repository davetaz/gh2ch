<?php

   $url = trim($_GET["repo"]);

   if($url == null || $url == "" || (substr($url,0,4) != "http")) {
	if ($_GET["script"]) {
		exit();
	} else {
		header("HTTP/1.1 400 Bad Request");	
		exit();
	}
   }

   $software_name = $_GET["software"];
   $distribution = $_GET["distribution"];
	if ($distribution == "") {
		$distribution = "unstable";
	}
   $urgency = $_GET["urgency"];
	if ($urgency == "") {
		$urgency = "low";
	}

   $base_json_url = str_replace("github.com","api.github.com/repos",$url);
   if ($software_name == "") {
	$software_name = substr($url,strrpos($url,"/")+1,strlen($url));
   }

   $output = process_github_changes($software_name,$base_json_url,$distribution,$urgency);
   if ($_GET["script"]) {
	$changelog = tempnam(sys_get_temp_dir(),'changelog');
	$handle = fopen($changelog,"w");
	fwrite($handle,$output);
	fclose($handle);
	echo $changelog;
   } else {
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header("Content-type: text/plain");
        header('Content-Disposition: attachment; filename="changelog"');
	echo $output;
   }

function process_github_changes($software_name,$base_json_url,$distribution,$urgency) {
	
	$next = true;
	while ($next == true) {
		$url = $base_json_url . "/commits?per_page=100";
		if ($last_sha != "") {
			$url = $url . "&sha=" . $last_sha;
		}
		$json_string = null;
		$handle = fopen($url,"r");
		if ($handle) {
			while (!feof($handle)) {
				$json_string  .= fgets($handle);
			}
		}
		fclose($handle);

		$commits = json_decode($json_string,true);
		if (count($commits) == 0 || count($commits) < 100) {
			$next = false;
		}
		$last_sha = "";
		for($i=0;$i<count($commits);$i++) {
			$object["sha"] = $commits[$i]["sha"];
			$node = $commits[$i]["commit"];
			$object["message"] = $node["message"];
			$object["date"] = $node["committer"]["date"];
			$object["name"] = $node["committer"]["name"];
			$object["email"] = $node["committer"]["email"];
			$commit[] = $object;
			$last_sha = $commits[$i]["sha"];
		}
	}
	
    $json_string = null;
    $url = $base_json_url . "/tags?per_page=100";
    $handle = fopen($url,"r");
    if ($handle) {
        while (!feof($handle)) {
            $json_string  .= fgets($handle);
        }
    }
    fclose($handle);

    $tags = json_decode($json_string,true);

    for($i=0;$i<count($tags);$i++) {
        $version = $tags[$i]["name"];
	$sha = $tags[$i]["commit"]["sha"];
	$tag[$version] = $sha;
    }
    krsort($tag);

    foreach($tag as $version => $sha) {
	$foo["version"] = $version;
 	$foo["sha"] = $sha;
	$out_tags[] = $foo;	
    }

    $tags = $out_tags;
    $last_commit_pos = 0;
    $open = false;
    for($i=0;$i<count($tags);$i++) {
	$current_sha = $tags[$i]["sha"];
	$next_sha = $tags[$i+1]["sha"];
	for($j=$last_commit_pos;$j<count($commit);$j++) {
		if ($commit[$j]["sha"] == $current_sha) {
			$tags[$i]["commits"][] = $commit[$j];
			$last_commit_pos=$j;
			$open = true;
		} elseif ($open && $commit[$j]["sha"] != $next_sha) {
			$tags[$i]["commits"][] = $commit[$j];
			$last_commit_pos=$j;
		} elseif ($commit[$j]["sha"] == $next_sha) {
			$open = false;
		}
	}
    }	

    $commit = "";
    $commits = "";
  
    for($i=0;$i<count($tags);$i++) {
	$author = "";
	$version_string = $software_name . ' (' . $tags[$i]["version"] .') '.$distribution.'; urgency=' . $urgency . "\n";
	$commits = $tags[$i]["commits"];
	for ($j=count($commits);$j>0;$j--) {
		$pos = $j-1;
		if ($pos == 0) {
			$time = strtotime($commits[$pos]["date"]);
			$date = date("r",$time);
			$tag_author = " -- " . $commits[$pos]["name"] . " <" . $commits[$pos]["email"] .">  " . $date . "\n";
		}
		$messages = $commits[$pos]["message"];
		foreach(preg_split("/(\r?\n)/", $messages) as $line){
		    $line = trim($line);
		    if ($line != "") {
			$messages_out .= "  * " . $line . "\n";
		    }
		}
	}
	$author["[".$commits[$pos]["name"] . " <" . $commits[$pos]["email"] . ">]"] = $messages_out; 
    	$ret .= $version_string;
	$ret .= "\n";
	foreach($author as $name => $messages) {
		$ret .= "  " . $name . "\n";
		$ret .= $author[$name];
	}
	$ret .= "\n"; 
	$ret .= $tag_author;
	$ret .= "\n";
    }
    return $ret;
}

?>

