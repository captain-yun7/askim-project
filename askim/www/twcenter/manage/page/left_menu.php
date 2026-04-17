<?
	if($perm_check["PAGE"] == false) error("권한이 없습니다.");
	$grplist = get_grplist("page");
	$g_total = sizeof($grplist);

	$sql = "SELECT page.idx, page.code, page.title, ifnull(grp.idx,0) as gidx, grp.grpname
		FROM wiz_page AS page
		LEFT JOIN wiz_page_grp AS grp ON page.menu = grp.idx
		ORDER BY grp.prior, grp.idx, page.prior, page.idx";
	$res = query($sql);
	$total = sql_fetch_row($res);
	$page_list = [];
	while($row = sql_fetch_arr($res)) {
		$page_list[$row['gidx']]['grpname'] = $row['grpname'];
		$page_list[$row['gidx']]['data'][] = $row;
	}
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td class="left_tit">페이지관리</td></tr>
	<tr> 
		<td> 
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table">
				<tr> 
					<th><a href="./page_list.php?menucode=PAGE">페이지관리</a></th>
				</tr>
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
						<td height="20" style="padding:10px 0 0 20px"><font color=red>등록된 페이지가<br>없습니다.</font></td>
					</tr>
				<?
				} else {
					if($g_total > 0) { 
				?>
				<?
					}
					foreach($page_list as $gidx=>$pages) {
						$thisgrp = false;
						if(strpos($PHP_SELF, "page_input.php") !== false && $idx && sizeof($pages['data']) > 0) {
							foreach($pages['data'] as $data) {
								if($data['idx'] == $idx) $thisgrp = true;
							}
						}
						$cls = (!$gidx) ? "nogrp" : "";
						$style = (!$gidx || $total < 5 || $thisgrp) ? " style='display:block'" : "";
?>
				<tr class="leftMenu">
					<td>
					<dl<?if($thisgrp) echo " class='on'";?>>
						<? if($gidx > 0) { ?><dt class="bbs_left_tit"><?=$pages['grpname']?></dt><? } ?>
						<dd class="depth_list <?=$cls?>"<?=$style?>>
<?						foreach($pages['data'] as $k=>$row) { ?>
							<p class="<? if($idx==$row['idx']) echo " clickover"?>"><a href="./page_input.php?mode=update&idx=<?=$row['idx']?>&menucode=PAGE"<? if($idx==$row['idx']) echo " class=\"menu\"";?>><?=$row['title']?></a></p>
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