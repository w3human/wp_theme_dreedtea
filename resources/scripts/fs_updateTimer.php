<?php 
	header("Content-type: text/javascript");
	
	date_default_timezone_set("MST");

	$_prefix=(! empty($_GET["prefix"]))?$_GET["prefix"]:"";
	
	$msTime=$_prefix."msTime";
	$currentTime=$_prefix."currentTime";
	$timeOnceLoad=$_prefix."timeOnceLoad";
	$endDate=$_prefix."endDate";
	$mstHTTP=$_prefix."mstHTTP";
	
	$onceLoaded=$_prefix."onceLoaded";
	$updateTimer=$_prefix."updateTimer";
	
	$timeElement=((! empty($_GET["timeElement"]))?$_GET["timeElement"]:$_prefix."timeLeft");
	$endElement=((! empty($_GET["endElement"]))?$_GET["endElement"]:"doc_body");
?>

var <?= $msTime ?>=new Date("<?= date('F j, Y G:i:s'); ?>");
var <?= $timeOnceLoad ?>=new Date();

function <?= $updateTimer ?>() {	
	var <?= $currentTime ?>=new Date();
	
	<?= $msTime ?>.setTime(<?= $msTime ?>.getTime()+(<?= $currentTime ?>.getTime()-<?= $timeOnceLoad ?>.getTime()));

	if (<?= $endDate ?>.getTime() > <?= $msTime ?>.getTime()) {
		var days=Math.round((<?= $endDate ?>.getTime()-<?= $msTime ?>.getTime())/(1000*60*60*24));
			var hours=Math.round(23-<?= $msTime ?>.getHours());
			var mins=Math.round(60-<?= $msTime ?>.getMinutes());
	
		document.getElementById("<?= $timeElement ?>").innerHTML=""+ days +"D "+ hours +"H "+ mins +"M";
		setTimeout(<?= $updateTimer ?>, (1000*10));	
	}
	else {
		aClass(<?= $endElement ?>, "flashsaleHasEnded");
	}
}

<?= $updateTimer ?>();

