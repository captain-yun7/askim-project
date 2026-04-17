<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/twcenter/common.php";

switch($workType){
	case "I":
		if($_POST["holiday_date"] == "" || $_POST["holiday_name"] == ""){
			error("올바르지 않은 값이 전송되었습니다.");
			exit;
		}

		$sql = " insert into wiz_holiday set holiday_date = '".$_POST["holiday_date"]."', holiday_name = '".$_POST["holiday_name"]."', holiday_type = '".$_POST["holiday_type"]."' ";
		$result = query($sql);

		echo "<script>alert('휴일이 등록되었습니다.');location.href = './holiday_list.php?code=".$_POST["code"]."';</script>";
		break;
	case "M":
		if($_POST["holiday_date"] == "" || $_POST["holiday_name"] == ""){
			error("올바르지 않은 값이 전송되었습니다.");
			exit;
		}

		$sql = " update wiz_holiday set holiday_date = '".$_POST["holiday_date"]."', holiday_name = '".$_POST["holiday_name"]."', holiday_type = '".$_POST["holiday_type"]."' where idx = '".$_POST["idx"]."' ";
		$result = query($sql);

		echo "<script>alert('휴일이 수정되었습니다.');location.href = './holiday_list.php?code=".$_POST["code"]."';</script>";
		break;
	case "D":
		if($_GET["idx"] == ""){
			error("올바르지 않은 값이 전송되었습니다.");
			exit;
		} else {
			$sql = " delete from wiz_holiday where idx = '".$_GET["idx"]."' ";
			$result = query($sql);

			echo "<script>alert('휴일이 삭제되었습니다.');location.href = './holiday_list.php?code=".$_GET["code"]."';</script>";
		}
		break;
}
?>