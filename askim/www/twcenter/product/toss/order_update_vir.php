<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
    /*
     * [상점 결제결과처리(DB) 페이지]
     */
	$postData = file_get_contents('php://input');
	$json = json_decode($postData);

	$log = "\r\n---------------------------Start----------------------------------\r\n";
	$log .= print_r($json, true);
	if ($json->status == 'DONE') {
		$vir_secret = $json->secret;

		$sql = "select * from wiz_order where vir_secret='$vir_secret'";
		$order_info = sql_fetch_object($sql);

		$orderid = $order_info->orderid;
		$log .= PHP_EOL."order sql : ".$sql.PHP_EOL;
		$log .= print_r($order_info, true);
//		@make_log($_SERVER['DOCUMENT_ROOT']."/twcenter/data/vir_log","\r\n---------------------------Start----------------------------------\r\n".print_r($order_info, true)."\r\n---------------------------End----------------------------------\r\n");
		$log .= PHP_EOL." order status : ".$order_info->status.PHP_EOL;
		$log .= PHP_EOL." vir_secret : ".$vir_secret.PHP_EOL;

		if($order_info->status == 'OR') {
			if($order_info->vir_secret == $vir_secret) {			///secret 검증
				/*********************************
				 * 주문정보 업데이트
				 *********************************/
				$_Payment['status']		= "OY";						//결제상태
				$_Payment['orderid']		= $order_info->orderid;					//주문번호
				$_Payment['paymethod']	= $order_info->pay_method;	//결제종류
				//$_Payment['ttno']			= $order_info->tno;					//거래번호
				$_Payment['pgname']		= "toss";					//PG사 종류
				//$_Payment['es_check']		= $oper_info['pay_escrow'];	//에스크로 사용여부
				$_Payment['es_stats']		= "IN";						//에스크로 상태(데이콤으로 기본정보 발송)

				//2025-06-23 에스크로 사용여부 체크 수정
				if($oper_info[pay_escrow] == "Y"){
					if($json->useEscrow === false) {
						$_Payment[es_check] = "N";
					}else{
						$_Payment[es_check] = "Y";
					}
				}
				/*
				$_Payment['bankkind']		= $LGD_FINANCECODE;			//은행코드
				$_Payment['accountno']	= $LGD_ACCOUNTNUM;			//계좌번호
				$_Payment['tprice']		= $LGD_AMOUNT;				//결제금액
				$_Payment['cash_num']		= $LGD_CASHRECEIPTNUM;		//현금영수증 승인번호
				$_Payment['cash_type']	= $LGD_CASHRECEIPTKIND;		//현금영수증 종류
				$_Payment['cash_segno']	= $LGD_CASSEQNO;			//가상계좌 입금순서
				*/

				Exe_payment($_Payment);								//-- 결제처리(상태변경,주문 업데이트)
				Exe_reserve();										//-- 적립금 처리 : 적립금 사용시 적립금 감소
				Exe_stock();										//-- 재고처리
				Exe_delbasket();									//-- 장바구니 삭제
				$resp = true;
				$resultMSG ="OK"; 

				/*
					작업자	: 정나혜 
					작업일시	: 2022-05-11
					작업내용	: 가상계좌 입금통보시 메일 or sms 보내기
				*/

				$re_info['name']   = $order_info->send_name;
				$re_info['email']  = $order_info->send_email;
				$re_info['hphone'] = $order_info->send_hphone;

				$status = 'OY';
				
				include $_SERVER['DOCUMENT_ROOT'].'/twcenter/product/order_mail.php';
				send_mailsms("order_pay", $re_info, $ordmail);

			} else {
				header("HTTP/1.0 400");
				exit;
			}
		}
	}

	$log .= "\r\n---------------------------End----------------------------------\r\n";

	@make_log(LOG_PATH."toss_vir_log.log", $log);

?>
