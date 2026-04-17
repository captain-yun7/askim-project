<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>
<?
$param = "searchopt=".$searchopt."&searchkey=".$searchkey."&s_ptype=".$s_ptype."&s_mode=".$s_mode;
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
	var selidx = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selidx = selidx + document.forms[i].idx.value + "|";
				}
			}
	}
	return selidx;
}

//선택회원 삭제
function delPoint(){

	selidx = selectValue();

	if(selidx == ""){
		alert("삭제할 포인트를 선택하세요.");
		return false;
	}else{
		if(confirm("선택한 포인트를 정말 삭제하시겠습니까?")){
			document.location = "point_save.php?mode=delpoint&selidx=" + selidx;
		}
	}
}

function pointDelete(idx) {

	if(confirm("정말 삭제하시겠습니까?")) {
		document.location = "point_save.php?mode=delete&idx="+idx+"&<?=$param?>";
	}

}
//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">포인트목록</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">회원에게 부여된 포인트를 관리합니다.</td>
        </tr>
      </table>

      <br>
      <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
      <input type="hidden" name="page" value="<?=$page?>">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td bgcolor="ffffff">
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
            <tr>
            <td width="15%" class="t_name">조건검색</td>
            <td width="85%" class="t_value">

             <table cellspacing="2" cellpadding="0">
             <tr>
             <td>
					    <select name="s_ptype" class="select">
					    <option value="">&nbsp; 전체</option>
					    <option value="JOIN">&nbsp; 회원가입</option>
					    <option value="LOGIN">&nbsp; 로그인</option>
					    <option value="BBS">&nbsp; 게시판 - 전체</option>
					    <option value="BBS_VIEW">&nbsp; 게시판 - 보기</option>
					    <option value="BBS_WRITE">&nbsp; 게시판 - 쓰기</option>
					    <option value="BBS_DOWN">&nbsp; 게시판 - 다운로드</option>
					    <option value="BBS_RECOM">&nbsp; 게시판 - 추천</option>
					    <option value="COMMENT">&nbsp; 코멘트</option>
					    <option value="MSG">&nbsp; 쪽지</option>
					    </select>
					    <script language="javascript">
					    <!--
					     s_ptype = document.searchForm.s_ptype;
					     for(ii=0; ii<s_ptype.length; ii++){
					        if(s_ptype.options[ii].value == "<?=$s_ptype?>")
					           s_ptype.options[ii].selected = true;
					     }
					    -->
					    </script>
					  </td>
             <td>
               <select name="searchopt" class="select">
               <option value="memid">아이디
               <option value="memo">포인트내역
               </select>
             </td>
             <td><input type="text" name="searchkey" value="<?=$searchkey?>" class="input"></td>
             <td><input type="image" src="../image/btn_search.gif"></td>
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
	  </form>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
      </table>
      <?
         $sql = "select count(idx) as all_total from wiz_point";
         $result = query($sql) or error("sql error");
         $row = sql_fetch_arr($result);
      	 $all_total = $row['all_total'];

				if(!empty($searchkey)) $search_sql = " and wp.$searchopt like '%$searchkey%' ";

				list($s_ptype, $s_mode) = explode("_", $s_ptype);

				if(!empty($s_ptype)) $ptype_sql = " and wp.ptype = '$s_ptype' ";
				if(!empty($s_mode) && !strcmp($s_ptype, "BBS")) $mode_sql = " and wp.mode = '$s_mode' ";
				else $s_mode = "";

      	 $sql = "select wp.*, wb.code, wb.subject
      	 				from wiz_point as wp left join wiz_bbs as wb on wp.bidx = wb.idx";
         $sql .= " where wp.idx != '' $search_sql $ptype_sql $mode_sql";
         $sql .=" order by wp.idx desc";
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
          <td>총 포인트수 : <b><?=$all_total?></b> , 검색 포인트수 : <b><?=$total?></b></td>
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
          <th width="10%">아이디</th>
          <th width="10%">포인트</th>
          <th>포인트내역</th>
          <th width="15%">적립일자</th>
          <th width="10%">기능</th>
        </tr>
        <tr><td colspan=20 class="t_rd"></td></tr>
        </form>
		<?
	 $sql = "select wp.*, wb.code, wb.subject
	 				from wiz_point as wp left join wiz_bbs as wb on wp.bidx = wb.idx";
   $sql .= " where wp.idx != '' $search_sql $ptype_sql $mode_sql";
   $sql .=" order by wp.idx desc limit $start, $rows";

		$result = query($sql) or error("sql error");

		while($row = sql_fetch_arr($result)){

			if(!empty($row['subject'])) {
				if(strlen($row['subject']) > 30) $subject = cut_str($row['subject'], 30)."...";
				else $subject = $row['subject'];
				$row['memo'] .= "&nbsp;(".$subject.")";
			}

		?>
	      <form name="frm<?=$no?>">
        <input type="hidden" name="idx" value="<?=$row['idx']?>">
        <tr>
          <td height="30" align="center"><input type="checkbox" name="select_checkbox"></td>
          <td align="center"><?=$no?></td>
          <td align="center"><?=$row['memid']?></td>
          <td align="center"><?=number_format($row['point'])?></td>
          <td><?=$row['memo']?></td>
          <td align="center"><?=$row['wdate']?> &nbsp;</td>
          <td align="center"><img src="../image/btn_delete_s.gif" style="cursor:hand" onClick="pointDelete('<?=$row['idx']?>')"></td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
        </form>
      <?
     		$no--;
      }

    	if($total <= 0){
    	?>
    	  <tr><td height="30" colspan="10" align="center">등록된 포인트가 없습니다.</td></tr>
        <tr><td colspan="20" class="t_line"></td></tr>
    	<?
    	}
      ?>
      </table>

      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
      	<tr><td height="5"></td></tr>
        <tr>
          <td width="33%">
            <!-- <img src="../image/btn_seldel.gif" style="cursor:hand" onClick="delPoint('');"> -->
			<input type="button" value="선택삭제" class="btnListchk4" onclick="delPoint('')">
          </td>
          <td width="33%"><? print_pagelist($page, $lists, $page_count, $param); ?></td>
          <td width="33%"></td>
        </tr>
      </table>

<? include "../foot.php"; ?>