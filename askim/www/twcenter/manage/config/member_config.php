<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/mem_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');

// 입력정보 사용여부
$info_tmp = explode("/",$mem_info['infouse']);
for($ii=0; $ii<count($info_tmp); $ii++){
	$info_use[$info_tmp[$ii]] = true;
}

// 입력정보 필수여부
$info_tmp = explode("/",$mem_info['infoess']);
for($ii=0; $ii<count($info_tmp); $ii++){
	$info_ess[$info_tmp[$ii]] = true;
}

$addname = explode("|", $mem_info['addname']);

$sql = "
	select fprior
		 , ftype
		 , fsize
		 , fnum
		 , flist 
	  from wiz_formfield 
	 where fidx = 'addinfo' 
	 order by fprior asc
";
$result = query($sql);

while($field_info = sql_fetch_arr($result)) {

	$flist[$field_info['fprior']] = explode("|", $field_info['flist']);

	$addtype[$field_info['fprior']] = $field_info['ftype'];
	$addsize[$field_info['fprior']] = $field_info['fsize'];
	$addnum[$field_info['fprior']] = $field_info['fnum'];

	//echo $field_info['fprior']." - ".$addsize[$field_info['fprior']]."<br>";

}

// SMS 충전에 따라 아이디/비밀번호 확인방법 활성/비활성
if($site_info['sms_id'] && $site_info['sms_pw']) {
	$sms_idpw_chk = "";
} else {
	$sms_idpw_chk = " disabled";
}

// 확인방법
$method_tmp = explode("/",$mem_info['method']);
for($ii=0; $ii<count($method_tmp); $ii++){
	$method_use[$method_tmp[$ii]] = true;
}

$page_name = "회원설정"; 
$page_desc = "아래코드를 삽입하여 회원관련 페이지를 생성합니다.  "; 
$navi_name = "  환경설정  > 회원설정";

?>
<script language="javascript">
<!--
function inputCheck(frm){
/*
   if(frm.agreement.value == ""){
      alert("가입약관을 입력하세요");
      frm.agreement.focus();
      return false;
   }
   if(frm.safeinfo.value == ""){
      alert("개인정보 보호정책을 입력하세요");
      frm.safeinfo.focus();
      return false;
   }
*/
}

  var flist_array = new Array();

<?php
for($jj = 1; $jj <= count($flist); $jj++) {
 ?>
  flist_array[<?php echo $jj ?>] = new Array();
<?php
	if(count($flist[$jj]) <= 1) {
 ?>
	flist_array[<?php echo $jj ?>][0] = "";
<?php
	}

	for($ii = 0; $ii < count($flist[$jj]); $ii++) {
		if(!empty($flist[$jj][$ii])) {
 ?>
	flist_array[<?php echo $jj ?>][<?php echo $ii ?>] = "<?php echo $flist[$jj][$ii] ?>";
<?php
		}
	}
}
 ?>

function flist(num) {
	var length = eval("document.frm.fnum"+num+".value");
	var tmp = '';

	if(!length || length <= 0)
	{
		length = 1;
	}
	else if(length > 20)
	{
		length = 20;
	}

	for(i=1; i<=length; i++)
	{

		if(flist_array[num].length > 0) {
			var ii = i - 1;
			if(flist_array[num][ii] == undefined) flist_array[num][ii] = "";
			tmp += " " + i + " <input type=\"text\" name=\"flist" + num + "[]\" value=\"" + flist_array[num][ii] + "\" class=\"input\" size='40'><br>";
		} else {
			tmp += " " + i + " <input type=\"text\" name=\"flist" + num + "[]\" value=\"\" class=\"input\" size='40'><br>";
		}
	}
	document.getElementById('flist_layer_' + num).innerHTML = tmp;

}

function setOpt(num) {

	var opt = eval("document.frm.ftype"+num+".value");

	document.getElementById('size_' + num).style.display = "";
	document.getElementById('num_' + num).style.display = "";
	document.getElementById('opt_' + num).style.display = "";

	//사이즈 - test, textarea, file
	if(opt == "text" || opt == "textarea" || opt == "file") {
		document.getElementById('size_' + num).style.display = "";
	}
	//옵션 갯수 - select, radio, checkbox
	if(opt == "select" || opt == "radio" || opt == "checkbox") {
		document.getElementById('num_' + num).style.display = "";
		document.getElementById('opt_' + num).style.display = "";
	}
}

