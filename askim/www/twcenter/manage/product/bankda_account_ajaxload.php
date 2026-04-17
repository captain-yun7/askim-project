<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/oper_info.php";
include_once "../../inc/bankda_info.php";

$directAccess  = $_POST['directAccess'];
$service_type  = $_POST['service_type'];
$partner_id    = $_POST['partner_id'];
$bkcode        = $_POST['bkcode'];
$bkdiv         = $_POST['bkdiv'];
$bkacctno      = str_replace("-", "", $_POST['bkacctno']);
$bkname        = bankda_bankcode($bkcode);
$mode          = $_POST['mode'];
$bkrnames      = $_POST['bkrnames'];
$bkacctholer   = $_POST['bkacctholer'];
$bkidx         = $_POST['bkidx'];
$bkno          = $_POST['bkno'];

if($directAccess == "y" && $service_type == $oper_info['bankda_service'] && $partner_id == $oper_info['bankda_partner_id']) {

	if($mode == "insert") {

		$accnum = sql_fetch("select count(bkacctno) as accCnt from bank_account where bkacctno = '".$bkacctno."' ");
		$accCnt = $accnum['accCnt'];

		if($accCnt <= 0) {

			/* 뱅크다 계좌추가 정보 */
			$sql_com = "";
			$sql_com .= " user_id            = '".$bankda_info['bankda_id']."'      ";
			$sql_com .= " , bkdiv            = '".$bkdiv."'                         ";
			$sql_com .= " , bkcode           = '".$bkcode."'                        ";
			$sql_com .= " , bkname           = '".$bkname."'                        ";
			$sql_com .= " , bkrnames         = '".$bkrnames."'                      ";
			$sql_com .= " , bkacctno         = '".$bkacctno."'                      ";
			$sql_com .= " , bkacctno2        = '".$_POST['bkacctno']."'             ";
			$sql_com .= " , bkacctholer      = '".$bkacctholer."'                   ";
			$sql_com .= " , wdate            = now()                                ";
			$sql_com .= " , btype            = 'Y'                                  ";

			$sql = "insert into bank_account set {$sql_com} ";
			query($sql);

		} else {

			$sql_com = "";
			$sql_com .= " user_id            = '".$bankda_info['bankda_id']."'      ";
			$sql_com .= " , bkdiv            = '".$bkdiv."'                         ";
			$sql_com .= " , bkcode           = '".$bkcode."'                        ";
			$sql_com .= " , bkname           = '".$bkname."'                        ";
			$sql_com .= " , bkrnames         = '".$bkrnames."'                      ";
			$sql_com .= " , bkacctno         = '".$bkacctno."'                      ";
			$sql_com .= " , bkacctno2        = '".$_POST['bkacctno']."'             ";
			$sql_com .= " , mdate            = now()                                ";
			$sql_com .= " , btype            = 'Y'                                  ";

			$sql = "update bank_account set {$sql_com} where bkacctno='".$bkacctno."' ";
			query($sql);

		}

		echo json_encode(json_result("0000", "계좌추가신청을 뱅크다에 전송했습니다.\n결과값을 확인하세요."));
		exit;

	} else if($mode == "modify") {

		/* 뱅크다 계좌정보 수정 */
		$sql_com = "";
		$sql_com .= " bkrnames         = '".$bkrnames."'                      ";
		$sql_com .= " , mdate          = now()                                ";
		$sql_com .= " , btype          = 'Y'                                  ";

		$sql = "
			update bank_account 
			   set {$sql_com} 
			 where idx = '".$bkidx."'
		";
		query($sql);

		echo json_encode(array("result"=>"0000", "msg"=>"정보수정신청을 뱅크다에 전송했습니다.\n결과값을 확인하세요.", "req1"=>$bankda_info['bankda_id'], "req2"=>$bankda_info['bankda_pw']));
		exit;

	} else if($mode == "delete" || $mode == "deleteall") {

		$sql_com = "";
		$sql_com .= " user_id            = ''                         ";
		$sql_com .= " , bkdiv            = ''                         ";
		$sql_com .= " , bkcode           = ''                         ";
		$sql_com .= " , bkrnames         = ''                         ";
		$sql_com .= " , wdate            = ''                         ";
		$sql_com .= " , mdate            = ''                         ";
		$sql_com .= " , btype            = 'N'                        ";

		$sql = "
			update bank_account 
			   set {$sql_com} 
			 where idx = '".$bkidx."'
		";
		query($sql);

		$acc_info = sql_fetch("select bkacctno from bank_account where idx = '".$bkidx."' ");

		$sql = "delete from bankda_io_history where actnumber = '".$acc_info['bkacctno']."' ";
		query($sql);

		if($mode == "delete") {
			echo json_encode(array("result"=>"0000", "msg"=>"해지요청을 뱅크다에 전송했습니다.\n결과값을 확인하세요.", "req1"=>$bankda_info['bankda_id'], "req2"=>$bankda_info['bankda_pw']));
			exit;
		} else {

			$sql = "delete from bank_account where idx = '".$bkidx."' ";
			query($sql);

			echo json_encode(array("result"=>"0000", "msg"=>"삭제되었습니다."));
			exit;
		}

	}

}
?>