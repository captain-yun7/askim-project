<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/lib/datepicker_lib.php";

$this_tbl = "wiz_member_dormancy";

$param = "slevel=".$slevel."&searchopt=".$searchopt."&searchkey=".$searchkey."&".$menucodeParam;

?>
<script language="JavaScript" type="text/javascript">
<!--

// 체크박스 전체선택
function selectAll(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].elements['id'] != null){
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
			if(document.forms[i].elements['id'] != null){
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
		if(document.forms[i].elements['id'] != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					seluser = seluser + document.forms[i].elements['id'].value + "|";
				}
			}
	}
	return seluser;
}

//선택회원 삭제
function delUser(){

	seluser = selectValue();

	if(seluser == ""){
		alert("삭제할 회원을 선택하세요.");
		return false;
	}else{
		if(confirm("선택한 회원을 정말 삭제하시겠습니까?")){
			document.location = "member_save.php?mode=deluser&delt=d&seluser=" + seluser + "&<?php echo $menucodeParam?>";
		}
	}
}

function allChange(){

	seluser = selectValue();

	if(seluser == ""){
		alert("휴면해제할 회원을 선택하세요.");
		return false;
	}else{
		if(confirm("선택한 회원을 휴면해제 하시겠습니까?")){
			document.location = "member_save.php?mode=allchange&seluser=" + seluser + "&<?php echo $menucodeParam?>";
		}
	}
}

