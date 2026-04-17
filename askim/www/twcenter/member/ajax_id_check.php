<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

if($_POST['ckuse'] == "CHK"){

	if(!empty($_POST['id'])){
		$id = $id;
	}

	$id_count = trim(strlen($id));

	if($id_count > 2 && $id_count < 13){

		if(!empty($id)){
			$sql1 = "select id from wiz_member where id='$id'";
			$result2 = query($sql1);
			$total = sql_fetch_row($result2);

			$sql2 = "select id from wiz_admin where id = '$id'";
			$result2 = query($sql2);
			$total2 = sql_fetch_row($result2);

			$sql3 = "select designer_id from wiz_siteinfo where designer_id  = '$id' or anywiz_id = '".md5($id)."'";
			$result3 = query($sql3);
			$total3 = sql_fetch_row($result3);
		}

		$hap_total = $total2 + $total3;

		$id_val = strip_tags(strtolower($id));
		$filterid = explode(",", trim($mem_info['prohibit_id']));

		for($i=0; $i<count($filterid); $i++) {
			$f_string = $filterid[$i];
			$pos = strpos($id_val, $f_string);

			if($pos !== false) {
				$res = 'true';
			}
		}

		if($res == "true"){
			$checkmsg = "F";
		} else {
			$checkmsg = "O";
		}

		$result = "OK";


	} else {

		$result = "FAIL";
	
	}

}

echo $id."|".$total."|".$hap_total."|".$id_count."|".$checkmsg."|".$result;
exit;

?>
