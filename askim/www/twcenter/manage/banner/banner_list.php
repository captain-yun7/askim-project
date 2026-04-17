<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include "../head.php"; ?>

     <script language="JavaScript" type="text/javascript">
     <!--
     function deleteBanner( idx, code ){
       if(confirm('선택한 디자인를 삭제하시겠습니까?\n\n삭제한 데이타는 복구할수 없습니다.')){
         document.location = 'banner_save.php?mode=ban_delete&idx=' + idx + '&code=' + code + '&page=<?=$page?>&<?=$menucodeParam?>';
       }
     }
     //-->
     </script>

			<?
			
			$sql = "select * from wiz_bannerinfo order by title";
			$result = query($sql) or error("sql error");
			$total = sql_fetch_row($result);

			$rows =50;
			$lists = 5;
			$page_count = ceil($total/$rows);
			if(!$page || $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;
			?>
			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">디자인관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">디자인을 수정합니다. 작업된 디자인 기능 외에는, 관리자 페이지에서 별도로 디자인을 추가하거나 삭제할 수 없습니다.</td>
        </tr>
      </table>
      
      <br>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="title_msg">총 디자인수 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
          <td align="right">
		    <?php if($wiz_admin['designer'] == "Y"){ ?>
			<input type="button" value="디자인생성" class="btnListchk3" onClick="document.location='banner_input.php?mode=ban_insert&<?=$menucodeParam?>';">
			<?php } ?>
		  </td>
        </tr>
        <tr><td height="3"></td></tr>
      </table> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
        	<th width="8%">번호</th>
          <th width="13%">디자인 그룹명</th>
          <th>컨텐츠명</th>
          <th width="13%">코드</th>
          <!--th width="13%">이미지</th-->
          <!-- <th width="10%">형태</th> -->
          <th width="10%">등록된 컨텐츠</th>
          <th width="13%">사용여부</th>
          <th width="20%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
				<?
	$sql = "SELECT banner.*, grp.grpname, ban.ban_cnt
		FROM (SELECT * FROM wiz_bannerinfo) AS banner
		LEFT JOIN wiz_banner_grp AS grp ON banner.grp = grp.idx
		LEFT JOIN (SELECT CODE, COUNT(*) AS ban_cnt FROM wiz_banner GROUP BY CODE) AS ban ON banner.code=ban.code
		ORDER BY grp.prior, grp.idx, banner.prior, banner.idx
		limit $start, $rows";
	$result = query($sql) or error("sql error");

	while($row = sql_fetch_obj($result)){

		if($row->types == "W") $row->types = "가로";
		else $row->types = "세로";

		if($row->isuse == "N") $row->isuse = "사용안함";
		else $row->isuse = "사용함";
				?>
		  	<tr> 
		  		<td align="center"><?=$no?></td>
		  <td align="center"><? if($row->grpname) echo $row->grpname; else echo "-";?></td>
          <td height="30"><a href="banner_input.php?mode=ban_update&idx=<?=$row->idx?>&page=<?=$page?>*<?=$menucodeParam?>"><?=$row->title?></a></td>
          <td align="center"><a href="banner_input.php?mode=ban_update&idx=<?=$row->idx?>&page=<?=$page?>&<?=$menucodeParam?>"><?=$row->code?></a></td>
          <td align="center"><?echo $row->ban_cnt ? $row->ban_cnt : "0"?></td>
          <!-- <td align="center"><?=$row->types?></td>
          <td align="center"><?=$row->types_num?></td> -->
          <td align="center"><?=$row->isuse?></td>
          <td align="center">
		  	  <input type="button" value="컨텐츠관리" class="base_btm gBlue" onClick="document.location='list.php?code=<?=$row->code?>&<?=$menucodeParam?>'">
			  <?php
			  if($wiz_admin['designer'] == "Y") {
			  ?>
		  	  <input type="button" value="수정" class="base_btm blue" onClick="document.location='banner_input.php?mode=ban_update&idx=<?=$row->idx?>&page=<?=$page?>&<?=$menucodeParam?>'">
		  	  <input type="button" value="삭제" class="base_btm gray" onClick="deleteBanner('<?=$row->idx?>', '<?=$row->code?>');">
			  <?php
				}
			  ?>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
	     <?
	     		$no--;
	      }
	                           
	    	if($total <= 0){
	    	?>
	    		<tr><td height="30" colspan="10" align="center">등록된 디자인이 없습니다.</td></tr>
	        <tr><td colspan="20" class="t_line"></td></tr>
	    	<?
	    	}
	      ?>
      </table>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td></td>
        </tr>
      </table>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td align="center"><? print_pagelist($page, $lists, $page_count, $menucodeParam); ?></td>
        </tr>
      </table>
      
<? include "../foot.php"; ?>