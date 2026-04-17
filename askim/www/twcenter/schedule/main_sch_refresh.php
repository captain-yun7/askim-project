<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";

?>
<span id="result">
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="t_style">
	<tr>
		<!-- <td height="20" align="center" class="t_name">시간</td>
		<td align="center" class="t_name">담당자</td> -->
		<td align="center" class="t_name">일정내용</td>
	</tr>
<?
$sql = "SELECT * FROM wiz_schedule_main WHERE schdate='{$date}' ";
$result = query($sql);
while($_data = sql_fetch_arr($result)){

?>
	<tr>
		<!-- <td></td>
		<td></td> -->
		<td class="t_value"><?=$_data['content']?></td>
	</tr>
<?
}
?>
</table>
</span>