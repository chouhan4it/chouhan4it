$(function() { 
	$("#frm_prsdtls").validationEngine();	
	$("#fldvUserId").blur(getUserNameAvailable);
	$(".GetExtrimLeft").click(ExtrimLeftRight);
	$(".getMobileNumber").blur(checkMobile);
	$(".getPanNumber").blur(checkPanCard);
	$(".checkEmail").blur(LoadCheckEmailId);
});
function ExtrimLeftRight(){
	var fldcLftRgt = $(this).val();
	var fldiSpMId  = $("#fldiSpMId").val();
	$("#fldvSplUId").val('');
	$("#fldvSpLName").val('');
	$("#fldiSpLId").val('');
	if(fldiSpMId>0 & fldcLftRgt!=""){
		var URLLOAD = JsURLPath+"json/json_listing.php?StrType=EXTREME_LEFT_RIGHT&fldiSpMId="+fldiSpMId+"&fldcLftRgt="+fldcLftRgt;
		$.getJSON(URLLOAD, function(JsonVal){
			if(JsonVal){
				$("#fldvSplUId").val(JsonVal.fldvSplUId);
				$("#fldvSpLName").val(JsonVal.fldvSpLName);
				$("#fldiSpLId").val(JsonVal.fldiSpLId);
			}
		});
	}
}
function checkPanCard(){
	var fldvPanNo = $("#fldvPanNo").val();
	$('#panMsg').removeClass("correctbox").addClass("errorbox");
	if(fldvPanNo!=""){
		var URLLOAD = JsURLPath+"json/json_listing.php?StrType=CHECK_PANCARD&fldvPanNo="+encodeURIComponent(fldvPanNo);
		$.getJSON(URLLOAD, function(JsonVal){
			if(JsonVal){
				if(JsonVal.fldiCounter > 0){
					$('#panMsg').html('PAN number already registered with us !!!');
					$("#fldvPanNo").val("");
					return false;
				}else{
					$('#panMsg').html("PAN number available for registration !!!");
					$('#panMsg').addClass("correctbox");
				}
			}
		});
	}else{
		$('#panMsg').html('Invalid PAN Number !!!');
		$("#fldvPanNo").val("");
	}		
}
function checkMobile(){
	var fldvMobile = $("#fldvMobile").val();
	$('#mobileMsg').removeClass("correctbox").addClass("errorbox");
	if(fldvMobile!=""){
		var URLLOAD = JsURLPath+"json/json_listing.php?StrType=CHECK_MOBILE&fldvMobile="+encodeURIComponent(fldvMobile);
		$.getJSON(URLLOAD, function(JsonVal){
			if(JsonVal){
				if(JsonVal.fldiCounter > 0){
					$('#mobileMsg').html('Mobile number already registered with us !!!');
					$("#fldvMobile").val("");
					return false;
				}else{
					$('#mobileMsg').html("Mobile number available for registration !!!");
					$('#mobileMsg').addClass("correctbox");
				}
			}
		});
	}else{
		$('#mobileMsg').html('Invalid Mobile Number !!!');
		$("#fldvMobile").val("");
	}	
}
function LoadCheckEmailId(){
	var fldvEmail = $("#fldvEmail").val();
	$('#EmailId').removeClass("correctbox").addClass("errorbox");
	if(fldvEmail!=""){
		var URLLOAD = JsURLPath+"json/json_listing.php?StrType=CHECK_EMAIL&fldvEmail="+encodeURIComponent(fldvEmail);
		$.getJSON(URLLOAD, function(JsonVal){
			if(JsonVal){
				if(JsonVal.fldiCounter > 0){
					$('#EmailId').html('Email address already registered with us !!!');
					$("#fldvEmail").val("");
					$("#fldvUserId").val("");
					return false;
				}else{
					$('#EmailId').html("Email address available for registration !!!");
					$('#EmailId').addClass("correctbox");
				}
			}
		});
	}else{
		$('#EmailId').html('Invalid Email address entered !!!');
	}
}

