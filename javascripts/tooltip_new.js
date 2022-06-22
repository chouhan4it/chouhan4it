<!--

ContentInfo = "";
topColor = "#0178B0"
subColor = "#ffffff"
var mouse_X;
var mouse_Y;
var HELPER_IFRAME_ID = "IFrmHelper";
var topDivZIndex = 10000;
var tip_active = 0;
var divIDLayer;
function update_tip_pos(){
        var x=mouse_X + 20; 
        //var x=mouse_X - 201; // +20 replaced with - 201 to bring tooltip to left
        var y=mouse_Y;
		document.getElementById(divIDLayer).style.left = x+"px";
		document.getElementById(divIDLayer).style.top  = y+"px";
		
		var oHelperIframe = document.getElementById(HELPER_IFRAME_ID);
		oHelperIframe.style.top = document.getElementById(divIDLayer).offsetTop+"px";
        oHelperIframe.style.left = document.getElementById(divIDLayer).offsetLeft +"px" ;   
}

var ie = document.all?true:false;

if (!ie) document.captureEvents(Event.MOUSEMOVE)
document.onmousemove = getMouseXY;

function getMouseXY(e){
var x,y;
if (self.pageYOffset) // all except Explorer
{
	x = self.pageXOffset;
	y = self.pageYOffset;
}
else if (document.documentElement && document.documentElement.scrollTop)
	// Explorer 6 Strict
{
	x = document.documentElement.scrollLeft;
	y = document.documentElement.scrollTop;
}
else if (document.body) // all other Explorers
{
	x = document.body.scrollLeft;
	y = document.body.scrollTop;
}
if (ie) { // grab the x-y pos.s if browser is IE
mouse_X = event.clientX + x;
mouse_Y = event.clientY + y;
}
else { // grab the x-y pos.s if browser is NS
mouse_X = e.pageX;
mouse_Y = e.pageY;
}
if (mouse_X < 0){mouse_X = 0;}
if (mouse_Y < 0){mouse_Y = 0;}
if(tip_active){update_tip_pos();}
}

function EnterContent(TTitle, TContent){

ContentInfo ='<table class="tooltiptable" border="0" style="width:200px;z-index:2;text-align:left;" cellspacing="0" cellpadding="0">'+
'<tr><td width="100%" class="vat" bgcolor="#0178B0">'+
'<table border="0" width="100%" cellspacing="1" cellpadding="0">'+
'<tr><td width="100%" bgcolor='+topColor+'>'+
'<table border="0" width="99%" cellspacing="0" cellpadding="0">'+
'<tr><td width="100%" style="padding-left:3px;color:#ffffff">'+
'<font class="tooltiptitle">'+TTitle+'</font>'+
'</td></tr>'+
'</table>'+
'</td></tr>'+
'<tr><td width="100%" bgcolor='+subColor+'>'+
'<table border="0" width="99%" cellpadding="0" cellspacing="1">'+
'<tr><td width="100%" style="padding-left:3px;">'+
'<font class="tooltipcontent">'+TContent+'</font>'+
'</td></tr>'+
'</table>'+
'</td></tr>'+
'</table>'+
'</td></tr>'+
'</table>';

}

function tip_it( divID, TTitle, TContent){
        divIDLayer=divID;
		tip_active = 1;
		document.getElementById(divID).style.visibility = "visible";
		EnterContent(TTitle, TContent);
		document.getElementById(divID).innerHTML = ContentInfo;
		
        CreateIframe();
		update_tip_pos();
		
		var oHelperIframe = document.getElementById(HELPER_IFRAME_ID);
		oHelperIframe.style.zIndex = 1;
		oHelperIframe.style.top = document.getElementById(divID).offsetTop ;
        oHelperIframe.style.left = document.getElementById(divID).offsetLeft;
        oHelperIframe.width = document.getElementById(divID).offsetWidth - 1;
        oHelperIframe.height = document.getElementById(divID).offsetHeight ;
        oHelperIframe.style.visibility = 'visible';
}
function hideIt(divID)
{
		tip_active = 0;
		document.getElementById(divID).style.visibility = "hidden";
		var oHelperIframe = document.getElementById(HELPER_IFRAME_ID);
        oHelperIframe.style.visibility = 'hidden';

}


// Add dynamic div to the page
function CreateIframe(){
// Creating and adding dynamic iframe to the page source.
var oBody = document.getElementsByTagName("BODY").item(0);
var oHelperIframe = document.createElement("IFRAME");
oHelperIframe.setAttribute("id", HELPER_IFRAME_ID);
oHelperIframe.style.border = 0; 
oHelperIframe.width = 0; 
oHelperIframe.height = 0;
oHelperIframe.style.position = "absolute";
oBody.appendChild(oHelperIframe);
}
//-->