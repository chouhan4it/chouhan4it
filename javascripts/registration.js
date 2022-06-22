function MembershipPreff(){
	var fldvSpUId = document.getElementById("fldvSpUId");
	var fldiSpMId = document.getElementById("fldiSpMId");
	var fldvSplUId = document.getElementById("fldvSplUId");
	var fldvFullName = document.getElementById("fldvFullName");
	var fldvPinNo = document.getElementById("fldvPinNo");
	var fldvPinKey = document.getElementById("fldvPinKey");
	var fldiPrice = document.getElementById("fldiPrice");
	var fldiTypeId = document.getElementById("fldiTypeId");                               
	
	if(fldvSpUId.value == ""){
		alert("Please enter your Sponsor Id!!! ");
		fldvSpUId.focus();
		return "N";
	}
	if(fldiSpMId.value == ""){
		alert("Please verify your Sponsor !!! ");
		fldvSpUId.focus();
		return "N";
	}
	if(trim(fldvFullName.value) == ""){
		alert("Please Enter your Name !!! ");
		fldvFullName.focus();
		return "N";
	}
	return "Y";
}

function PersnlDetails(){
	var fldvPOAdrs = document.getElementById("fldvPOAdrs")
	var fldiCityId = document.getElementById("fldiCityId")
	var fldiStateId = document.getElementById("fldiStateId")
	var fldvMobile =document.getElementById("fldvMobile")
	var fldvPanNo = document.getElementById("fldvPanNo")
	
	
	if(fldvPOAdrs.value == ""){
		alert("Please enter your Postal Address!!! ");
		fldvPOAdrs.focus();
		return "N";
	}
	if(fldvMobile.value == ""){
		alert("Please Enter Your Contact no.");
		fldvMobile.focus();
		return "N";
	}
	if(fldvMobile.value.length !=10){
		alert("Please Enter contact no. in 10 Digits .");
		fldvMobile.focus();
		return "N";
	} 
	if(fldvEmail.value == ""){
		alert("Please enter your email address.");
		fldvEmail.focus();
		return "N";
	}
	if(validateEmailv2(fldvEmail.value)==false){
		alert("Please enter valid email address.");
		fldvEmail.focus();
		return "N";
	}
	return "Y";
}

function VldtNominee(){
	return "Y";
}

function BankDetails(){
	var fldiBankId = document.getElementById("fldiBankId");
	var fldvBranch = document.getElementById("fldvBranch");
	var fldvAcctNo = document.getElementById("fldvAcctNo");
	
	
	if(fldiBankId.value == ""){
		alert("Please Enter your Bank Name!!! ");
		fldiBankId.focus();
		return "N";
	}
	if(fldvBranch.value == ""){
		alert("Please Enter your Bank Branch.!!! ");
		fldvBranch.focus();
		return "N";
	}
	if(fldvAcctNo.value == ""){
		alert("Please Enter your Account No.!!! ");
		fldvAcctNo.focus();
		return "N";
	}
	return "Y";
}
function CheckLoginDtls(){
	var fldvPass = document.getElementById("fldvPass")
	var fldvRePass = document.getElementById("fldvRePass");
	var fldvTrnsPass = document.getElementById("fldvTrnsPass");
	var C_Code = document.getElementById("C_Code");
	var fldcTerms = document.getElementById("fldcTerms");
	
	if(trim(fldvPass.value) == ""){
		alert("Please enter your Password");
		fldvPass.focus();
		return "N";
	}
	if(trim(fldvPass.value).length < 6){
		alert("Password shouldn't be less than 6 characters");
		fldvPass.focus();
		return "N";
	}
	if(trim(fldvRePass.value)==""){
		alert("Please enter your Confirm Password");
		fldvRePass.focus();
		return "N";
	}
	if(trim(fldvPass.value)!=trim(fldvRePass.value)){
		alert(" Retype password doesn't match!!! ");
		fldvPass.value="";
		fldvRePass.value="";
		fldvPass.focus();
		return "N";
	}
	if(trim(C_Code.value) == ""){
		alert("Please enter your Verification Code.!!! ");
		C_Code.focus();
		return "N";
	}
}

