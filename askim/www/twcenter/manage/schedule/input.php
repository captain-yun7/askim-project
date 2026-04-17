<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/bbs_info.php"; ?>
<?
// 검색 파라미터
$param = "code=$code&page=$page&category=$category&searchopt=$searchopt&searchkey=$searchkey&$menucodeParam";

$upfile_max = $bbs_info['upfile'];
$movie_max = $bbs_info['movie'];

if($mode == "insert" || $mode == ""){

	$mode = "insert";
	$bbs_row['name'] = $wiz_admin['name'];
	$bbs_row['email'] = $wiz_admin['email'];
	$bbs_row['addinfo1'] = $sch_date;
	$bbs_row['passwd'] = date('is');
	$bbs_row['count'] = 0;

	$delBtn = false;

}else if($mode == "update"){

	$sql = "select *, from_unixtime(wdate, '%Y-%m-%d') as wdate from wiz_bbs where code = '$code' and idx='$idx'";
	$result = query($sql) or error("sql error");
	$bbs_row = sql_fetch_arr($result);
	$bbs_row['content'] = stripslashes($bbs_row['content']);

	$delBtn = true;

}else if($mode == "reply"){

	$sql = "select subject, content, privacy, passwd from wiz_bbs where code = '$code' and idx='$idx'";
	$result = query($sql) or error("sql error");
	$bbs_row = sql_fetch_arr($result);

	$bbs_row['name'] 	= $wiz_admin['name'];
	$bbs_row['email'] = $wiz_admin['email'];
	$bbs_row['wdate'] = date('Y-m-d');
	$bbs_row['count'] = 0;
	$bbs_row['content'] = stripslashes($bbs_row['content']);
	$bbs_row['content'] = $bbs_row['content']."\n\n==================== 답 변 ====================\n\n";

	$delBtn = false;

}

