<?
if (!$year) {
$year	= date("Y");
$month	= date("m");
}

$prev		= date("Y-m",mktime(0,0,0,$month-1,1,$year));
$prevArray	= split("-",$prev);
$prevYear	= $prevArray[0];
$prevMonth	= $prevArray[1];

$next		= date("Y-m",mktime(0,0,0,$month+1,1,$year));
$nextArray	= split("-",$next);
$nextYear	= $nextArray[0];
$nextMonth	= $nextArray[1];

$nnext		= date("Y-m",mktime(0,0,0,$month+2,1,$year));
$nnextArray	= split("-",$nnext);
$nnextYear	= $nnextArray[0];
$nnextMonth	= $nnextArray[1];
?>

<script language="javascript">
<!--
function setSchedule(date){
   var url = "/twcenter/manage/schedule/schedule_view.php?date=" + date;
   window.open(url, "setSchedule", "height=500, width=600, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, left=150, top=100");
}
-->
</script>
<table width="169"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="37" align="right" valign="top" background="../image/day/bg_day_1.gif"><table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="15" height="17" align="left"></td>
        <td class="kfont4"></td>
        <td width="15" align="right"></td>
        <td width="14"></td>
      </tr>
      <tr>
        <td height="14" align="left" valign="top"><a href="<?echo $PHP_SELF?>?year=<?=$prevYear?>&month=<?=$prevMonth?>"><img src="../image/day/icon_left.gif" width="11" height="11" border="0"></a></td>
        <td valign="bottom" class="kfont4"><?=$year?>/<?=$month?></td>
        <td align="right" valign="top"><a href="<?echo $PHP_SELF?>?year=<?=$nextYear?>&month=<?=$nextMonth?>"><img src="../image/day/icon_right.gif" width="11" height="11" border="0"></a></td>
        <td></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" background="../image/day/bg_day_2.gif" style="padding-bottom:9 ">
    	<table width="147" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td bgcolor="#FFFFFF" align="center"><table width="140" height="119"  border="0" cellpadding="0" cellspacing="0">
          <tr align="center" valign="middle">
            <td height="5" colspan="7" class="kfont2"></td>
          </tr>
          
          <?
					$day = "";
					$first_day	= date("w",mktime(0,0,0,$month,1,$year)) + 1;  
					$total_day = date("t", mktime(0, 0, 0, $month, 1, $year));
					
					$start_month	= $year."-".$month."-01";
					$last_month = $year."-".$month."-".$total_day;
					
					$code = "schedule";
					
					$sql = "select *, from_unixtime(wdate, '%e') as day_idx from wiz_bbs where code='$code' and from_unixtime(wdate, '%Y-%m-%d') between '$start_month' and '$last_month' order by wdate";
					$result = query($sql);
					
					$day_bak = "";
					while ($row = sql_fetch_arr($result)) {

						$day_idx = $row['day_idx'];
						if($day_bak == $day_idx) $seq_idx++;
						else $seq_idx = 0;
						$day_bak = $day_idx;
						
						$schedule_list[$day_idx][$seq_idx]['subject'] = cut_str($row['subject'],10);
						$schedule_list[$day_idx][$seq_idx]['idx'] = $row['idx'];
					
					}

					$line = 5;
					if (($first_day > 5) && (ceil($total_day/5) == 7)) $line = 6;
					if (($first_day == 1) && ($total_day == 28)) $line =4;
				
					$k = 0;
				
					for ($i=1; $i<=$line; $i++) {
				
						echo "<tr>";
				
						for ($j=1; $j<=7; $j++) {
							if ((!$day) && ($j == $first_day)) $day = 1;
				
							$date_color = "black";
							if ($j == 7) $date_color = "blue";
							if ($j == 1) $date_color = "red";
				
							if ($day > 0){

							   if($schedule_list[$day]) $td_color = "#A1DE8D";
							   else $td_color = "#FFFFFF";
								
								$day_schedule = "";
								for($k=0;$k<count($schedule_list[$day]);$k++){
									if($k==0) $day_schedule = $schedule_list[$day][$k]['subject'];
									else $day_schedule .= "\n".$schedule_list[$day][$k]['subject'];
								}
									
								echo "<td bgcolor='$td_color' align='center'><a href='../schedule/list.php?code=schedule&year=$year&month=$month'><font color='".$date_color."' title='".$day_schedule."'>$day</font></a></td>";
								
							} else {
				
								echo "<td></td>";
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
        </table></td>
      </tr>
    </table>

    </td>
  </tr>
</table>