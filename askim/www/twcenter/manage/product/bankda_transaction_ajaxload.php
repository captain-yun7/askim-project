<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bankda_info.php";

include "../../../comm/API/xml/xml.php";

$param = array(
	'service_type'  => $oper_info['bankda_service'],
	'partner_id'    => $oper_info['bankda_partner_id'],
	'partner_pw'    => $oper_info['bankda_partner_pw'],
	'user_id'       => $bankda_info['bankda_id'],
	'bkcode'        => 0,
	'char_set'      => 'utf-8',
	'sort_order'    => 'D'
);

$get_url = "https://ssl.bankda.com/partnership/partner/xmldown.php";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $get_url);
curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$xml = curl_exec($curl);

$parser = new XMLParser($xml);
$parser->Parse();

foreach($parser->document->account as $account) {
	foreach($account->accinfo as $accinfo) {

		$_actnumber         = $accinfo->tagAttrs['actnumber'];		//-- 계좌번호
		$_bkname            = $accinfo->tagAttrs['bkname'];			//-- 거래은행명
		$_bkcode            = $accinfo->tagAttrs['bkcode'];			//-- 거래내역id(뱅크다의 일련번호)
		$_bkdate            = $accinfo->tagAttrs['bkdate'];			//-- 거래일자
		$_bktime            = $accinfo->tagAttrs['bktime'];			//-- 거래시간 (제공안하는 은행도 있어 노출안시킴)
		$_bkjukyo           = $accinfo->tagAttrs['bkjukyo'];		//-- 입금자명
		$_bkcontent         = $accinfo->tagAttrs['bkcontent'];		//-- 거래방식 등 부가정보
		$_bketc             = $accinfo->tagAttrs['bketc'];			//-- 점포명 등 부가정보
		$_bkinput           = $accinfo->tagAttrs['bkinput'];		//-- 입금액
		$_bkoutput          = $accinfo->tagAttrs['bkoutput'];		//-- 출금액
		$_bkjango           = $accinfo->tagAttrs['bkjango'];		//-- 거래후 잔액
		$_mid               = $accinfo->tagAttrs['mid'];			//-- 계좌소유자 ID

		$sql = "select bkcode from bankda_io_history where bkcode = '$_bkcode' ";
		$res = query($sql);
		$_hinfo = sql_fetch_arr($res);

		if(empty($_hinfo['bkcode']) || $_hinfo['bkcode'] != $_bkcode) {

			$sql_com = "";
			$sql_com .= " actnumber           = '$_actnumber'                    ";
			$sql_com .= " , bkname            = '$_bkname'                       ";
			$sql_com .= " , bkcode            = '$_bkcode'                       ";
			$sql_com .= " , bkdate            = '$_bkdate'                       ";
			$sql_com .= " , bktime            = '$_bktime'                       ";
			$sql_com .= " , bkjukyo           = '$_bkjukyo'                      ";
			$sql_com .= " , bkcontent         = '$_bkcontent'                    ";
			$sql_com .= " , bketc             = '$_bketc'                        ";
			$sql_com .= " , bkinput           = '$_bkinput'                      ";
			$sql_com .= " , bkoutput          = '$_bkoutput'                     ";
			$sql_com .= " , bkjango           = '$_bkjango'                      ";
			$sql_com .= " , mid               = '$_mid'                          ";

			$sql = "insert into bankda_io_history set {$sql_com} ";
			query($sql);

			$result = "0000";
		
		} else {

		}

	}
}

$_osql = "
	SELECT *
		 , (SELECT COUNT(*)
			  FROM bankda_io_history
			 WHERE actnumber = brd.actnumber
			   AND bkjukyo = brd.bkjukyo
			   AND bkinput = brd.bkinput
			   AND bkdate >= '".$oper_info['bankda_service_date']."'
			 GROUP BY actnumber, bkjukyo, bkinput
			 ORDER BY bkdate DESC, bktime DESC
			 ) AS matching_cnt
	  FROM bankda_io_history brd
	 WHERE brd.bkdate >= '".$oper_info['bankda_service_date']."'
