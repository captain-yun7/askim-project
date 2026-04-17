<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

$prev = "http://".$_http_host.$PHP_SELF;

$action = $ssl."/twcenter/member/myinfo_save.php";

if($wiz_session['id'] == "") error("로그인 후 이용하세요");

// 회원정보로드(sns)------------------------
if($wiz_session['login_Type'] == "sns"){
	$where = " id='{$wiz_session['id']}' ";
} else {
	$where = " id='{$wiz_session['id']}' ";
}

$sql = "SELECT * FROM wiz_member WHERE ".$where;
$result = query($sql);
$my_info = sql_fetch_arr($result);

if($my_info['photo'] != "" && file_exists(WIZHOME_PATH."/data/member/".$my_info['photo'])){
	$photo = "<img src=/twcenter/data/member/".$my_info['photo']." name='wiz_target_resize'> <input type='checkbox' name='delphoto' value='Y'><font color='red'>삭제</font><br>";
}
if($my_info['icon'] != "" && file_exists(WIZHOME_PATH."/data/member/".$my_info['icon'])){
	$icon = "<img src=/twcenter/data/member/".$my_info['icon']." width='".$icon_size."' height='".$icon_size."' align='absmiddle'> <input type='checkbox' name='delicon' value='Y'><font color='red'>삭제</font><br>";
}

$id				= $my_info['id'];
$name			= $my_info['name'];
$nick			= $my_info['nick'];
$resno			= $my_info['resno'];
list($email_1, $email_2) = explode("@", $my_info['email']);
$email			= $my_info['email'];
$homepage		= $my_info['homepage'];
$post			= $my_info['post'];
$address1		= $my_info['address1'];
$address2		= $my_info['address2'];

$job			= $my_info['job'];
$scholarship	= $my_info['scholarship'];
$hobby			= $my_info['hobby'];
$income			= $my_info['income'];
$intro			= $my_info['intro'];
$car			= $my_info['car'];

$addinfo1		= $my_info['addinfo1'];
$addinfo2		= $my_info['addinfo2'];
$addinfo3		= $my_info['addinfo3'];
$addinfo4		= $my_info['addinfo4'];
$addinfo5		= $my_info['addinfo5'];

$resno = substr($resno, 0, 7)."*******";

if($my_info['reemail'] == "Y") $reemail_y = "checked";
else if($my_info['reemail'] == "N")  $reemail_n = "checked";

if($my_info['resms'] == "Y") $resms_y = "checked";
else if($my_info['resms'] == "N")  $resms_n = "checked";

if($my_info['bgubun'] == "양력") $bgubun_s = "checked";
else if($my_info['bgubun'] == "음력") $bgubun_m = "checked";

if($my_info['marriage'] == "기혼") $marriage_y = "checked";
else if($my_info['marriage'] == "미혼") $marriage_n = "checked";

if($my_info['car'] == "소유") $car_y = "checked";
else if($my_info['car'] == "미소유") $car_n = "checked";

list($tphone1, $tphone2, $tphone3)       = explode("-", $my_info['tphone']);
list($hphone1, $hphone2, $hphone3)       = explode("-", $my_info['hphone']);
list($comtel1, $comtel2, $comtel3)       = explode("-", $my_info['comtel']);
list($birthday1, $birthday2, $birthday3) = explode("-", $my_info['birthday']);
list($memorial1, $memorial2, $memorial3) = explode("-", $my_info['memorial']);



// 정보입력 여부 체크
if($info_use['nick'] != true){
	$hide_nick_start = "<!--"; $hide_nick_end = "-->";
}
if($info_use['photo'] != true){
	$hide_photo_start = "<!--"; $hide_photo_end = "-->";
}
if($info_use['icon'] != true){
	$hide_icon_start = "<!--"; $hide_icon_end = "-->";
}
if($info_use['resno'] != true){
	$hide_resno_start = "<!--"; $hide_resno_end = "-->";
}
if($info_use['tphone'] != true){
	$hide_tphone_start = "<!--"; $hide_tphone_end = "-->";
}
if($info_use['hphone'] != true){
	$hide_hphone_start = "<!--"; $hide_hphone_end = "-->";
}
if($info_use['comtel'] != true){
	$hide_comtel_start = "<!--"; $hide_comtel_end = "-->";
}
if($info_use['email'] != true){
	$hide_email_start = "<!--"; $hide_email_end = "-->";
}
if($info_use['reemail'] != true){
	$hide_reemail_start = "<!--"; $hide_reemail_end = "-->";
}
if($info_use['resms'] != true){
	$hide_resms_start = "<!--"; $hide_resms_end = "-->";
}
if($info_use['homepage'] != true){
	$hide_homepage_start = "<!--"; $hide_homepage_end = "-->";
}
if($info_use['address'] != true){
	$hide_address_start = "<!--"; $hide_address_end = "-->";
}
if($info_use['birthday'] != true){
	$hide_birthday_start = "<!--"; $hide_birthday_end = "-->";
}
if($info_use['marriage'] != true){
	$hide_marriage_start = "<!--"; $hide_marriage_end = "-->";
}
if($info_use['memorial'] != true){
	$hide_memorial_start = "<!--"; $hide_memorial_end = "-->";
}
if($info_use['job'] != true){
	$hide_job_start = "<!--"; $hide_job_end = "-->";
}
if($info_use['scholarship'] != true){
	$hide_scholarship_start = "<!--"; $hide_scholarship_end = "-->";
}
if($info_use['consph'] != true){
	$hide_consph_start = "<!--"; $hide_consph_end = "-->";
}
if($info_use['hobby'] != true){
	$hide_hobby_start = "<!--"; $hide_hobby_end = "-->";
}
if($info_use['income'] != true){
	$hide_income_start = "<!--"; $hide_income_end = "-->";
}
if($info_use['car'] != true){
	$hide_car_start = "<!--"; $hide_car_end = "-->";
}
if($info_use['intro'] != true){
	$hide_intro_start = "<!--"; $hide_intro_end = "-->";
}
if($info_use['addinfo1'] != true){
	$hide_addinfo1_start = "<!--"; $hide_addinfo1_end = "-->";
}
if($info_use['addinfo2'] != true){
	$hide_addinfo2_start = "<!--"; $hide_addinfo2_end = "-->";
}
if($info_use['addinfo3'] != true){
	$hide_addinfo3_start = "<!--"; $hide_addinfo3_end = "-->";
}
if($info_use['addinfo4'] != true){
	$hide_addinfo4_start = "<!--"; $hide_addinfo4_end = "-->";
}
if($info_use['addinfo5'] != true){
	$hide_addinfo5_start = "<!--"; $hide_addinfo5_end = "-->";
}

