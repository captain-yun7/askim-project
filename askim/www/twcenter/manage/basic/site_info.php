<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/site_info.php";

include "../head.php";
echo $DAUM_POSTCODE.PHP_EOL;

if(!empty($site_info['site_url'])) {
	$site_url = $site_info['site_url'];
} else {
	$site_url = $_SERVER['HTTP_HOST'];
}

?>

<script language="javascript">
<!--
function inputCheck(frm){

	if(frm.site_name.value == ""){
		alert("사이트명을 입력하세요.");
		frm.site_name.focus();
		return false;
	}
	if(frm.site_url.value == ""){
		alert("사이트 URL을 입력하세요.");
		frm.site_url.focus();
		return false;
	}
	if(frm.site_email.value == ""){
		alert("관리자 이메일을 입력하세요.");
		frm.site_email.focus();
		return false;
	}
	if(frm.site_hand.value == ""){
		alert("관리자 휴대폰을 입력하세요.");
		frm.site_hand.focus();
		return false;
	}
}

function inputDomain(submode,idx){
   if(submode == "delete"){
      if(confirm("삭제하시겠습니까?")){
         document.location = "site_save.php?mode=domain&submode=delete&idx=" + idx;
      }
   }else{
	   var url = "./domain_input.php?submode=" + submode + "&idx=" + idx;
	   window.open(url,"inputDomain","height=250, width=367, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
   }
}

function inputEmail(submode,idx){
   if(submode == "delete"){
      if(confirm("삭제하시겠습니까?")){
         document.location = "site_save.php?mode=email&submode=delete&idx=" + idx;
      }
   }else{
	   var url = "./email_input.php?submode=" + submode + "&idx=" + idx;
	   window.open(url,"inputEmail","height=250, width=367, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
   }
}

function searchZip() {

	kind = 'com_';
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

			if(eval('frm.'+kind+'address2') != null)
				eval('frm.'+kind+'address2').focus();
		}
	}).open();

}
-->
</script>
</head>

		<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">사이트정보</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">사이트 기본정보를 관리합니다.</td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 기본정보</td>
        </tr>
      </table>
      <form name="frm" action="site_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="site_info">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
              <tr>
                <td align="left" class="t_name">사이트명</td>
                <td class="t_value" colspan="3"><input name="site_name" value="<?=$site_info['site_name']?>" type="text" class="input"></td>
              </tr>
              <tr>
                <td align="left" class="t_name">사이트 URL</td>
                <td class="t_value" colspan="3"><input name="site_url" type="text" value="<?=$site_url?>" size="60" class="input"></td>
              </tr>
              <tr>
			  <?
			  /*
			  작업자	: 임서연
			  작업일시	: 2020-03-05
			  작업내용	: 관리자 이메일 경고문구 추가(수정 반영 작업)
			  */
			  ?>
                <td align="left" class="t_name">관리자 이메일</td>
                <td class="t_value" colspan="3"><input name="site_email" type="text" value="<?=$site_info['site_email']?>" size="60" class="input">
				<div class="sub_tit_alt2">기본 메일발송에 수신자메일로 이용됩니다. <u>누락 및 한 개 이상 입력 시</u> 메일이 발송되지 않습니다. </div>
				</td>
              </tr>
              <tr>
                <td align="left" class="t_name">게시판관리자 이메일</td>
                <td class="t_value" colspan="3"><input name="bbs_email" type="text" value="<?=$site_info['bbs_email']?>" size="60" class="input">
				<div class="sub_tit_alt2"> 게시판관리자 이메일수신은 여러명이 동시에 수신할 수 있습니다. 수신할 이메일을 콤마(,)로 구분하여 입력합니다.</div>
				</td>
              </tr> 
              <tr>
                <td width="15%" align="left" class="t_name">관리자 전화번호</td>
                <td width="35%" class="t_value">
					<input name="site_tel" type="text" value="<?=$site_info['site_tel']?>" size="28" class="input"><BR>
					<div class="sub_tit_alt2">- SMS 이용시 발신번호<br>- SMS 이용시 사전등록번호로 등록 필요 <b><a href="http://www.web2002.co.kr/customer/faq.php?code=faq&category=&searchopt=subject&searchkey=%BB%E7%C0%FC&x=0&y=0" target="_blank">[자세히 보기]</a></b></div>
				</td>
                <td width="15%" align="left" class="t_name">관리자 휴대폰</td>
                <td width="35%" class="t_value">
					<input name="site_hand" type="text" value="<?=$site_info['site_hand']?>" class="input"><BR>
					<div class="sub_tit_alt2">- 관리자 SMS 수신번호<br>- SMS 이용시 사전등록번호로 등록 필요 <b><a href="http://www.web2002.co.kr/customer/faq.php?code=faq&category=&searchopt=subject&searchkey=%BB%E7%C0%FC&x=0&y=0" target="_blank">[자세히 보기]</a></b></div>
				</td>
              </tr>
              <tr>
                <td align="left" class="t_name">공통 브라우저 타이틀</td>
                <td class="t_value" colspan="3"><input name="browser_title" type="text" value="<?=$site_info['browser_title']?>" size="60" class="input"></td>
              </tr>
              <tr>
                <td width="15%" align="left" class="t_name">공통 메타네임(Description)</td>
                <td class="t_value padd" colspan="3">
					<textarea name="searchkey_de" rows="3" cols="120" class="textarea"><?=$site_info['searchkey_de']?></textarea>
					<div class="sub_tit_alt2"> 네이버 검색결과에 사이트 설명으로 노출되는 부분입니다.</div>
					<div class="sub_tit_alt2"> 메타태그 설명은 30~45자를 유지하는 게 좋습니다.</div>
				</td>
              </tr>
              <tr>
                <td width="15%" align="left" class="t_name">공통 메타네임(Classification)</td>
                <td class="t_value padd" colspan="3">
					<textarea name="searchkey_cl" rows="3" cols="120" class="textarea"><?=$site_info['searchkey_cl']?></textarea>
					<div class="sub_tit_alt2"> 사이트의 분류(카테고리)를 작성합니다.</div>
				</td>
              </tr>

              <tr>
                <td width="15%" align="left" class="t_name">공통 메타네임(keywords)</td>
                <td class="t_value padd" colspan="3">
					<textarea name="searchkey" rows="3" cols="120" class="textarea"><?=$site_info['searchkey']?></textarea>
					<div class="sub_tit_alt_red">※ 해당 항목은 네이버 및 구글에선 참고용으로만 활용합니다.</div>
					<div class="sub_tit_alt_red">※ 검색엔진 검색시 키워드를 사이트의 접근성을 향상에 활용한다고 명시되어 있으나, 검색엔진반영에는 일정 시간이 소요되며 일부 반영이 안될 수 있습니다.</div>
					<div class="sub_tit_alt2"> 키워드나 키워드 구문을 콤마(,)로 구분하세요.</div>
					<div class="sub_tit_alt2"> 목록의 처음에 가장 중요한 단어나 구문을 넣으세요.</div>
				</td>
              </tr>
              <!-- <tr>
                <td width="15%" align="left" class="t_name">A/S 서비스 항목 추가</td>
                <td class="t_value" colspan="3"><textarea name="asitem" rows="3" cols="120" class="textarea"><?=$site_info['asitem']?></textarea><br/><font color=red> ※ 단어별 ','로 구분하셔야 합니다.</font></td>
              </tr> -->

            </table></td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="30"><font color=red>- 관리자 이메일,휴대폰번호로 회원가입,탈퇴,폼메일 등 사이트에서 일어나는 상황을 알려줍니다.</font></td>
        </tr>
      </table>


      <!-- <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> FTP정보</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td align="left" class="t_name">접속주소(ip,도메인)</td>
          <td class="t_value" colspan="3"><input name="ftp_host" value="<?=$site_info['ftp_host']?>" type="text" class="input"></td>
        </tr>
        <tr>
          <td width="15%" align="left" class="t_name">아이디</td>
          <td width="35%" class="t_value"><input name="ftp_id" type="text" value="<?=$site_info['ftp_id']?>" size="28" class="input"></td>
          <td width="15%" align="left" class="t_name">비밀번호</td>
          <td width="35%" class="t_value">
          <input name="ftp_pw" type="text" value="" class="input"> <?=set_passwd($site_info['ftp_pw'])?>
          </td>
        </tr>
      </table> -->

      <!-- <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 도메인 정보</td>
          <td align="right"><img src="../image/btn_insert_s.gif" onClick="inputDomain('insert','');" style="cursor:hand"></td>
        </tr>
      </table>
      <table width="100%" border=0 cellspacing="1" cellpadding="3" class="t_style">
        <tr align="center">
          <td width="5%" class="t_name">No</td>
          <td width="20%" class="t_name">도메인</td>
          <td width="20%" class="t_name">구입사이트</td>
          <td width="12%" class="t_name">아이디</td>
          <td width="13%" class="t_name">비밀번호</td>
          <td width="15%" class="t_name">만료일</td>
          <td width="15%" class="t_name">기능</td>
        </tr>
        <?
        $no = 1;
        $sql = "select * from wiz_otherinfo where type = 'domain' order by idx asc";
        $result = query($sql) or error("sql error");
        $total = sql_fetch_row($result);
        while($row = sql_fetch_arr($result)){
        ?>
        <tr align="center" class="t_value">
          <td><?=$no?></td>
          <td><?=$row['info01']?></td>
          <td><?=$row['info02']?></td>
          <td><?=$row['info03']?></td>
          <td><?=set_passwd($row['info04'])?></td>
          <td><?=$row['info05']?></td>
          <td>
          	<a href="javascript:inputDomain('update','<?=$row['idx']?>')"><img src="../image/btn_edit_s.gif" border="0"></a>
          	<a href="javascript:inputDomain('delete','<?=$row['idx']?>')"><img src="../image/btn_delete_s.gif" border="0"></a>
          </td>
        </tr>
        <?
         $no++;
        }
        if($total <= 0){
        ?>
        <tr align="center" class="t_value"><td colspan="10" align="center" height="45">등록된 도메인이 없습니다.</td></tr>
        <?
        }
        ?>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 이메일 정보</td>
          <td align="right"><img src="../image/btn_insert_s.gif" onClick="inputEmail('insert','');" style="cursor:hand"></td>
        </tr>
      </table>
      <table width="100%" border=0 cellspacing="1" cellpadding="3" class="t_style">
        <tr align="center">
          <td width="5%" class="t_name">No</td>
          <td width="20%" class="t_name">이메일</td>
          <td width="20%" class="t_name">사용자명</td>
          <td width="20%" class="t_name">아이디</td>
          <td width="20%" class="t_name">비밀번호</td>
          <td width="15%" class="t_name">기능</td>
        </tr>
        <?
        $no = 1;
        $sql = "select * from wiz_otherinfo where type = 'email' order by idx asc";
        $result = query($sql) or error("sql error");
        $total = sql_fetch_row($result);
        while($row = sql_fetch_arr($result)){
        ?>
        <tr align="center" class="t_value">
          <td><?=$no?></td>
          <td><?=$row['info01']?></td>
          <td><?=$row['info02']?></td>
          <td><?=$row['info03']?></td>
          <td><?=set_passwd($row['info04'])?></td>
          <td>
          	<a href="javascript:inputEmail('update','<?=$row['idx']?>')"><img src="../image/btn_edit_s.gif" border="0"></a>
          	<a href="javascript:inputEmail('delete','<?=$row['idx']?>')"><img src="../image/btn_delete_s.gif" border="0"></a>
          </td>
        </tr>
        <?
          $no++;
        }
        if($total <= 0){
        ?>
        <tr align="center" class="t_value"><td colspan="10" align="center" height="45">등록된 이메일이 없습니다.</td></tr>
        <?
        }
        ?>
      </table> -->

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 사업자정보</td>
        </tr>
      </table>

      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" align="left" class="t_name">사업자등록번호</td>
          <td width="35%" class="t_value"><input name="com_num" type="text" value="<?=$site_info['com_num']?>" class="input"></td>
          <td width="15%" align="left" class="t_name">인감</td>
          <td width="35%" class="t_value">
	        	<?php
				$file_path = "../../data/config/";
				foreach(glob($file_path."com_seal.*") as $value) {
					$files = $value;
				}
				if(!isset($files)) $files = '';
				
				if(is_file($files)){
					$fnames = array_pop(explode('.', $files));
					$ext    = strtolower($fnames);
				?>
				<img src='/twcenter/data/config/com_seal.<?php echo $ext ?>'>
				<input type='checkbox' name='delseal' value='Y'>
				<font color='red'>삭제</font> <br>
				<? } ?>
				<div class="filebox preview-image">
					<input class="input upload-name" value="파일선택" disabled="disabled">
					<label for="input-file">파일 업로드</label>
					<input type="file" name="com_seal" id="input-file" class="upload-hidden">
				</div>
          </td>
        </tr>
        <tr>
          <td align="left" class="t_name">상호</td>
          <td class="t_value"><input name="com_name" type="text" value="<?=$site_info['com_name']?>" class="input"></td>
          <td align="left" class="t_name">대표자명</td>
          <td class="t_value"><input name="com_owner" type="text" value="<?=$site_info['com_owner']?>" class="input"></td>
        </tr>
        <tr>
          <td align="left" class="t_name">우편번호</td>
          <td class="t_value" colspan="3">
            <? $post1 = str_replace("-","",$site_info['com_post']); ?>
			<input name="com_post1" type="text" value="<?=$post1?>" size="7" class="input" style="text-align:center">
			<input type="button" value="우편번호검색" class="base_btn2" onClick="searchZip('');">
          </td>
        </tr>
        <tr>
          <td align="left" class="t_name">주소</td>
          <td class="t_value" colspan="3"><input name="com_address1" type="text" value="<?=$site_info['com_address']?>" size="80" class="input"></td>
        </tr>
        <tr>
          <td align="left" class="t_name">업태</td>
          <td class="t_value"><input name="com_kind" type="text" value="<?=$site_info['com_kind']?>" class="input"></td>
          <td align="left" class="t_name">종목</td>
          <td class="t_value"><input name="com_class" type="text" value="<?=$site_info['com_class']?>" class="input"></td>
        </tr>
        <tr>
          <td align="left" class="t_name">전화번호</td>
          <td class="t_value"><input name="com_tel" type="text" value="<?=$site_info['com_tel']?>" class="input"></td>
          <td align="left" class="t_name">팩스번호</td>
          <td class="t_value"><input name="com_fax" type="text" value="<?=$site_info['com_fax']?>" class="input"></td>
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


	  <div class="helpTip">
		<h4>체크사항</h4>
		<div class="content">
		  <div class="title">
		  </div>
		  <div class="explain">
			  - 관리자 전화번호 및 휴대폰번호는 SMS이용시 <span class="sub_tit_alt3">사전등록번호</span> 로 등록된 전화번호를 등록해주세요. <a href="http://web2002.co.kr/customer/faq.php?code=faq&category=&searchopt=subject&searchkey=%BB%E7%C0%FC%B5%EE%B7%CF&x=0&y=0" target="_blank"><span class="sub_tit_alt3">[자세히 보기]</span></a><br>
			  - 분실하기 쉬운 정보를 작성하여 추후 사이트 관리에 이용하는 부분입니다.
		  </div>
		</div>
	  </div>



<? include "../foot.php"; ?>