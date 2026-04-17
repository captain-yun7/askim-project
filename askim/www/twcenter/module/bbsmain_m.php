<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbsmain_info.php";

// 게시판정보
$sql = "select newc,hotc,skin,datetype_list from wiz_bbsinfo where code = '$code'";
$result = query($sql);
$total = sql_fetch_row($result);
$bbs_info = sql_fetch_arr($result);

// 생성되지 않은 게시판인경우
if($total <= 0){
	$msg = "<font color=red><b>".$code."</b></font> 게시판은 아직 생성되지 않았습니다.";
	echo "<table align=center><tr><td height=25>  ".$msg."  </td></tr></table>";
}


// 헤더,바디,풋터 자르기
$skin = str_replace("[/LOOP]","[LOOP]",$skin);
list($header,$body,$footer) = explode("[LOOP]",$skin);

echo $header;

$idx = 0;
$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));

if(empty($bbs_info['datetype_list'])) $bbs_info['datetype_list'] = "%Y.%m.%d";

$category_sql = "";
if($category != "") $category_sql = " and wb.category = '$category' ";

$sql = "select wb.idx,wb.subject,wb.content,wb.addinfo1,wb.addinfo2,wb.addinfo3,wb.upfile1,wb.wdate as wtime,from_unixtime(wb.wdate, '".$bbs_info['datetype_list']."') as wdate,wb.privacy,wb.content,wb.depno,wc.catname, wc.caticon, wb.memid, wb.memgrp
				from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
				where wb.code = '$code' $category_sql order by wb.prino desc limit $cnt";
$result = query($sql);
while($row = sql_fetch_arr($result)){

	if($line!=0 && ($idx%$line)==0) echo "<tr>";

	$row['content'] = strip_tags($row['content']);

	$main_subject = cut_str($row['subject'],$subject_len);
	$main_content = cut_str($row['content'],$content_len);
	$main_name = $row['name'];
	//$main_wdate = str_replace("-",".",$row['wdate']);
	$main_wdate = $row['wdate'];
	$main_count = $row['count'];

	$main_category=""; $main_lock_icon=""; $main_re_icon=""; $main_new_icon=""; $main_hot_icon=""; $main_re_space=""; $main_photo="";

	if($row['caticon'] != "") $main_category = "<img src='/twcenter/data/category/".$code."/".$row['caticon']."' align='absmiddle'>";		// category
	else if($row['catname'] != "") $main_category = "[".$row['catname']."]";

	$main_link= "/m/bbs/notice.php?ptype=view&idx=$row['idx']";

	if($row['privacy'] == "Y"){																																					// privacy
		if(
			($wiz_session['level'] == "0") ||																																		// 전체관리자
			($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
			($row['memid'] != "" && $row['memid'] == $wiz_session['id'])																 ||	// 자신의글
			($wiz_session['id'] != "" && strpos($row['memgrp'],$wiz_session['id']) !== false)								// 그룹의글
		){
		}else{
			$main_link = "/m/bbs/notice.php?ptype=passwd&mode=view&idx=$row['idx']";
		}
		$main_lock_icon = "<img src='$skin_dir/image/lock.gif' border='0' align='absmiddle'>";
	}

	//$main_link = "style=\"cursor:hand\" onClick=\"document.location='$purl?ptype=view&idx=$row['idx']'\"";
	//$main_link = "$purl?ptype=view&idx=$row['idx']";
	//$wtime = mktime(0,0,0,substr($row['wtime'],5,2),substr($row['wtime'],8,2),substr($row['wtime'],0,4));
	$wtime = $row['wtime'];

	if(($ttime-$wtime)/86400 <= $bbs_info['newc']) $main_new_icon = "<img src='/twcenter/bbsmain/image/new.gif' border='0' align='absmiddle'>";	// new
	if($row['count'] > $bbs_info['hotc']) $main_hot_icon = "<img src='/twcenter/bbsmain/image/hot.gif' border='0' align='absmiddle'>";				// hot
	if($row['depno'] != 0) $main_re_icon = "<img src='/twcenter/bbsmain/image/re.gif' border='0' align='absmiddle'>";												// re
	//for($ii=0; $ii < $row['depno']; $ii++) $main_re_space .= " ";																											// respace
	$main_subject = $main_re_space.$main_re_icon.$main_subject;

	$addinfo1 = $row['addinfo1'];
	$addinfo2 = $row['addinfo2'];
	$addinfo3 = number_format($row['addinfo3']);

	if(file_exists(WIZHOME_PATH."/data/bbs/".$code."/S".$row['upfile1'])) $main_photo = "/twcenter/data/bbs/$code/S".$row['upfile1'];		// img
	else $main_photo = "/twcenter/bbs/skin/photoBasic/image/noimg.gif";

	$bbsmain = stripslashes($body);
	$bbsmain = str_replace("{SUBJECT}",$main_subject,$bbsmain);
	$bbsmain = str_replace("{DATE}",$main_wdate,$bbsmain);
	$bbsmain = str_replace("{CATEGORY}",$main_category,$bbsmain);
	$bbsmain = str_replace("{CONTENT}",$main_content,$bbsmain);
	$bbsmain = str_replace("{PHOTO}",$main_photo,$bbsmain);
	$bbsmain = str_replace("{NEW}",$main_new_icon,$bbsmain);
	$bbsmain = str_replace("{LINK}",$main_link,$bbsmain);
	$bbsmain = str_replace("{ADDINFO1}",$addinfo1,$bbsmain); //링크값
	$bbsmain = str_replace("{ADDINFO2}",$addinfo2,$bbsmain); //상품요약설명
	$bbsmain = str_replace("{ADDINFO3}",$addinfo3,$bbsmain); //금액
	$bbsmain = str_replace("{idx}",$idx,$bbsmain);

	echo $bbsmain;

	$idx++;
}

echo $footer;

$bidx = "";
?>