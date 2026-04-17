<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include "../head.php"; ?>


     <script language="JavaScript" type="text/javascript">
     <!--
     function deleteBbs(code){
       if(confirm('선택한 일정을 삭제하시겠습니까?\n\n삭제한 데이타는 복구할수 없습니다.')){
         document.location = 'sch_save.php?mode=delete&code=' + code + '&page=<?=$page?>&<?=$menucodeParam?>';
       }
     }
     //-->
     </script>

			<?
			$level_info = level_info();

			$sql = "select * from wiz_bbsinfo where type='SCH' order by code";
			$result = query($sql) or error("sql error");
			$total = sql_fetch_row($result);

			$rows = 20;
			$lists = 5;
			$page_count = ceil($total/$rows);
			if(!$page || $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;
			?>
			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">일정관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">일정을 수정합니다. 작업된 일정 기능 외에는, 관리자 페이지에서 별도로 일정을 추가하거나 삭제할 수 없습니다.</td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="title_msg">총 일정수 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
          <td align="right"><!-- <img src="../image/btn_schadd.gif" style="cursor:hand" onClick="document.location='sch_input.php?mode=insert';"> -->
		    <input type="button" value="일정생성" class="btnListchk3" onClick="document.location='sch_input.php?mode=insert&<?=$menucodeParam?>';">
		  </td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th width="10%">영문명</th>
          <th>일정명</th>
          <th width="10%">목록보기</th>
          <th width="10%">내용보기</th>
          <th width="10%">글쓰기</th>
          <th width="10%">코멘트쓰기</th>
          <th width="10%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
				<?
				$sql = "select * from wiz_bbsinfo where type='SCH' order by code limit $start, $rows";
				$result = query($sql) or error("sql error");

				while($row = sql_fetch_obj($result)){
				?>
		  	<tr>
          <td height="38" align="center"><?=$no?></td>
          <td align="center"><a href="sch_input.php?mode=update&code=<?=$row->code?>&page=<?=$page?>&<?=$menucodeParam?>"><?=$row->code?></a></td>
          <td align="center"><a href="sch_input.php?mode=update&code=<?=$row->code?>&page=<?=$page?>&<?=$menucodeParam?>"><?=$row->title?></a></td>
          <td align="center"><?=$level_info[$row->lpermi]['name']?></td>
          <td align="center"><?=$level_info[$row->rpermi]['name']?></td>
          <td align="center"><?=$level_info[$row->wpermi]['name']?></td>
          <td align="center"><?=$level_info[$row->cpermi]['name']?></td>
          <td align="center">
          <img src="../image/btn_edit_s.gif" style="cursor:pointer" onClick="document.location='sch_input.php?mode=update&code=<?=$row->code?>&page=<?=$page?>&<?=$menucodeParam?>'">
          <img src="../image/btn_delete_s.gif" style="cursor:pointer" onClick="deleteBbs('<?=$row->code?>');">
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
				<?
				$no--;
				}

				if($total <= 0){
				?>
				<tr><td height="30" colspan="10" align="center">등록된 일정이 없습니다.</td></tr>
				<tr><td colspan="20" class="t_line"></td></tr>
				<?
				}
				?>
      </table>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
      	<tr><td height="5"></td></tr>
        <tr>
          <td align="center"><? print_pagelist($page, $lists, $page_count, $param.$menucodeParam); ?></td>
        </tr>
      </table>

<? include "../foot.php"; ?>