<?php
@ini_set('session.bug_compat_warn', 0);
@ini_set('session.bug_compat_42', 0);

include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";

if($_GET['catcode'] != "") $catcode = $_GET['catcode'];

$param = "searchopt=$searchopt&searchkey=$searchkey";

// 제품정보
$sql = "
	select wp.*
		 , wc.catcode
		 , wcat.prd_skin
		 , wcat.purl
	  from wiz_product2 as wp 
	  left join wiz_cprelation2 as wc 
	    on wp.prdcode = wc.prdcode
	  left join wiz_category2 as wcat 
	    on wc.catcode = wcat.catcode
	 where wp.prdcode='$prdcode'
";
$result = query($sql);
$row = sql_fetch_arr($result);

// 오늘본 제품목록에 추가
$view_exist = false;
$view_idx = $view_list ? count($view_list) : 0;
for($ii = 0; $ii < $view_idx; $ii++){
	if($view_list[$ii]['prdcode'] == $prdcode){ $view_exist = true; break; }
}

if(!$view_exist){
	$view_list[$view_idx]['prdcode'] = $prdcode;
	$view_list[$view_idx]['purl'] = $row['purl'];
	$view_list[$view_idx]['prdimg'] = $row['prdimg_R'];
	$_SESSION["view_list"] = $view_list;
}

// 스킨위치
if(!empty($row['prd_skin'])) $skin_dir = "/twcenter/product2/skin/".$row['prd_skin'];

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

$shortexp  = nl2br($row['shortexp']);
$content   = $row['content'];
//$prdimg    = "/twcenter/data/product2/".$row['prdimg_M1'];
if(img_type(WIZHOME_PATH."/data/product2/".$row['prdimg_M1'])) {
	$prdimg = "/twcenter/data/product2/".$row['prdimg_M1'];
} else {
	$prdimg = "/twcenter/product2/skin/prdBasic/image/noimg_M.gif";
}

$prdname   = $row['prdname'];
$prdnum    = $row['prdnum'];

for($i=1; $i<=12; $i++) {
	${'prdimg_S'.$i} = $row['prdimg_S'.$i];
}

for($i=1; $i<=10; $i++) {
	${'info_name'.$i}  = $row['info_name'.$i];
	${'info_value'.$i} = $row['info_value'.$i];
}

for($i=1; $i<=5; $i++) {
	${'upfile'.$i}         = $row['upfile'.$i];
	${'upfile'.$i.'_name'} = $row['upfile'.$i.'_name'];
}

if($prdimg_max < 12) $prdimg_hide_max = 12;
else $prdimg_hide_max = $prdimg_max;
for($ii = 1; $ii <= $prdimg_hide_max; $ii++) {
	if(!is_file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/product2/".${'prdimg_S'.$ii})){
		${'prdimg_hide_start'.$ii} = "<!--"; ${'prdimg_hide_end'.$ii} = "-->";
	}
}
for($ii = 1; $ii <= $prdfile_max; $ii++) {
	if(!is_file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/product2/".${'upfile'.$ii})){
		${'upfile_hide_start'.$ii} = "<!--"; ${'upfile_hide_end'.$ii} = "-->";
		${'upfile'.$ii.'_click'} = " onClick=\"alert('첨부파일이 존재하지 않습니다.');\" style=\"cursor:pointer\" ";
	} else {
		${'upfile'.$ii} = "<a href='/twcenter/product2/down.php?prdcode=".$prdcode."&no=".$ii."'>".${'upfile'.$ii.'_name'}."</a>";
		${'upfile'.$ii.'_click'} = " onClick=\"document.location='/twcenter/product2/down.php?prdcode=".$prdcode."&no=".$ii."';\">";
	}
}

if(empty($catcode)) $catcode = $row['catcode'];

// 카테고리 정보
$catcode1 = substr($catcode,0,2);
$catcode2 = substr($catcode,0,4);
$position = "";

$sql = "
	select * 
	  from wiz_category2 
	 where catuse != 'N' 
	   and (
			catcode = '000000'
			or (
				catcode like '$catcode1%' 
				and depthno = 1
			)
			or (
				catcode like '$catcode2%' 
				and depthno = 2
			)
			or (
				catcode = '$catcode'
				)
			) 
	 order by priorno01 asc, priorno02 asc, priorno03 asc
";
$result = query($sql);

while($cat_row = sql_fetch_arr($result)){
	if($catcode == $cat_row['catcode']){
		$cat_info = $cat_row;
		$catname = $cat_row['catname'];
	}
	if(is_file(WIZHOME_PATH."/data/product2/".$cat_row['catimg'])) {
		$catimg = "<img src='/twcenter/data/product2/".$cat_row['catimg']."'>";
		$position .= " &gt; <a href='".$_SERVER['PHP_SELF']."?ptype=list&catcode=".$cat_row['catcode']."'>".$cat_row['catname']."</a>";
	}

}

