<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/sch_info.php';

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

// 목록보기 권한체크
if($lpermi < $mem_level) error($sch_info['permsg'],$sch_info['perurl']);

if (!$year) { $year	= date("Y"); $month	= date("m"); }

$prev = date("Y-m",mktime(0,0,0,$month-1,1,$year));
$prevArray = explode("-",$prev);
$prevYear = $prevArray[0];
$prevMonth = $prevArray[1];

$next = date("Y-m",mktime(0,0,0,$month+1,1,$year));
$nextArray = explode("-",$next);
$nextYear = $nextArray[0];
$nextMonth = $nextArray[1];

$nnext = date("Y-m",mktime(0,0,0,$month+2,1,$year));
$nnextArray = explode("-",$nnext);
$nnextYear = $nnextArray[0];
$nnextMonth = $nnextArray[1];

$day = "";
$first_day	= date("w",mktime(0,0,0,$month,1,$year)) + 1;
$total_day	= date("t", mktime(0, 0, 0, $month, 1, $year));

$start_month	= $year."-".$month."-01";
$last_month = $year."-".$month."-".$total_day;

// 2018-06-15 연,월 셀렉트추가
$ppre_year  = date("Y")-2;
$nnex_year  = date("Y")+2;

$prev_btn = "<a href='".$PHP_SELF."?year=".$prevYear."&month=".$prevMonth."' title='이전'><span class='material-symbols-outlined'>arrow_back</span></a>";
$next_btn = "<a href='".$PHP_SELF."?year=".$nextYear."&month=".$nextMonth."' title='다음'><span class='material-symbols-outlined'>arrow_forward</span></a>";

$sel_year = "<select name='year' onChange='location.href=this.value;' class='select' title='년도'>";
for($i=$ppre_year; $i<=$nnex_year; $i++){
	$sel_year .= "<option value='".$PHP_SELF."?year=".$i."&month=".$month."'".($year == $i ? "selected" : "" ).">".$i."</option>";
}
$sel_year .= "</select>";

$sel_month = "<select name='month' onChange='location.href=this.value;' class='select' title='월'>";
for($i=0; $i<13; $i++){
	$sel_month .= "<option value='".$PHP_SELF."?year=".$year."&month=".$i."'".($month == $i ? "selected" : "" ).">".$i."</option>";
}
$sel_month .= "</select>";

$bbs_url = "/online/inquiry.php?ptype=input&mode=insert&code=online";
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
//echo $sql;
$result = query($sql);
$k = 0;
$schedule_list = array();
while($row = sql_fetch_arr($result)){
//	if($_SERVER['REMOTE_ADDR'] == '118.130.111.142') { echo date("Y-m-d", $row['wdate']).$row['subject']."<br>"; }

	for($i=strtotime($row['addinfo1']);$i<=strtotime($row['addinfo2']);$i = $i+86400){
		if($i >= strtotime($start_month) && $i <= strtotime($last_month)){
			if($schedule_list[date("j", $i)][$k] != "") $k++;
			$schedule_list[date("j", $i)][$k]['idx'] = $row['idx'];
			$schedule_list[date("j", $i)][$k]['subject'] = $row['subject'];
			$schedule_list[date("j", $i)][$k]['addinfo1'] = $row['addinfo1'];
			$schedule_list[date("j", $i)][$k]['addinfo2'] = $row['addinfo2'];
			$schedule_list[date("j", $i)][$k]['addinfo3'] = $row['addinfo3'];
			$schedule_list[date("j", $i)][$k]['addinfo4'] = $row['addinfo4'];
			$schedule_list[date("j", $i)][$k]['addinfo5'] = $row['addinfo5'];
			$schedule_list[date("j", $i)][$k]['addinfo6'] = $row['addinfo6'];
			$schedule_list[date("j", $i)][$k]['addinfo7'] = $row['addinfo7'];
			$schedule_list[date("j", $i)][$k]['addinfo8'] = $row['addinfo8'];
			$schedule_list[date("j", $i)][$k]['addinfo9'] = $row['addinfo9'];
			$schedule_list[date("j", $i)][$k]['addinfo10'] = $row['addinfo10'];
		}
	}
}