?>
<? include "../head.php"; ?>
<? include "../../lib/datepicker_lib.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function inputCheck(frm){
	if(frm.mode.value !="delete"){ //일정 삭제하는 경우에는 필수체크 제외
		if(frm.name.value == ""){
			alert("이름을 입력하세요.");
			frm.name.focus();
			return false;
		}

		<?php
		/*
		작업자명	: 이상민
		작업일시	: 2021-09-16
		작업내용	: 온라인예약관련 수정
		*/
		if($code == "inquiry") {
		?>
		if(frm.able_sDay.value == "") {
			alert("예약가능기간을 선택하시기 바랍니다.");
			frm.able_sDay.focus();
			return false;
		}
		if(frm.able_eDay.value == ""){
			alert("예약가능기간을 선택하시기 바랍니다.");
			frm.able_eDay.focus();
			return false;
		}
		if(frm.able_sDay.value > frm.able_eDay.value){
			alert("예약가능 종료일은 시작일 이전일 수 없습니다. \n기간 선택을 확인하시기 바랍니다.");
			frm.able_eDay.focus();
			return false;
		}

		if(frm.addinfo1.value == "") {
			alert("기간을 선택하시기 바랍니다.");
			frm.addinfo1.focus();
			return false;
		}
		if(frm.addinfo2.value == ""){
			alert("기간을 선택하시기 바랍니다.");
			frm.addinfo2.focus();
			return false;
		}
		if(frm.addinfo1.value > frm.addinfo2.value){
			alert("종료일은 시작일 이전일 수 없습니다. \n기간 선택을 확인하시기 바랍니다.");
			frm.addinfo2.focus();
			return false;
		}

		var timeCnt = 0;
		var timeErrorCnt = 0;
		var timeNullCnt = 0;

		$(".addinfo3").each(function(){
			if($(this).val() != ""){
				timeCnt++;
				if($(this).val().length <= 5){
					timeErrorCnt++;
				}
			}else{
				timeNullCnt++;
			}
		});

		if(timeCnt == 0){
			alert("시간을 하나 이상 등록하시기 바랍니다.");
			$(".addinfo3").eq(0).focus();
			return false;
		}

		if(timeErrorCnt > 0){
			alert("시간을 정확히 입력하시기 바랍니다.");
			$(".addinfo3").eq(0).focus();
			return false;
		}

		//시간이 아예 입력되지 않았을 경우 체크 2021-11-22
		if(timeNullCnt > 0){
			alert("시간을 정확히 입력하시기 바랍니다.");
			$(".addinfo3").eq(0).focus();
			return false;
		}

		if(frm.addinfo4.value == ""){
			alert("시간당 정원을 입력하시기 바랍니다.");
			frm.addinfo4.focus();
			return false;
		}

		//시간당 정원이 0명으로 입력 되었을 경우 2021-11-22
		if(Number(frm.addinfo4.value.trim()) == 0){
			alert("시간당 정원은 0명 이상이어야 합니다.");
			frm.addinfo4.focus();
			return false;
		}
		
		if(frm.addinfo7.value == "") {
			alert("예약당 인원제한을 설정하시기 바랍니다.");
			frm.addinfo7.focus();
			return false;
		}

		if(frm.addinfo8.value == ""){
			alert("예약당 인원제한을 설정하시기 바랍니다.");
			frm.addinfo8.focus();
			return false;
		}

		//예약당 인원제한이 0명으로 입력 되었을 경우 2021-11-22
		if(Number(frm.addinfo7.value.trim()) == 0){
			alert("예약당 인원제한은 0명 이상이어야 합니다.");
			frm.addinfo7.focus();
			return false;
		}

		if(Number(frm.addinfo8.value.trim()) == 0){
			alert("예약당 인원제한은 0명 이상이어야 합니다.");
			frm.addinfo8.focus();
			return false;
		}

		if(Number(frm.addinfo7.value.trim()) > Number(frm.addinfo8.value.trim())){
			alert("인원제한을 정확히 설정하시기 바랍니다.");
			frm.addinfo8.focus();
			return false;
		}

		if(Number(frm.addinfo4.value.trim()) < Number(frm.addinfo8.value.trim())){
			alert("예약당 인원제한은 시간당 정원보다 많을 수 없습니다.");
			frm.addinfo8.focus();
			return false;
		}

		if(!$(':input:radio[name=addinfo5]:checked').val()) {
			alert("예약 활성화 상태를 선택하시기 바랍니다.");
			return false;
		}

		<?php } ?>

		if(frm.subject.value == ""){
			alert("제목을 입력하세요.");
			frm.subject.focus();
			return false;
		}
		try{ content.outputBodyHTML(); } catch(e){ }
		if(frm.content.value == ""){
			alert("내용을 입력하세요.");
			return false;
		}

		if(frm.passwd.value == ""){
			alert("비밀번호를 입력하세요.");
			frm.passwd.focus();
			return false;
		}
	}
}

