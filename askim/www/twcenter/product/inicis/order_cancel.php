<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
/* INIcancel.php
 *
 * 이미 승인된 지불을 취소한다.
 * 은행계좌 이체 , 무통장입금은 이 모듈을 통해 취소 불가능.
 *  [은행계좌이체는 상점정산 조회페이지 (https://iniweb.inicis.com)를 통해 취소 환불 가능하며, 무통장입금은 취소 기능이 없습니다.]  
 *  
 * Date : 2007/09
 * Author : ts@inicis.com
 * Project : INIpay V5.0 for PHP
 * 
 * http://www.inicis.com
 * Copyright (C) 2007 Inicis, Co. All rights reserved.
 */

$mid = $oper_info["pay_id"];
$tid = $_POST["tid"];


if(isset($_POST["tid"]) && $_POST["tid"]) {
	$r_value = "R";
}

if($r_value == "R") {
	$p_orderid = $_POST['orderid'];
} else {
	$p_orderid = $orderid;
}


$ord_info = sql_fetch("select tno, status, pay_method from wiz_order where orderid = '".$p_orderid."' ");
$pay_method  = $ord_info['pay_method'];
$p_status    = $ord_info['status'];

if($r_value != "R") {
	$tid = $ord_info["tno"];
}


/**************************
 * 1. 라이브러리 인클루드 *
 **************************/
$tmp_status = $status;
require($_SERVER["DOCUMENT_ROOT"]."/twcenter/product/inicis/libs/INILib.php");
$status = $tmp_status;

/***************************************
 * 2. INIpay41 클래스의 인스턴스 생성 *
 ***************************************/
$inipay = new INIpay50;

/*********************
 * 3. 취소 정보 설정 *
 *********************/
$inipay->SetField("inipayhome", $_SERVER["DOCUMENT_ROOT"]."/twcenter/product/inicis"); // 이니페이 홈디렉터리(상점수정 필요)
$inipay->SetField("type", "cancel");                            // 고정 (절대 수정 불가)
$inipay->SetField("debug", true);                             // 로그모드("true"로 설정하면 상세로그가 생성됨.)
$inipay->SetField("mid", $mid);                                 // 상점아이디
/**************************************************************************************************
 * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
 * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
 * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
 * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
 **************************************************************************************************/
$inipay->SetField("admin", "1111");                            
$inipay->SetField("tid", $tid);                                 // 취소할 거래의 거래아이디
$inipay->SetField("cancelmsg", $msg);                           // 취소사유

/****************
 * 4. 취소 요청 *
 ****************/
$inipay->startAction();


/****************************************************************
 * 5. 취소 결과                                           	*
 *                                                        	*
 * 결과코드 : $inipay->getResult('ResultCode') ("00"이면 취소 성공)  	*
 * 결과내용 : $inipay->getResult('ResultMsg') (취소결과에 대한 설명) 	*
 * 취소날짜 : $inipay->getResult('CancelDate') (YYYYMMDD)          	*
 * 취소시각 : $inipay->getResult('CancelTime') (HHMMSS)            	*
 * 현금영수증 취소 승인번호 : $inipay->getResult('CSHR_CancelNum')    *
 * (현금영수증 발급 취소시에만 리턴됨)                          * 
 ****************************************************************/

$res_cd = $inipay->getResult("ResultCode");
$res_msg = $inipay->getResult('ResultMsg');

/*
if(isset($_POST["tid"]) && $_POST["tid"]) {
	$r_value = "R";
}

if($r_value == "R") {
	$p_orderid = $_POST['orderid'];
} else {
	$p_orderid = $orderid;
}

$ord_info = sql_fetch("select tno, status, pay_method from wiz_order where orderid = '".$p_orderid."' ");

$pay_method  = $ord_info['pay_method'];
$p_status    = $ord_info['status'];
*/
switch($res_cd){
	case "00":
		$res_msg = "주문 및 결제취소 요청이 완료되었습니다.";
		if(in_array($p_status, $cancel_status_array)) {

			if($r_value == "R") {

				$cancelmsg = "PG연동 주문 및 결제취소(고객)";
				$sql = "
					update wiz_order 
					   set cancelmsg = '$cancelmsg'
						 , status = 'OC'
						 , cancel_date = now() 
						 , cancel_result = 'CANCEL'
					 where orderid = '".$p_orderid."'
					   and tno = '".$tid."'
				";
				query($sql);

				echo json_encode(json_result("success", $res_msg));
				exit;

			} else {

				$cancelmsg = "PG연동 주문 및 결제취소(관리자)";
				$sql = "
					update wiz_order 
					   set cancelmsg = '".$cancelmsg."'
						 , cancel_result = 'CANCEL'
					 where orderid = '".$p_orderid."'
					   and tno = '".$tid."'
				";
				query($sql);

			}

		} else {

			if($r_value == "R") {
				echo json_encode(json_result("fail", "주문취소는 주문접수 또는 결제완료 상태에서 가능합니다."));
				exit;
			}
		
		}
		break;
	case "01":
		if($r_value == "R") {
		//	echo json_encode(json_result("fail", "결제 취소요청이 실패하였습니다."));
			echo json_encode(json_result("fail", $res_msg));
			exit;
		}
		break;
}
?>