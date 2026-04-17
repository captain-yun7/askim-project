<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
$param = "sdate=".$sdate."&edate=".$edate."&level=".$level."&searchopt=".$searchopt."&searchkey=".$searchkey."&reemail=".$reemail;
?>
<? include "../head.php"; ?>


<script language="JavaScript" type="text/javascript">
<!--
function sendMail(){

	var frm = document.frm;
  if(frm.subject.value == ""){
		alert("제목을 입력하세요");
		frm.subject.focus();
		return;
	}
	if(content.outputBodyHTML() == ""){
		alert("내용을 입력하세요");
		return;
	}

	if(confirm("메일을 발송 하시겠습니까? \n\n메일 발송창을 완료시까지 닫지마세요.")){

	window.open("","mailWin","height=300, width=300, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, top=100, left=100");

		frm.action = "mail_save.php";
		frm.target = "mailWin";
		frm.submit();

	}else{
		return;
	}

	frm.action = "<?=$PHP_SELF?>";
	frm.target = "";

}

$(function() {

	$('#sdate').datepicker({
		language: 'kr',
		autoClose: true

	});
	$('#edate').datepicker({
		language: 'kr',
		autoClose: true
	});

});

//-->
</script>

<?
$sql = "select id from wiz_member";
$result = query($sql) or error("sql error");
$all_total = sql_fetch_row($result);

if($sdate != "") $sdate_sql = " and wdate > '$sdate' ";
if($edate != "") $edate_sql = " and wdate <= '$edate 23:59:59' ";
if($searchkey != "") $search_sql = " and $searchopt like '%$searchkey%' ";
if($level != "") $level_sql = " and level = '$level' ";
if($reemail == "N") $reemail_sql = " and reemail != 'N' ";

$sql = "select id,passwd,name,hphone,email,visit,reemail,wdate from wiz_member where id != '' $sdate_sql $edate_sql $search_sql $level_sql$reemail_sql order by wdate desc";
//echo $sql;
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);

