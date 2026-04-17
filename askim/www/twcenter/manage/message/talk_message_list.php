<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include "../head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/lib/datepicker_lib.php";

include $_SERVER['DOCUMENT_ROOT']."/twcenter/dbclass.php";
$DB = new DB($AlimTalkDBConf);



$param = "company_name_chk=".$company_name_chk."&send_chk=".$send_chk."&phoneNum_chk=".$phoneNum_chk."&sdate=".$sdate."&edate=".$edate;
?>
<style>

.hide{
		display:none
}
.show{
		display:
}

</style>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif" align="absmiddle"></td>
		<td valign="bottom" class="tit">알림톡 발송내역</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">알림톡 발송 내역 조회</td>
	</tr>
</table>

<br>

<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<form name="searchForm" action="<?=$PHP_SELF?>" method="get">
	<input type="hidden" name="page" value="<?=$page?>">
		<tr>
			<td width="15%" class="t_name">발송결과</td>
			<td width="85%" class="t_value">
				<input type="radio" name="send_chk" value=""  style="vertical-align:-3px" <?if($send_chk=="") echo "checked"?>>전체
				<input type="radio" name="send_chk" value="Y" style="vertical-align:-3px" <?if($send_chk=="Y") echo "checked"?>>성공
				<input type="radio" name="send_chk" value="N" style="vertical-align:-3px" <?if($send_chk=="N") echo "checked"?>>실패
			</td>
		</tr>
		<!--
		<tr>
			<td width="15%" class="t_name">업체명</td>
			<td width="85%" class="t_value">
				<input type="text" name="company_name_chk" value="<?=$company_name_chk?>" class="input">
			</td>
		</tr>
		-->
		<tr>
			<td width="15%" class="t_name">발송번호</td>
			<td width="85%" class="t_value">
				<input type="text" name="phoneNum_chk" value="<?=$phoneNum_chk?>" class="input">
			</td>
		</tr>
		<tr>
			<td width="15%" class="t_name">기간검색</td>
			<td width="85%" class="t_value">
				<input type="text" name="sdate" id="sdate" value="<?=$sdate?>" class="input">
				~
				<input type="text" name="edate" id="edate" value="<?=$edate?>" class="input">
			</td>
		</tr>
	
</table>

<br>

<div align="center">
	<input type="submit" value="검색" class="search_btn2">&nbsp;
	<input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>?<?=$menucodeParam?>'">
	<!-- <input type="image" src="../image/btn_search.gif">
	<a href="/twcenter/manage/member/talk_message_list.php">
		<img src="../image/btn_clean.gif">
	</a> -->
</div>

<p align="right"><font color=blue>※ 발송메세지 클릭 시 상세보기가 가능합니다.</font></p>
</form>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td height="10"></td></tr>
</table>

