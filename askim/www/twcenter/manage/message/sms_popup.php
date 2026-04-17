<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";

$selected_count = explode("|",$seluser);
if (count($selected_count) > 1) {
	if(isset($seluser)){
		$array_selected = explode("|",$seluser);
		$i=0;
		$hp="";
		while($array_selected[$i]){

			$tmp_id = $array_selected[$i];
			$_gdata = sql_fetch( "SELECT hphone FROM wiz_member WHERE id='{$tmp_id}' " );

				if(isset($_gdata['hphone'])){
					$hp .= $_gdata['hphone'].";";
				}

			$i++;
		}

	}

	$hp_cnt = count(explode(";",$hp));

} else if($sms_u == "S") {
	$_gdata = sql_fetch( "SELECT hphone FROM wiz_member WHERE hphone='{$seluser}' " );
	if(isset($_gdata['hphone'])) $hp = $_gdata['hphone'].";";

} else {
	$_gdata = sql_fetch( "SELECT hphone FROM wiz_member WHERE id='{$seluser}' " );
	if(isset($_gdata['hphone'])) $hp = $_gdata['hphone'].";";
	$hp_cnt = (int)1;
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<title>:: SMS발송 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/comm/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript">
<!--
function inputCheck(frm){

	if(frm.strTelList.value == ""){
		alert("수신번호를 선택하세요.");
		frm.strTelList.focus();
		return false;
	}

	if(frm.se_num.value == ""){
		alert("발신번호를 입력하세요.");
		frm.se_num.focus();
		return false;
	}

	if(frm.message.value == ""){
		alert("메세지내용을 입력하세요");
		frm.message.focus();
		return false;
	}

	if(confirm('입력하신 내용으로 발송하시겠습니까?')) {
		var params = $('form').serialize();

		$.ajax({
			type: "POST"
			,url: "./sms_save.php"
			,dataType: "json"
			,data: params
			,success: function(data, textStatus, jqXHR) {
				if(data.result == "00"){
					alert(data.msg);
					self.close();
				} else {
					alert(data.msg);
					return false;
				}
			}
			,error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}
		});
		
	} else {
		return false;		
	}
}

$(function () {
	$('.remaining').each(function () {
		var $maxcount = $('.maxcount', this);
		var $count = $('.count', this);
		var $input = $("#message", this);

		var maximumByte = $maxcount.text() * 1;
		// update 함수는 keyup, paste, input 이벤트에서 호출한다.
		var update = function () {
			var before = $count.text() * 1;
			var str_len = $input.val().length;
			var cbyte = 0;
			var li_len = 0;
			for (i = 0; i < str_len; i++) {
				var ls_one_char = $input.val().charAt(i);
				if (escape(ls_one_char).length > 4) {
					cbyte += 2; //한글이면 2를더함
				} else {
					cbyte++;    //한글아니면 1을더함
				}
				if (cbyte <= maximumByte) {
					li_len = i + 1;
				}
			}

			if (parseInt(cbyte) > parseInt(maximumByte)) {
				var str = $input.val();
				var str2 = $input.val().substr(0, li_len);
				$input.val(str2);
				var cbyte = 0;
				for (i = 0; i < $input.val().length; i++) {
					var ls_one_char = $input.val().charAt(i);
					if (escape(ls_one_char).length > 4) {
						cbyte += 2; //한글이면 2를더함
					} else {
						cbyte++;    //한글아니면 1을더함
					}
				}
			}
			$count.text(cbyte);
		};
		$input.bind('input keyup keydown paste change', function () {
			setTimeout(update, 0)
		});
		update();
	});
});
//-->
</script>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">SMS발송</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<table width="100%" border="0" cellpadding=10 cellspacing=0>
  <tr>
	<td>
	  <form name="frm" id="frm" action="<?php echo WAY_GURL ?>" method="post">
	  <input type="hidden" name="mode" value="DirectSend">
	  <input type="hidden" name="se_name" value="<?php echo $site_info['site_name'] ?>">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr><td height="10"></td></tr>
		<tr>
		  <td align="right"><!-- <span class="title_msg">선택된 고객 : <strong id="total_prd_cnt"><?php echo $hp_cnt ?></strong>명</span>&nbsp;&nbsp;&nbsp; --></td>
		</tr>
	  </table>
	  <div class="sms_area">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
		  <td align="center" style="line-height:0; vertical-align:top;"><img src="../image/sms/sms_top.jpg"></td>
		</tr>
		<tr>
		  <td class="sms_bg">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			  <tr>
				<td align="center"><input type="text" class="sms_number" placeholder="수신번호" name="strTelList" value="<?php echo $hp ?>" readonly></td>
			  </tr>
			  <tr>
				<td align="center"><input type="text" class="sms_number" placeholder="발신번호" name="se_num" value="<?php echo $site_info['site_tel'] ?>"></td>
			  </tr>
			  <?php
			  if($site_info['sms_send_type'] == "L") {
			  ?>
			  <tr>
				<td align="center"><input type="text" class="sms_number" placeholder="발신제목" name="strSubject"></td>
			  </tr>
			  <?php } ?>
			  <tr>
				<td align="center" class="remaining"><textarea name="message" id="message" class="sms_text" placeholder="메시지를 입력해주세요" ></textarea>
				<p class="text-right">(<font color="#ff6600"><b><span class="count">0</span></b></font>/<span class="maxcount"><?php echo $sms_length; ?></span> Byte)</p>
				</td>
			  </tr>
			  <tr>
				<td align="center"><input type="button" class="base_btn5 sms" value="발송하기" onclick="inputCheck(this.form); return false;"></td>
			  </tr>
			  <tr><td height="10"></td></tr>
			</table>
		  </td>
		</tr>
		<tr>
		  <td align="center" style="line-height:0; vertical-align:top;"><img src="../image/sms/sms_foot.jpg"></td>
		</tr>
	  </table>
	  </div>
	  </form>
	</td>
  </tr>
</table>

</body>
</html>