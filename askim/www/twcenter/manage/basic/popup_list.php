<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include "../head.php"; ?>


<script language="JavaScript" type="text/javascript">
<!--
function delContent(idx){
   if(confirm('해당팝업을 삭제하시겠습니까?')){
      document.location = "popup_save.php?mode=popup&mode=delete&idx=" + idx + "&menucode=<?=$menucode?>";
   }
}
//-->
</script>
</head>

      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">팝업관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">팝업을 추가/수정/삭제 합니다.</td>
        </tr>
      </table>
	  <br />
<div class="helpTip box">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="explain">
		- 팝업을 추가/수정/삭제할 수 있습니다.<br>
        - PC 또는 모바일 페이지가 별도로 제작된 경우, 사이트 전체/PC/모바일을 선택하여 팝업을 적용할 수 있습니다.<br />
        - 모바일에서는 위치 및 크기 조절이 불가합니다.<br />
		- 모바일에서 레이어 팝업 적용시, 모바일 최소 해상도(320px)에 맞게 크기가 작게 조정되어 표시되며, 일반 팝업에서는 크기 조절이 불가합니다.
	  </div>
	</div>
</div>
<br />			
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr><td colspan=20 class="t_rd"></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th>제목</th>
          <th width="20%">공지기간</th>
          <th width="15%">등록일</th>
          <th width="10%">기능</th>
        </tr>
        <tr><td colspan=20 class="t_rd"></td></tr>
      <?
      $sql = "select * from wiz_popup order by idx desc";
      $result = query($sql) or error("sql error");
      $total = sql_fetch_row($result);

      $rows = 12;
      $lists = 5;
    	$page_count = ceil($total/$rows);
    	if($page < 1 || $page > $page_count) $page = 1;
    	$start = ($page-1)*$rows;
    	$no = $total-$start;
    	
      $sql = "select * from wiz_popup order by idx desc limit $start, $rows";
      $result = query($sql) or error("sql error");
      while($row = sql_fetch_obj($result)){
      ?>
        <tr align="center"> 
          <td height="30" align="center"><?=$no?></td>
          <td><?=$row->title?></td>
          <td><?=$row->sdate?>~<?=$row->edate?></td>
          <td><?=$row->wdate?></td>
          <td>
            <img src="../image/btn_edit_s.gif" style="cursor:pointer"  onclick="document.location='popup_input.php?mode=update&idx=<?=$row->idx?>&page=<?=$page?>&menucode=<?=$menucode?>'">
            <img src="../image/btn_delete_s.gif" style="cursor:pointer" onclick="delContent('<?=$row->idx?>');">
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
      <?
      		$no--;
        }
        
        if($total <= 0){
      ?>
       <tr><td height="30" colspan="10" align="center">등록된 팝업이 없습니다.</td></tr>
       <tr><td height="1" colspan="10" bgcolor="EBEBEB"></td></tr>
      <?
        }
      ?>
      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="5"></td></tr>
      	<tr>
      		<!-- <td width="33%"><img src="../image/btn_popupadd.gif" style="cursor:pointer" onClick="document.location='popup_input.php'"></td> -->
			<td width="33%"><input type="button" value="팝업등록" class="btnListchk" onClick="document.location='popup_input.php?menucode=<?=$menucode?>'"></td>
      		<td width="33%"><? print_pagelist($page, $lists, $page_count, ""); ?></td>
      		<td width="33%"></td>
      	</tr>
      </table>


<? include "../foot.php"; ?>