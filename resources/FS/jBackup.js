var mouseDown=new Boolean(false);

function stepThis(D) {
	if (mouseDown==true) {
		var startingValue=((inputE.hasAttribute("min"))?inputE.getAttribute("min"):0);
		
		if (inputE.value=="" || Number(inputE.value).toString()=="NaN") {
			inputE.value=startingValue;
		}
		else if (! FS["validate"].minMax(eval(Number(inputE.value) + D + ((inputProp("step")!=false)?inputProp("step"):1)), ((inputProp("min")!=false)?inputProp("min"):null), ((inputProp("max")!=false)?inputProp("max"):null))) {
			inputE.value=eval(Number(inputE.value) + D + ((inputProp("step")!=false)?inputProp("step"):1));
		}
		
		rClass(inputE, FS.inputErrorClassnames);
		
		eval("setTimeout(function() {stepThis('"+ D +"');}, 200)");
		
		FS["input"]["things"].run();
	}
}

if (jQuery!=undefined) {
	FS["plugins"].add(function() {if (FS["input"].test("type", FS["inTypes"]["h5"]) && inputProp("type")!=inputE.type) {
		aClass(inputE, "noPort"); FS["log"]("applyInput", "Is Not Support");
		
		switch(inputProp("type")) {
			case "number":
				var numCon=document.createElement("div");
					numCon.id="numCon_"+ inputE.name +"";
					numCon.className="numberCon";
				
				FS["input"].addAfter(numCon);
						
				// Up
				var up=document.createElement("a");
					up.id="up_"+ inputE.name +""; up.className="up";
					up.setAttribute("form", inputE.form.name);
					up.innerHTML="︿";
					
				numCon.appendChild(up);
				
				up.onmousedown=function() {
					mouseDown=new Boolean(true); inputE=document.forms[this.getAttribute("form")][(this.id.replace("up_", ""))];
					stepThis("+");
				};
								
				// Down
				var down=document.createElement("a");
					down.id="down_"+ inputE.name +""; down.className="down";
					down.setAttribute("form", inputE.form.name);
					down.innerHTML="﹀";
				
				numCon.appendChild(down);
				
				down.onmousedown=function() {
					mouseDown=new Boolean(true); inputE=document.forms[this.getAttribute("form")][(this.id.replace("down_", ""))];
					stepThis("-");
				};
				
				addEvent(window, "mouseup", function() {mouseDown=new Boolean(false);});

				break;
			
			case "range":
				if ($.ui.slider) {
					var input_jBackup=document.createElement("div");
						input_jBackup.id="jBackup_"+ inputE.name;
						input_jBackup.setAttribute("formname", form.getAttribute("name"));
					
					FS["input"].addAfter(input_jBackup);
					
					$("form[name='"+ form.getAttribute("name") +"'] #jBackup_"+ inputE.name +"").slider({
						"step": ((inputProp("step")!=false)?Number(inputProp("step")):1),
						"min": ((inputProp("min")!=false)?Number(inputProp("min")):0),
						"max": ((inputProp("max")!=false)?Number(inputProp("max")):100),
						
						"slide": function(event, ui) {
							inputE=document.forms[$(this).attr("formname")][$(this).attr("id").replace("jBackup_", "")];
							inputE.value=ui.value;
							FS["input"]["things"].run();
						},
					});
				}
				
				break;
			
			case "date":
				if ($.ui.datepicker) {
					$("form[name='"+ form.getAttribute("name") +"'] input[name='"+ inputE.name +"']").datepicker({
						"minDate": (((inputProp("min")!=false))?(convertDate(inputProp("min"))):null),
						"maxDate": (((inputProp("max")!=false))?(convertDate(inputProp("max"))):null),
					});
				}

				break;
		}
	}}, "input", "apply");
}

