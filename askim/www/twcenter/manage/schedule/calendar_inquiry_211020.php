<?
$day = "";
$first_day	= date("w",mktime(0,0,0,$month,1,$year)) + 1;  
$total_day = date("t", mktime(0, 0, 0, $month, 1, $year));

$start_month	= $year."-".$month."-01";
$last_month = $year."-".$month."-".$total_day;

$bbs_url = "/twcenter/manage/bbs/list.php?ptype=input&mode=insert&code=online&pos=&code_page=&menucode=BBS";
$sch_url = "input.php?code=".$code."&".$menucodeParam."&sch_date=";
$view_url = "view.php?code=".$code."&".$menucodeParam."&idx=";

$sql = "
		select 
			*
		from 
			wiz_bbs 
		where 
			code='$code' 
			and addinfo1 <= '".$last_month."' and addinfo2 >= '".$start_month."'
		order by 
			unix_timestamp(addinfo1) asc
";
$result = query($sql);
$k = 0;
while($row = sql_fetch_arr($result)){
	for($i=strtotime($row["addinfo1"]);$i<=strtotime($row["addinfo2"]);$i = $i+86400){
		if($i >= strtotime($start_month) && $i <= strtotime($last_month)){
			if($schedule_list[date("j", $i)][$k] != "") $k++;
			$schedule_list[date("j", $i)][$k]["idx"] = $row["idx"];
			$schedule_list[date("j", $i)][$k]["subject"] = $row["subject"];
			$schedule_list[date("j", $i)][$k]["addinfo1"] = $row["addinfo1"];
			$schedule_list[date("j", $i)][$k]["addinfo2"] = $row["addinfo2"];
			$schedule_list[date("j", $i)][$k]["addinfo3"] = $row["addinfo3"];
		}
	}
}

$sql = "
		select
			*
		from
			wiz_bbs
		where
			code = 'online'
			and addinfo2 BETWEEN '".$start_month."' AND '".$last_month."'
		order by
			unix_timestamp(addinfo2) asc, addinfo3 asc
";
$result = query($sql);
for($i=0;$row = sql_fetch_arr($result);$i++){
	$account_list[date("j", strtotime($row["addinfo2"]))][$row["addinfo3"]]["idx"] = $row["idx"];
	$account_list[date("j", strtotime($row["addinfo2"]))][$row["addinfo3"]]["status"] = $row["status"];
	$account_list[date("j", strtotime($row["addinfo2"]))][$row["addinfo3"]]["addinfo1"] = $row["addinfo1"];
	$account_list[date("j", strtotime($row["addinfo2"]))][$row["addinfo3"]]["addinfo2"] = $row["addinfo2"];
	$account_list[date("j", strtotime($row["addinfo2"]))][$row["addinfo3"]]["addinfo3"] = $row["addinfo3"];
	$k++;
}
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style">
	<tr height="25" align="center">
		<td width="14%" class="t_name"><b><font color=red>Sun</font></b></td>
		<td width="14%" class="t_name"><b>Mon</b></td>
		<td width="14%" class="t_name"><b>Tue</b></td>
		<td width="14%" class="t_name"><b>Wed</b></td>
		<td width="14%" class="t_name"><b>Thu</b></td>
		<td width="14%" class="t_name"><b>Fri</b></td>
		<td width="14%" class="t_name"><b><font color=blue>Sat</font></b></td>
	</tr>
	<?php
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
			if ($j == 7) $date_color = "blue";
			if ($j == 1) $date_color = "red";
			
			if ($day > 0){
				$tmp_day = $day;
				
				if(!strcmp($day, date('j'))) $day = "<b>".$day."</b>";

				$wdate = $year."-".$month."-".sprintf('%02d', $tmp_day);
	?>
		<td valign="top" bgcolor="#ffffff" class="verdana">
			<a href="<?php echo $sch_url.$wdate; ?>">
				<font size="3" color="<?=$date_color?>"><?=$day?></font>
			</a>
			<br>
			<?php
		if(!empty($schedule_list[$tmp_day])) {
			for($k=0;$k<=max(array_keys($schedule_list[$tmp_day]));$k++){
				if($schedule_list[$tmp_day][$k]["addinfo3"] != ""){
					$time_array = array();
					$time_array = explode("/", $schedule_list[$tmp_day][$k]["addinfo3"]);
					for($ti=0;$ti<sizeof($time_array);$ti++){
						$apply_cont = "";
						$apply_cont .= '<p class="apply_cont">';
						if($account_list[$tmp_day][$time_array[$ti]]["idx"] == ""){ // 예약내역 없음. 예약가능
							$apply_cont .= '<span><a href="input.php?code=inquiry&mode=update&idx='.$schedule_list[$tmp_day][$k]["idx"].'">'.$time_array[$ti].'</a></span>';
							$apply_cont .= '<input type="button" value="예약하기" class="btn_apply" onClick="document.location=\''.$bbs_url.'&sch_idx='.$schedule_list[$tmp_day][$k]["idx"].'&sch_date='.$wdate.'&sch_time='.$time_array[$ti].'\';" />';
						} else {
							$apply_cont .= '<span><a href="input.php?code=inquiry&mode=update&idx='.$schedule_list[$tmp_day][$k]["idx"].'">'.$time_array[$ti].'</a></span>';
							switch($account_list[$tmp_day][$time_array[$ti]]["status"]){
								case "1":
									$apply_cont .= '<span class="status apply">접수</span>';
									break;
								case "2":
									$apply_cont .= '<span class="status ok">승인</span>';
									break;
								case "3":
									$apply_cont .= '<span class="status cancel">취소</span>';
									break;
								case "4":
									$apply_cont .= '<span class="status end">완료</span>';
									break;
							}
						}
						$apply_cont .= '&nbsp;&nbsp;<a href="'.$view_url.$schedule_list[$tmp_day][$k]["idx"].'">'.$schedule_list[$tmp_day][$k]["subject"].'</a>';
						$apply_cont .= '</p>';

						echo $apply_cont;
					}
			?>
			<br>
			<?php
				}
			}
	}
			?>
		</td>
		<?php
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