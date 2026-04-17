<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$skin_dir = "/twcenter/schedule/skin/scheduleBasic";

if (!$year) { $year	= date("Y"); $month	= date("m"); }

$schPrev = date("Y-m",mktime(0,0,0,$month-1,1,$year));
$prevArray = split("-",$schPrev);
$prevYear = $prevArray[0];
$prevMonth = $prevArray[1];

$schNext = date("Y-m",mktime(0,0,0,$month+1,1,$year));
$nextArray = split("-",$schNext);
$nextYear = $nextArray[0];
$nextMonth = $nextArray[1];

$nnext = date("Y-m",mktime(0,0,0,$month+2,1,$year));
$nnextArray = split("-",$nnext);
$nnextYear = $nnextArray[0];
$nnextMonth = $nnextArray[1];

$first_day	= date("w",mktime(0,0,0,$month,1,$year)) + 1;
$total_day	= date("t", mktime(0, 0, 0, $month, 1, $year));

$start_month	= $year."-".$month."-01";
$last_month = $year."-".$month."-".$total_day;

$TEST = "../twcenter/manage/main/main.php";
// 버튼설정
$prev_gopage = $PHP_SELF."?year=".$prevYear."&month=".$prevMonth."#mainReload";
$next_gopage = $PHP_SELF."?year=".$nextYear."&month=".$nextMonth."#mainReload";

$prev_btn = "<a href='".$prev_gopage."'><img border=0 src='".$skin_dir."/image/arrow_prev.gif' alt='이전' /></a>";
$next_btn = "<a href='".$next_gopage."'><img border=0 src='".$skin_dir."/image/arrow_next.gif' alt='다음' /></a>";
//$sch_url = " onClick=\"document.location='/".$sch_info['pageurl']."?year=".$year."&month=".$month."'\";";

@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/list_head_s_n.php';

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

	echo "<tr height='50'>";

	for ($j=1; $j<=7; $j++) {
		if ((!$day) && ($j == $first_day)) $day = 1;


		if ($day > 0){

			$day_sch = "";
			for($k=0;$k<count($schedule_list[$day]);$k++){
				if($k==0) $day_sch = $schedule_list[$day][$k][subject];
				else $day_sch .= "\n".$schedule_list[$day][$k][subject];
			}

			$tmp_day = $day;
			if(strlen($tmp_day) == 1) $tmp_day = "0".$tmp_day;
			else                      $tmp_day = $tmp_day;

			$setDate = $year."-".$month."-".$tmp_day;

			$_cnt = sql_fetch("SELECT COUNT(schdate) as cnt FROM wiz_schedule_main WHERE schdate='{$setDate}' ");

			if(!strcmp($day, date('j')) && !strcmp($month, date('m'))) {
				$day      = "<b><font color='#ff0000'>".$day."</font></b>";
				if($_cnt['cnt'] > 0){
					$td_color = "style='background-color: #FFFFFF; cursor:pointer;' title='일정이 있습니다.'";
				} else {
					$td_color = "style='background-color: #FFFFFF; cursor:pointer;' title='일정이 없습니다.'";
				}
			} else if($_cnt['cnt'] > 0) {
				$day      = "<b><font color='#FFFFFF'>".$day."</font></b>";
				$td_color = "style='background-color: #44AFFB; cursor:pointer;' title='일정이 있습니다.'";
			} else {
				$day      = "<font color='#000000'>".$day."</font>";
				$td_color = "style='background-color: #FFFFFF; cursor:pointer;' title='일정이 없습니다.'";
			}
			

			@include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/list_body_s_n.php';

			$day = $tmp_day;

		}else{

			echo "<td bgcolor=#FFFFFF></td>";
		}

		if ($day != $total_day) {
			if (($day > 0) && ($day < $total_day)) $day++;
		} else {
			$day = "&nbsp;";
		}
	}
	echo "</tr>";
}

include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/list_foot_s_n.php';

$schedule_list = null;
?>