";
$_ores = query($_osql);
while($_oinfo = sql_fetch_arr($_ores)) {

	$matching_cnt = $_oinfo['matching_cnt'];
	$bk_date = date("Y-m-d H:i:s", strtotime($_oinfo['bkdate'].$_oinfo['bktime']));

	/*
	작업일시	: 2020-10-13
	작업자명	: 이상민
	작업내용	: 입금자명과 금액이 동일한 주문이 2건이상 존재할 때 자동으로 매칭시키지 않고 실패처리하여 관리자가 수동매칭시킬 수 있도록 구분값 추가
	*/
	$sql = "
			select
				count(*) as cnt
			from
				wiz_order
			where
				bk_actnumber = '".$_oinfo['actnumber']."' 
				AND account_name = '".$_oinfo['bkjukyo']."'
				AND total_price = '".$_oinfo['bkinput']."'
				AND status = 'OR'
				AND pay_method = 'PB'
	";
	$row = sql_fetch($sql);
	if($row["cnt"] > 1){
		$sql = "
			UPDATE bankda_io_history 
			   SET bkmatchres = 'MS' 
				 , bkmatchdate = now()
			 WHERE actnumber = '".$_oinfo['actnumber']."' 
			   AND bkjukyo = '".$_oinfo['bkjukyo']."'
			   AND bkinput = '".$_oinfo['bkinput']."'
			   AND bkmatchres IN('MT','MA','MB')
			";
		query($sql);
	}

	/*
	작업자명	: 이상민
	작업일시	: 2020-10-06
	작업내용	: 자동입금확인 시 주문서 상태값 "입금대기"인 것 중 가장 먼저 주문된 내역 1건을 입금완료 시키도록 수정
	*/
	$sql = "
		SELECT orderid
		  FROM wiz_order
		 WHERE bk_actnumber = '".$_oinfo['actnumber']."' 
		   AND account_name = '".$_oinfo['bkjukyo']."'
		   AND total_price = '".$_oinfo['bkinput']."'
		   AND status = 'OR'
		   AND pay_method = 'PB'
		ORDER BY orderid asc
		 LIMIT 0, 1
	";
	$res = query($sql);
	$row = sql_fetch_arr($res);

	$fix_result = array("MT","MA","MB");
	if(in_array($_oinfo['bkmatchres'], $fix_result)) {

		if($matching_cnt > 0) {

			if($matching_cnt > 1) {
				$sql = "
					UPDATE bankda_io_history 
					   SET bkmatchres = 'MB' 
						 , bkmatchdate = now()
					 WHERE actnumber = '".$_oinfo['actnumber']."' 
					   AND bkjukyo = '".$_oinfo['bkjukyo']."'
					   AND bkinput = '".$_oinfo['bkinput']."'
					   AND bkmatchres IN('MT','MA','MB')
					";
				query($sql);

			} else if($matching_cnt == 1) {

				if($row['orderid']) {

					$sql = "
						UPDATE bankda_io_history 
						   SET bkmatchres = 'MC' 
							 , bkmatchdate = now()
							 , orderid = '".$row['orderid']."'
						 WHERE actnumber = '".$_oinfo['actnumber']."' 
						   AND bkjukyo = '".$_oinfo['bkjukyo']."'
						   AND bkinput = '".$_oinfo['bkinput']."'
						   AND bkmatchres IN('MT','MA','MB')
					";
					query($sql);

					$sql = "
						UPDATE wiz_order 
						   SET status = 'OY'
							 , pay_date = now()
							 , bk_match_result = 'MC'
							 , bk_match_date = now()
							 , bk_code = '".$_oinfo['bkcode']."'
							 , bk_date = '".$bk_date."'
						 WHERE bk_actnumber = '".$_oinfo['actnumber']."' 
						   AND account_name = '".$_oinfo['bkjukyo']."'
						   AND total_price = '".$_oinfo['bkinput']."'
						   AND orderid = ''".$row['orderid']."'
					";
					query($sql);

				} else {

					$sql = "
						UPDATE bankda_io_history 
						   SET bkmatchres = 'MA' 
							 , bkmatchdate = now()
						 WHERE actnumber = '".$_oinfo['actnumber']."' 
						   AND bkjukyo = '".$_oinfo['bkjukyo']."'
						   AND bkinput = '".$_oinfo['bkinput']."'
						   AND bkmatchres IN('MT','MA','MB')
					";
					query($sql);

				}
				
			} else if($matching_cnt == 0) {

				$sql = "
					UPDATE bankda_io_history 
					   SET bkmatchres = 'MA' 
						 , bkmatchdate = now()
					 WHERE actnumber = '".$_oinfo['actnumber']."' 
					   AND bkjukyo = '".$_oinfo['bkjukyo']."'
					   AND bkinput = '".$_oinfo['bkinput']."'
					   AND bkmatchres IN('MT','MA','MB')
				";
				query($sql);

			}

		} else {

			$sql = "
				update bankda_io_history 
				   SET bkmatchres = 'MA' 
					 , bkmatchdate = now()
				 WHERE actnumber = '".$_oinfo['actnumber']."' 
				   AND bkjukyo = '".$_oinfo['bkjukyo']."'
				   AND bkinput = '".$_oinfo['total_price']."'
				   AND orderid = ''
				   AND bkmatchres IN('MT','MA','MB')
			";
			query($sql);

		}

	}

	$sql = "
		UPDATE wiz_order 
		   SET bk_match_date = now()
		 WHERE bk_match_result IN('MT','MA','MB')
	";
	query($sql);

}

echo json_encode(array("result"=>$result));
exit;
?>
