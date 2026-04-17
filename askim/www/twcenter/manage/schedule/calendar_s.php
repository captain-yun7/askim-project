  <?=$year?>-<?=$month?>
  <table border="0" cellspacing="1" cellpadding="0" bgcolor="#bdbdbd">
    <tr>
      <td align="center">일</td>
      <td align="center">월</td>
      <td align="center">화</td>
      <td align="center">수</td>
      <td align="center">목</td>
      <td align="center">금</td>
      <td align="center">토</td>
    </tr>
<?
	$day = "";
	$schedule_list = "";
	$FirstDayPosition	= date("w",mktime(0,0,0,$month,1,$year)) + 1;  
	$TotalDay			= date("t", mktime(0, 0, 0, $month, 1, $year));
	
	$StartMonth	= $year."-".$month."-01 00:00:00";
	$LastMonth	= $year."-".$month."-".$TotalDay." 23:59:59";
	
	$sql = "select *,count(idx) as cnt from wiz_schedule ";
	$sql = $sql."where sdate between '$StartMonth' and '$LastMonth' ";
	$sql = $sql."and content != '' ";
	$sql = $sql."group by substring(sdate,1,10) order by sdate";
	$result = query($sql);
   
	while ($row = sql_fetch_arr($result)) {
	   $idx = substr($row['sdate'],8,2);
	   $schedule_list[$idx] = true;
	}
	
	$line = 5;
	if (($FirstDayPosition > 5) && (ceil($TotalDay/5) == 7)) $line = 6;
	if (($FirstDayPosition == 1) && ($TotalDay == 28)) $line =4;

	$k = 0;

	for ($i=1; $i<=$line; $i++) {
?>
		<tr height="<?=$cell_height?>">
<?
		for ($j=1; $j<=7; $j++) {
			if ((!$day) && ($j == $FirstDayPosition)) $day = 1;

			$date_color = "black";
			if ($j == 7) $date_color = "blue";
			if ($j == 1) $date_color = "red";

			if ($day > 0){
			   
			   if($day < 10) $day = "0".$day;
			   
			   if($schedule_list[$day]) $td_color = "#A1DE8D";
			   else $td_color = "#FFFFFF";

				$tmp_day = $day;
				if(!strcmp($day, date('j'))) $day = "<b>".$day."</b>";
?>
				<td bgcolor="<?=$td_color?>" align="center" width="<?=$cell_width?>" bgcolor='#ffffff' style="cursor:hand;" onclick="setSchedule('<?=$year?>-<?=$month?>-<?=$tmp_day?>');">
					<font color="<?=$date_color?>"><?=$day?></font>
				</td>
<?
				$day = $tmp_day;
			} else {
?>
				<td bgcolor='#ffffff'></td>
<?
			}

			if ($day != $TotalDay) {
				if (($day > 0) && ($day < $TotalDay)) $day++;
			} else {
				$day = "&nbsp;";
			}
		}
?>
		</tr>
<?
	}
?>
	</table><br>