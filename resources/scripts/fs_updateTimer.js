var currentTime=new Date();

if (window.XMLHttpRequest) {var mstHTTP=new XMLHttpRequest();}
else {var mstHTTP=new ActiveXObject("Microsoft.XMLHTTP");}
	
mstHTTP.onreadystatechange=function() {
	if (mstHTTP.readyState==4 && mstHTTP.status==200) {
		onceLoaded(mstHTTP.responseText);
	}
};

function onceLoaded(cTIME) {	
	var currentTime=new Date(cTIME);
	
	if (endDate.getTime() > currentTime.getTime()) {
		var days=Math.round((endDate.getTime()-currentTime.getTime())/(1000*60*60*24));
			var hours=Math.round(23-currentTime.getHours());
			var mins=Math.round(60-currentTime.getMinutes());
			
		document.getElementById("timeLeft").innerHTML=""+ days +"D "+ hours +"H "+ mins +"M";
		setTimeout(updateTimer, (1000*10));	
	}
	else {
		aClass(doc_body, "flashsaleHasEnded");
	}
}

function updateTimer() {
	mstHTTP.open("GET", site.theme + "/ajax-MST.php", true);
	mstHTTP.send();
}

updateTimer();
