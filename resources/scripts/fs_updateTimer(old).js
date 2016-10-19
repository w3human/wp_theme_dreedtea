var currentTime=new Date();

if (window.XMLHttpRequest) {var xmlhttp=new XMLHttpRequest();}
else {var xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
	
xmlhttp.onreadystatechange=function() {
	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		var currentTime=new Date(xmlhttp.responseText);
		onceLoaded();
	}
}

function onceLoaded() {
	var writeTimeLeft=new Boolean(false);

	if (endDate.getTime() > currentTime.getTime()) {
		var days=Math.round((endDate.getTime()-currentTime.getTime())/(1000*60*60*24));
			var hours=Math.round(23-currentTime.getHours());
			var mins=Math.round(60-currentTime.getMinutes());
		
		writeTimeLeft=new Boolean(true);		
	}
	else if (
		endHour!=undefined 
		&&
		(endDate.getMonth()==currentTime.getMonth())
		&&
		(endDate.getDate()==currentTime.getDate())
		&&
		(endDate.getFullYear()==currentTime.getFullYear())
	) {
		var days=0;
			var hours=Math.round(endHour-currentTime.getHours());
			var mins=Math.round(60-currentTime.getMinutes());
	
		writeTimeLeft=new Boolean(true);
	}

	if (writeTimeLeft==true) {
		document.getElementById("timeLeft").innerHTML=""+ days +"D "+ hours +"H "+ mins +"M";
		// setTimeout(getTIME, (1000*10));
	}
	else {		
		aClass(doc_body, "flashsaleHasEnded");
	}
}

function updateTimer() {
	xmlhttp.open("POST", "http://timeapi.org/utc/now.json?callback=gotIT", true);
	xmlhttp.send();
}

updateTimer();
