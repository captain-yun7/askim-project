<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include "../head.php"; ?>

     <script language="JavaScript" type="text/javascript">
     <!--
     function deleteBbs(code){
     	if(code == "qna" || code == "review") {
     		alert("해당 게시판은 삭제할 수 없습니다.");
     	} else {
				if(confirm('선택한 게시판을 삭제하시겠습니까?\n\n삭제한 데이타는 복구할수 없습니다.')){
					document.location = 'bbs_save.php?mode=delete&code=' + code + '&page=<?=$page?>&<?=$menucodeParam?>';
				}
      }
     }
     //-->
     </script>

	<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">게시판관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">게시판을 수정합니다. 작업된 게시판 기능 외에는, 관리자 페이지에서 별도로 게시판을 추가하거나 삭제할 수 없습니다.</td>
        </tr>
      </table>

			<?
			$level_info = level_info();

			if($menu_arr["PRODUCT"] == true){
				$bbs_sql = " (type='BBS' and (type2='SHOP' or type2 IS NULL))";
			} else {
				$bbs_sql = " (type='BBS' and type2 IS NULL)";
			}

			$sql = "select * from wiz_bbsinfo where $bbs_sql";
			$result = query($sql) or error("sql error");
			$total = sql_fetch_row($result);

			$rows = 20;
			$lists = 5;
			$page_count = ceil($total/$rows);
			if(!$page || $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;

			?>
			<br>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="title_msg">총 게시판수 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
          <td align="right">
			<?
			if(strcmp($site_info['addbbs_use'], "N") || !strcmp($wiz_admin['designer'], "Y")) {
			?>
          	<!-- <img src="../image/btn_bbsadd.gif" style="cursor:hand" onClick="document.location='bbs_input.php?mode=insert';"> -->
			<input type="button" value="게시판추가" class="btnListchk3" onClick="document.location='bbs_input.php?mode=insert&<?=$menucodeParam?>';">
          	<?
          	}
          	?>
          </td>
        </tr>
		<tr><td height='3'></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td colspan=20 class="t_rd"></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th width="8%">그룹명</th>
          <th width="8%">영문명</th>
          <th>게시판명</th>
          <th width="8%">스킨</th>
		  <? if($site_info['mobile_use'] == "Y"){ ?>
          <th width="8%">모바일스킨</th>
		  <? } ?>
          <th width="8%">목록보기</th>
          <th width="8%">내용보기</th>
          <th width="8%">글쓰기</th>
          <th width="8%">답글쓰기</th>
          <th width="8%">코멘트쓰기</th>
          <th width="10%">기능</th>
        </tr>
        <tr><td colspan=20 class="t_rd"></td></tr>
<?
				$sql = "SELECT bbs.*, IFNULL(grp.idx,0) AS gidx, grp.grpname
					FROM wiz_bbsinfo AS bbs
					LEFT JOIN wiz_bbs_grp AS grp ON bbs.grp = grp.idx
					where $bbs_sql
					ORDER BY grp.prior, grp.idx, bbs.prior
					limit $start, $rows";

				$result = query($sql) or error("sql error");

				while($row = sql_fetch_obj($result)){
					if(empty($row->grp)) $bbs_grp_list[$row->grp] = "-";
				?>
		  	<tr>
          <td height="38" align="center"><?=$no?></td>
          <td align="center"><a href="bbs_input.php?mode=update&code=<?=$row->code?>&page=<?=$page?>"><?=$row->grpname ? $row->grpname : "-"?></a></td>
          <td align="center"><a href="bbs_input.php?mode=update&code=<?=$row->code?>&page=<?=$page?>"><?=$row->code?></a></td>
          <td align="center"><a href="bbs_input.php?mode=update&code=<?=$row->code?>&page=<?=$page?>"><?=$row->title?></a></td>
          <td align="center"><?=$row->skin?></td>
		  <? if($site_info['mobile_use'] == "Y"){ ?>
          <td align="center"><?=$row->skin_m?></td>
		  <? } ?>
          <td align="center"><?=$level_info[$row->lpermi]['name']?></td>
          <td align="center"><?=$level_info[$row->rpermi]['name']?></td>
          <td align="center"><?=$level_info[$row->wpermi]['name']?></td>
          <td align="center"><?=$level_info[$row->apermi]['name']?></td>
          <td align="center"><?=$level_info[$row->cpermi]['name']?></td>
          <td align="center">
          <a href="bbs_input.php?mode=update&code=<?=$row->code?>&page=<?=$page?>&<?=$menucodeParam?>"><img src="../image/btn_edit_s.gif" border="0"></a>
            <? if($wiz_admin['designer'] == "Y"){ ?>
            <img src="../image/btn_delete_s.gif" onClick="deleteBbs('<?=$row->code?>');" style="cursor:hand">
            <? } ?>
		  
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
	     	<?
	     		$no--;
	      }

	    	if($total <= 0){
	    	?>
	    		<tr><td height="30" colspan="10" align="center">등록된 게시판이 없습니다.</td></tr>
	        <tr><td colspan="20" class="t_line"></td></tr>
	    	<?
	    	}
	      ?>
      </table>

      <br>
	<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center"><? print_pagelist($page, $lists, $page_count, $param.$menucodeParam); ?></td>
		</tr>
	</table>

<? include "../foot.php"; ?>