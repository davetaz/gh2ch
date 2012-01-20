
$(document).ready(function(){
	$('INPUT.auto-hint, TEXTAREA.auto-hint').focus(function(){
	    if($(this).val() == $(this).attr('title')){
        	$(this).val('');
	        $(this).removeClass('auto-hint');
	    }
	});
	$('INPUT.auto-hint, TEXTAREA.auto-hint').blur(function(){
	    if($(this).val() == '' && $(this).attr('title') != ''){
		$(this).val($(this).attr('title'));
	        $(this).addClass('auto-hint');
	    }
	});
	$('INPUT.auto-hint, TEXTAREA.auto-hint').each(function(){
	    if($(this).attr('title') == ''){ return; }
	    if($(this).val() == ''){ $(this).val($(this).attr('title')); }
	    else { $(this).removeClass('auto-hint'); }
	});
});

function getChangelog(repo,software,distribution,urgency) {
	$.ajax({
		type: "GET",
                url: "get_changelog.php?repo="+repo+"&software="+software+"&distribution="+distribution+"&urgency="+urgency+"&script=true",
                cache: false,
                async: true,
		success: function(data) {
			if (data.length > 0) {
				//document.getElementById("submit").innerHTML = data;
				document.getElementById("submit").innerHTML = '<div align="center" style="font-size: 24px; margin-top: 6px;" >DONE</div>';
				window.location = "download_changelog.php?changelog=" + data;
			} else {
				document.getElementById("submit").innerHTML = '<input class="submit-button" type="submit" name="submit" value="FAILED!"/>';
			}
			return true;
		},
		error: function(data) {
			return false;
		}
	});
}

function showHelp() {
	var overlay = document.createElement("div");
	overlay.setAttribute("id","overlay");
   	overlay.setAttribute("class", "overlay");
   	document.body.appendChild(overlay);

	var help = document.createElement("div");
	help.setAttribute("id","helpdiv");
	help.setAttribute("class","helpdiv");
	$.get("help.php?window=true", function(data) {
		help.innerHTML = data;
	});
	document.body.appendChild(help);
}
function hideHelp() {
	document.body.removeChild(document.getElementById("helpdiv"));
	document.body.removeChild(document.getElementById("overlay"));
}
