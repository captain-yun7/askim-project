<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

/* INIescrow_delivery.php
 *
 * 이니페이 플러그인을 통해 요청된 지불을 처리한다.
 * 지불 요청을 처리한다.
 * 코드에 대한 자세한 설명은 매뉴얼을 참조하십시오.
 * <주의> 구매자의 세션을 반드시 체크하도록하여 부정거래를 방지하여 주십시요.
 *  
 * http://www.inicis.com
 * Copyright (C) 2006 Inicis Co., Ltd. All rights reserved.
 */

	$mid = $oper_info["pay_id"];

	/**************************
	 * 1. 라이브러리 인클루드 *
	 **************************/
	require($_SERVER['DOCUMENT_ROOT']."/twcenter/product/inicis/libs/INILib.php");
	
	
	/***************************************
	 * 2. INIpay50 클래스의 인스턴스 생성 *
	 ***************************************/
	$iniescrow = new INIpay50;

	/*********************
	 * 3. 지불 정보 설정 *
	 *********************/
	$iniescrow->SetField("inipayhome", $_SERVER['DOCUMENT_ROOT']."/twcenter/product/inicis/");      // 이니페이 홈디렉터리(상점수정 필요)
	$iniescrow->SetField("tid",$tid); // 거래아이디
	$iniescrow->SetField("mid",$mid); // 상점아이디
    /**************************************************************************************************
     * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
     * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
     * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
     * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
     **************************************************************************************************/
	$iniescrow->SetField("admin","1111"); // 키패스워드(상점아이디에 따라 변경)
	$iniescrow->SetField("type", "escrow"); 				                    // 고정 (절대 수정 불가)
	$iniescrow->SetField("escrowtype", "dlv"); 				                    // 고정 (절대 수정 불가)
	$iniescrow->SetField("dlv_ip", getenv("REMOTE_ADDR")); // 고정
	$iniescrow->SetField("debug",false); // 로그모드("true"로 설정하면 상세한 로그가 생성됨)
	
	$iniescrow->SetField("oid",$oid);
	$iniescrow->SetField("soid","1");
	$iniescrow->SetField("dlv_date",$dlv_date);
	$iniescrow->SetField("dlv_time",$dlv_time);
	$iniescrow->SetField("dlv_report",$EscrowType);
	$iniescrow->SetField("dlv_invoice",$invoice);
	$iniescrow->SetField("dlv_name",iconv("utf-8", "euc-kr", $dlv_name));
	
	$iniescrow->SetField("dlv_excode",$dlv_exCode);
	$iniescrow->SetField("dlv_exname",iconv("utf-8", "euc-kr", $dlv_exName));
	$iniescrow->SetField("dlv_charge",$dlv_charge);
	
	$iniescrow->SetField("dlv_invoiceday",$dlv_invoiceday);
	$iniescrow->SetField("dlv_sendname",iconv("utf-8", "euc-kr", $sendName));
	$iniescrow->SetField("dlv_sendpost",$sendPost);
	$iniescrow->SetField("dlv_sendaddr1",iconv("utf-8", "euc-kr", $sendAddr1));
	$iniescrow->SetField("dlv_sendaddr2",iconv("utf-8", "euc-kr", $sendAddr2));
	$iniescrow->SetField("dlv_sendtel",$sendTel);

	$iniescrow->SetField("dlv_recvname",iconv("utf-8", "euc-kr", $recvName));
	$iniescrow->SetField("dlv_recvpost",iconv("utf-8", "euc-kr", $recvPost));
	$iniescrow->SetField("dlv_recvaddr",iconv("utf-8", "euc-kr", $recvAddr));
	$iniescrow->SetField("dlv_recvtel",$recvTel);
	
	$iniescrow->SetField("dlv_goodscode",$goodsCode);
	$iniescrow->SetField("dlv_goods",iconv("utf-8", "euc-kr", $goods));
	$iniescrow->SetField("dlv_goodscnt",$goodCnt);
	$iniescrow->SetField("price",$price);
	$iniescrow->SetField("dlv_reserved1",$reserved1);
	$iniescrow->SetField("dlv_reserved2",$reserved2);
	$iniescrow->SetField("dlv_reserved3",$reserved3);
	
	$iniescrow->SetField("pgn",$pgn);

	/*********************
	 * 3. 배송 등록 요청 *
	 *********************/
	$iniescrow->startAction();
	
	
	/**********************
	 * 4. 배송 등록  결과 *
	 **********************/
	 
	 $tid        = $iniescrow->GetResult("tid"); 					// 거래번호
	 $resultCode = $iniescrow->GetResult("ResultCode");		// 결과코드 ("00"이면 지불 성공)
	 $resultMsg  = $iniescrow->GetResult("ResultMsg"); 		// 결과내용 (지불결과에 대한 설명)
	 $dlv_date   = $iniescrow->GetResult("DLV_Date");		// 처리날짜(YYYYMMDD)
	 $dlv_time   = $iniescrow->GetResult("DLV_Time");		// 처리시각(hhmmss)

//	 echo json_encode(json_result($resultCode, $resultMsg));

?>