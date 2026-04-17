<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include "../head.php"; ?>

<?
if (!$year) {
	$year	= date("Y");
	$month	= date("m");
}

$prev		= date("Y-m",mktime(0,0,0,$month-1,1,$year));
$prevArray	= explode("-",$prev);
$prevYear	= $prevArray[0];
$prevMonth	= $prevArray[1];

$next		= date("Y-m",mktime(0,0,0,$month+1,1,$year));
$nextArray	= explode("-",$next);
$nextYear	= $nextArray[0];
$nextMonth	= $nextArray[1];

$nnext		= date("Y-m",mktime(0,0,0,$month+2,1,$year));
$nnextArray	= explode("-",$nnext);
$nnextYear	= $nnextArray[0];
$nnextMonth	= $nnextArray[1];

$ppre_year  = date("Y")-2;
$nnex_year  = date("Y")+2;

?>
<script>
function holiday() {
	window.open("holiday_list.php?code=<?=$code?>", "holiday", "width=500,height=600");
}
</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">일정관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">일정을 추가/삭제/수정 합니다.</td>
	</tr>
</table>
<? if($code=="inquiry"){ ?>
<br>
<div class="helpTip box">
	<h4>안내사항</h4>
	<div class="content">
	  <div class="explain">
		- 예약 일정을 등록/수정/삭제할 수 있는 페이지입니다.<br />
        - "예약일정 등록"을 클릭하여 예약가능한 날짜와 시간을 지정합니다.<br />
        - <strong>예약일정을 수정 또는 삭제하실 경우 예약일정 제목 좌측의 "수정"버튼을 클릭하여 진행</strong>하시면 됩니다.<br />
		- <strong>비활성화된 일정은 제목이 빨간글씨</strong>로 표현됩니다.<br/>
		- 예약일정 제목을 클릭하시면 타임별 진행 또는 마감여부 및 예약인원을 확인하실 수 있습니다.<br />
		- 시간타임 선택 시 예약내역을 확인하실 수 있습니다.<br />
		- 비활성화 된 시간타임은 빨간글씨로 표현합니다.<br/>
	  </div>
	</div>
</div>
<? } ?>
<br>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign="top">
			<form name="frm" action="<?=$PHP_SELF?>">
			<input type="hidden" name="code" value="<?=$code?>">
			<input type="hidden" name="menucode" value="<?=$menucode?>">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr height="20">
					<td width="30%">
						<select name="year" onChange="this.form.submit();" class="select">
							<?
							for($i=$ppre_year; $i<=$nnex_year; $i++){
							?>
							<option value="<?=$i?>"><?=$i?>년
							<? } ?>
						</select>
						<select name="month" onChange="this.form.submit();" class="select">
							<option value="01">1월
							<option value="02">2월
							<option value="03">3월
							<option value="04">4월
							<option value="05">5월
							<option value="06">6월
							<option value="07">7월
							<option value="08">8월
							<option value="09">9월
							<option value="10">10월
							<option value="11">11월
							<option value="12">12월
						</select>
					</td>
					<td width="30%" align="center">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td><a href="<?echo $PHP_SELF?>?code=<?=$code?>&year=<?=$prevYear?>&month=<?=$prevMonth?>&<?=$menucodeParam?>"><img src='../image/btn_prev.gif' border='0'></a></td>
								<td>&nbsp;<b><?=$year?>년 <?=$month?>월</b>&nbsp;</td>
								<td><a href="<?echo $PHP_SELF?>?code=<?=$code?>&year=<?=$nextYear?>&month=<?=$nextMonth?>&<?=$menucodeParam?>"><img src='../image/btn_next.gif' border='0'></a></td>
							</tr>
						</table>
					</td>
					<td width="30%" align="right">
						<!-- <img src="../image/btn_bbswrite.gif" style="cursor:hand" onClick="document.location='input.php?code=<?=$code?>'"> -->
							<input type="button" value="공휴일관리" class="btnInquiry" onClick="holiday()">
						<? if($code=="inquiry"){ ?>
							<input type="button" value="전체일정 열기" class="btnInquiry" onClick="allchange('open');">
							<input type="button" value="전체일정 닫기" class="btnInquiry" onClick="allchange('close');">
							<input type="button" value="예약현황 확인" class="btnInquiry" onClick="document.location='../bbs/list.php?code=online&menucode=BBS'">
							<input type="button" value="예약일정 등록" class="btnListchk3" onClick="document.location='input.php?code=<?=$code?>&<?=$menucodeParam?>'">
						<? } else { ?>
							<input type="button" value="일정등록" class="btnListchk3" onClick="document.location='input.php?code=<?=$code?>&<?=$menucodeParam?>'">
						<? } ?>
					</td>
				</tr>
				<tr><td height=5></td></tr>
			</table>
			</form>
			<script language="javascript">
			<!--
			year = document.frm.year;
			for(ii=0; ii<year.length; ii++){
				if(year.options[ii].value == "<?=$year?>")
				year.options[ii].selected = true;
			}
			month = document.frm.month;
			for(ii=0; ii<month.length; ii++){
				if(month.options[ii].value == "<?=$month?>")
				month.options[ii].selected = true;
			}
			-->
			</script>
			<?php
			/*
			작업자명	: 이상민
			작업일시	: 2020-03-23
			작업내용	: 온라인예약과 일반 일정관리 달력의 분리
			*/
			switch($code){
				case "inquiry":
					include_once "calendar_inquiry.php";
					break;
				default:
					include_once "calendar.php";
			}
			?>
		</td>
	</tr>
</table>

<? include "../foot.php"; ?>