<? include_once "../../common.php"; ?>
<? include_once "../../inc/admin_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
$param = "searchopt=$searchopt&searchkey=$searchkey&".$menucodeParam;
?>
<? include "../head.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--

// 탈퇴회원 삭제
function delMemout(idx){
	if(confirm('삭제하시겠습니까?')){
		document.location = 'member_save.php?mode=memoutdel&idx=' + idx + "&<?=$menucodeParam?>";
	}
}

//-->
</script>
</head>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">탈퇴회원</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">탈퇴회원을 조회 관리합니다.</td>
        </tr>
      </table>

      <br>
      <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td bgcolor="ffffff">
          <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
          <tr>
          <td width="15%" class="t_name">조건검색</td>
          <td width="85%" class="t_value">

          	<table border="0" cellspacing="0" cellpadding="1">
          	<tr>
          	<td>
            <select name="searchopt" class="select">
            <option value="name" <? if($searchopt == "name") echo "selected"; ?>>고객명
            <option value="memid" <? if($searchopt == "memid") echo "selected"; ?>>아이디
            </select>&nbsp;
            </td>
            <td>
            <input type="text" name="searchkey" value="<?=$searchkey?>" class="input">
            </td>
            </table>
            <script language="javascript">
            searchopt = document.searchForm.searchopt;
            for(ii=0; ii<searchopt.length; ii++){
              if(searchopt.options[ii].value == "<?=$searchopt?>")
                searchopt.options[ii].selected = true;
            }
            </script>
          </td>
          </table>
          </td>
        </tr>
      </table>
	  <br>
		<table width="100%" cellspacing="1" cellpadding="3" border="0">
			<tr>
				<td align="center">
					<input type="submit" value="검색" class="search_btn2">&nbsp;
					<input type="button" value="전체회원" class="search_default" onclick="location.href='<?=$PHP_SELF?>?<?=$menucodeParam?>'">
				</td>
			</tr>
		</table>
	  </form>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
      </table>
      <?

        if($searchkey != "") $searchkey_sql = " and $searchopt like '%$searchkey%' ";
      	$sql = "select idx,memid,name,subject,content,from_unixtime(wdate, '%Y-%m-%d') as wdate from wiz_bbs where code = '[memout]' $searchkey_sql order by idx desc";
      	$result = query($sql) or error("sql error");
      	$total = sql_fetch_row($result);

        $rows = 20;
        $lists = 5;
       	$page_count = ceil($total/$rows);
       	if(!$page || $page > $page_count) $page = 1;
       	$start = ($page-1)*$rows;
       	$no = $total-$start;

      ?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td colspan=20 class="t_rd"></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th>회원명</th>
          <th>탈퇴사유</th>
          <th>충고내용</th>
          <th width="10%">탈퇴일</th>
          <th width="10%">기능</th>
        </tr>
        <tr><td colspan=20 class="t_rd"></td></tr>
			<?
      	$sql = "select idx,memid,name,subject,content,from_unixtime(wdate, '%Y-%m-%d') as wdate from wiz_bbs where code = '[memout]' $searchkey_sql order by idx desc limit $start, $rows";
      	$result = query($sql) or error("sql error");

			while($row = sql_fetch_obj($result)){
				$row->content = str_replace("\n","",$row->content);
			?>
        <tr>
          <td align="center" height="38"><?=$no?></td>
          <td align="center"><?=$row->name?> (<?=$row->memid?>)</td>
          <td align="center"><?=$row->subject?></td>
          <td align="center"><?=$row->content?></td>
          <td align="center"><?=$row->wdate?></td>
          <td align="center"><img src="../image/btn_delete_s.gif" style="cursor:hand" onclick="delMemout('<?=$row->idx?>');"></td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
     	<?
     		$no--;
      }

    	if($total <= 0){
      ?>
    		<tr><td height=30 colspan=10 align=center>탈퇴회원이 없습니다.</td></tr>
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