// 고객 메일발송
function sendMail(seluser){

	if(seluser == ""){
		var i;
		var seluser = "";
		var seluser_info = "";
		for(i=0;i<document.forms.length;i++){
			if(document.forms[i].elements['id'] != null){
				if(document.forms[i].select_checkbox){
					if(document.forms[i].select_checkbox.checked)
						seluser = seluser + document.forms[i].email.value + "|";
				}
			}
		}
	}

  if(seluser == ""){
		alert("이메일 발송할 회원을 선택하세요.");
		return false;
	}else{
	   var url = "mail_popup.php?seluser=" + seluser + "&mode=DirectSend";
	   window.open(url,"sendMail","height=800, width=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	}

}

// 고객 SMS발송
function sendSms(seluser){

	if(seluser == ""){
		var i;
		var seluser = "";
		for(i=0;i<document.forms.length;i++){
		if(document.forms[i].elements['id'] != null){
				if(document.forms[i].select_checkbox){
					if(document.forms[i].select_checkbox.checked)
						seluser = seluser + document.forms[i].elements['id'].value + "|";
				}
			}
		}
	}
	if(seluser == ""){
		alert("SMS 발송할 회원을 선택하세요.");
		return false;
	}else{
		var url = "sms_popup_dormancy.php?seluser=" + seluser;
		window.open(url,"sendSms","height=700, width=350, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	}

}

// 회원정보 엑셀다운
function excelDown(){

	seluser = selectValue();
	if(seluser != ''){
		document.location = "member_dormant_excel.php?seluser=" + seluser + "&<?php echo $param?>";
	} else {
		document.location = "member_dormant_excel.php?<?php echo $param?>";
	}

}

// 선택회원 메일발송
function setMail(no) {
	var frm = eval("document.frm" + no);

	frm.select_checkbox.checked = true;
	sendMail('');

}

//-->
</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif" align="absmiddle"></td>
		<td valign="bottom" class="tit">휴면계정관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">휴면계정회원을 검색/수정 관리합니다.</td>
	</tr>
</table>

<br />
<div class="helpTip box">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="explain">
		- 회원 중 휴면회원으로 전환된 회원을 관리합니다.<br />
       - 휴면회원 : 정보통신망법에 따라 1년 이상 사이트를 이용하지 않은 회원의 정보는 자동으로 휴면회원으로 분리하여 보관해야 합니다.<br />
       - 휴면회원은 정보가 분리되어 보관되기 때문에 "회원리스트"에서 검색되지 않으며, "휴면회원 관리"에서 별도 확인 / 관리할 수 있습니다.<br />
       - 분리 보관중인 휴면회원에게 메일이나 SMS를 발송할 수 없으며, 휴면회원 정보는 관리자만 열람하도록 권한을 제한하는 것을 권장합니다.<br />
       - 휴면회원에게는 휴면회원 전환 30일 전 휴면회원 전환안내 자동메일이 발송되며, 휴면회원은 로그인 시 설정된 값에 따라 사이트를 다시 이용할 수 있습니다.<br />
	   - 휴면 전환 이후 <strong>1년이 경과하면 회원 정보는 자동 파기</strong>됩니다.<br />
	   - 휴면회원 정보 삭제 한달 전 휴면회원 정보 삭제 안내 메일이 발송됩니다.<br />
	   - 휴면회원 정보 자동 삭제 시 해당 아이디는 재사용이 불가능합니다. (동일 아이디로 가입 불가)
	  </div>
	  <hr />
	  <h4 style='margin-top:15px;'>휴면회원 검색</h4>
	  <div class="explain">
		- 사이트 회원 중 휴면회원으로 전환된 회원 검색이 가능합니다.<br />
		- 조건검색 : 회원 등급과 기본정보 조건(고객명/아이디/이메일/전화번호/휴대폰)으로 설정할 수 있습니다.<br />
		- 휴면회원 전환기간 : 휴면 회원으로 전환된 기간을 검색할 수 있습니다.<br />
		- 최종방문일 : 최종 방문일을 기준으로 일정 기간동안 접속하지 않은 회원을 검색할 수 있습니다.<br />
		- 최종방문기간 : 특정 기간 사이에 방문했던 회원을 검색할 수 있습니다.<br />
		- 휴면회원 일괄해제 : 체크박스로 선택한 회원의 휴면상태를 해제합니다.<br />
         &nbsp;&nbsp;1년 이상 서비스 미사용 회원정보를 휴면상태로 전환하는 개인정보 유효기간제는 장기간 사용되지 않는 개인정보를 보호하고자 하는 것이 목적이므로<br />&nbsp;&nbsp;휴면회원 상태는 <strong>사이트에서 본인확인 이후 회원 본인이 직접 해제하는 것을 권장</strong>합니다.<br />
		 - 휴면회원삭제 : 휴면회원 정보를 모두 삭제합니다. <strong>삭제시 복구는 불가능합니다.</strong>
	  </div>
	</div>
</div>
<br />

<form name="searchForm" action="<?php echo $PHP_SELF?>" method="get">
<input type="hidden" name="page" value="<?php echo $page?>">
<input type="hidden" name="menucode" value="<?php echo $menucode?>">
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">조건검색</td>
		<td width="35%" class="t_value">

			<table cellspacing="2" cellpadding="0">
				<tr>
					<td>
						<select name="slevel" class="select">
							<option value=""> :: 등급선택 ::</option>
							<?php echo level_list();?>
						</select>
					</td>
					<td>
						<select name="searchopt" class="select">
							<option value="name">고객명
							<option value="id">아이디
							<option value="email">이메일
							<option value="tphone">전화번호
							<option value="hphone">휴대폰
						</select>
					</td>
					<td><input type="text" name="searchkey" value="<?php echo $searchkey?>" class="input"></td>
				</tr>
			</table>
			<script language="javascript">
			<!--
			slevel = document.searchForm.slevel;
			for(ii=0; ii<slevel.length; ii++){
				if(slevel.options[ii].value == "<?php echo $slevel?>")
				slevel.options[ii].selected = true;
			}
			searchopt = document.searchForm.searchopt;
			for(ii=0; ii<searchopt.length; ii++){
				if(searchopt.options[ii].value == "<?php echo $searchopt?>")
				 searchopt.options[ii].selected = true;
			}
			-->
			</script>
		</td>
		<td width="15%" class="t_name">휴면회원 전환기간</td>
		<td width="35%" class="t_value">
			<input type="text" name="sdate" id="sdate" class="input" size="15" value="<?php echo $sdate ?>"> ~
			<input type="text" name="edate" id="edate" class="input" size="15" value="<?php echo $edate ?>">
		</td>
		<tr>
			<td width="15%" class="t_name">최종방문일</td>
			<td width="35%" class="t_value">
				<input type="text" name="last_date" class="input Onum" size="8" value="<?php echo $last_date ?>"> 일 이상 로그인하지 않은 회원
			</td>
			<td width="15%" class="t_name">최종방문기간</td>
			<td width="35%" class="t_value">
				<input type="text" name="lsdate" id="lsdate" class="input" size="15" value="<?php echo $lsdate ?>"> ~
				<input type="text" name="ledate" id="ledate" class="input" size="15" value="<?php echo $ledate ?>">
			</td>
		</tr>

	</tr>
</table>
<br>
<table width="100%" cellspacing="1" cellpadding="3" border="0">
	<tr>
		<td align="center">
			<input type="submit" value="검색" class="search_btn2">&nbsp;
			<input type="button" value="전체회원" class="search_default" onclick="location.href='<?php echo $PHP_SELF?>?<?php echo $menucodeParam?>'">
		</td>
	</tr>
</table>
</form>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="10"></td></tr>
</table>
<?php
$today = date('n-d');
$toyear = date('Y');

$age_syear = substr($toyear-($s_age+9),-2)+1;
$age_eyear = substr($toyear-$s_age,-2)+2;

$join_sdate = $prev_year."-".$prev_month."-".$prev_day;
$join_edate = $next_year."-".$next_month."-".$next_day;

$sql = "
	select count(id) as all_total 
	  from {$this_tbl}
";
$result = query($sql);
$row = sql_fetch_arr($result);
$all_total = $row['all_total'];

$where = array();
$where[] = " where id != '' ";
if($slevel    != "")                     $where[] = " level = '$slevel'";
if($prev_year != "")                     $where[] = " wdate > '$join_sdate' and wdate <= '$join_edate 23:59:59'";
if($searchopt != "" && trim($searchkey)) $where[] = " $searchopt like '%$searchkey%'";
if($birthday == "Y")                     $where[] = " birthday like '%$today'";
if($memorial == "Y")                     $where[] = " memorial like '%$today'";
if($age       != "")                     $where[] = " resno > '$age_syear' and resno < '$age_eyear'";
if($address   != "")                     $where[] = " address like '%$s_address%'";
if($job       != "")                     $where[] = " job = '$s_job'";
if($marriage  != "")                     $where[] = " marriage = '$s_marriage'";
if($sdate && $edate)                     $where[] = " dchange_date BETWEEN '$sdate 00:00:00' AND '$edate 23:59:59' ";
if($lsdate && $ledate)                   $where[] = " visit_time BETWEEN '$lsdate 00:00:00' AND '$ledate 23:59:59' ";
if(trim($last_date))                     $where[] = " visit_time <= date_sub(now(), interval ".$last_date." day) ";

$sql_search   = ($where) ? implode(" AND ", $where) : "";

$sql = "
	select idx
		 , id
		 , passwd
		 , name
		 , hphone
		 , email
		 , level
		 , visit
		 , wdate 
	  from {$this_tbl}
	  $sql_search
";
$result = query($sql);
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
		<td><span class="title_msg">총 회원수 : <strong id="total_prd_cnt"><?php echo $all_total?></strong> , 검색 회원수 : <strong id="total_prd_cnt"><?php echo $total?></strong></span>
		</td>
		<td align="right">
			<input type="button" value="엑셀파일저장" class="btnExcel" onClick="excelDown();">
		</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<form>
	<tr><td colspan=20 class="t_rd"></td></tr>
	<tr class="t_th">
		<th width="5%"><input type="checkbox" name="select_tmp" onClick="selectReverse(this.form)"></th>
		<th width="5%">번호</th>
		<th>이름</th>
		<th width="15%">아이디</th>
		<th width="20%">문자 / 이메일</th>
		<th width="5%">방문수</th>
		<th width="10%">가입일</th>
		<th width="10%">최종로그인</th>
		<th width="10%">휴면전환일</th>
		<th width="10%">관리</th>
	</tr>
	<tr><td colspan=20 class="t_rd"></td></tr>
	</form>
	<?php
	$sql = "
		select idx
			 , id
			 , passwd
			 , name
			 , hphone
			 , email
			 , resms
			 , reemail
			 , level
			 , visit
			 , wdate
			 , visit_time
			 , dchange_date
			 , pw_update 
		  from {$this_tbl}
		  $sql_search
		 order by idx desc
		 limit $start, $rows
	";
	$result = query($sql);
	while($row = sql_fetch_arr($result)){

		if($site_info['sms_use'] == 'Y' && $row['hphone'] && $row['resms'] == 'Y') {
			$hphone = "<input type='button' class='base_btn_s gray2' value='SMS' title='SMS를 개별발송합니다.' onclick=sendSms('".$row['id']."');>";
		} else {
			$hphone = "<input type='button' class='base_btn_s gray2' value='SMS' onclick='javascript:alert(\"수신 미허용으로 개별발송이 불가능합니다.\")';>";
		}

		if($row['email'] && $row['reemail'] == 'Y') {
			$email = "<input type='button' class='base_btn_s gray2' value='메일' title='이메일을 개별발송합니다.' onclick=sendMail('".$row['email']."');>";
		} else {
			$email = "<input type='button' class='base_btn_s gray2' value='메일' onclick='javascript:alert(\"수신 미허용으로 개별발송이 불가능합니다.\")';>";
		}

		
		
		if(strpos($row['visit_time'], '0000-00-00') !== false) {
			$visit_time = '-';
		} else {
			$visit_time = substr($row['visit_time'],0,10);
		}


	?>
	<form name="frm<?php echo $no?>">
	<input type="hidden" name="id" value="<?php echo $row['id']?>">
	<input type="hidden" name="name" value="<?php echo $row['name']?>">
	<input type="hidden" name="email" value="<?php echo $row['email']?>">
	<input type="hidden" name="hphone" value="<?php echo $row['hphone']?>">
	<input type="hidden" name="passwd" value="<?php echo $row['passwd']?>">
	<tr>
		<td height="38" align="center"><input type="checkbox" name="select_checkbox"></td>
		<td align="center"><?php echo $no ?></td>
		<td align="center"><?php echo $row['name'] ?></td>
		<td align="center"><?php echo $row['id'] ?></td>
		<td align="center"><?php echo $hphone?> <?php echo $email ?></td>
		<td align="center"><?php echo $row['visit'] ?></td>
		<td align="center"><?php echo substr($row['wdate'],0,10) ?></td>
		<td align="center"><?php echo $visit_time ?></td>
		<td align="center"><strong><font color="#A90329"><?php echo substr($row['dchange_date'],0,10) ?></font></strong></td>
		<td align="center"><a href="member_dormant_account_input.php?mode=update&idx=<?php echo $row['idx']?>&page=<?php echo $page?>&<?php echo $param?>"><img src="../image/btn_view_s.gif" border="0"></a></td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	</form>
<?php
	$no--;
}

if($total <= 0)	{
?>
	<tr><td height="30" colspan="10" align="center">등록된 회원이 없습니다.</td></tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?php
}
?>
</table>

<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="5"></td></tr>
	<tr>
		<td width="30%">
			<input type="button" value="휴면회원 일괄해제" class="btnListchk2" onclick="allChange('')">
			<input type="button" value="휴면회원삭제" class="btnListchk4" onclick="delUser('')">
			<!-- <?php if($site_info['sms_use'] == "Y"){ ?>
			<input type="button" value="SMS발송" class="btnListchk" onclick="sendSms('')">
			<?php } ?> -->
		</td>
		<td width="20%"><?php print_pagelist($page, $lists, $page_count, $param); ?></td>
		<td width="30%"></td>
	</tr>
</table>

<?php include "../foot.php"; ?>