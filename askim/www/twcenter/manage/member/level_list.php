<? include_once "../../common.php"; ?>
<? include_once "../../inc/admin_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function delLevel(idx,level){

	if(confirm('회원등급을 삭제하시겠습니까?\n\n삭제할 등급에 속한 회원은 아래 등급으로 수정됩니다.')){
		document.location="level_save.php?mode=delete&idx=" + idx + "&level=" + level + "&<?=$menucodeParam?>";
	}
	
}

//-->
</script>
			
			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">등급관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">회원등급을 생성 관리합니다.</td>
        </tr>
      </table>
      
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td colspan=20 class="t_rd"></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th>등급명</th>
          <th width="15%">등급레벨</th>
          <th width="15%">회원보기</th>
		<?
		$menu_array=explode("/",$site_info['menu_use']);
		  if(in_array("PRODUCT",$menu_array)){
		?>
          <th width="15%">등급할인액(%)</th>
		<?}?>
          <th width="15%">기능</th>
        </tr>
        <tr><td colspan=20 class="t_rd"></td></tr>
        <tr> 
          <td height="30" align="center">1</td>
          <td align="center">관리자</td>
          <td align="center">0</td>
          <td align="center"><input type="button" value="회원보기" class="base_btm reg" onclick="document.location='../basic/admin_list.php'" style="width:70px;" /></td>
		
		<?
		  if(in_array("PRODUCT",$menu_array)){
		?>
		<td align="center"><a href="../basic/admin_list.php">0%</a></td>
        <?}?>  
		  <td align="center"><a href="../basic/admin_list.php">관리자목록</a></td>
        </tr>
        <tr><td height="1" colspan="10" bgcolor="EBEBEB"></td></tr>
   	<?
   	$sql = "select * from wiz_level order by level asc, idx asc";
   	$result = query($sql) or error("sql error");
   	$total = sql_fetch_row($result);
   	$no = 2;
   	while($row = sql_fetch_obj($result)){
   		
   		$row->permi = str_replace("00/","관리자(인트라넷)접근 / ",$row->permi);
   		$row->permi = str_replace("01/","환경설정 / ",$row->permi);
   		$row->permi = str_replace("02/","기본정보 / ",$row->permi);
   		$row->permi = str_replace("03/","사내업무 / ",$row->permi);
   		$row->permi = str_replace("04/","회원관리 / ",$row->permi);
   		$row->permi = str_replace("05/","게시판관리 / ",$row->permi);
   		$row->permi = str_replace("06/","마케팅분석 / ",$row->permi);

		if($row->distype == "W") $row->distype = "원";
		else  $row->distype = "% ";
   		
   	?>
       <tr class="colTbl"> 
          <td height="35" align="center"><?=$no?></td>
          <td align="center"><?=$row->name?></td>
          <td align="center"><?=$row->level?></td>
          <td align="center"><input type="button" value="회원보기" class="base_btm reg" onclick="document.location='member_list.php?slevel=<?php echo $row->idx ?>'" style="width:70px;" /></td>
          <?
	 	  if(in_array("PRODUCT",$menu_array)){
		  ?>
		  <td align="center"><?=$row->discount?><?=$row->distype?></td>
		  <?}?>
		  <td align="center">
            <img src="../image/btn_edit_s.gif" style="cursor:hand" onclick="document.location='level_input.php?mode=update&idx=<?=$row->idx?>&<?=$menucodeParam?>';">
            <img src="../image/btn_delete_s.gif" style="cursor:hand" onclick="delLevel('<?=$row->idx?>','<?=$row->level?>');">
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
     <? 
     		$no++;
      }
                           
    	if($total <= 0){
    	?>
    		<tr><td height="30" colspan="10" align="center">등록된 등급이 없습니다.</td></tr>
        <tr><td colspan="20" class="t_line"></td></tr>
    	<?
    	}
      ?>
      </table>
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr><td height="5"></td></tr>
				<tr>
					<td>
						<!-- <img src="../image/btn_addlevel.gif" style="cursor:hand" onClick="document.location='level_input.php?mode=insert';"> -->
						<input type="button" value="회원등급추가" class="btnListchk" onclick="document.location='level_input.php?mode=insert&<?=$menucodeParam?>';">
					</td>
				</tr>
			</table>


<div class="helpTip">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="explain">
		- 등급레벨 숫자가 클수록 낮은 등급입니다.<br />
		- 가장 낮은 등급은 회원가입시 자동으로 해당 등급으로 설정되기에 삭제시 주의바랍니다. <br />
		&nbsp; ex) 일반회원 &gt; VIP &gt; VVIP 등급이 있을 경우 일반회원등급을 삭제된 상태에서 회원가입시 자동으로 VIP 등급이 됩니다. 
	  </div>
</div>
			
<? include "../foot.php"; ?>