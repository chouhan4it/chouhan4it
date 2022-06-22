<script language="javascript" type="text/javascript">
function ValidateForm(){
	with(document.formstock){
		if(trim(franchisee_id_to.value) == ""){
			alert("Please enter franchisee name. !!!");
			franchisee_id_to.focus();
			return false;
		}
		if(trim(franchisee_name_to.value) == ""){
			alert("Sorry, franchisee name.  !!!");
			franchisee_name_to.focus();
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
		var DynRows = '<tr><td align="left"><input name="post_title[]" type="text" class="col-xs-10 col-sm-12 prod_search" id="post_title'+NewRow+'" ref="'+NewRow+'" /><input name="post_id[]" type="hidden" class="post_id_class"  id="post_id'+NewRow+'" ref="'+NewRow+'"/></td><td align="left"><select class="col-xs-12 col-sm-12" id="post_attribute_id'+NewRow+'" name="post_attribute_id[]" ><option value="">select attribute</option> </select></td><td align="left"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span><input name="post_mrp[]" type="text" class="col-xs-12 col-sm-12" id="post_mrp'+NewRow+'" ref="'+NewRow+'" readonly="true" placeholder="MRP Price"/> </div></td><td align="left"><input name="post_qty[]" type="text" class="col-xs-6 col-sm-6 CalcTotal checkQuantity" id="post_qty'+NewRow+'" ref="'+NewRow+'" onkeypress="return blockNonNumbers(this, event, false, false);" onkeyup="extractNumber(this,2,false);"/></td><td><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span><input name="post_price[]" type="text" class="col-xs-12 col-sm-12 CalcTotal" id="post_price'+NewRow+'" ref="'+NewRow+'" placeholder="DP Price"/> </div></td><td align="left"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span><input name="post_sum_price[]" type="text" class="col-xs-12 col-sm-12" placeholder="Total Price" id="post_sum_price'+NewRow+'" ref="'+NewRow+'" readonly="true"/> </div></td></tr>';
		$("#tblProdDtls > tbody:last").append(DynRows);
		ScrollDiv("tblProdBdy");
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
    	$(this).autocomplete("<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=PRODUCT",{
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
		min_sts : $("#min_sts").val();
		ref_no = $(this).attr("ref");
		q = encodeURIComponent($(this).attr("value"));
		var URL_LOAD = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=PRODUCT_SINGLE&q="+q;
		$.getJSON(URL_LOAD, function(JsonVal){
			if(JsonVal){
				$("#post_id"+ref_no).val(JsonVal.post_id);
				$("#post_mrp"+ref_no).val(JsonVal.post_mrp);
				if(min_sts>0){
					$("#post_price"+ref_no).val(JsonVal.post_price);
				}else{
					$("#post_price"+ref_no).val(JsonVal.post_mrp);
				}
				get_post_attribute(JsonVal.post_id,ref_no);
			}
		});
	});	
	
	function get_post_attribute(post_id,ref_no){
		if(post_id>0){
			var URL_LOAD = "<?php echo generateSeoUrl("json","jsonhandler"); ?>?switch_type=POST_ATTR&post_id="+post_id;
			$("#post_attribute_id"+ref_no).load(URL_LOAD);
		}
	}
	
	$("input.CalcTotal").live('blur', function () {
		iRowId = $(this).attr("ref");
		post_qty = parseInt($("#post_qty"+iRowId).val());
		post_price = parseFloat($("#post_price"+iRowId).val());
		if(post_qty>0 && post_price>0){
			post_sum_price = (parseInt(post_qty) * parseFloat(post_price)).toFixed(2);
			$("#post_sum_price"+iRowId).val(post_sum_price);
			$("#NetCalcTotal").trigger('click');
		}
	});
	
	$("input.checkQuantity").live('blur', function () {
		var iRowId = $(this).attr("ref");
		var post_qty = parseInt($("#post_qty"+iRowId).val());
		var available_qty = parseInt($("#available_qty"+iRowId).val());
		if(available_qty=='' || available_qty=='0'){
			alert("Quantity is not available?");
			$("#post_qty"+iRowId).val(0);
			return false;
		}
		if(post_qty>available_qty){
			alert("Invalid quantity, please check available qty?");
			$("#post_qty"+iRowId).val(0);
			return false;
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
			alert("Please Select Product Name !!!");
			return false;
		}else if(Ctrl>0){
			alert(MSG);
			return false;
		}else if($("#franchisee_id_to").val()==""){
			alert("Please enter franchisee name. !!!");
			return false;
			
		}else if($("#net_payable").val()>0){
			var Cnfrm = confirm("Are you sure want to place this order?");
			if(Cnfrm){return true;}
			else{return false;}
		}
	});
	
	$('#NetCalcTotal').click(function() {
		var net_payable = $('#net_payable');
		var iSubTtlPrice=0;
		var iNetPayble="";
		
		$("input.post_id_class").each(function(i){
			var CurrRef = $(this).attr("ref");
			var post_id = $(this).attr("value");		
			if(trim(post_id) != ""){
				iSubTtlPrice += parseFloat($('#post_sum_price'+CurrRef).val());
			}
		});
		iNetPayble = iSubTtlPrice;
		net_payable.val(iNetPayble);
	});

});
</script>