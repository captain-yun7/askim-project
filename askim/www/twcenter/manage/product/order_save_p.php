<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/oper_info.php"; ?>
<?

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "s_status=$s_status&srh_prev=$srh_prev&srh_next=$srh_next";
$param .= "&searchopt=$searchopt&searchkey=$searchkey&$menucodeParam";
if($reason != "") $param .= "&reason=$reason";
if($tax_type != "") $param .= "&tax_type=$tax_type";
//--------------------------------------------------------------------------------------------------

function changeStatus($orderid, $status, $basket_idx, $delsno="", $deldate="", $mresult=""){

	global $DOCUMENT_ROOT, $HTTP_HOST, $connect, $oper_info, $order_info, $site_info;

	// 운송장 번호가 있는 경우 update
	if(!empty($delsno)) {
		$sql = "
			update wiz_basket 
			   set del_num='$delsno'
			     , deliver_date='$deldate' 
			 where orderid='$orderid' 
			   and basketidx='$basket_idx'
		";
		query($sql);
	}

	$sql = "select * from wiz_order where orderid = '$orderid'";
	$order_info = sql_fetch_object($sql);

	$bsql = "select * from wiz_basket where orderid = '$orderid' and basketidx = '$basket_idx' ";
	$basket_info = sql_fetch_object($bsql);

	$ord_deliver_num  = $basket_info->deliver_num;
	$ord_deliver_date = $basket_info->deliver_date;

	$re_info['name']    = $order_info->send_name;
	$re_info['email']   = $order_info->send_email;
	$re_info['hphone']  = $order_info->send_hphone;

	$del_com = $oper_info['del_com'];

	/* -- -------------------------------------------------------------------------------- *\
	 * 알림톡 주문정보 공통
	\* -- -------------------------------------------------------------------------------- */
	if($site_info['alimtalk_use'] == 'Y' && !empty($site_info['alimtalk_senderkey'])) {

		$sql = "select count(orderid) as ordCnt, sum(amount) as ordAmt from wiz_basket where orderid= '$orderid'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		$total     = $row['ordCnt'];
		$total_sum = $row['ordAmt'];

		$bbsql = "select * from wiz_basket where orderid = '$orderid'";
		$bbresult = query($bbsql);

		while($bbrow = sql_fetch_obj($bbresult)){

			$prdname = "";
			$options = "";
			$amounts = "";

			if($total > 1){
				$payment_prdname = $bbrow->prdname." 외 ".($total-1)."개";
			}else{
				$payment_prdname = $bbrow->prdname;
			}

			$prdnames   = $payment_prdname."\n(총 주문수량 : ".$total_sum.")";

		}

	}

	if($basket_info->status){

		// 배송완료 → 다른 진행상태로 변경 시 배송완료수 -1
		if(!strcmp($basket_info->status, "DC") && strcmp($status, "DC")) {

			$sql = "
				select wb.prdcode
					 , wp.comcnt
				  from wiz_basket as wb 
				  left join wiz_product as wp 
				    on wb.prdcode = wp.prdcode
				 where wb.orderid = '$order_info->orderid'
				   and wb.basketidx = '$basket_idx'
			";
			$result = query($sql);
			while($row = sql_fetch_obj($result)){

				if($row->comcnt > 0) {
					$sql = "
						update wiz_product 
						   set comcnt = comcnt - 1 
						 where prdcode = '$row->prdcode'
					";
					query($sql);
				}

			}

		}

		// 주문취소, 환불완료 → 다른 진행상태로 변경 시 주문취소수 -1
		if((!strcmp($basket_info->status, "OC") && strcmp($status, "OC")) || (!strcmp($basket_info->status, "RC") && strcmp($status, "RC"))){

			$sql = "
				select wb.prdcode
					 , wp.cancelcnt
				  from wiz_basket as wb 
				  left join wiz_product as wp 
				    on wb.prdcode = wp.prdcode
				 where wb.orderid = '$order_info->orderid'
					   wb.basketidx = '$basket_idx'
			";
			$result = query($sql);
			while($row = sql_fetch_obj($result)){

				if($row->cancelcnt > 0) {
					$sql = "
						update wiz_product 
						   set cancelcnt = cancelcnt - 1 
						 where prdcode = '$row->prdcode'
					";
					query($sql);
				}

			}

		}

		// 입금확인시
		if($status == "OY"){

			$order_baskidx_cnt = count(explode('|', $order_info->basketidx));
			$qry = "
				select count(idx) as idx_cnt 
				  from wiz_basket 
				 where orderid='".$orderid."' 
				   and status='OY' 
			";
			$irow = sql_fetch_object($qry);
			$idx_cnt = $irow->idx_cnt;

			if($order_baskidx_cnt == $idx_cnt) {

				/*** 추천인 적립금 ***/
				if($oper_info['reserve_recom'] > 0){
					if(!empty($order_info->send_id)){
						$ch_sql = "select recom from wiz_member where id='$order_info->send_id' ";
						$ch_result = query($ch_sql);
						$ch_row = sql_fetch_obj($ch_result);

						$reserve_msg_ch = "추천인(".$order_info->send_id.") 적립금 ";
						$sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,wdate) values('', '$ch_row->recom', '$reserve_msg_ch',	$oper_info['reserve_recom'], '$orderid', now())";
						query($sql);
					}
				}

			}

			// 이전의 상태와 변경상태가 다른 경우에만
			if(strcmp($status, $basket_info->status)) {

				// 적립금사용 적용
				if($order_info->reserve_use > 0){

				$sql = "
					select count(idx) as total
					  from wiz_reserve 
					 where memid = '$order_info->send_id' 
					   and orderid = '$orderid' 
					   and reserve < 0 
					   and reservemsg like '상품구입시 사용%'
				";
				$total = sql_fetch_rows($sql);

					// 이미 적립금이 적용됬는지 체크
					if($total <= 0){
					    $reserve_msg = "상품구입시 사용";
					    $sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,basket_idx,wdate) values('', '$order_info->send_id', '$reserve_msg', -$order_info->reserve_use, '$orderid', '$basket_idx', now())";
					    query($sql);
					}

				}

				if($site_info['alimtalk_use'] == 'Y' && !empty($site_info['alimtalk_senderkey'])) {
					/**
					 * 입금확인일 경우 알림톡 발송
					 */
					$templateCode = $site_info['alimtalk_id']."_order_pay";

					$talk_info['prdname']    = $prdnames;
					$talk_info['prdoption']  = $options;
					$talk_info['prdamount']  = $amounts;
					$talk_info['name']       = $order_info->send_name;
					$talk_info['email']      = $order_info->send_email;
					$talk_info['hphone']     = $order_info->send_hphone;
					$return_code = send_alimtalk($templateCode,$talk_info);

				}

				$oper_time = ", pay_date = now()";
				/* == ==================================================================================================== == *\
				 * 뱅크다서비스 이용시 
				 * - 넘어온값이 MC(매칭성공된 건중 주문상태가 바뀌지 않은부분에 대해 관리자가 직접 입금확인(결제완료)처리시 MD(매칭성공:관리자)로 변경
				 * - 넘어온값이 MA,MB(불일치,동명이인)경우 관리자가 직접 입금확인상태로 변경시 ME(관리자입금확인)로 변경
				\* == ==================================================================================================== == */

				if($oper_info['bankda_use'] == "Y") {
					if($mresult == "MC") {
						$oper_time .= ", bk_match_result = 'MD', bk_match_date = now()";
					} else if($mresult == "MA" || $mresult == "MB") {
						$oper_time .= ", bk_match_result = 'ME', bk_match_date = now()";
					} else if($mresult == "MT") {
						$oper_time .= ", bk_match_result = 'ME', bk_match_date = now()";
					}
				}

				include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_mail.php";
				send_mailsms("order_pay", $re_info, $ordmail);

			}

		// 배송완료
		}else if(!strcmp($status, "DC")) {

			//적립금적용
			if($order_info->reserve_price > 0){

				$sql = "
					select idx 
					  from wiz_reserve 
					 where memid = '$order_info->send_id' 
					   and orderid = '$orderid' 
					   and reserve > 0
				";
				$total = sql_fetch_rows($sql);

				// 이미 적립금이 적용됬는지 체크
				if($total <= 0){
				    $reserve_msg = "상품구입시 적립됨";
					$sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,basket_idx,wdate) values('', '$order_info->send_id', '$reserve_msg', -$order_info->reserve_use, '$orderid', '$basket_idx', now())";
				    query($sql);
				}

			}

			// 마케팅분석 > 상품통계분석 > 배송완료 증가
			$sql = "
				select wb.prdcode
					 , wp.comcnt 
				  from wiz_basket as wb 
				  left join wiz_product as wp 
				    on wb.prdcode = wp.prdcode
				 where wb.orderid = '$order_info->orderid' 
				   and wb.basketidx = '$basket_idx'
			";
			$result = query($sql);
			while($row = sql_fetch_obj($result)){

				if(strcmp($basket_info->status, $status)) {
					$sql = "
						update wiz_product 
						   set comcnt = comcnt + 1 
						 where prdcode = '$row->prdcode'
					";
					query($sql);
				}
			}

			if($site_info['alimtalk_use'] == 'Y' && !empty($site_info['alimtalk_senderkey'])) {
				/**
				 * 배송완료 경우 알림톡 발송
				 */
				$templateCode = $site_info['alimtalk_id']."_order_deli_end";
				$talk_info['prdname']    = $prdnames;
				$talk_info['prdoption']  = $options;
				$talk_info['prdamount']  = $amounts;
				$talk_info['name']       = $order_info->send_name;
				$talk_info['email']      = $order_info->send_email;
				$talk_info['hphone']     = $order_info->send_hphone;
				$return_code = send_alimtalk($templateCode,$talk_info);

			}

			$oper_time = ", send_date = now()";

			include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_mail.php";
			send_mailsms("order_deli_end", $re_info, $ordmail);

		// 주문취소시, 환불완료시
		}else if($status == "OC" || $status == "RC"){

			$order_baskidx_cnt = count(explode('|', $order_info->basketidx));
			$qry = "
				select count(idx) as idx_cnt 
				  from wiz_basket 
				 where orderid='".$orderid."' 
				   and (status='OC' or status='RC') 
			";
			$ires = query($qry);
			$irow = sql_fetch_obj($ires);
			$idx_cnt = $irow->idx_cnt;

			/* 주문된 상품갯수와 처리상태의 갯수가 일치할때... */
			if($order_baskidx_cnt == $idx_cnt) {

				/*** 추천인 적립금 차감 ***/
				if($oper_info['reserve_use'] == 'Y') {
					
					if($oper_info['recom_use'] == 'Y' && $oper_info['reserve_recom'] > 0){
						if(!empty($order_info->send_id)){
							$ch_sql = "select recom from wiz_member where id='$order_info->send_id' ";
							$ch_result = query($ch_sql);
							$ch_row = sql_fetch_obj($ch_result);

							$sql = "delete from wiz_reserve where memid='$ch_row->recom' and orderid='$order_info->orderid'";
							query($sql);
						}
					}
				}

				if($status == "OC") {
					$sql = "update wiz_order set status = 'OC' where orderid='$order_info->orderid' ";
					query($sql);
				}

				if($status == "RC") {
					$sql = "update wiz_order set status = 'RC' where orderid='$order_info->orderid' ";
					query($sql);
				}

			}

			//적립금적용(해당주문에 대한 적립내역 삭제)
			$sql = "
				delete from wiz_reserve 
				 where memid='$order_info->send_id' 
				   and orderid='$order_info->orderid' 
				   and basket_idx='$basket_idx' 
			";
			query($sql);

			// 주문취소 시 주문접수일 경우를 제외하고 재고 증가 -> 주문접수인 경우에도 재고 증가
			// 주문취소, 주문완료 수량적용
			$sql = "
				select wb.prdcode
					 , wb.amount
					 , wb.optcode
					 , wb.status
					 , wp.optcode as p_optcode
					 , wp.optcode2 as p_optcode2
					 , wp.optvalue as p_optvalue
					 , wp.opt_use
					 , wp.shortage
					 , wp.stock
					 , wp.import_order
				  from wiz_basket as wb 
				  left join wiz_product as wp 
					on wb.prdcode = wp.prdcode
				 where wb.orderid = '$order_info->orderid'
				   and wb.basketidx = '$basket_idx'
			";
			$result = query($sql);
			while($row = sql_fetch_obj($result)){
				// 옵션별 재고관리 없는 제품이라면 전체재고 증가
				if(strcmp($row->opt_use, "Y")){

					$sql = "
						update wiz_product 
						   set cancelcnt = if(cancelcnt is null, 1, cancelcnt + 1)
						     , stock = stock + $row->amount 
						 where prdcode = '$row->prdcode'
					";
					query($sql);

				// 옵션별 재고관리 상품
				} else {

					$opt_list_app = "";

					$opt1_arr = explode("^", $row->p_optcode);
					$opt2_arr = explode("^", $row->p_optcode2);
					$opt_tmp  = explode("^^", $row->p_optvalue);

					list($optcode1, $optcode2) = explode("/", $row->optcode);

					if(strcmp($row->status, "CC")) {

						$opt1_cnt = count($opt1_arr) - 1;
						$opt2_cnt = count($opt2_arr) - 1;

						if($opt1_cnt < 1) $opt1_cnt = 1;
						if($opt2_cnt < 1) $opt2_cnt = 1;

						$no = 0;
						for($ii = 0; $ii < $opt1_cnt; $ii++) {
							for($jj = 0; $jj < $opt2_cnt; $jj++) {
								list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

								if(!empty($tmp_optvalue[$row->prdcode][$no])) $stock = $tmp_optvalue[$row->prdcode][$no];

								list($opt_v,$opt_p) = explode("^",$optcode1);
								list($opt_v2,$opt_p2) = explode("^",$optcode2);

								$opt1_arr[$ii] = str_replace(" ","",$opt1_arr[$ii]);
								$opt2_arr[$jj] = str_replace(" ","",$opt2_arr[$jj]);

								if(!strcmp($opt_v, $opt1_arr[$ii]) && !strcmp($opt_v2, $opt2_arr[$jj])) {
									if($site_info['viewType'] == "I"){

										$opt_val = explode("&&",$row->optcode);
										for($i=0; $i<count($opt_val)-1; $i++){
											$exp = $opt_val[$i];
											list($p_name,$p_price,$p_reserve,$p_amount) = explode("^",$exp);

											list($price_v, $reserve_v, $stock_v) = explode("^", $opt_tmp[$i]);
											$stock_v = $stock_v + $p_amount;

											$opt_list_app2 .= $price_v."^".$reserve_v."^".$stock_v."^^";
										}

									} else {
										$stock = $stock + $row->amount;
									}

								}

								$opt_list_app .= $price."^".$reserve."^".$stock."^^";

								$tmp_optvalue[$row->prdcode][$no] = $stock;
								$no++;
							}
						}

						if($site_info['viewType'] == "I"){
							$opt_list_value = $opt_list_app2;
						} else {
							$opt_list_value = $opt_list_app;
						}

						$optvalue_sql = ", optvalue = '$opt_list_value'";

					}

					$sql = "
						update wiz_product 
						   set cancelcnt = if(cancelcnt is null, 1, cancelcnt + 1 )
						       $optvalue_sql 
						 where prdcode = '$row->prdcode'
					";
					query($sql);

				}

			}

			/* 주문취소시 바스켓의 상태값도 모두 주문취소로 변경 */
			$order_baskidx_cnt = count(explode('|', $order_info->basketidx));
			$qry = "
				select count(idx) as idx_cnt 
				  from wiz_basket 
				 where orderid='".$orderid."' 
				   and (status='OC' or status='RC') 
			";
			$irow = sql_fetch_object($qry);
			$idx_cnt = $irow->idx_cnt;

			/* 주문된 상품갯수와 처리상태의 갯수가 일치할때... */
			if($order_baskidx_cnt == $idx_cnt) {

				$sql = "
					update wiz_order 
					   set status = '$status' 
					 where orderid = '$order_info->orderid' 
				";
				query($sql);

			}

			// 쿠폰
			$coupon_list = explode("|", $order_info->coupon_idx);
			if(is_array($coupon_list)) {
				foreach($coupon_list as $c_idx => $cidx) {
					$sql = "
						update wiz_mycoupon 
						   set coupon_use = 'N' 
						 where idx = '".$cidx."'
					";
					query($sql);
				}
			}

			if($site_info['alimtalk_use'] == 'Y' && !empty($site_info['alimtalk_senderkey'])) {
				/**
				 * 주문취소 알림톡 발송
				 */
				$templateCode = $site_info['alimtalk_id']."_order_cancel";
				$talk_info['prdname']    = $prdnames;
				$talk_info['prdoption']  = $options;
				$talk_info['prdamount']  = $amounts;
				$talk_info['name']       = $order_info->send_name;
				$talk_info['email']      = $order_info->send_email;
				$talk_info['hphone']     = $order_info->send_hphone;

				$return_code = send_alimtalk($templateCode,$talk_info);

			}


			$oper_time = ", cancel_date = now()";

			include $_SERVER['DOCUMENT_ROOT'].'/twcenter/product/order_mail.php';
			send_mailsms("order_cancel", $re_info, $ordmail);

		// 주문(환불)거부
		} else if(!strcmp($status, "OE")) {
		
			$sql = "
				update wiz_basket 
				   set status = '$status' 
				 where orderid = '$order_info->orderid' 
			";
			query($sql);

		// 배송처리시
		} else if(!strcmp($status, "DI")) {

			if($site_info['alimtalk_use'] == 'Y' && !empty($site_info['alimtalk_senderkey'])) {
				/**
				 * 배송처리경우 알림톡 발송
				 */
				$templateCode = $site_info['alimtalk_id']."_order_deliver";
				$talk_info['prdname']    = $prdnames;
				$talk_info['prdoption']  = $options;
				$talk_info['prdamount']  = $amounts;
				$talk_info['name']       = $order_info->send_name;
				$talk_info['email']      = $order_info->send_email;
				$talk_info['hphone']     = $order_info->send_hphone;

				$return_code = send_alimtalk($templateCode,$talk_info);

			}

			include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/order_mail_p.php";
			send_mailsms("order_deliver", $re_info, $ordmail);

			$oper_time = ", send_pro_date = now()";

		// 배송준비
		} else if(!strcmp($status, "DR")) {
			$oper_time = ", send_pre_date = now()";

		// 환불요청
		} else if(!strcmp($status, "RD")) {
			$oper_time = ", cancel_request_date = now()";

		// 교환요청
		} else if(!strcmp($status, "CD")) {
			$oper_time = ", ex_request_date = now()";

		// 교환완료일
		} else if(!strcmp($status, "CC")) {
			$oper_time = ", exchange_date = now()";
		}

		$sql = "
			update wiz_basket 
			   set status = '$status' 
			       $oper_time 
			 where orderid = '$orderid' 
			   and basketidx='$basket_idx' 
		";
		query($sql);


   }

   // 배송처리, 배송완료인 경우 배송정보 전송
   if(!strcmp($status, "DI") || !strcmp($status, "DC")) {
		// 배송정보 전송
		escrow_delivery($order_info, $oper_info, $ord_deliver_num, $ord_deliver_date);

	}

}

