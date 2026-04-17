<?php
header("Content-Type:application/json");

include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";

## 검색
$search_sql = "";

/*if(!empty($masterType))               $search_sql .= " AND masterType = '{$masterType}' ";
if(!empty($gyear))                    $search_sql .= " AND gyear      = '{$gyear}' ";
if(!empty($gisu))                     $search_sql .= " AND gisu       = '{$gisu}' ";
if(!empty($gojang))                   $search_sql .= " AND gojang     = '{$gojang}' ";
if(!empty($taxno))                    $search_sql .= " AND taxno      = '{$taxno}' ";
*/
if(!empty($status))		$search_sql .= " and tax_pub = '$status'";
if(!empty($srh_prev))		$search_sql .= " and tax_date >= '$srh_prev 00:00:00'";
if(!empty($srh_next))		$search_sql .= " and tax_date <= '$srh_next 23:59:59'";
if($searchopt && $searchkey) $search_sql .= " and $searchopt like '%".$searchkey."%'";

$alltotal = sql_fetch("SELECT COUNT(*) AS all_total FROM wiz_tax WHERE tax_date != '' and tax_type='".$tax_type."'");
$alltotal = $alltotal['all_total'];

$total    = sql_fetch("SELECT COUNT(*) AS total FROM wiz_tax WHERE tax_date != '' and tax_type='".$tax_type."' $search_sql ");
$total    = $total['total'];

$rows = ($tmp_rows) ? $tmp_rows : 20;
$lists = 10;
$page_count = ceil($total/$rows);
if($page < 1 || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;

$list_query = "
	select *
	  from wiz_tax
	 where tax_date != ''
	   and tax_type = '".$tax_type."'
	   $search_sql
	 order by tax_date desc, orderid desc
	 limit $start, $rows
";
//print_r($list_query);
$list_result = query($list_query);

$oData = array();
while($row = sql_fetch_obj($list_result)){

	if($row->tax_pub == "Y")      $stacolor = "6DCFF6";
	else if($row->tax_pub == "N") $stacolor = "ED1C24";
	else $stacolor = "";

	$prd_name = "";

	if(strpos($row->prd_info, "^^")) {
		$prd_info = explode("^^", $row->prd_info);
		$kk = 0;
		if($prd_info) {
			for($ii = 0; $ii < count($prd_info); $ii++) {
				if(!empty($prd_info[$ii])) {
					$tmp_prd = explode("^", $prd_info[$ii]);
					if($ii < 1) $prd_name = $tmp_prd[0];
					$kk++;
				}
			}
		}
		if($kk > 1) {
			$prd_name .= " 외 ".($kk-1)."건";
		}
	} else {
		$sql_bk = "SELECT GROUP_CONCAT(prdname) as prdname, count(*) as cnt FROM wiz_basket WHERE orderid='".$row->orderid."'";
		$row_bk = sql_fetch($sql_bk);
		$prd_name = $row_bk['prdname'];
		if($row_bk['cnt'] > 1) {
			$prd_name .= " 외 ".($row_bk['cnt']-1)."건";
		}
	}

	$rData = new stdClass();
	$rData->no              = $no;

	$com_address = $row->com_address1." ".$row->com_address2;

	$rData->orderid         = $row->orderid;
	$rData->prd_name        = $prd_name;
	$rData->com_num         = (!empty($row->com_num)) ? $row->com_num : "";
	$rData->com_name        = (!empty($row->com_name)) ? $row->com_name : "";
	$rData->com_owner       = (!empty($row->com_owner)) ? $row->com_owner : "";
	$rData->com_post        = (!empty($row->com_post)) ? $row->com_post : "";
	$rData->com_address     = (!empty($com_address)) ? $com_address : "";
	$rData->com_kind        = (!empty($row->com_kind)) ? $row->com_kind : "";
	$rData->com_class       = (!empty($row->com_class)) ? $row->com_class : "";
	$rData->com_tel         = $row->com_tel;
	$rData->com_email       = $row->com_email;
	$rData->shop_num        = $row->shop_num;
	$rData->shop_name       = $row->shop_name;
	$rData->shop_owner      = $row->shop_owner;
	$rData->shop_address    = $row->shop_address;
	$rData->shop_kind       = $row->shop_kind;
	$rData->shop_class      = $row->shop_class;
	$rData->shop_tel        = $row->shop_tel;
	$rData->shop_email      = $row->shop_email;
	$rData->prd_info        = $row->prd_info;
	$rData->supp_price      = number_format($row->supp_price);
	$rData->tax_price       = number_format($row->tax_price);
	$rData->tax_pub         = $row->tax_pub;
	$rData->tax_date        = $row->tax_date;
	$rData->tax_type        = $row->tax_type;
	$rData->tax_no          = (!empty($row->tax_no)) ? $row->tax_no : "";
	$rData->cash_type       = get_cash_type_name($row->cash_type);
	$rData->cash_type2		= $row->cash_type2;
	$rData->cash_type2_name      = get_cash_type2_name($row->cash_type2);
	$rData->cash_info       = $row->cash_info;
	$rData->cash_info2      = $row->cash_info2;
	$rData->cash_info3      = $row->cash_info3;
	$rData->cash_info4      = $row->cash_info4;
	$rData->cash_name       = $row->cash_name;
	$rData->cash_num        = $row->cash_num;
	$rData->wdate           = ($row->wdate) ? substr($row->wdate,0,10) : "";
	$rData->stacolor        = $row->stacolor;
	$rData->bill_rst        = $row->bill_rst;
	$rData->bill_err_code   = $row->bill_err_code;
	$rData->bill_err_msg    = $row->bill_err_msg;

	$rData->page			= $page;
	$rData->lists			= $lists;
	$rData->page_count		= $page_count;
	$rData->total			= $total;
	$rData->alltotal		= $alltotal;

	$oData[] = $rData;
	unset($rData);

	$no--;

}

$result_array = json_encode($oData);

echo "{ \"list\": ";
echo $result_array."\n";
echo "}";
?>
