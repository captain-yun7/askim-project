<? include_once "../../common.php"; ?>
<? include_once "../../inc/admin_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">회원통계</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">회원을 여러가지 조건으로 분석합니다.</td>
	</tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 등급별통계</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td colspan=20 class="t_rd"></td></tr>
	<tr class="t_th">
		<th width="15%">등급</td>
		<th width="15%">회원수</td>
		<th>그래프</td>
	</tr>
	<tr><td colspan=20 class="t_rd"></td></tr>
	<?
	$result = sql_fetch("select count(*) as cnt from wiz_member");
	$member_all = $result['cnt'];
	if($member_all <= 0) $member_all = 1;
			
	$result = query("select idx,level,name from wiz_level order by level asc");
	while($row = sql_fetch_obj($result)){
		
		$m_result = sql_fetch("select count(*) as cnt from wiz_member where level = '{$row->idx}'");
		$member_cnt = $m_result['cnt'];
		$member_percent = ($member_cnt/$member_all)*100;
		$per_member_percent = $member_percent."%";
	?>
	<tr> 
		<td height="35" align="center"><?=$row->name?></td>
		<td align="center"><?=$member_cnt?> 명</td>
		<td>
			<table width="<?php echo $per_member_percent ?>%" height="28" cellspacing="3" cellpadding="0" border="0">
				<tr>
					<td></td>
					<td style="border: 1px solid #1d7cd4; background-color:#2DA7FE; padding:0 3px; color:#fff"><?php echo $per_member_percent ?></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
	</tr>
</table>

<?php if($info_use['sex'] == true){ ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 성별통계</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td colspan=20 class="t_rd"></td></tr>
	<tr class="t_th">
		<th width="15%">성별</td>
		<th width="15%">회원수</td>
		<th>그래프</td>
	</tr>
	<tr><td colspan=20 class="t_rd"></td></tr>
	<?
	$rs = sql_fetch("select count(*) as sex from wiz_member where sex='M'");
	$man_total = $rs['sex'];
	if($man_total == "") $man_total = 0;

	$rs = sql_fetch("select count(*) as sex from wiz_member where sex='F'");
	$woman_total = $rs['sex'];
	if($woman_total == "") $woman_total = 0;

	$total = $man_total + $woman_total;
	if($total == 0) $total = 1;
	$man_percent = ($man_total/$total)*100;
	$woman_percent = ($woman_total/$total)*100;

	$man_percent = substr($man_percent,0,strpos($man_percent,'.')+3);
	$woman_percent = substr($woman_percent,0,strpos($woman_percent,'.')+3);

	$per_man_percent = $man_percent."%";
	$per_woman_percent = $woman_percent."%";

	?>
	<tr> 
		<td height="35" align="center">남</td>
		<td align="center"><?=$man_total?> 명</td>
		<td>
			<table width="<?php echo $per_man_percent ?>%" height="28" cellspacing="3" cellpadding="0" border="0">
				<tr>
					<td></td>
					<td style="border: 1px solid #1d7cd4; background-color:#2DA7FE; padding:0 3px; color:#fff"><?php echo $per_man_percent ?></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<tr> 
		<td height="35" align="center">여</td>
		<td align="center"><?=$woman_total?> 명</td>
		<td>
			<table width="<?php echo $per_woman_percent ?>%" height="28" cellspacing="3" cellpadding="0" border="0">
				<tr>
					<td></td>
					<td style="border: 1px solid #1d7cd4; background-color:#2DA7FE; padding:0 3px; color:#fff"><?php echo $per_woman_percent ?></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
</table>
<?php } ?>

<?php if($info_use['birthday'] == true){ ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 연령통계</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td colspan=20 class="t_rd"></td></tr>
		<tr class="t_th">
		<th width="15%">연령</td>
		<th width="15%">회원수</td>
		<th>그래프</th>
	</tr>
	<tr><td colspan=20 class="t_rd"></td></tr>
	<?
	$now_year = date('y')+101;
	//echo $now_year;
	$sql = "select birthday,substring(($now_year-substring(birthday,3,2)),1,1) as age, count(id) as memcnt from wiz_member group by substring(($now_year-substring(birthday,3,2)),1,1);";
	$result = query($sql);
	$total   = 0;
	$percent = 0;
	for($ii=1;$ii<=8;$ii++) $arr_age[$ii] = 0;
	while($row = sql_fetch_obj($result)){

		$birthday = str_replace("-","",$row->birthday);
		if($birthday > 0){
			$arr_age[$row->age] = $row->memcnt;
		} else {
			$arr_age[$row->age] = 0;
		}
		$total += $row->memcnt;

	}
	if($total == 0) $total = 1;

	$percent = "";
	for($i=1; $i<=8; $i++){
		$percent .= (int)(($arr_age[$i]/$total)*100)."|";
	}

	?>
	<?php
	$percent_cnt = explode("|", $percent);
	for($j=0; $j<count($percent_cnt)-1; $j++){
		$k = $j + 1;
		$age_percent = substr($percent_cnt[$j],0,strpos($percent_cnt[$j],'.')+3);
	?>
	<tr>
		<td height="35" align="center"><?php echo $k ?>0대</td>
		<td align="center"><?=$arr_age[$k]?> 명</td>
		<td>
			<table width="<?php echo $age_percent ?>%" height="28" cellspacing="3" cellpadding="0" border="0">
				<tr>
					<td></td>
					<td style="border: 1px solid #1d7cd4; background-color:#2DA7FE; padding:0 3px; color:#fff"><?php echo $age_percent ?>%</td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?php } ?>

</table>
<?php } ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 지역별통계</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td colspan=20 class="t_rd"></td></tr>
	<tr class="t_th">
		<th width="15%">지역</th>
		<th width="15%">회원수</th>
		<th>그래프</th>
	</tr>
	<tr><td colspan=20 class="t_rd"></td></tr>
	<?
	$sql = "select substring(address1,1,2) as address, count(address1) areatotal from wiz_member group by substring(address1,1,2) order by areatotal";
	$result = query($sql);
	$total = 0;
	while($row = sql_fetch_obj($result)){
		$arr_address[$row->address] = $row->areatotal;
		$total += $row->areatotal;
	}
	if($total == 0) $total = 1;

	$area_array = array('서울','경기','인천','대전','대구','광주','울산','부산','제주','강원','경북','경남','전북','전남','충북','충남','세종');
	
?>
	<?php
	$array_address     = array();
	$location_percent  = 0;
	$location_percent2 = "";
	foreach($area_array as $key=>$area) {

		$array_address        = ($arr_address[$area] > 0) ? $arr_address[$area] : 0;
		$location_percent   = ($arr_address[$area]/$total)*100;
		$location_percent2  = substr($location_percent,0,strpos($location_percent,'.')+3)."%";

	?>
	<tr>
		<td height="35" align="center"><?php echo $area ?></td>
		<td align="center"><?php echo $array_address?> 명</td>
		<td>
			<table width="<?php echo $location_percent ?>%" height="28" cellspacing="3" cellpadding="0" border="0">
				<tr>
					<td></td>
					<td style="border: 1px solid #1d7cd4; background-color:#2DA7FE; padding:0 3px; color:#fff"><?php echo $location_percent2 ?></td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?php } ?>

</table>

<? include "../foot.php"; ?>