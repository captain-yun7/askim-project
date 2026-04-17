<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/manage/head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/lib/datepicker_lib.php";

$param = array();
if(isset($slevel)     && $slevel)      $param[] = "slevel=".urlencode($slevel);
if(isset($searchopt)  && $searchopt)   $param[] = "searchopt=".urlencode($searchopt);
if(isset($searchkey)  && $searchkey)   $param[] = "searchkey=".urlencode($searchkey);
if(isset($sdate)      && $sdate)       $param[] = "sdate=".urlencode($sdate);
if(isset($edate)      && $edate)       $param[] = "edate=".urlencode($edate);
if(isset($last_date)  && $last_date)   $param[] = "last_date=".urlencode($last_date);
if(isset($lsdate)     && $lsdate)      $param[] = "lsdate=".urlencode($lsdate);
if(isset($ledate)     && $ledate)      $param[] = "ledate=".urlencode($ledate);
if($s_sns) {
	for($i=0; $i<sizeof($s_sns); $i++) {
		$param[] = "s_sns[]=".$s_sns[$i];
	}
} else {
	$s_sns = array();
}
$param   = ($param) ? implode("&", $param) : "";

$today = date('n-d');
$toyear = date('Y');

$age_syear = substr($toyear-($s_age+9),-2)+1;
$age_eyear = substr($toyear-$s_age,-2)+2;

$join_sdate = $prev_year."-".$prev_month."-".$prev_day;
$join_edate = $next_year."-".$next_month."-".$next_day;

$sql = "
	select count(id) as all_total 
	  from wiz_member 
	 where dchange_type = 'N'
";
$result = query($sql);
$row = sql_fetch_arr($result);
$all_total = $row['all_total'];

$where = array();
$where[] = " where id != '' and dchange_type = 'N' ";
if($slevel    != "")                     $where[] = " level = '$slevel'";
if($prev_year != "")                     $where[] = " wdate > '$join_sdate' and wdate <= '$join_edate 23:59:59'";
if($searchopt != "" && trim($searchkey)) $where[] = " $searchopt like '%$searchkey%'";
if($birthday == "Y")                     $where[] = " birthday like '%$today'";
if($memorial == "Y")                     $where[] = " memorial like '%$today'";
if($age       != "")                     $where[] = " resno > '$age_syear' and resno < '$age_eyear'";
if($address   != "")                     $where[] = " address like '%$s_address%'";
if($job       != "")                     $where[] = " job = '$s_job'";
if($marriage  != "")                     $where[] = " marriage = '$s_marriage'";
if($sdate && $edate)                     $where[] = " wdate BETWEEN '$sdate 00:00:00' AND '$edate 23:59:59' ";
if($lsdate && $ledate)                   $where[] = " visit_time BETWEEN '$lsdate 00:00:00' AND '$ledate 23:59:59' ";

if(!isset($last_date)) $last_date = '';
if(trim($last_date))                     $where[] = " visit_time <= date_sub(now(), interval ".$last_date." day) ";

if($s_sns) {
	for($i=0; $i<sizeof($s_sns); $i++) {
		if($s_sns[$i]) {
			$sns_sql .= ($sns_sql) ? " or " : " (";
			$sns_sql .= "sns_login='".$s_sns[$i]."'";
		}
	}
	if($sns_sql) $sns_sql .= ")";
	$where[] = $sns_sql;
}

$sql_search   = ($where) ? implode(" and ", $where) : "";

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
	  from wiz_member
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
			document.location = "member_save.php?mode=deluser&seluser=" + seluser + "&<?php echo $menucodeParam?>";
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
	   var url = "/twcenter/manage/message/mail_popup.php?seluser=" + seluser + "&mode=DirectSend";
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
		var url = "/twcenter/manage/message/sms_popup.php?seluser=" + seluser;
		window.open(url,"sendSms","height=700, width=350, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	}

}