function CallFunction(){
	var Submit = document.getElementById("Submit");
	var Cancel = document.getElementById("Cancel");
	var ReffVal = "Y";
	var PrsVal = "Y";
	var NomVal = "Y";
	var BankVal = "Y";
	var LgInVal = "Y";
	var VrfVal = "Y";
	
	ReffVal = MembershipPreff();
	if(ReffVal == "N"){return false;}else{PrsVal = PersnlDetails();}
	if(PrsVal == "N"){return false;}else{ LgInVal = CheckLoginDtls();}
	if(PrsVal == "N"){return false;}else{/*NomVal = VldtNominee();*/}
	if(NomVal == "N"){return false;}else{/*BankVal = BankDetails();*/}
	if(LgInVal == "N"){return false;}else{
		Submit.disabled="disabled";
		Cancel.disabled="disabled";
		return true;
	}
}

function VldtSPId(){
	var fldvSpUId = document.getElementById("fldvSpUId");
	var fldvSPName = document.getElementById("fldvSPName");
	var fldvSplUId = document.getElementById("fldvSplUId");
	var L = document.getElementById("L");
	var R = document.getElementById("R");
	L.disabled=false;
	R.disabled=false;
	L.checked=false;
	R.checked=false;
	fldvSplUId.value="";
	fldvSPName.value ="";
	if(trim(fldvSpUId.value) != ""){
		var index = ajax.length;
		ajax[index] = new sack();
		ajax[index].requestFile = JsURLPath+'ajax/singlereturn.php?StrType=ChkSPId&fldvSpUId='+fldvSpUId.value;
		ajax[index].onLoading=function(){
			fldvSPName.className="cmnfld loading";
		};
		ajax[index].onCompletion = function(){
			eval(ajax[index].response);
			fldvSPName.className="cmnfld";
		};
		ajax[index].runAJAX();
	}else{
		//return false;
	}
}
function SetSPBlank(){
	var L = document.getElementById("L");
	var R = document.getElementById("R");
	L.disabled=false;
	R.disabled=false;
	L.checked=false;
	R.checked=false;
	SetBlank('fldvSPName,fldvSplUId');
}

function ValidatePin(){
	var fldvPinNo = document.getElementById("fldvPinNo");
	var fldvPinKey = document.getElementById("fldvPinKey");
	var fldiPrice = document.getElementById("fldiPrice");
	var fldvPinName = document.getElementById("fldvPinName");
	var fldiTypeId = document.getElementById("fldiTypeId");
	var fldcType = document.getElementById("fldcType");
	var URL_LOAD = JsURLPath+'ajax/singlereturn.php?StrType=RChkPin&fldvPinNo='+fldvPinNo.value+'&fldvPinKey='+fldvPinKey.value+'&fldcType='+fldcType.value;
	
	if(trim(fldvPinNo.value) != "" && trim(fldvPinKey.value) != "" && trim(fldcType.value) != ""){
		var index = ajax.length;
		ajax[index] = new sack();
		ajax[index].requestFile = URL_LOAD;
		//document.write(ajax[index].requestFile);
		ajax[index].onLoading=function(){};
		ajax[index].onCompletion = function(){
			eval(ajax[index].response);
		};
		ajax[index].runAJAX();
	}else{
		fldiPrice.value = '';
	}
}

function CheckMemberId(){
	var fldvUserId = document.getElementById("fldvUserId");
	var fldvFullName = document.getElementById("fldvFullName");
	var SgstId = document.getElementById("SgstId");
	var iSgstNo = document.getElementById("iSgstNo");
	if(fldvUserId.value != ""){
		var index = ajax.length;
		ajax[index] = new sack();
		ajax[index].requestFile = JsURLPath+'ajax/singlereturn.php?StrType=ChkMemUId&fldvUserId='+fldvUserId.value;
		ajax[index].onLoading=function(){};
		ajax[index].onCompletion = function(){
			var retVal = ajax[index].response;
			if(retVal != ""){
				fldvUserId.value = retVal;
				SgstId.innerHTML = "<strong>"+retVal+ "</strong> will be your membership login user id. Please memorise it for future use.<br>";
				iSgstNo.value="0";
			}
			else{
				fldvUserId.value="";
				alert("Member user Id already in use");
				SgstId.innerHTML = "Member user Id already in use<br>";
				iSgstNo.value="0";
			}
		};
		ajax[index].runAJAX();
	}
}

