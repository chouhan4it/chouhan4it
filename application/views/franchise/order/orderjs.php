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
		if(CrntRows<=100){
		var DynRows = '<tr><td>'+NewRow+'</td><td align="left"><input name="post_title[]" type="text" class="col-xs-10 col-sm-12 prod_search" id="post_title'+NewRow+'" ref="'+NewRow+'" /><input name="post_id[]" type="hidden" class="post_id_class"  id="post_id'+NewRow+'" ref="'+NewRow+'"/></td><td><select class="col-xs-12 col-sm-12 stock_balance" id="post_attribute_id'+NewRow+'" ref="'+NewRow+'" name="post_attribute_id[]" ><option value="">select attribute</option></select></td><td><input name="post_batch[]" type="text" class="col-xs-10 col-sm-12" id="post_batch'+NewRow+'" ref="'+NewRow+'" /></td><td><input name="available_qty[]" type="text" class="col-xs-10 col-sm-12" id="available_qty'+NewRow+'" ref="'+NewRow+'"  readonly="true"/></td><td align="left"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span><input name="post_price[]" type="text" class="col-xs-12 col-sm-12" id="post_price'+NewRow+'" ref="'+NewRow+'" readonly="true" placeholder="Price"/> <input name="post_pv[]" type="hidden"   id="post_pv'+NewRow+'" ref="'+NewRow+'"/><input name="post_qty_limit[]" type="hidden"   id="post_qty_limit'+NewRow+'" ref="'+NewRow+'"/></div></td><td align="left"><input name="post_qty[]" type="text" class="col-xs-8 col-sm-8 CalcTotal" id="post_qty'+NewRow+'" ref="'+NewRow+'" onkeypress="return blockNonNumbers(this, event, false, false);" onkeyup="extractNumber(this,2,false);"/></td><td align="left"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span><input name="post_sum_price[]" type="text" class="col-xs-12 col-sm-12" placeholder="Total Price" id="post_sum_price'+NewRow+'" ref="'+NewRow+'" readonly="true"/><input name="post_sum_pv[]" type="hidden"   id="post_sum_pv'+NewRow+'" ref="'+NewRow+'"/> </div></td></tr>';
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
		var order_type = $("#order_type").val();
		ref_no = $(this).attr("ref");
		q = encodeURIComponent($(this).attr("value"));
		var URL_LOAD = "<?php echo FRANCHISE_PATH; ?>json/jsonhandler?switch_type=PRODUCT_SINGLE&q="+q;
		$.getJSON(URL_LOAD, function(JsonVal){
			if(JsonVal){
				$("#post_id"+ref_no).val(JsonVal.post_id);
				$("#post_code"+ref_no).val(JsonVal.post_code);
				$("#post_size"+ref_no).val(JsonVal.post_size);
				$("#post_batch"+ref_no).val(JsonVal.post_batch);
				$("#post_qty_limit"+ref_no).val(JsonVal.post_qty_limit);
				var post_qty = 1;
				;
				$("#post_qty"+ref_no).val(post_qty);
				if(order_type=="F"){
					$("#post_price"+ref_no).val(JsonVal.post_mrp);
					$("#post_price"+ref_no).attr('readonly',true);
				}else{
					$("#post_price"+ref_no).val(JsonVal.post_price);   
					$("#post_price"+ref_no).attr('readonly',false);
				}
				$("#post_pv"+ref_no).val(JsonVal.post_pv);
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
	
	$("select.stock_balance").live('blur',function(){
		ref_no = $(this).attr("ref");
		post_attribute_id = parseInt($(this).attr("value"));
		post_id =  parseInt($("#post_id"+ref_no).val());
		var URL_LOAD = "<?php echo FRANCHISE_PATH; ?>json/jsonhandler?switch_type=PRODUCT_AVL_QTY&post_id="+post_id+"&post_attribute_id="+post_attribute_id;
		$.getJSON(URL_LOAD, function(JsonVal){
			if(JsonVal){
				$("#available_qty"+ref_no).val(JsonVal.available_qty);
			}
		});
	});
	
	$("input.CalcTotal").live('blur', function () {
		iRowId = $(this).attr("ref");
		post_id = parseInt($("#post_id"+iRowId).val());
		post_qty = parseInt($("#post_qty"+iRowId).val());
		available_qty = parseInt($("#available_qty"+iRowId).val());
		post_qty_limit =  parseInt($("#post_qty_limit"+iRowId).val());
		post_price = parseFloat($("#post_price"+iRowId).val());
		post_pv = parseFloat($("#post_pv"+iRowId).val());
		if(post_qty>0){
			if(available_qty>post_qty){
				if(post_qty_limit>=post_qty){
					post_sum_price = (parseInt(post_qty) * parseFloat(post_price)).toFixed(2);
					post_sum_pv = (parseInt(post_qty) * parseFloat(post_pv)).toFixed(2);
					$("#post_sum_price"+iRowId).val(post_sum_price);
					$("#post_sum_pv"+iRowId).val(post_sum_pv);
					$("#NetCalcTotal").trigger('click');
				}else{
					alert("Maximum " +post_qty_limit+ " quantity is allowed in single purchase");
					$("#post_sum_price"+iRowId).val(0);
					$("#post_qty"+iRowId).val(0);
					$("#NetCalcTotal").trigger('click');
				}
			}else if(post_qty>post_qty_limit){
				
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
						$("#post_sum_price"+iRowId).val(post_sum_price);
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

	$('#CheckFPV').click(function() {
		$("#submitOrder").attr('disabled',true);
		var member_id = $('#member_id').val();
		var member_name = $('#member_name').val();
		var net_payable = $('#net_payable').val();
		var fpv_no = $('#fpv_no').val();
		var fpv_value = $('#fpv_value').val();
		var net_balance = $('#net_balance').val();
		var net_pv = $('#net_pv').val();
		var offer_module = $('#offer_module').val();
		var post_cat = $('#post_cat').val();
		
		var post_id_array = $('input.post_id_class').map(function(){
            return this.value;
        }).get();
		
		if(offer_module=='' && post_cat==0){
			var URL_LOAD = "<?php echo generateSeoUrlFranchise("json","jsonhandler",""); ?>";
			var data = {
				switch_type : "CHECKFPV",
				fpv_no : fpv_no,
				post_id_array : post_id_array,
				member_id : member_id
			};
			$.getJSON(URL_LOAD,data,function(JsonEval){
				if(JsonEval){
					if(JsonEval.coupon_val>0 && JsonEval.cpn_valid>0){
						net_payable_post_discount = net_payable-JsonEval.coupon_val;
						if(net_payable_post_discount<0){net_payable_post_discount=0;}
						$("#fpv_value").val(JsonEval.coupon_val);
						$("#net_balance").val(net_payable_post_discount);
						$("#net_pv").val(0);
						$("#submitOrder").attr('disabled',false);
						document.getElementById('offer_module').disabled = true;
					}else{
						$("#fpv_no").val('');
						$("#fpv_value").val(0);
						$("#net_balance").val(net_payable);
						$("#net_pv").val(net_pv);
						$("#submitOrder").attr('disabled',false);
						document.getElementById('offer_module').disabled = false;
					}
				}else{
					$("#fpv_no").val('');
					$("#fpv_value").val(0);
					$("#net_balance").val(net_payable);
					$("#net_pv").val(net_pv);
					$("#submitOrder").attr('disabled',false);
					document.getElementById('offer_module').disabled = false;
				}
			});
		}else{
			$("#fpv_no").val('');
			$("#fpv_value").val(0);
			$("#net_balance").val(net_payable);
			$("#net_pv").val(net_pv);
			$("#submitOrder").attr('disabled',false);
		}
	});

	$('#TempFPV').click(function() {
		$("#submitOrder").attr('disabled',true);
		var member_id = $('#member_id').val();
		var member_name = $('#member_name').val();
		var net_payable = $('#net_payable').val();
		var fpv_no = $('#fpv_no').val();
		var fpv_value = $('#fpv_value').val();
		var net_balance = $('#net_balance').val();
		var net_pv = $('#net_pv').val();
		var offer_module = $('#offer_module').val();
		var post_cat = $('#post_cat').val();
		$("input.post_id_class").each(function(i){
			var CurrRef = $(this).attr("ref");
			var post_id = $(this).attr("value");
			if(trim(post_id) == 31){
				post_cat = 1;
			}
		});
		alert("Shaping Solitaires Products can not be purchased thru FPV. Please change the Product & Submit again.");
		if(offer_module=='' && post_cat==0){
			var URL_LOAD = "<?php echo generateSeoUrlFranchise("json","jsonhandler",""); ?>";
			var data = {
				switch_type : "CHECKFPV",
				fpv_no : fpv_no,
				member_id : member_id
			};
			$.getJSON(URL_LOAD,data,function(JsonEval){
				if(JsonEval){
					if(JsonEval.coupon_val>0){
						net_payable_post_discount = net_payable-JsonEval.coupon_val;
						if(net_payable_post_discount<0){net_payable_post_discount=0;}
						$("#fpv_value").val(JsonEval.coupon_val);
						$("#net_balance").val(net_payable_post_discount);
						$("#net_pv").val(0);
						$("#submitOrder").attr('disabled',false);
						document.getElementById('offer_module').disabled = true;
					}else{
						$("#fpv_no").val('');
						$("#fpv_value").val(0);
						$("#net_balance").val(net_payable);
						$("#net_pv").val(net_pv);
						$("#submitOrder").attr('disabled',false);
						document.getElementById('offer_module').disabled = false;
					}
				}else{
					$("#fpv_no").val('');
					$("#fpv_value").val(0);
					$("#net_balance").val(net_payable);
					$("#net_pv").val(net_pv);
					$("#submitOrder").attr('disabled',false);
					document.getElementById('offer_module').disabled = false;
				}
			});
		}else{
			$("#fpv_no").val('');
			$("#fpv_value").val(0);
			$("#net_balance").val(net_payable);
			$("#net_pv").val(net_pv);
			$("#submitOrder").attr('disabled',false);
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
		
		/*if(TtlTrs==CtrlAll){
			alert("Please select products name !!!");
			return false;
		}else */
	    if(Ctrl>0){
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
				iSubTtlPrice += parseFloat($('#post_sum_price'+CurrRef).val());
				iSubTtlPv += parseFloat($('#post_sum_pv'+CurrRef).val());
			}
		});
		
		iNetPayble = iSubTtlPrice;
		iNetPvPayble = iSubTtlPv;
		
		net_payable.val(iNetPayble);
		net_pv.val(iNetPvPayble);
		//check_offer(iNetPayble,iNetPvPayble);
	});
	
	function check_offer(total_amount,total_pv){
		$("#submitOrder").attr('disabled',true);
		var member_id = $("#member_id").val();
		var offer_module = $("#offer_module").val();
		   if(offer_module=='OPOF' || offer_module=='OPOF-T' || offer_module=='OPOF-U' || offer_module=='OPOF-M'){
				var post_id = $(".post_id_class").first().val();
			}
			if(total_amount>0){
				var URL_CHECK = "<?php echo generateSeoUrlFranchise("json","jsonhandler",""); ?>";
				var data = {
					switch_type : "GET_OFFER",
					member_id : member_id,
					offer_module : offer_module,
					post_id : post_id,
					total_pv : total_pv,
					total_amount : total_amount
				};
				$.getJSON(URL_CHECK,data,function(JsonEval){
					if(JsonEval){
						if(JsonEval.offer_id>0){
							$("#offer-section").slideDown(600);
							CrntRows = $("#tblofferdtls tr").length;
							var NewRow =parseInt(CrntRows);
					
							var DynRows = '<tr><td>1</td><td align="left">'+JsonEval.offer_title+'<input name="post_id[]" type="hidden" class="post_id_class"  id="post_id0" ref="0"/></td><td>'+JsonEval.offer_code+'</td><td>'+JsonEval.offer_pv+'</td><td>'+JsonEval.offer_bv+'</td><td>'+JsonEval.offer_price+'</td><td align="left"><input name="offer_id"  type="radio" class="offer_radio" id="offer_id0" ref="0" value="'+JsonEval.offer_id+'" /> Yes &nbsp;&nbsp; <input name="offer_id"  type="radio" class="offer_radio" id="offer_id0" ref="0" value="0" /> No </td></tr>';
							$('#tblofferdtls >tbody >tr:last').remove();
							$("#tblofferdtls > tbody:last").append(DynRows);
							ScrollDiv("tblOfferBdy");
							$("#submitOrder").attr('disabled',false);
						}else{
							$("#submitOrder").attr('disabled',false);
							$(".offer_radio").prop('checked', false);
							$("#offer-section").slideUp(600);
						}
					}else{
						$("#submitOrder").attr('disabled',false);
						$(".offer_radio").prop('checked', false);
						$("#offer-section").slideUp(600);
					}
				});
			}
	
	}
	
	

});
</script>