// 회원정보 엑셀다운
function excelDown(){

	seluser = selectValue();
	if(seluser != ''){
		document.location = "member_excel.php?seluser=" + seluser + "&<?php echo $param?>";
	} else {
		document.location = "member_excel.php?<?php echo $param?>";
	}

}
// 회원정보 엑셀다운
function excelDown2(){

	seluser = selectValue();
	if(seluser != ''){
		document.location = "member_csv.php?seluser=" + seluser + "&<?php echo $param?>";
	} else {
		document.location = "member_csv.php?<?php echo $param?>";
	}

}

// 선택회원 메일발송
function setMail(no) {
	var frm = eval("document.frm" + no);

	frm.select_checkbox.checked = true;
	sendMail('');

}

$(document).on("click", ".btn-unlock", function(){
	var cnt = 0;
	var uid = new Array();
	uid[cnt] = $(this).attr("data-id");
	jQuery.ajax({
		url: "./member_save.php",
		type : "POST",
		data: {
			"mode" : "unlock",
			"uid" : uid
		},
		error: function(xhr,textStatus,errorThrown){
		},
		
		beforeSend: function() {
		},
		success: function(data){
			alert(data);
		},
		complete: function(){
			location.reload();
		}
	});
});
//-->
</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif" align="absmiddle"></td>
		<td valign="bottom" class="tit">회원관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">회원을 검색/수정 관리합니다.</td>
	</tr>
</table>

<br>

