<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
if($mode == "update"){

	$sql = "select * from wiz_admin where id = '$id'";
	$result = query($sql) or error("sql error");
	$_admin_info = sql_fetch_arr($result);
} else {
	$sql = "select idx from wiz_admin_lev order by idx limit 1";
	$row = sql_fetch($sql);
	$_admin_info['lev'] = $row['idx'];
}
?>
<?
$page_name = "관리자설정";
$page_desc = "관리자 상세정보를 설정합니다.";
$navi_name = " 기본설정 > 관리자설정";
?>
<? include "../head.php"; ?>

<!-- <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> -->
<? echo $DAUM_POSTCODE.PHP_EOL; ?>
<script language="JavaScript" type="text/javascript">
<!--
function inputCheck(frm){

	var invalidChars = /(--|#|;)/g;

	if(frm.id.value == ""){
		alert("관리자 아이디를 입력하세요");
		frm.id.focus();
		return false;
	}
	<?php if($mode == "insert") { ?>
		if(frm.passwd.value == ""){
			alert("관리자 비밀번호를 입력하세요");
			frm.passwd.focus();
			return false;
		}
	<? } ?>
	
	if (invalidChars.test(frm.passwd.value)) {
		alert("비밀번호에 --, #, ; 문자는 사용할 수 없습니다.");
		frm.passwd.focus();
		return false;
	}
	
	if(frm.name.value == ""){
		alert("관리자 이름을 입력하세요");
		frm.name.focus();
		return false;
	}
	if(frm.email.value == ""){
		alert("관리자 이메일을 입력하세요");
		frm.email.focus();
		return false;
	}
	if(frm.lev.value == ""){
		alert("관리자 등급을 선택하세요");
		frm.lev.focus();
		return false;
	}
}

// 주소찾기
function searchZip() {

	var kind = '';
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

			eval('frm.'+kind+'post1').value = data.zonecode;
			eval('frm.'+kind+'address1').value = fullAddr;

			if(eval('frm.'+kind+'address1') != null)
				eval('frm.'+kind+'address2').focus();
		}
	}).open();

}

// 아이디 중복확인
function idCheck(){
   var id = document.frm.id.value;
   var url = "../member/id_check.php?name=id&id=" + id;
   window.open(url, "idCheck", "width=500, height=200, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=150, top=150");
}
//-->
</script>
<script language="javascript">
<!--
function checkBasic(type){
   var check00 = document.getElementById("02-00").checked;
   document.getElementById("02-01").checked = check00;
   document.getElementById("02-02").checked = check00;
   document.getElementById("02-03").checked = check00;
   document.getElementById("02-04").checked = check00;
}
function checkBasic2(ck){
   var check00 = document.getElementById("02-00").checked
   if(ck.checked == true || check00){
      document.getElementById("02-00").checked = true;
      document.getElementById("02-01").checked = true;
   }
}
function checkWork(type){
   var check00 = document.getElementById("03-00").checked;
   document.getElementById("03-01").checked = check00;
   document.getElementById("03-02").checked = check00;
   document.getElementById("03-03").checked = check00;
}
function checkWork2(ck){
   var check00 = document.getElementById("03-00").checked
   if(ck.checked == true || check00){
      document.getElementById("03-00").checked = true;
      document.getElementById("03-01").checked = true;
   }
}
function checkMember(type){
   var check00 = document.getElementById("04-00").checked;
   document.getElementById("04-01").checked = check00;
   document.getElementById("04-02").checked = check00;
   document.getElementById("04-03").checked = check00;
   document.getElementById("04-04").checked = check00;
   document.getElementById("04-05").checked = check00;
   document.getElementById("04-06").checked = check00;
}
function checkMember2(ck){
   var check00 = document.getElementById("04-00").checked
   if(ck.checked == true || check00){
      document.getElementById("04-00").checked = true;
      document.getElementById("04-01").checked = true;
   }
}
function checkBbs(type){
   var check00 = document.getElementById("05-00").checked;
   document.getElementById("05-01").checked = check00;
   document.getElementById("05-02").checked = check00;
}
function checkBbs2(ck){
   var check00 = document.getElementById("05-00").checked
   if(ck.checked == true || check00){
      document.getElementById("05-00").checked = true;
      document.getElementById("05-01").checked = true;
   }
}