<?
//print_r($DB);
if($DB->getDbHost()) {

function fun_code($code){
	if($code=="AS"){
		return "발송성공";
	}else if($code=="AF"){
		return "발송실패";
	}else if($code=="SS"){
		return "문자발송 성공";
	}else if($code=="SF"){
		return "문자발송 실패";
	}else if($code=="EW"){
		return "문자발송중";
	}else if($code=="EF"){
		return "시스템 실패 처리";
	}else if($code=="EE"){
		return "시스템 오류";
	}else if($code=="EO"){
		return "시스템 타임아웃";
	}
}

function fun_altCode($altCode){
	if($altCode=="0000"){ 
		return "-";
	}else if($altCode=="1001"){ 
		return "발송 데이터 형식이 잘못됨";
	}else if($altCode=="1002"){ 
		return "허브 파트너키가 유효하지 않음";
	}else if($altCode=="1003"){ 
		return "발신 프로필 키가 유효하지 않음";
	}else if($altCode=="1004"){ 
		return "Request Body(Json)에서 name을 찾을 수 없음";
	}else if($altCode=="1005"){ 
		return "발신프로필을 찾을 수 없음";
	}else if($altCode=="1006"){ 
		return "삭제된 발신프로필. (메시지 사업 담당자에게 문의)";
	}else if($altCode=="1007"){ 
		return "차단 상태의 발신프로필. (메시지 사업 담당자에게 문의)";
	}else if($altCode=="1011"){ 
		return "계약정보를 찾을 수 없음. (메시지 사업 담당자에게 문의)";
	}else if($altCode=="1012"){ 
		return "잘못된 형식의 유저키 요청";
	}else if($altCode=="1021"){ 
		return "차단 상태의 플러스친구 (플러스친구 운영툴에서 확인)";
	}else if($altCode=="1022"){ 
		return "닫힘 상태의 플러스친구 (플러스친구 운영툴에서 확인)";
	}else if($altCode=="1023"){ 
		return "삭제된 플러스친구 (플러스친구 운영툴에서 확인)";
	}else if($altCode=="1024"){ 
		return "삭제대기 상태의 플러스친구 (플러스친구 운영툴에서 확인)";
	}else if($altCode=="1025"){ 
		return "메시지차단 상태의 플러스친구 (플러스친구 운영툴에서 확인)";
	}else if($altCode=="1030"){ 
		return "잘못된 파라메터 요청";
	}else if($altCode=="2003"){ 
		return "메시지 전송 실패. (테스트 시,친구관계가 아닌 경우)";
	}else if($altCode=="2004"){ 
		return "메시지와 템플릿의 일치성 확인시 오류 발생. (내부 오류 발생)";
	}else if($altCode=="3000"){ 
		return "예기치 않은 오류 발생";
	}else if($altCode=="3005"){ 
		return "메시지를 발송했으나 수신확인 안됨 (성공불확실)";
	}else if($altCode=="3006"){ 
		return "내부 시스템 오류로 메시지 전송 실패";
	}else if($altCode=="3008"){ 
		return "전화번호 오류";
	}else if($altCode=="3010"){ 
		return "Json 파싱 오류";
	}else if($altCode=="3011"){ 
		return "메시지가 존재하지 않음";
	}else if($altCode=="3012"){ 
		return "메시지 일련번호가 중복됨";
	}else if($altCode=="3013"){ 
		return "메시지가 비어 있음";
	}else if($altCode=="3014"){ 
		return "메시지 길이 제한 오류. (템플릿별 제한 길이 또는 1000자 초과)";
	}else if($altCode=="3015"){ 
		return "템플릿을 찾을 수 없음";
	}else if($altCode=="3016"){ 
		return "메시지 내용이 템플릿과 일치하지 않음";
	}else if($altCode=="3018"){ 
		return "메시지를 전송할 수 없음 (카카오톡을 사용하지 않음, 최근 3일 동안 카카오톡을 사용 안함, 알림톡 차단을 선택한 사용자)";
	}else if($altCode=="3019"){ 
		return "메시지가 발송되지 않은 상태";
	}else if($altCode=="3020"){ 
		return "메시지 확인 정보를 찾을 수 없음";
	}else if($altCode=="3022"){ 
		return "메시지 발송 가능한 시간이 아님";
	}else if($altCode=="3024"){ 
		return "메시지에 포함된 이미지를 전송할 수 없음";
	}else if($altCode=="4000"){ 
		return "메시지 전송 결과를 찾을 수 없음";
	}else if($altCode=="4001"){ 
		return "알 수 없는 메시지 상태";
	}else if($altCode=="9998"){ 
		return "현재 서비스를 제공하고 있지 않습니다. 시스템에 문제가 발생하여 담당자가 확인하고 있는 경우";
	}else if($altCode=="888"){ 
		return "시스템 오류";
	}else if($altCode=="999"){ 
		return "시스템 Timeout";
	}
}

$sql_t = "select count(idx) as cnt from wiz_talk_message ";
$sql_t .= " where idx != '' and company_id!= '' and company_id='$_alimtalk_id' ";

if($company_name_chk != ""){
	$sql_t .= " and company_name like '%$company_name_chk%'";
}

if($phoneNum_chk != ""){
	$sql_t .= " and phoneNum like '%$phoneNum_chk%'";
}

if($send_chk != ""){
	if($send_chk=="Y"){
		$sql_t .= " and code = 'AS'";
	}else{
		$sql_t .= " and code != 'AS'";
	}
}

if($sdate=="" && $edate==""){
	$sql_t .= "";
}else if($sdate!="" && $edate==""){
	$sdate_conv = str_replace("-","",$sdate)."000000";
	$edate_conv = str_replace("-","",$edate)."235959";
	$sql_t .= " and sndDtm > '$sdate_conv'";
}else if($sdate=="" && $edate!=""){
	$sdate_conv = str_replace("-","",$sdate)."000000";
	$edate_conv = str_replace("-","",$edate)."235959";
	$sql_t .= " and sndDtm < '$edate_conv'";
}else if($sdate!="" && $edate!=""){
	$sdate_conv = str_replace("-","",$sdate)."000000";
	$edate_conv = str_replace("-","",$edate)."235959";
	$sql_t .= " and sndDtm > '$sdate_conv' and sndDtm < '$edate_conv' ";
}else{
	$sdate_conv = str_replace("-","",$sdate)."000000";
	$edate_conv = str_replace("-","",$edate)."235959";
	$sql_t .= "";
}

$sql_t .=" order by idx desc";
$result_t = $DB->query($sql_t);
$row_t = $DB->fetch_array($result_t);
$total = $row_t['cnt'];



$rows = 20;
$lists = 5;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;

?>
<script>
function hideview(frm,self){
	if($(self).next().attr("class")=="show"){
		$(self).next().attr("class","hide");
		$(self).attr("class","show");
	}else{
		$(self).next().attr("class","show");
		$(self).attr("class","hide");
	}
}

function hideview2(frm,self){
	if($(self).prev().attr("class")=="show"){
		$(self).prev().attr("class","hide");
		$(self).attr("class","show");
	}else{
		$(self).prev().attr("class","show");
		$(self).attr("class","hide");
	}
}

</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td colspan=20 class="t_rd"></td></tr>
		<tr class="t_th">
		<th width="5%">번호</th>
		<th width="10%">업체아이디</th>
		<!--
		<th width="10%">업체명</th>
		-->
		<!--
		<th width="10%">템플릿코드</th>
		-->
		<th width="10%">발송번호</th>
		<th width="10%">발송시간</th>
		<th width="25%">발송메세지</th>
		<th width="10%">발송결과</th>
		<th width="10%">발송실패상세결과</th>
	</tr>
	<tr>
		<td colspan=20 class="t_rd"></td>
	</tr>
	<?
	$sql = "select * from wiz_talk_message";
	$sql .= " where idx != '' and company_id!= '' and company_id='$_alimtalk_id' ";
	if($company_name_chk != ""){
		$sql .= " and company_name like '%$company_name_chk%'";
	}

	if($phoneNum_chk != ""){
		$sql .= " and phoneNum like '%$phoneNum_chk%'";
	}

	if($send_chk != ""){
		if($send_chk=="Y"){
			$sql .= " and code = 'AS'";
		}else{
			$sql .= " and code != 'AS'";
		}
	}


	if($sdate=="" && $edate==""){
		$sql .= "";
	}else if($sdate!="" && $edate==""){
		$sdate_conv = str_replace("-","",$sdate)."000000";
		$edate_conv = str_replace("-","",$edate)."235959";
		$sql .= " and sndDtm > '$sdate_conv'";
	}else if($sdate=="" && $edate!=""){
		$sdate_conv = str_replace("-","",$sdate)."000000";
		$edate_conv = str_replace("-","",$edate)."235959";
		$sql .= " and sndDtm < '$edate_conv'";
	}else if($sdate!="" && $edate!=""){
		$sdate_conv = str_replace("-","",$sdate)."000000";
		$edate_conv = str_replace("-","",$edate)."235959";
		$sql .= " and sndDtm > '$sdate_conv' and sndDtm < '$edate_conv' ";
	}else{
		$sdate_conv = str_replace("-","",$sdate)."000000";
		$edate_conv = str_replace("-","",$edate)."235959";
		$sql .= "";
	}

	$sql .=" order by idx desc limit $start, $rows";

	//echo $sql;
	$result = $DB->query($sql);

	while($row = $DB->fetch_array($result)) {

		$company_id = $row['company_id'];
		$company_name = $row['company_name'];
		$templateCode = $row['templateCode'];
		$phoneNum = $row['phoneNum'];
		$message = mb_substr($row['message'],0,50,'UTF-8');
		$message_length = mb_strlen($row['message'],'UTF-8');
		if($message_length>50){
			$message .= "...";
		}
		$code = $row['code'];
		$altCode = $row['altCode'];

		$fun_code = fun_code($code);
		$fun_altCode = fun_altCode($altCode);

		$sndDtm = $row['sndDtm'];
		$send_time = substr($row['sndDtm'],0,4)."-".substr($row['sndDtm'],4,2)."-".substr($row['sndDtm'],6,2)." ".substr($row['sndDtm'],8,2).":".substr($row['sndDtm'],10,2).":".substr($row['sndDtm'],12,2);

	?>
	<form>
	<tr>
		<td height="30" align="center"><?=$no?></td>
		<td align="center"><?=$company_id?></td>
		<!--
		<td align="center"><?=$company_name?></td>
		-->
		<!--
		<td align="center"><?=$templateCode?></td>
		-->
		<td align="center"><?=$phoneNum?></td>
		<td align="center"><?=$send_time?></td>
		<td align="left" onclick="hideview(this.form,this)" style="cursor:pointer;"><?=$message?></td>
		<td align="left" onclick="hideview2(this.form,this)" style="cursor:pointer;" class="hide"><?=nl2br($row['message'])?></td>
		<td align="center"><?=$fun_code?></td>
		<td align="center"><?=$fun_altCode?></td>
	</tr>
	</form>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?
	$no--;
	}

	if($total <= 0){
	?>
	<tr><td height="30" colspan="10" align="center">발송내역이 없습니다.</td></tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
</table>

<br>

<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="5"></td></tr>
	<tr>
		<td width="30%">
		</td>
		<td width="20%"><? print_pagelist($page, $lists, $page_count, $param); ?></td>
		<td width="30%"></td>
	</tr>
</table>

<?php
include "../foot.php";
$DB->close();

}
?>
