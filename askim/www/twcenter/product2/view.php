<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";


//웹 취약점 대비 - 파라미터 필터링 2022-06-30 정나혜
foreach($_REQUEST as $k => $v){
	${$k} = xss_clean($v);
}

$prdcode = strip_tags($prdcode);
$page = strip_tags($page);
$catcode = strip_tags($catcode);
$ptype = strip_tags($ptype);

if(!isset($searchopt)) $searchopt = '';
$searchopt = strip_tags($searchopt);

if(!isset($searchkey)) $searchkey = '';
$searchkey = strip_tags($searchkey);

if($_GET['catcode'] != "") $catcode = $_GET['catcode'];

$param = "searchopt=$searchopt&searchkey=$searchkey";

/* 즐겨찾기상태에서 미진열상품 클릭시 메인으로 이동처리 */
$show_row = sql_fetch("select showset from wiz_product2 where prdcode='$prdcode' ");
if($show_row['showset'] != 'Y') {
	alert_gourl('해당상품은 진열중이 아닙니다.','/');
}

// 상품정보
$sql = "
	select wp.*
		 , wc.catcode
		 , wcat.prd_skin
	  from wiz_product2 as wp 
	  left join wiz_cprelation2 as wc 
	    on wp.prdcode = wc.prdcode
	  left join wiz_category2 as wcat 
	    on wc.catcode = wcat.catcode
	 where wp.prdcode='$prdcode'
";
$result = query($sql);
$row = sql_fetch_arr($result);

if(mobile_check() == true && $site_info["mobile_use"] == "Y") {
	$row['prd_skin'] = "prdBasic_m";
} else if (strpos($_SERVER['PHP_SELF'], "/m_com/") !== false) {
	$row['prd_skin'] = "prdBasic_m";
} else {
	$row['prd_skin'] = $row['prd_skin'];
}

// 스킨위치
if(!empty($row['prd_skin'])) $skin_dir = "/twcenter/product2/skin/".$row['prd_skin'];

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

$shortexp     = nl2br($row['shortexp']);
$content      = $row['content'];
$prdimg       = "/twcenter/data/product2/".$row['prdimg_M1'];

/** 제품 이미지 없을 때 noimg 나오게 하기**/
if(is_file($_SERVER['DOCUMENT_ROOT'].'/twcenter/data/product2/'.$row['prdimg_M1'])) { 
	$prdimg	= "/twcenter/data/product2/".$row['prdimg_M1'];			
	$noprdimg = 'N'; 
}else {
	$prdimg	= $skin_dir."/image/noimg_M.gif";	
	$noprdimg = 'Y'; 
}
/** 제품 이미지 없을 때 noimg 나오게 하기 끝 **/

$prdimg_in    = "/twcenter/data/product2/".$row['prdimg_L1'];
/** 제품 이미지 없을 때 noimg 나오게 하기**/
if(is_file($_SERVER['DOCUMENT_ROOT'].'/twcenter/data/product2/'.$row['prdimg_L1'])) { 
	$prdimg_in	= "/twcenter/data/product2/".$row['prdimg_L1'];			
	$noprdimg = 'N'; 
}else {
	$prdimg_in	= $skin_dir."/image/noimg_L.gif";	
	$noprdimg = 'Y'; 
}
/** 제품 이미지 없을 때 noimg 나오게 하기 끝 **/

$prdname      = $row['prdname'];
$shortexp      = $row['shortexp'];
$prdnum       = $row['prdnum'];

for($i=1; $i<=12; $i++) {
	${'prdimg_S'.$i} = $row['prdimg_S'.$i];
	${'prdimg_M'.$i} = $row['prdimg_M'.$i];
	${'prdimg_L'.$i} = $row['prdimg_L'.$i];
}

for($i=1; $i<=10; $i++) {
	${'info_name'.$i}  = $row['info_name'.$i];
	${'info_value'.$i} = $row['info_value'.$i];
}

$info_name1   = str_replace("{HTTP_HOST}",$_http_host,$info_name1);

for($i=1; $i<=5; $i++) {
	${'upfile'.$i}         = $row['upfile'.$i];
	${'upfile'.$i.'_name'} = $row['upfile'.$i.'_name'];
}

for($i=1; $i<=5; $i++) {
	${'addinfo'.$i}        = $row['addinfo'.$i];
}

