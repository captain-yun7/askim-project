<?php
include $_SERVER["DOCUMENT_ROOT"]."/twcenter/common.php";
include $_SERVER["DOCUMENT_ROOT"]."/twcenter/inc/twcenter_check.php";

switch($workType){
	case "acc_complete":
		if($sch_idx != "" && $aidx != ""){
			$sql = " update wiz_bbs set status = '5' where addinfo1 = '".$sch_idx."' and idx = '".$aidx."' ";
			$result = query($sql);
			if(!$result) echo "fail";
			else echo "success";
		} else {
			echo "fail";
		}
		break;
	case "acc_ok":
		if($sch_idx != "" && $aidx != ""){
			$sql = " update wiz_bbs set status = '4' where addinfo1 = '".$sch_idx."' and idx = '".$aidx."' ";
			$result = query($sql);
			if(!$result) echo "fail";
			else echo "success";
		} else {
			echo "fail";
		}
		break;
	case "acc_info":
		$str = array();

		$str["request"] = $_REQUEST;

		if($sch_idx != "" && $idx != ""){
			switch($code){
				case "inquiry":
					$bbs_code = "online";
					$subject_sql= "(select subject from wiz_bbs where code = '".$code."' and idx = wb.addinfo1) ";
					break;
			}

			$sql = " 
					select 
						*
						, ".$subject_sql." as acc_subject
					from 
						wiz_bbs as wb
					where 
						idx = '".$idx."' 
			";
			$row = sql_fetch($sql);
			$str['sql1'] = $sql;

			$now_accinfo = $row;

			$sql = " 
					select 
						*
						, ".$subject_sql." as acc_subject
					from 
						wiz_bbs as wb
					where 
						name = '".$row["name"]."' 
						and email = '".$row["email"]."' 
						and code = '".$row["code"]."' 
						and idx != '".$idx."' 
					order by 
						prino desc 
			";
			$str['sql2'] = $sql;
			$result = query($sql);
			for($i=0;$row = sql_fetch_arr($result);$i++){
				$list[$i] = $row;
			}

			$str["datas"] = $now_accinfo;
			$str["datas"]["already_list"] = $list;
			$str["result"] = "success";
		} else {
			$str["sql"] = "";
			$str["result"] = "fail";
		}

		echo json_encode($str, true);
		break;
}
?>