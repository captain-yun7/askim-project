<?php
include $_SERVER["DOCUMENT_ROOT"]."/twcenter/common.php";
?>
<!-- 게시판 리스트 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="5" height="1" bgcolor="#333333"></td>
	</tr>
	<tr height="30">
		<td width="10%" class="table_tit">번호</td>
		<td class="table_tit">제목</td>
		<td width="10%"  class="table_tit">평점</td>
		<td width="10%" class="table_tit">작성자</td>
		<td width="15%" class="table_tit">작성일</td>
	</tr>
	<tr>
		<td colspan="5" height="1" bgcolor="#d7d7d7"></td>
	</tr>
	<?php
	$rows = 10;
	$lists = 5;

	$sql = " 
			select
				count(idx) as cnt
			from
				wiz_bbs
			where
				code = '".$review_code."'
				and prdcode = '".$prdcode."'
	";
	$row = sql_fetch($sql);
	$total = $row["cnt"];

	$page_count = ceil($total/$rows);
	if(!$rpage || $rpage > $page_count) $rpage = 1;
	$start = ($rpage-1)*$rows;
	$no = $total-$start;
	
	$sql = "
			select 
				*
				, DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y.%m.%d') as wdate 
			from 
				wiz_bbs 
			where 
				code = '$review_code' 
				and prdcode='$prdcode' 
			order by 
				prino desc 
			limit 
				$start, $rows
	";
	$result = query($sql);
	$Count = @sql_fetch_row($result);

	$write_pages = get_paging($rows, $rpage, $page_count, "javascript:ajax_paging('","')");
	for($i=0;$row = sql_fetch_arr($result);$i++){
		$catname=""; $re_space=""; $depno=""; $lock=""; $new=""; $hot="";
		$review_display = "none";
		
		$subject = "<a href=\"javascript:reviewShow('$no');\">".$row["subject"]."</a>";
		
		if($row["privacy"] == "Y"){
			$grp_sql = "
						select 
							count(idx) as cnt
						from 
							wiz_bbs 
						where 
							code='".$review_code."'
							and grpno='".$row["grpno"]."' 
							and passwd='".$passwd."' 
							and idx = '".$idx."'
			";
			$grp_row = sql_fetch($grp_sql);
			$grp_passwd = $grp_row["cnt"];
			if(
				($mem_level == "0" && !empty($wiz_session['id'])) ||											// 전체관리자
				($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
				($row["memid"] != "" && $row["memid"] == $wiz_session['id']) || 								// 자신의글
				($row["passwd"] != "" && $row["passwd"] == $passwd && !strcmp($idx, $row["idx"])) ||		// 비밀번호일치
				($wiz_session['id'] != "" && strpos($row["memgrp"],$wiz_session['id']) !== false) ||			// 그룹의글
				($grp_passwd > 0)																			// 그룹비번
			){
			}else{
				$subject = "<a href='".$purl."?ptype=passwd&mode=view&idx=".$row["idx"]."&code=".$review_code."&page=".$page."&".$param."'>".$row["subject"]."</a>";
			}

			$lock = "<img src='/twcenter/images/lock.gif' align='absmiddle'>";
		}
		
		$re_space = ""; 
		for($ii=0; $ii < $row["depno"]; $ii++) $re_space .= "&nbsp;&nbsp;";			// respace
		if($row["depno"] != 0) $depno = "<img src='/twcenter/images/re.gif' align='absmiddle'>";			// re
		
		$upfile_max = 12;
		for($ii = 1; $ii <= $upfile_max; $ii++) {
			if(img_type(WIZHOME_PATH."/data/bbs/$review_code/M".$row["upfile".$ii])) {
				${"upimg".$ii} = "<div align='".$bbs_info['img_align']."'><a href=javascript:openImg('".$row["upfile".$ii]."');><img src='/twcenter/data/bbs/$review_code/M".$row["upfile".$ii]."' border='0'></a></div>";
			}else{
				${"upimg".$ii} = "";
			}
		}
		
		if($row["ctype"] != "H")  $row["content"] = str_replace("\n", "<br>", $row["content"]);
		
		if($row["catname"] != "") $catname = "[".$row["catname"]."]";
	?>
	<tr height="40">
		<td align="center"><?=$no?></td>
		<td style="padding-left:10px;"><?=$catname?> <?=$re_space?><?=$depno?> <?=$subject?> <?=$lock?></td>
		<td align="center"><img src="/twcenter/images/icon_star_<?=$row["star"]?>.gif"></td>
		<td align="center"><?=$row["name"]?></td>
		<td align="center"><?=$row["wdate"]?></td>
	</tr>
	<tr>
		<td colspan="5" height="1" bgcolor="#d7d7d7"></td>
	</tr>
	<tr>
		<td colspan="5">
			<div id="review<?=$no?>" style="display:<?=$review_display?>" class="review_con">
				<? for($ii = 1; $ii <= $upfile_max; $ii++) echo ${"upimg".$ii} ?>
				<?=$row["content"]?>
			</div>
		</td>
	</tr>
	<?php
		$no--;
		$rows--;
	}
	
	if($total <= 0){
	?>
	<tr>
		<td align="center" colspan="5" height="50">등록된 게시물이 없습니다.</td>
	</tr>
	<tr>
		<td colspan="5" height="1" bgcolor="#d7d7d7"></td>
	</tr>
	<?php
	}
	?>
</table>

<!-- 페이지 번호 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="50" align="center">
			<?php
			if($Count > 0) echo $write_pages;
			?>
		</td>
	</tr>
</table>
<!-- 페이지 번호 끝 -->