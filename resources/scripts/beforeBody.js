var fullvWidth=50*16, fullvWidth_em=fullvWidth/16;

tClass(doc_body, "lessThanMinW", (window.width < fullvWidth));

addEvent(window, "resize", function() {
	tClass(doc_body, "lessThanMinW", (window.width < fullvWidth));	
});

// keydownFs
var keydownFs=new Array();
	keydownFs["keyCode"]=new Array();;
	keydownFs["xFunction"]=new Array();
	
function add_keydown(keyCode, xFunction) {
	var keyReplaced=new Boolean(false);
	
	findingKeyCode: for (i=0; i<keydownFs["keyCode"].length; i++) {
		if (keydownFs.keyCode[i]==keyCode) {
			keydownFs.xFunction[i]=xFunction;
			
			keyReplaced=new Boolean(true);
			break findingKeyCode;
		}
	}
	
	if (keyReplaced==false) {
		keydownFs["keyCode"].splice(keydownFs["keyCode"].length, 0, Number(keyCode));
		keydownFs["xFunction"].splice(keydownFs["xFunction"].length, 0, xFunction);
	}
}

addEvent(window, "keydown", function(event) {
	findingKeyCode: for (i=0; i<keydownFs["keyCode"].length; i++) {
		if (keydownFs.keyCode[i]==event.keyCode) {
			keydownFs.xFunction[i]();
			break findingKeyCode;
		}
	}
});

// RSSS FEED
function showFeed(context, data) {
	var container=document.getElementById(context);
	container.innerHTML="";

	var mainList=document.createElement('ul');
	var entries=data.feed.entries;
	
	for (i=0; i<entries.length; i++) {
		var listItem=document.createElement("li");
	
		var link=document.createElement("a");
			link.setAttribute("href", entries[i].link);
			link.setAttribute("target", "_blank");
			link.className+=" title";
			link.innerHTML=entries[i].title;
		
		var content=document.createElement("p");
			content.innerHTML=entries[i].contentSnippet.replace("Continue reading &#8594;", "");
			content.className="excerpt";
			
		listItem.appendChild(link);
		listItem.appendChild(content);
		
		mainList.appendChild(listItem);
	}

	container.appendChild(mainList);	 
}

function grss(url, num, id) {
	document.write("<script type='text/javascript' src='https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num="+ num +"&q="+ url +"&callback=showFeed&context="+ id +"'></script>");
}

// Site Wide Popups (Resizing Styler)
var popupEles=new Array(), closeB_leftBy={"rightORleft": 0, "center": 0,};

function popupEle(popupName) {
	findingPopup: for (ele=0; ele<popupEles.length; ele++) {
		if (popupEles[ele].getAttribute("name")==popupName) {return popupEles[ele]; break findingPopup;}
	}
}

function closePopup(popupName) {
	rClass(doc_body, [("viewingPopup_"+ popupName), "viewingPopup"]);
	rClass(popupEle(popupName), ["active", "abs"]);
}

function popupStyler(popupName) {
	var popupELE=popupEle(popupName);
	
	if (! hClass(doc_body, [("viewingPopup_"+ popupName), "viewingPopup"])) {
		aClass(doc_body, ["viewingPopup", ("viewingPopup_"+ popupName)]);
		aClass(popupELE, "active");
	}
	
	if (window.width > fullvWidth) {
		addSheet("#"+ popupName +" .popupLayer{left: "+ ((window.width-document.getElementById("THE_"+ popupName).offsetWidth)/2) +"px;}");
 			
		if ((document.getElementById("THE_"+ popupName).parentNode.offsetTop + document.getElementById("THE_"+ popupName).offsetHeight) > window.height) {
			aClass(popupELE, "abs"); window.scrollTo(0, 1);
		}
		else {
			rClass(popupELE, "abs");
		}
	}
}



