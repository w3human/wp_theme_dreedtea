function changeSlideOnMini(eleID, slideNum) {
	if (eleID!=undefined && slideNum!=undefined && document.querySelector("#"+ eleID +".miniSlider")) {
		if (! ifQselector) {
			var slides_query=document.querySelectorAll("#"+ eleID +".miniSlider .slide");
			var selector_query=document.querySelectorAll(" #"+ eleID +".miniSlider .slidePicker a");
		}
		else {
			var slides_query=new Array();
			
			findingSlider: for (ms=0; ms<miniSlider_eleQuery.length; ms++) {
				var a_miniSlider=miniSlider_eleQuery[ms];
				
				if (a_miniSlider.getAttribute("id")==eleID) {
					for (mss=0; mss<a_miniSlider.childNodes.length; mss++) {
						var maySlide=a_miniSlider.childNodes[mss];
						
						if (hClass(maySlide, "slidePicker")) {
							var selector_query=a_miniSlider.childNodes[mss].childNodes;
						}
						else if (hClass(maySlide, "slide")) {
							slides_query.splice(slides_query.length, 0, maySlide);
						}
					}
					
					break findingSlider;
				}
			}
		}
				
		if (slideNum=="next" || slideNum=="back") {
			for (es=0; es<slides_query.length; es++) {
				if (! hClass(slides_query[es], "hideSlide")) {
					eval("var slideNum="+ ((slideNum=="next")?((es==(slides_query.length-1))?"0":"es+1"):((es==0)?(slides_query.length-1):"es-1")));
				}
			}
		}
		
		for (es=0; es<slides_query.length; es++) {
			eval(((es==slideNum)?"r":"a")+"Class(slides_query[es], 'hideSlide');");
		}		
		
		for (es=0; es<selector_query.length; es++) {
			eval(((es==slideNum)?"a":"r")+"Class(selector_query[es], 'selected');");
		}
	}
}

function miniSliderTimer(eleID, timer) {
	if (eval("miniSlider_"+ eleID +"_timer")==true) {
		setTimeout(function() {
			if (eval("miniSlider_"+ eleID +"_timer")==true) {
				changeSlideOnMini(eleID, "next");
				miniSliderTimer(eleID, timer);
			}
		}, timer);
	}
}

if (! ifQselector) {var miniSlider_eleQuery=document.querySelectorAll(".miniSlider[id]");}
else {
	var miniSlider_eleQuery=new Array();
	
	for (fe=0; fe<document.getElementsByTagName("div").length; fe++) {
		var ele=document.getElementsByTagName("div")[fe];
		
		if (ele.hasAttribute("id") && hClass(ele, "miniSlider")) {
			miniSlider_eleQuery.splice(miniSlider_eleQuery.length, 0, ele);
		}
	}
}

if (miniSlider_eleQuery.length) {

for (E=0; E<miniSlider_eleQuery.length; E++) {
	var a_miniSlider=miniSlider_eleQuery[E];
	
	if (! ifQselector) {
		var a_miniSlider_slidesQuery=document.querySelectorAll("#"+ a_miniSlider.getAttribute("id") +".miniSlider .slide");
		var has_slidePicker=new Boolean((document.querySelector("#"+ a_miniSlider.getAttribute("id") +".miniSlider .slidePicker"))?true:false);
		var slidePicker_ele=document.querySelector("#"+ a_miniSlider.getAttribute("id") +".miniSlider .slidePicker");
	}
	else {
		var a_miniSlider_slidesQuery=new Array();
		
		for (ss=0; ss<a_miniSlider.childNodes.length; ss++) {
			var maySlide=a_miniSlider.childNodes[ss];
			
			if (hClass(maySlide, "slidePicker")) {
				var has_slidePicker=new Boolean(true);
				var slidePicker_ele=maySlide;
			}
			else if (hClass(maySlide, "slide")) {
				a_miniSlider_slidesQuery.splice(a_miniSlider_slidesQuery.length, 0, maySlide);
			}
		}
	}
	
	if (has_slidePicker==true && a_miniSlider_slidesQuery.length>1) {
		for (S=0; S<a_miniSlider_slidesQuery.length; S++) {
			var slideSelector=document.createElement("a");
				slideSelector.setAttribute("eleid", a_miniSlider.getAttribute("id"));
				slideSelector.setAttribute("num", S);
				
				slideSelector.onclick=function(event) {
					if (! hClass(this, "selected")) {
						eval("miniSlider_"+ this.getAttribute("eleid") +"_timer=new Boolean(false);");
						
						changeSlideOnMini(this.getAttribute("eleid"), this.getAttribute("num"));
						
						event.preventDefault();
					}
				};
				
			slidePicker_ele.appendChild(slideSelector);
		}
		
		changeSlideOnMini(a_miniSlider.getAttribute("id"), 0);
		
		if (a_miniSlider.hasAttribute("timer")) {
			if (Number(a_miniSlider.getAttribute("timer"))>1000) {
				eval("var miniSlider_"+ a_miniSlider.getAttribute("id") +"_timer=new Boolean(true);");
				miniSliderTimer(a_miniSlider.getAttribute("id"), a_miniSlider.getAttribute("timer"));
			}
		}
	}
	
	
}
}

