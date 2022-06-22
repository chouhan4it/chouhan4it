<?php
session_start(0);
include("../incs/dbconnection.php");
include("../incs/inc_common.php");
include("check_session.php");
include("../captcha/securimage.php");

if($_REQUEST[fldiAgntId]!=""){
	$fldiAgntId = $_REQUEST[fldiAgntId];
	$Q_DTLS = "SELECT * FROM tbl_agents WHERE fldiAgntId='$fldiAgntId'";
	$AR_DTLS = ExecQ($Q_DTLS, 1);
	$fldiAgntId = $AR_DTLS[fldiAgntId];
}else{
	$fldiAgntId = $_REQUEST[fldiAgntId];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><TITLE><?php echo GetMISCCharges("fldvCmpName")?></TITLE>
<link href="../include/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../jquery/jquery-1.4.4.min.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript" src="../jquery/jquery.print.js"></script>
<script language="javascript" type="text/javascript">
$(function(){
	$( "#PrintNow" ).click(function(){$( "#Letter" ).print(); return( false );});
	$( "#GoBack" ).click(function(){window.location.href='print_welcome.php?'});
});
</script>
<style media="all">
.smalltxt{color:#5C5C5C;font-size:12px;font-family:Verdana;font-weight:normal;text-decoration:none; line-height:20px;}
.cat_link{font-family:Verdana, Arial, Helvetica, sans-serif;color:#FF6600; font-size:11px; font-weight:bold;}
.commontext{font-family:Trebuchet MS; font-size:11px; color:#000000; font-family:Verdana; line-height:15px;}
.style1 {color: #116CAE}
</style>
</HEAD>
<body style="padding:0; margin:0; background-color:#FFFFFF" onload="window.parent.scroll(0,0)">
<table width="820" border="0" align="center" cellpadding="4" cellspacing="0">
<tr>
  <td align="center">
  <table width="272" border="0" align="left" cellpadding="0" cellspacing="0">
  <tr>
    <td width="123"><input name="PrintNow" type="button" class="btn_new_admin" id="PrintNow" value="Print ID" /></td>
    <td width="149"><img src="../setupimages/button_back.gif" class="pointer" onclick="window.location.href='agent_print_id.php'" /></td>
  </tr>
</table>
<div style="clear:left">&nbsp;</div>
  <div id="Letter">
    	<img src="agent_id_print_img.php?fldiAgntId=<?php echo $fldiAgntId?>"  width="500" height="360" />
	<br />
  </div>
  </td></tr>
</table>	  	
</body>
</html>