//--------------------------------------------------//
function validateEmailv2(email){
    if(email.length <= 0){ return true; }
    var splitted = email.match("^(.+)@(.+)$");
    if(splitted == null) return false;
    if(splitted[1] != null ){
		var regexp_user=/^\"?[\w-_\.]*\"?$/;
		if(splitted[1].match(regexp_user) == null) return false;
    }
    if(splitted[2] != null){
		var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
		if(splitted[2].match(regexp_domain) == null){
			var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
			if(splitted[2].match(regexp_ip) == null) return false;
		}
      return true;
	}
	return false;
}

//--------------------------------------------------//
// Removes leading whitespaces
function LTrim(value){
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
}
// Removes ending whitespaces
function RTrim(value) {
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
}
// Removes leading and ending whitespaces
function trim(value) {
	return LTrim(RTrim(value));
}
//------------------------------------------------//


//-----------------------------------------------//
//Numeric Validater starts
// mredkj.com
function extractNumber(obj, decimalPlaces, allowNegative){
	var temp = obj.value;
	// avoid changing things if already formatted correctly
	var reg0Str = '[0-9]*';
	if(decimalPlaces > 0){
		reg0Str += '\\.?[0-9]{0,' + decimalPlaces + '}';}else if(decimalPlaces < 0){
		reg0Str += '\\.?[0-9]*';
	}
	reg0Str = allowNegative ? '^-?' + reg0Str : '^' + reg0Str;
	reg0Str = reg0Str + '$';
	var reg0 = new RegExp(reg0Str);
	if (reg0.test(temp)) return true;

	// first replace all non numbers
	var reg1Str = '[^0-9' + (decimalPlaces != 0 ? '.' : '') + (allowNegative ? '-' : '') + ']';
	var reg1 = new RegExp(reg1Str, 'g');
	temp = temp.replace(reg1, '');

	if(allowNegative){
		// replace extra negative
		var hasNegative = temp.length > 0 && temp.charAt(0) == '-';
		var reg2 = /-/g;
		temp = temp.replace(reg2, '');
		if (hasNegative) temp = '-' + temp;
	}
	
	if(decimalPlaces != 0){
		var reg3 = /\./g;
		var reg3Array = reg3.exec(temp);
		if (reg3Array != null) {
			// keep only first occurrence of .
			//  and the number of places specified by decimalPlaces or the entire string if decimalPlaces < 0
			var reg3Right = temp.substring(reg3Array.index + reg3Array[0].length);
			reg3Right = reg3Right.replace(reg3, '');
			reg3Right = decimalPlaces > 0 ? reg3Right.substring(0, decimalPlaces) : reg3Right;
			temp = temp.substring(0,reg3Array.index) + '.' + reg3Right;
		}
	}
	obj.value = temp;
}

function blockNonNumbers(obj, e, allowDecimal, allowNegative){
	var key;
	var isCtrl = false;
	var keychar;
	var reg;
		
	if(window.event) {
		key = e.keyCode;
		isCtrl = window.event.ctrlKey
	}
	else if(e.which) {
		key = e.which;
		isCtrl = e.ctrlKey;
	}
	
	if (isNaN(key)) return true;
	keychar = String.fromCharCode(key);
	// check for backspace or delete, or if Ctrl was pressed
	if (key == 8 || isCtrl)
	{
		return true;
	}

	reg = /\d/;
	var isFirstN = allowNegative ? keychar == '-' && obj.value.indexOf('-') == -1 : false;
	var isFirstD = allowDecimal ? keychar == '.' && obj.value.indexOf('.') == -1 : false;
	
	return isFirstN || isFirstD || reg.test(keychar);
}
//Numeric Validater ends

//-----------------------------------------------//

//-----------------------------------------------//
function ReturnDaysFromDates(FromDate, Todate){
	var StartDate = (FromDate).split("-");
	var EndDate = (Todate).split("-");
	
	t1 = StartDate[2] + "/" + StartDate[1] + "/" + StartDate[0];
    t2 = EndDate[2] + "/" + EndDate[1] + "/" + EndDate[0];
	
	//t1="10/10/2006";
    //t2="15/10/2006";

 	var one_day=1000*60*60*24;
	
	var x=t1.split("/");
	var y=t2.split("/");
		
	var date1=new Date(x[2],(x[1]-1),x[0]);
	var date2=new Date(y[2],(y[1]-1),y[0]);
	var month1=x[1]-1;
	var month2=y[1]-1;
   	_Diff=Math.ceil((date2.getTime()-date1.getTime())/(one_day));
	if(_Diff < 0){
		_Diff = 0;
	}
	return _Diff;
}

