<? include_once "../../common.php"; ?>
<? include_once "../../inc/admin_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
$param = "code=".$code."&searchopt=".$searchopt."&searchkey=".$searchkey."&".$menucodeParam;
?>
<? include "../head.php"; ?>
<script language="JavaScript" type="text/javascript">
<!--
function delConfirm(idx){
	if(confirm("삭제 하시겠습니까?")){
		document.location = "poll_save.php?mode=delete&idx=" + idx + "&<?=$param?>";
	}
}
//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">설문관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">설문을 관리합니다.</td>
        </tr>
      </table>

      <br>
      <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="code" value="<?=$code?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
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
               <select name="searchopt" class="select">
               <option value="">:: 선택 ::
               <option value="subject">설문명
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
				<a href="<?=$PHP_SELF?>?code=<?=$code?>&<?=$menucodeParam?>"><input type="button"  value="전체목록" class="search_default"></a>
			</td>
		</tr>
	</table>

	  </form>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
      </table>
			<?
			$today = date('n-d');
			$toyear = date('Y');

			$age_syear = substr($toyear-($s_age+9),-2)+1;
			$age_eyear = substr($toyear-$s_age,-2)+2;

			$join_sdate = $prev_year."-".$prev_month."-".$prev_day;
			$join_edate = $next_year."-".$next_month."-".$next_day;

			$sql = "select count(idx) as all_total from wiz_poll where code = '$code'";
			$result = query($sql) or error("sql error");
			$row = sql_fetch_obj($result);
			$all_total = $row->all_total;

			if($searchopt != "") $search_sql .= " and $searchopt like '%$searchkey%' ";

			$sql = "select * from wiz_poll where code = '$code' $search_sql order by idx desc";
			$result = query($sql) or error("sql error");
			$total = sql_fetch_row($result);

			$rows = 20;
			$lists = 5;
			$page_count = ceil($total/$rows);
			if(!$page || $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;

			if($start>1) mysqli_data_seek($result,$start);

			?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="title_msg">총 설문수 : <strong id="total_prd_cnt"><?=$all_total?></strong></span></td>
          <td align="right">
		    <input type="button" value="설문생성" class="btnListchk3" onClick="document.location='poll_input.php?mode=insert&code=<?=$code?>&<?=$menucodeParam?>';">
          </td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th>설문</th>
          <th width="15%">기간</th>
          <th width="10%">참여자수</th>
          <th width="10%">진행여부</th>
          <th width="10%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
			<?
			while(($row = sql_fetch_arr($result)) && $rows){
			?>
        <tr>
          <td height="38" align="center"><?=$no?></td>
          <td style="padding: 0 0 0 10px"><a href="poll_input.php?mode=update&idx=<?=$row['idx']?>&page=<?=$page?>&<?=$param?>"><?=$row['subject']?></a></td>
          <td align="center"><?=$row['sdate']?>~<?=$row['edate']?></td>
          <td align="center"><?=$row['cnt']?></td>
          <td align="center"><? if($row['polluse'] == "Y" && $row['edate'] >= date('Y-m-d')) echo "진행중"; else echo "<font color=red>진행종료</font>"; ?></td>
          <td align="center">
          <img src="../image/btn_edit_s.gif" style="cursor:hand" onClick="document.location='poll_input.php?mode=update&idx=<?=$row['idx']?>&page=<?=$page?>&<?=$param?>';">
          <img src="../image/btn_delete_s.gif" style="cursor:hand" onClick="delConfirm('<?=$row['idx']?>')">
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
			<?
				$no--;
				$rows--;
			}

			if($total <= 0){
			?>
    	  <tr><td height="30" colspan="10" align="center">등록된 설문이 없습니다.</td></tr>
        <tr><td colspan="20" class="t_line"></td></tr>
			<?
			}
			?>
      </table>

      <br>

      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><? print_pagelist($page, $lists, $page_count, $param); ?></td>
        </tr>
      </table>

<? include "../foot.php"; ?>