function getUserNameAvailable(){
	var UserErrMsg = $('#UserErrMsg');
	UserErrMsg.html("");
	var LoadImg = $('#LoadImg');
	LoadImg.show();
	var fldvUserId_Final = $('#fldvUserId_Final');
	fldvUserId_Final.val("");
	var fldvUserId = encodeURIComponent(trim($(this).attr("value")));
	UserErrMsg.removeClass;
	
	if(fldvUserId!=""){
		$(this).addClass("loading").delay(200).queue(function(next){
			$(this).removeClass("loading");
			next();
		});
		if(fldvUserId.length<6 || fldvUserId.length>32){
			UserErrMsg.addClass("errorbox");
			UserErrMsg.html("It must be between 6-32 characters.");
			return false;
		}
		var FirstLetter = fldvUserId.substring(0, 1);
		if(isNumber(FirstLetter) == true){
			UserErrMsg.addClass("errorbox");
			UserErrMsg.html("Username should be start with a letter.");
			return false;
		}
		var pattern = /[0-9]+/g;
		var matches = fldvUserId.match(pattern);
		if(isNumber(matches) == false){
			UserErrMsg.addClass("errorbox");
			UserErrMsg.html("Username should contain at-least one numeric value.");
			return false;
		}
		
		$.getJSON(JsURLPath+"json/json_listing.php?StrType=CHKUSER&fldvUserId="+fldvUserId, function(JsonVal) {
			if(JsonVal){
				if(JsonVal.fldiMemId > 0){
					UserErrMsg.addClass("errorbox");
					UserErrMsg.html('<b>"'+fldvUserId+'"</b> username already exists choose another.');
					return false;
				}else{
					UserErrMsg.addClass("correctbox");
					UserErrMsg.html("<b>\"" + fldvUserId + "\"</b> username availabe.");
					$('#fldvUserId_Final').val(fldvUserId);
				}
			}else{
				UserErrMsg.addClass("correctbox");
				UserErrMsg.html("<b>\"" + fldvUserId + "\"</b> username availabe.");
				$('#fldvUserId_Final').val(fldvUserId);
				
			}
		});
	}else{
		UserErrMsg.html("Please choose your username.");
		$('#fldvUserId_Final').val();
		return false;
	}
}
$(".fldiTypeId").on('click',function(){
	$("#fldiBp").val("");
	$("#fldiPrice").val("");
	$("#fldiAmount").val("");
	$("#fldvPinNo").val("");
	$("#fldvPinKey").val("");
	$("#fldiPinId").val("");
});
$(".verifyPins").on('click',function(){
	var fldiTypeId= $('input[name=fldiTypeId]:checked').val()
	var fldvPinNo = $("#fldvPinNo").val();
	var fldvPinKey = $("#fldvPinKey").val();
	$("#lateFeeMark").slideUp(600);
	$("#ajaxMessage").removeClass("warning");
	$("#ajaxMessage").removeClass("attention");
	$("#ajaxMessage").removeClass("success");
	if(fldvPinNo!="" && fldvPinKey!=""){
		$("#ajaxMessage").html("<img src='../setupimages/loading_small.gif'>");
		var URL_PINS = JsURLPath+"json/json_listing.php?StrType=PIN_VERIFY&fldvPinNo="+fldvPinNo+"&fldvPinKey="+fldvPinKey;
		$.getJSON(URL_PINS,function(JsonReturn){
			if(JsonReturn){
				$("#ajaxMessage").html("");
				switch(JsonReturn.ErrorMsg){
					case "USED":
						$("#ajaxMessage").addClass("warning");
						$("#ajaxMessage").html("!! This pin already used, please try another !!");
						$("#fldvPinName").val('');
						$("#fldiBp").val('');
						$("#fldiPrice").val('');
						$("#fldiLateFee").val('');
						$("#fldiPinId").val('');
					break;
					case "BLOCK":
						$("#ajaxMessage").addClass("warning");
						$("#ajaxMessage").html("!! Please contact your administrator !!");
						$("#fldvPinName").val('');
						$("#fldiBp").val('');
						$("#fldiPrice").val('');
						$("#fldiLateFee").val('');
						$("#fldiPinId").val('');
					break;
					case "INVALID":
						$("#ajaxMessage").addClass("warning");
						$("#ajaxMessage").html("!! Invalid pin details , please try another !!");
						$("#fldvPinName").val('');
						$("#fldiBp").val('');
						$("#fldiPrice").val('');
						$("#fldiLateFee").val('');
						$("#fldiPinId").val('');
					break;
					case "LATE":
						$("#ajaxMessage").addClass("attention");
						$("#ajaxMessage").html("!! Validated successfully ,  please proceed further !!");
						$("#lateFeeMark").slideDown(600);
						$("#fldvPinName").val(JsonReturn.fldvPinName);
						$("#fldiBp").val(JsonReturn.fldiBp);
						$("#fldiPrice").val(JsonReturn.fldiPrice);
						$("#fldiLateFee").val(JsonReturn.fldiLateFee);
						$("#fldiPinId").val(JsonReturn.fldiPinId);
					break;
					case "SUCESSS":
						$("#ajaxMessage").addClass("success");
						$("#ajaxMessage").html("!! Validated successfully , please proceed further !!");
						$("#fldvPinName").val(JsonReturn.fldvPinName);
						$("#fldiBp").val(JsonReturn.fldiBp);
						$("#fldiPrice").val(JsonReturn.fldiPrice);
						$("#fldiPinId").val(JsonReturn.fldiPinId);
					break;
					default:
						$("#ajaxMessage").addClass("warning");
						$("#ajaxMessage").html("!! Invalid details !!");
				}
			}
		});
	}
});