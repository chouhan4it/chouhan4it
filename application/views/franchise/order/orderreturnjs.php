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
	$('img#add').click(function() {
		CrntRows = $("#tblProdDtls tr").length;
		var NewRow =parseInt(CrntRows+1);
		if(CrntRows<=17){
		var DynRows = '<tr><td>'+NewRow+'</td><td align="left"><input name="post_title[]" type="text" class="col-xs-10 col-sm-12 prod_search" id="post_title'+NewRow+'" ref="'+NewRow+'" /><input name="post_id[]" type="hidden" class="post_id_class"  id="post_id'+NewRow+'" ref="'+NewRow+'"/></td><td><input name="post_code[]" type="text" class="col-xs-10 col-sm-12" id="post_code'+NewRow+'" ref="'+NewRow+'" /></td><td><input name="post_size[]" type="text" class="col-xs-10 col-sm-12" id="post_size'+NewRow+'" ref="'+NewRow+'" /></td><td><input name="post_batch[]" type="text" class="col-xs-10 col-sm-12" id="post_batch'+NewRow+'" ref="'+NewRow+'" /></td><td><input name="available_qty[]" type="text" class="col-xs-10 col-sm-12" id="available_qty'+NewRow+'" ref="'+NewRow+'"  readonly="true"/></td><td align="left"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span><input name="post_price[]" type="text" class="col-xs-12 col-sm-12" id="post_price'+NewRow+'" ref="'+NewRow+'" readonly="true" placeholder="MRP Price"/> <input name="post_pv[]" type="hidden"   id="post_pv'+NewRow+'" ref="'+NewRow+'"/></div></td><td align="left"><input name="post_qty[]" type="text" class="col-xs-6 col-sm-6 CalcTotal" id="post_qty'+NewRow+'" ref="'+NewRow+'" onkeypress="return blockNonNumbers(this, event, false, false);" onkeyup="extractNumber(this,2,false);"/></td><td align="left"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span><input name="post_sum_price[]" type="text" class="col-xs-12 col-sm-12" placeholder="Total Price" id="post_sum_price'+NewRow+'" ref="'+NewRow+'" readonly="true"/><input name="post_sum_pv[]" type="hidden"   id="post_sum_pv'+NewRow+'" ref="'+NewRow+'"/> </div></td></tr>';
		$("#tblProdDtls > tbody:last").append(DynRows);
		ScrollDiv("tblProdBdy");
		}
	});
	
	$('img#remove').click(function() {
		CrntRows = $("#tblProdDtls tr").length;
		CrntRows = $('#tblProdDtls >tbody >tr').length;
		if(CrntRows>6){
			$('#tblProdDtls >tbody >tr:last').remove();
			ScrollDiv("tblProdBdy");
		}
	});
	
	$('input.prod_search').livequery(function() {
    	$(this).autocomplete("<?php echo FRANCHISE_PATH; ?>json/jsonhandler?switch_type=PRODUCT",{
			width: 260,
			matchContains: true,
			//mustMatch: true,
			minChars: 0,
			//multiple: true,
			highlight: false,
			//multipleSeparator: ",",
			selectFirst: true
		});
	});
	
	$("input.prod_search").live('blur', function(){
		ref_no = $(this).attr("ref");
		q = encodeURIComponent($(this).attr("value"));
		var URL_LOAD = "<?php echo FRANCHISE_PATH; ?>json/jsonhandler?switch_type=PRODUCT_SINGLE&q="+q;
		$.getJSON(URL_LOAD, function(JsonVal){
			if(JsonVal){
				$("#post_id"+ref_no).val(JsonVal.post_id);
				$("#post_code"+ref_no).val(JsonVal.post_code);
				$("#post_size"+ref_no).val(JsonVal.post_size);
				$("#post_batch"+ref_no).val(JsonVal.post_batch);
				$("#available_qty"+ref_no).val(JsonVal.available_qty);
				$("#post_price"+ref_no).val(JsonVal.post_price);
				$("#post_pv"+ref_no).val(JsonVal.post_pv);
				
			}
		});
	});	
	
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
		order_detail_id = parseFloat($("#order_detail_id"+iRowId).val());
		if(post_qty>0 && post_price>0 && order_detail_id>0){
			var URL_LOAD = "<?php echo generateSeoUrlFranchise("json","jsonhandler",""); ?>";
			var data = {
				switch_type : "RETURN_QTY",
				order_detail_id : order_detail_id,
				post_qty : post_qty
			};
			$.getJSON(URL_LOAD,data,function(JsonEval){
				if(JsonEval){
					if(JsonEval.post_qty>0){
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
				}else{
					alert("Please enter valid qty?");
					$("#post_qty"+iRowId).val(0);
					return false;
				}
			});
			
		}	
		
	});
	
	$('#formstock').submit(function() {
		var Ctrl=0;
		var CtrlAll=0;
		var MSG="";
		
		var TtlTrs = $('#tblProdDtls >tbody >tr').length;
		$("input.post_id_class").each(function(i){
			var CurrRef = $(this).attr("ref");
			var post_id = $(this).attr("value");
			if(trim(post_id) != ""){
				var post_title=$('#post_title'+CurrRef);
				var post_id=$('#post_id'+CurrRef);
				var post_qty=$('#post_qty'+CurrRef);
				
				if(post_title.val()==""){
					MSG +="\nPlease Select Product Name !!!";
					post_title.focus();
					Ctrl++;
					return false;
				}
				if(post_id.val()==""){
					MSG +="\nPlease Select Product Name !!!";
					post_title.focus();
					Ctrl++;
					return false;
				}
				if(post_qty.val()<1){
					MSG +="\nPlease enter Sale Quantity !!!";
					post_qty.focus();
					Ctrl++;
					return false;
				}
			}else{
				CtrlAll++;
			}
		});
		if(TtlTrs==CtrlAll){
			alert("Please select product name !!!");
			return false;
		}else if(Ctrl>0){
			alert(MSG);
			return false;
		}else if($("#member_id").val()==""){
			alert("Please enter valid member name. !!!");
			return false;
			
		}else if($("#net_payable").val()>0){
			var Cnfrm = confirm("Are you sure want to place this order?");
			if(Cnfrm){return true;}
			else{return false;}
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