<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

$q_sql  = "";
for($i=1; $i<=13; $i++) {
	$j = ($i == 1) ? "" : $i;
	$q_sql .= " and optcode".$j." = '".${'optcode'.$j.'_opt'}."' \n";
}

$bsql = "
	select * 
	  from wiz_wishlist 
	 where memid = '".$wiz_session['id']."' 
	   $q_sql
	   and prdcode = '".$prdcode."'
";
$bresult = query($bsql);
$num_brow = sql_fetch_row($bresult);

$basket_exist = 0;
if($num_brow != "0"){
	$brow = sql_fetch_arr($bresult);
	$upidx = $brow['idx'];
	++$basket_exist;
}

//if($basket_exist > 0) {
//	echo "9999";
//	exit;
//}

if($basket_exist <= 0){
	
	$sellprice = $tmp_sellprice_opt + $opt_price1 + $opt_price2 + $opt_price3 + $opt_price8 + $opt_price9 + $opt_price10 + $opt_price11;
	$reserve   = $tmp_reserve_opt + $opt_reserve1 + $opt_reserve2 + $opt_reserve3 + $opt_reserve8 + $opt_reserve9 + $opt_reserve10 + $opt_reserve11;

	if($prdcode != "") {

		$opt_title_sql  = "";
		$opt_code_sql   = "";

		for($i=2; $i<=13; $i++) {
			$opt_title_sql  .= " , opttitle".$i."         = '".${'opttitle'.$i.'_opt'}."'       ";
			$opt_code_sql   .= " , optcode".$i."          = '".${'optcode'.$i.'_opt'}."'        ";
		}

		$sql_com = "";
		$sql_com .= " memid             = '".$wiz_session['id']."'             ";
		$sql_com .= " , prdcode         = '".$prdcode."'                       ";
		$sql_com .= " , opttitle        = '".$opttitle_opt."'                  ";
		$sql_com .= " , optcode         = '".$optcode_opt."'                   ";
		$sql_com .= " {$opt_title_sql}                                         ";
		$sql_com .= " {$opt_code_sql}                                          ";
		$sql_com .= " , amount          = '".$amount_opt."'                    ";
		$sql_com .= " , wdate           =  now()                               ";
		$sql_com .= " , price           = '".$sellprice."'                     ";
		$sql_com .= " , reserve         = '".$reserve."'                       ";

		$sql = "insert into wiz_wishlist set {$sql_com} ";
		query($sql);

	}
}

?>