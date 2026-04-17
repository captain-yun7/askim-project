<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once "../../inc/twcenter_check.php";

$workType = "I";

if($idx){
	$sql = " select * from wiz_holiday where idx = '".$idx."' ";
	$row = sql_fetch($sql);
	$workType = "M";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/twcenter/js/jquery-1.8.3.min.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
function inputCheck(frm) {
	if(frm.holiday_date.value == ''){
		alert("날짜를 선택하시기 바랍니다.");
		frm.holiday_date.focus();
		return false;
	}

	if(frm.holiday_name.value == '') {
		alert("일정명을 입력해주세요.");
		frm.holiday_name.focus();
		return false;
	}
}

function holiday_proc(workType, idx){
	switch(workType){
		case "edit":
			location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?code=<?php echo $code; ?>&idx="+idx;
			break;
		case "del":
			location.href = "./holiday_save.php?workType=D&code=<?php echo $code; ?>&idx="+idx;
			break;
	}
}

//-->
</script>
</head>
<body>
<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/twcenter/lib/datepicker_lib.php";
?>
<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">공휴일 관리</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<table width="100%" border="0" cellspacing="0" cellpadding="6" align="center">
	<tr>
		<td align="center">
			<form name="regForm" action="./holiday_save.php" method="post" onsubmit="return inputCheck(this)">
			<input type="hidden" name="workType" id="workType" value="<?php echo $workType; ?>">
			<input type="hidden" name="idx" id="idx" value="<?php echo $row["idx"]; ?>">
			<table width="99%" cellspacing="1" cellpadding="3" border="0" class="t_style">
				<tr>
					<td width="15%" class="t_name" style="text-align:center"> 추가 </td>
					<td>
						<input type="text" name="holiday_date" id="holiday_date" value="<?php echo $row["holiday_date"]; ?>" class="input datepicker" autocomplete="off"><br>
						<select name="holiday_type" id="holiday_type" class="select">
							<!-- <option value="휴관">휴관</option> -->
							<option value="공휴일" <?php if($row["holiday_type"] == "공휴일") echo "selected"; ?>>공휴일</option>
						</select>
						<input type="text" name="holiday_name" id="holiday_name" value="<?php echo $row["holiday_name"]; ?>" placeholder="공휴일명" class="input">
						<input type="submit" value="등록" class="btnListchk3">
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
<Br>
<table width="99%" border="0" cellspacing="0" cellpadding="0" align='center'>
	<form>
	<tr><td class="t_rd" colspan="20"></td></tr>
	<tr class="t_th">
		<th width="25%">날짜</th>
		<th width="20%">구분</th>
		<th>공휴일명</th>
		<th width="20%">기능</th>
	</tr>
	<tr><td class="t_rd" colspan="10"></td></tr>
	<?php
	if($s_year) $where_sql = " where years = '$s_year'";
	$sql = "select * from wiz_holiday $where_sql order by holiday_date desc ";
	$res = query($sql);
	while($row = sql_fetch_arr($res)) {
	?>
	<tr height=30>
		<td align="center"><?=$row["holiday_date"]?></td>
		<td align="center"><?=$row["holiday_type"]?></td>
		<td align="center"><?=$row["holiday_name"]?></td>
		<td align="center">
		<img src="../image/btn_edit_s.gif" style="cursor:hand" onclick="holiday_proc('edit', '<?=$row["idx"]?>');">
		<img src="../image/btn_delete_s.gif" style="cursor:hand" onclick="holiday_proc('del', '<?=$row["idx"]?>');">
		</td>
		<tr><td colspan="20" class="t_line"></td></tr>
	</tr>
	<?php
	}
	?>
</table>