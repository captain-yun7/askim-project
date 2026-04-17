<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/mem_info.php";

// 회원정보
if($mode == "") $mode = "insert";
if($mode == "update"){

	$sql = "SELECT * FROM wiz_member WHERE (idx = '$idx' OR (id = '$id' AND id != ''))";
	$result = query($sql);
	$my_info = sql_fetch_arr($result);

	$c_visit_time = strtotime(substr($my_info['visit_time'],0,10));
	$c_this_time  = time();
	$one_year_later_notice   = date("Y-m-d",strtotime("+11 month", $c_visit_time));	// SMS 발송예정일
	$one_year_later_dormancy = date("Y-m-d",strtotime("+1 year", $c_visit_time));	// 휴면처리

	if($site_info['login_pw_chg'] == "Y") {
		$pw_update = time_chk($my_info['pw_update']);
		if($pw_update >= $site_info['login_pwchg_day']) {
			$alt_msg = "<font color='red'>* 비밀번호를 주기적으로 변경해주세요. (마지막 변경일 : ".substr($my_info['pw_update'],0,10).")</font>";
		} else {
			$alt_msg = "";
		}
	} else {
		$alt_msg = "";
	}
	
	// 총 주문액(주문 테이블, 주문완료,배송준비,배송처리,배송완료)
	$sql = "
		SELECT SUM(total_price) AS total_price 
		  FROM wiz_order 
		 WHERE send_id = '".$my_info['id']."' 
		   AND (status = 'OY' OR status = 'DR' OR status = 'DI' OR status = 'DC') 
	";
	$result = query($sql);
	$row = sql_fetch_obj($result);
	$total_price = $row->total_price;

	// 적립금
	$sql = "SELECT SUM(reserve) as reserve FROM wiz_reserve WHERE memid = '".$my_info['id']."'";
	$result = query($sql);
	$row = sql_fetch_obj($result);
	$reserve = $row->reserve;
}

$param = "slevel=".$slevel."&searchopt=".$searchopt."&searchkey=".$searchkey."&page=".$page."&".$menucodeParam;

