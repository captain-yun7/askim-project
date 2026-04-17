<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/form_info.php";

// 자동등록글체크
get_spam_check();

// 스팸글체크기능 사용여부
if(!isset($form_info['spam_check'])) $form_info['spam_check'] = '';

if(strcmp($form_info['spam_check'], "Y")){
	$hide_spam_check_start = "<!--"; $hide_spam_check_end = "-->";
}

// 약관내용
$agreement = $form_info['agree_text'];

if($form_info['agree_use'] != "Y" || $wiz_session['id'] != "") {
	$hide_agree_start = "<!--"; $hide_agree_end = "-->";
}

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;
echo "<link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css\">".PHP_EOL;
//echo "<script type=\"text/javascript\" src=\"//code.jquery.com/jquery.min.js\"></script>".PHP_EOL;
//echo "<script type=\"text/javascript\" src=\"//code.jquery.com/ui/1.11.4/jquery-ui.min.js\"></script>".PHP_EOL;
echo "<script type=\"text/javascript\" src=\"/comm/js/upload_file_limit.js\"></script>".PHP_EOL;

if(!function_exists("createObject")) {

	function createObject($no,$type,$size,$flist){

		global $upfile_idx;
		global $spam_check;
		global $norobot_key;
		global $form_info;

		$syear = date('Y')-1;		// 시작년도 ( date('Y') 해당년도 , $syear=2011; 처럼 지정가능 )
		$eyear = $syear + 10;		// 끝년도 ( $eyear = 2020; 처럼 지정가능)

		$fname = "f".$no;

		// 반복하지 않는 속성
		$tmp_type = Array("","address","birthday","phone","email","name","tel","fax");

		for($ii = 0;$ii < count($tmp_type); $ii++) {
			if(!strcmp($type, $tmp_type[$ii])) $flist = " ";
		}

		$tmp_flist = explode("|",$flist);

		if($type == "select") $finput = "<select id='".$form_info['idx']."_".$fname."_0' name='fname[".$no."][]' title='옵션을 선택해주세요'><option value=''>-- 선택 --</option>";

		for($ii=0;$ii<count($tmp_flist);$ii++){

		//if($tmp_flist[$ii] != ""){

			if($type == "text" || $type == "name"){
				if($mobile_key == "M"){
					$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_$ii' name='fname[".$no."][]' class='input' size='".$size."' title='text' placeholder='".$fname."'>".$tmp_flist[$ii];
				}else{
					$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_$ii' name='fname[".$no."][]' class='input' size='".$size."' title='text'>".$tmp_flist[$ii];
				}

			}else if($type == "file"){

				$upfile_idx++;
				$finput .= "<input type='file' id='".$form_info['idx']."_".$fname."_$ii' name='upfile".$upfile_idx."' class='input' size='".$size."' title='file input_file'>".$tmp_flist[$ii];

			}else if($type == "radio"){

				$finput .= "<input type='radio' id='".$form_info['idx']."_".$fname."_$ii' name='fname[".$no."]' value='".$tmp_flist[$ii]."' title='radio'>".$tmp_flist[$ii]."&nbsp; ";

			}else if($type == "checkbox"){

				$finput .= "<input type='checkbox' id='".$form_info['idx']."_".$fname."_$ii' name='fname[".$no."][]' value='".$tmp_flist[$ii]."' title='checkbox' class='checkbox_input'><label for='".$form_info['idx']."_".$fname."_$ii' class='checkbox_label'>".$tmp_flist[$ii]."</label>";

			}else if($type == "textarea"){

				$finput .= "<textarea id='".$form_info['idx']."_".$fname."_$ii' name='fname[".$no."][]' rows='".$size."' class='textarea' title='textarea'></textarea>";

			}else if($type == "select"){

				$finput .= "<option value='".$tmp_flist[$ii]."' title='옵션을 선택해주세요'>".$tmp_flist[$ii]."</option>";

			}else if($type == "address"){

				$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_0' name='".$fname."_post1' onClick=postSearch('".$fname."_'); class='input input_num' size='6' readonly  title='우편번호'>";
				//$finput .= "&nbsp;<img src='/twcenter/images/address_btn.gif' align='absmiddle' style='cursor:pointer' onClick=postSearch('".$fname."_') alt='우편번호검색'><br>";
				$finput .= "<input type=button value='우편번호 검색' align='absmiddle' style='cursor:pointer' onClick=postSearch('".$fname."_') class='btn_address'><br>";
				$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_2' name='".$fname."_address1' class='input input_l input_address' size='".$size."' title='주소입력1'><br>";
				$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_3' name='".$fname."_address2' class='input input_l input_address' size='".$size."' title='주소입력2'><br>";

			}else if($type == "pdate"){

				$finput .= "<div class='input_pdate'><input type='text' id='".$form_info['idx']."_".$fname."_".$ii."' name='fname[".$no."][]' class='input input_s' size='".$size."' readonly title='pdate'></div>";

			?>
			<script type="text/javascript">
				$(function() {

					var calendar = {
						showOn: "both",
						buttonImage: "/twcenter/images/cal2.png",
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

					$("#<?=$form_info['idx']."_".$fname."_".$ii?>").datepicker(calendar);

				});
			</script>
			<?

			}else if($type == "tdate"){

				$finput .= "<select id='".$form_info['idx']."_".$fname."_".$ii."_0' name='fname[".$no."][]' title='연도를 선택해주세요'><option value=''>-- 선택 --</option>";
				for($jj=$syear;$jj<=$eyear;$jj++){
					$finput .= "<option value='".$jj."'>".$jj."</option>";
				}
				$finput .= "</select>년 ";

						$finput .= "<select id='".$form_info['idx']."_".$fname."_".$ii."_1' name='fname[".$no."][]' title='월을 선택해주세요'><option value=''>-- 선택 --</option>";
				for($jj=1;$jj<=12;$jj++){
					if($jj<10) $jj = "0".$jj;
					$finput .= "<option value='".$jj."'>".$jj."</option>";
				}
				$finput .= "</select>월 ";

						$finput .= "<select id='".$form_info['idx']."_".$fname."_".$ii."_2' name='fname[".$no."][]'' title='일을 선택해주세요'><option value=''>-- 선택 --</option>";
				for($jj=1;$jj<=31;$jj++){
					if($jj<10) $jj = "0".$jj;
					$finput .= "<option value='".$jj."'>".$jj."</option>";
				}
				$finput .= "</select>일 ";


				$finput .= "<select id='".$form_info['idx']."_".$fname."_".$ii."_3' name='fname[".$no."][]'' title='시를 선택해주세요'><option value=''>-- 선택 --</option>";
				for($jj=1;$jj<=24;$jj++){
					if($jj<10) $jj = "0".$jj;
					$finput .= "<option value='".$jj."'>".$jj."</option>";
				}
				$finput .= "</select>시 ".$tmp_flist[$ii];

			}else if($type == "birthday") {

				$finput .= "<select id='".$form_info['idx']."_".$fname."_".$ii."_0' name='fname[".$no."][]'' title='연도를 선택해주세요'><option value=''>-- 선택 --</option>";
				for($jj=1900;$jj<=date('Y');$jj++){
					$finput .= "<option value='".$jj."'>".$jj."</option>";
				}
				$finput .= "</select>년 ";

						$finput .= "<select id='".$form_info['idx']."_".$fname."_".$ii."_1' name='fname[".$no."][]'' title='월을 선택해주세요'><option value=''>-- 선택 --</option>";
				for($jj=1;$jj<=12;$jj++){
					if($jj<10) $jj = "0".$jj;
					$finput .= "<option value='".$jj."'>".$jj."</option>";
				}
				$finput .= "</select>월 ";

						$finput .= "<select id='".$form_info['idx']."_".$fname."_".$ii."_2' name='fname[".$no."][]'' title='일을 선택해주세요'><option value=''>-- 선택 --</option>";
				for($jj=1;$jj<=31;$jj++){
					if($jj<10) $jj = "0".$jj;
					$finput .= "<option value='".$jj."'>".$jj."</option>";
				}
				$finput .= "</select>일 ";

			}else if($type == "phone" || $type == "tel") {

				$tphone_list = "02,031,032,033,041,042,043,044,051,052,053,054,055,061,062,063,064,010,011,016,017,018,019";
				$hphone_list = "010,011,016,017,018,019,02,031,032,033,041,042,043,044,051,052,053,054,055,061,062,063,064";
				if(!strcmp($type, "tel")) $num_list = explode(",", $tphone_list);
				else if(!strcmp($type, "phone")) $num_list = explode(",", $hphone_list);

				$finput .= "<select class='select_num' id='".$form_info['idx']."_".$fname."_0' name='fname[".$no."][]'' title='국번을 선택해주세요'>";
				for($jj = 0; $jj < count($num_list); $jj++) {
					$finput .= "<option value='".$num_list[$jj]."'>".$num_list[$jj]."</option>";
				}
				$finput .= "</select><span class='hyp'>-</span>";

				$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_1' name='fname[".$no."][]' class='input input_num' size='".$size."' maxlength='4' title='번호를 입력해주세요' Onlynum='true'><span class='hyp'>-</span>";
				$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_2' name='fname[".$no."][]' class='input input_num' size='".$size."' maxlength='4' title='번호를 입력해주세요' Onlynum='true'>".$tmp_flist[$ii];

			}else if($type == "fax") {

				$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_0' name='fname[".$no."][]' class='input input_num' size='".$size."' maxlength='4' Onlynum='true' title='번호를 입력해주세요'><span class='hyp'>-</span>";
				$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_1' name='fname[".$no."][]' class='input input_num' size='".$size."' maxlength='4' Onlynum='true' title='번호를 입력해주세요'><span class='hyp'>-</span>";
				$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_2' name='fname[".$no."][]' class='input input_num' size='".$size."' maxlength='4' Onlynum='true' title='번호를 입력해주세요'>";

			}else if($type == "email") {

				//$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_$ii' name='fname[".$no."][]' class='input' size='".$size."'>".$tmp_flist[$ii];
				$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_1' name='".$fname."_email1' class='input input_s input_email' size='".$size."' title='이메일주소입력1' /><span class='hyp'>@</span>";
				$finput .= "<input type='text' id='".$form_info['idx']."_".$fname."_2' name='".$fname."_email2' class='input input_s input_email' size='".$size."' title='이메일주소입력2' />";

				$email_str = "naver.com,daum.net,hanmail.net,gmail.com,hotmail.com,nate.com";
				$email_arr = explode(",", $email_str);

				$finput .= "<select class='select_email' id='".$form_info['idx']."_".$fname."_".$ii."_3' name='fname[".$no."][]' onChange=\"document.getElementById('".$form_info['idx']."_".$fname."_2').value=this.value\" title='주소를 선택해주세요'><option value=''>직접입력</option>";
				for($jj=0;$jj<count($email_arr);$jj++){
					$finput .= "<option value='".$email_arr[$jj]."'>".$email_arr[$jj]."</option>";
				}
				$finput .= "</select>".$tmp_flist[$ii];

			}else if($type == "spamcheck") {

					get_spam_check();
					$finput .= "<input type='hidden' name='tmp_vcode_".$form_info['idx']."' value='".md5($norobot_key)."' title='자동등록방지 입력'>".$spam_check;

			}

		//}
		}

		if($type == "select") $finput .= "</select>";

		return $finput;

	}

	function checkObject($no,$name,$essen,$type,$flist){

		global $form_info;

		$fname = "f".$no;
		if($flist == "") $flist = " ";

		if($essen == "Y"){

			if($type == "text" || $type == "textarea" || $type == "file" || $type == "pdate" || $type == "name"){
				
				$flist_list = explode("|",$flist);
				for($ii=0;$ii<count($flist_list);$ii++){

					echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_".$ii."\");\n";
					echo "if(obj.value == \"\"){\n";
					echo "   alert(\"".$name."을 입력하세요\");\n";
					echo "   obj.focus();\n";
					echo "   return false;\n";
					echo "}\n";

				}

			}else if($type == "select"){

				echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_0\");\n";
				echo "if(obj.value == \"\"){\n";
				echo "   alert(\"".$name."을 선택하세요\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}\n";

			}else if($type == "tdate"){

				$flist_list = explode("|",$flist);
				for($ii=0;$ii<count($flist_list);$ii++){

					echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_".$ii."_0\");\n";
					echo "if(obj.value == \"\"){\n";
					echo "   alert('년도를 선택하세요');\n";
					echo "   obj.focus();\n";
					echo "   return false;\n";
					echo "}\n";

					echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_".$ii."_1\");\n";
					echo "if(obj.value == \"\"){\n";
					echo "   alert('월을 선택하세요');\n";
					echo "   obj.focus();\n";
					echo "   return false;\n";
					echo "}\n";
					
					echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_".$ii."_2\");\n";
					echo "if(obj.value == \"\"){\n";
					echo "   alert('일자를 선택하세요');\n";
					echo "   obj.focus();\n";
					echo "   return false;\n";
					echo "}\n";
					
					echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_".$ii."_3\");\n";
					echo "if(obj.value == \"\"){\n";
					echo "   alert('시간을 선택하세요');\n";
					echo "   obj.focus();\n";
					echo "   return false;\n";
					echo "}\n";

				}

			}else if($type == "checkbox" || $type == "radio"){

				echo "var c_checked = false;";

				$flist_list = explode("|",$flist);
				for($ii=0;$ii<count($flist_list);$ii++){

					echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_".$ii."\");\n";
					echo "if(obj.checked == true) c_checked = true;\n";

				}

				echo "if(c_checked == false){\n";
				echo "   alert(\"".$name." 을 선택하세요\");\n";
				echo "   return false;\n";
				echo "}\n";


			}else if($type == "address"){

				echo "if(frm.".$fname."_address1.value == ''){\n";
				echo "   alert('주소를 입력하세요');\n";
				echo "   return false;\n";
				echo "}\n";
				
				echo "if(frm.".$fname."_address2.value == ''){\n";
				echo "   alert('주소를 입력하세요');\n";
				echo "   frm.".$fname."_address2.focus();\n";
				echo "   return false;\n";
				echo "}\n";

			}else if($type == "birthday"){

				$flist_list = explode("|",$flist);
				for($ii=0;$ii<count($flist_list);$ii++){

					echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_".$ii."_0\");\n";
					echo "if(obj.value == \"\"){\n";
					echo "   alert('년도를 선택하세요');\n";
					echo "   obj.focus();\n";
					echo "   return false;\n";
					echo "}\n";
					
					echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_".$ii."_1\");\n";
					echo "if(obj.value == \"\"){\n";
					echo "   alert('월을 선택하세요');\n";
					echo "   obj.focus();\n";
					echo "   return false;\n";
					echo "}\n";
					
					echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_".$ii."_2\");\n";
					echo "if(obj.value == \"\"){\n";
					echo "   alert('일자를 선택하세요');\n";
					echo "   obj.focus();\n";
					echo "   return false;\n";
					echo "}\n";

				}

			}else if($type == "phone" || $type == "tel"){

				echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_0\");\n";
				echo "if(obj.value == \"\"){\n";
				echo "   alert(\"".$name."을 입력하세요\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}else if(!check_Num(obj.value)){\n";
				echo "	alert(\"지역번호는 숫자만 가능합니다.\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}\n";

				echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_1\");\n";
				echo "if(obj.value == \"\"){\n";
				echo "   alert(\"".$name."을 입력하세요\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}else if(!check_Num(obj.value)){\n";
				echo "	alert(\"국번은 숫자만 가능합니다.\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}\n";
				
				echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_2\");\n";
				echo "if(obj.value == \"\"){\n";
				echo "   alert(\"".$name."을 입력하세요\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}else if(!check_Num(obj.value)){\n";
				echo "	alert(\"전화번호는 숫자만 가능합니다.\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}\n";

			}else if($type == "fax"){

				echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_0\");\n";
				echo "if(obj.value == \"\"){\n";
				echo "   alert(\"".$name."을 입력하세요\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}else if(!check_Num(obj.value)){\n";
				echo "	alert(\"숫자만 가능합니다.\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}\n";

				echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_1\");\n";
				echo "if(obj.value == \"\"){\n";
				echo "   alert(\"".$name."을 입력하세요\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}else if(!check_Num(obj.value)){\n";
				echo "	alert(\"숫자만 가능합니다.\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}\n";
				
				echo "var obj = document.getElementById(\"".$form_info['idx']."_".$fname."_2\");\n";
				echo "if(obj.value == \"\"){\n";
				echo "   alert(\"".$name."을 입력하세요\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}else if(!check_Num(obj.value)){\n";
				echo "	alert(\"숫자만 가능합니다.\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}\n";

			} else if($type == "email"){

				echo "var obj = frm.".$fname."_email1;\n";
				echo "var obj2 = frm.".$fname."_email2;\n";

				echo "var s_email_1 = $('#".$form_info['idx']."_".$fname."_1');\n";
				echo "var s_email_2 = $('#".$form_info['idx']."_".$fname."_2');\n";

				echo "if(frm.".$fname."_email1.value == ''){\n";
				echo "   alert(\"".$name."를 입력하세요\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "} else if(frm.".$fname."_email2.value == ''){\n";
				echo "   alert(\"".$name."를 입력하세요\");\n";
				echo "   obj2.focus();\n";
				echo "   return false;\n";
				echo "}\n";

				echo "if(!check_Email2(s_email_1, s_email_2, '올바른 이메일주소를 입력하세요.')) return false;";

			} else if($type == "spamcheck"){

				//echo "var obj = document.getElementById(\"vcode\");\n";
				echo "var obj = document.formFrm".$form_info['idx'].".vcode;\n";
				echo "if (obj != undefined && (hex_md5(obj.value) != md5_norobot_key".$form_info['idx'].")) {\n";
				echo "   alert(\"".$name."을 정확히 입력해주세요\");\n";
				echo "   obj.focus();\n";
				echo "   return false;\n";
				echo "}\n";

			}


		}

	}
}
?>
<!-- <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> -->
<? echo $DAUM_POSTCODE.PHP_EOL; ?>
<script language="javascript">
<!--
function formCheck<?=$form_info['idx']?>(frm){
<?php
if($form_info['agree_use'] == "Y" && $wiz_session['id'] == "") {
?>
	var agree = true;
	if(!frm.agree.checked) agree = false;

	if(!agree) {
		alert("약관에 동의해 주시기 바랍니다.");
		frm.agree.focus();
		return false;
	}
<?php
}

$no = 0;
$sql = "select * from wiz_formfield where fidx = '".$form_info['idx']."' order by fprior asc, idx asc";
$result = query($sql);
while($row = sql_fetch_arr($result)){
	checkObject($no,$row['fname'],$row['fessen'],$row['ftype'],$row['flist']);
	$no++;
}
?>
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
-->
</script>


<?php
$fidx = $form_info['idx'];

// 상단파일
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/form_head.php";

$no = 0;
$upfile_idx = 0;
$sql = "select * from wiz_formfield where fidx = '$fidx' order by fprior asc, idx asc";
$result = query($sql);
while($row = sql_fetch_arr($result)){

	$essen_chk = ($row['fessen'] == 'Y') ? "<span>*</span>" : "";
	$fname = $row['fname']." ".$essen_chk;
	if(img_type(WIZHOME_PATH."/data/form/title/".$row['fimg'])) { $fname = "<img src='/twcenter/data/form/title/".$row['fimg']."' align='absmiddle'>"; }
	$finput = createObject($no,$row['ftype'],$row['fsize'],$row['flist']);
	include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/form_body.php";
	$no++;

}

// 하단파일
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/form_foot.php";

?>