if($info_use['hphone'] == true && $info_ess['hphone'] == true){
	$hphone_chk = " onchange='hphoneCheck()'";
} else {
	$hphone_chk = "";
}

if($info_use['email'] == true && $info_ess['email'] == true){
	$email_chk = " onchange='emailCheck()'";
} else {
	$email_chk = "";
}

function createObject($no,$type,$size,$flist, $value){

	global $upfile_idx;

	$syear = date('Y')-1;
	$eyear = $syear + 10;

	$fname = "f".$no;

	// 반복하지 않는 속성
	$tmp_type = Array("","address","birthday","phone","email","name","tel","fax");

	for($ii = 0;$ii < count($tmp_type); $ii++) {
		if(!strcmp($type, $tmp_type[$ii])) $flist = " ";
	}

	$tmp_flist = explode("|",$flist);

	if($type == "select") $finput = "<select id=\"".$fname."_0\" name=\"fname[".$no."][]\"><option value=\"\">--</option>";

	for($ii=0;$ii<count($tmp_flist);$ii++){

		if($type == "text"){

			$tmp_value = explode("|", $value);

			$finput .= "<input type=\"text\" id=\"".$fname."_$ii\" name=\"fname[".$no."][]\" class=\"input_m\" size=\"".$size."\" value=\"".$tmp_value[$ii]."\">".$tmp_flist[$ii];

		} else if($type == "file") {

			$tmp_value = explode("|", $value);

			$upfile_idx++;
			$finput .= "<input type=\"file\" id=\"".$fname."_$ii\" name=\"upfile".$upfile_idx."\" class=\"input_file\" size=\"".$size."\">";
			$finput .= "<input type=\"hidden\" id=\"tmp_".$fname."_$ii\" name=\"tmp_upfile_".$no."_".$upfile_idx."\" value=\"".$tmp_value[$ii]."\">";

			if(!empty($tmp_value[$ii])) {
				$finput .= " <a href=\"/twcenter/data/member/".$tmp_value[$ii]."\" target=\"_blank\">".$tmp_value[$ii]."</a> ";
				$finput .= " <a href=\"".$PHP_SELF."?ptype=save&mode=addfile_del&upfile=".$tmp_value[$ii]."&no=".$no."\"><font color=\"red\">[삭제]</font></a> ";
			}
			$finput .= $tmp_flist[$ii];

		} else if($type == "radio") {

			$tmp_value = $value;
			if(!strcmp($tmp_flist[$ii], $tmp_value)) $tmp_check = "checked";
			else $tmp_check = "";

			$finput .= "<input type=\"radio\" id=\"".$fname."_$ii\" name=\"fname[".$no."]\" value=\"".$tmp_flist[$ii]."\" ".$tmp_check.">".$tmp_flist[$ii]."&nbsp; ";

		} else if($type == "checkbox") {

			$tmp_value = explode("|", $value);
			for($jj = 0; $jj < count($tmp_value); $jj++) {
				if(!strcmp($tmp_flist[$ii], $tmp_value[$jj])) ${"tmp_check".$ii} = "checked";
			}

			$finput .= "<input type=\"checkbox\" id=\"".$fname."_$ii\" name=\"fname[".$no."][]\" value=\"".$tmp_flist[$ii]."\" ".${"tmp_check".$ii}.">".$tmp_flist[$ii]."&nbsp; ";

		} else if($type == "textarea") {

			$tmp_value = $value;
			$finput .= "<textarea id=\"".$fname."_$ii\" name=\"fname[".$no."][]\" rows=\"".$size."\" class=\"input\" style=\"width:99%\">".$tmp_value."</textarea>";

		} else if($type == "select") {

			$tmp_value = $value;
			if(!strcmp($tmp_flist[$ii], $tmp_value)) $tmp_select = "selected";
			else $tmp_select = "";

			$finput .= "<option value=\"".$tmp_flist[$ii]."\" ".$tmp_select.">".$tmp_flist[$ii]."</option>\n";

		} else if($type == "address") {

			$tmp_value	= explode("|", $value);
			$tmp_zip	= explode("-", $tmp_value[0]);

			$finput .= "<input type=\"text\" id=\"".$fname."_0\" name=\"".$fname."_post\" onClick=postSearch(\"".$fname."_\"); class=\"input_s add_num\" readonly value=\"".$tmp_zip[0]."\"> ";
			$finput .= "<input type=\"button\" value=\"주소찾기\" onClick=postSearch(\"".$fname."_\"); style=\"position:relative; vertical-align:top;\"><br>";
			$finput .= "<input type=\"text\" id=\"".$fname."_2\" name=\"".$fname."_address1\" class=\"input_l address\" size=\"".$size."\" value=\"".$tmp_value[1]."\"><br>";
			$finput .= "<input type=\"text\" id=\"".$fname."_3\" name=\"".$fname."_address2\" class=\"input_l address\" size=\"".$size."\" value=\"".$tmp_value[2]."\"><br>";

		} else if($type == "pdate") {

			$tmp_value = $value;

			$finput .= "<input type='text' id='".$fname."_".$ii."' name='fname[".$no."][]' value=\"".$tmp_value."\" class='input input_s' size='".$size."' readonly title='pdate'> ";
		
		 ?>
		<script type="text/javascript">
		$(function() {

			var calendar = {
				showOn: "both", 
				buttonImage: "/twcenter/images/calendar_btn2.gif", 
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
				yearRange: 'c-10:c+5'
			};

			$("#<?php echo $fname."_".$ii ?>").datepicker(calendar);

		});
		</script>

		<?php
		} else if($type == "tdate") {

			$tmp_value	= explode("~", $value);
			$tmp_dt		= explode("&nbsp;", $tmp_value[$ii]);
			$tmp_date	= explode("-", $tmp_dt[0]);

			$finput .= "<select id=\"".$fname."_".$ii."_0\" name=\"fname[".$no."][]\">\n<option value=\"\">:: 년도 ::</option>\n";
			for($jj=$syear;$jj<=$eyear;$jj++){

				if(!strcmp($jj, $tmp_date[0])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>\n";
			}
			$finput .= "</select>년 ";

			$finput .= "<select id=\"".$fname."_".$ii."_1\" name=\"fname[".$no."][]\">\n<option value=\"\">:: 월 ::</option>\n";
			for($jj=1;$jj<=12;$jj++){

				if($jj<10) $jj = "0".$jj;

				if(!strcmp($jj, $tmp_date[1])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>\n";
			}
			$finput .= "</select>월 ";

			$finput .= "<select id=\"".$fname."_".$ii."_2\" name=\"fname[".$no."][]\">\n<option value=\"\">:: 일 ::</option>\n";
			for($jj=1;$jj<=31;$jj++){

				if($jj<10) $jj = "0".$jj;

				if(!strcmp($jj, $tmp_date[2])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>\n";
			}
			$finput .= "</select>일 ";

			$finput .= "<select id=\"".$fname."_".$ii."_3\" name=\"fname[".$no."][]\">\n<option value=\"\">:: 시 ::</option>\n";
			for($jj=1;$jj<=24;$jj++){

				if($jj<10) $jj = "0".$jj;

				if(!strcmp($jj, $tmp_dt[1])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>\n";
			}
			$finput .= "</select>시 ".$tmp_flist[$ii];

		} else if($type == "birthday") {

			$tmp_value = explode("|", $value);

			$finput .= "<select id=\"".$fname."_".$ii."_0\" name=\"fname[".$no."][]\">\n<option value=\"\">:: 년도 ::</option>\n";
			for($jj=1900;$jj<=date('Y');$jj++){

				if(!strcmp($jj, $tmp_value[0])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>\n";
			}
			$finput .= "</select>년 ";

			$finput .= "<select id=\"".$fname."_".$ii."_1\" name=\"fname[".$no."][]\">\n<option value=\"\">:: 월 ::</option>\n";
			for($jj=1;$jj<=12;$jj++){
				if($jj<10) $jj = "0".$jj;

				if(!strcmp($jj, $tmp_value[1])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>\n";
			}
			$finput .= "</select>월 ";

			$finput .= "<select id=\"".$fname."_".$ii."_2\" name=\"fname[".$no."][]\">\n<option value=\"\">:: 일 ::</option>\n";
			for($jj=1;$jj<=31;$jj++){
				if($jj<10) $jj = "0".$jj;

				if(!strcmp($jj, $tmp_value[2])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>\n";
			}
			$finput .= "</select>일 ";

		} else if($type == "phone" || $type == "tel") {

			$tmp_value = explode("|", $value);

			$tphone_list = "02,031,032,033,041,042,043,051,052,053,054,055,061,062,063,064";
			$hphone_list = "010,011,016,017,018,019";
			if(!strcmp($type, "tel"))        $num_list = explode(",", $tphone_list);
			else if(!strcmp($type, "phone")) $num_list = explode(",", $hphone_list);

			$finput .= "<select  id='".$fname."_0' name='fname[".$no."][]'' title='국번을 선택해주세요'>\n";
			for($jj = 0; $jj < count($num_list); $jj++) {

				if($num_list[$jj] == $tmp_value[1]) $slted = "selected";
				else                                $slted = "";

				$finput .= "<option value='".$num_list[$jj]."' ".$seltd.">".$num_list[$jj]."</option>\n";

			}
			$finput .= "</select> - ";

			$finput .= "<input type='text' id='".$fname."_1' name='fname[".$no."][]' class='input input_num' size='".$size."' maxlength='4' title='번호를 입력해주세요' Onlynum='true' value=\"".$tmp_value[1]."\"> - ";
			$finput .= "<input type='text' id='".$fname."_2' name='fname[".$no."][]' class='input input_num' size='".$size."' maxlength='4' title='번호를 입력해주세요' Onlynum='true' value=\"".$tmp_value[2]."\">".$tmp_flist[$ii];

		} else if($type == "fax") {

			$tmp_value = explode("|", $value);

			$finput .= "<input type='text' id='".$fname."_0' name='fname[".$no."][]' class='input input_num' size='".$size."' maxlength='4' Onlynum='true' value=\"".$tmp_value[0]."\"> - ";
			$finput .= "<input type='text' id='".$fname."_1' name='fname[".$no."][]' class='input input_num' size='".$size."' maxlength='4' Onlynum='true' value=\"".$tmp_value[1]."\"> - ";
			$finput .= "<input type='text' id='".$fname."_2' name='fname[".$no."][]' class='input input_num' size='".$size."' maxlength='4' Onlynum='true' value=\"".$tmp_value[2]."\"> ";

		} else if($type == "email") {

			$tmp_value = explode("@", $value);

			$finput .= "<input type='text' id='".$fname."_1' name='".$fname."_email1' class='input input_s' size='".$size."' value=\"".$tmp_value[0]."\"> @ ";
			$finput .= "<input type='text' id='".$fname."_2' name='".$fname."_email2' class='input input_s' size='".$size."' value=\"".$tmp_value[1]."\"> ";

			$email_str = "naver.com,daum.net,hanmail.net,gmail.com,hotmail.com,nate.com";
			$email_arr = explode(",", $email_str);

			$finput .= "<select id='".$fname."_".$ii."_3' name='fname[".$no."][]' onChange=\"document.getElementById('".$fname."_2').value=this.value\" class='change_emailAdd'>\n<option value=''>직접입력</option>\n";
			for($jj=0;$jj<count($email_arr);$jj++){
				if($email_arr[$jj] == $tmp_value[1]) $slted = "selected";
				else                                 $slted = "";

				$finput .= "<option value='".$email_arr[$jj]."' ".$slted.">".$email_arr[$jj]."</option>\n";
			}
			$finput .= "</select> ".$tmp_flist[$ii];

		 ?>
		<script type="text/javascript">
		$(function() {
			$(".change_emailAdd").on("change", function() {
				var value = $(this).val();
				if(value != ""){
					$("#<?php echo $fname."_2" ?>").attr("readonly",true);
					$("#<?php echo $fname."_2" ?>").val(value);
				} else {
					$("#<?php echo $fname."_2" ?>").attr("readonly",false);
					$("#<?php echo $fname."_2" ?>").val('');
				}
			});
		});
		</script>

		<?php
		}

	}

	if($type == "select") $finput .= "</select>";

	return $finput;

}

function checkObject($no,$name,$essen,$type,$flist,$value) {

	$fname = "f".$no;
	if($flist == "") $flist = " ";

	if($essen == "Y") {

		if($type == "text" || $type == "textarea" || $type == "pdate" || $type == "name"){
			
			$flist_list = explode("|",$flist);
			for($ii=0;$ii<count($flist_list);$ii++){

				$checkObj .= "var obj = document.getElementById(\"".$fname."_".$ii."\");\n";
				$checkObj .= "if(obj.value == \"\"){\n";
				$checkObj .= "   alert(\"".$name."을 입력하세요\");\n";
				$checkObj .= "   obj.focus();\n";
				$checkObj .= "   return false;\n";
				$checkObj .= "}\n";

			}

		} else if($type == "file") {

			$flist_list = explode("|",$flist);
			for($ii=0;$ii<count($flist_list);$ii++){

				$tmp_value = explode("|", $value);

				if($tmp_value[$ii] == "") {
					
					$checkObj .= "var obj = document.getElementById(\"".$fname."_".$ii."\");\n";
					$checkObj .= "if(obj.value == \"\"){\n";
					$checkObj .= "   alert(\"".$name."을 입력하세요\");\n";
					$checkObj .= "   obj.focus();\n";
					$checkObj .= "   return false;\n";
					$checkObj .= "}\n";

				}

			}

		} else if($type == "select") {

			$checkObj .= "var obj = document.getElementById(\"".$fname."_0\");\n";
			$checkObj .= "if(obj.value == \"\"){\n";
			$checkObj .= "   alert(\"".$name."을 선택하세요\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}\n";

		}else if($type == "tdate"){

			$flist_list = explode("|",$flist);
			for($ii=0;$ii<count($flist_list);$ii++){

				$checkObj .= "var obj = document.getElementById(\"".$fname."_".$ii."_0\");\n";
				$checkObj .= "if(obj.value == \"\"){\n";
				$checkObj .= "   alert('년도를 선택하세요');\n";
				$checkObj .= "   obj.focus();\n";
				$checkObj .= "   return false;\n";
				$checkObj .= "}\n";

				$checkObj .= "var obj = document.getElementById(\"".$fname."_".$ii."_1\");\n";
				$checkObj .= "if(obj.value == \"\"){\n";
				$checkObj .= "   alert('월을 선택하세요');\n";
				$checkObj .= "   obj.focus();\n";
				$checkObj .= "   return false;\n";
				$checkObj .= "}\n";
				
				$checkObj .= "var obj = document.getElementById(\"".$fname."_".$ii."_2\");\n";
				$checkObj .= "if(obj.value == \"\"){\n";
				$checkObj .= "   alert('일자를 선택하세요');\n";
				$checkObj .= "   obj.focus();\n";
				$checkObj .= "   return false;\n";
				$checkObj .= "}\n";
				
				$checkObj .= "var obj = document.getElementById(\"".$fname."_".$ii."_3\");\n";
				$checkObj .= "if(obj.value == \"\"){\n";
				$checkObj .= "   alert('시간을 선택하세요');\n";
				$checkObj .= "   obj.focus();\n";
				$checkObj .= "   return false;\n";
				$checkObj .= "}\n";

			}

		}else if($type == "checkbox" || $type == "radio"){

			$checkObj .= "var c_checked = false;";

			$flist_list = explode("|",$flist);
			for($ii=0;$ii<count($flist_list);$ii++){

				$checkObj .= "var obj = document.getElementById(\"".$fname."_".$ii."\");\n";
				$checkObj .= "if(obj.checked == true) c_checked = true;\n";

			}

			$checkObj .= "if(c_checked == false){\n";
			$checkObj .= "   alert(\"".$name." 을 선택하세요\");\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}\n";


		}else if($type == "address"){

			$checkObj .= "if(frm.".$fname."_address1.value == ''){\n";
			$checkObj .= "   alert('주소를 입력하세요');\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}\n";
			
			$checkObj .= "if(frm.".$fname."_address2.value == ''){\n";
			$checkObj .= "   alert('주소를 입력하세요');\n";
			$checkObj .= "   frm.".$fname."_address2.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}\n";

		}else if($type == "birthday"){

			$flist_list = explode("|",$flist);
			for($ii=0;$ii<count($flist_list);$ii++){

				$checkObj .= "var obj = document.getElementById(\"".$fname."_".$ii."_0\");\n";
				$checkObj .= "if(obj.value == \"\"){\n";
				$checkObj .= "   alert('년도를 선택하세요');\n";
				$checkObj .= "   obj.focus();\n";
				$checkObj .= "   return false;\n";
				$checkObj .= "}\n";
				
				$checkObj .= "var obj = document.getElementById(\"".$fname."_".$ii."_1\");\n";
				$checkObj .= "if(obj.value == \"\"){\n";
				$checkObj .= "   alert('월을 선택하세요');\n";
				$checkObj .= "   obj.focus();\n";
				$checkObj .= "   return false;\n";
				$checkObj .= "}\n";
				
				$checkObj .= "var obj = document.getElementById(\"".$fname."_".$ii."_2\");\n";
				$checkObj .= "if(obj.value == \"\"){\n";
				$checkObj .= "   alert('일자를 선택하세요');\n";
				$checkObj .= "   obj.focus();\n";
				$checkObj .= "   return false;\n";
				$checkObj .= "}\n";

			}

		}else if($type == "phone" || $type == "tel"){

			$checkObj .= "var obj = document.getElementById(\"".$fname."_0\");\n";
			$checkObj .= "if(obj.value == \"\"){\n";
			$checkObj .= "   alert(\"".$name."을 입력하세요\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}else if(!check_Num(obj.value)){\n";
			$checkObj .= "	alert(\"지역번호는 숫자만 가능합니다.\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}\n";

			$checkObj .= "var obj = document.getElementById(\"".$fname."_1\");\n";
			$checkObj .= "if(obj.value == \"\"){\n";
			$checkObj .= "   alert(\"".$name."을 입력하세요\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}else if(!check_Num(obj.value)){\n";
			$checkObj .= "	alert(\"국번은 숫자만 가능합니다.\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}\n";
			
			$checkObj .= "var obj = document.getElementById(\"".$fname."_2\");\n";
			$checkObj .= "if(obj.value == \"\"){\n";
			$checkObj .= "   alert(\"".$name."을 입력하세요\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}else if(!check_Num(obj.value)){\n";
			$checkObj .= "	alert(\"전화번호는 숫자만 가능합니다.\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}\n";

		}else if($type == "fax"){

			$checkObj .= "var obj = document.getElementById(\"".$fname."_0\");\n";
			$checkObj .= "if(obj.value == \"\"){\n";
			$checkObj .= "   alert(\"".$name."을 입력하세요\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}else if(!check_Num(obj.value)){\n";
			$checkObj .= "	alert(\"숫자만 가능합니다.\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}\n";

			$checkObj .= "var obj = document.getElementById(\"".$fname."_1\");\n";
			$checkObj .= "if(obj.value == \"\"){\n";
			$checkObj .= "   alert(\"".$name."을 입력하세요\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}else if(!check_Num(obj.value)){\n";
			$checkObj .= "	alert(\"숫자만 가능합니다.\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}\n";
			
			$checkObj .= "var obj = document.getElementById(\"".$fname."_2\");\n";
			$checkObj .= "if(obj.value == \"\"){\n";
			$checkObj .= "   alert(\"".$name."을 입력하세요\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}else if(!check_Num(obj.value)){\n";
			$checkObj .= "	alert(\"숫자만 가능합니다.\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}\n";

		} else if($type == "email"){

			$checkObj .= "var obj = frm.".$fname."_email1;\n";
			$checkObj .= "var obj2 = frm.".$fname."_email2;\n";

			$checkObj .= "var s_email_1 = $('#".$fname."_1');\n";
			$checkObj .= "var s_email_2 = $('#".$fname."_2');\n";

			$checkObj .= "if(frm.".$fname."_email1.value == ''){\n";
			$checkObj .= "   alert(\"".$name."를 입력하세요\");\n";
			$checkObj .= "   obj.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "} else if(frm.".$fname."_email2.value == ''){\n";
			$checkObj .= "   alert(\"".$name."를 입력하세요\");\n";
			$checkObj .= "   obj2.focus();\n";
			$checkObj .= "   return false;\n";
			$checkObj .= "}\n";

			$checkObj .= "if(!check_Email2(s_email_1, s_email_2, '올바른 이메일주소를 입력하세요.')) return false;";

		}

	}

	return $checkObj;

}

$sql = "select fprior, ftype, fsize, flist from wiz_formfield where fidx = 'addinfo' order by fprior asc";
$result = query($sql) or error("sql error");
while($row = sql_fetch_arr($result)) {
	$no = $row['fprior'];

	${"addinfo".$no."_input"} = createObject($no,$row['ftype'],$row['fsize'],$row['flist'], ${"addinfo".$no});
	${"addinfo".$no."_check"} = checkObject($no,${"addname".$no},"Y",$row['ftype'],$row['flist'], ${"addinfo".$no});
}

 ?>
<!-- <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> -->
<? echo $DAUM_POSTCODE.PHP_EOL; ?>
<script language="JavaScript">
<!--

// 입력값 체크
function myinfoCheck(frm){

	var sdigit = "<?php echo $mem_info['sdigit'] ?>";
	var edigit = "<?php echo $mem_info['edigit'] ?>";
	var spechk = "<?php echo $spectial_Char ?>";
	
	if("<?php echo $wiz_session['login_Type'] ?>" != "sns"){

		if(frm.old_passwd.value==""){
			alert("현재비밀번호를 입력해주세요");
			return false;
		}

		if(frm.passwd1.value != ""){

			if(!check_Pass($.trim(frm.passwd1.value), sdigit, edigit, spechk)){
				frm.passwd1.focus();
				return false;
			}

			if(frm.id.value == frm.passwd1.value) {
				alert("아이디와 동일하게 비밀번호를 설정하셨습니다.");
				frm.passwd1.value = "";
				frm.passwd2.value = "";
				frm.passwd1.focus();
				return false;
			}

			if(frm.passwd1.value.search(/\s/) != -1){
				alert("비밀번호는 공백없이 입력해주세요.");
				frm.passwd1.focus();
				return false;
			}

			var dup_Reg = /(\w)\1\1\1/;
			if(dup_Reg.test(frm.passwd1.value)){
				alert("비밀번호에 같은 문자를 4번이상 사용하실 수 없습니다.");
				frm.passwd1.focus();
				return false;
			}

			if(frm.passwd2.value == ""){
				alert("비밀번호를 재입력해주세요.");
				frm.passwd2.focus();
				return false;
			}

			if(!check_Pass($.trim(frm.passwd2.value), sdigit, edigit, spechk)){
				frm.passwd2.focus();
				return false;
			}

			if(frm.passwd1.value != frm.passwd2.value){alert("비밀번호가 일치하지 않습니다");frm.passwd1.focus();return false;}


		}

	}

<?php if($info_use['nick']=="true" && $info_ess['nick']=="true"){ ?>

   if(frm.nick.value == ""){alert("닉네임을 입력하세요.");frm.nick.focus();return false;}
	if(frm.nick.value != ""){
		var Nduplication = $("#Nduplication").val();
		if(Nduplication == 'F'){
			alert("이미 사용중이거나 사용할 수 없는 닉네임 입니다");
			$("#nick").focus();
			return false;
		}
	}
<?php } ?>



<?php if($info_use['photo']=="true" && $info_ess['photo']=="true"){ ?>

	if(frm.photo.value == ""){alert("사진을 입력하세요.");frm.photo.focus();return false;}

<?php } ?>

<?php if($info_use['icon']=="true" && $info_ess['icon']=="true"){ ?>

	if(frm.icon.value == ""){alert("아이콘을 입력하세요.");frm.icon.focus();return false;}

<?php } ?>

<?php if($info_use['resno']=="true" && $info_ess['resno']=="true"){ ?>

	//if(frm.resno1.value == ""){alert("주민번호를 입력하세요");frm.resno1.focus();return false;}
	//if(frm.resno2.value == ""){alert("주민번호를 입력하세요");frm.resno2.focus();return false;}
	//if(!check_ResidentNO(frm.resno1.value, frm.resno2.value)){alert("주민번호가 올바르지 않습니다");frm.resno1.value == "";frm.resno2.value == "";frm.resno1.focus();return false;}

<?php } ?>

<?php if($info_use['tphone']=="true" && $info_ess['tphone']=="true"){ ?>

	var tphone0 = $.trim($("#tphone0").val());
	var tphone1 = $.trim($("#tphone1").val());
	var tphone2 = $.trim($("#tphone2").val());

	if(tphone0 == ""){alert("전화번호를 입력하세요");$("#tphone0").focus();return false;
	}else if(!check_Num(tphone0)){alert("지역번호는 숫자만 가능합니다.");$("#tphone0").focus();return false;}

	if(tphone1 == ""){alert("전화번호를 입력하세요");$("#tphone1").focus();return false;
	}else if(!check_Num(tphone1)){alert("국번은 숫자만 가능합니다.");$("#tphone1").focus();return false;}

	if(tphone2 == ""){alert("전화번호를 입력하세요");$("#tphone2").focus();return false;
	}else if(!check_Num(tphone2)){alert("전화번호는 숫자만 가능합니다");$("#tphone2").focus();return false;}

	var tphone  = [];
	var tphone_input = $("input[name='tphone[]']");
	$(tphone_input).each(function(i){
		var tmp_tphone = $("#tphone" + i).val();
			tphone.push(tmp_tphone);
	});
	var str_tphone = tphone.join("-");
	if(!check_Tphone2(str_tphone, "T")) return false;

<?php } else if($info_use['tphone']=="true") { ?>

	var tphone0 = $.trim($("#tphone0").val());
	var tphone1 = $.trim($("#tphone1").val());
	var tphone2 = $.trim($("#tphone2").val());

	if(tphone0 != "" || tphone1 != "" || tphone2 != "") {
		var tphone  = [];
		var tphone_input = $("input[name='tphone[]']");
		$(tphone_input).each(function(i){
			var tmp_tphone = $("#tphone" + i).val();
				tphone.push(tmp_tphone);
		});
		var str_tphone = tphone.join("-");
		if(!check_Tphone2(str_tphone, "T")) return false;
	}

<?php } ?>

<?php if($info_use['hphone']=="true" && $info_ess['hphone']=="true"){ ?>

	var hphone0 = $.trim($("#id_hphone1").val());
	var hphone1 = $.trim($("#id_hphone2").val());
	var hphone2 = $.trim($("#id_hphone3").val());

	if(hphone0 == "") {
		alert("휴대폰번호를 입력하세요");
		$("#id_hphone1").focus();
		return false;
	} else if(!check_Num(hphone0)) {
		alert("휴대폰번호는 숫자만 가능합니다.");
		$("#id_hphone1").focus();
		return false;
	}

	if(hphone1 == "") {
		alert("휴대폰번호를 입력하세요");
		$("#id_hphone2").focus();
		return false;
	} else if(!check_Num(hphone1)) {
		alert("휴대폰번호는 숫자만 가능합니다.");
		$("#id_hphone2").focus();
		return false;
	}

	if(hphone2 == "") {
		alert("휴대폰번호를 입력하세요");
		$("#id_hphone3").focus();
		return false;
	} else if(!check_Num(hphone2)) {
		alert("휴대폰번호는 숫자만 가능합니다");
		$("#id_hphone3").focus();
		return false;
	}

	var str_hphone = hphone0 + "-" + hphone1 + "-" + hphone2;
	if(!check_Hphone2(str_hphone, "올바른 휴대전화번호를 입력하세요.")) return false;

	/*
	작업일시	: 2022-02-24
	작업자명	: 이상민
	작업내용	: 정보수정 시 휴대폰번호 필수입력일 경우 중복확인
	*/
	var chk_hphone = true;

	$.ajax({
		type:"post"
		, async: false
		, url:  "/twcenter/member/ajax_hphonemail_check.php"
		, data : {"hphone":str_hphone,"ckuse":"CHK"}
		, success: function(data) {
			if(data == "D"){
				alert("입력하신 휴대폰번호로 등록한 회원이 있습니다.");
				$("#id_hphone1").val('');
				$("#id_hphone2").val('');
				$("#id_hphone3").val('');
				$("#id_hphone1").focus();
				chk_hphone = false;
			} else {

			}
		}
		, error: function(a,b,c){
			console.log(a);
			console.log(b);
			console.log(c);
			chk_hphone = false;
		}
	});

	if(!chk_hphone) return false;

<?php } else if($info_use['hphone']=="true") { ?>

	var hphone0 = $.trim($("#id_hphone1").val());
	var hphone1 = $.trim($("#id_hphone2").val());
	var hphone2 = $.trim($("#id_hphone3").val());

	if(hphone0 != "" || hphone1 != "" || hphone2 != "") {
		var str_hphone = hphone0 + "-" + hphone1 + "-" + hphone2;
		if(!check_Hphone2(str_hphone, "올바른 휴대전화번호를 입력하세요.")) return false;
		
		var chk_hphone = true;

		$.ajax({
			type:"post"
			, async: false
			, url:  "/twcenter/member/ajax_hphonemail_check.php"
			, data : {"hphone":str_hphone,"ckuse":"CHK"}
			, success: function(data) {
				if(data == "D"){
					alert("입력하신 휴대폰번호로 등록한 회원이 있습니다.");
					$("#id_hphone1").val('');
					$("#id_hphone2").val('');
					$("#id_hphone3").val('');
					$("#id_hphone1").focus();
					chk_hphone = false;
				} else {

				}
			}
			, error: function(a,b,c){
				console.log(a);
				console.log(b);
				console.log(c);
				chk_hphone = false;
			}
		});

		if(!chk_hphone) return false;
	}

<?php } ?>

<?php if($info_use['comtel']=="true" && $info_ess['comtel']=="true"){ ?>

	var comtel0 = $.trim($("#comtel0").val());
	var comtel1 = $.trim($("#comtel1").val());
	var comtel2 = $.trim($("#comtel2").val());

	if(comtel0 == ""){alert("전화번호를 입력하세요");$("#comtel0").focus();return false;
	}else if(!check_Num(comtel0)){alert("지역번호는 숫자만 가능합니다.");$("#comtel0").focus();return false;}

	if(comtel1 == ""){alert("전화번호를 입력하세요");$("#comtel1").focus();return false;
	}else if(!check_Num(comtel1)){alert("국번은 숫자만 가능합니다.");$("#comtel1").focus();return false;}

	if(comtel2 == ""){alert("전화번호를 입력하세요");$("#comtel2").focus();return false;
	}else if(!check_Num(comtel2)){alert("전화번호는 숫자만 가능합니다");$("#comtel2").focus();return false;}

	var comtel  = [];
	var comtel_input = $("input[name='comtel[]']");
	$(comtel_input).each(function(i){
		var tmp_comtel = $("#comtel" + i).val();
			comtel.push(tmp_comtel);
	});
	var str_comtel = comtel.join("-");
	if(!check_Tphone2(str_comtel, "T")) return false;

<?php } else if($info_use['comtel']=="true") { ?>

	var comtel0 = $.trim($("#comtel0").val());
	var comtel1 = $.trim($("#comtel1").val());
	var comtel2 = $.trim($("#comtel2").val());

	if(comtel0 != "" || comtel1 != "" || comtel2 != "") {
		var comtel  = [];
		var comtel_input = $("input[name='comtel[]']");
		$(comtel_input).each(function(i){
			var tmp_comtel = $("#comtel" + i).val();
				comtel.push(tmp_comtel);
		});
		var str_comtel = comtel.join("-");
		if(!check_Tphone2(str_comtel, "T")) return false;
	}

<?php } ?>

<?php if($info_use['email']=="true" && $info_ess['email']=="true"){ ?>

	var s_email_1 = $("#s_email_1");
	var s_email_2 = $("#s_email_2");

	if(!check_Email2(s_email_1, s_email_2, "올바른 이메일주소를 입력하세요.")) return false;

	/*
	작업일시	: 2022-02-24
	작업자명	: 이상민
	작업내용	: 회원가입 시 이메일 필수입력일 경우 중복확인
	*/

	var email  = s_email_1.val()+"@"+s_email_2.val();
	var chk_email = true;

	$.ajax({
		type:"post"
		, async: false
		, url:  "/twcenter/member/ajax_hphonemail_check.php"
		, data : {"email":email,"ckuse":"CHK"}
		, success: function(data) {
			console.log(data);
			if(data == "D"){
				alert("입력하신 이메일로 등록한 회원이 있습니다.");
				$("#s_email_1").val('');
				$("#s_email_2").val('');
				$("#email_select").val('');
				$("#s_email_1").focus();
				chk_email = false;
			} else {
			}
		}
		, error: function(){
			chk_email = false;
		}
	});

	if(!chk_email) return false;

<?php } ?>

<?php if($info_use['homepage']=="true" && $info_ess['homepage']=="true"){ ?>

	if(frm.homepage.value == ""){alert("홈페이지를 입력하세요.");frm.homepage.focus();return false;}

<?php } ?>

<?php if($info_use['address']=="true" && $info_ess['address']=="true"){ ?>

	if(frm.post.value == ""){alert("우편번호를 입력하세요");frm.post.focus();return false;}
	if(frm.address1.value == ""){alert("주소를 입력하세요");frm.address1.focus();return false;}
	if(frm.address2.value == ""){alert("상세주소를 입력하세요");frm.address2.focus();return false;}

<?php } ?>

<?php if($info_use['birthday']=="true" && $info_ess['birthday']=="true"){ ?>

	if(frm.birthday1.value == ""){alert("생년월일을 입력하세요.");frm.birthday1.focus();return false;}
	if(frm.birthday2.value == ""){alert("생년월일을 입력하세요.");frm.birthday2.focus();return false;}
	if(frm.birthday3.value == ""){alert("생년월일을 입력하세요.");frm.birthday3.focus();return false;}
	if(frm.bgubun[0].checked == false && frm.bgubun[1].checked == false){alert("양력 음력을 선택하세요.");return false;}

<?php } ?>

<?php if($info_use['marriage']=="true" && $info_ess['marriage']=="true"){ ?>
	if(frm.marriage[0].checked == false && frm.marriage[1].checked == false){alert("결혼여부를 선택하세요.");return false;}

<?php } ?>

<?php if($info_use['memorial']=="true" && $info_ess['memorial']=="true"){ ?>

	if(frm.memorial1.value == ""){alert("결혼기념일을 입력하세요.");frm.memorial1.focus();return false;}
	if(frm.memorial2.value == ""){alert("결혼기념일을 입력하세요.");frm.memorial2.focus();return false;}
	if(frm.memorial3.value == ""){alert("결혼기념일을 입력하세요.");frm.memorial3.focus();return false;}

<?php } ?>

<?php if($info_use['job']=="true" && $info_ess['job']=="true"){ ?>

	if(frm.job.value == ""){alert("직업을 선택하세요.");frm.job.focus();return false;}

<?php } ?>

<?php if($info_use['scholarship']=="true" && $info_ess['scholarship']=="true"){ ?>

	if(frm.scholarship.value == ""){alert("학력을 선택하세요.");frm.scholarship.focus();return false;}

<?php } ?>

<?php if($info_use['consph']=="true" && $info_ess['consph']=="true"){ ?>

	var consphLen=frm['consph[]'].length;

	if(consphLen == undefined){
		if( frm['consph[]'].checked == false ){alert("관심분야가 선택되지 않았습니다.");frm['consph[]'].focus();return false;  }
	}else {
		var ChkLike=0;
		for(i=0;i<consphLen;i++){if( frm['consph[]'][i].checked == true ){ ChkLike=1; break;}}
		if( ChkLike==0 ){alert("관심분야는 한개 이상 선택하셔야 합니다.");frm['consph[]'][0].focus();return false; }
	}

<?php } ?>

<?php if($info_use['hobby']=="true" && $info_ess['hobby']=="true"){ ?>

	if(frm.hobby.value == ""){alert("취미를 입력하세요.");frm.hobby.focus();return false;}

<?php } ?>

<?php if($info_use['income']=="true" && $info_ess['income']=="true"){ ?>

	if(frm.income.value == ""){alert("월평균 소득일 선택하세요.");frm.income.focus();return false;}

<?php } ?>

<?php if($info_use['car']=="true" && $info_ess['car']=="true"){ ?>

	if(frm.car[0].checked==false && frm.car[1].checked==false ){alert("자동차 소유여부를 선택하세요.");return false;}

<?php } ?>

<?php if($info_use['intro']=="true" && $info_ess['intro']=="true"){ ?>

	if(frm.intro.value == ""){alert("자기소개를 입력하세요.");frm.intro.focus();return false;}

<?php } ?>

<?php if($info_use['addinfo1']=="true" && $info_ess['addinfo1']=="true"){ ?>

	<?php echo $addinfo1_check ?>

<?php } ?>

<?php if($info_use['addinfo2']=="true" && $info_ess['addinfo2']=="true"){ ?>

	<?php echo $addinfo2_check ?>

<?php } ?>

<?php if($info_use['addinfo3']=="true" && $info_ess['addinfo3']=="true"){ ?>

	<?php echo $addinfo3_check ?>

<?php } ?>

<?php if($info_use['addinfo4']=="true" && $info_ess['addinfo4']=="true"){ ?>

	<?php echo $addinfo4_check ?>

<?php } ?>

<?php if($info_use['addinfo5']=="true" && $info_ess['addinfo5']=="true"){ ?>

	<?php echo $addinfo5_check ?>

<?php } ?>

}

// 닉네임 중복확인
function nickCheck(){
	var nick = document.myinfoFrm.nick.value;
	var url = "/twcenter/member/nick_check.php?nick=" + nick;
	window.open(url, "nickCheck", "width=410, height=280, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=150, top=150");
}

function closeDaumPostcode() {
	$("#LayerPop").hide();
}

function postSearch(kind) {

	if(kind == undefined) kind = "";
	new daum.Postcode({
		oncomplete: function(data) {

			var frm = document.myinfoFrm;

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

			eval('frm.'+kind+'post').value = data.zonecode;
			eval('frm.'+kind+'address1').value = fullAddr;

			if(eval('frm.'+kind+'address1') != null)
				eval('frm.'+kind+'address2').focus();
		}
	}).open();

}

$(function(){

	$("#nick").bind("keyup change", function(e) {

		var nick       = $("#nick").val();

		$.ajax({
			type:"post"
			, async: false
			, url:  "/twcenter/member/ajax_nick_check.php"
			, data : {"nick":nick,"ckuse":"CHK"}
			, success: function(data) {
				var sp_val = data.split("|");
				if(sp_val[1] > 0){
					$("#resultNick").html("<font color=red>"+sp_val[0]+"</font> 는 이미 사용중인 닉네임 입니다.");
					$("#Nduplication").val('F');
				} else if(sp_val[2] == 0) {
					$("#nick").val('');
					$("#resultNick").html("(닉네임을 입력해주세요.)");
				} else if(sp_val[3] == 'F') {
					$("#resultNick").html("<font color=red>"+sp_val[0]+"</font> 는 사용할 수 없는 닉네임 입니다.");
					$("#Nduplication").val('F');
				} else if(sp_val[3] == 'O') {
					$("#resultNick").html("<font color=red>"+sp_val[0]+"</font> 는 사용가능한 닉네임 입니다.");
					$("#Nduplication").val('O');
				} else {
					$("#resultNick").html("<font color=red>"+sp_val[0]+"</font> 는 사용가능한 닉네임 입니다.");
					$("#Nduplication").val('O');
				}
			}	
			, error: function(){
			}
		});
		return false;

	});

});

<?php if($info_use['hphone'] == true && $info_ess['hphone'] == true){ ?>
function hphoneCheck(){

	var hphone1 = $.trim($("#id_hphone1").val());
	var hphone2 = $.trim($("#id_hphone2").val());
	var hphone3 = $.trim($("#id_hphone3").val());

	var hphone  = hphone1+"-"+hphone2+"-"+hphone3;

	$.ajax({
		type:"post"
		, async: false
		, url:  "/twcenter/member/ajax_hphonemail_check.php"
		, data : {"hphone":hphone,"ckuse":"CHK"}
		, success: function(data) {
			if(data == "D"){
				alert("입력하신 휴대폰번호로 등록한 회원이 있습니다.");
				$("#id_hphone1").val('');
				$("#id_hphone2").val('');
				$("#id_hphone3").val('');
				$("#id_hphone1").focus();
				return false;
			}
		}
		, error: function(){
		}
	});
	return false;
	
}
<?php } ?>

<?php if($info_use['email']=="true" && $info_ess['email']=="true"){ ?>

	function emailCheck(){

		var s_email_1 = $.trim($("#s_email_1").val());
		var s_email_2 = $.trim($("#s_email_2").val());

		var email  = s_email_1+"@"+s_email_2;
		$.ajax({
			type:"post"
			, async: false
			, url:  "/twcenter/member/ajax_hphonemail_check.php"
			, data : {"email":email,"ckuse":"CHK"}
			, success: function(data) {
				if(data == "D"){
					alert("입력하신 이메일로 등록한 회원이 있습니다.");
					$("#s_email_1").val('');
					$("#s_email_2").val('');
					$("#email_select").val('');
					$("#s_email_1").focus();
					return false;
				} else {
				}
			}
			, error: function(){
			}
		});
		return false;
		
	}

<?php } ?>
//-->
</script>

<?php
// 이미지 리사이즈를 위해서 처리하는 부분
echo "<table border=0 cellspacing=0 cellpadding=0 style='width:500px;height:0px; display:none' id='wiz_get_table_width'>
				<col width=100%></col>
				<tr>
					<td><img src='' border='0' name='wiz_target_resize' width='0' height='0'></td>
				</tr>
			</table>";
$_ResizeCheck = true;
 ?>

<?php include $_SERVER['DOCUMENT_ROOT'].'/'.$skin_dir.'/myinfo.php'; ?>

<?php view_img_resize() ?>