<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";

if($mode == "insert") {
	
	$sql = "SELECT MAX(idx) AS idx FROM wiz_bbsmain";
	$result = query($sql);
	$row = sql_fetch_arr($result);
	$idx = $row['idx'] + 1;

	$sql_com = "";
	$sql_com .= " idx                   = '$idx'                       ";
	$sql_com .= " , code                = '$code'                      ";
	$sql_com .= " , btype               = '$btype'                     ";
	$sql_com .= " , purl                = '$purl'                      ";
	$sql_com .= " , cnt                 = '$cnt'                       ";
	$sql_com .= " , line                = '$line'                      ";
	$sql_com .= " , skin                = '$skin'                      ";
	$sql_com .= " , subject_len         = '$subject_len'               ";
	$sql_com .= " , content_len         = '$content_len'               ";

	$sql = "INSERT INTO wiz_bbsmain SET {$sql_com} ";
	query($sql);
	
	complete("저장 되었습니다.","bbsmain_input.php?code=$code&idx=$idx");
	
} else if($mode == "update") {

	$sql_com = "";
	$sql_com .= " btype                 = '$btype'                      ";
	$sql_com .= " , purl                = '$purl'                       ";
	$sql_com .= " , cnt                 = '$cnt'                        ";
	$sql_com .= " , line                = '$line'                       ";
	$sql_com .= " , skin                = '$skin'                       ";
	$sql_com .= " , subject_len         = '$subject_len'                ";
	$sql_com .= " , content_len         = '$content_len'                ";

	$sql = "UPDATE wiz_bbsmain SET {$sql_com} WHERE code = '$code' and idx = '$idx' ";
	query($sql);
	
	complete("수정 되었습니다.","bbsmain_input.php?code=$code&idx=$idx");
	
}else if(!strcmp($mode, "delete")) {
	$sql = "DELETE FROM wiz_bbsmain WHERE code = '$code' AND idx = '$idx'";
	query($sql);
	
	complete("삭제 되었습니다.","bbsmain_config.php");
}


?>