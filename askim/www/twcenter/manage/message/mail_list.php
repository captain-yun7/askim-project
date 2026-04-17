<?php 
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/site_info.php";
include "../head.php";

?>
<script language="javascript">
<!--
function delConfirm(code){
	
	if(confirm("정말 삭제하시겠습니까?")){
		document.location = "mail_save.php?mode=delete&code=" + code + "&<?php echo $menucodeParam?>";
	}
	
}
-->
</script>
			
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">메세지설정</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">상황별 메일/SMS발송 내용을 관리합니다.</td>
	</tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td colspan=20 class="t_rd"></td></tr>
		<tr class="t_th">
		<th width="8%">번호</th>
		<th width="15%">코드</th>
		<th>분류명</th>
		<th width="15%">SMS 발송여부(고객/관리자)</th>
		<th width="15%">메일 발송여부(고객/관리자)</th>
		<th width="10%">기능</th>
	</tr>
	<tr><td colspan=20 class="t_rd"></td></tr>
<?php
function getYesNo($yn){
	if($yn == "N" || $yn == "") return "<font color=red>발송안함</font>";
	else return "<font color=blue>발송함</font>";
}

$where = " where (1)";
if($menu_arr["PRODUCT"] == false) {
	$where .= " and type not in('ORD')";
}

if($menu_arr["MEMBER"] == false) {
	$where .= " and type not in('MEM')";
}

$sql = "
	select * 
	  from wiz_mailsms 
	  $where
	 order by wdate asc
";
$result = query($sql);
$total = sql_fetch_row($result);
$no = $total;
while($row = sql_fetch_obj($result)){

	$row->subject 		= stripslashes($row->subject);
	$row->sms_msg 		= stripslashes($row->sms_msg);
	$row->email_subj	= stripslashes($row->email_subj);
	$row->email_msg 	= stripslashes($row->email_msg);

	if(!empty($row->sms_msg)) $sms_msg = getYesNo($row->sms_send)."/".getYesNo($row->sms_oper);
	else                      $sms_msg = "";
?>
	<tr> 
		<td height="38" align="center"><?php echo $no?></td>
		<td align="center"><?php echo $row->code?></td>
		<td><a href="mail_input.php?mode=update&code=<?php echo $row->code?>"><?php echo $row->subject?></a></td>
		<td align="center"><?php echo $sms_msg?></td>
		<td align="center"><?php echo getYesNo($row->email_send)?> / <?php echo getYesNo($row->email_oper)?></td>
		<td align="center">
			<img src="../image/btn_edit_s.gif" style="cursor:pointer" onclick="document.location='mail_input.php?mode=update&code=<?php echo $row->code?>&<?php echo $menucodeParam?>';">
			<? if($row->type == "ADD") { ?>
			<img src="../image/btn_delete_s.gif" style="cursor:pointer" onclick="delConfirm('<?php echo $row->code?>');">
			<? } ?>
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?php
$no--;
}

if($total <= 0){
?>
	<tr><td height="30" colspan="10" align="center">등록된 메일이 없습니다.</td></tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?php
}
?>
</table>
<?php
if($wiz_admin['designer'] == "Y") {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td height="5"></td></tr>
	<tr>
		<td>
			<input type="button" value="메세지설정" class="btnListchk" onclick="document.location='mail_input.php?mode=insert&<?php echo $menucodeParam?>';">
		</td>
	</tr>
</table>
<?php
}
?>
<?php include "../foot.php"; ?>