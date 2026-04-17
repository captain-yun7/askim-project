<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbs_info.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php"; ?>
<?

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

if(
	($mem_level == "0") ||																																		// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)	||	// 게시판관리자
	(!empty($wiz_admin['id']))																																	// 관리자
) {
} else {
	echo "<script>alert('관리자만 접근가능합니다.'); self.close();</script>";
	exit;
}
if($chgstat == ""){
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>진행상황 변경</title>
<link href="../manage/wiz_style.css" rel="stylesheet" type="text/css">
<script language="javascript">
<!--
function inputCheck(frm){
	if(!frm.chg_code.value) {
		alert("변경할 진행상황을 선택해주세요.");
		return false;
	} else {
		if(!confirm("진행상황을 변경하시겠습니까?")){
			return false;
		}
	}
}
-->
</script>
<body>
<table align="center" width="100%" border="0" cellspacing="10" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<form name="frm" action="<?=$PHP_SELF?>" method="post" onSubmit="return inputCheck(this)">
			<input type="hidden" name="code" value="<?=$code?>">
			<input type="hidden" name="selbbs" value="<?=$selbbs?>">
			<input type="hidden" name="chgstat" value="Y">
			
			  <tr>
			    <td>
			      <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
			        <tr>
			          <td width="100" height="10" align="center" class="t_name">진행상태</td>
			          <td class="t_value">			          
									
									<select name="status" class=select>
									    <option>:: 선택하세요 ::</option>
										<option value="1" <? if($row['status'] == "1") echo "selected"; ?>>접수</option>
										<option value="2" <? if($row['status'] == "2") echo "selected"; ?>>승인</option>
										<option value="3" <? if($row['status'] == "3") echo "selected"; ?>>취소</option>
										<option value="4" <? if($row['status'] == "4") echo "selected"; ?>>완료 </option>
									</select>
			          </td>
			        </tr>
			      </table>
			    </td>
			  </tr>
			</table><br>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td align="center"><input type="submit" value=" 변경 " class="sbtn"></td></tr>
			</form>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?
}else{
	
	$selarr = explode("|",$selbbs);
	
	for($ii = 0;$ii < count($selarr);$ii++){
		
		if($selarr[$ii] !=""){
			
			//$sql = "select * from wiz_bbs where idx='$selarr[$ii]'";
			//$result = query($sql) or error("sql error");
			//$row = sql_fetch_arr($result);
		

			$usql = "update wiz_bbs set status = '$status' where idx='$selarr[$ii]'";
			//echo $usql."<br>";
			query($usql) or error("sql error");
		}
	}
	
	echo "<script>alert('진행 상황이 변경되었습니다..');opener.document.location.reload();self.close();</script>";
}
?>