function setEss(frm, val) {

	for(ii = 0; ii < frm.elements["info_use[]"].length; ii++) {
		if(frm.elements["info_use[]"][ii].value == val) {
			frm.elements["info_ess[]"][ii].checked = frm.elements["info_use[]"][ii].checked;
			break;
		}
	}

}

-->
</script>
</head>

<body onload="flist(1);flist(2);flist(3);flist(4);flist(5);setOpt(1);setOpt(2);setOpt(3);setOpt(4);setOpt(5);">

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">회원관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">아래코드를 삽입하여 회원관련 페이지를 생성합니다.</td>
	</tr>
</table>

<div class="helpTip3 box_line">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="title">
	  </div>
	  <div class="explain">
		  - 스킨위치 : /twcenter/member/skin<br>
		  - 회원가입항목은 기본항목선택, 임의추가도 가능합니다.<br>
		  - 아이디/비번찾기 : 아이디, 비밀번호 찾기가 한페이지에 보여집니다.<br>
		  - 아이디찾기 : 아이디 찾기만 페이지에 보여집니다. / 비밀번호찾기 : 비밀번호 찾기만 페이지에 보여집니다.
	  </div>
	</div>
</div>

<form name="frm" action="member_save.php?<?php echo $param ?>" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="<?php echo $mode ?>">
<input type="hidden" name="idx" value="<?php echo $idx ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif"> 페이지</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="9%" class="t_name">회원가입</td>
					<td width="6%" class="t_value3" align="center">
						<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">
					</td>
					<td width="85%" class="t_value2">
					<?php
					$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/join.php\";     // 회원가입 ?&gt;";
					 ?>
					<script language="javascript">
						var clipboard = new Clipboard('#clipboardBtn');
					</script>
						<span id="clip"><font color=red><?php echo $php_code ?></font></span>&nbsp;
					</td>
				</tr>
				<tr>
					<td class="t_name">로그인페이지</td>
					<td class="t_value3" align="center">
						<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn2" data-clipboard-action="copy" data-clipboard-target="#clip2">
					</td>
					<td class="t_value2">
					<?php
					$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/login.php\";     // 로그인페이지 ?&gt;";
					 ?>
					<script language="javascript">
						var clipboard = new Clipboard('#clipboardBtn2');
					</script>
						<span id="clip2"><font color=red><?php echo $php_code ?></font></span>&nbsp;
					</td>
				</tr>

				<tr>
					<td class="t_name">아이디/비번 찾기</td>
					<td class="t_value3" align="center">
						<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn3" data-clipboard-action="copy" data-clipboard-target="#clip3">
					</td>
					<td class="t_value2">
					<?php
					$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/idpw.php\";     // 아이디/비번찾기 ?&gt;";
					 ?>
					<script language="javascript">
						var clipboard = new Clipboard('#clipboardBtn3');
					</script>
						<span id="clip3"><font color=red><?php echo $php_code ?></font></span>&nbsp;
					</td>
				</tr>

				<tr>
					<td class="t_name">아이디찾기</td>
					<td class="t_value3" align="center">
						<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn4" data-clipboard-action="copy" data-clipboard-target="#clip4">
					</td>
					<td class="t_value2">
					<?php
					$php_code = "&lt;?php \$stype=\"id\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/idpw.php\";     // 아이디찾기 ?&gt;";
					 ?>
					<script language="javascript">
						var clipboard = new Clipboard('#clipboardBtn4');
					</script>
						<span id="clip4"><font color=red><?php echo $php_code ?></font></span>&nbsp;
					</td>
				</tr>

				<tr>
					<td class="t_name">비밀번호찾기</td>
					<td class="t_value3" align="center">
						<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn5" data-clipboard-action="copy" data-clipboard-target="#clip5">
					</td>
					<td class="t_value2">
					<?php
					$php_code = "&lt;?php \$stype=\"pw\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/idpw.php\";     // 비밀번호찾기 ?&gt;";
					?>
					<script language="javascript">
						var clipboard = new Clipboard('#clipboardBtn5');
					</script>
						<span id="clip5"><font color=red><?php echo $php_code ?></font></span>&nbsp;
					</td>
				</tr>
				<tr>
					<td class="t_name">회원정보수정</td>
					<td class="t_value3" align="center">
						<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn6" data-clipboard-action="copy" data-clipboard-target="#clip6">
					</td>
					<td class="t_value2">
					<?php
					$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/myinfo.php\";     // 회원정보수정 ?&gt;";
					?>
					<script language="javascript">
						var clipboard = new Clipboard('#clipboardBtn6');
					</script>
						<span id="clip6"><font color=red><?php echo $php_code ?></font></span>&nbsp;
					</td>
				</tr>

				<tr>
					<td class="t_name">회원탈퇴</td>
					<td class="t_value3" align="center">
						<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn7" data-clipboard-action="copy" data-clipboard-target="#clip7">
					</td>
					<td class="t_value2">
					<?php
					$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/myout.php\";     // 회원탈퇴 ?&gt;";
					?>
					<script language="javascript">
						var clipboard = new Clipboard('#clipboardBtn7');
					</script>
						<span id="clip7"><font color=red><?php echo $php_code ?></font></span>&nbsp;
					</td>
				</tr>

				<tr>
					<td class="t_name">로그인박스</td>
					<td class="t_value3" align="center">
						<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn8" data-clipboard-action="copy" data-clipboard-target="#clip8">
					</td>
					<td class="t_value2">
					<?php
					$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/loginbox.php\";     // 로그인박스 ?&gt;";
					?>
					<script language="javascript">
						var clipboard = new Clipboard('#clipboardBtn8');
					</script>
						<span id="clip8"><font color=red><?php echo $php_code ?></font></span>&nbsp;
					</td>
				</tr>

				<tr>
					<td class="t_name" colspan="2">페이지URL</td>
					<td class="t_value">
						<table width="80%" border="0" cellspacing="1" cellpadding="0" class="t_style">
							<tr>
								<td width="20%" class="t_name">회원가입 페이지</td>
								<td>&nbsp;http//<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="join_url" value="<?php echo $mem_info['join_url'] ?>" size="40" class="input"></td>
							</tr>
							<tr>
								<td class="t_name">회원정보 페이지</td>
								<td>&nbsp;http//<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="myinfo_url" value="<?php echo $mem_info['myinfo_url'] ?>" size="40" class="input"></td>
							</tr>
							<tr>
								<td class="t_name">로그인 페이지</td>
								<td>&nbsp;http//<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="login_url" value="<?php echo $mem_info['login_url'] ?>" size="40" class="input"></td>
							</tr>
							<tr>
								<td class="t_name">아이디/비번 페이지</td>
								<td>&nbsp;http//<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="idpw_url" value="<?php echo $mem_info['idpw_url'] ?>" size="40" class="input"></td>
							</tr>
							<tr>
								<td class="t_name">로그아웃 후 이동페이지</td>
								<td>&nbsp;http//<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="out_url" value="<?php echo $mem_info['out_url'] ?>" size="40" class="input"><br></td>
							</tr>
						</table>
						인트로페이지를 사용하는 경우 로그아웃하면 다시 인트로로 가는것을 막기위해 이동페이지 주소를 설정합니다.
					</td>
				</tr>
			</table>
			<br>

			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif"> 탑메뉴</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="9%" class="t_name">로그인/<br />로그아웃</td>
					<td width="6%" class="t_value3" align="center">
						<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn9" data-clipboard-action="copy" data-clipboard-target="#clip9">
					</td>
					<td width="85%" class="t_value2">
					<?php
					$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/toplogin.php\";     // 로그인,로그아웃 ?&gt;";
					?>
					<script language="javascript">
						var clipboard = new Clipboard('#clipboardBtn9');
					</script>
						<span id="clip9"><font color=red><?php echo str_replace("\n","<br>",$php_code) ?></span></font>
					</td>
				</tr>

				<tr>
					<td class="t_name">회원가입/<br />마이페이지</td>
					<td class="t_value3" align="center">
						<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn10" data-clipboard-action="copy" data-clipboard-target="#clip10">
					</td>
					<td class="t_value2">
					<?php
					$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/topjoin.php\";     // 회원가입,정보수정 ?&gt;";
					?>
					<script language="javascript">
						var clipboard = new Clipboard('#clipboardBtn10');
					</script>
						<span id="clip10"><font color=red><?php echo str_replace("\n","<br>",$php_code) ?></font></span>
					</td>
				</tr>
				<tr>
					<td class="t_name" colspan="2">이미지설정</td>
					<td class="t_value">
						<table width="80%" border="0" cellspacing="1" cellpadding="0" class="t_style">
							<tr>
								<td width="20%" class="t_name">로그인 이미지</td>
								<td>&nbsp;http//<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="login_img" value="<?php echo $mem_info['login_img'] ?>" size="40" class="input"></td>
							</tr>
							<tr>
								<td class="t_name">로그아웃 이미지</td>
								<td>&nbsp;http//<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="logout_img" value="<?php echo $mem_info['logout_img'] ?>" size="40" class="input"></td>
							</tr>
							<tr>
								<td class="t_name">회원가입 이미지</td>
								<td>&nbsp;http//<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="join_img" value="<?php echo $mem_info['join_img'] ?>" size="40" class="input"></td>
							</tr>
							<tr>
								<td class="t_name">마이페이지 이미지</td>
								<td>&nbsp;http//<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="myinfo_img" value="<?php echo $mem_info['myinfo_img'] ?>" size="40" class="input"></td>
							</tr>
						</table>
						이미지를 설정하지 않으면 텍스트로 보여집니다.
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 휴면해제</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
	<tr>
		<td width="15%" class="t_name">처리방법</td>
		<td width="85%" class="t_value">
			<span style="vertical-align: middle">
				<input type="checkbox" name="release_sleep_type[]" value="A" <?php if($sleep_use["A"]==true || $mem_info["A"]==false) echo "checked";  ?>> 로그인할때 자동해제<br>
				<input type="checkbox" name="release_sleep_type[]" value="E" <?php if($sleep_use["E"]==true) echo "checked"; ?>> 이메일 인증을 통한 해제 <span class="sub_tit_alt2"> (이메일을 통해 <b>임시비밀번호</b> 전송)</span><br>
				<input type="checkbox" name="release_sleep_type[]" value="S" <?php if($sleep_use["S"]==true) echo "checked"; ?>> SMS 인증을 통한 해제 <span class="sub_tit_alt2"> (SMS연동 필요)</span><br>

		</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 아이디/패스워드 찾기</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
	<tr>
		<td width="15%" class="t_name">확인방법</td>
		<td width="85%" class="t_value">
			<span style="vertical-align: middle">
				<input type="checkbox" name="method[]" value="E" checked <?php if($method_use["E"]==true) echo "checked";  ?>> 이메일 발송&nbsp;
				<input type="checkbox" name="method[]" value="S" <?php echo $sms_idpw_chk  ?>  <?php if($method_use["S"]==true) echo "checked";  ?>> SMS 발송&nbsp;&nbsp;<span class="sub_tit_alt2"> SMS발송은 '기본설정 > SMS관리' 에서 아이디 및 토큰키를 확인해주세요.</span>

		</td>
	</tr>