$mcontent     = $row['mcontent'];
$mobile_content = "";
if(!empty($mcontent)) {
	$mobile_content = preg_replace('/(<p>&nbsp;<\/p>)+$/','',$mcontent);
	$mobile_content = preg_replace('/(<p><br \/><\/p>)+$/','',$mobile_content);
} 
if(empty($mobile_content)){
	$mcontent = $content;
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
		${'upfile'.$ii.'_click'}   = " onClick=\"alert('첨부파일이 존재하지 않습니다.');\" style=\"cursor:pointer\" ";
	} else {
		${'upfile'.$ii}          = "<a href='/twcenter/product2/down.php?prdcode=".$prdcode."&no=".$ii."'>".${'upfile'.$ii.'_name'}."</a>";
		${'upfile'.$ii.'_click'} = " onClick=\"document.location='/twcenter/product2/down.php?prdcode=".$prdcode."&no=".$ii."';\">";
	}
}

if(empty($catcode)) $catcode = $row['catcode'];

// 카테고리 정보
$catcode1 = substr($catcode,0,2);
$catcode2 = substr($catcode,0,4);
$catcode3 = substr($catcode,0,6);
$position = "";

$sql = "select * from wiz_category2 where catuse != 'N' and (
						catcode = '0000000000'
						or (catcode like '$catcode1%' and depthno = 1)
						or (catcode like '$catcode2%' and depthno = 2)
						or (catcode like '$catcode3%' and depthno = 3)
						or (catcode = '$catcode')) order by priorno01 asc, priorno02 asc, priorno03, priorno04 asc";

$result = query($sql);

while($cat_row = sql_fetch_arr($result)){
	if($catcode == $cat_row['catcode']){
		$cat_info = $cat_row;
		$catname = $cat_row['catname'];
	}
	if(is_file(WIZHOME_PATH."/data/product/".$cat_row['catimg'])) 
		$catimg = "<img src='/twcenter/data/product/".$cat_row['catimg']."'>";
	$position .= " &gt; <a href='".$_SERVER['PHP_SELF']."?ptype=list&catcode=".$cat_row['catcode']."'>".$cat_row['catname']."</a>";

}

if($cat_info['depthno'] == 1)      $tmp_catcode = substr($catcode,0,2);
else if($cat_info['depthno'] == 2) $tmp_catcode = substr($catcode,0,4);
else if($cat_info['depthno'] == 3) $tmp_catcode = substr($catcode,0,4);
if($cat_info['depthno'] < 3) $cat_info['depthno']++;

$ii=0;
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
	if($catcode == $cat_row['catcode']) 
		$cat_row['catname'] = "<strong>".$cat_row['catname']."</strong>";
	$catlist[$ii] .= "<a href='".$_SERVER['PHP_SELF']."?ptype=list&catcode=".$cat_row['catcode']."'>".$cat_row['catname']."</a>";
	$ii++;
}

// 하위 카테고리가 없을 경우 현재 카테고리
if($ii <= 0) {
	
	$cat_info['depthno'] = $cat_info['depthno'] - 1;
	
	if($cat_info['depthno'] == 1)      $tmp_catcode = substr($catcode,0,2);
	else if($cat_info['depthno'] == 2) $tmp_catcode = substr($catcode,0,2);
	else if($cat_info['depthno'] == 3) $tmp_catcode = substr($catcode,0,4);
	else if($cat_info['depthno'] == 4) $tmp_catcode = substr($catcode,0,6);

	$sql = "
		select catcode
			 , catname
			 , depthno
			 , purl 
		  from wiz_category2 
		 where catuse != 'N' 
		   and catcode like '$tmp_catcode%' 
		   and depthno = '".$cat_info['depthno']."' 
		 order by priorno01, priorno02, priorno03, priorno04 asc
	";
	$result = query($sql);
	while($cat_row = sql_fetch_arr($result)){
		if($catcode == $cat_row['catcode']) 
			$cat_row['catname'] = "<strong>".$cat_row['catname']."</strong>";
		if(!empty($cat_row['purl'])) $purl = "../".$cat_row['purl'];
		else                       $purl = $_SERVER['PHP_SELF'];
		$catlist[$ii] = "<a href='$purl?ptype=list&catcode=".$cat_row['catcode']."'>".$cat_row['catname']."</a>";

		$ii++;
	}
}
// 하위 카테고리가 없을 경우 현재 카테고리 끝

// 카테고리파일
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/category.php";