<form name="searchForm" action="<?php echo $PHP_SELF?>" method="get">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="menucode" value="<?php echo $menucode ?>">
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">조건검색</td>
		<td width="15%" class="t_value">

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
		<td width="15%" class="t_name">회원가입기간</td>
		<td width="35%" class="t_value">
			<input type="text" name="sdate" id="sdate" class="input" size="15" value="<?php echo $sdate ?>"> ~
			<input type="text" name="edate" id="edate" class="input" size="15" value="<?php echo $edate ?>">
		</td>
	</tr>
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
<? if($oper_info['sns_login_use'] == "Y" && $oper_info['sns_login_method']) { 
		$sns_login_method = explode("/", $oper_info['sns_login_method']);
	?>
	<tr>
		<td class="t_name">SNS가입회원</td>
		<td class="t_value" colspan="3">
		<?
			foreach($sns_login_method as $k=>$sns_type) { 
				if(empty($sns_type)) continue;
				if($sns_type == "KT") $sns_type = "KK";
				switch($sns_type) {
					case "KK" :
						$sns_name = "카카오톡";
						break;
					case "NH" :
						$sns_name = "네이버";
						break;
					case "GG" :
						$sns_name = "구글";
						break;
					case "FB" :
						$sns_name = "페이스북";
						break;
					default :
						$sns_name = "";
						break;
				}
				if(empty($sns_name)) continue;
				$sns_checked = (in_array($sns_type, $s_sns)) ? " checked" : "";
		?>
			<label for="sns<?=$sns_type?>" style="margin-right:15px;"><input type="checkbox" name="s_sns[]" id="sns<?=$sns_type?>" value="<?=$sns_type?>"<?=$sns_checked?>><?=$sns_name?></label>
		<? } ?>
		</td>
	</tr>
<? } ?>

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
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td><span class="title_msg">총 회원수 : <strong id="total_prd_cnt"><?php echo $all_total?></strong> , 검색 회원수 : <strong id="total_prd_cnt"><?php echo $total?></strong></span>
		</td>
		<td align="right">
			<input type="button" value="CSV파일저장" class="btnExcel" onClick="excelDown2();">
			<input type="button" value="엑셀파일저장" class="btnExcel" onClick="excelDown();">
			<input type="button" value="회원등록" class="btnListchk3" onClick="document.location='member_input.php?mode=insert&<?php echo $menucodeParam?>';">
		</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<form>
	<tr><td colspan=20 class="t_rd"></td></tr>
	<tr class="t_th">
	<?
	/*
	작업자명	: 임서연
	작업일시	: 2020-04-01
	작업내용	: sms사용안함 선택시에 문자칸 미노출 처리
	*/
	?>
	<?php if($menu_arr["PRODUCT"] == true) { ?>
		<th width="5%"><input type="checkbox" name="select_tmp" onClick="selectReverse(this.form)"></th>
		<th width="5%">번호</th>
		<th width="12%">아이디</th>
		<th>이름</th>
		<th width="10%">등급</th>
		<?if($site_info['sms_use'] == 'Y'){?><th width="8%">문자</th><?}?>
		<th width="8%">구매건수</th>
		<th width="8%">구매금액</th>
		<?php if($oper_info['reserve_use'] == "Y") { ?>
		<th width="8%">적립금</th>
		<?php } ?>
		<th width="5%">방문수</th>
		<?php if($site_info['login_limit_use'] == "Y"){ ?>
		<th width="5%">로그인실패</th>
		<?php } ?>
		<th width="8%">가입일</th>
		<th width="8%">최종로그인</th>
		<th width="8%">관리</th>
	<?php } else { ?>
		<th width="5%"><input type="checkbox" name="select_tmp" onClick="selectReverse(this.form)"></th>
		<th width="5%">번호</th>
		<th width="15%">아이디</th>
		<th>이름</th>
		<th width="10%">등급</th>
		<?if($site_info['sms_use'] == 'Y'){?><th width="8%">문자</th><?}?>
		<th width="5%">방문수</th>
		<?php if($site_info['login_limit_use'] == "Y"){ ?>
		<th width="5%">로그인실패</th>
		<?php } ?>
		<th width="10%">가입일</th>
		<th width="10%">최종로그인</th>
		<th width="8%">관리</th>
	<?php } ?>
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
			 , pw_update 
			 , login_fail_count
		  from wiz_member
		  $sql_search
		 order by idx desc
		 limit $start, $rows
	";
	$result = query($sql);
	while($row = sql_fetch_arr($result)){
		if($site_info['sms_use'] == 'Y' && $row['hphone'] && $row['resms'] == 'Y') {
			$hphone = "<input type='button' class='base_btn_s2 gray2' value='SMS' title='SMS를 개별발송합니다.' onclick=sendSms('".$row['id']."');>";
		} else {
			$hphone = "<input type='button' class='base_btn_s2 gray2' value='SMS' onclick='javascript:alert(\"수신 미허용으로 개별발송이 불가능합니다.\")';>";
		}
		/*
		작업자명	: 이상민
		작업일시	: 2020-03-18
		작업내용	: 내부프로젝트게시판 3/4요청에 의거 회원목록에서 이메일 발송기능 제거

		if($row['email'] && $row['reemail'] == 'Y') {
			$email = "<input type='button' class='base_btn_s2 gray2' value='메일' title='이메일을 개별발송합니다.' onclick=sendMail('".$row['email']."');>";
		} else {
			$email = "<input type='button' class='base_btn_s2 gray2' value='메일' onclick='javascript:alert(\"수신 미허용으로 개별발송이 불가능합니다.\")';>";
		}
		*/
		
		if(strpos($row['visit_time'], '0000-00-00') !== false) {
			$visit_time = '-';
		} else {
			$visit_time = substr($row['visit_time'],0,10);
		}

		//$default_day = "365";
		//$day         = "30";
		//$last_visit_time  = date("Y-m-d", time() - (($default_day + $day) * 86400));

		$c_visit_time = strtotime($visit_time);
		$c_this_time  = time();
		$one_year_later_notice = date("Y-m-d",strtotime("+11 month", $c_visit_time));

		if(strtotime($one_year_later_notice) < $c_this_time) {
			$one_year_later = "<font color='red'>".$visit_time."</font>";
		} else {
			$one_year_later = $visit_time;
		}
		/*
		작업자		: 이상민
		작업일시	: 2020-03-10
		작업내용	: 구매건수 카운트에는 결제완료, 배송준비중, 배송처리, 배송완료건만 포함되도록
		*/
		$o_sql = "
			select count(orderid) as ord_cnt
				 , (select sum(total_price) 
					  from wiz_order 
					 where send_id = wo.send_id 
					   and (status = 'OY' or status = 'DR' or status = 'DI' or status = 'DC')
					) as total_price
			  from wiz_order wo
			 where 
				send_id = '".$row['id']."' 
				and (status = 'OY' or status = 'DR' or status = 'DI' or status = 'DC')
		";
		$o_res = query($o_sql);
		$o_row = sql_fetch_arr($o_res);

		$ord_cnt     = number_format($o_row['ord_cnt']);
		
		if(!isset($o_row['total_price'])) $o_row['total_price'] = 0.00;
		$total_price = number_format($o_row['total_price']);

		if($oper_info['reserve_use'] == "Y") {
			$r_sql = "select sum(reserve) as reserve from wiz_reserve where memid = '".$row['id']."'";
			$r_res = query($r_sql);
			$r_row = sql_fetch_arr($r_res);
			
			if(!isset($r_row['reserve'])) $r_row['reserve'] = 0.00;
			$reserve = number_format($r_row['reserve']);
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
		<td align="center"><?php echo $row['id'] ?></td>
		<td align="center"><?php echo $row['name'] ?></td>
		<td align="center"><?php echo get_level($row['level']) ?></td>
		<?php
		/*
		작업자명	: 이상민
		작업일시	: 2020-03-18
		작업내용	: 내부프로젝트게시판 3/4요청에 의거 회원목록에서 이메일 발송기능 제거
		
		작업자명	: 임서연
		작업일시	: 2020-04-01
		작업내용	: sms사용안함 선택시에 버튼 미노출 처리
		*/
		?>
		<?if($site_info['sms_use'] == 'Y'){?><td align="center"><?php echo $hphone?> <?php //echo $email?></td><?}?>
		<?php if($menu_arr["PRODUCT"] == true) { ?>
		<td align="center"><?php echo $ord_cnt ?> 건</td>
		<td align="center"><?php echo $total_price ?> 원</td>
		<?php if($oper_info['reserve_use'] == "Y") { ?><td align="center"><?php echo $reserve ?> 원</td><?php } ?>
		<?php } ?>
		<td align="center"><?php echo $row['visit'] ?></td>
		<?php if($site_info['login_limit_use'] == "Y"){ ?>
		<td align="center"><?php echo $row["login_fail_count"]; ?></td>
		<?php } ?>
		<td align="center"><?php echo substr($row['wdate'],0,10) ?></td>
		<td align="center"><?php echo $one_year_later ?></td>
		<td align="center">
			<input type="button" value="보기" class="base_btm blue2" onclick="location.href='member_input.php?mode=update&idx=<?=$row['idx']?>&page=<?=$page?>&<?=$param?>';"> 
			<?php if($site_info['login_limit_use'] == "Y" && ($row['login_fail_count'] >= $site_info['login_limit_count'])){ ?>
			<input type="button" value="차단해제" class="base_btm reg btn-unlock" data-id="<?php echo $row['id']; ?>">
			<?php } ?>
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	</form>
<?php
	$no--;
}

if($total <= 0)	{
?>
	<tr><td height="30" colspan="12" align="center">등록된 회원이 없습니다.</td></tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?php
}
?>
</table>

<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="5"></td></tr>
	<tr>
		<td width="30%">
			<input type="button" value="회원삭제" class="btnListchk4" onclick="delUser('')">
			<?php if($site_info['sms_use'] == "Y"){ ?>
			<input type="button" value="SMS발송" class="btnListchk" onclick="sendSms('')">
			<?php } ?>
		</td>
		<td width="20%"><?php print_pagelist($page, $lists, $page_count, $param); ?></td>
		<td width="30%"></td>
	</tr>
</table>

<?php include "../foot.php"; ?>