<?
$day = "";
$first_day	= date("w",mktime(0,0,0,$month,1,$year)) + 1;  
$total_day = date("t", mktime(0, 0, 0, $month, 1, $year));

$start_month	= $year."-".$month."-01";
$last_month = $year."-".$month."-".$total_day;

$sql = "select *, from_unixtime(wdate, '%e') as day_idx from wiz_bbs where code='$code' and from_unixtime(wdate, '%Y-%m-%d') between '$start_month' and '$last_month' order by wdate";
$result = query($sql);

$day_bak = "";
while ($row = sql_fetch_arr($result)) {
	
	//$day_idx = str_replace("0","",substr($row['wdate'],8,2));
	$day_idx = $row['day_idx'];
	if($day_bak == $day_idx) $seq_idx++;
	else $seq_idx = 0;
	$day_bak = $day_idx;
	
	$schedule_list[$day_idx][$seq_idx]['subject'] = cut_str($row['subject'],100);
	$schedule_list[$day_idx][$seq_idx]['idx']     = $row['idx'];
	$schedule_list[$day_idx][$seq_idx]['depno']   = $row['depno'];
}

$hsql = " select * from wiz_holiday where (holiday_date between '".$start_month."' and '".$last_month."') and holiday_type = '공휴일' ";
$hres = query($hsql);
$holidays = array();
while($hrow = sql_fetch_arr($hres)) {
	$holidays[] = $hrow['holiday_date'];
}
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style account_calendar">
  <tr height="25" align="center">
    <td width="14%" class="t_name"><b><font color=red>Sun</font></b></td>
    <td width="14%" class="t_name"><b>Mon</b></td>
    <td width="14%" class="t_name"><b>Tue</b></td>
    <td width="14%" class="t_name"><b>Wed</b></td>
    <td width="14%" class="t_name"><b>Thu</b></td>
    <td width="14%" class="t_name"><b>Fri</b></td>
    <td width="14%" class="t_name"><b><font color=blue>Sat</font></b></td>
  </tr>
<?
if(($first_day > 5) && ((floor($total_day / 5) == 6) || (floor($total_day / 5) == 7))) {
	$line = 6;
	if($total_day == 30 && $first_day == 6)
	$line--;
} else if(($first_day == 1) && ($total_day == 28)) {
	$line = 4;
} else {
	$line = 5;
}

$k = 0;

for ($i=1; $i<=$line; $i++) {
	
	echo "<tr height=80>";
	
	for ($j=1; $j<=7; $j++) {
		
		if ((!$day) && ($j == $first_day)) $day = 1;

		$date_color = "";
		$hcss = "";
		if ($j == 7) $date_color = "blue";
		if ($j == 1) $date_color = "red";
		
		if ($day > 0){
			$tmp_day = $day;
			if(!strcmp($day, date('j'))) $day = "<b>".$day."</b>";

			$wdate = $year."-".$month."-".sprintf('%02d', $tmp_day);

			if(in_array($wdate, $holidays)){
				$date_color = "red";
				$hcss = "holiday";
			}

?>
				<td valign="top" bgcolor="#ffffff" class="verdana <?=$hcss?>">
					<a href="input.php?code=<?=$code?>&<?=$menucodeParam?>&wdate=<?=$year?>-<?=$month?>-<?=sprintf('%02d', $tmp_day)?>"><font size="3" color="<?=$date_color?>"><?=$day?></font></a><br>
					
					<!-- ### 온라인 예약 : 목록에 예약가능한 시간대로 예약버튼 / 예약 진행상황 아이콘이 표시되어야 합니다. ###-->
					<? if($code=="inquiry"){ ?>
						<!-- 예약 가능시 -->
						<p class="apply_cont"><span>10:00</span><input type="button" value="예약하기" class="btn_apply" onClick="document.location='../bbs/list.php?ptype=input&mode=insert&code=online';" /></p> 
						<!-- 예약 완료시 -->
						<p class="apply_cont"><span>10:00</span><span class="status apply">접수</span></p>
						<p class="apply_cont"><span>10:00</span><span class="status ok">승인</span></p>
						<p class="apply_cont"><span>10:00</span><span class="status cancel">취소</span></p>
						<p class="apply_cont"><span>10:00</span><span class="status end">완료</span></p> 
					<? } ?>
					<!-- // --->
					<?
						if(!empty($schedule_list[$tmp_day])) {
							for($k=0;$k<count($schedule_list[$tmp_day]);$k++){
							
								$re_space = "";
								if($schedule_list[$tmp_day][$k]['depno'] != 0) $re_icon  = "->";
								for($ii=0; $ii < $schedule_list[$tmp_day][$k]['depno']; $ii++) $re_space .= "&nbsp;&nbsp;";

					?>
					&nbsp;<?=$re_space?><?=$re_icon?><a href="view.php?code=<?=$code?>&<?=$menucodeParam?>&idx=<?=$schedule_list[$tmp_day][$k]['idx']?>"><?=$schedule_list[$tmp_day][$k]['subject']?></a><br>
					<?		}
						}
					?>
				</td>
<?
			$day = $tmp_day;
		} else {
			echo "<td bgcolor='#ffffff'></td>";
		}
		
		if ($day != $total_day) {
			if (($day > 0) && ($day < $total_day)) $day++;
		} else {
			$day = "&nbsp;";
		}
	}
	
	echo "</tr>";
}
		

?>
</table>