function GenMemUId(){
	var fldvUserId = document.getElementById("fldvUserId");
	var fldvFullName = document.getElementById("fldvFullName");

	var SgstId = document.getElementById("SgstId");
	var iSgstNo = document.getElementById("iSgstNo");
	
	if(fldvFullName.value != ""){
		if(parseInt(iSgstNo.value) == 0){
			var index = ajax.length;
			ajax[index] = new sack();
			ajax[index].requestFile = JsURLPath+'ajax/singlereturn.php?StrType=GenMemUId&fldvFullName='+fldvFullName.value;
			ajax[index].onLoading=function(){};
			ajax[index].onCompletion = function(){
				SgstId.innerHTML += ajax[index].response;
				iSgstNo.value = parseInt(iSgstNo.value) + 3;
			};
			ajax[index].runAJAX();
		}

	}
}
function AssignValue(StrValue){
	var fldvUserId = document.getElementById("fldvUserId");
	fldvUserId.value = StrValue;
	var SgstId = document.getElementById("SgstId");
	SgstId.innerHTML = "<strong>"+StrValue+ "</strong> will be your membership login user id. Please memorise it for future use.<br>";
	var iSgstNo = document.getElementById("iSgstNo");
	iSgstNo.value = "0";
}

function CallFunctionEdit(){
	var ReffVal = "Y";
	var PrsVal = "Y";
	var NomVal = "Y";
	var BankVal = "Y";
	
	PrsVal = PersnlDetails();
	if(PrsVal == "N"){return false;}else{NomVal = VldtNominee();}
	if(NomVal == "N"){return false;}else{BankVal = BankDetails();}
	if(BankVal == "N"){return false;}else{return true;}
}
function CallFunctionPass(){
	var fldvPass = document.getElementById("fldvPass")
	var fldvRePass = document.getElementById("fldvRePass");
	var Img_Code = document.getElementById("C_Code");
	
	if(trim(fldvPass.value) == ""){
		alert("Please enter your Password!!!");
		fldvPass.focus();
		return false;
	}
	if(trim(fldvPass.value).length < 6){
		alert("Password shouldn't be less than 6 characters!!!");
		fldvPass.focus();
		return false;
	}
	if(trim(fldvRePass.value)==""){
		alert("Please enter your Confirm Password!!!");
		fldvRePass.focus();
		return false;
	}
	if(trim(fldvPass.value)!=trim(fldvRePass.value)){
		alert("Retype password doesn't match!!! ");
		fldvPass.value="";
		fldvRePass.value="";
		fldvPass.focus();
		return false;
	}
	if(trim(Img_Code.value)==""){
		alert("Please enter your security verification code!!!");
		return false;
	}
	return true;
}


function AvailEditMemId(){
	var fldvUserId_Curr = document.getElementById("fldvUserId_Curr");
	var fldvUserId_New = document.getElementById("fldvUserId_New");
	var MsgBox = document.getElementById("MsgBox");
	if(fldvUserId_New.value != ""){
		var index = ajax.length;
		ajax[index] = new sack();
		ajax[index].requestFile = JsURLPath+'ajax/singlereturn.php?StrType=EdtMemUId&fldvUserId_New='+fldvUserId_New.value+'&fldvUserId_Curr='+fldvUserId_Curr.value;
		ajax[index].onLoading=function(){
			MsgBox.className = "processing";
			MsgBox.innerHTML="";
		};
		ajax[index].onCompletion = function(){
			var retVal = ajax[index].response;
			if(retVal != ""){
				if(retVal.length > 6){
					fldvUserId_New.value = retVal;
					MsgBox.className = "errMsg";
					MsgBox.innerHTML = "<strong>"+retVal+ "</strong> will be your membership login user id. Please memorise it for future use.<br>";
				}else{
					fldvUserId_New.value = "";
					MsgBox.className = "errMsg";
					MsgBox.innerHTML = "Please enter minimum 6 characters for your user name!!!";
				}
			}
			else{
				fldvUserId_New.value="";
				MsgBox.className = "errMsg";
				MsgBox.innerHTML = "Member user id already in use, <br>If you really want to change the user name then use another user id";
			}
		};
		ajax[index].runAJAX();
	}
}

