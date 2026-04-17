<?php
header("Content-Type:application/json");
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

$usid = $wiz_session['id'];

switch($gType){
	case "L":
		if(isset($idx)) {
			$and_sql = " AND idx = {$idx}";
		} else if($basicChk == "Y") {
			$and_sql = " AND basicdelivery = '{$basicChk}'";
		}

		$sql = "
			SELECT *
			  FROM wiz_delivery_set
			 WHERE de_id = '{$usid}'
			   $and_sql
		";
		$drs = query($sql);
		$drw = sql_fetch_arr($drs);

		list($hp1, $hp2, $hp3) = explode("-", $drw['re_hphone']);
		list($tp1, $tp2, $tp3) = explode("-", $drw['re_tphone']);

		$rData = new stdClass();
		$rData->re_name			= $drw['re_name'];
		$rData->re_post			= $drw['re_post'];
		$rData->re_addr			= $drw['re_addr'];
		$rData->re_addr2		= $drw['re_addr2'];
		$rData->hp1				= $hp1;
		$rData->hp2				= $hp2;
		$rData->hp3				= $hp3;
		$rData->tp1				= $tp1;
		$rData->tp2				= $tp2;
		$rData->tp3				= $tp3;
		$rData->basic_de		= $drw['basicdelivery'];

		$oData[] = $rData;
		unset($rData);

		$result_array = json_encode($oData);

		echo "{ \"list\": ";
		echo $result_array."\n";
		echo "}";
		break;
	case "P":
		$basicChk = $_POST['basicChk'];

		if($basicChk == "C") {

			$ord_sql = "SELECT rece_name
							 , rece_tphone
							 , rece_hphone
							 , rece_post
							 , rece_address
						  FROM wiz_order
						 WHERE send_id = '$usid'
						 ORDER BY orderid DESC
						 LIMIT 1
					   ";
			$ord_res = query($ord_sql);
			$ord_row = sql_fetch_arr($ord_res);

			list($hp1, $hp2, $hp3) = explode("-", $ord_row['rece_hphone']);
			list($tp1, $tp2, $tp3) = explode("-", $ord_row['rece_tphone']);

			$rData = new stdClass();
			$rData->rece_name      = $ord_row['rece_name'];
			$rData->rece_post      = $ord_row['rece_post'];
			$rData->rece_address   = $ord_row['rece_address'];
			$rData->rece_address2  = "";
			$rData->hp1			   = $hp1;
			$rData->hp2			   = $hp2;
			$rData->hp3			   = $hp3;
			$rData->tp1			   = $tp1;
			$rData->tp2			   = $tp2;
			$rData->tp3			   = $tp3;

			$oData[] = $rData;
			unset($rData);

		} else if($basicChk == "V" || $basicChk == "D") {

			$rData = new stdClass();
			$rData->rece_name      = "";
			$rData->rece_post      = "";
			$rData->rece_address   = "";
			$rData->rece_address2  = "";
			$rData->hp1			   = "";
			$rData->hp2			   = "";
			$rData->hp3			   = "";
			$rData->tp1			   = "";
			$rData->tp2			   = "";
			$rData->tp3			   = "";

			$oData[] = $rData;
			unset($rData);


		}

		$result_array = json_encode($oData);

		echo "{ \"list\": ";
		echo $result_array."\n";
		echo "}";
		break;
}
?>