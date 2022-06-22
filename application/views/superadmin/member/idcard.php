<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$model = new OperationModel();
$segment = $this->uri->uri_to_assoc(2);
$member_id = _d(FCrtRplc($segment['member_id']));
$AR_MEM = $model->getMember($member_id);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Id Card</title>
<style>
@font-face {
    font-family: myFirstFont;
    src: url(../../../../setupimages/FtraBk_1.ttf);
}
body{ font-size:8.9px;  !important}
.wrapper{ width:259px; height:151px; margin:0 auto; background-color:#fefefel; background:url(../../../../setupimages/bg.png) 102% 60% no-repeat; font-family: myFirstFont;}
.header{ height:17px; background: #86A366;
background: -moz-linear-gradient(left, #86A366 0%, #86A366 100%);
background: -webkit-gradient(left top, right top, color-stop(0%, #86A366), color-stop(100%, #86A366));
background: -webkit-linear-gradient(left, #86A366 0%, #86A366 100%);
background: -o-linear-gradient(left, #86A366 0%, #86A366 100%);
background: -ms-linear-gradient(left, #86A366 0%, #86A366 100%);
background: linear-gradient(to right, #86A366 0%, #86A366 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#86A366', endColorstr='#86A366', GradientType=1 );}
.header h5{font-family:Verdana, Geneva, sans-serif; color:#fff; text-transform:uppercase; margin:0px; padding:0px; text-align:center; font-size:11px; line-height:16.5px}
.header h3{ color:#fff; text-transform:uppercase; margin:0px; padding:0px; text-align:center; font-size:9px; color:#FFF; line-height:15px; font-weight:lighter}

.logo{ background:url(../../../../setupimages/logo.png) center center no-repeat; width:166px; height:65px; background-size:80%; margin:5px auto}
.info-wrap{ width:259px; margin-bottom:4px }
.photo{ width:48px; height:51px; border:1px solid #999; float:left; margin-left:9px; margin-top:-6px !important}
.info{ width:180px; float:left; margin-left:7px;}
.clr{ clear:both}
</style>
<!--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://files.codepedia.info/files/uploads/iScripts/html2canvas.js"></script>
-->
</head>
<body>
<!--<a href="<?php echo generateSeoUrlAdmin("HtmlToPdf","pdf",array("member_id"=>_e($AR_MEM['member_id']))); ?>" tabindex="0" class="dt-button buttons-pdf buttons-flash btn btn-white btn-primary btn-bold"><span><i class="fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span></span> </a>-->
<div class="wrapper" id="html-content-holder">
<div class="header"><h5>IDENTITY CARD</h5></div>
<div class="logo"></div>
<div class="info-wrap">
<div class="photo" style="padding-bottom:2px;"><img class="editable img-responsive" src="<?php echo getMemberImage($AR_MEM['member_id']); ?>" width="48" height="51"><br><span style="padding-right:7px; font-size:7.2px; font-family: myFirstFont; color:#999; font-weight:bold;"><?php echo $AR_MEM['user_id']; ?></span></div>
<div class="info">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td colspan="2">Name: <span style="font-size:7.6px;"><?php echo ucwords($AR_MEM[first_name].' '.$AR_MEM[last_name]);?></span></td>
</tr>
<tr>
	<td colspan="2">Rank: <span style="font-size:7.6px;"><?php echo ucwords($AR_MEM['rank_name']);?></span></td>
</tr>
<tr>
	<td align="left" colspan="2"> <p style="margin-top:2px; margin-bottom:2px; padding-left:28px;">(Consultant Associate)</p></td>
</tr>
<tr>
    <td colspan="2">User Id  <span style="padding-left:4px;">:</span> <?php echo ($AR_MEM['user_id']); ?></td>
</tr>
<tr>
    <td colspan="2">Registered On : <?php echo DisplayDate($AR_MEM['date_join']); ?></td>
</tr>
</table>
</div>
<div class="clr"></div>
</div>
<div class="clr"></div>
<div class="header"><h3>SKILL &nbsp;&nbsp; EXPERIENCE &nbsp;&nbsp; TOWARDS &nbsp;&nbsp; SUCCESS</h3></div>
</div>
<!--<input id="btn-Preview-Image" type="button" value="Preview"/>
<div id="previewImage"></div>
<script>
$(document).ready(function(){
var element = $("#html-content-holder"); // global variable
var getCanvas; // global variable
    $("#btn-Preview-Image").on('click', function () {
         html2canvas(element, {
         onrendered: function (canvas) {
                $("#previewImage").append(canvas);
                getCanvas = canvas;
             }
         });
    });
	$("#btn-Convert-Html2Image").on('click', function () {
    var imgageData = getCanvas.toDataURL("image/png");
    // Now browser starts downloading it instead of just showing it
    var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
    $("#btn-Convert-Html2Image").attr("download", "your_pic_name.png").attr("href", newData);
	});
});
</script>-->
</body>
</html>