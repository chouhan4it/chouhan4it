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
		var DynRows = '<tr><td align="left"><input name="post_title[]" type="text" class="col-xs-10 col-sm-12 prod_search" id="post_title'+NewRow+'" ref="'+NewRow+'" /><input name="post_id[]" type="hidden" class="post_id_class"  id="post_id'+NewRow+'" ref="'+NewRow+'"/></td><td align="left"><select class="col-xs-12 col-sm-12 stock_balance" id="post_attribute_id'+NewRow+'" name="post_attribute_id[]" ref="'+NewRow+'" ><option value="">select attribute</option> </select></td><td align="left"><input name="available_qty[]" readonly="true"  type="text" class="col-xs-12 col-sm-12" id="available_qty'+NewRow+'" ref="'+NewRow+'" /></td><td align="left"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span><input name="post_price[]" type="text" class="col-xs-12 col-sm-12" id="post_price'+NewRow+'" ref="'+NewRow+'" readonly="true" placeholder="MRP Price"/> </div></td><td align="left"><input name="post_qty[]" type="text" class="col-xs-12 col-sm-12 CalcTotal checkQuantity" id="post_qty'+NewRow+'" ref="'+NewRow+'" onkeypress="return blockNonNumbers(this, event, false, false);" onkeyup="extractNumber(this,2,false);"/></td><td><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span><input name="post_dp_price[]" type="text" class="col-xs-12 col-sm-12 CalcTotal" id="post_dp_price'+NewRow+'" ref="'+NewRow+'" placeholder="Price"/> </div></td><td align="left"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span><input name="post_sum_price[]" type="text" class="col-xs-12 col-sm-12" placeholder="Total Price" id="post_sum_price'+NewRow+'" ref="'+NewRow+'" readonly="true"/> </div></td></tr>';
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
	
	$(document).on("click", 'input.prod_search', function(event) { 
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
	
	$(document).on("blur", 'input.prod_search', function(event) { 
		ref_no = $(this).attr("ref");
		q = encodeURIComponent($(this).attr("value"));
		var franchisee_id = encodeURIComponent($("#franchisee_id_to").attr("value"));
		var URL_LOAD = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=PRODUCT_SINGLE&q="+q+"&franchisee_id="+franchisee_id;
		$.getJSON(URL_LOAD, function(JsonVal){
			if(JsonVal){
				$("#post_id"+ref_no).val(JsonVal.post_id);
				$("#available_qty"+ref_no).val(JsonVal.available_qty);
				$("#post_price"+ref_no).val(JsonVal.post_price);
				$("#post_pv"+ref_no).val(JsonVal.post_pv);
				$("#post_dp_price"+ref_no).val(JsonVal.post_dp_price);
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
	
	$(document).on("blur", 'select.stock_balance', function(event) { 
		ref_no = $(this).attr("ref");
		post_attribute_id = parseInt($(this).attr("value"));
		post_id =  parseInt($("#post_id"+ref_no).val());
		var URL_LOAD = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=PRODUCT_AVL_QTY&post_id="+post_id+"&post_attribute_id="+post_attribute_id;
		$.getJSON(URL_LOAD, function(JsonVal){
			if(JsonVal){
				$("#available_qty"+ref_no).val(JsonVal.available_qty);
			}
		});
	});
	
	$(document).on("blur", 'input.CalcTotal', function(event) { 
		iRowId = $(this).attr("ref");
		post_qty = parseInt($("#post_qty"+iRowId).val());
		post_dp_price = parseFloat($("#post_dp_price"+iRowId).val());
		if(post_qty>0 && post_dp_price>0){
			post_sum_price = (parseInt(post_qty) * parseFloat(post_dp_price)).toFixed(2);
			$("#post_sum_price"+iRowId).val(post_sum_price);
			$("#NetCalcTotal").trigger('click');
		}
	});
	
	$(document).on("blur", 'input.checkQuantity', function(event) { 
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
			var Cnfrm = confirm("Are you sure want to add this product into stock?");
			if(Cnfrm){return true;}
			else{return false;}
		}
	});
	
	$('#NetCalcTotal').click(function() {
		var net_payable = $('#net_payable');
		var net_post_pv = $('#net_post_pv');
		var iSubTtlPrice=0;
		var iSubTtlPostPv = 0;
		var iNetPayble="";
		var iNetPostPv="";
		
		$("input.post_id_class").each(function(i){
			var CurrRef = $(this).attr("ref");
			var post_id = $(this).attr("value");		
			if(trim(post_id) != ""){
				iSubTtlPrice += parseFloat($('#post_sum_price'+CurrRef).val());
				iSubTtlPostPv += parseFloat($('#post_qty'+CurrRef).val())*parseFloat($('#post_pv'+CurrRef).val());
			}
		});
		iNetPayble = iSubTtlPrice;
		iNetPostPv = iSubTtlPostPv;
		net_payable.val(iNetPayble);
		net_post_pv.val(iNetPostPv);
	});

});
</script>