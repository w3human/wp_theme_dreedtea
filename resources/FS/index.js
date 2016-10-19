/* ------ PART 1: Functions And Varibles ------ */
var vowels=new RegExp("(a|e|i|o|u)", "i"), theMonths={"full": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"], "shorten": new Array(),}, forIn_months={"shorten": {}, "full": {},};

for (a in theMonths["full"]) {theMonths["shorten"].splice(a, 0, theMonths["full"][a].slice(0, 3)); forIn_months["shorten"][theMonths.shorten[a]]=(Number(a)+1); forIn_months["full"][theMonths.full[a]]=(Number(a)+1);}

var AMs=/(AM|am|aM|Am)/g, PMs=/(PM|pm|pM|Pm)/g, PAMs=/(AM|am|aM|Am|PM|pm|pM|Pm)/g;

var mmAttr=new Array("min", "max");

var regExp_lib={
	"email": new RegExp("^[_A-Za-z0-9-]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$"),	
	
	"date": new RegExp("^((("+ theMonths.shorten.join("|") + theMonths.full.join("|") +")( )\\d{1,2}(, )\\d{4})|((\\d{4}|\\d{1,2})(\\/|-)\\d{1,2}(\\/|-)(\\d{4}|\\d{1,2})))$"),
		
	"url": new RegExp("^(\www.|(http|https|ftp)\://)[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~])*[^\.\,\)\(\s]$"),
		
	"time": /^((([0]?[1-9]|1[0-2])(:|\.)[0-5][0-9]((:|\.)[0-5][0-9])?( )?(AM|am|aM|Am|PM|pm|pM|Pm))|(([0]?[0-9]|1[0-9]|2[0-3])(:|\.)[0-5][0-9]((:|\.)[0-5][0-9])?))$/,
	
	"month":  new RegExp("((("+ theMonths.shorten.join("|") + theMonths.full.join("|") +")(( \\d{4})|))|(\\d{4}-\\d{2}))"),
	
	"months":  new RegExp("^("+ theMonths.shorten.join("|") + theMonths.full.join("|") +")$"),
};

var form={}, inputE={}, inputProps=["type", "name", "required", "require", "min", "max", "step"];
var allOfThese=new Array(""), checkedInputs=new Array(), inputApplyLog=new Array();

function formNum(formName) {find_formNum: for (v=0; v<document.forms.length; v++) {if (document.forms[v].name==formName) {return v; break find_formNum;}}}

function inputProp(prop) {
	if (prop!=undefined) {
		if (prop=="typeInWords" && inputE.hasAttribute("type")) {
			return ("a"+((vowels.test(inputProp("type")[0]))?"n":""))+" "+inputProp("type");
		}
		else if (inputE.hasAttribute(prop)) {
			if (prop=="required") {return inputE.hasAttribute(prop);}
			else {return inputE.getAttribute(prop);}
		}
		else {
			return inputE.hasAttribute(prop);
		}
	}
}

function allOptions(inputObj) {
	if (FS["inTypes"].choices()) {
		if (ifQselector) {
			var x=document.querySelectorAll("form[name='"+ form.name +"'] "+((inputObj.tagName=="SELECT")?"select[name='"+ inputObj.name +"'] option":"input[type='"+ inputObj.type +"'][name='"+ inputObj.name +"']"));
		}
		else {
			var inputs=((inputE.tagName=="SELECT")?inputObj:document.getElementsByName(inputObj.name)), x=new Array();
		
			for (z=0; z<inputs.length; z++) {if ((inputObj.tagName=="SELECT" && inputs[z].tagName=="OPTION") || (inputs[z].getAttribute("type")==inputObj.getAttribute("type"))) {x.splice(x.length, 0, inputs[z]);}}
		}
		
		FS.log("allOptions run for ([inputPath])");
		
		return x;
	}
	else {
		// Logbook = "[inputPath] has no options"
		return false;
	}
}

function convertTime(X) {if (regExp_lib["time"].test(X)) {return Number(Number(X.arrayReplace([PAMs, / /g, /(:)/g, "."], ["", "", ".", ""]))+((PMs.test(X))?12:0));} else {FS.log(X +" is not a time!"); return null;}}

function convertDate(theDate) {theDate=theDate.replace(/(-)/g, "/"); var dX=new Date(((regExp_lib["months"]).test(theDate))?forIn_months[((theDate in forIn_months.full)?"full":"shorten")][theDate].toString():theDate); if (! (/\d{4}/g).test(theDate)) {dX.setFullYear(cDate.getFullYear());} return (dX);}

var FS={
	"inTypes": {
		"all": ["button", "checkbox", "color", "date", "datetime", "datetime-local", "email", "file", "hidden", "image", "month", "number", "password", "radio", "range", "reset", "search", "submit", "tel", "text", "time", "url", "week"],
		
		"h5": ["search", "tel", "url", "email", "date", "month", "week", "time", "number", "range", "color"],
		"text": ["color", "date", "datetime", "datetime-local", "email", "month", "number", "password", "search", "tel", "text", "time", "url", "week"],
		"checkedProp": ["checkbox", "radio"],
		"choices": function() {return (inputE.tagName=="SELECT" || FS["input"].test("type", FS["inTypes"].checkedProp));}, 
		
		"dc": ["button", "datetime", "datetime-local", "color", "file", "hidden", "image" , "reset", "submit", "week"],
	},
	
	"logBook": new Array(),
	
	"log": function(to, what) {
		if (to!=undefined) {
			if (to=="applyInput") {inputApplyLog.splice(inputApplyLog.length, 0, what);}
			else {
				FS["logBook"].splice(FS["logBook"].length, 0, FS["input"].transformString(to));
				console.log("FS-LOG: "+ FS["input"].transformString(to));
			}
		}
	},

	"input": {
		"errorNames": new Array("invalid", "noValue", "noYear", "notAll", "notA", "outOfRange", "toLow", "toHigh"),
		"defaultOption": {"value": "DEFAULT", "text": "-- DEFAULT --",},

		"test": function(attr, value) {return (inputE.hasAttribute(attr) && (new RegExp("("+ ((Array.isArray(value))?value.join("|"):value) +")", "g")).test(inputE.getAttribute(attr)));},
		
		"goodToGo": function() {return ((inputE.tagName=="OUTPUT" || hClass(inputE, "dontCheck") || FS["input"].test("type", FS["inTypes"].dc) || FS["input"].test("name", checkedInputs))?false:true);},
		
		"transformString": function(stringX) {
			stringX=stringX.replace((/(\[type\])/g), inputE.getAttribute("type"));
			stringX=stringX.replace((/(\[min\])/g), ((inputE.getAttribute("min"))?inputE.getAttribute("min"):""));
			stringX=stringX.replace((/(\[max\])/g), ((inputE.getAttribute("max"))?inputE.getAttribute("max"):""));
			
			stringX=stringX.replace((/(\[inputPath\])/g), "form("+ inputE.form.name +").input("+ inputE.name +")");
			
			return stringX;
		},
		
		"addAfter": function(what) {			
			if (FS["input"].test("type", FS["inTypes"].checkedProp)) {
				inputE.parentNode.insertBefore(what, allOfThese[(allOfThese.length-1)].nextSibling.nextSibling);
			}
			else {
				inputE.parentNode.insertBefore(what, inputE.nextSibling);
			}
		},
		
		//~ "addEvent": function(event, func) {
			//~ if (event!=undefined && typeof(func)==="function") {
				//~ if (FS["input"].test("type", FS["inTypes"].checkedProp)) {
					//~ allOfThese=allOptions(inputE);
					//~ 
					//~ for (opt=0; opt<allOfThese.length; opt++) {
						//~ addEvent(allOfThese[opt], event, func);
					//~ }
				//~ }
				//~ else {
					//~ addEvent(inputE, event, func);
				//~ }
			//~ }
		//~ }, 
		
		"things": {
			"list": new Array(),
			"add": function(func) {FS["input"]["things"]["list"].splice(FS["input"]["things"]["list"], 0, func);},
			"run": function() {for (P in FS["input"]["things"].list) {FS["input"]["things"].list[P]();}},
		},
		
		"setInfo": function() {
			if (ifQselector) {return document.querySelector("form[name='"+ form.name +"'] input[type='hidden'][name='"+ inputE.name +"']");}
			else {
				find_setInfo: for (si=0; si<document.getElementsByName(inputE.name).length; si++) {
					if (document.getElementsByName(inputE.name)[si].form.name==inputE.form.name && document.getElementsByName(inputE.name)[si].type=="hidden") {
						return document.getElementsByName(inputE.name)[si]; break find_setInfo;
					}
					else if (si==(document.getElementsByName(inputE.name).length-1)) {
						return false;
					}
				}
			}
		},
		
		"values": function() {
			if ((allOptions(inputE)!=false && FS["input"].test("type", "checkbox")) || (inputE.tagName=="SELECT" && inputE.hasAttribute("multiple"))) {
				var OPTIONS=((inputE.tagName=="SELECT")?inputE:allOptions(inputE)), VALUE=new Array();
				for (z=0; z<OPTIONS.length; z++) {if (OPTIONS[z].selected || OPTIONS[z].checked) {VALUE.splice(VALUE.length, 0, OPTIONS[z].value);}}
				return VALUE;
			}
			else {return false;}
		},
		
		"hasBeenChecked": function() {
			var allOfThese=allOptions(inputE);
			
			checkChecked: for (v=0; v<allOfThese.length; v++) {
				if (allOfThese[v].checked==true) {return true; break checkChecked;}
				else if ((allOfThese.length-1)==v) {return false;}
 			}
		},
		
		"isInvalid": function() {
			rClass(inputE, FS["input"].errorNames);
			
			if (
				(inputE.hasAttribute("validate") && FS["validate"].attr())
				||
				(FS["input"].test("type", "radio") && FS["validate"].radio())
				||
				(FS["validate"].anInput())
			) {
				aClass(inputE, "invalid"); return true;
			}
			else {
				return false;
			}
		},
		
		"isValid": function() {return (! FS["input"].isInvalid());},
		
		"theError": function() {
			var allErrors=new Array("notAll", "notA", "noValue", "noYear", "toLow", "toHigh");
			
			for (G=0; G<allErrors.length; G++) {
				if (hClass(inputE, allErrors[G])) {return allErrors[G];}
				else if ((allErrors.length-1)==G) {return "noError";}
			}
		},
	},

	"outputs": {
		"for": function() {
			if (ifQselector) {var x=document.querySelectorAll("output[for='"+ inputE.name +"']");}
			else {var x=new Array(); for (Z=0; Z<document.getElementsByTagName("output").length; Z++) {if (inputE.name==document.getElementsByTagName("output")[Z].getAttribute("for")) {x.splice(x.length, 0, document.getElementsByTagName("output")[Z]);}}}
			
			return x;
		},
		
		"set": function() {
			var OUTs=FS["outputs"].for(), value=((FS["input"].values()!=false)?FS["input"].values().join(", "):inputE.value);
			for (U in OUTs) {OUTs[U].innerHTML=value;}
		},
		
		"clear": function() {var OUTs=FS["outputs"].for(); for (U in OUTs) {OUTs[U].innerHTML="";}},
	},
	
	"validate": {
		"attr": function() {eval(""+ inputE.getAttribute("validate") +"");},
		
		"minMax": function (compare, min, max) {
			compare=Number(compare);
			
			if (min && max && Number(min)>Number(max)) {
				FS.log("[inputPath] attr(min) is higher then attr(max)"); return true;
			}
			else {				
				for (i in mmAttr) {
					if ((inputProp(mmAttr[i])!=false || FS["input"].test("type", "checkbox")) && Number(eval(mmAttr[i])).toString()!="NaN") {
						var symbol=(mmAttr[i]=="min")?"<":">"; eval(mmAttr[i]+"=Number("+ mmAttr[i] +");");
					
						if (eval(compare + symbol + mmAttr[i])) {
							aClass(inputE, ["outOfRange", "to"+((mmAttr[i]=="min")?"Low":"High")]); return true;
						}
					}
				}
			}
		},
		
		"radio": function() {
			if (inputProp("required") && ! FS["input"].hasBeenChecked()) {
				aClass(inputE, "noValue"); return true;
			}
		},
		
		"formatted": {
			"types": ["number", "email", "url", "time", "date", "month"],
			
			"check": function() {
				switch(inputProp("type")) {
					case "number": 
						if (Number(inputE.value).toString()=="NaN") {
							aClass(inputE, "notA"); return true;
						}
						else if (FS["validate"].minMax(Number(inputE.value), ((inputProp("min")!=false)?inputProp("min"):null), ((inputProp("max")!=false)?inputProp("max"):null))) {
							return true;
						}						
						
						break;
						
					case "time":
						if (regExp_lib["time"].test(inputE.value)) {
							if (FS["validate"].minMax(convertTime(inputE.value), ((inputProp("min")!=false)?convertTime(inputProp("min")):null), ((inputProp("max")!=false)?convertTime(inputProp("max")):null))) {
								return true;
							}
						}
						else {
							aClass(inputE, "notA"); return true;
						}
						
						break;
					
					case "date":
						if (regExp_lib["date"].test(inputE.value)) {
							var valueAsDate=convertDate(inputE.value);
							
							if (valueAsDate!="Invalid Date") {
								if (FS["validate"].minMax(valueAsDate.getTime(), ((inputProp("min")!=false)?convertDate(inputProp("min")).getTime():null), ((inputProp("max")!=false)?convertDate(inputProp("max")).getTime():null))) {
									return true;
								}
							}
							else {
								aClass(inputE, "notA"); return true;
							}
						}
						else {
							aClass(inputE, "notA"); return true;
						}
						
						break;
					
					case "month":
						if (regExp_lib["month"].test(inputE.value)) {
							var valueAsDate=convertDate(inputE.value);
							
							if (
								FS["validate"].minMax(valueAsDate.getTime(), ((inputProp("min")!=false)?convertDate(inputProp("min")).getTime():null), ((inputProp("max")!=false)?convertDate(inputProp("max")).getTime():null))
							) {
								return true;
							}
						} 
						else {
							aClass(inputE, "notA"); return true;
						}
						
						break;
							
					default:
						if (! regExp_lib[inputProp("type")].test(inputE.value)) {
							aClass(inputE, "notA"); return true;
						}
						
						break;
				}
			},
		},
		
		"anInput": function() {
			if (inputE.tagName=="SELECT") {
				if ((inputProp("required") && (inputE.value==FS["selector"]["requiredDopt"]["value"] || inputE.value==""))) {
					aClass(inputE, "noValue"); return true;
				}
				else if (inputE.hasAttribute("multiple") && FS["input"].values().length>0) {
					if (FS["validate"].minMax(FS["input"].values().length, ((inputProp("min")!=false)?inputProp("min"):null), ((inputProp("max")!=false)?inputProp("max"):null))) {
						return true;
					}
				}
			}
			else if (inputProp("type")=="checkbox") {
				var allOfThese=allOptions(inputE);
				
				if (allOfThese.length==1) {
					if (inputProp("required") && ! inputE.checked) {
						aClass(inputE, "noValue"); return true;
					}
				}
				else if (allOfThese.length>1) {
					var numOfChecked=FS["input"].values().length;
					
					if (FS["input"].setInfo()!=false) {
						var setInfo=FS["input"].setInfo();
						
						if (setInfo.hasAttribute("require") && setInfo.getAttribute("require")=="all" && numOfChecked!=allOfThese.length) {
							aClass(inputE, "notAll"); return true;
						}
						else if (setInfo.hasAttribute("required") && numOfChecked<1) {
							aClass(inputE, "noValue"); return true;
						}
						else if (numOfChecked>=1 && FS["validate"].minMax(FS["input"].values().length, ((setInfo.hasAttribute("min"))?setInfo.getAttribute("min"):null), ((setInfo.hasAttribute("max"))?setInfo.getAttribute("max"):null))) {
							return true;
						}					
					}
				}
			}
			else if (FS["input"].test("type", "range")) {
				if (inputProp("required") && inputE.hasAttribute("defaultValue") && inputE.getAttribute("defaultValue")==inputE.value) {aClass(inputE, "noValue"); return true;}
			}
			else {
				if (inputProp("required") && inputE.value=="") {aClass(inputE, "noValue"); return true;}
				else if (inputE.value!="") {
					if (FS["input"].test("type", FS["validate"]["formatted"].types)) {
						return FS["validate"]["formatted"].check();
					}
					else {
						if (inputE.hasAttribute("pattern") && ! (new RegExp(inputE.getAttribute("pattern"))).test(inputE.value)) {
							return true;
						}
						else if (FS["validate"].minMax(Number(inputE.value.length), ((inputProp("min")!=false)?inputProp("min"):null), ((inputProp("max")!=false)?inputProp("max"):null))) {
							return true;
						}
					}
				}
			}
		},
				
		"form": function(formName) {
			form=document.forms[formNum(formName)];
			
			FS.log("FORM("+ form.name +") Validation Log:");
			
			for (round=1; round<=2; round++) {
				checkedInputs=new Array("~");
				
				for (input=0; input<form.length; input++) {
					inputE=form[input];
												
					if (FS["input"].goodToGo()) {
						if (FS["input"].isInvalid()) {
							if (round==1) {FS.log("- input("+ inputE.name +"): Not-Valid: "+ FS["input"].theError() +"");}
							else if (round==2) {
								if (form.hasAttribute("oninvalid") && (/(scrollToInput)/g).test(form.getAttribute("oninvalid"))) {window.scrollTo(0, inputE.scrollTop);}
																
								return false;
							}
						}
						else {
							if (round==1) {FS.log("- input("+ inputE.name +"): Valid");}
						}
						
						FS["plugins"].run("input", "validating");
						
						if (FS["input"].test("type", FS["inTypes"].checkedProp)) {checkedInputs.splice(checkedInputs.length, 0, inputE.name);}
					}
				}
			}
		},
	},
	
	"plugins": {
		"form": {"validating": new Array(), "apply": new Array(),}, 
		"input": {"validating": new Array(), "apply": new Array(),},
		
		"add": function(what, fi, to) {FS["plugins"][fi][to].splice(FS["plugins"][fi][to].length, 0, what);},
		"run": function(fi, what) {for (Z=0; Z<FS["plugins"][fi][what].length; Z++) {FS["plugins"][fi][what][Z]();}},
	},
};

FS.apply=function() {
	for (aForm=0; aForm<document.forms.length; aForm++) {
		form=document.forms[aForm]; checkedInputs=new Array("~");

		if (hClass(form, "validateThis")) {
			form.setAttribute("onsubmit", 
				((form.hasAttribute("onsubmit"))?form.getAttribute("onsubmit"):"") +" return FS.validate.form('"+ form.getAttribute("name") +"');"
			); form.setAttribute("novalidate", "");
		
			FS["plugins"].run("form", "apply");
		}
			
		for (input=0; input<form.length; input++) {
			inputE=form[input]; inputApplyLog=new Array();
			
			if (FS["input"].goodToGo()) {
				// Input Helps
				tClass(inputE, "textF", FS["input"].test("type", FS["inTypes"].text));
				
				if (hClass(form, "validateThis")) {
					if (inputE.tagName=="SELECT") {
						if (! inputE.hasAttribute("multiple") && inputE[inputE.selectedIndex].defaultSelected && inputE[inputE.selectedIndex].value==FS["input"]["defaultOption"]["value"]) {
							inputE[inputE.selectedIndex].innerHTML=FS["input"]["defaultOption"]["text"];
						}
						else if (! inputE.hasAttribute("multiple")) {
							var defaultOption=document.createElement("option");
								defaultOption.value=FS["input"]["defaultOption"]["value"];
								defaultOption.innerHTML=FS["input"]["defaultOption"]["text"];
								defaultOption.setAttribute("selected", "selected");
						
						inputE.insertBefore(defaultOption, inputE.firstChild); inputE.value=FS["input"]["defaultOption"]["text"];
						}	
					}
					else if (FS["input"].test("type", FS["inTypes"].checkedProp)) {
						allOfThese=allOptions(inputE);
					}
					else if (FS["input"].test("type", "range")) {
						inputE.setAttribute("defaultValue", inputE.value);
					}
					else if (FS["input"].test("type", ["time", "date", "month"])) {
						for (c in mmAttr) {
							if (inputProp(mmAttr[c])!=false && ! regExp_lib[(inputProp("type"))].test(inputProp(mmAttr[c]))) {
								inputE.removeAttribute(mmAttr[c]);
								FS["log"]("applyInput", "Removed: attr("+ mmAttr +"), because it was not "+ inputProp("typeInWords") +")");
							}
						}
					}
					else if (FS["input"].test("type", FS["inTypes"].text)) {
						for (c in mmAttr) {
							if (inputProp(mmAttr[c])!=false && Number(inputProp(mmAttr[c])).toString()=="NaN") {
								inputE.removeAttribute(mmAttr[c]);
								FS["log"]("applyInput", "Removed: attr("+ mmAttr +"), because it was not a number");
							}
						}
					}
					
					// Inputs To Outputs
					if (FS["outputs"]["for"]().length!=0) {					
						if (FS["input"].test("type", FS["inTypes"].checkedProp)) {
							if (allOfThese.length>1) {for (c in allOfThese) {addEvent(allOfThese[c], "change", function() {inputE=this; FS["outputs"]["set"]();});}}
							else {addEvent(inputE, "change", function() {inputE=this; FS["outputs"][((this.checked)?"set":"clear")]();});}
						}
						else {addEvent(inputE, ((inputE.tagName=="SELECT")?"change":"input"), function() {inputE=this; FS["outputs"][((hClass(this, "invalid"))?"clear":"set")]();});}
					}
					
					// Plugins And After...			
					if ((inputE.parentNode.childNodes.length==1) || (FS["input"].test("type", FS["inTypes"].checkedProp) && ! allOfThese[(allOfThese.length-1)].nextSibling)) {inputE.parentNode.appendChild(document.createElement("span"));}
					
					FS["plugins"].run("input", "apply");
					
					// Adding Input's Apply Log
					FS.log("[inputPath] "+ ((inputApplyLog.length>0)?"\ \n -":"") + inputApplyLog.join("\n -") +"");
					
					if (FS["input"].test("type", FS["inTypes"].checkedProp)) {checkedInputs.splice(checkedInputs.length, 0, inputE.name);}
				}
			}				
		}
	}
	
	FS["input"]["things"].add(FS["outputs"]["set"]);
};

