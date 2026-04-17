<?
	if($perm_check["BANNER"] == false) error("권한이 없습니다.");

	$grplist = get_grplist("banner");
	$g_total = sizeof($grplist);

	$sql = "SELECT banner.code, banner.title, ifnull(grp.idx,0) as gidx, grp.grpname
		FROM wiz_bannerinfo AS banner
		LEFT JOIN wiz_banner_grp AS grp ON banner.grp = grp.idx
		where banner.isuse!='N'
		ORDER BY grp.prior, grp.idx, banner.prior, banner.idx";
	$res = query($sql);
	$total = sql_fetch_row($res);
	$banner_list = [];
	while($row = sql_fetch_arr($res)) {
		$banner_list[$row['gidx']]['grpname'] = $row['grpname'];
		$banner_list[$row['gidx']]['data'][] = $row;
	}
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td class="left_tit">디자인관리</td></tr>
	<tr> 
		<td> 
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table">
<? if($wiz_admin['designer'] == "Y"){ ?>
				<tr> 
					<th><a href="./banner_list.php?menucode=BANNER">디자인관리</a></th>
				</tr>
<? } ?>
				<tr> 
					<th class="ListTit">
						목록
						<p class="btns">
							<input type="button" value="모두 열기" class="btn_tree_expand open"><input type="button" value="모두 닫기" class="btn_tree_expand close">
						</p>
					
					</th>
				</tr>
<?
	if($total <= 0){
?>
	<tr>
			<td height="20" style="padding:10px 0 0 20px"><font color=red>등록된 디자인이<br>없습니다.</font></td>
		</tr>
<?
	} else {
		if($g_total > 0) {
?>
				<!-- <tr>
					<td align=right><input type="button" value="모두 열기" class="btn_tree_expand open"><input type="button" value="모두 닫기" class="btn_tree_expand close"></td>
				</tr> -->
<?		}

		foreach($banner_list as $gidx=>$banner) {
			$thisgrp = false;
			if(sizeof($banner['data']) > 0) {
				foreach($banner['data'] as $bdata) {
					if($bdata['code'] == $code) $thisgrp = true;
				}
			}
			$cls = (!$gidx) ? "nogrp" : "";
			$style = (!$gidx || $total < 10 || $thisgrp) ? " style='display:block'" : "";
?>
				<tr class="leftMenu">
					<td>
					<dl<?if($thisgrp) echo " class='on'";?>>
						<? if($gidx > 0) { ?><dt class="bbs_left_tit"><?=$banner['grpname']?></dt><? } ?>
						<dd class="depth_list <?=$cls?>"<?=$style?>>
<?			foreach($banner['data'] as $k=>$row) { ?>
							<p class="<? if($code==$row['code']) echo " clickover"?>"><a href="./list.php?code=<?=$row['code']?>&menucode=BANNER"<? if($code==$row['code']) echo " class=\"menu\"";?>><?=$row['title']?></a></p>
<?			} ?>
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
