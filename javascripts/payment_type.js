$(function() { 
	$(".CSCHCQ").change(SetCSCQDDFields);
	
});

function SetCSCQDDFields(){
	var fldvPmtBy = $(".CSCHCQ").val();
	$(".CQDDDTLS").val("");
	switch(fldvPmtBy){
		case "CS":
			$(".CQDDDTLS").attr("disabled","disabled");
		break;
		case "":
		case "CQ":
		case "DD":
			$('.CQDDDTLS').removeAttr("disabled");
		break;
	}
}