// 주문상태 변경
if($mode == "chgstatus"){

	changeStatus($orderid, $chg_status, $basketidx, $deliver_num, $deliver_date);
	complete("주문정보가 수정되었습니다.","order_list_p.php?page=$page&$param");

// 주문정보 수정
}else if($mode == "update"){

	$send_post = $send_post."-".$send_post2;
	$rece_post = $rece_post."-".$rece_post2;

	if(!empty($chg_status)) {
		changeStatus($orderid, $chg_status, $basketidx, $deliver_num, $deliver_date, $mresult);
		$chg_status_sql = " status = '$chg_status', ";
	}

	$sql = "

		update wiz_order set

			$chg_status_sql
			send_name    = '$send_name'
			,send_tphone  = '$send_tphone'
			,send_hphone  = '$send_hphone'
			,send_email   = '$send_email'
			,send_post    = '$send_post'
			,send_address = '$send_address'
			,rece_name    = '$rece_name'
			,rece_tphone  = '$rece_tphone'
			,rece_hphone  = '$rece_hphone'
			,rece_post    = '$rece_post'
			,rece_address = '$rece_address'
			,demand       = '$demand'
			,message      = '$message'
			,cancelmsg    = '$cancelmsg'
			,exchangemsg  = '$exchangemsg'
			,descript     = '$descript'
			,deliver_num  = '$deliver_num'
			,deliver_date = '$deliver_date'
			,tax_type     = '$tax_type'
			,id_info      = '$id_info'
			,bill_yn      = '$bill_yn'
			,authno       = '$authno'
			,del_com      = '$del_com'
			,bk_memo      = '$bk_memo'

		where

			orderid = '$orderid'

	";
	query($sql);

	$sql = "select orderid from wiz_tax where orderid = '$orderid'";
	$row = sql_fetch($sql);

	include_once "../../inc/site_info.php";

	if(isset($_POST['com_num']) && $_POST['com_num']) {
		$com_num = implode("-", array_filter($_POST['com_num']));
		$com_num = (strpos($com_num, '--') !== false) ? '' : $com_num;
	} else {
		$com_num = "";
	}

	if(isset($_POST['com_tel']) && $_POST['com_tel']) {
		$com_tel = implode("-", array_filter($_POST['com_tel']));
		$com_tel = (strpos($com_tel, '--') !== false) ? '' : $com_tel;
	} else {
		$com_tel = "";
	}

	if(isset($_POST['cash_info']) && $_POST['cash_info']) {
		$cash_info = implode("-", array_filter($_POST['cash_info']));
		$cash_info = (strpos($cash_info, '--') !== false) ? '' : $cash_info;
	} else {
		$cash_info = "";
	}

	if(isset($_POST['cash_info2']) && $_POST['cash_info2']) {
		$cash_info2 = implode("-", array_filter($_POST['cash_info2']));
		$cash_info2 = (strpos($cash_info2, '--') !== false) ? '' : $cash_info2;
	} else {
		$cash_info2 = "";
	}

	if(isset($_POST['cash_info3']) && $_POST['cash_info3']) {
		$cash_info3 = implode("-", array_filter($_POST['cash_info3']));
		$cash_info3 = (strpos($cash_info3, '--') !== false) ? '' : $cash_info3;
	} else {
		$cash_info3 = "";
	}

	if(isset($_POST['cash_info4']) && $_POST['cash_info4']) {
		$cash_info4 = implode("-", array_filter($_POST['cash_info4']));
		$cash_info4 = (strpos($cash_info4, '--') !== false) ? '' : $cash_info4;
	} else {
		$cash_info4 = "";
	}

	$shop_name 		= $site_info['com_name'];
	$shop_owner 	= $site_info['com_owner'];
	$shop_num		= $site_info['com_num'];
	$shop_address	= $site_info['com_address'];
	$shop_kind 		= $site_info['com_kind'];
	$shop_class		= $site_info['com_class'];
	$shop_tel		= $site_info['com_tel'];
	$shop_email		= $site_info['site_email'];

	if(!strcmp($tax_pub, "Y") && strcmp($tmp_tax_pub, "Y"))
		$tax_pub_sql = "
			,wdate        = now()
			,shop_name    = '$shop_name'
			,shop_owner   = '$shop_owner'
			,shop_num     = '$shop_num'
			,shop_address = '$shop_address'
			,shop_kind    = '$shop_kind'
			,shop_class   = '$shop_class'
			,shop_tel     = '$shop_tel'
			,shop_email   = '$shop_email'
		";

	if(!empty($row['orderid'])) {
		$sql = "

			update wiz_tax set 
			
				com_num        = '$com_num'
				,com_name      = '$com_name'
				,com_owner     = '$com_owner'
				,com_post      = '$com_post'
				,com_address1  = '$com_address'
				,com_address2  = '$com_address2'
				,com_kind      = '$com_kind'
				,com_class     = '$com_class'
				,com_tel       = '$com_tel'
				,com_email     = '$com_email'
				,tax_pub       = '$tax_pub'
				,tax_type      = '$tax_type'
				,cash_type     = '$cash_type'
				,cash_type2    = '$cash_type2'
				,cash_info     = '$cash_info'
				,cash_info2    = '$cash_info2'
				,cash_info3    = '$cash_info3'
				,cash_info4    = '$cash_info4'
				,cash_name     = '$cash_name'
				$tax_pub_sql
				
			where
			
				orderid = '$orderid'
				
		";
	} else {

		include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

		$shop_name 		= $site_info['com_name'];
		$shop_owner 	= $site_info['com_owner'];
		$shop_num		= $site_info['com_num'];
		$shop_address	= $site_info['com_address'];
		$shop_kind 		= $site_info['com_kind'];
		$shop_class		= $site_info['com_class'];
		$shop_tel		= $site_info['com_tel'];
		$shop_email		= $site_info['shop_email'];

		$supp_price = intval($total_price/1.1);
		$tax_price = $total_price - $supp_price;

		$sql = "
		
			INSERT INTO wiz_tax(
			
				orderid,com_num,com_name,com_owner,com_post,com_address1,com_address2,com_kind,
				com_class,com_tel,com_email,shop_num,shop_name,shop_owner,
				shop_address,shop_kind,shop_class,shop_tel,shop_email,
				prd_info,supp_price,tax_price,tax_pub,tax_date,
				tax_type,cash_type,cash_type2,cash_info,cash_info2,cash_info3,cash_info4,cash_name
				
			) VALUES (
			
				'".$orderid."','".$com_num."','".$com_name."','".$com_owner."','".$com_post."','".$com_address."','".$com_address2."','".$com_kind."',
				'".$com_class."','".$com_tel."','".$com_email."','".$shop_num."','".$shop_name."','".$shop_owner."',
				'".$shop_address."','".$shop_kind."','".$shop_class."','".$shop_tel."','".$shop_email."',
				'".$prd_info."','".$supp_price."','".$tax_price."','".$tax_pub."',now(),
				'".$tax_type."','".$cash_type."','".$cash_type2."','".$cash_info."','".$cash_info2."','".$cash_info3."','".$cash_info4."','".$cash_name."'
				
			)";

	}

	query($sql);

	complete("주문정보가 수정되었습니다.","order_info.php?orderid=$orderid&page=$page&$param");


// 주문삭제
}else if($mode == "delete"){

	$i=0;
	$array_selorder = explode("|",$selorder);
	while($array_selorder[$i]){
		$orderid = $array_selorder[$i];
		$sql = "delete from wiz_order where orderid = '$orderid'";
		query($sql);

		$sql = "delete from wiz_basket where orderid = '$orderid'";
		query($sql);

		$sql = "delete from wiz_tax where orderid = '$orderid'";
		query($sql);

		$i++;
	}

	complete("주문을 삭제하였습니다.","order_list_p.php?page=$page&$param");


// 주문상태 일괄변경
}else if($mode == "batchStatus"){

	$i=0;
	$array_selorder = explode("|",$selorder);
	echo $array_selorder;
	while($array_selorder[$i]){
		list($orderid, $old_status) = explode(":",$array_selorder[$i]);

		if(strcmp($old_status, "OC") && strcmp($old_status, "RC")) {
	//		changeStatus($orderid, $chg_status, $deliveryno[$i], $deliver_date[$i]);
		}

		$i++;
	}

	//echo "<script>alert('주문상태를 변경하였습니다.');opener.document.location.reload();self.close();</script>";

// 상품 취소
}else if($mode == "cancel"){

	$sql = "

		select

			wb.*                          ,
			wo.reserve_use                ,
			wo.reserve_price              ,
			wo.deliver_price              ,
			wo.prd_price                  ,
			wm.level                      ,
			wp.optcode as p_optcode       ,
			wp.optcode2 as p_optcode2     ,
			wp.optvalue as p_optvalue

		from

			wiz_basket as wb LEFT JOIN wiz_order as wo ON wb.orderid = wo.orderid
			LEFT JOIN wiz_member AS wm ON wo.send_id = wm.id
			LEFT JOIN wiz_product AS wp ON wb.prdcode = wp.prdcode

		where

			wb.idx = '$idx'
			
	";
	$row = sql_fetch($sql);

	//주문접수상태
	if(!strcmp($orderstatus, "OR")) {

		$reserve_price = $row['reserve_price'] - ($row['prdreserve'] * $row['amount']);
		$prd_price 	   = $row['prd_price'] - ($row['prdprice'] * $row['amount']);

		$discount_price = level_discount($row['level'],$prd_price);				// 회원할인 [$discount_msg 메세지 생성]
		$deliver_price  = deliver_price($prd_price, $oper_info);				// 배송비
		$total_price    = $prd_price + $deliver_price - $discount_price;		// 전체결제금액

		// 주문정보에서 해당금액, 적립금, 배송비, 회원할인비 가감
		$sql = "
		
			update wiz_order set 
			
				reserve_price   = '$reserve_price'
				,deliver_price  = '$deliver_price'
				,discount_price = '$discount_price'
				,prd_price      = '$prd_price'
				,total_price    = '$total_price'
			
			where
			
				orderid = '$row['orderid']'
				
		";
		query($sql);

		// basket 업데이트
		$sql = "
		
			update wiz_basket set 
			
				status    = 'CC'
				,twcenter    = '$wiz_admin['id']'
				,bank     = '$bank'
				,account  = '$account'
				,acc_name = '$acc_name'
				,reason   = '$reason'
				,memo     = '$memo'
				,repay    = '$repay'
				,ca_date  = now()
				,cc_date  = now()
				
			where
			
				idx = '$idx'
		
		";
		query($sql);

		// 주문상품 부분취소로 모두처리됐을시 주문취소로 업데이트
		$sql = "select orderid from wiz_basket where orderid = '$row['orderid']' and status != 'CC' ";
		$basket_cnt = sql_fetch_rows($sql);

		if($basket_cnt == 0){

			$sql = "
			
				update wiz_order set 
				
					status          = 'OC'
					,cancel_date    = now()
					,deliver_price  = 0
					,total_price    = 0
					
				where
				
					orderid = '$row['orderid']'
			
			";
			query($sql);

		}

		complete("상품이 취소되었습니다.","order_info.php?orderid=$row['orderid']&page=$page&$param");

	} else {

		// basket 업데이트
		$sql = "
		
			update wiz_basket set 
			
				status    = 'CA'
				,twcenter    = '$wiz_admin['id']'
				,bank     = '$bank'
				,account  = '$account'
				,acc_name = '$acc_name'
				,reason   = '$reason'
				,memo     = '$memo'
				,repay    = '$repay'
				,ca_date  = now()
				
			where
			
				idx = '$idx'
				
		";
		query($sql);

		complete("상품이 취소요청이 되었습니다. 주문취소목록에서 확인하실 수 있습니다.","order_info.php?orderid=$orderid&page=$page&$param");

	}

// 개별취소 목록(부분취소)
} else if(!strcmp($mode, "cancel_status")){

	$sql = "

		select 
		
			wb.*                         ,
			wo.reserve_use               ,
			wo.reserve_price             ,
			wo.deliver_price             ,
			wo.prd_price                 ,
			wo.send_id                   ,
			wo.status as o_status        ,
			wm.level                     ,
			wp.optcode as p_optcode      ,
			wp.optcode2 as p_optcode2    ,
			wp.optvalue as p_optvalue    ,
			wp.opt_use                   ,
			wp.shortage
			
		from
		
			wiz_basket as wb LEFT JOIN wiz_order as wo ON wb.orderid = wo.orderid
			LEFT JOIN wiz_member AS wm ON wo.send_id = wm.id
			LEFT JOIN wiz_product AS wp ON wb.prdcode = wp.prdcode
			
		where
		
			wb.idx = '$idx'
			
	";
	$row = sql_fetch($sql);

	if(!strcmp($chg_status, "CC")) {

		if(!strcmp($row['status'], "CC")) {
			error("이미 취소처리된 상품입니다.");
		} else {

			$reserve_price   = $row['reserve_price'] - ($row['prdreserve'] * $row['amount']);
			$prd_price 		 = $row['prd_price'] - ($row['prdprice'] * $row['amount']);

			$discount_price  = level_discount($row['level'],$prd_price);			// 회원할인 [$discount_msg 메세지 생성]
			$deliver_price   = deliver_price($prd_price, $oper_info);			// 배송비
			$total_price     = $prd_price + $deliver_price - $discount_price;	// 전체결제금액

			// 주문 정보에서 해당 금액, 적립금, 배송비, 회원할인비 가감
			$sql = "
			
				update wiz_order set 
				
					reserve_price   = '$reserve_price'
					,deliver_price  = '$deliver_price'
					,discount_price = '$discount_price'
					,prd_price      = '$prd_price'
					,total_price    = '$total_price'
					
				where
				
					orderid = '$row['orderid']'
					
			";
			query($sql);

			// 상품 재고
			// 주문접수일 경우를 제외하고 재고증가
			if(strcmp($row['o_status'], "OR")) {
				// 옵션별 재고관리 없는 제품이라면 전체 재고 증가
				if(strcmp($row['opt_use'], "Y")){

					if(!strcmp($row['shortage'], "S")) {
						$sql = "update wiz_product set cancelcnt = if(cancelcnt is null, 1, cancelcnt + 1), stock = stock + $row['amount'] where prdcode = '$row['prdcode']'";
						query($sql);
					} else {
						$sql = "update wiz_product set cancelcnt = if(cancelcnt is null, 1, cancelcnt + 1) where prdcode = '$row['prdcode']'";
						query($sql);
					}

				// 옵션별 재고관리 상품
				}else{

					$opt_list_app = "";

					$opt1_arr = explode("^", $row['p_optcode']);
					$opt2_arr = explode("^", $row['p_optcode2']);
					$opt_tmp  = explode("^^", $row['p_optvalue']);

					list($optcode1, $optcode2) = explode("/", $row['optcode']);

					$opt1_cnt = count($opt1_arr) - 1;
					$opt2_cnt = count($opt2_arr) - 1;

					if($opt1_cnt < 1) $opt1_cnt = 1;
					if($opt2_cnt < 1) $opt2_cnt = 1;

					$no = 0;
					for($ii = 0; $ii < $opt1_cnt; $ii++) {
						for($jj = 0; $jj < $opt2_cnt; $jj++) {
							list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

							if(!empty($tmp_optvalue[$row['prdcode']][$no])) $stock = $tmp_optvalue[$row['prdcode']][$no];

							list($opt_v,$opt_p) = explode("^",$optcode1);
							list($opt_v2,$opt_p2) = explode("^",$optcode2);

							$opt1_arr[$ii] = str_replace(" ","",$opt1_arr[$ii]);
							$opt2_arr[$jj] = str_replace(" ","",$opt2_arr[$jj]);

							if(!strcmp($opt_v, $opt1_arr[$ii]) && !strcmp($opt_v2, $opt2_arr[$jj])) {

								if($site_info['viewType'] == "I"){
									$opt_val = explode("&&",$row->optcode);
									for($i=0; $i<count($opt_val)-1; $i++){
										$exp = $opt_val[$i];
										list($p_name,$p_price,$p_reserve,$p_amount) = explode("^",$exp);

										list($price_v, $reserve_v, $stock_v) = explode("^", $opt_tmp[$i]);
										$stock_v = $stock_v + $p_amount;

										$opt_list_app2 .= $price_v."^".$reserve_v."^".$stock_v."^^";
									}
								
								} else {
									$stock = $stock + $row->amount;
								}

							}

							$opt_list_app .= $price."^".$reserve."^".$stock."^^";

							$tmp_optvalue[$row['prdcode']][$no] = $stock;
							$no++;
						}
					}

					if($site_info['viewType'] == "I"){
						$opt_list_value = $opt_list_app2;
					} else {
						$opt_list_value = $opt_list_app;
					}

					$sql = "update wiz_product set cancelcnt = if(cancelcnt is null, 1, cancelcnt + 1), optvalue = '$opt_list_value' where prdcode = '$row['prdcode']'";
					query($sql);

				}
			}
		}

		// 적립금으로 환불 시 적립금 적립
		if(!strcmp($row['repay'], "R")) {
			$sql = "
		
				insert into wiz_reserve(
				
					idx                                     ,
					memid                                   ,
					reservemsg                              ,
					reserve                                 ,
					orderid                                 ,
					wdate
					
				) values ( 
				
					''                                      ,
					'$row['send_id']'                         ,
					'상품환불 적립금'                       ,
					'".($row['prdprice'] * $row['amount'])."'   ,
					'$row['orderid']'                         ,
					now()
					
				)";
			query($sql);
		}

		$cc_date_sql = ", cc_date = now() ";

	} else if(!strcmp($chg_status, "CI")) {

		$ci_date_sql = ", ci_date = now() ";

	}

	$sql = "update wiz_basket set status = '$chg_status' $cc_date_sql $ci_date_sql where idx = '$idx'";
	query($sql);

	// 주문상품 부분취소로 모두처리됐을시 주문취소로 업데이트
	$sql = "select orderid from wiz_basket where orderid = '$row['orderid']' and status != 'CC' ";
	$basket_cnt = sql_fetch_rows($sql);

	if($basket_cnt == 0){

		$sql = "
		
			update wiz_order set 
			
				status          = 'OC'
				,cancel_date    = now()
				,deliver_price  = 0
				,total_price    = 0
				
			where
			
				orderid = '$row['orderid']'
		
		";
		query($sql);

	}


	// 세금계산서 금액 수정
	$supp_price = intval($total_price/1.1);
	$tax_price = $total_price - $supp_price;

	$prd_info = "";

	$b_sql = "select prdname, prdprice, amount from wiz_basket where orderid = '$row['orderid']' and status != 'CC' order by idx asc";
	$b_result = query($b_sql);
	while($b_row = sql_fetch_arr($b_result)) {
		$prd_info .= $b_row['prdname']."^".$b_row['prdprice']."^".$b_row['amount']."^^";
	}

	$sql = "
		update wiz_tax 
		   set supp_price='$supp_price'
		     , tax_price='$tax_price'
			 , prd_info='$prd_info' 
		 where orderid = '$row['orderid']'
	";
	query($sql);


 	complete("적용되었습니다.","cancel_list.php?page=$page&$param");

// 개별취소 삭제
} else if(!strcmp($mode, "delete_basket")) {

	$idx_list = explode("|", $selbasket);
	for($ii = 0; $ii < count($idx_list); $ii++) {
		$idx = $idx_list[$ii];

		$sql = "delete from wiz_basket where idx = '$idx'";
		query($sql);
	}

  complete("삭제되었습니다.","cancel_list.php?page=$page&$param");

// 취소상태 일괄변경
}else if($mode == "batchStatusBasket"){

	$i=0;
	$array_selbasket = explode("|",$selbasket);
	while($array_selbasket[$i]){
		$idx = $array_selbasket[$i];

		$sql = "

			select 
			
				wb.*                         ,
				wo.reserve_use               ,
				wo.reserve_price             ,
				wo.deliver_price             ,
				wo.prd_price                 ,
				wo.send_id                   ,
				wm.level                     ,
				wp.optcode as p_optcode      ,
				wp.optcode2 as p_optcode2    ,
				wp.optvalue as p_optvalue
				
			from
			
				wiz_basket as wb LEFT JOIN wiz_order as wo ON wb.orderid = wo.orderid
				LEFT JOIN wiz_member AS wm ON wo.send_id = wm.id
				LEFT JOIN wiz_product AS wp ON wb.prdcode = wp.prdcode
				
			where
			
				wb.idx = '$idx'
				
		";
		$row = sql_fetch($sql);

		if(!strcmp($row['status'], "CC")) {
		} else {
			if(!strcmp($chg_status, "CC")) {
				$reserve_price   = $row['reserve_price'] - ($row['prdreserve'] * $row['amount']);
				$prd_price 		 = $row['prd_price'] - ($row['prdprice'] * $row['amount']);

				$discount_price  = level_discount($row['level'],$prd_price);			// 회원할인 [$discount_msg 메세지 생성]
				$deliver_price   = deliver_price($prd_price, $oper_info);			// 배송비
				$total_price     = $prd_price + $deliver_price - $discount_price;	// 전체결제금액

				// 주문 정보에서 해당 금액, 적립금, 배송비, 회원할인비 가감
				$sql = "
				
					update wiz_order set 
					
						reserve_price   = '$reserve_price'
						,deliver_price  = '$deliver_price'
						,discount_price = '$discount_price'
						,prd_price      = '$prd_price'
						,total_price    = '$total_price'
						
					where
					
						orderid = '$row['orderid']'
						
				";
				query($sql);

				// 상품 재고
				// 옵션별 재고관리 없는 제품이라면 전체재고 증가
				if($row['optcode'] == ""){

					$sql = "update wiz_product set cancelcnt = if(cancelcnt is null, 1, cancelcnt + 1) , comcnt = comcnt - 1, stock = stock + $row['amount'] where prdcode = '$row['prdcode']'";
					query($sql);

				// 옵션별 재고관리 상품
				}else{

					$opt_list_app = "";

					$opt1_arr = explode("^", $row['p_optcode']);
					$opt2_arr = explode("^", $row['p_optcode2']);
					$opt_tmp = explode("^^", $row['p_optvalue']);

					list($optcode1, $optcode2) = explode("/", $row['optcode']);

					$opt1_cnt = count($opt1_arr) - 1;
					$opt2_cnt = count($opt2_arr) - 1;

					if($opt1_cnt < 1) $opt1_cnt = 1;
					if($opt2_cnt < 1) $opt2_cnt = 1;

					$no = 0;
					for($ii = 0; $ii < $opt1_cnt; $ii++) {
						for($jj = 0; $jj < $opt2_cnt; $jj++) {
							list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

							if(!empty($tmp_optvalue[$row['prdcode']][$no])) $stock = $tmp_optvalue[$row['prdcode']][$no];

							list($opt_v,$opt_p) = explode("^",$optcode1);
							list($opt_v2,$opt_p2) = explode("^",$optcode2);

							$opt1_arr[$ii] = str_replace(" ","",$opt1_arr[$ii]);
							$opt2_arr[$jj] = str_replace(" ","",$opt2_arr[$jj]);

							if(!strcmp($opt_v, $opt1_arr[$ii]) && !strcmp($opt_v2, $opt2_arr[$jj])) {

								if($site_info['viewType'] == "I"){
									$opt_val = explode("&&",$row->optcode);
									for($i=0; $i<count($opt_val)-1; $i++){
										$exp = $opt_val[$i];
										list($p_name,$p_price,$p_reserve,$p_amount) = explode("^",$exp);

										list($price_v, $reserve_v, $stock_v) = explode("^", $opt_tmp[$i]);
										$stock_v = $stock_v + $p_amount;

										$opt_list_app2 .= $price_v."^".$reserve_v."^".$stock_v."^^";
									}
								
								} else {
									$stock = $stock + $row->amount;
								}

							}

							$opt_list_app .= $price."^".$reserve."^".$stock."^^";

							$tmp_optvalue[$row['prdcode']][$no] = $stock;
							$no++;
						}
					}

					if($site_info['viewType'] == "I"){
						$opt_list_value = $opt_list_app2;
					} else {
						$opt_list_value = $opt_list_app;
					}

					$sql = "update wiz_product set cancelcnt = if(cancelcnt is null, 1, cancelcnt + 1) , comcnt = comcnt - 1, optvalue = '$opt_list_value'
							where prdcode = '$row['prdcode']'";
					query($sql);

				}

				$cc_date_sql = ", cc_date = now() ";
			}

			// 적립금으로 환불 시 적립금 적립
			if(!strcmp($row['repay'], "R")) {
				$sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,wdate)
						values('', '$row['send_id']', '상품환불 적립금','".($row['prdprice'] * $row['amount'])."','$row['orderid']',now())";
				query($sql);
			}
			$sql = "update wiz_basket set status = '$chg_status' $cc_date_sql where idx = '$idx'";
			query($sql);

			// 세금계산서 금액 수정
			$supp_price = intval($total_price/1.1);
			$tax_price = $total_price - $supp_price;

			$prd_info = "";

			$b_sql = "select prdname, prdprice, amount from wiz_basket where orderid = '$row['orderid']' and status != 'CC' order by idx asc";
			$b_result = query($b_sql);
			while($b_row = sql_fetch_arr($b_result)) {
				$prd_info .= $b_row['prdname']."^".$b_row['prdprice']."^".$b_row['amount']."^^";
			}

			$sql = "
				update wiz_tax 
				   set supp_price='$supp_price'
				     , tax_price='$tax_price'
					 , prd_info='$prd_info' 
				 where orderid = '$row['orderid']'
			";
			query($sql);

		}

		$i++;
	}

	echo "<script>alert('상태를 변경하였습니다.\\n\\n취소완료된 건은 상태가 변경되지 않습니다.');opener.document.location.reload();self.close();</script>";