</table>
<br>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" class="t_name">스킨</td>
					<td width="85%" class="t_value" colspan="3">
						<table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style">
							<tr>
								<td class="t_name" width="15%">PC</td>
								<td width="35%">
									<select name="skin" class="select">
									<?php
									$dh = opendir("../../member/skin");
									while(($file = readdir($dh)) !== false){
										if($file != "." && $file != ".." && (strpos($file, '_m') === false)){
											$file_list[] = $file;
										}
									}
									sort ($file_list); reset ($file_list);
									for($ii=0;$ii<count($file_list);$ii++){
									 ?>
									<option value="<?php echo $file_list[$ii] ?>"><?php echo $file_list[$ii] ?></option>
									<?php
									}
									 ?>
									</select>
									<script language="javascript">
									<!--
									skin = document.frm.skin;
									for(ii=0; ii<skin.length; ii++){
										if(skin.options[ii].value == "<?php echo $mem_info['skin']  ?>")
										skin.options[ii].selected = true;
									}
									-->
									</script>
									스킨위치 : /twcenter/member/skin
								</td>
								<td class="t_name" width="15%">Mobile</td>
								<td width="35%">
									<select name="m_skin" class="select">
									<?php
									$dh2 = opendir("../../member/skin");
									while(($file = readdir($dh2)) !== false){
										if($file != "." && $file != ".." && (strpos($file, '_m') !== false)){
											$file_list2[] = $file;
										}
									}
									sort ($file_list2); reset ($file_list2);
									for($ii=0;$ii<count($file_list2);$ii++){
									 ?>
									<option value="<?php echo $file_list2[$ii] ?>"><?php echo $file_list2[$ii] ?></option>
									<?php
									}
									?>
									</select>
									<script language="javascript">
									<!--
									m_skin = document.frm.m_skin;
									for(ii=0; ii<m_skin.length; ii++){
										if(m_skin.options[ii].value == "<?php echo $mem_info['m_skin']  ?>")
										m_skin.options[ii].selected = true;
									}
									-->
									</script>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="t_name">입력정보 선택</td>
					<td class="t_value" colspan="3">
						<table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style">
							<tr>
								<td class="t_name" width="15%">아이디</td>
								<td width="35%">&nbsp;사용함</td>
								<td class="t_name" width="15%">비밀번호</td>
								<td width="35%">&nbsp;사용함 &nbsp;&nbsp;&nbsp;
									<input type="checkbox" name="info_use[]" value="specChar" <?php if($info_use["specChar"]==true) echo "checked";  ?>> <font color=red>특수문자허용</font>&nbsp;&nbsp;
									자리수 : <input type="text" name="sdigit" value="<?php echo $mem_info['sdigit']  ?>" class="input Onum" size="3" maxlength="2">자리 ~ <input type="text" name="edigit" value="<?php echo $mem_info['edigit']  ?>" class="input Onum" size="3" maxlength="2">자리
								</td>
							</tr>
							<tr>
								<td class="t_name">이름</td>
								<td>&nbsp;사용함</td>
								<td class="t_name">이메일</td>
								<td>&nbsp;사용함
									<input type="checkbox" name="info_use[]" value="email" checked style="display:none">
									<input type="checkbox" name="info_ess[]" value="email" checked style="display:none">
								</td>
							</tr>
							<tr>
								<td class="t_name">주민번호</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="resno" <?php if($info_use["resno"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="resno" <?php if($info_ess["resno"]==true) echo "checked"; ?>></span>필수항목
								</td>
								<td  class="t_name">주소</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="address" <?php if($info_use["address"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="address" <?php if($info_ess["address"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
							<tr>
								<td  class="t_name">전화번호</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="tphone" <?php if($info_use["tphone"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="tphone" <?php if($info_ess["tphone"]==true) echo "checked"; ?>></span>필수항목
								</td>
								<td  class="t_name">회사전화</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="comtel" <?php if($info_use["comtel"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="comtel" <?php if($info_ess["comtel"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
							<tr>
								<td  class="t_name">휴대폰</td>
								<td colspan="3">
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="hphone" <?php if($info_use["hphone"]==true) echo "checked"; ?>></span>사용함
									<?/*2020-04-27 임서연 문자인증 항목 추가*/?>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="hphone_cert" <?php if($info_use["hphone_cert"]==true) echo "checked"; ?>></span>문자인증
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="hphone" <?php if($info_ess["hphone"]==true) echo "checked"; ?>></span>필수항목&nbsp;
									<font color=red>* 사용함 / 필수항목선택시 휴대폰번호로 회원가입유무를 체크합니다.</font><br>
									<font color=red>* 문자인증은 sms기능이 정상적으로 설정되어있는경우에만 가능합니다.</font>
								</td>
								<!-- <td  class="t_name">휴대폰 회원가입여부확인</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="hphone_join" <? if($info_use["hphone_join"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="hphone_join" <? if($info_ess["hphone_join"]==true) echo "checked"; ?>></span>필수항목
								</td> -->
							</tr>
							<tr>
								<td  class="t_name">메일 수신여부</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="reemail" <?php if($info_use["reemail"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="reemail" <?php if($info_ess["reemail"]==true) echo "checked"; ?>></span>필수항목
								</td>
								<td  class="t_name">SMS 수신여부</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="resms" <?php if($info_use["resms"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="resms" <?php if($info_ess["resms"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
							<tr>
								<td class="t_name">닉네임</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="nick" <?php if($info_use["nick"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="nick" <?php if($info_ess["nick"]==true) echo "checked"; ?>></span>필수항목
								</td>
								<td class="t_name">추천인</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="recom" <?php if($info_use["recom"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="recom" <?php if($info_ess["recom"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
							<tr>
								<td class="t_name"><b>스팸글체크</b></td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="spam" <?php if($info_use["spam"]==true) echo "checked"; ?> onClick="setEss(this.form, this.value)"></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="spam" <?php if($info_ess["spam"]==true) echo "checked"; ?> ></span>필수항목
									<?/* 2020-04-01 임서연 주석처리(선택해제가 되지 않는문제) onClick="return false;"*/?>
								</td>
								<td class="t_name">회원아이콘</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="icon" <?php if($info_use["icon"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="icon" <?php if($info_ess["icon"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
						</table>
						<br>
						<table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style">
							<tr>
								<td class="t_name" width="15%">회원사진</td>
								<td width="35%">
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="photo" <?php if($info_use["photo"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="photo" <?php if($info_ess["photo"]==true) echo "checked"; ?>></span>필수항목
								</td>
								<td  class="t_name" width="15%">홈페이지</td>
								<td width="35%">
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="homepage" <?php if($info_use["homepage"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="homepage" <?php if($info_ess["homepage"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
							<tr>
								<td  class="t_name">생년월일</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="birthday" <?php if($info_use["birthday"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="birthday" <?php if($info_ess["birthday"]==true) echo "checked"; ?>></span>필수항목
								</td>
								<td  class="t_name">직업</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="job" <?php if($info_use["job"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="job" <?php if($info_ess["job"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
							<tr>
								<td  class="t_name">관심분야</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="consph" <?php if($info_use["consph"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="consph" <?php if($info_ess["consph"]==true) echo "checked"; ?>></span>필수항목
								</td>
								<td  class="t_name">취미</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="hobby" <?php if($info_use["hobby"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="hobby" <?php if($info_ess["hobby"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
							<tr>
								<td  class="t_name">학력</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="scholarship" <?php if($info_use["scholarship"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="scholarship" <?php if($info_ess["scholarship"]==true) echo "checked"; ?>></span>필수항목
								</td>
								<td  class="t_name">자기소개</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="intro" <?php if($info_use["intro"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="intro" <?php if($info_ess["intro"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
							<tr>
								<td  class="t_name">결혼여부</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="marriage" <?php if($info_use["marriage"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="marriage" <?php if($info_ess["marriage"]==true) echo "checked"; ?>></span>필수항목
								</td>
								<td  class="t_name">결혼기념일</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="memorial" <?php if($info_use["memorial"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="memorial" <?php if($info_ess["memorial"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
							<tr>
								<td  class="t_name">월평균소득</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="income" <?php if($info_use["income"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="income" <?php if($info_ess["income"]==true) echo "checked"; ?>></span>필수항목
								</td>
								<td  class="t_name">자동차소유</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="car" <?php if($info_use["car"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="car" <?php if($info_ess["car"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
						</table>
						<br>
						<table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style">
							<tr>
								<td  class="t_name" width="15%">추가항목1</td>
								<td width="35%">
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="addinfo1" <?php if($info_use["addinfo1"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="addinfo1" <?php if($info_ess["addinfo1"]==true) echo "checked"; ?>></span>필수항목
								</td>
								<td class="t_name" width="15%">추가항목2</td>
								<td width="35%">
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="addinfo2" <?php if($info_use["addinfo2"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="addinfo2" <?php if($info_ess["addinfo2"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
							<tr>
								<td  class="t_name">추가항목3</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="addinfo3" <?php if($info_use["addinfo3"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="addinfo3" <?php if($info_ess["addinfo3"]==true) echo "checked"; ?>></span>필수항목
								</td>
								<td  class="t_name">추가항목4</td>
								<td>
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="addinfo4" <?php if($info_use["addinfo4"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="addinfo4" <?php if($info_ess["addinfo4"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
							<tr>
								<td  class="t_name">추가항목5</td>
								<td colspan="3">
									<span style="vertical-align: middle"><input type="checkbox" name="info_use[]" value="addinfo5" <?php if($info_use["addinfo5"]==true) echo "checked"; ?>></span>사용함
									<span style="vertical-align: middle"><input type="checkbox" name="info_ess[]" value="addinfo5" <?php if($info_ess["addinfo5"]==true) echo "checked"; ?>></span>필수항목
								</td>
							</tr>
						</table>

					</td>
				</tr>
				<tr>
					<td class="t_name">추가항목명</td>
					<td class="t_value padd" colspan="3">
						<table width="99%" border="0" cellspacing="1" cellpadding="0" class="t_style">
							<tr>
								<td class="t_name" width="15%" align="center">구분</td>
								<td width="25%" class="t_name" align="center">항목명</td>
								<td width="10%" class="t_name" align="center">항목속성</td>
								<td width="10%" class="t_name" align="center">항목사이즈</td>
								<td width="8%" class="t_name" align="center">세부항목 개수</td>
								<td width="25%" class="t_name" align="center">세부항목</td>
							</tr>

							<?php
							for($ii = 1; $ii <= 5; $ii++) {
								$jj = $ii - 1;
							 ?>
							<tr>
								<td class="t_name">추가항목<?php echo $ii ?></td>
								<td valign="top" align="center"><input type="text" name="add_name[]" value="<?php echo $addname[$jj] ?>" class="input" size="50"></td>
								<td valign="top" align="center">
									<select name="ftype<?php echo $ii ?>" onChange="setOpt(<?php echo $ii ?>)" class="select">
										<option value="text">text</option>
										<option value="select">select</option>
										<option value="radio">radio</option>
										<option value="checkbox">checkbox</option>
										<option value="textarea">textarea</option>
										<option value="file">file</option>
										<option value="pdate">일자(달력)</option>
										<option value="tdate">년월일시</option>
										<option value="birthday">생년월일</option>
										<option value="tel">전화번호</option>
										<option value="phone">휴대폰</option>
										<option value="address">주소찾기</option>
										<option value="email">이메일</option>
									</select>
									<script language="javascript">
									<!--
									ftype = document.frm.ftype<?php echo $ii ?>;
									var tmp = "";
									for(ii=0; ii<ftype.length; ii++){
										if(ftype.options[ii].value == "<?php echo $addtype[$ii] ?>") {
										ftype.options[ii].selected = true;
										tmp = ftype.options[ii].value;
										}
									}
									-->
									</script>
								</td>
								<td valign="top" align="center" id="size_<?php echo $ii ?>" style="display:none">
									<input name="fsize<?php echo $ii ?>" type="text" value="<?php echo $addsize[$ii] ?>" size="15" class="input">
								</td>
								<td valign="top" id="num_<?php echo $ii ?>" style="display:none" align="center">
									<select name="fnum<?php echo $ii ?>" onChange="flist('<?php echo $ii ?>');" class="select">
									<? for($kk=1;$kk<21;$kk++){  ?>
										<option value="<?php echo $kk ?>" <? if($addnum[$ii] == $kk) echo "selected";  ?>><?php echo $kk ?></option>
									<? }  ?>
									<select>
								</td>
								<td valign="top" id="opt_<?php echo $ii ?>" style="display:none" align="center">
									<span id='flist_layer_<?php echo $ii ?>'></span>
								</td>
							</tr>
							<?php
							}
							 ?>

						</table>
					</td>
				</tr>
				<tr>
					<td class="t_name">아이디,닉네임 금지단어</td>
					<td class="t_value" colspan="3">
						<textarea name="prohibit_id" class="txt txtfullp2"><?php echo $mem_info['prohibit_id'] ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="t_name">직업</td>
					<td class="t_value">
						<textarea name="job_list" rows="3" style="width:98%" class="textarea"><?php echo $mem_info['job_list'] ?></textarea>
					</td>
					<td class="t_name">학력</td>
					<td class="t_value">
						<textarea name="sch_list" rows="3" style="width:98%" class="textarea"><?php echo $mem_info['sch_list'] ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="t_name">월평균소득</td>
					<td class="t_value">
						<textarea name="income_list" rows="3" style="width:98%" class="textarea"><?php echo $mem_info['income_list'] ?></textarea>
					</td>
					<td class="t_name">관심분야</td>
					<td class="t_value">
						<textarea name="consph_list" rows="3" style="width:98%" class="textarea"><?php echo $mem_info['consph_list'] ?></textarea>
					</td>
				</tr>
				<!--
				<tr>
					<td class="t_name">가입약관</td>
					<td class="t_value" colspan="3">
						<textarea name="agreement" rows="10" cols="90" class="textarea"><?php echo $mem_info['agreement'] ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="t_name">개인정보 보호정책</td>
					<td class="t_value" colspan="3">
						<textarea name="safeinfo" rows="10" cols="90" class="textarea"><?php echo $mem_info['safeinfo'] ?></textarea>
					</td>
				</tr>
				//-->
			</table>
		</td>
	</tr>
</table>

<br>
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
			<input type="submit" value="확인" class="base_btn reg">
		</td>
	</tr>
	</table>
</form>

</body>

<?php include_once(WIZHOME_PATH.'/manage/foot.php'); ?>
