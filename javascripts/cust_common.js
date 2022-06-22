$(function() { 
	$(".ValidAmt").blur(function(){
		ValidatePinAmount();
	});
	$(".fldvSpUser").on('blur',getUplinerValidation);
	$(".fldvSpUser").on('change',getUplinerValidation);
	$(".verifyPins").on('blur',getPinValidate);
	$(".verifyPins").on('change',getPinValidate);
	//$(".checkDistributor").on('blur',getDistributorValidation);
	$(".checkUsername").on('blur',checkUsername);
	$(".checkPlace").on('click',getPlacement);
});

function ValidatePinAmount(){
	var fldiPrice = $('#fldiPrice').val();
	var fldiNoOfPin = $('#fldiNoOfPin').val();
	if(fldiPrice>=10000 && (fldiPrice % 10000) == 0){
		$('#fldiNetAmnt').val(1000);
	}else{
		alert("Pin amount should be minimum of Rs. 10000 and in multiple of Rs 10000.");
		$('#fldiPrice').val('');
		$('#fldiNoOfPin').val('');
		$('#fldiNetAmnt').val('');
	}
}
function checkUsername(){
	var UserErrMsg = $('#ajaxMessage');
	UserErrMsg.html("");
	UserErrMsg.removeClass("success");
	UserErrMsg.removeClass("warning");
	var fldvUserName = encodeURIComponent(trim($("#fldvUserName").val()));
	if(fldvUserName!=""){
		UserErrMsg.html("loading...");
		var CHECK_USER = JsURLPath+"json/json_listing.php?StrType=CHEK_USERNAME&fldvUserName="+fldvUserName;
		$.getJSON(CHECK_USER, function(JsonVal) {
			if(JsonVal){
				if(JsonVal.fldiCtrl > 0){
					UserErrMsg.addClass("warning");
					UserErrMsg.html("<b>\"" + fldvUserName + "\"</b> this username id not available");
					$("#fldvUserName").val("");
					$("#fldvUserName").focus();
					return false;
				}else{
					UserErrMsg.html("");
				}
			}
		});
	}		
}
function getDistributorValidation(){
	var UserErrMsg = $('#ErrorMsg');
	UserErrMsg.html("");
	UserErrMsg.removeClass("success");
	UserErrMsg.removeClass("warning");
	var LoadImg = $('#LoadImg');
	LoadImg.show();
	var fldiSpMId = $('#fldiSpMId');
	fldiSpMId.val("");
	var fldvSpUser = encodeURIComponent(trim($("#fldvSpUId").val()));
	$("#ErrorMsg").show();
	if(fldvSpUser!=""){
		UserErrMsg.html("loading...");
		var URL_LOAD = JsURLPath+"json/json_listing.php?StrType=CHKEUPLINER&fldvUserId="+fldvSpUser;
		$.getJSON(URL_LOAD, function(JsonVal) {
			if(JsonVal){
				if(JsonVal.fldiMemId > 0 & JsonVal.fldiMemId!=''){
					UserErrMsg.addClass("success");
					UserErrMsg.html('<b>'+fldvSpUser+'</b> this id will be distributor Id.');
					$('#fldiSpMId').val(JsonVal.fldiMemId);
				}else{
					UserErrMsg.addClass("errorbox");
					UserErrMsg.html("<b>\"" + warning + "\"</b> Invalid distributor Id.");
					$("#fldiSpMId").val("");
					$("#fldvSpUId").val("");
					$("#fldvSpUId").focus();
					return false;
				}
			}else{
				UserErrMsg.removeClass("success");
				UserErrMsg.addClass("warning");
				UserErrMsg.html("<b>\"" + fldvSpUser + "\"</b> Invalid distributor Id.");
				$("#fldiSpMId").val("");
				$("#fldvSpUId").val("");
				$("#fldvSpUId").focus();
				return false;
				
			}
		});
	}
}
function getUplinerValidation(){
	var UserErrMsg = $('#ErrorMsg');
	UserErrMsg.html("");
	UserErrMsg.removeClass("success");
	UserErrMsg.removeClass("warning");
	var LoadImg = $('#LoadImg');
	LoadImg.show();
	var fldiSpMId = $('#fldiSpMId');
	fldiSpMId.val("");
	var fldvSpUser = encodeURIComponent(trim($("#fldvSpUId").val()));
	$("#ErrorMsg").show();
	if(fldvSpUser!=""){
		UserErrMsg.html("loading...");
		var URL_LOAD = JsURLPath+"json/json_listing.php?StrType=CHKEUPLINER&fldvUserId="+fldvSpUser;
		$.getJSON(URL_LOAD, function(JsonVal) {
			if(JsonVal){
				if(JsonVal.fldiMemId > 0 & JsonVal.fldiMemId!=''){
					UserErrMsg.addClass("success");
					UserErrMsg.html('<b>'+fldvSpUser+'</b> This id will be sponsor Id.');
					$('#fldiSpMId').val(JsonVal.fldiMemId);
					$('#fldvSpFullName').val(JsonVal.fldvFullName);
					$(".checkPlace").attr('checked',false);
				}else{
					UserErrMsg.addClass("errorbox");
					UserErrMsg.html("<b>\"" + warning + "\"</b> Invalid sponsor Id.");
					$("#fldiSpMId").val("");
					$("#fldvSpUId").val("");
					$("#fldvSpFullName").val("");
					$(".checkPlace").attr('checked',false);
					return false;
				}
			}else{
				UserErrMsg.removeClass("success");
				UserErrMsg.addClass("warning");
				UserErrMsg.html("<b>\"" + fldvSpUser + "\"</b> Invalid sponsor Id.");
				$("#fldiSpMId").val("");
				$("#fldvSpUId").val("");
				$("#fldvSpFullName").val("");
				$(".checkPlace").attr('checked',false);
				return false;
				
			}
		});
	}else{
		UserErrMsg.html("Please enter sponsor Id.");
		$('#fldiSpMId').val();
		$("#fldvSpFullName").val("");
		return false;
	}
}
function getPlacement(){
	var fldiSpMId = $("#fldiSpMId").val();
	var fldcLftRgt = $(this).val();
	if(fldiSpMId>0 && fldcLftRgt!=''){
		var URL_PLACE = JsURLPath+"json/json_listing.php?StrType=CHECK_PLACE&fldiSpMId="+fldiSpMId+"&fldcLftRgt="+fldcLftRgt;
		$.getJSON(URL_PLACE, function(JsonVal) {
 			if(JsonVal.fldiSpLId > 0){
				$("#fldiSpLId").val(JsonVal.fldiSpLId);
				$("#fldvSplUId").val(JsonVal.fldvSplUId);
			}else{
				$("#fldiSpLId").val('');
				$("#fldvSplUId").val('');
				$(".checkPlace").attr('checked',false);
			}
		});
	}
}
function getPinValidate(){
	var fldvPinNo = $("#fldvPinNo").val();
	var fldvPinKey = $("#fldvPinKey").val();
	$("#ajaxMessage").removeClass("warning");
	$("#ajaxMessage").removeClass("attention");
	$("#ajaxMessage").removeClass("success");
	if(fldvPinNo!="" && fldvPinKey!=""){
		$("#ajaxMessage").html("Loading....");
		var URL_PINS = JsURLPath+"json/json_listing.php?StrType=PIN_VERIFY&fldvPinNo="+fldvPinNo+"&fldvPinKey="+fldvPinKey;
		$.getJSON(URL_PINS,function(JsonReturn){
			if(JsonReturn){
				$("#ajaxMessage").html("");
				switch(JsonReturn.ErrorMsg){
					case "USED":
						$("#ajaxMessage").addClass("warning");
						$("#ajaxMessage").html("!! This pin already used, please try another !!");
						$("#fldvPinName").val('');
						$("#fldiProdPV").val('');
						$("#fldiPrice").val('');
						$("#fldiPinId").val('');
						$("#fldiTypeId").val('');
					break;
					case "BLOCK":
						$("#ajaxMessage").addClass("warning");
						$("#ajaxMessage").html("!! Please contact your administrator !!");
						$("#fldvPinName").val('');
						$("#fldiProdPV").val('');
						$("#fldiPrice").val('');
						$("#fldiPinId").val('');
						$("#fldiTypeId").val('');
					break;
					case "INVALID":
						$("#ajaxMessage").addClass("warning");
						$("#ajaxMessage").html("!! Invalid pin details , please try another !!");
						$("#fldvPinName").val('');
						$("#fldiProdPV").val('');
						$("#fldiPrice").val('');
						$("#fldiPinId").val('');
						$("#fldiTypeId").val('');
					break;
					case "LATE":
						$("#ajaxMessage").addClass("attention");
						$("#ajaxMessage").html("!! Validated successfully ,  please proceed further !!");
						$("#lateFeeMark").slideDown(600);
						$("#fldvPinName").val(JsonReturn.fldvPinName);
						$("#fldiProdPV").val(JsonReturn.fldiProdPV);
						$("#fldiPrice").val(JsonReturn.fldiPrice);
						$("#fldiPinId").val(JsonReturn.fldiPinId);
					break;
					case "SUCESSS":
						$("#ajaxMessage").addClass("success");
						$("#ajaxMessage").html("!! Validated successfully , please proceed further !!");
						$("#fldvPinName").val(JsonReturn.fldvPinName);
						$("#fldiProdPV").val(JsonReturn.fldiProdPV);
						$("#fldiPrice").val(JsonReturn.fldiPrice);
						$("#fldiPinId").val(JsonReturn.fldiPinId);
						$("#fldiTypeId").val(JsonReturn.fldiTypeId);
					break;
					default:
						$("#ajaxMessage").addClass("warning");
						$("#ajaxMessage").html("!! Invalid details !!");
				}
			}
		});
	}
}