//--------------Date Validator-------------------------------//
/**
* Declaring valid date character, minimum year and maximum year
*/
var dtCh= "-";
var minYear=1900;
var maxYear=2100;

function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}

function isDate(dtStrId){
	dtStr = document.getElementById(dtStrId).value
	if(dtStr != ""){
		var daysInMonth = DaysArray(12)
		var pos1=dtStr.indexOf(dtCh)
		var pos2=dtStr.indexOf(dtCh,pos1+1)
		var strDay=dtStr.substring(0,pos1)
		var strMonth=dtStr.substring(pos1+1,pos2)
		var strYear=dtStr.substring(pos2+1)
		strYr=strYear
		if(strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
		if(strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
		for(var i = 1; i <= 3; i++){
			if(strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
		}
		month=parseInt(strMonth)
		day=parseInt(strDay)
		year=parseInt(strYr)
		if(pos1==-1 || pos2==-1){
			alert("The date format should be : dd-mm-yyyy")
			getfocus(dtStrId)
			return false
		}
		if(strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
			alert("Please enter a valid day")
			getfocus(dtStrId)
			return false
		}
		if(strMonth.length<1 || month<1 || month>12){
			alert("Please enter a valid month")
			getfocus(dtStrId)
			return false
		}
		if(strYear.length != 4 || year==0 || year<minYear || year>maxYear){
			alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
			getfocus(dtStrId)
			return false
		}
		if(dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
			alert("Please enter a valid date")
			getfocus(dtStrId)
			return false
		}
		return true;
	}
}

function DateDifferent(FromDate, Todate){
	//Includes both the dates
	t1 = FromDate.replace(/-/g,"/");
    t2 = Todate.replace(/-/g,"/");
	//t1="01/10/2006";
    //t2="10/10/2006";
 	
	var one_day=1000*60*60*24;
	var x=t1.split("/");
	var y=t2.split("/");
	var date1=new Date(x[2],(x[1]-1),x[0]);
	var date2=new Date(y[2],(y[1]-1),y[0]);
	var month1=x[1]-1;
	var month2=y[1]-1;
   	DtDiff=Math.ceil((date2.getTime()-date1.getTime())/(one_day));
	if(DtDiff < 0){DtDiff = 0;}
	return DtDiff + 1;
}
//---------------------------------------------------------//


//-----------------------------------------------//


//-----------------------------------------------------//
function toMinuteAndSecond( x ) {
	return Math.floor(x/60) + ":" + x%60;
}

function setTimer(remain, Display){
	(function countdown(){
		document.getElementById(Display).value = toMinuteAndSecond(remain);
   	(remain -= 1) >= 0 && setTimeout(arguments.callee, 1000);
	})();
}

function OpenPage(URL, WName, Width, Height, Left, Top, Scrollbars, Resizable){
	window.open(URL, WName,"width="+Width+", height="+Height+",left="+Left+",top="+Top+",scrollbars="+Scrollbars+",resizable="+Resizable);
}

function getfocus(StrId){
	document.getElementById(StrId).focus();
}
//-----------------------------------------------------//

//-----------------------------------------------------//
//tool tips functions
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}

function prepareInputsForHints() {
	var inputs = document.getElementsByTagName("input");
	for (var i=0; i<inputs.length; i++){
		// test to see if the hint span exists first
		if (inputs[i].parentNode.getElementsByTagName("span")[0]) {
			// the span exists!  on focus, show the hint
			inputs[i].onfocus = function () {
				this.parentNode.getElementsByTagName("span")[0].style.display = "inline";
			}
			// when the cursor moves away from the field, hide the hint
			inputs[i].onblur = function () {
				this.parentNode.getElementsByTagName("span")[0].style.display = "none";
			}
		}
	}
	// repeat the same tests as above for selects
	var selects = document.getElementsByTagName("select");
	for (var k=0; k<selects.length; k++){
		if (selects[k].parentNode.getElementsByTagName("span")[0]) {
			selects[k].onfocus = function () {
				this.parentNode.getElementsByTagName("span")[0].style.display = "inline";
			}
			selects[k].onblur = function () {
				this.parentNode.getElementsByTagName("span")[0].style.display = "none";
			}
		}
	}
	
	// repeat the same tests as above for textarea
	var textareas = document.getElementsByTagName("textarea");
	for (var k=0; k<textareas.length; k++){
		if (textareas[k].parentNode.getElementsByTagName("span")[0]) {
			textareas[k].onfocus = function () {
				this.parentNode.getElementsByTagName("span")[0].style.display = "inline";
			}
			textareas[k].onblur = function () {
				this.parentNode.getElementsByTagName("span")[0].style.display = "none";
			}
		}
	}
}
//-----------------------------------------------------//

/* Get URL Parameter values
*/
function getURLValues(Param){
  Param = Param.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+Param+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec(window.location.href);
  if( results == null )
    return "";
  else
    return results[1];
}
//-----------------------------------------------------//



//-----------------------------------------------------//
function GetFormValuesById(FormObj){
	var ElmntVal = "";
	var ValuePart ="";
	for(i=0; i< FormObj.elements.length; i++){
		if(FormObj.elements[i].type != "button" && FormObj.elements[i].type != "reset"){
			if(FormObj.elements[i].value != ""){
				if(FormObj.elements[i].type == 'radio'){
					if(FormObj.elements[i].checked == true){
						ValuePart = FormObj.elements[i].value.replace(/&/g, "~");
						ElmntVal += FormObj.elements[i].name + "=" + ValuePart + "&";
					}
				}else if(FormObj.elements[i].type == 'checkbox'){
					if(FormObj.elements[i].checked == true){
						ValuePart = FormObj.elements[i].value.replace(/&/g, "~");
						ElmntVal += FormObj.elements[i].name + "=" + ValuePart + "&";
					}
				}else{
					ValuePart = FormObj.elements[i].value.replace(/&/g, "~");
					ElmntVal += FormObj.elements[i].name + "=" + ValuePart + "&";
				}
			}
		}
	}
	return ElmntVal;
}
//-----------------------------------------------------//

//-----------------------------------------------------//
function OnLoadMsg(MsgId, ClassNm, InrHtml){
	MsgId.className = ClassNm;
	MsgId.innerHTML = InrHtml;
}
function ScrollDiv(objDiv){
	var objDiv = document.getElementById(objDiv);
	objDiv.scrollTop = objDiv.scrollHeight;
}
function SetBlank(StrNames){
	var IdName = StrNames.split(",");
	for(Li = 0; Li < IdName.length; Li++){
		document.getElementById(IdName[Li]).value = "";
	}
}
function SetFocus(StrId){
	document.getElementById(StrId).focus();
}
//-----------------------------------------------------//


//---------------------------------------------------//
function RestrictSpecialChar(txtName, maxLength){
	var exp = String.fromCharCode(window.event.keyCode) 
	var address=document.getElementById(txtName);
  	//Allowed some special chars and alphabets and numbers
	//If you don’t want any special char you can remove from the below line	

	var r = new RegExp("[_0-9a-zA-Z \r]", "g");
	if (exp.match(r) == null){
		window.event.keyCode = 0
		return false;
	}
	if(address.value.length >= maxLength){
		alert("Maximum of "+ maxLength +" characters are allowed to be entered as a description"); 
		address.value = address.value.substring(0, maxLength);    
		return false;
	}
}  

//On paste it should not be more the max length allowed
function RestrictOnPaste(txtName, maxLength){
	var address=document.getElementById(txtName);
	if(address.value.length >= maxLength){   
		alert("Maximum of " + maxLength + "characters are allowed to be entered as a description"); 
		address.value = address.value.substring(0, maxLength);    
		return false;
	}
} 
function ImageValid(filename){
	var ext = getExtension(filename);
    switch (ext.toLowerCase()) {
	case 'jpeg':
	case 'jpg':
	case 'png':
	case 'gif':
        return true;
    }
    return false;
}
function ImageFile(file) {
    var extension = file.substr( (file.lastIndexOf('.') +1));
    switch(extension.toLowerCase()) {
        case 'png':
		case 'jpg':
        case 'jpeg':
		case 'gif':
            return 1;
        break;
        default:
            return 0;
    }
};
function Excelfile(file) {
    var extension = file.substr( (file.lastIndexOf('.') +1));
    switch(extension.toLowerCase()) {
        case 'xls':
		case 'xlsx':
        case 'csv':
            return 1;
        break;
        default:
            return 0;
    }
};
function getRadioBtnVals(radioObj){
    if(!radioObj){return "";}
    var radioLength = radioObj.length;
    if(radioLength == undefined)
        if(radioObj.checked)
            return radioObj.value;
        else
            return "";
    for(var i = 0; i < radioLength; i++) {
        if(radioObj[i].checked) {
            return radioObj[i].value;
        }
    }
    return "";
}