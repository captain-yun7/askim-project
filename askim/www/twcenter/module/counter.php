<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

// 오늘 방문자
$sql = "select time, sum(cnt) as cnt from wiz_contime group by substring(time,1,8) order by substring(time,1,8) desc";
$result = query($sql) or error("sql_error");
$visit_cnt = sql_fetch_obj($result);
$today_cnt = $visit_cnt->cnt*1;

// 어제 방문자
$visit_cnt = sql_fetch_obj($result);
$yester_cnt = $visit_cnt->cnt*1;

// 전체 방문자
$sql = "select sum(cnt) as cnt from wiz_contime";
$result = query($sql) or error("sql_error");
$visit_cnt = sql_fetch_obj($result);
$total_cnt = $visit_cnt->cnt*1;

// 현재접속자
$dir = @opendir(WIZHOME_PATH."/data/connect");
$now_time = mktime(date('H'), date('i'), 0, date('m'), date('d'), date('Y'));

while(($file = readdir($dir)) !== false)
{
   if(($file != ".") && ($file != ".."))
   {
      $now_cnt++;
      $fileinfo = stat(WIZHOME_PATH."/data/connect/$file");
      if($fileinfo[9] < $now_time - 120)
      {
         unlink(WIZHOME_PATH."/data/connect/$file");
         $now_cnt--;
      }
   }
}

?>