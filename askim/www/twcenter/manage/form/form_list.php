<? include_once "../../common.php"; ?>
<? include_once "../../inc/admin_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>
<?
$param = "code=".$code."&searchopt=".$searchopt."&searchkey=".$searchkey."&searchstatus=".$searchstatus."&".$menucodeParam;
?>

<script language="JavaScript" type="text/javascript">
<!--

// 체크박스 전체선택
function selectAll(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
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
			if(document.forms[i].idx != null){
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
	var selbbs = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selbbs = selbbs + document.forms[i].idx.value + "|";
				}
			}
	}
	return selbbs;
}

function delForm(){

	selform = selectValue();

	if(selform == ""){
		alert("삭제할 게시물을 선택하세요.");
		return false;
	}else{
		if(confirm("선택한 게시물을 정말 삭제하시겠습니까?")){
			document.location = "form_save.php?code=<?=$code?>&mode=delete&selform=" + selform + "&page=<?=$page?>&<?=$menucodeParam?>";
		}
	}
}

function delConfirm(idx){
	if(confirm("삭제 하시겠습니까?")){
		document.location = "form_save.php?code=<?=$code?>&mode=delete&idx=" + idx + "&page=<?=$page?>&<?=$menucodeParam?>";
	}
}

function form_config() {
	window.open('form_config.php?code=<?=$code?>', 'form_config', 'width=700,height=380');
}
// 엑셀다운
function excelDown(){
	<? if(empty($code)) { ?>
		alert("폼메일목록에서 폼메일을 선택한 후 저장할 수 있습니다.");
	<? } else { ?>
		document.location = "form_excel.php?<?=$param?>";
	<? } ?>
}

//-->
</script>

      <?
			$today = date('n-d');
			$toyear = date('Y');

			$age_syear = substr($toyear-($s_age+9),-2)+1;
			$age_eyear = substr($toyear-$s_age,-2)+2;

			$join_sdate = $prev_year."-".$prev_month."-".$prev_day;
			$join_edate = $next_year."-".$next_month."-".$next_day;

			$sql = "select count(idx) as all_total from wiz_form";
			$result = query($sql) or error("sql error");
			$row = sql_fetch_obj($result);
			$all_total = $row->all_total;

			if($code != "") $code_sql = " and code = '$code' ";
			if($searchkey != "") $search_sql = " and $searchopt like '%$searchkey%' ";
			if(!empty($searchstatus)) $status_sql = " and status = '$searchstatus' ";

			$sql = "select * from wiz_form where code != '' $code_sql $search_sql order by idx desc";
			$result = query($sql) or error("sql error");
			$total = sql_fetch_row($result);

			$rows = 20;
			$lists = 5;
			$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$page_count = ceil($total/$rows);
			if(!$page || $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;

      ?>
      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">폼메일관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">작성된 폼메일을 관리 합니다.</td>
        </tr>
      </table>

      <br>
      <form action="<?=$PHP_SELF?>">
      <input type="hidden" name="code" value="<?=$code?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
		<tr>
        	<td width="15%" class="t_name">조건검색</td>
        	<td width="85%" class="t_value">
				<table cellspacing="2" cellpadding="0">
					<tr>
						<td>
							<select name="searchstatus" class="select">
							<option value="">:: 처리상태 :: </option>
							<option value="대기중" <? if($searchstatus == "대기중") echo "selected"; ?>>대기중</option>
							<option value="처리중" <? if($searchstatus == "처리중") echo "selected"; ?>>처리중</option>
							<option value="처리완료" <? if($searchstatus == "처리완료") echo "selected"; ?>>처리완료</option>
							</select>
						</td>
						<td>
						  <select name="searchopt" class="select">
							<option value="content">작성내용</option>
							</select>
						</td>
						<td><input type="text" name="searchkey" value="<?=$searchkey?>" class="input"></td>
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
					<input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>?code=<?=$code?>&<?=$menucodeParam?>'">
				</td>
			</tr>
		</table>

		</form>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="title_msg">총 등록수 : <strong id="total_prd_cnt"><?=$all_total?></strong></span></td>
          <td align="right">
		  <? if($wiz_admin['designer'] == 'Y' && $code) { ?>
				<!--input type="button" value="엑셀파일저장" class="btnExcel" onclick="excelDown();"-->
			  <input type="button" value="수신설정" class="btnListchk3" onclick="form_config()">
		  <? } ?>
		  </td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<form>
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
        	<th width="5%"><input type="checkbox" name="select_tmp" onClick="selectReverse(this.form)"></th>
          <th width="5%">번호</th>
          <th>제목</th>
          <th width="18%">작성자</th>
          <th width="15%">작성일</th>
          <th width="10%">처리상태</th>
          <th width="10%">기능</th>
        </tr> 
        <tr><td class="t_rd" colspan=20></td></tr>
        </form>
			<?

			$sql = "select * from wiz_form where code != '' $code_sql $status_sql $search_sql order by idx desc limit $start, $rows";
			$result = query($sql) or error("sql error");

			while($row = sql_fetch_arr($result)){

			$wtime = mktime(0,0,0,substr($row['wdate'],5,2),substr($row['wdate'],8,2),substr($row['wdate'],0,4));
			if(($ttime-$wtime)/172800 <= $bbs_info['newc']) $new = "<img src='../image/bbs/new.gif' border='0' align='absmiddle'>";	// new
			else $new = "";

			if(!empty($row['phone'])) $row['phone'] = " / ".$row['phone'];
			if(!empty($row['email'])) $row['email'] = " / ".$row['email'];
			?>
			  <form>
		    <input type="hidden" name="idx" value="<?=$row['idx']?>">
        <tr height="38">
        	<td align="center"><input type="checkbox" name="select_checkbox"></td>
          <td align="center"><?=$no?></td>
          <td><?=$row['subject']?> <?=$new?></td>
          <td align="center" style="padding:2px"><?=$row['name']?><?=$row['phone']?><?=$row['email']?></td>
          <td align="center"><?=$row['wdate']?></td>
          <td align="center"><?=$row['status']?></td>
          <td align="center">
          <img src="../image/btn_view_s.gif" style="cursor:hand" onClick="document.location='form_input.php?<?=$param?>&idx=<?=$row['idx']?>&page=<?=$page?>';">
          <img src="../image/btn_delete_s.gif" style="cursor:hand" onClick="delConfirm('<?=$row['idx']?>')">
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
        </form>
      <?
				$no--;
      }

    	if($total <= 0){
    	?>
    	  <tr><td height="30" colspan="10" align="center">작성된 폼메일이 없습니다.</td></tr>
        <tr><td colspan="20" class="t_line"></td></tr>
    	<?
    	}
      ?>
      </table>

      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
      	<tr><td height="5"></td></tr>
        <tr>
          <td width="33%">
            <!-- <img src="../image/btn_bbslist.gif" style="cursor:hand" onClick="document.location='form_list.php?code=<?=$code?>';">
            <img src="../image/btn_seldel.gif" style="cursor:hand" onClick="delForm();"> -->
			<input type="button" value="선택삭제" class="btnListchk" onClick="delForm();">

          </td>
          <td width="33%" align="center"><? print_pagelist($page, $lists, $page_count, $param); ?></td>
          <td width="33%"></td>
        </tr>
      </table>

<? include "../foot.php"; ?>