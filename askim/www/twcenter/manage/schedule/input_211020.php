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

}else if($mode == "update"){

	$sql = "select *, from_unixtime(wdate, '%Y-%m-%d') as wdate from wiz_bbs where code = '$code' and idx='$idx'";
	$result = query($sql) or error("sql error");
	$bbs_row = sql_fetch_arr($result);
	$bbs_row['content'] = stripslashes($bbs_row['content']);

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

}

?>
<? include "../head.php"; ?>
<? include "../../lib/datepicker_lib.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function inputCheck(frm){

	if(frm.name.value == ""){
	  alert("이름을 입력하세요.");
	  frm.name.focus();
	  return false;
	}
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

	$("img.ui-datepicker-trigger").attr("style","margin-left:5px; vertical-align:middle; cursor:pointer;");
	$("#ui-datepicker-div").hide();

});

$(function() {

	$('#wdate').datepicker({
		language: 'kr',
		autoClose: true

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

      <br>
      <form name="frm" action="save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)">
      <input type="hidden" name="code" value="<?=$code?>">
      <input type="hidden" name="mode" value="<?=$mode?>">
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
				  if($code=="inquiry"){
					  /*
					  작업자명	: 이상민
					  작업일시	: 2020-03-23
					  작업내용	: 예약가능 시간 및 기간 wiz_bbs에 저장하기 위해 필드명 변경
					  */
				  ?>
				  <tr>
					<td class="t_name">예약 가능기간 *</td>
					<td class="t_value cal_time" colspan="3">
						<span><input name="addinfo1" type="text" value="<?=$bbs_row['addinfo1']?>" autocomplete="off" id="consul_sdate" class="input"></span> ~ 
						<span><input name="addinfo2" type="text" value="<?=$bbs_row['addinfo2']?>" autocomplete="off" id="consul_edate" class="input"></span>
						<span class="sub_tit_alt2" style="border:none;" >※ 예약 가능기간에 이미 선택된 날짜는 비활성화됩니다.</span>
					</td>
				  </tr>
				  <tr>
					<td class="t_name">예약 가능시간 *</td>
					<td class="t_value checkbox" colspan="3">
						<?
						$_arr2    = $bbs_row['addinfo3'];
						$arr_tmp2 = explode("/",$_arr2);
						for($ii=0; $ii<count($arr_tmp2); $ii++){
							$arr_data2[$arr_tmp2[$ii]]="checked";
						}
						?>
						<label for="0900"><input type="checkbox" name="addinfo3[]" id="0900" value="09:00" <?=$arr_data2['09:00']?>> 09:00~</label>
						<label for="1000"><input type="checkbox" name="addinfo3[]" id="1000" value="10:00" <?=$arr_data2['10:00']?>> 10:00~</label>
						<label for="1100"><input type="checkbox" name="addinfo3[]" id="1100" value="11:00" <?=$arr_data2['11:00']?>> 11:00~</label>
						<label for="1200"><input type="checkbox" name="addinfo3[]" id="1200" value="12:00" <?=$arr_data2['12:00']?>> 12:00~</label>
						<label for="1300"><input type="checkbox" name="addinfo3[]" id="1300" value="13:00" <?=$arr_data2['13:00']?>> 13:00~</label>
						<label for="1400"><input type="checkbox" name="addinfo3[]" id="1400" value="14:00" <?=$arr_data2['14:00']?>> 14:00~</label>
						<label for="1500"><input type="checkbox" name="addinfo3[]" id="1500" value="15:00" <?=$arr_data2['15:00']?>> 15:00~</label>
						<label for="1600"><input type="checkbox" name="addinfo3[]" id="1600" value="16:00" <?=$arr_data2['16:00']?>> 16:00~</label>
						<label for="1700"><input type="checkbox" name="addinfo3[]" id="1700" value="17:00" <?=$arr_data2['17:00']?>> 17:00~</label>
						<label for="1800"><input type="checkbox" name="addinfo3[]" id="1800" value="18:00" <?=$arr_data2['18:00']?>> 18:00~</label>
						<label for="1900"><input type="checkbox" name="addinfo3[]" id="1900" value="19:00" <?=$arr_data2['19:00']?>> 19:00~</label>
						<label for="2000"><input type="checkbox" name="addinfo3[]" id="2000" value="20:00" <?=$arr_data2['20:00']?>> 20:00~</label>
						<label for="2100"><input type="checkbox" name="addinfo3[]" id="2100" value="21:00" <?=$arr_data2['21:00']?>> 21:00~</label>
						<label for="2200"><input type="checkbox" name="addinfo3[]" id="2200" value="22:00" <?=$arr_data2['22:00']?>> 22:00~</label>
					</td>
				  </tr>
			  <? } ?>
			 <? if($code != "inquiry"){ ?>
              <tr>
                <td class="t_name">일자</td>
                <td class="t_value">
                	<span class="calendar"><input name="wdate" type="text" id="wdate" value="<?=$bbs_row['wdate']?>" class="datepicker-here input2" autocomplete="off"></span>
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
                 <? if($code != "inquiry"){ ?><input type="checkbox" name="privacy" value="Y" <? if($bbs_row['privacy'] == "Y" || ($mode != "update" && $bbs_info['privacy'] == "Y")) echo "checked"; ?>>비밀글<? } ?>
                <input type="checkbox" name="ctype" value="H" <? if($bbs_row['ctype'] == "H") echo "checked"; ?>>HTML사용
                </td>
              </tr>
              <tr>
                <td class="t_name">내용</td>
                <td class="t_value" colspan="3">
                <?
				if($bbs_info['editor'] == "Y"){
					$edit_content = $bbs_row['content'];
					include "../../webedit/WIZEditor.html";
				}else{
				?>
                  <textarea name="content" rows="16" cols="80" class="textarea" style="width:100%"><?=$bbs_row['content']?></textarea>
					<?
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
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='list.php?<?=$param?>';">
          </td>
        </tr>
      </table>
	  </form>

<? include "../foot.php"; ?>