if($cat_info['depthno'] == 1)      $tmp_catcode = substr($catcode,0,2);
else if($cat_info['depthno'] == 2) $tmp_catcode = substr($catcode,0,4);
else if($cat_info['depthno'] == 3) $tmp_catcode = substr($catcode,0,4);
if($cat_info['depthno'] < 3) $cat_info['depthno']++;

$brow = 1;
$sql = "
	select catcode
		 , catname
		 , depthno 
	  from wiz_category2 
	 where catuse != 'N' 
	   and catcode like '$tmp_catcode%' 
	   and depthno = '".$cat_info['depthno']."' 
	 order by priorno01, priorno02, priorno03 asc
";
$result = query($sql);
while($cat_row = sql_fetch_arr($result)){
	$tr = "";
	if($brow % 5 == 0 && $brow != 0) $tr = "<tr>";

	if($catcode == $cat_row['catcode']) $cat_row['catname'] = "<strong>".$cat_row['catname']."</strong>";
	$catlist .= "<td><a href='".$_SERVER['PHP_SELF']."?ptype=list&catcode=".$cat_row['catcode']."'>".$cat_row['catname']."</a></td>".$tr;
$brow++;
}

// 하위 카테고리가 없을 경우 현재 카테고리
if($brow <= 1) {
	
	$cat_info['depthno'] = $cat_info['depthno'] - 1;
	
	if($cat_info['depthno'] == 1)      $tmp_catcode = substr($catcode,0,2);
	else if($cat_info['depthno'] == 2) $tmp_catcode = substr($catcode,0,2);
	else if($cat_info['depthno'] == 3) $tmp_catcode = substr($catcode,0,4);

	$csql = "
		select catcode
		     , catname
			 , depthno
			 , purl 
		  from wiz_category2 
		 where catuse != 'N' 
		   and catcode like '$tmp_catcode%' 
		   and depthno = '".$cat_info['depthno']."' 
		 order by priorno01, priorno02, priorno03 asc
	";
	$cresult = query($csql);
	while($crow = sql_fetch_arr($cresult)){
		$tr = "";
		if($brow % 5 == 0 && $brow != 0) $tr = "<tr>";
	
		if($catcode == $crow['catcode']) $crow['catname'] = "<strong>".$crow['catname']."</strong>";
		if(!empty($crow['purl'])) $purl = "/".$crow['purl'];
		else $purl = $PHP_SELF;
		$catlist .= "<td><a href='$purl?ptype=list&catcode=".$crow['catcode']."'>".$crow['catname']."</a></td>".$tr;
		$brow++;
	}
}

for($i=1; $i<=10; $i++) {
	if(${'info_name'.$i} == "") {
		${'info_hide_start'.$i} = "<!--"; ${'info_hide_end'.$i} = "-->";
	}
}

$print_btn = "<a href=\"javascript:prdPrint();\" class='printBtn'>인쇄하기</a>";

?>
<script language="javascript">
<!--

var prdimg = "<?php echo $row['prdimg_L1'] ?>";

function chgImage(idx){
<?php
for($ii = 1; $ii <= $prdimg_max; $ii++) {
?>
	if(idx == "<?php echo $ii ?>"){
		prdimg = "<?php echo $row['prdimg_L'.$ii] ?>";
		document.prdimg.src = "/twcenter/data/product2/<?php echo $row['prdimg_M'.$ii] ?>";
	}
<?php
}
?>

}

function prdImg(){
	var url = "/twcenter/product2/prdimg.php?prdimg=" + prdimg;
	window.open(url,"prdImg","width=100,height=100,scrollbars=no,resizable=yes");
}

function prevAlert(){
	alert("이전제품이 없습니다.");
}

function nextAlert(){
	alert("다음제품이 없습니다.");
}

// 프린트
function prdPrint(){
	print();
}

-->
</script>

<?php
// 다음이전 제품
$prev = "";
$next = "";
?>
<html>
<head>
<title>::제품출력::</title>
<link rel="icon" href="/img/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..500,0..1,-50..200" />
<style>
	html, body  {margin:0; padding:0; list-style:none; filter: expression(document.execCommand('BackgroundImageCache', false, true));}
</style>
</head>
<body onLoad="resizeTo(920, 750);prdPrint()" style="padding:20px; box-sizing:border-box;">
<?php
// 상단파일
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/view_head.php";

// 하단파일
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/view_foot.php";
?>
</body>
</html>