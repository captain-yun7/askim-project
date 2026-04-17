<?php include_once "../../common.php"; ?>
<?php include_once "../../inc/form_info.php"; ?>
<?php
function createObject($no,$type,$size,$flist){

	global $upfile_idx;
	global $spam_check;
	global $norobot_key;

	$fname = "f".$no;

	// 반복하지 않는 속성
	$tmp_type = Array("","address","birthday","phone","email","name","tel","fax");

	for($ii = 0;$ii < count($tmp_type); $ii++) {
		if(!strcmp($type, $tmp_type[$ii])) $flist = " ";
	}
	$tmp_flist = explode("|",$flist);

	if($type == "select") $finput = "<select id='".$fname."_0' name='fname[".$no."][]' class='select'><option value=''>선택하세요</option>";

	for($ii=0;$ii<count($tmp_flist);$ii++){

		//if($tmp_flist[$ii] != ""){

		if($type == "text" || $type == "name"){

			$finput .= "<input type='text' id='".$fname."_$ii' name='fname[".$no."][]' class='input' size='".$size."'>".$tmp_flist[$ii];

		}else if($type == "file"){

			$upfile_idx++;
			$finput .= "<input type='file' id='".$fname."_$ii' name='upfile".$upfile_idx."' class='input' size='".$size."'>".$tmp_flist[$ii];

		}else if($type == "radio"){

			$finput .= "<input type='radio' id='".$fname."_$ii' name='fname[".$no."]' value='".$tmp_flist[$ii]."'>".$tmp_flist[$ii]."&nbsp; ";

		}else if($type == "checkbox"){

			$finput .= "<input type='checkbox' id='".$fname."_$ii' name='fname[".$no."][]' value='".$tmp_flist[$ii]."'>".$tmp_flist[$ii]."&nbsp; ";

		}else if($type == "textarea"){

			$finput .= "<textarea id='".$fname."_$ii' name='fname[".$no."][]' rows='".$size."' class='textarea' style='width:99%'></textarea>";

		}else if($type == "select"){

			$finput .= "<option value='".$tmp_flist[$ii]."'>".$tmp_flist[$ii]."</option>";

		}else if($type == "address"){

			$finput .= "<input type='text' id='".$fname."_0' name='".$fname."_post1' onClick=postSearch('".$fname."_'); class='input' size='6' readonly>";
			$finput .= "&nbsp;<img src='/twcenter/images/address_btn.gif' align='absmiddle' style='cursor:pointer' onClick=postSearch('".$fname."_')><br>";
			$finput .= "<input type='text' id='".$fname."_2' name='".$fname."_address1' class='input' size='".$size."'><br>";
			$finput .= "<input type='text' id='".$fname."_3' name='".$fname."_address2' class='input' size='".$size."'><br>";

		}else if($type == "pdate"){
			
			$finput .= "<input type='text' id='".$fname."_".$ii."' name='fname[".$no."][]' class='input' size='".$size."' readonly> ";

		?>
		<script type="text/javascript">
			$(function() {

				var calendar = {
					showOn: "both",
					buttonImage: "../image/cal2.png",
					buttonImageOnly: true,
					showButtonPanel: true, 
					dateFormat: "yy-mm-dd",
					currentText: '오늘', 
					closeText: '닫기', 
					changeMonth: true, 
					changeYear: true, 
					dayNames: ['일요일','월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
					dayNamesMin: ['<font color=red>일</font>', '월', '화', '수', '목', '금', '<font color=#0071bf>토</font>'], 
					monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
					monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
					yearRange: 'c-10:c'
				};

				$("#<?=$fname."_".$ii?>").datepicker(calendar);
				$('img.ui-datepicker-trigger').attr('align', 'absmiddle');

			});
		</script>
		<?

		}else if($type == "tdate"){

			$finput .= "<select id='".$fname."_".$ii."_0' name='fname[".$no."][]' class='select'><option value=''>--</option>";
			for($jj=2008;$jj<=2015;$jj++){
				$finput .= "<option value='".$jj."'>".$jj."</option>";
			}
			$finput .= "</select>년 ";

						$finput .= "<select id='".$fname."_".$ii."_1' name='fname[".$no."][]' class='select'><option value=''>--</option>";
			for($jj=1;$jj<=12;$jj++){
				if($jj<10) $jj = "0".$jj;
				$finput .= "<option value='".$jj."'>".$jj."</option>";
			}
			$finput .= "</select>월 ";

						$finput .= "<select id='".$fname."_".$ii."_2' name='fname[".$no."][]' class='select'><option value=''>--</option>";
			for($jj=1;$jj<=31;$jj++){
				if($jj<10) $jj = "0".$jj;
				$finput .= "<option value='".$jj."'>".$jj."</option>";
			}
			$finput .= "</select>일 ";


			$finput .= "<select id='".$fname."_".$ii."_3' name='fname[".$no."][]' class='select'><option value=''>--</option>";
			for($jj=1;$jj<=24;$jj++){
				if($jj<10) $jj = "0".$jj;
				$finput .= "<option value='".$jj."'>".$jj."</option>";
			}
			$finput .= "</select>시 ".$tmp_flist[$ii];

		}else if($type == "birthday") {

			$finput .= "<select id='".$fname."_".$ii."_0' name='fname[".$no."][]' class='select'><option value=''>--</option>";
			for($jj=1900;$jj<=date('Y');$jj++){
				$finput .= "<option value='".$jj."'>".$jj."</option>";
			}
			$finput .= "</select>년 ";

						$finput .= "<select id='".$fname."_".$ii."_1' name='fname[".$no."][]' class='select'><option value=''>--</option>";
			for($jj=1;$jj<=12;$jj++){
				if($jj<10) $jj = "0".$jj;
				$finput .= "<option value='".$jj."'>".$jj."</option>";
			}
			$finput .= "</select>월 ";

						$finput .= "<select id='".$fname."_".$ii."_2' name='fname[".$no."][]' class='select'><option value=''>--</option>";
			for($jj=1;$jj<=31;$jj++){
				if($jj<10) $jj = "0".$jj;
				$finput .= "<option value='".$jj."'>".$jj."</option>";
			}
			$finput .= "</select>일 ";

		}else if($type == "phone" || $type == "tel") {

			$tphone_list = "02,031,032,033,041,042,043,051,052,053,054,055,061,062,063,064";
			$hphone_list = "010,011,016,017,018,019";
			if(!strcmp($type, "tel")) $num_list = explode(",", $tphone_list);
			else if(!strcmp($type, "phone")) $num_list = explode(",", $hphone_list);

			$finput .= "<select  id='".$fname."_0' name='fname[".$no."][]' class='select'>";
			for($jj = 0; $jj < count($num_list); $jj++) {
				$finput .= "<option value='".$num_list[$jj]."'>".$num_list[$jj]."</option>";
			}
			$finput .= "</select> - ";
			$finput .= "<input type='text' id='".$fname."_1' name='fname[".$no."][]' class='input' size='".$size."' Onlynum='true'> - ";
			$finput .= "<input type='text' id='".$fname."_2' name='fname[".$no."][]' class='input' size='".$size."' Onlynum='true'>".$tmp_flist[$ii];

		}else if($type == "fax") {

			$finput .= "<input type='text' id='".$fname."_0' name='fname[".$no."][]' class='input' size='".$size."' Onlynum='true'> - ";
			$finput .= "<input type='text' id='".$fname."_1' name='fname[".$no."][]' class='input' size='".$size."' Onlynum='true'> - ";
			$finput .= "<input type='text' id='".$fname."_2' name='fname[".$no."][]' class='input' size='".$size."' Onlynum='true'> ";

		}else if($type == "email") {

			//$finput .= "<input type='text' id='".$fname."_$ii' name='fname[".$no."][]' class='input' size='".$size."'>".$tmp_flist[$ii];
			$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_1' name='".$fname."_email1' class='input' size='".$size."'> @ ";
			$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_2' name='".$fname."_email2' class='input' size='".$size."'> ";

			$email_str = "naver.com,daum.net,hanmail.net,gmail.com,hotmail.com,nate.com";
			$email_arr = explode(",", $email_str);

			$finput .= "<select id='".$form_info['idx']."_".$fname."_".$ii."_3' name='fname[".$no."][]' onChange=\"document.getElementById('".$form_info['idx']."_".$fname."_2').value=this.value\"  class='select'><option value=''>직접입력</option>";
			for($jj=0;$jj<count($email_arr);$jj++){
				$finput .= "<option value='".$email_arr[$jj]."'>".$email_arr[$jj]."</option>";
			}
			$finput .= "</select> ".$tmp_flist[$ii];


		}else if($type == "spamcheck") {

			get_spam_check();
			$finput .= "<input type='hidden' name='tmp_vcode' id='tmp_vcode' value='".md5($norobot_key)."'>".$spam_check;

		}

	//}
	}

	if($type == "select") $finput .= "</select>";

	return $finput;

}

?>
<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<link href="<?php echo $skin_dir?>/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> -->
<? echo $DAUM_POSTCODE.PHP_EOL; ?>
<script language="JavaScript" src="../../js/lib.js"></script>
<script language="JavaScript" type="text/javascript">
<!--

function popField(idx) {
<?php
if(!strcmp($fmode, "update")) {
?>
	if(idx != "") var url = "form_field_input.php?fidx=<?=$fidx?>&code=<?=$code?>&idx="+idx;
	else var url = "form_field_input.php?fidx=<?=$fidx?>&code=<?=$code?>";

   window.open(url, "Form_Field_Input", "width=640, height=520, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, left=50, top=50");
<?php
} else {
?>
	alert("폼메일 생성 후 항목을 생성해 주세요.");
<?php
}
?>
}

function delField(idx){

	if(confirm("삭제 하시겠습니까?")){
		document.location = "form_save.php?mode=field&fmode=<?=$fmode?>&sub_mode=delete&fidx=<?=$fidx?>&code=<?=$code?>&idx=" + idx;
	}

}

// 우편번호 찾기
function postSearch(kind){
	if(kind == undefined) kind = '';
	new daum.Postcode({
		oncomplete: function(data) {

			var frm;

			for(i=0;i<document.forms.length;i++){
				frm = document.forms[i];

				var extraAddr = '';
				var fullAddr = '';

				if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
					fullAddr = data.roadAddress;
				} else { // 사용자가 지번 주소를 선택했을 경우(J)
					fullAddr = data.jibunAddress;
				}

				if(data.userSelectedType === 'R'){

					if(data.bname !== ''){
						extraAddr += data.bname;
					}
					
					if(data.buildingName !== ''){
						extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
					}
					fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');

				}

				if(eval('frm.'+kind+'post1')){
					eval('frm.'+kind+'post1').value = data.zonecode;
					eval('frm.'+kind+'address1').value = fullAddr;
				
					if(eval('frm.'+kind+'address1') != null)
						eval('frm.'+kind+'address2').focus();
				}

			}
		}
	}).open();
}

