<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>


<script language="JavaScript" type="text/javascript">
<!--
function delAdmin(admin_id){
   if(confirm('해당관리자를 삭제하시겠습니까?')){
      document.location = "twcenter_save.php?mode=site_admin&mode=delete&admin_id=" + admin_id + "&menucode=<?=$menucode?>";
   }
}

function unlockAdm(admin_id){
	if(confirm('해당 관리자 로그인 차단을 해제하시겠습니까?')){
		document.location = "twcenter_save.php?mode=unlock&admin_id=" + admin_id + "&menucode=<?=$menucode?>";
	}
}

//-->
</script>
</head>

	  <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">관리자목록</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">관리자를 추가/수정/삭제 합니다.</td>
        </tr>
      </table>
      
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=10></td></tr>
        <tr class="t_th">
          <th width="5%">번호</td>
          <th width="10%">등급</td>
          <th width="10%">아이디</td>
          <th>성명</td>
          <th width="15%">마지막접속</td>
		  <?php if($site_info['login_limit_use'] == "Y"){ ?>
			<th width="10%">로그인실패</td>
		  <?php } ?>
          <th width="20%">등록일</td>
          <th width="10%">기능</td>
        </tr>
        <tr><td class="t_rd" colspan=10></td></tr>
      <?
	  $sql_lev = "select * from wiz_admin_lev order by idx";
	  $res_lev = query($sql_lev);
	  $adm_lev_list = array("10000"=>"슈퍼관리자");
	  while($row_lev = sql_fetch_arr($res_lev)) {
		  $adm_lev_list[$row_lev['idx']] = $row_lev['name'];
	  }

      $sql = "select * from wiz_admin order by wdate desc";
      $result = query($sql) or error("sql error");
      $total = sql_fetch_row($result);

      $rows = 12;
      $lists = 5;
    	$page_count = ceil($total/$rows);
    	if($page < 1 || $page > $page_count) $page = 1;
    	$start = ($page-1)*$rows;
    	$no = $total-$start;
    	
      $sql = "select * from wiz_admin order by wdate desc limit $start, $rows";
      $result = query($sql) or error("sql error");
      
      while($row = sql_fetch_arr($result)){
      ?>
        <tr align="center"> 
          <td height="30" align="center"><?=$no?></td>
          <td><?=$adm_lev_list[$row['lev']]?></td>
          <td><?=$row['id']?></td>
          <td><?=$row['name']?></td>
          <td><?=$row['last']?></td>
		  <?php if($site_info['login_limit_use'] == "Y"){ ?>
	          <td><?=$row['login_fail_count']?></td>
		  <?php } ?>
          <td><?=$row['wdate']?></td>
          <td>
		  <? if($wiz_admin['designer'] == "Y" || $row['id'] != "twcenter" || $wiz_admin['id'] == "twcenter") { ?>
            <img src="../image/btn_edit_s.gif" style="cursor:pointer" onclick="document.location='twcenter_input.php?mode=update&id=<?=$row['id']?>&page=<?=$page?>&menucode=<?=$menucode?>'">
            <img src="../image/btn_delete_s.gif" style="cursor:pointer" onclick="delAdmin('<?=$row['id']?>');">
		  <? } ?>
			<?php
				if($site_info['login_limit_use'] == "Y" && ($row['login_fail_count'] >= $site_info['login_limit_count'])){?>
					<input type="button" value="차단해제" class="base_btm btn-admunlock" onclick="unlockAdm('<?=$row['id']?>')">
			<?php
				}
			?>
          </td>
        </tr>
        <tr><td colspan="8" class="t_line"></td></tr>
      <?
      		$no--;
        }
        if($total <= 0){
      ?>
        <tr><td height="30" colspan="10" align="center">등록된 관리자가 없습니다.</td></tr>
        <tr><td height="1" colspan="10" bgcolor="EBEBEB"></td></tr>
      <?
        }
      ?>
      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="5"></td></tr>
      	<tr>
      		<!-- <td width="33%"><img src="../image/btn_adminadd.gif" style="cursor:hand" onClick="document.location='admin_input.php?mode=insert';"></td> -->
			<td width="33%"><input type="button" value="관리자등록" class="btnListchk" onClick="document.location='twcenter_input.php?mode=insert&menucode=<?=$menucode?>';"></td>
      		<td width="33%"><? print_pagelist($page, $lists, $page_count, ""); ?></td>
      		<td width="33%"></td>
      	</tr>
      </table>
                             
<? include "../foot.php"; ?>