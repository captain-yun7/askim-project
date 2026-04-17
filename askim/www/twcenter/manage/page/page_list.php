<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include "../head.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function delContent(idx){
   if(confirm('해당페이지를 삭제하시겠습니까?')){
      document.location = "page_save.php?mode=popup&mode=delete&idx=" + idx + "&<?=$menucodeParam?>";
   }
}
//-->
</script>
</head>
<?
$sql = "select count(*) as total from wiz_page";
$row = sql_fetch($sql);
$total = $row['total'];
?>
		<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">페이지관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">페이지를 수정합니다. 작업된 페이지 기능 외에는, 관리자 페이지에서 별도로 페이지를 추가하거나 삭제할 수 없습니다.</td>
        </tr>
      </table>
      
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><span class="title_msg">총 등록페이지 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
          <td align="right">
			<? if($wiz_admin['designer'] == "Y"){ ?>
		  <!-- <img src="../image/btn_pageadd.gif" style="cursor:hand" onClick="document.location='page_input.php'"> -->
			<input type="button" value="페이지등록" class="btnListchk3" onClick="document.location='page_input.php?<?=$menucodeParam?>'">
		  <? } ?>
		  </td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th width="10%">코드</th>
          <th>페이지</th>
          <th>주소</th>
          <th width="15%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
      <?

      $rows = 20;
      $lists = 5;
    	$page_count = ceil($total/$rows);
    	if($page < 1 || $page > $page_count) $page = 1;
    	$start = ($page-1)*$rows;
    	$no = $total-$start;
    	
      $sql = "select page.*, grp.grpname
		from wiz_page as page
		left join wiz_page_grp as grp on page.menu=grp.idx
		ORDER BY grp.prior, grp.idx, page.prior, page.idx
		limit $start, $rows";
      $result = query($sql) or error("sql error");
      
      while($row = sql_fetch_obj($result)){
			if(empty($row->menu)) $page_grp_list[$row->menu] = "-";
      ?>
        <tr> 
          <td height="30" align="center"><?=$no?></td>
          <td align="center"><?=$row->code?></td>
          <td><? echo ($row->grpname) ? $row->grpname : "-";?> &gt; <?=$row->title?></td>
          <td><a href="http://<?=$HTTP_HOST?>/<?=$row->url?>" target="_blank">http://<?=$HTTP_HOST?>/<?=$row->url?></a></td>
          <td align="center">
            <img src="../image/btn_edit_s.gif" style="cursor:hand" onclick="document.location='page_input.php?mode=update&idx=<?=$row->idx?>&page=<?=$page?>&<?=$menucodeParam?>'">
            <img src="../image/btn_delete_s.gif" style="cursor:hand" onclick="delContent('<?=$row->idx?>');">
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
      <?
      		$no--;
        }
        
        if($total <= 0){
      ?>
       <tr><td height="30" colspan="10" align="center">등록된 페이지가 없습니다.</td></tr>
       <tr><td colspan="20" class="t_line"></td></tr>
      <?
        }
      ?>
      </table>
	  <br>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
      	<tr><td height="5"></td></tr>
        <tr> 
          <td><? print_pagelist($page, $lists, $page_count, $menucodeParam); ?></td>
        </tr>
      </table>

<? include "../foot.php"; ?>