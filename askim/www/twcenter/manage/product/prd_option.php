<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>

<script language="javascript">
<!--
function openOption(mode,idx,menucode){
	var url = "option_input.php?mode=" + mode + "&idx=" + idx + "&<?=$menucodeParam?>";
  window.open(url,"","height=370, width=400, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");
}
function delOption(idx,menucode){
	if(confirm('옵션을 삭제하시겠습니까?')){
		document.location="option_save.php?mode=delete&idx=" + idx + "&<?=$menucodeParam?>";
	}
}
-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">옵션항목관리</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">옵션항목을 설정합니다.</td>
			  </tr>
			</table>
			
			<br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 옵션목록</td>
			    <td align="right">
					<!-- <img src="../image/btn_optionadd.gif" style="cursor:hand" onClick="openOption('insert','');"> -->
					<input type="button" value="옵션등록" class="btnListchk3" onClick="openOption('insert','');">
				</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr><td class="t_rd" colspan="20"></td></tr>
        <tr class="t_th">
          <th width="8%">No</th>
          <th width="20%">옵션명</th>
          <th>옵션항목</th>
          <th width="10%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan="20"></td></tr>
        <?
        $sql = "select idx from wiz_option order by idx desc";
        $result = query($sql) or error("sql error");
        
        $total = sql_fetch_row($result);

        $rows = 20;
        $lists = 5;

				$page_count = ceil($total/$rows);
				if(!$page || $page > $page_count) $page = 1;
				$start = ($page-1)*$rows;
				$no = $total-$start;
				
        $sql = "select * from wiz_option order by idx desc limit $start, $rows";
        $result = query($sql) or error("sql error");
        
        while(($row = sql_fetch_obj($result)) && $rows){
        ?>
        <tr> 
          <td  align="center"><?=$no?></td>
          <td height="30" align="center"><?=$row->opttitle?></td>
          <td height="30" align="center"><?=$row->optcode?></td>
          <td>
          <img src="../image/btn_edit_s.gif" style="cursor:hand" onClick="openOption('update','<?=$row->idx?>')">&nbsp;
          <img src="../image/btn_delete_s.gif" style="cursor:hand" onClick="delOption('<?=$row->idx?>')">
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
        <?
     		$no--;
         $rows--;
         }
                              
       	if($total <= 0){
       	?>
       		<tr><td height='30' colspan=7 align=center>등록된 옵션항목이 없습니다.</td></tr>
       		<tr><td colspan="20" class="t_line"></td></tr>
       	<?
       	}
        ?>
      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="15"></td></tr>
      	<tr>
      		<td width="33%"></td>
      		<td width="33%"><? print_pagelist($page, $lists, $page_count, $menucodeParam); ?></td>
      		<td width="33%"></td>
      	</tr>
     	</table>

		<br>
		<div class="helpTip">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="title"></div>
	  <div class="explain">
		- 상품등록시 옵션목록 가져오기를 통하여 쉽게 옵션을 등록할 수 있습니다.
	  </div>
	</div>
</div>
<? include "../foot.php"; ?>