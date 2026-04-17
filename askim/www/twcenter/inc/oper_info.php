<?php
$sql = "SELECT * FROM wiz_operinfo";
$oper_info = sql_fetch($sql);

$limit_disabled    = ($oper_info['unLimited'] == "Y") ? " disabled" : "";
$bankda_match_time = ($oper_info['bankda_match_time']/60)/1000;


$sql = "SELECT estimate_use, estimate_bigo FROM wiz_siteinfo";
$row = sql_fetch($sql);

$oper_info['estimate_use']  = $row['estimate_use'];
$oper_info['estimate_bigo'] = $row['estimate_bigo'];

$review_code = "review";
$sql = "select usetype from wiz_bbsinfo where code = '$review_code'";
$row = sql_fetch($sql);

$oper_info['review_usetype'] = $row['usetype'];

$qna_code = "qna";
$sql = "select usetype from wiz_bbsinfo where code = '$qna_code'";
$row = sql_fetch($sql);

$oper_info['qna_usetype'] = $row['usetype'];
$oper_info['prd_cnt']     = 5;

$tmp_bkn_list = array();
$tmp_bka_list = array();

$bnk_sql = "select bkname,bkacctno from bank_account";
$bnk_res = query($bnk_sql);
while($bnk_row = sql_fetch_arr($bnk_res)) {
	$tmp_bkn_list[] = $bnk_row['bkname'];
	$tmp_bka_list[] = $bnk_row['bkacctno'];
}

$receipt_pay_method_array  = array("PC","PN","PV","PB");	// 현금영수증/카드영수증 노출 결제방법
$receipt_status_array      = array("OY","DR","DI","DC");	// 현금영수증 출력가능 상태값
$cancel_pay_method_array   = array("PC","PN","PV","PH");	// 주문취소 노출 결제방법
$cancel_status_array       = array("OR","OY");				// 주문취소 가능상태값

$_del_type = array
(
	"DA"		   => array("DA","배송비 전액무료"),
	"DB"		   => array("DB","수신자부담(착불)"),
	"DC"		   => array("DC","고정배송비"),
	"DD"		   => array("DD","구매가격별 배송비")
);

$_bank_code = array
(
	"03"		   => array("03","기업은행"),
	"04"		   => array("04","국민은행"),
	"13"		   => array("13","농협"),
	"20"		   => array("20","우리은행"),
	"23"		   => array("23","제일(SC)은행"),
	"26"		   => array("26","신한은행"),
	"28"		   => array("28","씨티은행"),
	"31"		   => array("31","대구은행"),
	"32"		   => array("32","부산은행"),
	"34"		   => array("34","광주은행"),
	"35"		   => array("35","제주은행"),
	"37"		   => array("37","전북은행"),
	"39"		   => array("39","경북은행"),
	"45"		   => array("45","새마을금고"),
	"48"		   => array("48","신협"),
	"71"		   => array("71","우체국"),
	"81"		   => array("81","하나은행"),
	"88"		   => array("88","수협"),
	"89"		   => array("89","산업은행"),
	"91"		   => array("91","케이뱅크")
);

$_bank_code2 = array(
	"기업은행",
	"국민은행",
	"농협",
	"우리은행",
	"제일(SC)은행",
	"신한은행",
	"씨티은행",
	"대구은행",
	"부산은행",
	"광주은행",
	"제주은행",
	"전북은행",
	"경북은행",
	"새마을금고",
	"신협",
	"우체국",
	"하나은행",
	"수협",
	"산업은행",
	"케이뱅크"
);

$_bk_status_code = array
(
	""			   => array("","현재상태"),
//	"MT"		   => array("MT","입금대기"),
	"MC"		   => array("MC","매칭성공(자동)"),
	"MD"		   => array("MD","매칭성공(관리자)"),
	"MM"		   => array("MM","수동매칭"),
	"MA"		   => array("MA","매칭실패(불일치)"),
	"MB"		   => array("MB","매칭실패(동명이인)"),
	/*
	작업일시	: 2020-10-13
	작업자명	: 이상민
	작업내용	: 입금자명과 금액이 동일한 입금내역이 2건이상 존재할 때 자동으로 매칭시키지 않고 실패처리하여 관리자가 수동매칭시킬 수 있도록 구분값 추가
	*/
	"MS"		   => array("MS","매칭실패(주문금액동일)"),
	"ME"		   => array("ME","관리자입금확인"),
	"MF"		   => array("MF","관리자미확인")
);

/*$_bk_manual_status_code = array
(
	""			   => array("","현재상태"),
	"MA"		   => array("MA","매칭실패(불일치)"),
	"MB"		   => array("MB","매칭실패(동명이인)"),
	"MF"		   => array("MF","관리자미확인")
);*/

$_bk_manual_status_code = array
(
	""			   => array("","현재상태"),
	"MA"		   => array("MA","매칭실패(불일치)"),
	"MB"		   => array("MB","매칭실패(동명이인)")
);

?>