$(function() {
	var calendar = {
		showOn: "both", 
		buttonImage: "/twcenter/images/calendar_btn2.gif", 
		buttonImageOnly: true,
		showButtonPanel: true, 
		currentText: '오늘', 
		closeText: '닫기', 
		dateFormat: "yy-mm-dd",
		changeMonth: true, 
		changeYear: true, 
		dayNames: ['일요일','월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
		dayNamesMin: ['<font color=red>일</font>', '월', '화', '수', '목', '금', '<font color=#0071bf>토</font>'], 
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
		//	numberOfMonths: [2,2]
	};

	$("#consul_sdate").datepicker(calendar);
	$("#consul_edate").datepicker(calendar);
	$("#resv_sdate").datepicker(calendar);
	$("#resv_edate").datepicker(calendar);

	$("img.ui-datepicker-trigger").attr("style","margin-left:5px; vertical-align:middle; cursor:pointer;");
	$("#ui-datepicker-div").hide();

	$('#wdate').datepicker({
		language: 'kr',
		autoClose: true
	});

	$(document).on("click", ".time_btn", function(){
		var workType = $(this).attr("data-type");

		switch(workType){
			case "add":
				var target = $(".span_time:last");
				var clone = target.clone();
				clone.find(".addinfo3").val("");

				$("#td_time").append(clone);
				break;
			case "del":
				var cnt = $(".span_time").length;
				if(cnt > 1){
					var target = $(this).parent(".span_time");
					target.remove();
				} else {
					alert("더이상 삭제하실 수 없습니다.");
				}
				break;
		}
	});

	$(document).on("click", "#btn_del", function(){
		if(!confirm("일정을 삭제하시겠습니까?")) return;

		$("#mode").val("delete");

		$("#frm").submit();
	});

});
//-->
</script>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">일정관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">일정을 추가/삭제/수정 합니다.</td>
	</tr>
</table>
<? if($code == "inquiry") { ?>
<br>
<div class="helpTip box">
	<h4>기간 설정 예시</h4>
	<div class="content">
	  <div class="explain">
		- <font color="#0000FF">예약을 할 수 있는 기간</font> : 02/01 ~ 02/10<br>
		- <font color="#ff0080">일정이 진행되는 기간</font> : 03/01 ~ 03/05<br>
		ex) <font color="#ff0080">03월 01일부터 05일</font>까지 진행되는 공연 티켓을 예매할 수 있는 기간은 <font color="#0000FF">02월 01일 부터 10일</font>까지입니다.
	  </div>
	</div>
</div>
<? } ?>
<br>
<form name="frm" id="frm" action="save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)">
<input type="hidden" name="code" id="code" value="<?=$code?>">
<input type="hidden" name="mode" id="mode" value="<?=$mode?>">
<input type="hidden" name="idx" value="<?=$idx?>">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="ctype" value="<?=$ctype ?>">
<input type="hidden" name="menucode" value="<?=$menucode ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" class="t_name">작성자</td>
					<td width="35%" class="t_value"><input name="name" type="text" value="<?=$bbs_row['name']?>" class="input"></td>
					<td width="15%" class="t_name">이메일</td>
					<td width="35%" class="t_value"><input name="email" type="text" value="<?=$bbs_row['email']?>" size="30" class="input"></td>
				</tr>
				<?
				if($code=="inquiry" ){
				/*
				작업자명	: 이상민
				작업일시	: 2020-03-23
				작업내용	: 예약가능 시간 및 기간 wiz_bbs에 저장하기 위해 필드명 변경
				
				작업자명	: 이상민
				작업일시	: 2021-09-16
				작업내용	: 예약가능 기간과 일정기간의 분리
				*/
				?>
				<tr>
					<td class="t_name"><font color="#0000FF">예약을 할 수 있는 기간</font> *</td>
					<td class="t_value cal_time" colspan="3">
						<?php
						if($bbs_row['addinfo9'] != ""){
							$able_sDay = date("Y-m-d", strtotime($bbs_row['addinfo9']));
							$able_sTime = date("H", strtotime($bbs_row['addinfo9']));
						}

						if($bbs_row['addinfo10'] != ""){
							$able_eDay = date("Y-m-d", strtotime($bbs_row['addinfo10']));
							$able_eTime = date("H", strtotime($bbs_row['addinfo10']));
						}
						?>
						<span><input name="able_sDay" type="text" value="<?=$able_sDay?>" autocomplete="off" id="resv_sdate" class="input"></span>
						<select name="able_sTime" class="select">
							<?php
							for($i=0;$i<24;$i++){
								if($i < 10) $time = "0".$i;
								else $time = $i;
							?>
							<option value="<?php echo $time; ?>" <?php if($able_sTime==$i) echo "selected"; ?>><?php echo $time; ?>시</option>
							<?php } ?>
						</select> ~ 
						<span><input name="able_eDay" type="text" value="<?=$able_eDay?>" autocomplete="off" id="resv_edate" class="input"></span>
						<select name="able_eTime" class="select">
							<?php
							for($i=0;$i<24;$i++){
								if($i < 10) $time = "0".$i;
								else $time = $i;
							?>
							<option value="<?php echo $time; ?>" <?php if($able_eTime==$i) echo "selected"; ?>><?php echo $time; ?>시</option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="t_name"><font color="#ff0080">일정이 진행되는 기간</font> *</td>
					<td class="t_value cal_time" colspan="3">
						<span><input name="addinfo1" type="text" value="<?=$bbs_row['addinfo1']?>" autocomplete="off" id="consul_sdate" class="input"></span> ~ 
						<span><input name="addinfo2" type="text" value="<?=$bbs_row['addinfo2']?>" autocomplete="off" id="consul_edate" class="input"></span>
					</td>
				</tr>
				<tr>
					<td class="t_name">시간 * <input type="button" value="추가" class="btn_all time_btn" data-type="add"><p style="font-weight:normal; color:#888">예) 09:00 ~ 10:00</p></td>
					<td class="t_value checkbox" colspan="3" id="td_time">
						<?php
						$_arr2    = $bbs_row['addinfo3'];
						
						if(!isset($_arr2)) $_arr2 = '';
						$arr_tmp2 = explode("/",$_arr2);

						$_arr3    = $bbs_row['addinfo6'];
						if(!isset($_arr3)) $_arr3 = '';
						$arr_tmp3 = explode("/",$_arr3);
						for($ii=0; $ii<count($arr_tmp2); $ii++){
						?>
						<span class="span_time">
							<input name="addinfo3[]" type="text" value="<?php echo $arr_tmp2[$ii]; ?>" size="20" class="input addinfo3" /> 
							<select name="addinfo6[]" class="select">
								<option value="Y">활성화</option>
								<option value="N" <?php if($arr_tmp3[$ii] == "N") echo "selected"; ?>>비활성화</option>
							</select>
							<input type="button" value="삭제" class="btn_close time_btn" data-type="del">
						</span>
						<?php
						}
						?>
						<p><span class="sub_tit_alt2" style="border:none;" >※ 타임별 활성화/비활성화를 선택합니다.</span></p>
					</td>
				</tr>
				<tr>
					<td class="t_name">시간당 정원 *</td>
					<td class="t_value cal_time">
						<input name="addinfo4" type="text" value="<?php echo $bbs_row["addinfo4"]; ?>" size="20" class="input numCk" />
						<span class="sub_tit_alt2" style="border:none;" >※ 한 타임당 가능인원이 책정되며, 숫자만 입력가능합니다.</span>
					</td>
					<td class="t_name">예약당 인원제한 *</td>
					<td class="t_value cal_time">
						<input type="text" name="addinfo7" class="input numCk" size="10" value="<?php echo $bbs_row['addinfo7']; ?>" placeholder="최소인원"> ~ 
						<input type="text" name="addinfo8" class="input numCk" size="10" value="<?php echo $bbs_row['addinfo8']; ?>" placeholder="최대인원">
					</td>
				</tr>
				<tr>
					<td class="t_name">예약 활성화 *</td>
					<td class="t_value cal_time" colspan="3">
						<input type="radio" name="addinfo5" value="Y" <?php if($bbs_row["addinfo5"]=="Y"||$mode=="insert") echo "checked"; ?>> 활성화&nbsp;&nbsp;&nbsp;
						<input type="radio" name="addinfo5" value="N" <?php if($bbs_row["addinfo5"]=="N") echo "checked"; ?>> 비활성화&nbsp;&nbsp;&nbsp;
					</td>
				</tr>
				<? } ?>
				<? if($code != "inquiry"){ ?>
				<tr>
					<td class="t_name">일자</td>
					<td class="t_value">
						<span class="calendar"><input name="wdate" type="text" id="wdate" value="<?=$bbs_row['wdate']?>" class="datepicker-here input2"></span>
					</td>
					<td class="t_name">조회수</td>
					<td class="t_value"><input name="count" type="text" value="<?=$bbs_row['count']?>" class="input"></td>
				</tr>
				<? } ?>
				<tr>
					<td class="t_name">제목</td>
					<td class="t_value" colspan="3">
						<?
						if($bbs_info['category'] != ""){
							$catlist = explode(",",$bbs_info['category']);
							echo "<select name='category'>";
							echo "<option value=''>분류</option>";
							for($ii=0;$ii<count($catlist);$ii++){
								if($bbs_row['category'] == $catlist[$ii]) $selected = "selected";
								else $selected = "";
								echo "<option value='".$catlist[$ii]."' ".$selected.">".$catlist[$ii]."</option>";
							}
							echo "</select>";
						}
						?>
						<input type="text" name="subject"  value="<?=$bbs_row['subject']?>" size="60" class="input">
						<!--<input type="checkbox" name="notice" value="Y" <? if($bbs_row['notice'] == "Y") echo "checked"; ?>>공지글-->
						<? if($code != "semi_sch" && $code != "group_sch" && $code!="exhi_sch"){ ?><input type="checkbox" name="privacy" value="Y" <? if($bbs_row['privacy'] == "Y" || ($mode != "update" && $bbs_info['privacy'] == "Y")) echo "checked"; ?>>비밀글<? } ?>
						<input type="checkbox" name="ctype" value="H" <? if($bbs_row['ctype'] == "H") echo "checked"; ?>>HTML사용
					</td>
				</tr>
				<tr>
					<td class="t_name">내용</td>
					<td class="t_value" colspan="3">
						<?php
						if($bbs_info['editor'] == "Y"){
							$edit_content = $bbs_row['content'];
							include "../../webedit/WIZEditor.html";
						} else {
						?>
						<textarea name="content" rows="16" cols="80" class="textarea" style="width:100%"><?=$bbs_row['content']?></textarea>
						<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<td class="t_name">비밀번호</td>
					<td width="275" class="t_value" colspan="3"><input name="passwd" type="text" value="<?=$bbs_row['passwd']?>" class="input"></td>
				</tr>
				<?php
				for($ii = 1; $ii <= $upfile_max; $ii++) {
					$upfile = "upfile".$ii;
					$upfile_name = "upfile".$ii."_name";
				?>
				<tr>
					<td class="t_name">파일첨부<?=$ii?></td>
					<td class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file<?=$ii?>">파일 업로드</label>
							<input type="file" name="upfile<?=$ii?>" id="input-file<?=$ii?>" class="upload-hidden">
						</div>

						<? if($bbs_row[$upfile] != ""){ ?>
						<input type="checkbox" name="delupfile[]" value="upfile<?=$ii?>"> 삭제
						&nbsp;<a href='../../data/bbs/<?=$code?>/<?=$bbs_row[$upfile]?>' target='_blank'><?=$bbs_row[$upfile_name]?></a>
						<? } ?>
					</td>
				</tr>
				<?php
				}
				
				for($ii = 1; $ii <= $movie_max; $ii++) {
					$movie = "movie".$ii;
					if($ii == 1) {
				?>
				<tr>
					<td class="t_name">동영상<?=$ii?></td>
					<td class="t_value" colspan="3">
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file_move<?=$ii?>">파일 업로드</label>
							<input type="file" name="<?=$movie?>" id="input-file_move<?=$ii?>" class="upload-hidden">
						</div>
					
						<? if($bbs_row[$movie] != ""){ ?>
						<input type="checkbox" name="delupfile[]" value="<?=$movie?>"> 삭제
						&nbsp;<a href='../../data/bbs/<?=$code?>/<?=$bbs_row[$movie]?>' target='_blank'><?=$bbs_row[$movie]?></a>
						<? } ?>
					</td>
				</tr>
				<?php
					} else {
				?>
				<tr>
					<td class="t_name">동영상<?=$ii?></td>
					<td width="275" class="t_value" colspan="3"><input name="<?=$movie?>" size="50" type="text" value="<?=$bbs_row[$movie]?>" class="input"></td>
				</tr>
				<?php
					}
				}
				?>
			</table>
		</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='list.php?<?=$param?>';">&nbsp;
			<?php if($delBtn){ ?>
			<input type="button" value="삭제" class="base_btn red" id="btn_del">
			<?php } ?>
		</td>
	</tr>
</table>
</form>

<? include "../foot.php"; ?>