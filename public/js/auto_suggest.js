// Autosuggestion Form Element Client Side
// 
// Copyright and source: http://www.webreference.com/programming/javascript/ncz/
// 
// This code is taken pretty much verbatim from Nicholas's series of articles
// and so many thanks to Nicholas C. Zakas for an excellent tutorial!

//
// AutoSuggest Control Class 
//
// constructor
function AutoSuggestControl(oTextbox, oProvider) {
	this.cur = -1;
	this.layer = null;
	this.provider = oProvider;
	this.textbox = oTextbox;
	this.init();
};

// initialise this object and assign it to the textbox
// that we are going to put the information into.
AutoSuggestControl.prototype.init = function () {
	var oThis = this;
	this.textbox.onkeyup = function (oEvent) {
		if (!oEvent) oEvent = window.event;
		oThis.handleKeyUp(oEvent);
	};
	this.textbox.onkeydown = function (oEvent) {
		if (!oEvent) oEvent = window.event;
		oThis.handleKeyDown(oEvent);
	};
	this.textbox.onblur = function () {
		oThis.hideSuggestions();
	};
	this.createDropDown();
};
	
// select the range of added information in the text box
AutoSuggestControl.prototype.selectRange = function (iStart, iLength) {
	if (this.textbox.createTextRange) { // internet explorer
		var oRange = this.textbox.createTextRange();
		oRange.moveStart("character", iStart);
		oRange.moveEnd("character", iLength - this.textbox.value.length);
		oRange.select();
	} else if (this.textbox.setSelectionRange) { // mozilla
		this.textbox.setSelectionRange(iStart, iLength);
	}
	this.textbox.focus();
};

// add suggestions in only if selectRange is supported by the browser
// by typing ahead the first of the suggestions and changing it as
// they type in more of the possibility
AutoSuggestControl.prototype.typeAhead = function (sSuggestion) {
	if (this.textbox.createTextRange || this.textbox.setSelectionRange) {
		var iLen = this.textbox.value.length;
		this.textbox.value = sSuggestion;
		this.selectRange(iLen, sSuggestion.length);
	}
};

// the method which triggers the type ahead and hence selectRange
// methods - this is called by the provider with the returned suggestions
AutoSuggestControl.prototype.autosuggest = function (aSuggestions, bTypeAhead) {
	if (aSuggestions.length > 0) {
		if (bTypeAhead) {
				this.typeAhead(aSuggestions[0]);
		}
		this.showSuggestions(aSuggestions);
	} else {
		this.hideSuggestions();
	}
};
// handling key presses on the textbox this is attached to
// make sure they are within the range of normal characters
// (assuming that people are typing in English only
AutoSuggestControl.prototype.handleKeyUp = function (oEvent) {
	var iKeyCode = oEvent.keyCode;
	if (iKeyCode == 8 || iKeyCode == 46)
		this.provider.requestSuggestions(this, false);
	if (iKeyCode < 32 || (iKeyCode >= 33 && iKeyCode <= 46) || (iKeyCode >= 112 && iKeyCode <= 123)) {
		//ignore
	} else {
		this.provider.requestSuggestions(this);
	}
};
		
// hide the suggestions dropdown box
AutoSuggestControl.prototype.hideSuggestions = function () {
	this.layer.style.visibility = "hidden";
};

// select the suggestion they have highlighted
AutoSuggestControl.prototype.highlightSuggestion = function (oSuggestionNode) {
	for (var i=0; i < this.layer.childNodes.length; i++) {
		var oNode = this.layer.childNodes[i];
		if (oNode == oSuggestionNode) {
			oNode.className = "current"
		} else if (oNode.className == "current") {
			oNode.className = "";
		}
	}
};

// create the dropdown suggest box below the textbox
AutoSuggestControl.prototype.createDropDown = function () {
	this.layer = document.createElement("div");
	this.layer.className = "suggestions";
	this.layer.style.visibility = "hidden";
	this.layer.style.width = this.textbox.offsetWidth;
	document.body.appendChild(this.layer);
	
	// now to assign the event handlers (keyboard presses are handled separately)
	var oThis = this;
	this.layer.onmousedown = this.layer.onmouseup =
	this.layer.onmouseover = function (oEvent) {
		oEvent = oEvent || window.event;
		oTarget = oEvent.target || oEvent.srcElement;
		if (oEvent.type == "mousedown") {
				oThis.textbox.value = oTarget.firstChild.nodeValue;
				oThis.hideSuggestions();
		} else if (oEvent.type == "mouseover") {
				oThis.highlightSuggestion(oTarget);
		} else {
				oThis.textbox.focus();
		}
	};
};

// find out where the left hand side of the element is
AutoSuggestControl.prototype.getLeft = function () {
	var oNode = this.textbox;
	var iLeft = 0;
	while(oNode.tagName != "BODY") {
		iLeft += oNode.offsetLeft;
		oNode = oNode.offsetParent;
	}
	return iLeft;
};

