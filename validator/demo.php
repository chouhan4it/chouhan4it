<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="images/fav.ico" type="icon" />

<!--Validator-->
<link rel="stylesheet" href="validator/validationEngine.jquery.css" type="text/css"/>
<script src="validator/jquery-1.8.2.min.js" type="text/javascript"></script>
<script src="validator/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="../validator/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!--Validator-->
<script>
$(function() { 
	$("#frmupdt").validationEngine();
});
</script>
</head>
<body style="padding:0; margin:0; background-color:#FFFFFF; min-height:1000px" onload="window.parent.scroll(0,0)">
<form method="post" name="frmupdt" id="frmupdt" autocomplete="off">
			<table width="90%" border="0" cellpadding="5" cellspacing="0" class="tblbdr2">
			
			  <tr class="header">
			    <td colspan="4" align="left">Rank Setup </td>
			    </tr>
			  <tr>
				<td width="19%" align="right" class="cmntext">Rank Name </td>
				<td width="23%" align="left"><input name="fldvRankName" id="fldvRankName" type="text" class="validate[required]" size="30" value="<?php echo $fldvRankName;?>"></td>
			    <td width="29%" align="left">&nbsp;</td>
			    <td width="29%" align="left">&nbsp;</td>
			  </tr>
			  <tr>
				<td align="right" class="cmntext">Direct Commission </td>
				<td align="left"><input name="fldiDrctCmsn" id="fldiDrctCmsn" type="text" class="validate[required,custom[number]]" size="30" value="<?php echo $fldiDrctCmsn;?>"></td>
			    <td align="right" class="cmntext">Calculation Percent </td>
			    <td align="left"><input name="fldcCalcPrcnt" id="fldcCalcPrcnt" type="text" class="validate[required,custom[number]]" size="30" value="<?php echo $fldcCalcPrcnt;?>" /></td>
			  </tr>
			  <tr>
				<td align="right" class="cmntext">Promotion Target </td>
				<td align="left"><input name="fldiPromAmt" id="fldiPromAmt" type="text" class="validate[required,custom[number]]" size="30" value="<?php echo $fldiPromAmt;?>" /></td>
			    <td align="left">&nbsp;</td>
			    <td align="left">&nbsp;</td>
			  </tr>
			  <tr>
                <td align="right" class="cmntext">Allowance(%)</td>
			    <td align="left"><input name="fldiAlwnPrcnt" id="fldiAlwnPrcnt" type="text" class="validate[required,custom[number]]" size="30" value="<?php echo $fldiAlwnPrcnt;?>" /></td>
			    <td align="right" class="cmntext">Allowance<br />
			      (Net % Amt / 6 Months)</td>
			    <td align="left"><input name="fldiAlwnAmt" id="fldiAlwnAmt" type="text" class="validate[required,custom[number]]" size="30" value="<?php echo $fldiAlwnAmt;?>" /></td>
			  </tr>
			  <tr>
				<td align="right" class="cmntext">Bonus(%)</td>
				<td align="left"><input name="fldiBonusPrcnt" id="fldiBonusPrcnt" type="text" class="validate[required,custom[number]]" size="30" value="<?php echo $fldiBonusPrcnt;?>" /></td>
			    <td align="right" class="cmntext">Bonus</td>
			    <td align="left"><input name="fldiBonusAmt" id="fldiBonusAmt" type="text" class="validate[required,custom[number]]" size="30" value="<?php echo $fldiBonusAmt;?>" /></td>
			  </tr>
			  <tr>
				<td align="right" class="cmntext">Reward Business </td>
				<td align="left"><input name="fldiRwrdBsns" id="fldiRwrdBsns" type="text" class="validate[required,custom[number]]" size="30" value="<?php echo $fldiRwrdBsns;?>" /></td>
			    <td align="right" class="cmntext">Reward(%)</td>
			    <td align="left"><input name="fldiRwrdPrcnt" id="fldiRwrdPrcnt" type="text" class="validate[required,custom[number]]" size="30" value="<?php echo $fldiRwrdPrcnt;?>" /></td>
			  </tr>
			  <tr>
			    <td align="right" class="cmntext">Reward</td>
			    <td align="left"><input name="fldvRwrdName" id="fldvRwrdName" type="text" class="validate[required]" size="30" value="<?php echo $fldvRwrdName;?>" /></td>
                <td align="left">&nbsp;</td>
			    <td align="left">&nbsp;</td>
			  </tr>
			  <tr>
				<td align="right" class="cmntext">Total Distribution </td>
				<td align="left"><input name="fldiTtlPrcnt" id="fldiTtlPrcnt" type="text" class="validate[required,custom[number]]" size="30" value="<?php echo $fldiTtlPrcnt;?>" /></td>
			    <td align="left">&nbsp;</td>
			    <td align="left">&nbsp;</td>
			  </tr>
			  <tr>
				<td colspan="4" align="center" class="context">
				  <input type="hidden" name="fldiRankId" id="fldiRankId" value="<?php echo $fldiRankId;?>">
				  <input type="hidden" name="FAction" id="FAction" value="<?php echo $FAction;?>">
				  <input name="Submit" type="submit" class="btn_new_admin" value="Submit" >
				  <input name="Reset" type="reset" class="btn_new_admin" value="Reset" >
				  <input name="Cancel" type="button" class="btn_new_admin" id="Cancel" value="Cancel" onClick="window.location='?page=<?php echo $Page;?>'" >					  </td>
				</tr>
			</table>
			</form>
		</body>
</html>
