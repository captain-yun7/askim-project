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
	$BaroService_URL = 'http://testws.baroservice.com/CASHBILL.asmx?WSDL';
} else if($oper_info['tax_type'] == "R") {
	$BaroService_URL = 'http://ws.baroservice.com/CASHBILL.asmx?WSDL';
}

$BaroService_CASHBILL = new SoapClient($BaroService_URL, array(
	'trace' => 'true',
	'encoding' => 'UTF-8'
));
					
function getErrStr($CERTKEY, $ErrCode){
	global $BaroService_CASHBILL;

	$ErrStr = $BaroService_CASHBILL->GetErrString(array(
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
		 * # 바로빌 현금영수증 등록부분 시작 ▶▶▶▶▶▶▶▶▶▶
		 *
		 ************************************************************************************************************/
		$_mData2 = sql_fetch(" SELECT * FROM wiz_tax WHERE orderid = '".$orderid."' ");

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


		if($_mData2['tax_type'] == 'C' && $Baro_BILL_rst <> 'Y') {

			$_oData = sql_fetch("select send_id,total_price from wiz_order where orderid='".$orderid."' ");
			$_gData = get_mem($_oData['send_id']);

			$CERTKEY = $oper_info['tax_certkey'];						//실제 인증키

			$bdate = $_mData2['tax_date'];
			if($bdate != "") {
				$bdate_conv = str_replace("-","",$bdate);
			} else {
				$bdate_conv = date("Ymd");
			}

			$cash_type    = $_mData2['cash_type'];
			if($cash_type == "C") {
				if($_mData2['cash_type2'] == "COMNUM") {
					$consumer_chk = str_replace("-","",$_mData2['cash_info2']);		// 사업자번호
					$TradeUsage   = 2;
					$TradeMethod  = 4;
				} else if($_mData2['cash_type2'] == "CARDNUM") {
					$consumer_chk = str_replace("-","",$_mData2['cash_info']);		// 카드번호
					$TradeUsage   = 1;
					$TradeMethod  = 1;
				}
			} else if($cash_type == "P") {
				if($_mData2['cash_type2'] == "HPHONE") {
					$consumer_chk = str_replace("-","",$_mData2['cash_info3']);		// 휴대폰번호
					$TradeUsage   = 1;
					$TradeMethod  = 5;
				} else if($_mData2['cash_type2'] == "CARDNUM") {
					$consumer_chk = str_replace("-","",$_mData2['cash_info']);		// 카드번호
					$TradeUsage   = 1;
					$TradeMethod  = 1;
				}
			}
			//-------------------------------------------
			//현금영수증 발급
			//-------------------------------------------
			$CashBill = array(
				'MgtKey'				=> $orderid,					//연동사부여 문서키	
				'TradeDate'				=> $bdate_conv,					//거래일자 (YYYYMMDD), 공백입력 시 Today로 작성됨.
				'FranchiseCorpNum'		=> $kr_num,						//가맹점 사업자번호
				'FranchiseMemberID'		=> $oper_info['tax_id'],		//가맹점 바로빌 회원 아이디
				'FranchiseCorpName'		=> $kr_name,					//가맹점 회사명
				'FranchiseCEOName'		=> $kr_owner,					//가맹점 대표자명
				'FranchiseAddr'			=> $kr_address,					//가맹점 주소
				'FranchiseTel'			=> $kr_tel,						//가맹점 전화번호
				'IdentityNum'			=> $consumer_chk,				//소비자 신분확인번호 ("-" 를 제외한 주민등록번호/사업자번호/휴대폰번호/카드번호 중 택1)
				'HP'					=> '',							//소비자 휴대폰번호 (문자 전송시 활용)
				'Fax'					=> '',							//소비자 팩스번호 (팩스 전송시 활용)
				'Email'					=> '',							//소비자 이메일 (이메일 전송시 활용)
				'TradeType'				=> 'N',							//거래구분 : N-승인거래, D-취소거래
				'TradeUsage'			=> $TradeUsage,					//거래용도 : 1-소득공제용, 2-지출증빙용 (신분확인번호가 사업자번호인 경우 지출증빙용으로)
				'TradeMethod'			=> $TradeMethod,				//거래방법 : 1-카드, 3-주민등록번호, 4-사업자번호, 5-휴대폰번호 (신분확인번호 종류에 따라 선택)
				'ItemName'				=> $prd_name,					//품목명
				'Amount'				=> $supp_price,					//공급가액
				'Tax'					=> $tax_price,					//부가세
				'ServiceCharge'			=> '0',							//봉사료
				'CancelType'			=> '',							//취소사유 : 1-거래취소, 2-오류발행, 3-기타 (거래구분이 취소거래일 경우에만 작성)
				'CancelNTSConfirmNum'	=> '',							//취소할 원본 현금영수증의 국세청 승인번호
				'CancelNTSConfirmDate'	=> '',							//취소할 원본 현금영수증의 국세청 승인일자 (YYYYMMDD)
			);

			$Result = $BaroService_CASHBILL->RegistCashBill(array(
				'CERTKEY'	=> $CERTKEY,
				'CorpNum'	=> $CashBill['FranchiseCorpNum'],
				'UserID'	=> $CashBill['FranchiseMemberID'],
				'Invoice'	=> $CashBill
			))->RegistCashBillResult;

			if($Result > 0) {

				// 국세청승인번호얻기
				$Result2 = $BaroService_CASHBILL->GetCashBill(array(
					'CERTKEY'	=> $CERTKEY,
					'CorpNum'	=> $CashBill['FranchiseCorpNum'],
					'UserID'	=> $CashBill['FranchiseMemberID'],
					'MgtKey'	=> $orderid
				))->GetCashBillResult;

				if($Result2->TradeType == 'N' || $Result2->TradeType == 'D') {

					$sql_com = "";
					$sql_com .= " SET tax_pub       = 'Y', tax_no='".$Result2->NTSConfirmNum."', NTSConfirmNum = '".$Result2->NTSConfirmNum."', bill_err_code = '', bill_err_msg = '', wdate = now() ";
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
				$sql_com .= " WHERE orderid     = '" . $orderid . "'                                                      ";
				
				query(" UPDATE wiz_tax $sql_com ");
			}


		}
		/************************************************************************************************************
		 *
		 * # 바로빌 현금영수증 등록부분 끝 ◀◀◀◀◀◀◀◀◀◀
		 *
		 ************************************************************************************************************/

	$i++;
	}
			
	if($error_cnt > 0){
		// 실패건이 있을 시
		$return_txt  = "바로빌 현금영수증 발행에 실패하였습니다.\n정확한 발행여부는 바로빌 사이트에 문의바랍니다.\n";

		// 발행실패한 계산서번호 안내.
		$return_txt .= "- 현금영수증발행 실패번호 -\n";
		foreach($error_arr as $k => $tno){
			$return_txt .= $tno."\n";
		}

	}else{
		// 실패건이 없을 시,
		$return_txt  = "바로빌 현금영수증이 발행되었습니다.\n정확한 발행여부는 바로빌 사이트를 이용해주세요.";
	}

	echo json_encode(json_result("00", $return_txt));
	exit;


}

?>