<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

$query = $_SERVER['QUERY_STRING'];
$vars = array();
foreach(explode('&', $query) as $pair) {
	list($key, $value) = explode('=', $pair);
	$key = urldecode($key);
	$value = urldecode($value);
	$vars[$key][] = $value;
}

$itemIds = $vars['ITEM_ID'];

if (count($itemIds) < 1) {
	exit('ITEM_ID 는 필수입니다.');
}

header('Content-Type: application/xml;charset=utf-8');
echo ('<?xml version="1.0" encoding="utf-8"?>');
?>
<response>
<?php
if(is_array($itemIds)) {
	foreach($itemIds as $idx => $prdcode) {

		$sql = "select wp.*, wc.catcode from wiz_product as wp inner join wiz_cprelation as wc on wp.prdcode = wc.prdcode where wp.prdcode = '$prdcode' group by wp.prdcode";
		$result = query($sql) or error("sql error");
		$prd_row = sql_fetch_arr($result);

		if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$prd_row["prdimg_L1"])) $prd_row["prdimg_L1"] = "/images/noimg_L.gif";
		else $prd_row["prdimg_L1"] = "/twcenter/data/prdimg/".$prd_row["prdimg_L1"];

		if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$prd_row["prdimg_S1"])) $prd_row["prdimg_S1"] = "/images/noimg_S.gif";
		else $prd_row["prdimg_S1"] = "/twcenter/data/prdimg/".$prd_row["prdimg_S1"];


		$id = $prdcode = $prd_row['prdcode'];
		$name = $prd_row['prdname'];
		$description = "";
		$price = $prd_row['sellprice'];

		if($prd_row['shortage'] == "Y") $quantity = 0;
		else if($prd_row['shortage'] == "N") $quantity = 99999;
		else if($prd_row['shortage'] == "S") $quantity = $prd_row['stock'];

		$catcode01 = substr($prd_row['catcode'], 0, 2)."000000";
		$catcode02 = substr($prd_row['catcode'], 0, 4)."0000";
		$catcode03 = substr($prd_row['catcode'], 0, 6)."00";
		$catcode04 = substr($prd_row['catcode'], 0, 8);

		$sql = "select * from wiz_category where (depthno = '1' and catcode = '$catcode01') or (depthno = '2' and catcode = '$catcode02') or (depthno = '3' and catcode = '$catcode03') or (depthno = '4' and catcode = '$catcode04') order by depthno asc";
		$result = query($sql) or error("sql error");
		while($cat_row = sql_fetch_arr($result)) {
			if($cat_row['depthno'] == "1") $catname1 = $cat_row['catname'];
			if($cat_row['depthno'] == "2") $catname2 = $cat_row['catname'];
			if($cat_row['depthno'] == "3") $catname3 = $cat_row['catname'];
			if($cat_row['depthno'] == "4") $catname4 = $cat_row['catname'];
		}

?>
	<item id="<?=$id?>">
		<name><![CDATA[<?=$name?>]]></name><?escape?>
		<url><?=WAY_HOST?>/shop/shop.php?ptype=view<![CDATA[&]]>prdcode=<?=$prdcode?></url>
		<description><![CDATA[<?=$description?>]]></description>
		<image><?=WAY_HOST?><?=$prd_row["prdimg_L1"]?></image>
		<thumb><?=WAY_HOST?><?=$prd_row["prdimg_S1"]?></thumb>
		<price><?=$price?></price>
		<quantity><?=$quantity?></quantity>
		<category>
			<first id="MJ01"><![CDATA[<?=$catname1?>]]></first>
			<second id="ML01"><![CDATA[<?=$catname2?>]]></second>
			<third id="MN01"><![CDATA[<?=$catname3?>]]></third>
		</category>
		<options>
		<?php
		for($ii = 1; $ii <= 11; $ii++) {
			$no = ($ii == 1) ? "" : $ii;
			if($prd_row['opttitle'.$no] != "") {
				$exp_str = ($ii <= 4) ? "^" : ",";
				$opt_list = explode($exp_str, $prd_row['optcode'.$no]);
		?>
			<option name="<?=$prd_row['opttitle'.$no]?>">
				<?php
				if(is_array($opt_list)) {
					foreach($opt_list as $opt_idx => $opt_code) {
				?>
				<select> <![CDATA[ <?=$opt_code?> ]]> </select>
				<?php
					}
				}
				?>
			</option>
		<?php
			}
		}
		?>
		</options>
	</item>
<?php
	}
}
echo('</response>');
?>
