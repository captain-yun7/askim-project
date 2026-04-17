<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";

$sql = "SELECT * FROM wiz_schedule_main WHERE schdate = '{$date}' ";
$result = query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../manage/wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/twcenter/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
function sch_process(){

	$.id_schmemo = $("#id_schmemo").val();
	if($.id_schmemo == ''){
		alert("일정내용을 적어주세요.");
		return false;
	} else {

		var param = $("#frm").serialize();

		$.ajax({
			url: 'main_sch_process.php',
			type: 'POST',
			dataType: 'json',
			data: param,
			timeout: 10000,
			success: function(data) {
				if (data.result == '00') {
					alert(data.msg);
					window.parent.opener.document.location.hash = "#mainReload";
					window.parent.opener.document.location.reload(true);
					window.self.close();
				}
			},
			error: function(data) {
				console.log(data);
			}
		});
		
	}

}

$(function() {

	$('#itemAdd').click(function(){

		var html = '';

		html += '<tr>';
		html +=     '<td style="padding:3px" align="center">';
		html +=     '시작시간 : <select name="stime[]" class="select"><option value="">시작시간</option><? for($i=0; $i<24; $i++){$i = (strlen($i) == 1) ? "0".$i : $i; ?><option value="<?=$i?>"><?=$i?>시</option><? } ?></select>&nbsp;&nbsp;';
		html +=     '종료시간 : <select name="etime[]" class="select"><option value="">종료시간</option><? for($i=0; $i<24; $i++){$i = (strlen($i) == 1) ? "0".$i : $i; ?><option value="<?=$i?>"><?=$i?>시</option><? } ?></select>&nbsp;&nbsp;일정담당자 : <input type="text" name="schname[]" class="input"> <input type="button" name="delRow" value="삭제" class="mana_memo_de delRow"><br>';
		html += '<textarea name="schmemo[]" id="id_schmemo" rows="5" cols="100%" class="textarea"></textarea></td>';
		html += '</tr>';

		$('#AddOption').append(html);

		$('.delRow').click(function(){
			$(this).parent().parent().remove();
		});
		
	});

	$('.delRow').click(function(){
		$(this).parent().parent().remove();
	});

});

</script>
</head>
<body>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td></td>
	</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../manage/image/ic_tit.gif"></td>
		<td valign="bottom" class="tit"><?=$date?> 일정추가</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt"><?=$date?> 일정등록 및 수정이 가능합니다.</td>
	</tr>
</table>
<br>
<input type="button" value="일정추가" class="mana_memo_ac" id="itemAdd">
<form name="frm" id="frm" method="post">
<input type="hidden" name="schdate" value="<?=$date?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="t_style" id="AddOption">
<?
while($_data = sql_fetch_arr($result)){
?>
	<tr>
		<td style="padding:3px">
			시작시간 : 
			<select name="stime[]" class="select">
			<option value="">시작시간</option>
			<?
			for($i=0; $i<24; $i++){
				$i = (strlen($i) == 1) ? "0".$i : $i;
			?>
			<option value="<?=$i?>" <? if($_data['stime'] == $i) echo "selected"?>><?=$i?>시</option>
			<?
			}
			?>
			</select>&nbsp;&nbsp;
			종료시간 : 
			<select name="etime[]" class="select">
			<option value="">종료시간</option>
			<?
			for($i=0; $i<24; $i++){
				$i = (strlen($i) == 1) ? "0".$i : $i;
			?>
			<option value="<?=$i?>" <? if($_data['etime'] == $i) echo "selected"?>><?=$i?>시</option>
			<?
			}
			?>
			</select>&nbsp;&nbsp;
			일정담당자 : <input type="text" name="schname[]" class="input" value="<?=$_data['twcentername']?>"><br>
			<textarea name="schmemo[]" id="id_schmemo" rows="5" cols="100%" class="textarea"><?=$_data['content']?></textarea> <input type="button" name="delRow" value="삭제" class="button_s delRow">
		</td>
	</tr>
<?
}
?>
</table>
<br>
<table width="100%" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td colspan="2" align="center">
			<img src="../manage/image/btn_confirm_l.gif" style="cursor:pointer" onclick="sch_process()">
			<img src="../manage/image/btn_close_l.gif" style="cursor:pointer" onClick="self.close();">
		</td>
	</tr>
</table>
</form>

</body>
</html>