//-->
</script>
<body topmargin="0" leftmargin="0">

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6">
              <tr>
                <td align="right">
						<input type="button" value="항목추가" class="base_btm reg" onClick="popField('')">
			<input type="button" value="새로고침" class="base_btm reg" onClick="document.location='<?=$PHP_SELF?>?fmode=<?=$fmode?>&fidx=<?=$fidx?>&code=<?=$code?>'">

                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

		<form name="frm" style="margin:0;">
        <input type="hidden" name="fidx" value="<?=$fidx?>">
        <input type="hidden" name="code" value="<?=$code?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" class="t_name"><b>항목명</b></td>
                <td class="t_value"><b>미리보기</b></td>
                <td width="8%" align="center" class="t_value"><b>필수여부</b></td>
                <td width="8%" align="center" class="t_value"><b>순서</b></td>
                <td width="8%" align="center" class="t_value"><b>기능</b></td>
              </tr>
              <?
              $sql = "select * from wiz_formfield where fidx = '$fidx' order by fprior asc";
              $result = query($sql);
              $no = 0;
              while($row = sql_fetch_arr($result)){
              	if($row['fessen'] == "") $row['fessen'] = "N";
              	if(img_type(WIZHOME_PATH."/data/form/title/".$row['fimg'])) { 
					$row['fimg'] = "<img src='/twcenter/data/form/title/".$row['fimg']."' align='absmiddle'>"; }

				$essen_chk = ($row['fessen'] == 'Y') ? "*" : "";

			  ?>
              <tr>
                <td class="t_name"><?=$row['fname']?> <?php echo $essen_chk ?> <?=$row['fimg']?></td>
                <td class="t_value"><?=createObject($no,$row['ftype'],$row['fsize'],$row['flist'])?></td>
                <td align="center" class="t_value"><?=$row['fessen']?></td>
                <td align="center" class="t_value">
                	<table>
			            <tr><td><a href="form_save.php?mode=prior&fmode=<?=$fmode?>&posi=up&idx=<?=$row['idx']?>&prior=<?=$row['fprior']?>&fidx=<?=$fidx?>&code=<?=$code?>"><img src="../image/up_icon.gif" border="0" alt="1단계 위로"></a><br></td></tr>
			            <tr><td><a href="form_save.php?mode=prior&fmode=<?=$fmode?>&posi=down&idx=<?=$row['idx']?>&prior=<?=$row['fprior']?>&fidx=<?=$fidx?>&code=<?=$code?>"><img src="../image/down_icon.gif" border="0" alt="1단계 아래로"></a></td></tr>
			            </table>
                </td>
                <td align="center" class="t_value">
                	<table>
						<tr>
							<td>
								<a href="javascript:popField('<?=$row['idx']?>')"><img src="../image/btn_edit_s.gif" border="0"></a>
								<a href="javascript:delField('<?=$row['idx']?>')"><img src="../image/btn_delete_s.gif" border="0"></a>
							</td>
						</tr>
                	</table>
                </td>
              </tr>
              <?
              	$no++;
              }
              ?>
            </table>
          </td>
        </tr>
      </table>
	  </form>

</body>
</html>