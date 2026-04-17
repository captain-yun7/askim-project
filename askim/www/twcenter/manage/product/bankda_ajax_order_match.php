<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if(isset($_POST['sorter'])){
	$userlist = "";
	//$sorter = preg_replace('#[^a-z0-9.]#i', '', $_POST['sorter']);
	$sorter = $_POST['sorter'];
	$array = explode(".", $sorter);
}

if($array[0] == "sellprice") $array[0] = "wp.sellprice";
else if($array[0] == "item_proc_date") $array[0] = "wpd.item_proc_date";
else if($array[0] == "item_avg2_weight") $array[0] = "wp.item_avg2_weight";

if(!$_POST['sorter'] || $_POST['sorter'] == "undefined") $order_sql = " ORDER BY wp.prior DESC, wp.prdcode DESC";
else													 $order_sql = " ORDER BY ".$array[0]." ".$array[1];

$prev_period1 = $sdate;
$next_period1 = $edate." 23:59:59";

if($oper_info['bankda_service_date'] > $prev_period) {
	$prev_periods = $oper_info['bankda_service_date'];
} else {
	$prev_periods = $prev_period1;
}

if(!empty($oper_info['bankda_service_date'])) {
	$service_date = " AND X.order_date >= '".$oper_info['bankda_service_date']."'";
	$start_date1  = $prev_periods;
} else {
	$service_date = "";
	$start_date1  = $prev_period1;
}


$where = array();

if(isset($sdate) && $sdate && isset($edate) && $edate) {
	$where[] = "X.order_date >= '".$start_date1."' and X.order_date <= '$next_period1' ";
}

if($searchopt2 && $searchkey2) $where[] = " INSTR(X.$searchopt2, '".$searchkey2."') > 0";
if($price1 && $price2) {
	$where[] = "X.total_price >= '".$price1."' and X.total_price <= '$price2' ";
}
$search_query   = ($where) ? " AND ".implode(" AND ", $where) : "";

$sql = "
	SELECT COUNT(X.orderid) AS total 
	  FROM wiz_order as X
	 WHERE X.orderid != ''
	   $service_date
	   $search_query
	   AND X.status = 'OR'
	   AND X.pay_method = 'PB'
";
$result = query($sql);
$row = sql_fetch_arr($result);
$total = $row['total'];

$idx   = 0;
$line  = 1;
$rows  = 5;
$lists = 10;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;

$sql = "
	SELECT X.*
	  FROM wiz_order as X
	 WHERE X.orderid != ''
	   $service_date
	   $search_query
	   AND X.status = 'OR'
	   AND X.pay_method = 'PB'
	 LIMIT $start, $rows
";
$res = query($sql);
while($row = sql_fetch_obj($res)) {

	$order_date   = substr($row->order_date,0,10);
	$orderid      = $row->orderid;
	$total_price  = $row->total_price;
	$account_name = $row->account_name;

	$rData = new stdClass();

	$rData->ordnum				= $no;
	$rData->order_date			= $order_date;
	$rData->orderid				= $orderid;
	$rData->total_price			= number_format($total_price);
	$rData->account_name		= $account_name;

	$rData->page				= $page;
	$rData->lists				= $lists;
	$rData->page_count			= $page_count;
	$rData->total				= $total;

	if($_POST['sorter'] != 'undefined'){
		$rData->sorter		= $_POST['sorter'];
	}

	$oData[] = $rData;
	unset($rData);

$no--;
$idx++;
}

$result_array = json_encode($oData);

echo "{ \"list\": ";
echo $result_array."\n";
echo "}";

?>
