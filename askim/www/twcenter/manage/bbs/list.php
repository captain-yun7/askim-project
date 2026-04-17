<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/bbs_info.php"; ?>
<?
$page_name = "게시물관리";
$page_desc = "게시물을 관리합니다.";
$navi_name = " 게시판관리 > 게시물관리";

$param = "code=$code&category=$category&searchopt=$searchopt&searchkey=$searchkey";

$wiz_session['id']		= $wiz_admin['id'];
$wiz_session['name']	= $wiz_admin['name'];
$wiz_session['email'] = $wiz_admin['email'];
$wiz_session['level'] = 0;
?>




<? include "../head.php"; ?>

<script language="javascript"> 
function autoResize(){ 
  document.getElementById("bbs_frame").style.height=bbs_frame.document.documentElement.scrollHeight; 
} 
</script>

<script type="text/javascript">
// iframe resize
function autoResize(i)
{
    var iframeHeight=
    (i).contentWindow.document.body.scrollHeight;
    (i).height=iframeHeight+20;
}
</script>

	<table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td><img src="../image/ic_tit.gif"></td>
      <td valign="bottom" class="tit"><?=$bbs_info['title']?></td>
      <td width="2"></td>
      <td valign="bottom" class="tit_alt">게시물을 관리합니다.</td>
    </tr>
  </table>

  <br><br><br>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="center">
			<? $code=$code; include_once "bbs.php"; ?>
			<!-- <iframe name='bbs_frame' id="bbs_frame" src='bbs.php?code=<?=$code?>' style='width:100%;'  frameborder='0' scrolling='yes' onload='autoResize(this)'> </iframe> -->
			</td>
		</tr>
	</table>

<? include "../foot.php"; ?>