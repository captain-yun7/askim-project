<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>
<?
$param = "slevel=".$slevel."&searchopt=".$searchopt."&searchkey=".$searchkey."&".$menucodeParam;
?>
<script language="JavaScript" type="text/javascript">
<!--

// 체크박스 전체선택
function selectAll(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].id != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = true;
			}
		}
	}
	return;
}

// 체크박스 선택해제
function selectCancel(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].id != null){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

// 체크박스선택 반전
function selectReverse(form){
	if(form.select_tmp.checked){
		selectAll();
	}else{
		selectCancel();
	}
}

// 체크박스 선택리스트
function selectValue(){
	var i;
	var seluser = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					seluser = seluser + document.forms[i].idx.value + "|";
				}
			}
	}
	return seluser;
}

//선택회원 삭제
function delUser(){

	seluser = selectValue();

	if(seluser == ""){
		alert("삭제할 쪽지를 선택하세요.");
		return false;
	}else{
		if(confirm("선택한 쪽지를 정말 삭제하시겠습니까?")){
			document.location = "message_save.php?mode=deluser&seluser=" + seluser + "&<?=$menucodeParam?>";
		}
	}
}

// 고객 메일발송
function sendMail(seluser){

	if(seluser == ""){
		var i;
		var seluser = "";
		for(i=0;i<document.forms.length;i++){
			if(document.forms[i].id != null){
				if(document.forms[i].select_checkbox){
					if(document.forms[i].select_checkbox.checked)
						seluser = seluser + document.forms[i].name.value + ":" + document.forms[i].email.value + ",";
				}
			}
		}
	}

  if(seluser == ""){
		alert("이메일 발송할 회원을 선택하세요.");
		return false;
	}else{
	   var url = "mail_popup.php?seluser=" + seluser;
	   window.open(url,"sendMail","height=650, width=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	}

}

// 고객 SMS발송
function sendSms(seluser){

	if(seluser == ""){
		var i;
		var seluser = "";
		for(i=0;i<document.forms.length;i++){
			if(document.forms[i].hphone != null){
				if(document.forms[i].select_checkbox){
					if(document.forms[i].select_checkbox.checked)
						seluser = seluser + document.forms[i].hphone.value + ",";
				}
			}
		}
	}

  if(seluser == ""){
		alert("SMS 발송할 회원을 선택하세요.");
		return false;
	}else{
	   var url = "sms_popup.php?seluser=" + seluser;
	   window.open(url,"sendSms","height=350, width=350, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	}

}

// 회원정보 엑셀다운
function excelDown(){

	document.location = "member_excel.php?<?=$param?>";

}

//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">쪽지목록</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">주고받은 쪽지를 관리합니다.</td>
        </tr>
      </table>

      <br>
      <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
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
               <select name="searchopt" class="select">
               <option value="se_name">보낸사람(이름)
               <option value="se_id">보낸사람(아이디)
               <option value="re_name">받은사람(이름)
               <option value="re_id">받은사람(아이디)
               <option value="subject">제목
               <option value="content">내용
               </select>
             </td>
             <td><input type="text" name="searchkey" value="<?=$searchkey?>" class="input"></td>
             <!-- <td><input type="image" src="../image/btn_search.gif"></td> -->
             </tr>
             </table>
             <script language="javascript">
             <!--
             searchopt = document.searchForm.searchopt;
             for(ii=0; ii<searchopt.length; ii++){
               if(searchopt.options[ii].value == "<?=$searchopt?>")
                 searchopt.options[ii].selected = true;
             }
             -->
             </script>

           </td>
           </tr>
           </table>
          </td>
        </tr>
      </table>
	  <br>
		<table width="100%" cellspacing="1" cellpadding="3" border="0">
			<tr>
				<td align="center">
					<input type="submit" value="검색" class="search_btn2">&nbsp;
					<input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>?<?=$menucodeParam?>'">
				</td>
			</tr>
		</table>
	  </form>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
      </table>
      <?
         $sql = "select count(idx) as all_total from wiz_message";
         $result = query($sql) or error("sql error");
         $row = sql_fetch_arr($result);
      	 $all_total = $row['all_total'];

      	 $sql = "select * from wiz_message";
         $sql .= " where idx != ''";
         if($searchopt != "") $sql .= " and $searchopt like '%$searchkey%'";
         $sql .=" order by idx desc";
      	$result = query($sql) or error("sql error");
      	$total = sql_fetch_row($result);

         $rows = 20;
         $lists = 5;
       	$page_count = ceil($total/$rows);
       	if(!$page || $page > $page_count) $page = 1;
       	$start = ($page-1)*$rows;
       	$no = $total-$start;

      ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="title_msg">총 쪽지수 : <strong id="total_prd_cnt"><?=$all_total?></strong> , 검색 쪽지수 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
          <td align="right">
          </td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <form>
        <tr><td colspan=20 class="t_rd"></td></tr>
        <tr class="t_th">
          <th width="5%"><input type="checkbox" name="select_tmp" onClick="selectReverse(this.form)"></th>
          <th width="8%">번호</th>
          <th width="10%">보낸사람</th>
          <th width="10%">받는사람</th>
          <th>제목</th>
          <th width="8%">보낸날짜</th>
          <th width="8%">첨부</th>
          <th width="8%">수신여부</th>
          <th width="10%">기능</th>
        </tr>
        <tr><td colspan=20 class="t_rd"></td></tr>
        </form>
		<?
		$sql = "select * from wiz_message";
		$sql .= " where idx != ''";
		if($searchopt != "") $sql .= " and $searchopt like '%$searchkey%'";
		$sql .=" order by idx desc limit $start, $rows";
		$result = query($sql) or error("sql error");

		while($row = sql_fetch_arr($result)){
			if(!empty($row['upfile'])) $row['upfile'] = "Y";
			else $row['upfile'] = "N";
		?>
	      <form name="frm<?=$no?>">
        <input type="hidden" name="idx" value="<?=$row['idx']?>">
        <tr>
          <td height="38" align="center"><input type="checkbox" name="select_checkbox"></td>
          <td align="center"><?=$no?></td>
          <td align="center"><?=$row['se_name']?> (<?=$row['se_id']?>)</td>
          <td align="center"><?=$row['re_name']?> (<?=$row['re_id']?>)</td>
          <td align="center"><a href="message_input.php?mode=update&idx=<?=$row['idx']?>&page=<?=$page?>&<?=$param?>"><?=$row['subject']?></a></td>
          <td align="center"><?=substr($row['wdate'],0,10)?> &nbsp;</td>
          <td align="center"><?=$row['upfile']?></td>
          <td align="center"><?=$row['status']?></td>
          <td align="center"><img src="../image/btn_view_s.gif" style="cursor:pointer" onClick="document.location='message_input.php?mode=update&idx=<?=$row['idx']?>&page=<?=$page?>&<?=$param?>';"></td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
        </form>
      <?
     		$no--;
      }

    	if($total <= 0){
    	?>
    	  <tr><td height="30" colspan="10" align="center">등록된 쪽지가 없습니다.</td></tr>
        <tr><td colspan="20" class="t_line"></td></tr>
    	<?
    	}
      ?>
      </table>

      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
      	<tr><td height="5"></td></tr>
        <tr>
          <td width="33%">
            <!-- <img src="../image/btn_seldel.gif" style="cursor:hand" onClick="delUser('');"> -->
			<input type="button" value="선택삭제" class="btnListchk" onclick="delUser('');">
          </td>
          <td width="33%"><? print_pagelist($page, $lists, $page_count, $param); ?></td>
          <td width="33%"></td>
        </tr>
      </table>

<? include "../foot.php"; ?>