<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if($mode == "barobill") {

/************************************************************************************************************
 * # Subject   : 바로빌 연동서비스 웹서비스 참조(WebService Reference) URL	
 * # File	   : BaroService_TI.php
 * # Test URL  : 'http://testws.baroservice.com/TI.asmx?WSDL'
 * # Real URL  : 'http://ws.baroservice.com/TI.asmx?WSDL'
 ************************************************************************************************************/
$kr_name 		= $site_info['com_name'];							/* 상호명 */
$kr_owner 		= $site_info['com_owner'];							/* 대표자명 */
$kr_num			= str_replace("-", "", $site_info['com_num']);		/* 사업자번호 */
$kr_address		= $site_info['com_address'];						/* 주소 */
$kr_kind 		= $site_info['com_kind'];							/* 업태 */
$kr_class		= $site_info['com_class'];							/* 종목 */

$kr_damdang		= $site_info['com_name'];							/* 담당자 이름 */
$kr_eamil		= $site_info['site_email'];							/* 담당자 이메일 */
$kr_tel			= $site_info['site_tel'];							/* 전화번호 */
$kr_hand		= $site_info['site_hand'];							/* 담당자 휴대폰 */

if($oper_info['tax_type'] == "T" || $oper_info['tax_type'] == "") {
	$BaroService_URL = 'http://testws.baroservice.com/TI.asmx?WSDL';
} else if($oper_info['tax_type'] == "R") {
	$BaroService_URL = 'http://ws.baroservice.com/TI.asmx?WSDL';
}

$BaroService_TI = new SoapClient($BaroService_URL, array(
	'trace' => 'true',
	'encoding' => 'UTF-8'
));
					
function getErrStr($CERTKEY, $ErrCode){
	global $BaroService_TI;

	$ErrStr = $BaroService_TI->GetErrString(array(
		'CERTKEY' => $CERTKEY,
		'ErrCode' => $ErrCode
	))->GetErrStringResult;

	return $ErrStr;
}
/**********************************************************************************************************/

	$array_selected = explode("|",$selected);
	$i=0;
	$error_cnt = 0;
	$error_arr = array();
	$prd_name = "";
	while($array_selected[$i]) {

		$orderid = $array_selected[$i];

		/************************************************************************************************************
		 *
		 * # 바로빌 세금계산서 등록부분 시작 ▶▶▶▶▶▶▶▶▶▶
		 *
		 ************************************************************************************************************/
		$_mData2 = sql_fetch(" SELECT * FROM wiz_tax WHERE orderid = '".$orderid."' ");

		$Baro_BILL_sano = str_replace("-", "", $_mData2['com_num']);	// 사업자번호
		$Baro_BILL_rst  = $_mData2['tax_pub'];							// 바로빌 발급결과
		$supp_price		= $_mData2['supp_price'];						// 공급가액
		$tax_price		= $_mData2['tax_price'];						// 세액

		$prd_info = explode("^^", $_mData2['prd_info']);
		$kk = 0;
		if($prd_info) {
			for($ii = 0; $ii < count($prd_info); $ii++) {
				if(!empty($prd_info[$ii])) {
					$tmp_prd = explode("^", $prd_info[$ii]);
					if($ii < 1) $prd_name = cut_str($tmp_prd[0], 25);
					$kk++;
				}
			}
		}

		if($kk > 1) {
			$prd_name .= " 외 ".($kk-1)."건";
		}


		if($Baro_BILL_sano > 0 && $Baro_BILL_rst <> 'Y') {

			$_oData = sql_fetch("select send_id,total_price from wiz_order where orderid='".$orderid."' ");
			$_gData = get_mem($_oData['send_id']);

			$CERTKEY = $oper_info['tax_certkey'];						//실제 인증키
		
			$IssueDirection = 1;										//1-정발행, 2-역발행(위수탁 세금계산서는 정발행만 허용)
			$TaxInvoiceType = 1;										//1-세금계산서, 2-계산서, 4-위수탁세금계산서, 5-위수탁계산서
		
			//-------------------------------------------
			//과세형태
			//-------------------------------------------
			//TaxInvoiceType 이 1,4 일 때 : 1-과세, 2-영세
			//TaxInvoiceType 이 2,5 일 때 : 3-면세
			//-------------------------------------------
			$TaxType	 = 1;
			$TaxCalcType = 1;						//세율계산방법 : 1-절상, 2-절사, 3-반올림
		
			$PurposeType = 2;						//1-영수, 2-청구

			//-------------------------------------------
			//수정사유코드
			//-------------------------------------------
			//공백-일반세금계산서, 1-기재사항의 착오 정정, 2-공급가액의 변동, 3-재화의 환입, 4-계약의 해제, 5-내국신용장 사후개설, 6-착오에 의한 이중발행
			//-------------------------------------------
			$ModifyCode = '';
			
			$Kwon		= '';						//별지서식 11호 상의 [권] 항목
			$Ho			= '';						//별지서식 11호 상의 [호] 항목
			$SerialNum	= '';						//별지서식 11호 상의 [일련번호] 항목
		
			//-------------------------------------------
			//공급가액 총액
			//-------------------------------------------
			$AmountTotal = $supp_price;
		
			//-------------------------------------------
			//세액합계
			//-------------------------------------------
			//$TaxType 이 2 또는 3 으로 셋팅된 경우 0으로 입력
			//-------------------------------------------
			$TaxTotal = $tax_price;
		
			//-------------------------------------------
			//합계금액
			//-------------------------------------------
			//공급가액 총액 + 세액합계 와 일치해야 합니다.
			//-------------------------------------------
			$TotalAmount = (int)$AmountTotal+$TaxTotal;
			
			$Cash		= '';							//현금
			$ChkBill	= '';							//수표
			$Note		= '';							//어음
			$Credit		= '';							//외상미수금

			/* 비고 */
			//list($e_juno1, $e_juno2) = explode("-", $_mData2['juno']);
			//$e_juno2 = ($len = mb_strlen($e_juno2))>2 ? mb_substr($e_juno2,0,1).str_repeat('*',$len-1) : $e_juno2;
			//$juno_num = $e_juno1."-".$e_juno2;
			//$bigo_data  = $_mData2['name']." (".$juno_num.") ".$_mData2['suryo']."<br>".$_mData2['goga'];
		
			$item   = $prd_name;

			$Remark1 = "";
			$Remark2 = "";
			$Remark3 = "";
		
			$bdate = $_mData2['tax_date'];
			if($bdate != "") {
				$bdate_conv = str_replace("-","",$bdate);
			} else {
				$bdate_conv = date("Ymd");
			}
			$WriteDate = $bdate_conv;										//작성일자 (YYYYMMDD), 공백입력 시 Today로 작성됨.
		
			//-------------------------------------------
			//공급자 정보 - 정발행시 세금계산서 작성자
			//------------------------------------------

			$MgtNum_creativ = "sodiff".$orderid;

			$InvoicerParty = array(
				'MgtNum' 		=> $orderid,								//정발행시 필수입력 - 연동사부여 문서키
				'CorpNum' 		=> $kr_num,									//필수입력 - 연계사업자 사업자번호 ('-' 제외, 10자리) 
				'TaxRegID' 		=> '',
				'CorpName' 		=> $kr_name,								//필수입력
				'CEOName' 		=> $kr_owner,								//필수입력
				'Addr' 			=> $kr_address,
				'BizType' 		=> $kr_kind,
				'BizClass' 		=> $kr_class,
				'ContactID' 	=> $oper_info['tax_id'],					//필수입력 - 담당자 바로빌 아이디 
				'ContactName' 	=> $kr_damdang,								//필수입력
				'TEL' 			=> $kr_tel,
				'HP' 			=> $kr_hand,
				'Email' 		=> $kr_eamil								//필수입력
			);
		
			//-------------------------------------------
			//공급받는자 정보 - 역발행시 세금계산서 작성자
			//------------------------------------------
			$InvoiceeParty = array(
				'MgtNum' 		=> '',										//역발행시 필수입력 - 연동사부여 문서키
				'CorpNum' 		=> $Baro_BILL_sano,							//필수입력
				'TaxRegID' 		=> '',
				'CorpName' 		=> $_mData2['com_name'],					//필수입력
				'CEOName' 		=> $_mData2['com_owner'],					//필수입력
				'Addr' 			=> $_mData2['com_address1']." ".$_mData2['com_address2'],
				'BizType' 		=> $_mData2['com_kind'],
				'BizClass' 		=> $_mData2['com_class'],
				'ContactID' 	=> '',										//역발행시 필수입력 - 담당자 바로빌 아이디
				'ContactName' 	=> $_mData2['com_name'],					//필수입력
				'TEL' 			=> $_mData2['com_tel'],
				'HP' 			=> $_gData['hphone'],
				'Email' 		=> $_mData2['com_email']					//역발행시 필수입력
			);
		
			//-------------------------------------------
			//수탁자 정보 - 위수탁 발행시 세금계산서 작성자
			//------------------------------------------
			$BrokerParty = array(
				'MgtNum' 		=> '',				//위수탁발행시 필수입력 - 연동사부여 문서키
				'CorpNum' 		=> '',				//위수탁발행시 필수입력 - 연계사업자 사업자번호 ('-' 제외, 10자리)
				'TaxRegID' 		=> '',
				'CorpName' 		=> '',				//위수탁발행시 필수입력
				'CEOName' 		=> '',				//위수탁발행시 필수입력
				'Addr' 			=> '',
				'BizType' 		=> '',
				'BizClass' 		=> '',
				'ContactID' 	=> '',				//위수탁발행시 필수입력 - 담당자 바로빌 아이디
				'ContactName' 	=> '',				//위수탁발행시 필수입력	
				'TEL' 			=> '',
				'HP' 			=> '',
				'Email' 		=> ''				//위수탁발행시 필수입력	
			);
		
			//-------------------------------------------
			//품목
			//-------------------------------------------
			$TaxInvoiceTradeLineItems = array(
				'TaxInvoiceTradeLineItem'	=> array(
					array(
						'PurchaseExpiry'=> $bdate_conv,			//YYYYMMDD
						'Name'			=> $item,
						'Information'	=> '',
						'ChargeableUnit'=> '1',
						'UnitPrice'		=> $supp_price,
						'Amount'		=> $supp_price,
						'Tax'			=> '0',
						'Description'	=> ''
					)
				)
			);
		
			//-------------------------------------------
			//전자세금계산서
			//-------------------------------------------
			$TaxInvoice = array(
				'InvoiceKey'				=> '',
				'InvoiceeASPEmail'			=> '',
				'IssueDirection'			=> $IssueDirection,
				'TaxInvoiceType'			=> $TaxInvoiceType,
				'TaxType'					=> $TaxType,
				'TaxCalcType'				=> $TaxCalcType,
				'PurposeType'				=> $PurposeType,
				'ModifyCode'				=> $ModifyCode,
				'Kwon'						=> $Kwon,
				'Ho'						=> $Ho,
				'SerialNum'					=> $SerialNum,
				'Cash'						=> $Cash,
				'ChkBill'					=> $ChkBill,
				'Note'						=> $Note,
				'Credit'					=> $Credit,
				'WriteDate'					=> $WriteDate,
				'AmountTotal'				=> $AmountTotal,
				'TaxTotal'					=> $TaxTotal,
				'TotalAmount'				=> $TotalAmount,
				'Remark1'					=> $Remark1,
				'Remark2'					=> $Remark2,
				'Remark3'					=> $Remark3,
				'InvoicerParty'				=> $InvoicerParty,
				'InvoiceeParty'				=> $InvoiceeParty,
				'BrokerParty'				=> $BrokerParty,
				'TaxInvoiceTradeLineItems'	=> $TaxInvoiceTradeLineItems
			);
		
			$SendSMS    = true;							//문자 발송여부 (공급받는자 정보의 HP 항목이 입력된 경우에만 발송됨)
			$ForceIssue = false;						//가산세가 예상되는 세금계산서 발행 여부
			$MailTitle  = '';							//전송되는 이메일의 제목 설정 (공백 시 바로빌 기본 제목으로 전송됨)

			//-------------------------------------------

			//정발행
			$Result = $BaroService_TI->RegistAndIssueTaxInvoice(array(
				'CERTKEY'	=> $CERTKEY,
				'CorpNum'	=> $TaxInvoice['InvoicerParty']['CorpNum'],
				'Invoice'	=> $TaxInvoice,
				'SendSMS'	=> $SendSMS,
				'ForceIssue'=> $ForceIssue,
				'MailTitle'	=> $MailTitle,
			))->RegistAndIssueTaxInvoiceResult;

			/*
			//역발행
			$Result = $BaroService_TI->RegistTaxInvoiceReverse(array(
				'CERTKEY'	=> $CERTKEY,
				'CorpNum'	=> $TaxInvoice['InvoiceeParty']['CorpNum'],
				'Invoice'	=> $TaxInvoice
			))->RegistTaxInvoiceReverseResult;
		
			//위수탁
			$Result = $BaroService_TI->RegistBrokerTaxInvoice(array(
				'CERTKEY'	=> $CERTKEY,
				'CorpNum'	=> $TaxInvoice['BrokerParty']['CorpNum'],
				'Invoice'	=> $TaxInvoice
			))->RegistBrokerTaxInvoiceResult;
			*/

			//echo $MgtNum_creativ."::".$_mData2['sano']."::".$Result;

			//-- 바로빌 발행후 wiz_tax 업데이트

			/*
			 * 메뉴얼 http://dev.barobill.co.kr/manual/manual/index.html#TI#QUICK#RegistAndIssueTaxInvoice
			 * 반환타입 : int
			 * 반환값 - 1:성공, 음수:오류코드반환
			 */

			if($Result > 0) {

				// 국세청승인번호얻기
				$Result2 = $BaroService_TI->GetTaxInvoiceState(array(
					'CERTKEY'		=> $CERTKEY,
					'CorpNum'		=> $TaxInvoice['InvoicerParty']['CorpNum'],
					'MgtKey'		=> $orderid
				))->GetTaxInvoiceStateResult;

				if($Result2->BarobillState > 0) {

					$sql_com = "";
					$sql_com .= " SET tax_pub       = 'Y', tax_no='".$Result2->NTSSendKey."', NTSConfirmNum = '".$Result2->NTSSendKey."', bill_err_code = '', bill_err_msg = '', wdate = now()         ";
					$sql_com .= " WHERE orderid     = '" . $orderid . "'                             ";
					query(" UPDATE wiz_tax $sql_com ");

				}

			} else {

				$json_string = file_get_contents('errcode.json');
				$err = json_decode($json_string, true);
				$err_msg = $err[$Result];

				$error_cnt++;
				$error_arr[] = $orderid."(".$_mData2['com_name'].")";
				$sql_com = "";
				$sql_com .= " SET tax_pub       = 'N', bill_err_code = '".$Result."', bill_err_msg = '".$err_msg."'       ";
				$sql_com .= " WHERE orderid     = '" . $orderid . "'                                                     ";
				query(" UPDATE wiz_tax $sql_com ");
			}


		}
		/************************************************************************************************************
		 *
		 * # 바로빌 세금계산서 등록부분 끝 ◀◀◀◀◀◀◀◀◀◀
		 *
		 ************************************************************************************************************/

	$i++;
	}
			
	if($error_cnt > 0){
		// 실패건이 있을 시
		$return_txt  = "바로빌 세금계산서 발행에 실패하였습니다.\n정확한 발행여부는 바로빌 사이트에 문의바랍니다.\n";

		// 발행실패한 계산서번호 안내.
		$return_txt .= "- 세금계산서발행 실패 계산서번호 -\n";
		foreach($error_arr as $k => $tno){
			$return_txt .= $tno."\n";
		}

	}else{
		// 실패건이 없을 시,
		$return_txt  = "바로빌 세금계산서가 발행되었습니다.\n정확한 발행여부는 바로빌 사이트를 이용해주세요.";
	}

	echo json_encode(json_result("00", $return_txt));
	exit;


}

?>