function checkMarketing(type){
   var check00 = document.getElementById("07-00").checked;
   document.getElementById("07-01").checked = check00;
   document.getElementById("07-02").checked = check00;
   document.getElementById("07-03").checked = check00;
}
function checkMarketing2(ck){
   var check00 = document.getElementById("07-00").checked
   if(ck.checked == true || check00){
      document.getElementById("07-00").checked = true;
      document.getElementById("07-01").checked = true;
   }
}
-->
</script>
</head>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">관리자목록</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">관리자를 추가/수정/삭제 합니다.</td>
        </tr>
      </table>

      <br>
      <form name="frm" action="twcenter_save.php" method="post" onSubmit="return inputCheck(this);" enctype="multipart/form-data">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td height="30" align="left" class="t_name">관리자등급 <font color=red>*</font></td>
                <td class="t_value" colspan="3">
					<select name="lev" class="select">
						<option value="">:: 등급선택 ::</option>
						<option value="10000"<?if($_admin_info['lev'] == 10000) echo " selected";?>>슈퍼관리자</option>
						<? 
						$sql_lev = "select * from wiz_admin_lev order by idx asc";
						$res_lev = query($sql_lev);
						while($row_lev = sql_fetch_arr($res_lev)) {
						?>
						<option value="<?=$row_lev['idx']?>"<?if($_admin_info['lev'] === $row_lev['idx']) echo " selected";?>><?=$row_lev['name']?></option>
						<?
						}
						?>
					</select>
                </td>
			  </tr>
              <tr>
                <td width="15%" height="30" align="left" class="t_name">아이디 <font color=red>*</font></td>
                <td width="35%" class="t_value">
                	<input name="id" type="text" value="<?=$_admin_info['id']?>" class="input" readonly>
					<? if(strcmp($mode, "update")) { ?>
                	<img src="../image/btn_idcheck.gif" align="absmiddle" style="cursor:hand" onCLick="idCheck()">
					<? } ?>
                </td>
                <td width="15%" height="30" align="left" class="t_name">비밀번호 <font color=red>*</font></td>
                <td width="35%" class="t_value"><input name="passwd" type="text" class="input">
				<font color=red>--, #, ; 은 비밀번호 사용이 불가합니다.</font>
				</td>
              </tr>
              <tr>
                <td height="30" align="left" class="t_name">이름 <font color=red>*</font></td>
                <td class="t_value"><input name="name" type="text" value="<?=$_admin_info['name']?>" class="input"></td>
                <td height="30" align="left" class="t_name">이메일 <font color=red>*</font></td>
                <td class="t_value"><input name="email" type="text" value="<?=$_admin_info['email']?>" class="input"></td>
              </tr>
              <tr>
                <td height="30" align="left" class="t_name">전화번호</td>
                <td class="t_value">
                  <? 
				  if(!isset($_admin_info['tphone'])) $_admin_info['tphone'] = '';
				  list($tphone, $tphone2, $tphone3) = explode("-",$_admin_info['tphone']); ?>
                  <input type="text" name="tphone" value="<?=$tphone?>" size="5" class="input"> -
                  <input type="text" name="tphone2" value="<?=$tphone2?>" size="5" class="input"> -
                  <input type="text" name="tphone3" value="<?=$tphone3?>" size="5" class="input">
                </td>
                <td height="30" align="left" class="t_name">휴대폰</td>
                <td class="t_value">
                  <? 
				  if(!isset($_admin_info['hphone'])) $_admin_info['hphone'] = '';
				  list($hphone, $hphone2, $hphone3) = explode("-",$_admin_info['hphone']); ?>
                  <input type="text" name="hphone" value="<?=$hphone?>"  size="5" class="input"> -
                  <input type="text" name="hphone2" value="<?=$hphone2?>"  size="5" class="input"> -
                  <input type="text" name="hphone3" value="<?=$hphone3?>"  size="5" class="input">
                  </td>

              </tr>
              <tr>
                <td height="30" align="left" class="t_name">우편번호</td>
                <td class="t_value" colspan="3">
                  <? 
				  if(!isset($_admin_info['post'])) $_admin_info['post'] = '';
				  $post1 = str_replace("-","",$_admin_info['post']); ?>
                  <input name="post1" type="text" value="<?=$post1?>" size="5" class="input">
                  <input type="button" value="우편번호검색" class="base_btn2" onClick="searchZip('');">
                </td>
              </tr>
              <tr>
                <td height="30" align="left" class="t_name">주소</td>
                <td class="t_value" colspan="3">
                <input name="address1" type="text" value="<?=$_admin_info['address1']?>" size="60" class="input"><br>
                <input name="address2" type="text" value="<?=$_admin_info['address2']?>" size="60" class="input">
                </td>
              </tr>
              <?php if($wiz_admin['designer'] == "Y"){ ?>
              <!-- <tr>
                <td height="25" align="left" class="t_name">접근권한</td>
                <td class="t_value" colspan="3">
                  <?
                  $permi_list = explode("/",$_twcenter_info['permi']);
                  for($ii=0; $ii<count($permi_list); $ii++){
                     $tmp_permi[$permi_list[$ii]] = true;
                  }
                  ?>
                  <table border="0" cellpadding="5" width="100%">
                    <tr>
                      <td width="33%" bgcolor="#efefef" valign="top">
                        <input type="checkbox" size="20" name="permi[]" value="00-00" checked disabled><b>관리자(인트라넷)접근</b><br><br>
                        <input type="checkbox" size="20" name="permi[]" value="01-00" <? if($tmp_permi["01-00"]==true || $mode == "insert") echo "checked"; ?>><b>환경설정</b><br><br>
                        <input type="checkbox" size="20" name="permi[]" value="06-00" <? if($tmp_permi["06-00"]==true || $mode == "insert") echo "checked"; ?>><b>폼메일</b>
                      </td>
                      <td width="33%" bgcolor="#efefef" valign="top">
                        <input type="checkbox" size="20" name="permi[]" id="02-00" value="02-00" onClick="checkBasic();" <? if($tmp_permi["02-00"]==true || $mode == "insert") echo "checked"; ?>><b>기본정보</b><br>
                        <input type="checkbox" size="20" name="permi[]" id="02-01" value="02-01" onClick="checkBasic2(this);" <? if($tmp_permi["02-01"]==true || $mode == "insert") echo "checked"; ?>>기본정보설정<br>
                        <input type="checkbox" size="20" name="permi[]" id="02-02" value="02-02" onClick="checkBasic2(this);" <? if($tmp_permi["02-02"]==true || $mode == "insert") echo "checked"; ?>>관리자설정<br>
                        <input type="checkbox" size="20" name="permi[]" id="02-03" value="02-03" onClick="checkBasic2(this);" <? if($tmp_permi["02-03"]==true || $mode == "insert") echo "checked"; ?>>팝업관리<br>
                        <input type="checkbox" size="20" name="permi[]" id="02-04" value="02-04" onClick="checkBasic2(this);" <? if($tmp_permi["02-04"]==true || $mode == "insert") echo "checked"; ?>>SMS관리/충전
                      </td>
                      <td width="33%" bgcolor="#efefef" valign="top">
                        <input type="checkbox" size="20" name="permi[]" id="03-00" onClick="checkWork();" value="03-00" <? if($tmp_permi["03-00"]==true || $mode == "insert") echo "checked"; ?>><b>사내업무</b><br>
                        <input type="checkbox" size="20" name="permi[]" id="03-01" onClick="checkWork2(this);" value="03-01" <? if($tmp_permi["03-01"]==true || $mode == "insert") echo "checked"; ?>>스케쥴관리<br>
                        <input type="checkbox" size="20" name="permi[]" id="03-02" onClick="checkWork2(this);" value="03-02" <? if($tmp_permi["03-02"]==true || $mode == "insert") echo "checked"; ?>>사내웹하드<br>
                        <input type="checkbox" size="20" name="permi[]" id="03-03" onClick="checkWork2(this);" value="03-03" <? if($tmp_permi["03-03"]==true || $mode == "insert") echo "checked"; ?>>거래처관리
                      </td>
                    </tr>
                    <tr>
                      <td bgcolor="#efefef" valign="top">
                        <input type="checkbox" size="20" name="permi[]" id="04-00" onClick="checkMember();" value="04-00" <? if($tmp_permi["04-00"]==true || $mode == "insert") echo "checked"; ?>><b>회원관리</b><br>
                        <input type="checkbox" size="20" name="permi[]" id="04-01" onClick="checkMember2(this);" value="04-01" <? if($tmp_permi["04-01"]==true || $mode == "insert") echo "checked"; ?>>회원목록<br>
                        <input type="checkbox" size="20" name="permi[]" id="04-02" onClick="checkMember2(this);" value="04-02" <? if($tmp_permi["04-02"]==true || $mode == "insert") echo "checked"; ?>>회원등록<br>
                        <input type="checkbox" size="20" name="permi[]" id="04-03" onClick="checkMember2(this);" value="04-03" <? if($tmp_permi["04-03"]==true || $mode == "insert") echo "checked"; ?>>등급관리<br>
                        <input type="checkbox" size="20" name="permi[]" id="04-04" onClick="checkMember2(this);" value="04-04" <? if($tmp_permi["04-04"]==true || $mode == "insert") echo "checked"; ?>>탈퇴회원<br>
                        <input type="checkbox" size="20" name="permi[]" id="04-05" onClick="checkMember2(this);" value="04-05" <? if($tmp_permi["04-05"]==true || $mode == "insert") echo "checked"; ?>>메일발송<br>
                        <input type="checkbox" size="20" name="permi[]" id="04-06" onClick="checkMember2(this);" value="04-06" <? if($tmp_permi["04-06"]==true || $mode == "insert") echo "checked"; ?>>SMS발송<br>
                      </td>
                      <td bgcolor="#efefef" valign="top">
                        <input type="checkbox" size="20" name="permi[]" id="05-00" onClick="checkBbs();" value="05-00" <? if($tmp_permi["05-00"]==true || $mode == "insert") echo "checked"; ?>><b>게시판관리</b><br>
                        <input type="checkbox" size="20" name="permi[]" id="05-01" onClick="checkBbs2(this);" value="05-01" <? if($tmp_permi["05-01"]==true || $mode == "insert") echo "checked"; ?>>게시판목록<br>
                        <input type="checkbox" size="20" name="permi[]" id="05-02" onClick="checkBbs2(this);" value="05-02" <? if($tmp_permi["05-02"]==true || $mode == "insert") echo "checked"; ?>>게시물관리<br>
                      </td>
                      <td bgcolor="#efefef" valign="top">
                        <input type="checkbox" size="20" name="permi[]" id="07-00" onClick="checkMarketing();" value="07-00" <? if($tmp_permi["07-00"]==true || $mode == "insert") echo "checked"; ?>><b>마케팅분석</b><br>
                        <input type="checkbox" size="20" name="permi[]" id="07-01" onClick="checkMarketing2(this);" value="07-01" <? if($tmp_permi["07-01"]==true || $mode == "insert") echo "checked"; ?>>접속자분석<br>
                        <input type="checkbox" size="20" name="permi[]" id="07-02" onClick="checkMarketing2(this);" value="07-02" <? if($tmp_permi["07-02"]==true || $mode == "insert") echo "checked"; ?>>접속경로분석<br>
                        <input type="checkbox" size="20" name="permi[]" id="07-03" onClick="checkMarketing2(this);" value="07-03" <? if($tmp_permi["07-03"]==true || $mode == "insert") echo "checked"; ?>>회원통계<br>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr> -->
              <?php } ?>
              <tr>
                <td height="25" align="left" class="t_name">메모</td>
                <td class="t_value" colspan="3">
                <textarea name="descript" rows="5" cols="90" class="textarea"><?=$_admin_info['descript'] ?></textarea>
                </td>
              </tr>
            </table></td>
        </tr>
      </table>

      <br>
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center" >
			<input type="submit" value="확인" class="base_btn reg">
			<input type="button" value="목록" class="base_btn gray" onclick="document.location='twcenter_list.php?page=<?=$page?>';">
          </td>
        </tr>
      </table>
	  </form>

<? include "../foot.php"; ?>