$rows = 6;
$lists = 5;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $start+1;
?>

      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">단체메일발송</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">회원을 검색하여 단체 메일을 발송합니다.</td>
        </tr>
      </table>

      <br>
      <form name="frm" action="<?=$PHP_SELF?>" method="post">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="mode" value="mailsend">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td bgcolor="ffffff">
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
            <tr>
            <td width="15%" class="t_name">&nbsp; 조건검색</td>
            <td width="85%" class="t_value">

             <table cellspacing="2" cellpadding="0">
             <tr>
             <td>
               <select name="level" class="select">
               <option value=""> :: 등급선택 ::</option>
               <?=level_list();?>
               </select>
             </td>
             <td>
               <select name="searchopt" class="select">
               <option value="name">고객명
               <option value="id">아이디
               <option value="resno">주민번호
               <option value="email">이메일
               <option value="tphone">전화번호
               <option value="hphone">휴대폰
               </select>
             </td>
             <td><input type="text" name="searchkey" value="<?=$searchkey?>" class="input"></td>
             <td><input type="image" src="../image/btn_search.gif"></td>
             </tr>
             </table>
             <script language="javascript">
             <!--
             level = document.frm.level;
             for(ii=0; ii<level.length; ii++){
               if(level.options[ii].value == "<?=$level?>")
                 level.options[ii].selected = true;
             }
             searchopt = document.frm.searchopt;
             for(ii=0; ii<searchopt.length; ii++){
               if(searchopt.options[ii].value == "<?=$searchopt?>")
                 searchopt.options[ii].selected = true;
             }
             -->
             </script>

           </td>
           </tr>
           <tr>
            <td width="120" class="t_name">&nbsp; 가입기간</td>
            <td class="t_value">
            	<? if($sdate == "") $sdate = ""; if($edate == "") $edate = ""; ?>
              <input type="text" id="sdate" name="sdate" value="<?=$sdate?>" size="12" class="datepicker-here input2"> ~
              <input type="text" id="edate" name="edate" value="<?=$edate?>" size="12" class="datepicker-here input2">
            </td>
            </tr>
            <tr>
            <td width="120" class="t_name">&nbsp; 메일수신</td>
            <td class="t_value">
            	<span style="vertical-align: middle"><input type="radio" name="reemail" value="Y" <? if($reemail == "Y" || $reemail == "") echo "checked"; ?>></span>회원전체
                <span style="vertical-align: middle"><input type="radio" name="reemail" value="N" <? if($reemail == "N") echo "checked"; ?>></span>수신거부회원 제외
            </td>
            </tr>
           </table>
          </td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td height="15"></td></tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>총 회원수 : <?=$all_total?> , 검색 회원수 : <?=$total?></td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td colspan=20 class="t_rd"></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th>이름</th>
          <th>아이디</th>
          <th>휴대폰</th>
          <th>이메일</th>
          <th width="5%">방문수</th>
          <th width="10%">메일수신</th>
          <th width="10%">가입일</th>
        </tr>
        <tr><td colspan=20 class="t_rd"></td></tr>
				<?
				$sql = "select id,passwd,name,hphone,email,visit,reemail,wdate from wiz_member where id != '' $sdate_sql $edate_sql $search_sql $level_sql$reemail_sql order by wdate desc limit $start, $rows";
				$result = query($sql) or error("sql error");

				while($row = sql_fetch_obj($result)){
					if($row->reemail == "N") $row->reemail = "아니오";
					else $row->reemail = "예";
				?>
        <input type="hidden" name="id" value="<?=$row->id?>">
        <tr>
          <td align="center" height="30"><?=$no?></td>
          <td align="center"><?=$row->name?></td>
          <td align="center"><?=$row->id?></td>
          <td align="center"><?=$row->hphone?></td>
          <td align="center"><?=$row->email?></td>
          <td align="center"><?=$row->visit?></td>
          <td align="center"><?=$row->reemail?></td>
          <td align="center"><?=substr($row->wdate,0,10)?> &nbsp;</td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
     <?
				$no++;
			}

    	if($total <= 0){
    	?>
    		<tr><td height=30 colspan=10 align=center>검색된 회원이 없습니다.</td></tr>
    		<tr><td colspan="20" class="t_line"></td></tr>
    	<?
    	}
      ?>
      </table>

      <br>

      <? print_pagelist($page, $lists, $page_count, "$param"); ?>

      <br>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="15%" class="t_name">분할발송</td>
                <td width="85%" class="t_value">
                <? if($snum == "") $snum = "1"; if($enum == "") $enum = "1000"; ?>
                <input type="text" name="snum" value="<?=$snum?>" size="6" class="input" checked>번부터 ~
                <input type="text" name="enum" value="<?=$enum?>" size="6" class="input">번까지 발송
                </td>
              </tr>
              <tr>
                <td class="t_name">제목</td>
                <td class="t_value">
                <input type="text" name="subject" value="<?=$subject?>" size="80" class="input">
                </td>
              </tr>
              <tr>
                <td class="t_name">내용</td>
                <td class="t_value">

                <select name="mailform" onChange="this.form.submit()" class="select">
                <option value="">:: 메일폼 선택 ::</option>
                <?
                $sql = "select * from wiz_mailsms";
					      $result = query($sql) or error("sql error");
					      while($row = sql_fetch_arr($result)){
                ?>
                <option value="<?=$row['code']?>"><?=$row['subject']?></option>
                <?
              	}
                ?>
                </select>
                <script language="javascript">
                mailform = document.frm.mailform;
                for(ii=0; ii<mailform.length; ii++){
                  if(mailform.options[ii].value == "<?=$mailform?>")
                  mailform.options[ii].selected = true;
                }
                </script>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="t_value">
                <?
                // 메일스킨
                if($mailform == "") $mailform = "basic";
                $sql = "select * from wiz_mailsms where code = '$mailform'";
                $result = query($sql) or error("sql error");
                $row = sql_fetch_obj($result);

                $edit_content = $row->email_msg;
                $edit_content = site_replace($site_info, $re_info, $edit_content);
                include "../../webedit/WIZEditor.html";
                ?>
                 <table width="98%" border="0" cellpadding="5" cellspacing="3" align="center" class="e_style">
                    <tr>
                      <td bgcolor="#FFFFFF">
                      <table>
                      <tr>
                      <td><b>{DATE}</b> 오늘날짜 &nbsp;</td>
                      <td><b>{MEM_ID}</b> 회원아이디 &nbsp;</td>
                      <!--td><b>{MEM_PW}</b> 회원비밀번호 &nbsp;</td-->
                      <td><b>{MEM_NAME}</b> 회원이름</td>
                      </tr>
                      <tr>
                      <td><b>{SITE_NAME}</b> 사이트명 &nbsp;</td>
                      <td><b>{SITE_EMAIL}</b> 사이트 이메일</td>
                      </tr>
                      <tr>
                      <td><b>{SITE_TEL}</b> 사이트 전화번호 &nbsp;</td>
                      <td><b>{SITE_URL}</b> 사이트 주소로 변경되어 발송됩니다.</td>
                      <td></td>
                      </tr>
                      </table>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table width="100%" height="5" border="0" cellpadding="0" cellspacing="0">
        <tr><td></td></tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="20"><font color=red>+ 포탈사이트에서 해당 서버를 차단한경우 메일이 정상적으로 발송되지 않습니다.</font></td>
        </tr>
        <tr>
          <td height="20"><font color=red>+ 1회 발송량이 많을경우 서버 설정에 따라 발송중 중단될 수 있습니다.</font></td>
        </tr>
      </table>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr><td></td></tr>
      </table>
      <table border="0" align="center" cellspacing="0" cellpadding="0">
        <tr>
          <td><img src="../image/btn_send_l.gif" style="cursor:hand" onClick="sendMail();"></td>
        </tr>
      </table>
	  </form>

<? include "../foot.php"; ?>