<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

	$sql = "
		select wb.* 
			 , wo.reserve_use 
			 , wo.reserve_price 
			 , wo.deliver_price 
			 , wo.prd_price 
			 , wm.level 
			 , wp.optcode as p_optcode 
			 , wp.optcode2 as p_optcode2 
			 , wp.optvalue as p_optvalue
		  from wiz_basket as wb 
		  left join wiz_order as wo 
		    on wb.orderid = wo.orderid
		  left join wiz_member AS wm 
		    on wo.send_id = wm.id
		  left join wiz_product AS wp 
		    on wb.prdcode = wp.prdcode
		 where wb.idx = '$idx'
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
			update wiz_order 
			   set reserve_price  = '".$reserve_price."'
				 , deliver_price  = '".$deliver_price."'
				 , discount_price = '".$discount_price."'
				 , prd_price      = '".$prd_price."'
				 , total_price    = '".$total_price."'
			 where orderid = '".$row['orderid']."'
		";
		query($sql);

		// basket 업데이트
		$sql = "
			update wiz_basket 
			   set status   = 'CC'
				 , bank     = '".$bank."'
				 , account  = '".$account."'
				 , acc_name = '".$acc_name."'
				 , reason   = '".$reason."'
				 , memo     = '".$memo."'
				 , repay    = '".$repay."'
				 , ca_date  = now()
				 , cc_date  = now()
			 where idx = '".$idx."'
		";
		query($sql);

		// 주문상품 부분취소로 모두처리됐을시 주문취소로 업데이트
		$sql = "
			select orderid 
			  from wiz_basket 
			 where orderid = '".$row['orderid']."' 
			   and status != 'CC' 
		";
		$basket_cnt = sql_fetch_rows($sql);

		if($basket_cnt == 0){

			$sql = "
				update wiz_order 
				   set status         = 'OC'
					 , cancel_date    = now()
					 , deliver_price  = 0
					 , total_price    = 0
				 where orderid = '".$row['orderid']."'
			
			";
			query($sql);

		}

		if($mobile != "M"){
			complete("상품이 취소되었습니다.","/member/order_list.php?ptype=view&orderid=$orderid&page=$page&$param");
		}else{
			echo "<script>alert('상품이 취소되었습니다.'); opener.location.reload(); self.close(); </script>";
		}
	} else {

		// basket 업데이트
		$sql = "
		
			update wiz_basket 
			   set status   = 'CA'
				 , bank     = '".$bank."'
				 , account  = '".$account."'
				 , acc_name = '".$acc_name."'
				 , reason   = '".$reason."'
				 , memo     = '".$memo."'
				 , repay    = '".$repay."'
				 , ca_date  = now()
			 where idx = '".$idx."'
				
			";
			query($sql);

			if($mobile != "M"){
				complete("상품이 취소요청이 되었습니다. 주문목록에서 확인하실 수 있습니다.","/member/order_list.php?ptype=view&orderid=$orderid&page=$page&$param");
			}else{
				echo "<script>alert('상품이 취소요청되었습니다. 주문목록에서 확인 가능합니다.'); opener.location.reload(); self.close(); </script>";
			}
		}

?>