if($info_name1 == ""){
	$info_hide_start1 = "<!--"; $info_hide_end1 = "-->";
}
if($info_name2 == ""){
	$info_hide_start2 = "<!--"; $info_hide_end2 = "-->";
}
if($info_name3 == ""){
	$info_hide_start3 = "<!--"; $info_hide_end3 = "-->";
}
if($info_name4 == ""){
	$info_hide_start4 = "<!--"; $info_hide_end4 = "-->";
}
if($info_name5 == ""){
	$info_hide_start5 = "<!--"; $info_hide_end5 = "-->";
}
if($info_name6 == ""){
	$info_hide_start6 = "<!--"; $info_hide_end6 = "-->";
}
if($info_name7 == ""){
	$info_hide_start7 = "<!--"; $info_hide_end7 = "-->";
}
if($info_name8 == ""){
	$info_hide_start8 = "<!--"; $info_hide_end8 = "-->";
}
if($info_name9 == ""){
	$info_hide_start9 = "<!--"; $info_hide_end9 = "-->";
}
if($info_name10 == ""){
	$info_hide_start10 = "<!--"; $info_hide_end10 = "-->";
}


$list_btn = "<a href='".$_SERVER['PHP_SELF']."?ptype=list&page=".$page."&catcode=".$catcode."&".$param."' class='listBtn'>목록</a>";
$print_btn = "<a href='javascript:' onClick=\"prdPrint()\" class='printBtn'>인쇄</a>";

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
	var url = "/twcenter/product2/prdimg.php?prdimg=" + prdimg + "&prdcode=<?php echo $prdcode ?>";
	window.open(url,"prdImg","width=100,height=100,scrollbars=no,resizable=yes");
}

function prevAlert(){
	alert("이전상품이 없습니다.");
}

function nextAlert(){
	alert("다음상품이 없습니다.");
}

// 프린트
function prdPrint(){
	var url = "/twcenter/product2/print.php?prdcode=<?php echo $prdcode ?>";
	window.open(url,"prdPrint","width=100,height=100,scrollbars=yes,resizable=yes");
}

-->
</script>

<?php
// 다음이전 상품
$catcode01 = str_replace("00","",substr($catcode,0,2));
$catcode02 = str_replace("00","",substr($catcode,2,2));
$catcode03 = str_replace("00","",substr($catcode,4,2));
$catcode04 = str_replace("00","",substr($catcode,6,2));

$tmp_catcode = $catcode01.$catcode02.$catcode03.$catcode04;
$sql = "
	select wp.prdcode 
	  from wiz_cprelation2 wc
	     , wiz_product2 wp
		 , wiz_category2 as wcat 
	 where wc.catcode like '$tmp_catcode%' 
	   and wc.prdcode = wp.prdcode 
	   and wc.catcode = wcat.catcode 
	   and wcat.catuse != 'N' 
	   and wp.showset != 'N' 
	   and wp.prdcode > '$prdcode' 
	 order by wp.prdcode asc limit 1
";
$result = query($sql);
if($row = sql_fetch_obj($result)) {
	$prev = "<a href='".$_SERVER['PHP_SELF']."?ptype=view&prdcode=".$row->prdcode."&catcode=".$catcode."'><img src='".$skin_dir."/image/btn_view_prev.gif'></a>";
} else {
	$prev = "<a href=javascript:prevAlert();><img src='".$skin_dir."/image/btn_view_prev.gif'></a>";
}

$sql = "
	select wc.prdcode 
	  from wiz_cprelation2 wc
	     , wiz_product2 wp
		 , wiz_category2 as wcat 
	 where wc.catcode like '$tmp_catcode%' 
	   and wc.prdcode = wp.prdcode 
	   and wc.catcode = wcat.catcode 
	   and wcat.catuse != 'N' 
	   and wp.showset != 'N' 
	   and wp.prdcode < '$prdcode' 
	 order by wp.prdcode desc limit 1
";
$result = query($sql);
if($row = sql_fetch_obj($result)) {
	$next = "<a href='".$_SERVER['PHP_SELF']."?ptype=view&prdcode=".$row->prdcode."&catcode=".$catcode."'><img src='".$skin_dir."/image/btn_view_next.gif'></a>";
} else {
	$next = "<a href=javascript:nextAlert();><img src='".$skin_dir."/image/btn_view_next.gif'></a>";
}

// 상단파일
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/view_head.php";

// 관련상품
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/relation.php";

// 하단파일
include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/view_foot.php";
 ?>
