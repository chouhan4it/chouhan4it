<script language="javascript" type="text/javascript">
function ValidateForm(){
	with(document.formstock){
		if(trim(member_id.value) == ""){
			alert("Please enter member name. !!!");
			member_id.focus();
			return false;
		}
		
		if(trim(order_date.value) == ""){
			alert("Sorry, enter order date !!!");
			order_date.focus();
			return false;
		}
		
		if(trim(net_payable.value) == ""){
			alert("Please calculate the order, then submit");
			return false;
		}
		if(parseFloat(trim(net_payable.value)) <= 0){
			alert("Please calculate the order, then submit");
			net_payable.focus();
			return false;
		}
	}
	
}

$(function(){
	
	
	$("input.CalcTotal").live('blur', function () {
		iRowId = $(this).attr("ref");
		post_id = parseInt($("#post_id"+iRowId).val());
		post_qty = parseInt($("#post_qty"+iRowId).val());
		available_qty = parseInt($("#available_qty"+iRowId).val());
		post_price = parseFloat($("#post_price"+iRowId).val());
		post_pv = parseFloat($("#post_pv"+iRowId).val());
		if(post_qty>0){
			if(available_qty>post_qty){
				post_sum_price = (parseInt(post_qty) * parseFloat(post_price)).toFixed(2);
				post_sum_pv = (parseInt(post_qty) * parseFloat(post_pv)).toFixed(2);
				$("#post_sum_price"+iRowId).val(post_sum_price);
				$("#post_sum_pv"+iRowId).val(post_sum_pv);
				$("#NetCalcTotal").trigger('click');
			}else{
				alert("Quantity not available, Please check available quantity ");
				$("#post_sum_price"+iRowId).val(0);
				$("#post_qty"+iRowId).val(0);
				$("#NetCalcTotal").trigger('click');
			}
		}
		
	});
	
	$("input.CalcTotalReturn").live('blur', function () {
		iRowId = $(this).attr("ref");
		post_id = parseInt($("#post_id"+iRowId).val());
		post_qty = parseInt($("#post_qty"+iRowId).val());
		post_price = parseFloat($("#post_price"+iRowId).val());
		post_pv = parseFloat($("#post_pv"+iRowId).val());
		stock_id = parseFloat($("#stock_id"+iRowId).val());
		if(post_qty>0){
			post_sum_price = (parseInt(post_qty) * parseFloat(post_price)).toFixed(2);
			post_sum_pv = (parseInt(post_qty) * parseFloat(post_pv)).toFixed(2);
			$("#post_sum_price"+iRowId).val(post_sum_price);
			$("#post_sum_pv"+iRowId).val(post_sum_pv);
			$("#NetCalcTotal").trigger('click');
		}else{
			alert("Please enter valid qty?");
			$("#post_qty"+iRowId).val(0);
			return false;
		}
		
	});
	
	$('#NetCalcTotal').click(function() {
		var net_payable = $('#net_payable');
		var net_pv = $('#net_pv');
		var iSubTtlPrice=0;
		var iSubTtlPv=0;
		var iNetPayble="";
		var iNetPvPayble="";
		
		$("input.post_id_class").each(function(i){
			var CurrRef = $(this).attr("ref");
			var post_id = $(this).attr("value");
			if(trim(post_id) != ""){
				var post_sum_price = ($('#post_sum_price'+CurrRef).val()>0)? $('#post_sum_price'+CurrRef).val():0;
				var post_sum_pv = ($('#post_sum_pv'+CurrRef).val()>0)? $('#post_sum_pv'+CurrRef).val():0;
				iSubTtlPrice += parseFloat(post_sum_price);
				iSubTtlPv += parseFloat(post_sum_pv);
			}
		});
		
		iNetPayble = iSubTtlPrice;
		iNetPvPayble = iSubTtlPv;
		
		net_payable.val(iNetPayble);
		net_pv.val(iNetPvPayble);
	});

});
</script>