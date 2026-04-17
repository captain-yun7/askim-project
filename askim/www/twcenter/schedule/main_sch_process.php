<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include $_SERVER['DOCUMENT_ROOT'].'/comm/API/json/JSON.php';

$sql = "DELETE FROM wiz_schedule_main WHERE schdate='{$schdate}' ";
query($sql);

for($i=0; $i<count($schmemo); $i++){

	$content = mysqli_real_escape_string($schmemo[$i]);

	$sql_com = "";
	$sql_com .= " SET schdate           = '" . $schdate . "'                  ";
	$sql_com .= ", adminid              = '" . $wiz_admin['id'] . "'          ";
	$sql_com .= ", adminname            = '" . $schname[$i] . "'              ";
	$sql_com .= ", subject              = '" . $subject[$i] . "'              ";
	$sql_com .= ", content              = '" . $content . "'                  ";
	$sql_com .= ", stime                = '" . $stime[$i] . "'                ";
	$sql_com .= ", etime                = '" . $etime[$i] . "'                ";
	$sql_com .= ", alltime              = '" . $alltime[$i] . "'              ";

	query(" INSERT INTO wiz_schedule_main $sql_com ");

}

	$result = "00";
	$msg = "일정이 등록되었습니다.";

	echo json_encode(json_result_data($result, $msg, $schdate));
	exit;

?>