// find out where the top of the element is
AutoSuggestControl.prototype.getTop = function () {
	var oNode = this.textbox;
	var iTop = 0;
	while(oNode.tagName != "BODY") {
		iTop += oNode.offsetTop;
		oNode = oNode.offsetParent;
	}
	return iTop;
};

// populate the dropdown box with the suggestions
AutoSuggestControl.prototype.showSuggestions = function (aSuggestions) {
	var oDiv = null;
	this.layer.innerHTML = "";
	for (var i=0; i < aSuggestions.length; i++) {
		oDiv = document.createElement("div");
		oDiv.appendChild(document.createTextNode(aSuggestions[i]));
		this.layer.appendChild(oDiv);
	}
	this.layer.style.left = this.getLeft() + "px";
	this.layer.style.top = (this.getTop()+this.textbox.offsetHeight) + "px";
	this.layer.style.width = this.textbox.offsetWidth + "px";
	this.layer.style.visibility = "visible";
};

// highlight the next suggestion
AutoSuggestControl.prototype.nextSuggestion = function () {
	var cSuggestionNodes = this.layer.childNodes;
	if (cSuggestionNodes.length > 0 && this.cur < cSuggestionNodes.length-1) {
		var oNode = cSuggestionNodes[++this.cur];
		this.highlightSuggestion(oNode);
		this.textbox.value = oNode.firstChild.nodeValue;
	}
};

// highlight the previous suggestion
AutoSuggestControl.prototype.previousSuggestion = function () {
	var cSuggestionNodes = this.layer.childNodes;
	if (cSuggestionNodes.length > 0 && this.cur > 0) {
		var oNode = cSuggestionNodes[--this.cur];
		this.highlightSuggestion(oNode);
		this.textbox.value = oNode.firstChild.nodeValue;
	}
};

// handle when a user pushes a key down for up, down and enter buttons
AutoSuggestControl.prototype.handleKeyDown = function (oEvent) {
		switch(oEvent.keyCode) {
				case 38: //up arrow
						this.previousSuggestion();
						break;
				case 40: //down arrow
						this.nextSuggestion();
						break;
				case 13: //enter
						this.hideSuggestions();
						break;
		}
};


//
// Suggestion Provider Class
//

// dummy suggestions array
/*
function StateSuggestions() {
	this.states = [
		"Alabama", "Alaska", "Arizona", "Arkansas",
		"California", "Colorado", "Connecticut",
		"Delaware", "Florida", "Georgia", "Hawaii",
		"Idaho", "Illinois", "Indiana", "Iowa",
		"Kansas", "Kentucky", "Louisiana",
		"Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota",
		"Mississippi", "Missouri", "Montana",
		"Nebraska", "Nevada", "New Hampshire", "New Mexico", "New York",
		"North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon",
		"Pennsylvania", "Rhode Island", "South Carolina", "South Dakota",
		"Tennessee", "Texas", "Utah", "Vermont", "Virginia",
		"Washington", "West Virginia", "Wisconsin", "Wyoming"
	];
};
StateSuggestions.prototype.requestSuggestions = function (oAutoSuggestControl, bTypeAhead) {
	var aSuggestions = [];
	var sTextboxValue = oAutoSuggestControl.textbox.value;
	if (sTextboxValue.length > 0) {
		var sTextboxValueLC = sTextboxValue.toLowerCase();
		for (var i=0; i < this.states.length; i++) {
			var sStateLC = this.states[i].toLowerCase();
			if (sStateLC.indexOf(sTextboxValueLC) == 0) {
					aSuggestions.push(sTextboxValue + this.states[i].substring(sTextboxValue.length));
			}
		}
		oAutoSuggestControl.autosuggest(aSuggestions, bTypeAhead);
	}
};
*/
function SuggestionProvider() {
    this.http = zXmlHttp.createRequest();
}

SuggestionProvider.prototype.requestSuggestions = function (oAutoSuggestControl,bTypeAhead) {
var oHttp = this.http;
//cancel any active requests
if (oHttp.readyState != 0) {
oHttp.abort();
}

//define the data
var oData = {
requesting: "StatesAndProvinces",
text: oAutoSuggestControl.userText,
limit: 5
};

//open connection to server
oHttp.open("post", path_url+"/produto-crud/getnome", true);
oHttp.onreadystatechange = function () {
if (oHttp.readyState == 4) {
alert(oHttp.responseText);	
//evaluate the returned text JavaScript (an array)
var aSuggestions = JSON.parse(oHttp.responseText);

//provide suggestions to the control
oAutoSuggestControl.autosuggest(aSuggestions, bTypeAhead);
}
};

//send the request
oHttp.send(JSON.stringify(oData));

};