function createObject($no,$type,$size,$flist, $value){

	global $upfile_idx;
	global $idx;

	$fname = "f".$no;

	// 반복하지 않는 속성
	$tmp_type = Array("","address","birthday","phone","email");

	for($ii = 0;$ii < count($tmp_type); $ii++) {
		if(!strcmp($type, $tmp_type[$ii])) $flist = " ";
	}

	$tmp_flist = explode("|",$flist);

	if($type == "select") $finput = "<select id=\"".$fname."_0\" name=\"fname[".$no."][]\" class='select'><option value=''>--선택--</option>";

	for($ii=0;$ii<count($tmp_flist);$ii++){

		//if($tmp_flist[$ii] != ""){
		if($type == "text"){

			if(!isset($value)) $value = '';
			$tmp_value = explode("|", $value);

			$finput .= "<input type=\"text\" id=\"".$fname."_$ii\" name=\"fname[".$no."][]\" class='input' size=\"".$size."\" value=\"".$tmp_value[$ii]."\">".$tmp_flist[$ii];

		} else if($type == "file") {
			if(!isset($value)) $value = '';
			$tmp_value = explode("|", $value);

			$upfile_idx++;
			$finput .= "<input type=\"file\" id=\"".$fname."_$ii\" name=\"upfile".$upfile_idx."\" class='input' size=\"".$size."\">";
			$finput .= "<input type=\"hidden\" name=\"tmp_upfile_".$no."_".$upfile_idx."\" value=\"".$tmp_value[$ii]."\">";

			if(!empty($tmp_value[$ii])) {
				$finput .= " <a href=\"/twcenter/data/member/".$tmp_value[$ii]."\" target=\"_blank\">".$tmp_value[$ii]."</a> ";
				$finput .= " <a href=\"member_save.php?mode=addfile_del&upfile=".$tmp_value[$ii]."&idx=".$idx."&no=".$no."&".$param."\"><font color='red'>[삭제]</font></a> ";
			}
			$finput .= $tmp_flist[$ii];

		} else if($type == "radio") {

			$tmp_value = $value;
			if(!strcmp($tmp_flist[$ii], $tmp_value)) $tmp_check = "checked";
			else $tmp_check = "";

			$finput .= "<span style=\"vertical-align: middle\"><input type=\"radio\" id=\"".$fname."_$ii\" name=\"fname[".$no."]\" value=\"".$tmp_flist[$ii]."\" ".$tmp_check."></span>".$tmp_flist[$ii]."&nbsp; ";

		} else if($type == "checkbox") {

			$tmp_value = explode("|", $value);
			for($jj = 0; $jj < count($tmp_value); $jj++) {
				if(!strcmp($tmp_flist[$ii], $tmp_value[$jj])) ${"tmp_check".$ii} = "checked";
			}

			$finput .= "<span style=\"vertical-align: middle\"><input type=\"checkbox\" id=\"".$fname."_$ii\" name=\"fname[".$no."][]\" value=\"".$tmp_flist[$ii]."\" ".${"tmp_check".$ii}."></span>".$tmp_flist[$ii]."&nbsp; ";

		} else if($type == "textarea") {

			$tmp_value = $value;

			$finput .= "<textarea id=\"".$fname."_$ii\" name=\"fname[".$no."][]\" rows=\"".$size."\" class=\"input\" style=\"width:99%\">".$tmp_value."</textarea>";

		} else if($type == "select") {

			$tmp_value = $value;
			if(!strcmp($tmp_flist[$ii], $tmp_value)) $tmp_select = "selected";
			else $tmp_select = "";

			$finput .= "<option value=\"".$tmp_flist[$ii]."\" ".$tmp_select.">".$tmp_flist[$ii]."</option>";

		} else if($type == "address") {
			
			if(!isset($value)) $value = '';
			$tmp_value	= explode("|", $value);

			$finput .= "<input type=\"text\" id=\"".$fname."_1\" name=\"".$fname."_post\" onClick=searchZip(\"".$fname."_\"); class=\"input\" size=\"7\" readonly value=\"".$tmp_value[0]."\"> ";
			$finput .= "<input type=\"button\" value=\"우편번호검색\" onClick=searchZip(\"".$fname."_\"); class=\"base_btn2\"><span class=\"tip_br5\"></span>";
			$finput .= "<input type=\"text\" id=\"".$fname."_2\" name=\"".$fname."_address1\" class=\"input input580\" size=\"".$size."\" value=\"".$tmp_value[1]."\"><span class=\"tip_br5\"></span>";
			$finput .= "<input type=\"text\" id=\"".$fname."_3\" name=\"".$fname."_address2\" class=\"input input580\" size=\"".$size."\" value=\"".$tmp_value[2]."\">";


		} else if($type == "pdate") {

			$tmp_value = explode("~", $value);

			$finput .= "<input type=\"text\" id=\"".$fname."_".$ii."\" name=\"fname[".$no."][]\" class=\"input\" size=\"".$size."\" onClick=Calendar5(\"document.frm\",\"".$fname."_".$ii."\"); readonly value=\"".$tmp_value[$ii]."\"> ";
			$finput .= "<input type=\"button\" value=\"달력\" onClick=Calendar5(\"document.frm\",\"".$fname."_".$ii."\"); class=\"button\">".$tmp_flist[$ii];

		} else if($type == "tdate") {

			$tmp_value	= explode("~", $value);
			$tmp_dt		= explode("&nbsp;", $tmp_value[$ii]);
			$tmp_date	= explode("-", $tmp_dt[0]);

			$finput .= "<select id=\"".$fname."_".$ii."_0\" name=\"fname[".$no."][]\" class=\"select\"><option value=\"\">--선택--</option>";

			for($jj=2018;$jj<=date("Y")+1;$jj++){

				if(!strcmp($jj, $tmp_date[0])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>";
			}

			$finput .= "</select>년 ";

			$finput .= "<select id=\"".$fname."_".$ii."_1\" name=\"fname[".$no."][]\" class=\"select\"><option value=\"\">--선택--</option>";
			
			for($jj=1;$jj<=12;$jj++){

				if($jj<10) $jj = "0".$jj;

				if(!strcmp($jj, $tmp_date[1])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>";

			}
			$finput .= "</select>월 ";

			$finput .= "<select id=\"".$fname."_".$ii."_2\" name=\"fname[".$no."][]\" class=\"select\"><option value=\"\">--선택--</option>";

			for($jj=1;$jj<=31;$jj++){

				if($jj<10) $jj = "0".$jj;

				if(!strcmp($jj, $tmp_date[2])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>";
			}

			$finput .= "</select>일 ";

			$finput .= "<select id=\"".$fname."_".$ii."_3\" name=\"fname[".$no."][]\" class=\"select\"><option value=\"\">--선택--</option>";

			for($jj=1;$jj<=24;$jj++){

				if($jj<10) $jj = "0".$jj;

				if(!strcmp($jj, $tmp_dt[1])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>";
			}

			$finput .= "</select>시 ".$tmp_flist[$ii];


		} else if($type == "birthday") {

			$tmp_value = explode("|", $value);

			$finput .= "<select id=\"".$fname."_".$ii."_0\" name=\"fname[".$no."][]\" class=\"select\"><option value=\"\">--선택--</option>";

			for($jj=1900;$jj<=date('Y');$jj++){

				if(!strcmp($jj, $tmp_value[0])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>";

			}

			$finput .= "</select>년 ";

			$finput .= "<select id=\"".$fname."_".$ii."_1\" name=\"fname[".$no."][]\" class=\"select\"><option value=\"\">--선택--</option>";

			for($jj=1;$jj<=12;$jj++){
				if($jj<10) $jj = "0".$jj;

				if(!strcmp($jj, $tmp_value[1])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>";
			}
			$finput .= "</select>월 ";

			$finput .= "<select id=\"".$fname."_".$ii."_2\" name=\"fname[".$no."][]\" class=\"select\"><option value=\"\">--선택--</option>";

			for($jj=1;$jj<=31;$jj++){
				if($jj<10) $jj = "0".$jj;

				if(!strcmp($jj, $tmp_value[2])) $tmp_select = "selected";
				else $tmp_select = "";

				$finput .= "<option value=\"".$jj."\" ".$tmp_select.">".$jj."</option>";
			}
			$finput .= "</select>일 ";

		} else if($type == "phone" || $type == "tel") {

				$tmp_value = explode("|", $value);

				$finput .= "<input type=\"text\" id=\"".$fname."_0\" name=\"fname[".$no."][]\" class=\"input\" size=\"".$size."\" maxlength=\"4\" value=\"".$tmp_value[0]."\"> - ";
				$finput .= "<input type=\"text\" id=\"".$fname."_1\" name=\"fname[".$no."][]\" class=\"input\" size=\"".$size."\" maxlength=\"4\" value=\"".$tmp_value[1]."\"> - ";
				$finput .= "<input type=\"text\" id=\"".$fname."_2\" name=\"fname[".$no."][]\" class=\"input\" size=\"".$size."\" maxlength=\"4\" value=\"".$tmp_value[2]."\">".$tmp_flist[$ii];

		} else if($type == "email") {

			$tmp_value = $value;

			$finput .= "<input type=\"text\" id=\"".$fname."_$ii\" name=\"fname[".$no."][]\" class=\"input\" size=\"".$size."\" value=\"".$tmp_value."\">".$tmp_flist[$ii];
		}

		//}
	}

	if($type == "select") $finput .= "</select>";

	return $finput;

}

$sql = "select fprior, ftype, fsize, flist from wiz_formfield where fidx = 'addinfo' order by fprior asc";
$result = query($sql);
while($row = sql_fetch_arr($result)) {
	$no = $row['fprior'];

	${'addinfo'.$no.'_input'} = createObject($no,$row['ftype'],$row['fsize'],$row['flist'], $my_info["addinfo".$no]);
}

include "../head.php";
echo $DAUM_POSTCODE.PHP_EOL;
echo "<script type=\"text/javascript\" src=\"/comm/js/upload_file_limit.js\"></script>".PHP_EOL;

?>
<script language="javascript">
<!--
function inputCheck(frm){

	var sdigit = "<?php echo $mem_info['sdigit'] ?>";
	var edigit = "<?php echo $mem_info['edigit'] ?>";
	var spechk = "<?php echo $spectial_Char ?>";

	if(frm.id.value == ""){
		alert("아이디를 입력하세요");
		frm.id.focus();
		return false;
	}
	var duplication = $("#duplication").val();
	if(duplication == 'F'){
		alert("이미 사용중이거나 사용할 수 없는 아이디 입니다");
		frm.id.focus();
		return false;
	}
	<?php if($mode == "insert") { ?>
		if(frm.passwd.value == ""){
			alert("비밀번호를 입력하세요");
			frm.passwd.focus();
			return false;
		}

		if(frm.id.value == frm.passwd.value) {
			alert("아이디와 동일하게 비밀번호를 설정하셨습니다.");
			frm.passwd.value = "";
			frm.passwd.focus();
			return false;
		}

		if(!check_Pass($.trim(frm.passwd.value), sdigit, edigit, spechk)){
			frm.passwd.focus();
			return false;
		}

		if(frm.passwd.value.search(/\s/) != -1){
			alert("비밀번호는 공백없이 입력해주세요.");
			frm.passwd.focus();
			return false;
		}

		var dup_Reg = /(\w)\1\1\1/;
		if(dup_Reg.test(frm.passwd.value)){
			alert("비밀번호에 같은 문자를 4번이상 사용하실 수 없습니다.");
			frm.passwd.focus();
			return false;
		}

	<?php } else if($mode == "update") {?>

		if(frm.passwd.value != ""){

			if(frm.id.value == frm.passwd.value) {
				alert("아이디와 동일하게 비밀번호를 설정하셨습니다.");
				frm.passwd.value = "";
				frm.passwd.focus();
				return false;
			}

			if(!check_Pass($.trim(frm.passwd.value), sdigit, edigit, spechk)){
				frm.passwd.value = "";
				frm.passwd.focus();
				return false;
			}

			if(frm.passwd.value.search(/\s/) != -1){
				alert("비밀번호는 공백없이 입력해주세요.");
				frm.passwd.focus();
				return false;
			}

			var dup_Reg = /(\w)\1\1\1/;
			if(dup_Reg.test(frm.passwd.value)){
				alert("비밀번호에 같은 문자를 4번이상 사용하실 수 없습니다.");
				frm.passwd.focus();
				return false;
			}

		}

	<?php } ?>

	if(frm.name.value == ""){
		alert("이름을 입력하세요");
		frm.name.focus();
		return false;
	}

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
	console.log(tphone2);
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

	if(hphone0 == ""){alert("휴대폰번호를 입력하세요");$("#id_hphone1").focus();return false;
	}else if(!check_Num(hphone0)){alert("휴대폰번호는 숫자만 가능합니다.");$("#id_hphone1").focus();return false;}

	if(hphone1 == ""){alert("휴대폰번호를 입력하세요");$("#id_hphone2").focus();return false;
	}else if(!check_Num(hphone1)){alert("휴대폰번호는 숫자만 가능합니다.");$("#id_hphone2").focus();return false;}

	if(hphone2 == ""){alert("휴대폰번호를 입력하세요");$("#id_hphone3").focus();return false;
	}else if(!check_Num(hphone2)){alert("휴대폰번호는 숫자만 가능합니다");$("#id_hphone3").focus();return false;}

	var str_hphone = hphone0 + "-" + hphone1 + "-" + hphone2;
	if(!check_Hphone2(str_hphone, "올바른 휴대전화번호를 입력하세요.")) return false;

<?php } else if($info_use['hphone']=="true") { ?>

	var hphone0 = $.trim($("#id_hphone1").val());
	var hphone1 = $.trim($("#id_hphone2").val());
	var hphone2 = $.trim($("#id_hphone3").val());

	if(hphone0 != "" || hphone1 != "" || hphone2 != "") {
		var str_hphone = hphone0 + "-" + hphone1 + "-" + hphone2;
		if(!check_Hphone2(str_hphone, "올바른 휴대전화번호를 입력하세요.")) return false;
	}

<?php } ?>

<?php if($info_use['email']=="true" && $info_ess['email']=="true"){ ?>
	var s_email_1 = $("#s_email_1");
	var s_email_2 = $("#s_email_2");

	if(!check_Email2(s_email_1, s_email_2, "올바른 이메일주소를 입력하세요.")) return false;
<?php } ?>

<?php if($info_use['comtel']=="true" && $info_ess['comtel']=="true"){ ?>

	var comtel0 = $("#comtel0").val();
	var comtel1 = $("#comtel1").val();
	var comtel2 = $("#comtel2").val();

	if(comtel0 == ""){alert("회사전화번호를 입력하세요");$("#comtel0").focus();return false;
	}else if(!check_Num(comtel0)){alert("지역번호는 숫자만 가능합니다.");$("#comtel0").focus();return false;}

	if(comtel1 == ""){alert("회사전화번호를 입력하세요");$("#comtel1").focus();return false;
	}else if(!check_Num(comtel1)){alert("국번은 숫자만 가능합니다.");$("#comtel1").focus();return false;}

	if(comtel2 == ""){alert("회사전화번호를 입력하세요");$("#comtel2").focus();return false;
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

	var comtel0 = $("#comtel0").val();
	var comtel1 = $("#comtel1").val();
	var comtel2 = $("#comtel2").val();

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

<?php }else{ ?>
	/* 2021-05-21 필수입력이 아니어도, 닉네임 입력값이 있다면 검사*/
	if(frm.nick.value !=""){
		var Nduplication = $("#Nduplication").val();
		if(Nduplication == 'F'){
			alert("이미 사용중이거나 사용할 수 없는 닉네임 입니다");
			$("#nick").focus();
			return false;
		}
	}
<?php } ?>

}

// 고객 메일발송
function sendEmail(seluser){
	var url = "send_email.php?seluser=" + seluser;
	window.open(url,"sendEmail","height=600, width=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

function sendEmailDormancy(seluser){
	var url = "mail_popup_dormancy.php?seluser=" + seluser;
	window.open(url,"sendEmailDormancy","height=700, width=800, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

function sendSmsDormancy(seluser){
	var url = "sms_popup_dormancy.php?seluser=" + seluser;
	window.open(url,"sendSms","height=700, width=350, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 주소찾기
function searchZip(kind) {

	if(kind == undefined) kind = "";
	new daum.Postcode({
		oncomplete: function(data) {

			var frm = document.frm;

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

//주소찾기 (배송지관리)
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
	

			document.getElementById("re_post_"+kind).value = data.zonecode;
			document.getElementById("re_addr_"+kind).value = fullAddr;

			if(document.getElementById("re_addr_"+kind).value != null)
				document.getElementById("re_addr2_"+kind).focus();
		}
	}).open();

}

// 회원별 포인트내역
function pointList(id,name){
	var url = "member_point.php?id=" + id + "&name=" + name;
	window.open(url,"pointList","height=400, width=800, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}
// 아이디 중복확인
function idCheck(){
	var id = document.frm.id.value;
	var url = "../member/id_check.php?name=id&id=" + id;
	window.open(url, "idCheck", "width=500, height=200, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=150, top=150");
}

// 회원별 적립금내역
function reserveList(id,name){
	if(id == ""){
		alert("적립금내역이 존재하지 않습니다.");
		return false;
	} else {
		var url = "member_reserve.php?id=" + id + "&name=" + name;
		window.open(url,"reserveList","height=400, width=800, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	}
}

// 회원별 구매내역
function orderList(id,name){
	if(id == ""){
		alert("총 주문액이 존재하지 않습니다.");
		return false;
	} else {
		var url = "member_order.php?id=" + id + "&name=" + name;
		window.open(url,"orderList","height=400, width=800, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	}
}

function ReleaseRestore(id){
	if(id == ""){
		alert("휴면처리를 위한 필수값이 존재하지 않습니다.");
		return false;
	} else {
		var url = "member_save.php?seluser=" + id + "&mode=dormancyChg";
		document.location = url;
	}
}


$(function(){
<? if($mode != "update") { ?>
	$("#id").bind("keyup change", function(e) {

		var id       = $("#id").val();

		$.ajax({
			type:"post"
			, async: false
			, url:  "/twcenter/member/ajax_id_check.php"
			, data : {"id":id,"ckuse":"CHK"}
			, success: function(data) {
				console.log(data);
				var sp_val = data.split("|");

				if(sp_val[5] == "FAIL"){

					if(sp_val[3] < 3 || sp_val[3] > 12){
						//$("#resultMsg").html("<font color=red>아이디는 영문+숫자조합으로 3~12문자로 입력하세요.</font>");
						$("#resultMsg").html("<font color=red>아이디는 영문 또는 영문+숫자조합 3~12자로 입력하세요.</font>");
					} else {
						//$("#resultMsg").html("<font color=red>아이디는 영문+숫자조합으로 입력하세요.</font>");
						$("#resultMsg").html("<font color=red>아이디는 영문 또는 영문+숫자조합 3~12자로 입력하세요.</font>");
					}
				} else {

					var idchk   = sp_val[0];
					var idReg   = /^[a-z]+[a-z0-9]{2,11}$/g;

					if(idchk) {

						if(idchk.search(/\s/) != -1){
							$("#resultMsg").html("<font color=red>아이디는 공백없이 입력해주세요.</font>");
						} else if(!idReg.test(idchk)) {
							$("#resultMsg").html("<font color=red>아이디는 영문 또는 영문+숫자조합으로 3~12자리만 가능합니다.</font>");
						} else if(!check_Char(idchk)){
							$("#resultMsg").html("<font color=red>아이디는 특수문자를 사용할수 없습니다.</font>");
						} else if(sp_val[1] > 0){
							$("#resultMsg").html("<font color=red>"+sp_val[0]+"</font> 는 이미 사용중인 아이디 입니다. 아이디를 확인해주세요.");
							$("#duplication").val('F');
						} else if(sp_val[2] > 0) {
							$("#id").val('');
							$("#resultMsg").html("<font color=red>"+sp_val[0]+"</font> 는 사용할 수 없는 아이디 입니다.");
						} else if(sp_val[3] == 0) {
							$("#id").val('');
							$("#resultMsg").html("(영문 또는 영문+숫자조합 3~12자, 가입 후 ID변경은 불가함을 알려드립니다.)");
						} else if(sp_val[4] == 'F') {
							$("#resultMsg").html("<font color=red>"+sp_val[0]+"</font> 는 사용할 수 없는 아이디 입니다.");
							$("#duplication").val('F');
						} else if(sp_val[4] == 'O') {
							$("#resultMsg").html("<font color=red>"+sp_val[0]+"</font> 는 사용가능한 아이디 입니다.");
							$("#duplication").val('O');
						} else {
							$("#resultMsg").html("<font color=red>"+sp_val[0]+"</font> 는 사용가능한 아이디 입니다.");
							$("#duplication").val('O');
						}

					}

				}

			}
			, error: function(){
			}
		});
		e.preventDefault();

	});
<? } ?>
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
-->
</script>
</head>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">회원관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">회원을 검색/수정 관리합니다.</td>
	</tr>
</table>

<br>
<?php if($mode == "update"){ ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="right"><b>가입일</b> : <?php echo $my_info['wdate']?> &nbsp; <b>로그인 횟수</b> : <?php echo $my_info['visit']?> &nbsp; <b>마지막 로그인</b> : <?php echo $my_info['visit_time']?></td>
	</tr>
</table>
<?php } ?>

<form name="frm" action="member_save.php?<?php echo $param?>" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="<?php echo $mode?>">
<input type="hidden" name="duplication" id="duplication">
<input type="hidden" name="Nduplication" id="Nduplication">

<?php
if($wiz_admin['id'] != "" && $idx == ""){
	$idxresult = query("select idx from wiz_member where id = '$id'");
	$memidx = sql_fetch_arr($idxresult);
	echo '<input type="hidden" name="idx" value="'.$memidx['idx'].'">';
	echo '<input type="hidden" name="prdparam" value="'.urlencode($prdparam ?? '').'">';
}else{
	echo '<input type="hidden" name="idx" value="'.$idx.'">';
}
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" class="t_name">아이디</td>
					<td width="35%" class="t_value">
						<input name="id" id="id" type="text" value="<?php echo $my_info['id']?>" class="input"<? if(!strcmp($mode, "update")) echo " readonly";?>>
						<div><span id="resultMsg"></span></div>
						<?php //if(strcmp($mode, "update")) { ?>
						<!-- <input type="button" value="중복체크" class="base_btn2 gray" onclick="idCheck()"> -->
						<?php //} ?>
					</td>
					<td width="15%" class="t_name">비밀번호</td>
					<td width="35%" class="t_value">
						<input name="passwd" type="text" value="" class="input" maxlength="<?php echo $mem_info['edigit'] ?>" size="30"><br>
						<?php echo $alt_msg ?>
					</td>
				</tr>
				<tr>
					<td class="t_name">이름</td>
					<td class="t_value"><input name="name" type="text" value="<?php echo $my_info['name']?>" class="input"></td>
					<td class="t_name">회원등급</td>
					<td class="t_value">
						<select name="level" class="select">
						<?php echo level_list();?>
						</select>
						<script language="javascript">
						<!--
						level = document.frm.level;
						for(ii=0; ii<level.length; ii++){
							if(level.options[ii].value == "<?php echo $my_info['level']?>")
							level.options[ii].selected = true;
						}
						-->
						</script>
					</td>
				</tr>
				<?php
				$menu_tmp = explode("/",$site_info['menu_use']);
				for($ii=0; $ii<count($menu_tmp); $ii++){
					$menu_arr[$menu_tmp[$ii]] = true;
				}
				?>
				<?php if($menu_arr["PRODUCT"] == true){?>
				<tr>
					<td class="t_name">총 주문액</td>
					<?php
					// 2018-06-15 쇼핑몰관리>운영정보설정에서 적립급정보를 사용안함 선택시 적립금내역 안보이게
					$sql = "SELECT reserve_use FROM wiz_operinfo";
					$result = query($sql);
					$oper_info = sql_fetch_arr($result);

					if($oper_info['reserve_use'] == "Y"){
					?>
					<td class="t_value"><?php echo number_format($total_price ?? 0)?>원 <input type="button" value="상세보기" class="base_btm reg" onclick="orderList('<?php echo $my_info['id']?>','<?php echo $my_info['name']?>')"></td>
					<td class="t_name">적립금</td>
					<td class="t_value"><?php echo number_format($reserve ?? 0)?>원 <input type="button" value="상세보기" class="base_btm reg" onclick="reserveList('<?php echo $my_info['id']?>','<?php echo $my_info['name']?>')"></td>
					<?php
					} else {
					?>
					<td class="t_value" colspan="3"><?php echo number_format($total_price)?>원 <input type="button" value="상세보기" class="base_btm reg" onclick="orderList('<?php echo $my_info['id']?>','<?php echo $my_info['name']?>')"></td>
					<?php
					}
					?>
				</tr>
				<?php } ?>
				<?php if($info_use['nick'] == true){ ?>
				<tr>
					<td class="t_name">닉네임</td>
					<td colspan="3" class="t_value">
						<input name="nick" id="nick" type="text" value="<?php echo $my_info['nick']?>" class="input">&nbsp;<span id="resultNick"></span>
					</td>
				</tr>
				<?php } ?>
				<?php 
				if($my_info['visit_time'] != '0000-00-00 00:00:00') {
					if(!isset($one_year_later_notice)) $one_year_later_notice = '';
					
					if((strtotime($one_year_later_notice) <= $c_this_time) && $mode == "update") { 
						if(!empty($my_info['id']) && $site_info['sms_use'] == "Y") {
							$sms_notice   = "<input type='button' class='base_btn_s blue2' value='휴면처리공지 SMS발송' onclick=sendSmsDormancy('".$my_info['id']."');>";
						}
						if(!empty($my_info['email'])) {
							$email_notice = "<input type='button' class='base_btn_s blue3' value='휴면처리공지 이메일발송' onclick=sendEmailDormancy('".$my_info['email']."');>";
						}
				?>
				<tr>
					<td class="t_name">휴면처리공지</td>
					<td colspan="3" class="t_value">
						<?php echo $sms_notice ?> <?php echo $email_notice ?>
					</td>
				</tr>
				<?php
					}
				}
				?>

				<?php if($site_info['point_use'] == "Y"){ ?>
				<tr>
					<td class="t_name">포인트</td>
					<td class="t_value" colspan="3">
						<?php echo  number_format(get_point($my_info['id'])) ?> 포인트
						<input type="button" value="상세보기" class="base_btm reg" onclick="javascript:pointList('<?php echo $my_info['id']?>', '<?php echo $my_info['name']?>')">
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['photo'] == true){ ?>
				<tr>
					<td class="t_name">회원사진</td>
					<td class="t_value" colspan="3">
						<?php
						if($my_info['photo'] != "" && file_exists(WIZHOME_PATH."/data/member/".$my_info['photo'])){
							echo "<img src=/twcenter/data/member/".$my_info['photo'].">";
							echo "<input type='checkbox' name='delphoto' value='Y'>";
							echo "<font color='red'>삭제</font> <br>";
						}
						?>
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled" style="vertical-align:top;">
							<label for="input-file">파일 업로드</label>
							<input type="file" name="photo" id="input-file" class="upload-hidden" style="vertical-align:top;">
						</div>
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['icon'] == true){ ?>
				<tr>
					<td class="t_name">회원아이콘</td>
					<td class="t_value" colspan="3">
						<?php
						if($my_info['icon'] != "" && file_exists(WIZHOME_PATH."/data/member/".$my_info['icon'])){
							echo "<img src=/twcenter/data/member/".$my_info['icon']." width='".$icon_size."' height='".$icon_size."'>";
							echo "<input type='checkbox' name='delicon' value='Y'>";
							echo "<font color='red'>삭제</font> <br>";
						}
						?>
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled" style="vertical-align:top;">
							<label for="input-file1">파일 업로드</label>
							<input type="file" name="icon" id="input-file1" class="upload-hidden" style="vertical-align:top;">
						</div>

					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['resno'] == true){ ?>
				<tr>
					<td class="t_name">주민번호</td>
					<td colspan="3" class="t_value">
						<?php list($resno1, $resno2) = explode("-",$my_info['resno']); ?>
						<input type="text" name="resno1" value="<?php echo $resno1?>" size="9" class="input"> -
						<input type="text" name="resno2" value="<?php echo $resno2?>" size="9" class="input">
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['tphone'] == true){ ?>
				<tr>
					<td class="t_name">전화번호</td>
					<td colspan="3" class="t_value">
						<?php if(!isset($my_info['tphone'])) $my_info['tphone'] = ''; ?>
						<?php list($tphone1, $tphone2, $tphone3) = explode("-",$my_info['tphone']); ?>
						<input type="text" name="tphone[]" value="<?php echo $tphone1?>" id="tphone0" size="5" maxlength="3" class="input Onum"> - 
						<input type="text" name="tphone[]" value="<?php echo $tphone2?>" id="tphone1" size="5" maxlength="4" class="input Onum"> - 
						<input type="text" name="tphone[]" value="<?php echo $tphone3?>" id="tphone2" size="5" maxlength="4" class="input Onum">
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['hphone'] == true){ ?>
				<tr>
					<td class="t_name">휴대폰</td>
					<td colspan="3" class="t_value">
						<?php if(!isset($my_info['hphone'])) $my_info['hphone'] = ''; ?>
						<?php list($hphone1, $hphone2, $hphone3) = explode("-",$my_info['hphone']); ?>
						<input type="text" name="hphone[]" id="id_hphone1" value="<?php echo $hphone1?>" id="id_hphone1" size="5" maxlength="3" class="input Onum"> - 
						<input type="text" name="hphone[]" id="id_hphone2" value="<?php echo $hphone2?>" id="id_hphone2" size="5" maxlength="4" class="input Onum"> - 
						<input type="text" name="hphone[]" id="id_hphone3" value="<?php echo $hphone3?>" id="id_hphone3" size="5" maxlength="4" class="input Onum" <?php echo $hphone_chk ?>> 
						<?php if($info_use['resms'] == true){ ?>
							<span style="vertical-align: middle"><input type="checkbox" name="resms" value="Y" <?php if($my_info['resms'] == "Y") echo "checked"; ?>> SMS수신에 동의합니다.</span>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['comtel'] == true){ ?>
				<tr>
					<td class="t_name">회사전화</td>
					<td colspan="3" class="t_value">
						<?php list($comtel1, $comtel2, $comtel3) = explode("-",$my_info['comtel']); ?>
						<input type="text" name="comtel[]" value="<?php echo $comtel1?>" id="comtel0" size="5" maxlength="3" class="input Onum"> - 
						<input type="text" name="comtel[]" value="<?php echo $comtel2?>" id="comtel1" size="5" maxlength="4" class="input Onum"> - 
						<input type="text" name="comtel[]" value="<?php echo $comtel3?>" id="comtel2" size="5" maxlength="4" class="input Onum"> 
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['email'] == true){ ?>
				<tr>
					<td class="t_name">이메일</td>
					<td colspan="3" class="t_value">
						<?php if(!isset($my_info['email'])) $my_info['email'] = ''; ?>
						<?php list($email_1, $email_2) = explode("@",$my_info['email']); ?>
						<input name="email[]" id="s_email_1" type="text" class="input" value="<?php echo $email_1 ?>"> @ 
						<input name="email[]" id="s_email_2" type="text" class="input" value="<?php echo $email_2 ?>">
						<select name="email_select" id="email_select" class="select" onchange="change_email(this.value);">
							<?php foreach($_email_data AS $val){ ?>
							<option value="<?php echo $val[0] ?>" <?php if($email_2 == $val[0]) echo "selected";?>><?php echo $val[1] ?></option>
							<?php } ?>
						</select> 
						<?php if($info_use['reemail'] == true){ ?>
							<span style="vertical-align: middle"><input type="checkbox" name="reemail" value="Y" <?php if($my_info['reemail'] == "Y") echo "checked"; ?>> 이메일수신에 동의합니다.</span>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['homepage'] == true){ ?>
				<tr>
					<td class="t_name">홈페이지</td>
					<td class="t_value" colspan="3">
						<input name="homepage" type="text" value="<?php echo $my_info['homepage']?>" size="40" class="input">
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['address'] == true){ ?>
				<tr>
					<td class="t_name">주소</td>
					<td colspan="3" class="t_value" colspan="3">
						<input name="post" type="text" value="<?php echo $my_info['post']?>" size="7" class="input" style="text-align:center"> 
						<input type="button" value="우편번호검색" class="base_btn2" onClick="searchZip('');"><span class="tip_br5"></span>
						<input name="address1" type="text" value="<?php echo $my_info['address1'] ?>" class="input input580"><span class="tip_br5"></span>
						<input name="address2" type="text" value="<?php echo $my_info['address2'] ?>" class="input input580">
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['birthday'] == true){ ?>
				<tr>
					<td class="t_name">생년월일</td>
					<td class="t_value" colspan="3">
						<?php if(!isset($my_info['birthday'])) $my_info['birthday'] = ''; ?>
						<?php list($birthday1, $birthday2, $birthday3) = explode("-", $my_info['birthday']); ?>
						<input name="birthday[]" value="<?php echo $birthday1?>" type="text" class="input Onum" id="birthday0" size="4" maxlength="4">년
						<input name="birthday[]" value="<?php echo $birthday2?>" type="text" class="input Onum" id="birthday1" size="2" maxlength="2">월
						<input name="birthday[]" value="<?php echo $birthday3?>" type="text" class="input Onum" id="birthday2" size="2" maxlength="2">일 (
						<span style="vertical-align: middle"><input type="radio" name="bgubun" value="양력" <?php if($my_info['bgubun'] == "양력") echo "checked"; ?>></span>양력
						<span style="vertical-align: middle"><input type="radio" name="bgubun" value="음력" <?php if($my_info['bgubun'] == "음력") echo "checked"; ?>></span>음력 )
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['marriage'] == true){ ?>
				<tr>
					<td class="t_name"> 결혼 여부</td>
					<td colspan="3" class="t_value">
					  <span style="vertical-align: middle"><input type="radio" name="marriage" value="미혼" <?php if($my_info['marriage'] == "미혼") echo "checked"; ?>></span>미혼
					  <span style="vertical-align: middle"><input type="radio" name="marriage" value="기혼" <?php if($my_info['marriage'] == "기혼") echo "checked"; ?>></span>기혼
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['memorial'] == true){ ?>
				<tr>
					<td class="t_name">결혼기념일</td>
					<td colspan="3" class="t_value">
						<?php list($memorial1, $memorial2, $memorial3) = explode("-", $my_info['memorial']); ?>
						<input name="memorial[]" value="<?php echo $memorial1?>" id="memorial0" type="text" size="4" maxlength="4" class="input Onum">년
						<input name="memorial[]" value="<?php echo $memorial2?>" id="memorial1" type="text" size="2" maxlength="2" class="input Onum">월
						<input name="memorial[]" value="<?php echo $memorial3?>" id="memorial2" type="text" size="2" maxlength="2" class="input Onum">일
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['job'] == true){ ?>
				<tr>
					<td class="t_name">직업</td>
					<td colspan="3" class="t_value">
						<?php echo $job_list?>
					</td>
				</tr>
				<script language="javascript">
				<!--
					if(document.frm.job != null){
						job = document.frm.job;
						for(ii=0; ii<job.length; ii++){
							if(job.options[ii].value == "<?php echo $my_info['job']?>")
							job.options[ii].selected = true;
						}
					}
				-->
				</script>
				<?php } ?>

				<?php if($info_use['scholarship'] == true){ ?>
				<tr>
					<td class="t_name">학력</td>
					<td colspan="3" class="t_value">
					  <?php echo $sch_list?>
					</td>
				</tr>
				<script language="javascript">
				<!--
					if(document.frm.scholarship != null){
						scholarship = document.frm.scholarship;
						for(ii=0; ii<scholarship.length; ii++){
						if(scholarship.options[ii].value == "<?php echo $my_info['scholarship']?>")
							scholarship.options[ii].selected = true;
						}
					}
				-->
				</script>
				<?php } ?>

				<?php if($info_use['income'] == true){ ?>
				<tr>
					<td class="t_name">월평균소득</td>
					<td class="t_value" colspan="3">
						<?php echo $income_list?>
					</td>
				</tr>
				<script language="javascript">
				<!--
					if(document.frm.income != null){
						income = document.frm.income;
						for(ii=0; ii<income.length; ii++){
						 if(income.options[ii].value == "<?php echo $my_info['income']?>")
							income.options[ii].selected = true;
						}
					  }

				-->
				</script>
				<?php } ?>

				<?php if($info_use['consph'] == true){ ?>
				<tr>
					<td class="t_name">관심분야</td>
					<td colspan="3" class="t_value">
					 <?php echo $consph_list?>
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['hobby'] == true){ ?>
				<tr>
					<td class="t_name">취미</td>
					<td class="t_value" colspan="3">
						<input name="hobby" type="text" value="<?php echo $my_info['hobby']?>" size="40" class="input">
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['car'] == true){ ?>
				<tr>
					<td class="t_name">자동차소유</td>
					<td class="t_value" colspan="3">
						<span style="vertical-align: middle"><input name="car" type="radio" value="소유" <?php if($my_info['car'] == "소유") echo "checked"; ?>></span>소유
						<span style="vertical-align: middle"><input name="car" type="radio" value="미소유" <?php if($my_info['car'] == "미소유") echo "checked"; ?>></span>미소유
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['intro'] == true){ ?>
				<tr>
					<td height="25" class="t_name">자기소개</td>
					<td class="t_value" colspan="3">
						<textarea name="intro" rows="5" cols="90" class="textarea"><?php echo $my_info['intro']?></textarea>
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['recom'] == true){ ?>
				<tr>
					<td class="t_name">추천인</td>
					<td colspan="3" class="t_value">
						<input type="text" name="recom" value="<?php echo $my_info['recom']?>" class="input">
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['addinfo1'] == true){ ?>
				<tr>
					<td class="t_name"><?php echo $addname1?></td>
					<td colspan="3" class="t_value">
						<?php echo $addinfo1_input?>
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['addinfo2'] == true){ ?>
				<tr>
					<td class="t_name"><?php echo $addname2?></td>
					<td colspan="3" class="t_value">
						<?php echo $addinfo2_input?>
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['addinfo3'] == true){ ?>
				<tr>
					<td class="t_name"><?php echo $addname3?></td>
					<td colspan="3" class="t_value">
						<?php echo $addinfo3_input?>
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['addinfo4'] == true){ ?>
				<tr>
					<td class="t_name"><?php echo $addname4?></td>
					<td colspan="3" class="t_value">
						<?php echo $addinfo4_input?>
					</td>
				</tr>
				<?php } ?>

				<?php if($info_use['addinfo5'] == true){ ?>
				<tr>
					<td class="t_name"><?php echo $addname5?></td>
					<td colspan="3" class="t_value">
						<?php echo $addinfo5_input?>
					</td>
				</tr>
				<?php } ?>

				<tr>
					<td class="t_name">관리자메모</td>
					<td class="t_value" colspan="3">
					 <textarea name="memo" rows="5" cols="90" class="textarea"><?php echo $my_info['memo']?></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<? if($menu_arr["PRODUCT"] == true) { ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 배송지정보</td>
	</tr>
</table>
<?php
$sql_c = "
	SELECT COUNT(*) AS cnt
	  FROM wiz_delivery_set
	 WHERE de_id='{$my_info['id']}'
	 ORDER BY basicdelivery DESC
";
$rs_c = query($sql_c);
$rs_w = sql_fetch_arr($rs_c);
$basicdelivery_cnt = $rs_w['cnt'];
if($basicdelivery_cnt <= 0){
?>
<!-- ## 배송지가 없는 경우 ##-->
<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
		<tbody><tr>
			<td class="t_value" align="center">배송지 정보가 없습니다.</td>
		</tr>
	</tbody>
</table>
<!-- // -->
<?php } else { ?>
<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
	<tbody>
		<?php
		$sql = "
			SELECT *
			  FROM wiz_delivery_set
			 WHERE de_id='{$my_info['id']}'
			 ORDER BY basicdelivery DESC
		";
		$drs = query($sql);
		$i = 0;
		while($drw = sql_fetch_arr($drs)) {

			$idx           = $drw['idx'];
			$re_name       = xss_clean($drw['re_name']);
			$re_post       = $drw['re_post'];
			$re_addr       = xss_clean($drw['re_addr']);
			$re_addr2      = xss_clean($drw['re_addr2']);
			$re_hphone     = $drw['re_hphone'];
			$re_tphone     = $drw['re_tphone'];
			$basicdelivery = $drw['basicdelivery'];

			list($hphone0, $hphone1, $hphone2) = explode("-", $re_hphone);
			list($tphone0, $tphone1, $tphone2) = explode("-", $re_tphone);

			if($basicdelivery == "Y") {
				$basic_lo = '<p class="basic">기본배송지</p>';
			} else {
				$basic_lo = '';
			}

		?>
		<input type="hidden" name="bucode[]" id="bucode_<?php echo $i ?>">
		<input type="hidden" name="gbucode[]" value="<?php echo $drw['bucode'] ?>">
		<input type="hidden" name="didx[]" value="<?php echo $idx ?>">
		<tr>
			<td class="t_value">
				<?php if($basic_lo != ""){ ?>
				<div class="deli_btn"><?php echo $basic_lo ?></div>
				<?php } ?>
				<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
					<colgroup>
						<col width="15%">
						<col width="">
					</colgroup>
					<tbody><tr>
						<td class="t_name">받는사람 *</td>
						<td class="t_value"><input name="re_name[]" id="re_name_<?php echo $i ?>" type="text" class="input" value="<?php echo $re_name ?>"></td>
					</tr>
					<tr>
						<td class="t_name">주소 *</td>
						<td class="t_value">
							<input type="text" name="re_post[]" id="re_post_<?php echo $i ?>" size="5" class="input add_num" value="<?php echo $re_post ?>" readonly="">
							<input type="button" onclick="postSearch('<?php echo $i ?>')" value="우편번호 검색" class="base_btn2"><br>
							<input type="text" name="re_addr[]" id="re_addr_<?php echo $i ?>" size="100" maxlength="80" class="input address" value="<?php echo $re_addr ?>" readonly=""><br>
							<input type="text" name="re_addr2[]" id="re_addr2_<?php echo $i ?>" size="100" maxlength="80" class="input address_en" value="<?php echo $re_addr2 ?>">
						</td>
					</tr>
					<tr>
						<td class="t_name">전화번호 *</td>
						<td class="t_value">
							<input name="re_tphone[<?php echo $i ?>][]" id="tphone0<?php echo $i ?>" type="text" class="input" size="4" maxlength="3" value="<?php echo $tphone0 ?>" onlynum="true" style="ime-mode: disabled">
							-
							<input name="re_tphone[<?php echo $i ?>][]" id="tphone1<?php echo $i ?>" type="text" class="input" size="4" maxlength="4" value="<?php echo $tphone1 ?>" onlynum="true" style="ime-mode: disabled">
							-
							<input name="re_tphone[<?php echo $i ?>][]" id="tphone2<?php echo $i ?>" type="text" class="input" size="4" maxlength="4" value="<?php echo $tphone2 ?>" onlynum="true" style="ime-mode: disabled">
						</td>
					</tr>
					<tr>
						<td class="t_name">휴대 전화번호 *</td>
						<td class="t_value">
							<input name="re_hphone[<?php echo $i ?>][]" id="hphone0<?php echo $i ?>" type="text" class="input" size="4" maxlength="3" value="<?php echo $hphone0 ?>" onlynum="true" style="ime-mode: disabled">
							-
							<input name="re_hphone[<?php echo $i ?>][]" id="hphone1<?php echo $i ?>" type="text" class="input" size="4" maxlength="4" value="<?php echo $hphone1 ?>" onlynum="true" style="ime-mode: disabled">
							-
							<input name="re_hphone[<?php echo $i ?>][]" id="hphone2<?php echo $i ?>" type="text" class="input" size="4" maxlength="4" value="<?php echo $hphone2 ?>" onlynum="true" style="ime-mode: disabled">
						</td>
					</tr>
				</tbody></table>
			</td>
		</tr>
		<?php
			$i++;
		}

		?>
	</tbody>
</table>
<? } ?>
<? } ?>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='member_list.php?<?php echo $param?>';">&nbsp;
			<?php 
			if($one_year_later_dormancy == date("Y-m-d") && $mode == "update") { 
			?>
			<input type="button" value="휴면처리" class="base_btn red" onClick="ReleaseRestore('<?php echo $my_info['id'] ?>')">
			<?php } ?>
		</td>
	</tr>
</table>
</form>

<?php include "../foot.php"; ?>