function ValEditUserForm(){
	with(document.frmmbrreg){
		if(trim(fldvUserId_Curr.value) == ""){
			alert("Please enter a E-Idto update");
			return false;
		}
		if(trim(fldvUserId_New.value) == ""){
			alert("Please enter the new ID No.");
			fldvUserId_New.focus();
			return false;
		}
		if(trim(C_Code.value) == ""){
			alert("Please enter the security code for updation");
			C_Code.focus();
			return false;
		}
	}
	var RtrnBack = confirm("You are going to update the user's ID No.!!!\nIf you really want to change then click on OK!!!\nElse click on Cancel to return bank!!!");
	if(RtrnBack){
		return true;
	}else{
		return false;
	}
}
function VerifySpsr(){
	var fldvSpUId=document.getElementById("fldvSpUId");
	var fldiSpMId=document.getElementById("fldiSpMId");
	var fldvSpName=document.getElementById("fldvSpName");
	if(trim(fldvSpUId.value)!=""){ 
		var index = ajax.length;
		ajax[index] = new sack();
		ajax[index].requestFile = JsURLPath+'ajax/singlereturn.php?StrType=ChkSp&fldvSpUId='+fldvSpUId.value;
		//document.write(ajax[index].requestFile);
		ajax[index].onLoading=function(){};
		ajax[index].onCompletion = function(){
			eval(ajax[index].response);
		};
		ajax[index].runAJAX();
	}else{
		fldvSpName.value='';
	}
}

// veryfy ID No. for transfer e-wallt
function VerifyMemId(){
	var fldvSpUId = document.getElementById("fldvSpUId");
	var fldvRefFullName = document.getElementById("fldvRefFullName");
	if(trim(fldvSpUId.value) != ""){
		var index = ajax.length;
		ajax[index] = new sack();
		ajax[index].requestFile = JsURLPath+'ajax/singlereturn.php?StrType=ValMem&fldvSpUId='+fldvSpUId.value;
		ajax[index].onLoading=function(){};
		ajax[index].onCompletion = function(){
			eval(ajax[index].response);
		};
		ajax[index].runAJAX();
	}else{
		fldvRefFullName.value = '';
	}
}

// Verify ID No. upgrade as green member
function VerifyMem(){
	var fldvUserId = document.getElementById("fldvUserId");
	var fldiMemId = document.getElementById("fldiMemId");
	var fldvFullName = document.getElementById("fldvFullName");
	if(trim(fldvUserId.value)!=""){ 
		var index = ajax.length;
		ajax[index] = new sack();
		ajax[index].requestFile = JsURLPath+'ajax/singlereturn.php?StrType=ChkMem&fldvUserId='+fldvUserId.value;
		//document.write(ajax[index].requestFile);
		ajax[index].onLoading=function(){};
		ajax[index].onCompletion = function(){
			eval(ajax[index].response);
		};
		ajax[index].runAJAX();
	}else{
		fldvFullName.value='';
	}
}

