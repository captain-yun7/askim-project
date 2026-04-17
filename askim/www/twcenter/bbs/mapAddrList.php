<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

if($searchopt) {
	if(!strcmp($searchopt, "subcon")) $search_sql = " and (subject like '%$searchkey%' or content like '%$searchkey%') ";
	else $search_sql = " and $searchopt like '%$searchkey%' ";
}

$sql = "select idx, subject, address from wiz_bbs where code='".$_GET['code']."' $search_sql ";
$result = query($sql);
while($row = sql_fetch_arr($result)) {

	$rData = new stdClass();
	$rData->title			= $row['subject'];
	$rData->address			= $row['address'];
	$rData->idx				= $row['idx'];

	$oData[] = $rData;
	unset($rData);

}
$result_array = json_encode($oData);

echo $result_array;
?>