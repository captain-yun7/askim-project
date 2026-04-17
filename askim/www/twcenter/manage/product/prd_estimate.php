<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>
<?
// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "searchopt=$searchopt&keyword=$keyword&$menucodeParam";
//--------------------------------------------------------------------------------------------------

$code = "review";
include "../../inc/bbs_info.php";
?>



<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">제품후기</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">제품후기를 관리합니다.</td>
	</tr>
</table>
<br><br><br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="center">
			<? $code=$code; include_once "../bbs/bbs.php"; ?>
			<!-- <iframe name='bbs_frame' id="bbs_frame" src='bbs.php?code=<?=$code?>' style='width:100%;'  frameborder='0' scrolling='yes' onload='autoResize(this)'> </iframe> -->
			</td>
		</tr>
	</table>



<? include "../foot.php"; ?>