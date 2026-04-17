<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/bbs_info.php"; ?>
<?
$page_name = "일정관리";
$page_desc = "일정을 추가/삭제/수정 합니다.";
$navi_name = " 일정관리 > 일정관리";

$param = "code=$code&category=$category&searchopt=$searchopt&searchkey=$searchkey";

$wiz_session['id']	= $wiz_admin['id'];
$wiz_session['name']	= $wiz_admin['name'];
$wiz_session['email'] = $wiz_admin['email'];
$wiz_session['level'] = 0;
?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="../wiz_style.css" rel="stylesheet" type="text/css">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<? include $_SERVER['DOCUMENT_ROOT'].'/twcenter/module/schedule.php'; // 일정관리 ?>
			</td>
		</tr>
	</table>
