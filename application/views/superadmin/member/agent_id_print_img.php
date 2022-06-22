<?php
session_start(0);
include("../incs/dbconnection.php");
include("../incs/inc_common.php");
//include("check_session.php");
$fldiAgntId = $_REQUEST[fldiAgntId];
$Q_DATA = "SELECT * FROM tbl_agents WHERE fldiAgntId='$fldiAgntId'";
$AR_DATA = ExecQ($Q_DATA,1);
$fldiAgntId = $AR_DATA[fldiAgntId];

$fldvFullName = GetAgentSingleDtls($AR_DATA[fldiAgntId],"fldvFullName");
$fldvAgntId = GetAgentSingleDtls($AR_DATA[fldiAgntId],"fldvAgntId");
$flddDOB = GetAgentSingleDtls($AR_DATA[fldiAgntId],"flddDOB");
$fldvAdrs1 = GetAgentSingleDtls($AR_DATA[fldiAgntId],"fldvPOAdrs");
$fldvPanNo = GetAgentSingleDtls($AR_DATA[fldiAgntId],"fldvPanNo");
$flddDOJ = GetAgentSingleDtls($AR_DATA[fldiAgntId],"flddDOJ");
$fldvMobile = GetAgentSingleDtls($AR_DATA[fldiAgntId],"fldvMobile");
$fldvRank = SelectTableWithOption("tbl_setup_agnt_rank","fldvRankName","fldiRankId='$AR_DATA[fldiRankId]'");
$fldvIntroName = SelectTableWithOption("tbl_agents","fldvFullName","fldiAgntId='$AR_DATA[fldiSpMId]'");
$fldvBranch = SelectTableWithOption("tbl_branch_mstr","fldvBranch","fldiBrnchId='$AR_DATA[fldiBrnchId]'");
$fldvCity = FCrtRplc($AR_DATA[fldvCity]);

if($AR_DATA[fldvPinCode]!=""){ $fldvAdrs2 .= ", PIN-".GetAgentSingleDtls($AR_DATA[fldiAgntId],"fldvPinCode"); }
$fldvAddress = $fldvAdrs1.$fldvAdrs2;

$image = imagecreatefromjpeg("id/agent_identity_card.jpg");
$color = imagecolorallocate($image, 2, 2, 2);
$font = 'fonts/verdanai.ttf';
$fontSize = "13";
$fontRotation = "0";

imagettftext($image, 10, $fontRotation, 145, 315, $color, $font, $fldvAgntId);
imagettftext($image, 10, $fontRotation, 145, 348, $color, $font, substr($fldvFullName,0,21));
imagettftext($image, 10, $fontRotation, 100, 388, $color, $font, $fldvRank);

imagettftext($image, 10, $fontRotation, 100, 432, $color, $font, $fldvMobile);
imagettftext($image, 10, $fontRotation, 480, 43, $color, $font, DisplayDate($flddDOJ));
imagettftext($image, 10, $fontRotation, 480, 72, $color, $font, DisplayDate($flddDOB));
imagettftext($image, 10, $fontRotation, 480, 103, $color, $font, $fldvBranch);
imagettftext($image, 10, $fontRotation, 480, 136, $color, $font, $fldvCity);

imagettftext($image, 10, $fontRotation, 480, 167, $color, $font, wordwrap($fldvAddress,27,"\n",true));
header("Content-Type: image/jpeg");
imagejpeg($image);
imagedestroy($image);
?>