<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/bbs_info.php"; ?>
<?
$page_name = "게시물관리";
$page_desc = "게시물을 관리합니다.";
$navi_name = " 게시판관리 > 게시물관리";

$param = "code=$code&category=$category&searchopt=$searchopt&searchkey=$searchkey";

$wiz_session['id']	= $wiz_admin['id'];
$wiz_session['name']	= $wiz_admin['name'];
$wiz_session['email'] = $wiz_admin['email'];
$wiz_session['level'] = 0;
?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<? include $_SERVER['DOCUMENT_ROOT'].'/twcenter/module/bbs.php'; // 게시판 ?>
			</td>
		</tr>
	</table>
