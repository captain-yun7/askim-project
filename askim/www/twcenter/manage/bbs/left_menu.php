<?
	if($perm_check["BBS"] == false) error("권한이 없습니다.");

	$grplist = get_grplist("bbs");
	$g_total = sizeof($grplist);

	if($menu_arr["PRODUCT"] == true){
		$bbs_sql = " (bbs.type='BBS' and (bbs.type2='SHOP' or bbs.type2 IS NULL))";
	} else {
		$bbs_sql = " (bbs.type='BBS' and bbs.type2 IS NULL)";
	}
	$sql = "SELECT bbs.code, bbs.title, bbs.prior, IFNULL(grp.idx,0) AS gidx, grp.grpname
		FROM wiz_bbsinfo AS bbs
		LEFT JOIN wiz_bbs_grp AS grp ON bbs.grp = grp.idx
		where $bbs_sql
		ORDER BY grp.prior, grp.idx, bbs.prior";
	$res = query($sql);
	$total = sql_fetch_row($res);
	$bbs_list = [];
	while($row = sql_fetch_arr($res)) {
		$bbs_list[$row['gidx']]['grpname'] = $row['grpname'];
		$bbs_list[$row['gidx']]['data'][] = $row;
	}
?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td class="left_tit">게시판관리</td></tr>
				<tr>
					<td>
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table">
							<tr> 
								<th><a href="./bbs_list.php?menucode=BBS">게시판관리</a></th>
							</tr>
							<tr class="leftMenu">
								<td <? if(strpos($PHP_SELF, 'total_bbs_list') !== false) echo "class='clickover'"?>><a href="./total_bbs_list.php?menucode=BBS" class="menu">게시물통합관리</a></td>
							</tr>
							<!-- <tr class="leftMenu borderBtm">
								<td <? if(strpos($PHP_SELF, 'total_comment_list') !== false) echo "class='clickover'"?>><a href="./total_comment_list.php?menucode=BBS" class="menu">코멘트통합관리</a></td>
							</tr> -->
							<tr>
								<th class="ListTit">목록
								<p class="btns">
									<input type="button" value="모두 열기" class="btn_tree_expand open"><input type="button" value="모두 닫기" class="btn_tree_expand close">
								</p>
								</th>
							</tr>
							<?
							if($total <= 0){
							?>
							<tr>
									<td height="20" style="padding-left:20px"><font color=red>등록된 게시판이<br>없습니다.</font></td>
								</tr>
							<?
							} else {
					if($g_total > 0) { 
				?>

				<?
					}
					foreach($bbs_list as $gidx=>$bbs) {
						$thisgrp = false;
						if(strpos($PHP_SELF, "list.php") !== false && $code && sizeof($bbs['data']) > 0) {
							foreach($bbs['data'] as $data) {
								if($data['code'] == $code) $thisgrp = true;
							}
						}
						$cls = (!$gidx) ? "nogrp" : "";
						$style = (!$gidx || $total < 5 || $thisgrp) ? " style='display:block'" : "";
?>
				<tr class="leftMenu">
					<td>
					<dl<?if($thisgrp) echo " class='on'";?>>
						<? if($gidx > 0) { ?><dt class="bbs_left_tit"><?=$bbs['grpname']?></dt><? } ?>
						<dd class="depth_list <?=$cls?>"<?=$style?>>
<?						foreach($bbs['data'] as $k=>$row) { ?>
							<p class="<? if($code==$row['code']) echo " clickover"?>"><a href="./list.php?code=<?=$row['code']?>&menucode=BBS"<? if($code==$row['code']) echo " class=\"menu\"";?>><?=$row['title']?></a></p>
<?						} ?>
						</dd>
					</dl>
					</td>
				</tr>
<?
				}
			}
?>
						</table>
					</td>
				</tr>
			</table>





