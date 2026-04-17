<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
header("Content-Type: application/xml; charset=UTF-8");

$rsql = " select code, page_url from wiz_bbsinfo where rss = 'Y' ";
$rresult = query($rsql);
for($i=0;$rrow = sql_fetch_arr($rresult);$i++){
	$codeAr[$i] = $rrow['code'];
	$page_url[$rrow['code']] = $rrow['page_url'];
}

$content = "";

$content .= '<?xml version="1.0" encoding="UTF-8" ?>';
$content .= '<rss version="2.0">';
$content .= '	<channel>';
$content .= '		<title><![CDATA['.$site_info['browser_title'].']]></title>';
$content .= '		<link><![CDATA['.SSL.WAY_URL.']]></link>';
$content .= '		<description><![CDATA['.$site_info['searchkey_de'].']]></description>';
$content .= '		<language>ko-kr</language>';

$sql = "
		select 
			wb.*
		from
			wiz_bbs as wb
			LEFT JOIN wiz_bbscat AS wc ON wb.category = wc.idx
		where 
			wb.code in ('".implode("','", $codeAr)."')
		order by
			wb.wdate desc
		limit
			0, 100
";
$result = query($sql);
for($i=0;$row = sql_fetch_arr($result);$i++){
	$link_url = "";
	$image_info = array();

	$link_url .= SSL.$_SERVER['HTTP_HOST']."/".$page_url[$row['code']]."?ptype=view&idx=".$row['idx']."&page=&code=".$row['code'];

	if(file_exists(WIZHOME_PATH."/data/bbs/".$code."/S".$row['upfile1'])){
		$upimg_s = "/twcenter/data/bbs/".$code."/S".$row['upfile1'];
		$image_info = getimagesize($upimg_s);
	} else {
		$upimg_s = "";
	}

	$content .= '	<item>';
	$content .= '		<title><![CDATA['.htmlspecialchars($row['subject']).']]></title>';
	$content .= '		<link><![CDATA['.$link_url.']]></link>';
	$content .= '		<description><![CDATA['.htmlspecialchars($row['content']).']]></description>';
	$content .= '		<pubDate>'.date("D, d M Y H:i:s O", $row['wdate']).'</pubDate>';
	if($upimg_s != ""){
		$content .= '	<enclosure url="'.SSL.$_SERVER['HTTP_HOST'].$upimg_s.'" type="'.$image_info['mime'].'" />';
	}
	$content .= '	</item>';
}

$content .= '	</channel>';
$content .= '</rss>';

echo $content;
?>