// 세금계산서 목록 > 승인
} else if(!strcmp($mode, "tax_status")) {

	include_once "../../inc/site_info.php";

	$shop_name 		= $site_info['com_name'];
	$shop_owner 	= $site_info['com_owner'];
	$shop_num		= $site_info['com_num'];
	$shop_address	= $site_info['com_address'];
	$shop_kind 		= $site_info['com_kind'];
	$shop_class		= $site_info['com_class'];
	$shop_tel		= $site_info['com_tel'];
	$shop_email		= $site_info['site_email'];
	
	//세금계산서
	if($tmp_tax_pub=="T"){

	}

  if(!strcmp($tax_pub, "Y") && strcmp($tmp_tax_pub, "Y")) $tax_pub_sql = ", wdate = now(), shop_name='$shop_name', shop_owner='$shop_owner', shop_num='$shop_num', shop_address='$shop_address', shop_kind='$shop_kind', shop_class='$shop_class', shop_tel='$shop_tel', shop_email='$shop_email' ";

	$sql = "
		update wiz_tax 
		   set tax_pub = '$tax_pub' 
		   $tax_pub_sql 
		 where orderid = '$orderid'
	";
	query($sql);

 	complete("적용되었습니다.","tax_list.php?page=$page&$param");

// 세금계산서 삭제
} else if(!strcmp($mode, "tax_delete")) {

	$orderid_list = explode("|", $selvalue);
	for($ii = 0; $ii < count($orderid_list); $ii++) {
		$orderid = $orderid_list[$ii];
		$sql = "delete from wiz_tax where orderid = '$orderid'";
		query($sql);

		$sql = "update wiz_order set tax_type = 'N' where orderid = '$orderid'";
		query($sql);
	}

	complete("삭제되었습니다.","tax_list.php?page=$page&$param");

// 세금계산서 목록 > 상태일괄변경
} else if(!strcmp($mode, "batchStatusTax")) {

	include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

	$shop_name 		= $site_info['com_name'];
	$shop_owner 	= $site_info['com_owner'];
	$shop_num			= $site_info['com_num'];
	$shop_address	= $site_info['com_address'];
	$shop_kind 		= $site_info['com_kind'];
	$shop_class		= $site_info['com_class'];
	$shop_tel			= $site_info['com_tel'];
	$shop_email		= $site_info['shop_email'];

  if(!strcmp($tax_pub, "Y") && strcmp($tmp_tax_pub, "Y")) 
	  $tax_pub_sql = ", wdate = now(), shop_name='$shop_name', shop_owner='$shop_owner', shop_num='$shop_num', shop_address='$shop_address', shop_kind='$shop_kind', shop_class='$shop_class', shop_tel='$shop_tel', shop_email='$shop_email' ";

	$orderid_list = explode("|", $selvalue);
	for($ii = 0; $ii < count($orderid_list); $ii++) {

		$orderid = $orderid_list[$ii];

		$sql = "
			update wiz_tax 
			   set tax_pub = '$tax_pub' 
			   $tax_pub_sql 
			 where orderid = '$orderid'
		";
		query($sql);

	}

  echo "<script>alert('변경되었습니다.');opener.document.location.reload();self.close();</script>";

// 주문정보내 상품별 배송업체 선택
} else if(!strcmp($mode, "deliverySel")) {

	$query = "
		update wiz_basket 
		   set del_com='{$del_com}'
		     , del_num='{$del_num}'
			 , deliver_date='{$deliver_date}' 
		 where idx={$basIdx} 
		   and orderid='{$orderid}' 
	";
	query($query);

	echo "ok";
	exit;

} else if(!strcmp($mode, "basApply")) {

	$basketidx      = $_POST['basketidx'];
	$orderid        = $_POST['orderid'];
	$bas_chg_status = $_POST['bas_chg_status'];

	if($bas_chg_status == 'DI' || $bas_chg_status == 'DC') {
		$sql = "select del_num, del_com from wiz_basket where orderid = '".$orderid."' and basketidx = '".$basketidx."' ";
		$del_row = sql_fetch($sql);
		$del_num = $del_row['del_num'];
		$del_com = $del_row['del_com'];

		if($del_num == '') {
			echo "no_delnum";
			exit;
		}

		if($del_com == '') {
			echo "no_delcom";
			exit;
		}

	}

	changeStatus($orderid, $bas_chg_status, $basketidx);

	$sql = "select basketidx, status from wiz_order where orderid = '".$orderid."' ";
	$orow = sql_fetch($sql);
	$obasket_idx = $orow['basketidx'];
	$order_status = $orow['status'];

	$n_array = array();
	$qry = "select status from wiz_basket where orderid='".$orderid."' ";
	$res = query($qry);
	while($n_row = sql_fetch_arr($res)) {
		$n_array[] = $n_row['status'];
	}

	$order_baskidx_cnt = count(explode('|', $obasket_idx));
	$qry = "select count(idx) as idx_cnt from wiz_basket where orderid='".$orderid."' and status='".$bas_chg_status."' ";
	$ires = query($qry);
	$irow = sql_fetch_obj($ires);
	$idx_cnt = $irow->idx_cnt;

	if($order_baskidx_cnt == $idx_cnt) {
		$sql = "update wiz_order set status = '".$bas_chg_status."' where orderid = '".$orderid."' ";
		query($sql);
	}

	if($order_baskidx_cnt > 1) {

		$n_array = array();
		$qry = "select status from wiz_basket where orderid='".$orderid."' ";
		$res = query($qry);
		while($n_row = sql_fetch_arr($res)) {
			$n_array[] = $n_row['status'];
		}

		$chg_status_array = array();
		$chg_status_array[] = $bas_chg_status;

		$_array1 = array('OR','OY','DR','DI','DC','RD','RC','CD','CC');
		$_array2 = array_merge($n_array, $chg_status_array);
		$result_array = array_intersect($_array1, $_array2);
		$stauts_array_last = end($result_array);

		if($order_baskidx_cnt != $idx_cnt) {
			switch($stauts_array_last) {
				case 'OY' : $part_status = 'PY'; break;
				case 'OC' : $part_status = 'PZ'; break;
				case 'DR' : $part_status = 'PR'; break;
				case 'DI' : $part_status = 'PP'; break;
				case 'DC' : $part_status = 'PD'; break;
				case 'OC' : $part_status = 'PC'; break;
				case 'RD' : $part_status = 'PO'; break;
				case 'CD' : $part_status = 'PE'; break;
				case 'CC' : $part_status = 'PQ'; break;
	//			case 'OR' : $part_status = $stauts_array_last; break;
	//			case 'OY' : $part_status = $stauts_array_last; break;
				default   : $part_status = $order_status; break;
			}
			$sql = "update wiz_order set status = '".$part_status."' where orderid = '".$orderid."' ";
			query($sql);

		}

		/* -- ---------------------------------------------------- -- *\
		 * wiz_order의 status가 배송완료로 변경시 적립금 적립 
		\* -- ---------------------------------------------------- -- */
		$sql = "select * from wiz_order where orderid = '".$orderid."'";
		$order_info = sql_fetch_object($sql);

		if($order_info->status == "DC") {

			//적립금적용
			if($order_info->reserve_price > 0) {

				$sql = "
					select count(idx) as total 
					  from wiz_reserve 
					 where memid = '$order_info->send_id' 
					   and orderid = '$orderid' 
					   and reserve > 0 
					   and basket_idx = '$basket_idx'
				";
				$res = query($sql);
				$row = sql_fetch_arr($res);
				$total = $row['total'];

				// 이미 적립금이 적용됬는지 체크
				if($total <= 0){
					$reserve_msg = "상품구입시 적립됨";
					$sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,basket_idx,wdate) values('', '$order_info->send_id', '$reserve_msg', $order_info->reserve_price, '$orderid', '$basket_idx', now())";
					query($sql);
				}

			}

		}

	}

	echo "ok";
	exit;

}
?>