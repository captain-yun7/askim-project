<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>


<script language="JavaScript" type="text/javascript">
<!--
function del(idx){
   if(confirm('선택하신 관리자 등급을 삭제하시겠습니까?')){
      document.location = "twcenter_save.php?mode=level_delete&idx=" + idx + "&menucode=<?=$menucode?>";
   }
}

//-->
</script>
</head>

			
			
			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">관리자등급관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">관리자 등급을 추가/수정/삭제 합니다.</td>
        </tr>
      </table>
      
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=7></td></tr>
        <tr class="t_th">
          <th width="8%">번호</td>
          <th width="15%">등급명</td>
          <th>접근권한</td>
          <th width="10%">기능</td>
        </tr>
        <tr><td class="t_rd" colspan=7></td></tr>
        <tr align="center"> 
          <td height="30" align="center">1</td>
          <td>슈퍼관리자</td>
          <td>모든 기능</td>
          <td></td>
        </tr>
        <tr><td colspan="7" class="t_line"></td></tr>
      <?
      $sql = "select * from wiz_admin_lev order by idx asc";
      $result = query($sql) or error("sql error");
	  $total = sql_fetch_row($result);

	  $no = 2;      
      while($row = sql_fetch_arr($result)){
      ?>
        <tr align="center"> 
          <td height="30" align="center"><?=$no?></td>
          <td><?=$row['name']?></td>
          <td><?=implode("/", twcenter_perm_list($row['permi']))?></td>
          <td>
            <img src="../image/btn_edit_s.gif" style="cursor:pointer" onclick="document.location='twcenter_level_input.php?mode=level_update&idx=<?=$row['idx']?>&page=<?=$page?>&menucode=<?=$menucode?>'">
            <img src="../image/btn_delete_s.gif" style="cursor:pointer" onclick="del('<?=$row['idx']?>');">
          </td>
        </tr>
        <tr><td colspan="7" class="t_line"></td></tr>
      <?
      		$no++;
        }
      ?>
      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="5"></td></tr>
      	<tr>
			<td><input type="button" value="관리자 등급 추가" class="btnListchk" onClick="document.location='twcenter_level_input.php?mode=level_insert&menucode=<?=$menucode?>';"></td>
      	</tr>
      </table>
                             
<? include "../foot.php"; ?>