$hsql = " select * from wiz_holiday where (holiday_date between '".$start_month."' and '".$last_month."') and holiday_type = '공휴일' ";
$hres = query($hsql);
$holidays = array();
while($hrow = sql_fetch_arr($hres)) {
	$holidays[] = $hrow['holiday_date'];
}

$sql = "
		select
			*
		from
			wiz_bbs
		where
			code = 'online'
			and addinfo2 BETWEEN '".$start_month."' AND '".$last_month."'
			and status != '3'
		order by
			unix_timestamp(addinfo2) asc, addinfo3 asc
";

$result = query($sql);
for($i=0;$row = sql_fetch_arr($result);$i++){
	$days = date("j", strtotime($row['addinfo2']));
	$sidx = $row['addinfo1'];
	$time = $row['addinfo3'];

	$account_list[$days][$sidx][$time]['idx'] = $row['idx'];
	$account_list[$days][$sidx][$time]['status'] = $row['status'];
	$account_list[$days][$sidx][$time]['addinfo1'] = $row['addinfo1'];
	$account_list[$days][$sidx][$time]['addinfo2'] = $row['addinfo2'];
	$account_list[$days][$sidx][$time]['addinfo3'] = $row['addinfo3'];
	$account_list[$days][$sidx][$time]['tot_cnt'] += $row['addinfo4'];
	$k++;
}

if(!isset($_GET['items'])) $_GET['items'] = '';
$itemAr = explode("|", $_GET['items']);

@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_head.php";

if(($first_day > 5) && ((floor($total_day / 5) == 6) || (floor($total_day / 5) == 7))) {
	$line = 6;
	if($total_day == 30 && $first_day == 6) $line--;
} else if(($first_day == 1) && ($total_day == 28)) {
	$line = 4;
} else {
	$line = 5;
}

$k = 0;

for ($i=1; $i<=$line; $i++) {

	echo "<tr height='".$cell_height."'>";

	for ($j=1; $j<=7; $j++) {
		$td_color = "#fff";
		$day_sch = "";
		if ((!$day) && ($j == $first_day)) $day = 1;

		$day_color = "";
		$hcss = "";
		if ($j == 7) $day_color = "blue";
		if ($j == 1) $day_color = "red";

		if(in_array($year."-".$month."-".sprintf('%02d', $day), $holidays)){
			$day_color = "red";
			$hcss = "holiday";
		}

		if ($day > 0){
			$day_img = "<img src='/twcenter/manage/image/schedule/day".$day."-".$day_color.".gif' alt='' >";
			$day_sch = "";
			if(!is_array($schedule_list[$day])) $schedule_list[$day] = array();
			for($k=0;$k<count($schedule_list[$day]);$k++){
//				if($hcss == "") {
					$day_sch .= "&nbsp;<a href='$PHP_SELF?ptype=view&idx=".$schedule_list[$day][$k]['idx']."'>".$schedule_list[$day][$k]['subject']."</a><br>";
//				}
			}

		} else {
			$day_img = "";
			if(!is_array($schedule_list[$day])) $schedule_list[$day] = array();
		}

		$tmp_day = $day;

		$wdate = $year."-".$month."-".sprintf('%02d', $tmp_day);
		if(!strcmp($day, date('j')) && !strcmp($month, date('m'))){
			$day = "<b>".$day."</b>";
			$td_color = "#e0eaff";
		}

		if($day == "" || $day == "&nbsp;" || ( ($year."-".$month < date("Y-m")) || ( ($year."-".$month <= date("Y-m")) && $day < date("j", time())) )) {
			$td_color = "#f1f1f1";
		}

		@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_body.php";

		$day = $tmp_day;

		if ($day != $total_day) {
			if (($day > 0) && ($day < $total_day)) $day++;
		} else {
			$day = "&nbsp;";
		}
	}
	echo "</tr>";
}

@include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/list_foot.php";
$schedule_list = null;
?>