<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if(isset($_POST['sorter'])){
	$userlist = "";
	$sorter = $_POST['sorter'];
	$array = explode(".", $sorter);
}

//if($array[0] == "sellprice") $array[0] = "wp.sellprice";
//else if($array[0] == "item_proc_date") $array[0] = "wpd.item_proc_date";
//else if($array[0] == "item_avg2_weight") $array[0] = "wp.item_avg2_weight";

if(!$_POST['sorter'] || $_POST['sorter'] == "undefined") $order_sql = " ORDER BY X.bkdate DESC";
else													 $order_sql = " ORDER BY ".$array[0]." ".$array[1];

$prev_period = $srh_prev;
$next_period = $srh_next." 23:59:59";

if($oper_info['bankda_service_date'] > $prev_period) {
	$prev_periods = $oper_info['bankda_service_date'];
} else {
	$prev_periods = $prev_period;
}

if(!empty($oper_info['bankda_service_date'])) {
	$service_date = " AND X.bkdate >= '".$oper_info['bankda_service_date']."'";
	$start_date   = $prev_periods;
} else {
	$service_date = "";
	$start_date   = $prev_period;
}


$where = array();

if(!empty($s_status)) {
	$where[] = "X.bkmatchres = '$s_status'";
} 

if(!empty($s_bank)) {
	$where[] = "X.bkname = '$s_bank'";
} 

if(isset($srh_prev) && $srh_prev && isset($srh_next) && $srh_next) {
	$where[] = "X.bkdate >= '".$start_date."' and X.bkdate <= '$next_period' ";
}

if($searchopt && $searchkey) $where[] = " INSTR(X.$searchopt, '".$searchkey."') > 0";

$search_query   = ($where) ? " AND ".implode(" AND ", $where) : "";

$sql = "
	SELECT COUNT(X.idx) AS total 
	  FROM bankda_io_history as X
	 WHERE 1
	   AND bkmatchres IN('MA','MB')
	   $service_date
	   $search_query
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
	SELECT X.bkdate
		 , X.actnumber
		 , X.bkname
		 , X.bkinput
		 , X.bkjukyo
		 , X.bkmatchres
		 , X.bkmatchdate
	  FROM bankda_io_history as X
	 WHERE 1
	   AND bkmatchres IN('MA','MB')
	   $service_date
	   $search_query
	 LIMIT $start, $rows
";
$res = query($sql);
while($row = sql_fetch_obj($res)) {

	$match_date   = substr($row->bkmatchdate,0,10);

//	if($row->bk_match_result == 'MT') {

		$bkdate    = $row->bkdate;							## 입금완료일
		$actnumber = $row->actnumber;						## 계좌번호
		$bkname    = $row->bkname;							## 은행명
		$bkinput   = number_format($row->bkinput);			## 입금액
		$bkjukyo   = $row->bkjukyo;							## 입금자
		$match_result = bk_match_result($row->bkmatchres);	## 현재상태
		$match_date   = substr($row->bkmatchdate,0,10);		## 최종매칭일

//	}

	$rData = new stdClass();

	$rData->num					= $no;
	$rData->bkdate				= $bkdate;
	$rData->actnumber			= $actnumber;
	$rData->bkname				= $bkname;
	$rData->bkinput				= $bkinput;
	$rData->bkjukyo				= $bkjukyo;
	$rData->match_result		= $match_result;
	$rData->match_date			= $match_date;
	$rData->orderid				= $orderid;
	$rData->mresult_code		= $mresult_code;

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

//print_r($sql);
$result_array = json_encode($oData);

echo "{ \"list\": ";
echo $result_array."\n";
echo "}";

?>