function VerifyDstrb(){
	var fldvDstrId = document.getElementById("fldvDstrId");
	var fldiDistId = document.getElementById("fldiDistId");
	var fldvDstrbFullName = document.getElementById("fldvDstrbFullName");
	if(trim(fldvDstrId.value)!=""){ 
		var index = ajax.length;
		ajax[index] = new sack();
		ajax[index].requestFile = JsURLPath+'ajax/singlereturn.php?StrType=ChkDstrb&fldvDstrId='+fldvDstrId.value;
		//document.write(ajax[index].requestFile);
		ajax[index].onLoading=function(){};
		ajax[index].onCompletion = function(){
			eval(ajax[index].response);
		};
		ajax[index].runAJAX();
	}else{
		fldvFullName.value='';
	}
}
function VerifySponsor(){
	var fldvSprUser = document.getElementById("fldvSprUser");
	var fldiSpMId = document.getElementById("fldiSpMId");
	var fldvSprName = document.getElementById("fldvSprName");
	var URL_LOAD = JsURLPath+'ajax/singlereturn.php?StrType=ChkSponsor&fldvSprUser='+fldvSprUser.value;
	if(trim(fldvSprUser.value)!=""){ 
		var index = ajax.length;
		ajax[index] = new sack();
		ajax[index].requestFile = URL_LOAD;
		//document.write(ajax[index].requestFile);
		ajax[index].onLoading=function(){};
		ajax[index].onCompletion = function(){
			eval(ajax[index].response);
		};
		ajax[index].runAJAX();
	}else{
		fldvFullName.value='';
	}
}

// Function for validate placement id
function CheckSPN(){
		var DivMsgId = document.getElementById("DivMsg2");
		var fldcSpnsr = document.getElementById("fldcSpnsr");
		if(fldcSpnsr.value != ''){
			var index = ajax.length;
			ajax[index] = new sack();
			ajax[index].requestFile = JsURLPath+'ajax/singlereturn.php?StrType=ChkSpnsr&fldcSpnsr='+fldcSpnsr.value;
			ajax[index].onCompletion = function(){
				DivMsgId.align = 'left';
				if(fldcSpnsr.checked == false){
					fldvSpUId.value = '';
					fldvSposor.value = '';
					DivMsgId.className = 'txtred';
					DivMsgId.innerHTML = "Enter Sponsor Id !!!";
				}else{
					var myStr = ajax[index].response;
					var mySpl = myStr.split(",");
					DivMsgId.className = 'greentext';
					DivMsgId.innerHTML = mySpl[0];
					fldvSpUId.value = mySpl[1];
					fldvSposor.value = mySpl[1];
				}
			};
			ajax[index].runAJAX();
		}
	}
	
function ValidateUpgrdPin(){
	var fldvPinNo = document.getElementById("fldvPinNo");
	var fldvPinKey = document.getElementById("fldvPinKey");
	var fldiPrice = document.getElementById("fldiPrice");
	var fldvPinName = document.getElementById("fldvPinName");
	var fldiTypeId = document.getElementById("fldiTypeId");
	
	if(trim(fldvPinNo.value) != "" && trim(fldvPinKey.value) != ""){
		var index = ajax.length;
		ajax[index] = new sack();
		ajax[index].requestFile = JsURLPath+'ajax/singlereturn.php?StrType=RChkUpgrdPin&fldvPinNo='+fldvPinNo.value+'&fldvPinKey='+fldvPinKey.value;
		ajax[index].onLoading=function(){};
		ajax[index].onCompletion = function(){
			eval(ajax[index].response);
		};
		ajax[index].runAJAX();
	}else{
		fldiPrice.value = '';
		fldiTypeId.value = '';
	}
}

// Verify ID No. for upgrade membership
function VerifyMemUpgrd(){
	var fldvUserId = document.getElementById("fldvUserId");
	var fldiMemId = document.getElementById("fldiMemId");
	var fldvFullName = document.getElementById("fldvFullName");
	if(trim(fldvUserId.value)!=""){ 
		var index = ajax.length;
		ajax[index] = new sack();
		ajax[index].requestFile = JsURLPath+'ajax/singlereturn.php?StrType=ChkUpgrdMem&fldvUserId='+fldvUserId.value;
		//document.write(ajax[index].requestFile);
		ajax[index].onLoading=function(){};
		ajax[index].onCompletion = function(){
			eval(ajax[index].response);
		};
		ajax[index].runAJAX();
	}else{
		fldvFullName.value='';
	}
}