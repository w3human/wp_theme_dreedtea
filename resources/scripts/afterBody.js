// Check Link URL To Current URL
if (! hClass(doc_body, "dontCheckLinks")) {
	for (a=0; a<document.links.length; a++) {
		var link=document.links[a];
		
		if (link.href==location.href || link.href==location.href+"/") {
			aClass(link, "onIt"); link.removeAttribute("href");
		}
	}
}

/* applying .longerThanWin */ 
tClass(doc_body, "longerThanWin", (document.getElementById("everything").offsetHeight>window.height));

addEvent(window, "resize", function() {
	tClass(doc_body, "longerThanWin", (document.getElementById("everything").offsetHeight>window.height));
});

/* Giving Sub-Menu  */
if (ifQselector) {
	for (menuI=0; menuI<document.querySelectorAll("#menu nav .link nav").length; menuI++) {
		aClass(document.querySelectorAll("#menu nav .link nav")[menuI].parentNode, "hasSub");
	}
}
else if (jQuery) {
	$("#menu nav .link nav").each(function(index) {$(this).parent().addClass("hasSub");});
}

// Site Wide Popups (Applying)
var popupEle_QUERY=((ifQselector)?document.querySelectorAll(".popup_con"):document.getElementsByTagName("div"));

for (i=0; i<popupEle_QUERY.length; i++) {
	if (hClass(popupEle_QUERY[i], "popup_con")) {
		popupEles.splice(popupEles.length, 0, document.getElementById(popupEle_QUERY[i].getAttribute("name")));
		
		for (c=0; c<popupEle_QUERY[i].childNodes.length; c++) {
			var childNode=popupEle_QUERY[i].childNodes[c];
			
			if (hClass(childNode, "colorLayer")) {
				childNode.onclick=function() {
					document.getElementById("CLOSE_"+this.id.replace("CL_", "")).click();
				};
				
				break;
			}
		}
	}
}

if (onMobile==false) {
	addEvent(window, "resize", function() {
		findingPopup: for (i=0; i<popupEles.length; i++) {
			if (hClass(popupEles[i], "active")) {
				popupStyler(popupEles[i].getAttribute("name"));
	
				break findingPopup;
			}
		}
	});
	
	addEvent(window, "scroll", function() {
		if (window.width >= fullvWidth) {
			findingPopup: for (i=0; i<popupEles.length; i++) {
				if (hClass(popupEles[i], "active") && hClass(popupEles[i], "abs")) {
					var magicNum=((document.getElementById("THE_"+popupEles[i].getAttribute("name")).parentNode.offsetTop+document.getElementById("THE_"+popupEles[i].getAttribute("name")).parentNode.offsetHeight)-window.height)+10;
					
					if (window.scrollY>magicNum) {window.scrollTo(window.scrollX, magicNum);}
					break findingPopup;
				}
			}
		}
	});
	
	// Closing Popups With [ESC] KEY
	function esc_key() {
		findingPopup: for (elez=0; elez<popupEles.length; elez++) {
			if (hClass(popupEles[elez], "active")) {
				document.getElementById("CLOSE_"+ popupEles[elez].getAttribute("name")).click();
				break findingPopup;
			}
		}
	}

	add_keydown(27, esc_key);
}

addEvent(window, "resize", function() {
	if (window.width < 1000) {
		if (($("#top-nav").width()+$("#searchform").width()) > $("#everything").width()) {
			aClass(document.getElementById("col_two_top"), "toLong");
		}
		else {
			rClass(document.getElementById("col_two_top"), "toLong");
		}
	}
});

