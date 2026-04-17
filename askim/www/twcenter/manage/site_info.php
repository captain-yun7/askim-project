<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/site_info.inc"; ?>
<? include "../../inc/twcenter_check.inc"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link href="../style.css" rel="stylesheet" type="text/css">
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
	if(frm.site_tel.value == ""){
		alert("관리자 연락처를 입력하세요.");
		frm.site_tel.focus();
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

function searchZip(){
	document.frm.com_address.focus();
	var url = "../member/search_zip.php?kind=com_";
	window.open(url,"searchZip","height=350, width=367, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}
-->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="26" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td background="../img/top_line_back.gif"><table width="506" height="20" border="0" cellspacing="0">
        <tr>
          <td><img src="../img/dot.gif" width="28" height="1"></td>
          <td width="476"><font color="#FFFFFF">HOME &gt; 기본설정 &gt; 기본정보설정</font></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="12"></td>
    <td>

      <table height="26" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="22"><img src="../img/icon_04.gif" width="15" height="15"></td>
          <td><strong>기본정보설정</strong></td>
          <td width="10"></td>
          <td>사이트 관리에 필요한 기본정보를 설정합니다.</td>
          <td width="5"></td>
          <td width="50"><img src="../img/menuall.gif" width="47"></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr><td height="3" bgcolor="CCCCCC"></td></tr>
        <tr><td height="10">&nbsp;</td></tr>
      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="b_title02">+ 사이트정보</td>
        </tr>
      </table>
      <form name="frm" action="site_save.php" method="post" onSubmit="return inputCheck(this);">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="site_info">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
              <tr>
                <td align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;사이트명</td>
                <td class="t_value" colspan="3"><input name="site_name" value="<?=$site_info->site_name?>" type="text" class="input"></td>
              </tr>
              <tr>
                <td align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;사이트 URL</td>
                <td class="t_value" colspan="3"><input name="site_url" type="text" value="<?=$site_info->site_url?>" size="60" class="input"></td>
              </tr>
              <tr>
                <td width="20%" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;관리자 이메일</td>
                <td width="30%" class="t_value"><input name="site_email" type="text" value="<?=$site_info->site_email?>" size="28" class="input"></td>
                <td width="20%" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;관리자 휴대폰</td>
                <td width="30%" class="t_value"><input name="site_tel" type="text" value="<?=$site_info->site_tel?>" class="input"></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="30"><font color=red>- 관리자 이메일,휴대폰번호로 회원가입,탈퇴,폼메일 등 사이트에서 일어나는 상황을 알려줍니다.</font></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="b_title02">+ FTP 정보</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
              <tr>
                <td align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;호스트명</td>
                <td class="t_value" colspan="3"><input name="ftp_host" value="<?=$site_info->ftp_host?>" type="text" class="input"></td>
              </tr>
              <tr>
                <td width="20%" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;아이디</td>
                <td width="30%" class="t_value"><input name="ftp_id" type="text" value="<?=$site_info->ftp_id?>" size="28" class="input"></td>
                <td width="20%" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;비밀번호</td>
                <td width="30%" class="t_value">
                <input name="ftp_pw" type="text" value="" class="input"> <?=set_passwd($site_info->ftp_pw)?>
                </td>
              </tr>
            </table></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr><td height="10"></td></tr>
        <tr>
          <td class="b_title02">+ 도메인 정보</td>
          <td align="right"><input name="Button" type="button" class="gbtn" value="등록"  onClick="inputDomain('insert','');"></td>
        </tr>
      </table>
      <table width="100%" border=0 cellspacing="1" cellpadding="3" class="t_style">
        <tr align="center" class="t_name">
          <td width="5%">No</td>
          <td width="20%">도메인</td>
          <td width="20%">구입사이트</td>
          <td width="12%">아이디</td>
          <td width="13%">비밀번호</td>
          <td width="15%">만료일</td>
          <td width="15%">기능</td>
        </tr>
        <?
        $no = 1;
        $sql = "select * from wiz_otherinfo where type = 'domain' order by idx asc";
        $result = query($sql) or error("sql error");
        $total = sql_fetch_row($result);
        while($row = sql_fetch_obj($result)){
        ?>
        <tr align="center" class="t_value">
          <td><?=$no?></td>
          <td><?=$row->info01?></td>
          <td><?=$row->info02?></td>
          <td><?=$row->info03?></td>
          <td><?=set_passwd($row->info04)?></td>
          <td><?=$row->info05?></td>
          <td><a href="javascript:inputDomain('update','<?=$row->idx?>')">[수정]</a> <a href="javascript:inputDomain('delete','<?=$row->idx?>')">[삭제]</a></td>
        </tr>
        <?
         $no++;
        }
        if($total <= 0){
        ?>
        <tr align="center" class="t_value"><td colspan="10" align="center">등록된 도메인이 없습니다.</td></tr>
        <?
        }
        ?>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr><td height="10"></td></tr>
        <tr>
          <td class="b_title02">+ 이메일 정보</td>
          <td align="right"><input name="Button" type="button" class="gbtn" value="등록" onClick="inputEmail('insert','');"></td>
        </tr>
      </table>
      <table width="100%" border=0 cellspacing="1" cellpadding="3" class="t_style">
        <tr align="center" class="t_name">
          <td width="5%">No</td>
          <td width="20%">이메일</td>
          <td width="20%">사용자명</td>
          <td width="20%">아이디</td>
          <td width="20%">비밀번호</td>
          <td width="15%">기능</td>
        </tr>
        <?
        $no = 1;
        $sql = "select * from wiz_otherinfo where type = 'email' order by idx asc";
        $result = query($sql) or error("sql error");
        $total = sql_fetch_row($result);
        while($row = sql_fetch_obj($result)){
        ?>
        <tr align="center" class="t_value">
          <td><?=$no?></td>
          <td><?=$row->info01?></td>
          <td><?=$row->info02?></td>
          <td><?=$row->info03?></td>
          <td><?=set_passwd($row->info04)?></td>
          <td><a href="javascript:inputEmail('update','<?=$row->idx?>')">[수정]</a> <a href="javascript:inputEmail('delete','<?=$row->idx?>')">[삭제]</a></td>
        </tr>
        <?
          $no++;
        }
        if($total <= 0){
        ?>
        <tr align="center" class="t_value"><td colspan="10" align="center">등록된 이메일이 없습니다.</td></tr>
        <?
        }
        ?>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr><td height="10"></td></tr>
        <tr>
          <td class="b_title02">+ 사업자정보</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
              <tr>
                <td align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;사업자등록번호</td>
                <td class="t_value" colspan="3"><input name="com_num" type="text" value="<?=$site_info->com_num?>" class="input"></td>
              </tr>
              <tr>
                <td width="20%" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;상호</td>
                <td width="30%" class="t_value"><input name="com_name" type="text" value="<?=$site_info->com_name?>" class="input"></td>
                <td width="20%" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;대표자명</td>
                <td width="30%" class="t_value"><input name="com_owner" type="text" value="<?=$site_info->com_owner?>" class="input"></td>
              </tr>
              <tr>
                <td align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;우편번호</td>
                <td class="t_value" colspan="3">
                  <? list($post, $post2) = explode("-",$site_info->com_post); ?>
                  <input name="com_post" type="text" value="<?=$post?>" size="5" class="input"> -
                  <input name="com_post2" type="text" value="<?=$post2?>" size="5" class="input">
                  <input name="Button" type="button" class="gbtn" value=" 찾 기 " onClick="searchZip();">
                </td>
              </tr>
              <tr>
                <td align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;주소</td>
                <td class="t_value" colspan="3"><input name="com_address" type="text" value="<?=$site_info->com_address?>" size="50" class="input"></td>
              </tr>
              <tr>
                <td align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;업태</td>
                <td class="t_value"><input name="com_kind" type="text" value="<?=$site_info->com_kind?>" class="input"></td>
                <td align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;종목</td>
                <td class="t_value"><input name="com_class" type="text" value="<?=$site_info->com_class?>" class="input"></td>
              </tr>
              <tr>
                <td align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;전화번호</td>
                <td class="t_value"><input name="com_tel" type="text" value="<?=$site_info->com_tel?>" class="input"></td>
                <td align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;팩스번호</td>
                <td class="t_value"><input name="com_fax" type="text" value="<?=$site_info->com_fax?>" class="input"></td>
              </tr>
            </table></td>
        </tr>
      </table><br>

      <table width="100%" height="79" border="0" cellpadding="0" cellspacing="6" bgcolor="F9F9F9">
        <tr>
          <td height="30" colspan="2"><img src="../img/help.gif" width="107" height="19"></td>
        </tr>
        <tr>
          <td>FTP 정보, 도메인정보, 이메일정보, 사업자정보 는 사이트 운영에 영향을 미치는 것이 아니며</td>
        </tr>
        <tr>
          <td>분실하기 쉬운 정보를 작성하여 추후 사이트 관리에 이용하는 부분입니다.</td>
        </tr>
      </table><br>

      <table align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="60"><input type="submit" class="sbtn" value=" 확 인 "></td>
          <td width="10">&nbsp;</td>
          <td width="60"><input type="button" class="sbtn" value=" 취 소 " onClick="history.go(-1);"></td>
        </tr>
      </table>
	  </form><br><br><br>

    </td>
    <td width="14"></td>
  </tr>
</table>