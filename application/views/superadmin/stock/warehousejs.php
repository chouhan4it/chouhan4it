<script language="javascript" type="text/javascript">
function ValidateForm(){
	with(document.formstock){
		if(trim(order_no.value) == ""){
			alert("Please enter order no. !!!");
			order_no.focus();
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
		var DynRows = '<tr><td align="left"><input name="post_title[]" type="text" class="col-xs-10 col-sm-12 prod_search" id="post_title'+NewRow+'" ref="'+NewRow+'" /><input name="post_id[]" type="hidden" class="post_id_class"  id="post_id'+NewRow+'" ref="'+NewRow+'"/></td><td align="left"><select class="col-xs-12 col-sm-12" id="post_attribute_id'+NewRow+'" name="post_attribute_id[]" ><option value="">select attribute</option> </select></td><td align="left"><input maxlength="12" name="batch_no[]" type="text" placeholder="Batch No" class="col-xs-10 col-sm-12 validate[custom[onlyLetterNumber],minSize[6]]"  id="batch_no'+NewRow+'" ref="'+NewRow+'" /></td><td align="left"><select class="col-xs-12 col-sm-12" id="supplier_id" name="supplier_id[]" ><option value="">----select supplier----</option> <?php  DisplayCombo($_REQUEST['supplier_id'],"SUPPLIER");  ?></select></td><td align="left"><div class="input-group"><input class="form-control col-xs-10 col-sm-12"   placeholder="YYYY-DD-MM" name="mfg_date[]" id="mfg_date" value="" type="text"  /></div></td><td><div class="input-group"><input class="form-control col-xs-10 col-sm-12" name="exp_date[]" id="exp_date" value="" type="text"   placeholder="YYYY-DD-MM" /></div></td><td align="left"><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span><input name="post_price[]" type="text" class="col-xs-12 col-sm-12" id="post_price'+NewRow+'" ref="'+NewRow+'" placeholder="Price"/> </div></td><td><input name="post_qty[]" type="text" class="col-xs-12 col-sm-12 CalcTotal" id="post_qty'+NewRow+'" ref="'+NewRow+'" onkeypress="return blockNonNumbers(this, event, false, false);" onkeyup="extractNumber(this,2,false);"/></td><td><div class="input-group"> <span class="input-group-addon"><i class="fa fa-inr"></i></span><input name="post_sum_price[]" type="text" class="col-xs-12 col-sm-12" placeholder="Total Price" id="post_sum_price'+NewRow+'" ref="'+NewRow+'" readonly="true"/> </div></td></tr>';
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
		var URL_LOAD = "<?php echo ADMIN_PATH; ?>json/jsonhandler?switch_type=PRODUCT_SINGLE&q="+q;
		$.getJSON(URL_LOAD, function(JsonVal){
			if(JsonVal){
				$("#post_id"+ref_no).val(JsonVal.post_id);
				$("#post_price"+ref_no).val(JsonVal.post_price);
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
	
	$(document).on("blur", 'input.checkBatchNo', function(event) { 
		ref_no = $(this).attr("ref");
		q = encodeURIComponent($(this).attr("value"));
		var URL_LOAD = "<?php echo BASE_PATH; ?>json/jsonhandler?switch_type=CHECK_BATCH&q="+q;
		$.getJSON(URL_LOAD, function(JsonVal){
			if(JsonVal){
				if(JsonVal.row_ctrl>0){
					alert("This batch no is already exist , please enter another no");
					$("#batch_no"+ref_no).val('');
					return false;
				}
			}
		});
	});
	$(document).on("blur", 'input.CalcTotal', function(event) { 
		iRowId = $(this).attr("ref");
		post_qty = parseInt($("#post_qty"+iRowId).val());
		post_price = parseFloat($("#post_price"+iRowId).val());
		if(post_qty>0 && post_price>0){
			post_sum_price = (parseInt(post_qty) * parseFloat(post_price)).toFixed(2);
			$("#post_sum_price"+iRowId).val(post_sum_price);
			$("#NetCalcTotal").trigger('click');
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
				var supplier_id=$('#supplier_id'+CurrRef);
				var batch_no=$('#batch_no'+CurrRef);
				var post_price=$('#post_price'+CurrRef);

				var exp_date=$('#exp_date'+CurrRef);
				var mfg_date=$('#mfg_date'+CurrRef);
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
				if(batch_no.val()==""){
					MSG +="\nPlease enter batch no of product !!!";
					batch_no.focus();
					Ctrl++;
					return false;
				}
				if(supplier_id.val()==""){
					MSG +="\nPlease select supplier of product !!!";
					supplier_id.focus();
					Ctrl++;
					return false;
				}
				if(mfg_date.val()==""){
					MSG +="\nPlease enter manufacture date of Product !!!";
					mfg_date.focus();
					Ctrl++;
					return false;
				}
				if(exp_date.val()==""){
					MSG +="\nPlease enter expiry date of Product !!!";
					exp_date.focus();
					Ctrl++;
					return false;
				}
				
				if(post_qty.val()<1){
					MSG +="\nPlease enter Sale Quantity !!!";
					post_qty.focus();
					Ctrl++;
					return false;
				}
				
				if(post_price.val()=="" || post_price.val()==0){
					MSG +="\nPlease enter product price !!!";
					post_price.focus();
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
		}else if($("#net_payable").val()>0){
			var Cnfrm = confirm("Are you sure want to add this product into stock?");
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