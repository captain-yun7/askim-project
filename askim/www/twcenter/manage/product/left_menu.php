<?
	if($perm_check["PRODUCT"] == false) error("권한이 없습니다.");
?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td class="left_tit">쇼핑몰관리</td></tr>
				<tr>
					<td>
						<span class="toggleA">
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table">
							<tr>
								<th><a href="./shop_oper.php?menucode=PRODUCT" >운영정보설정</a></th>
							</tr>

							<tr>
								<th><a href="./prd_list.php?menucode=PRODUCT">상품관리</a></td>
							</tr>
							<tr><td class="pad"></td></tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'prd_list') !== false) echo "class='clickover'"?>><a href="./prd_list.php?menucode=PRODUCT" class="menu">상품조회</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'prd_input') !== false) echo "class='clickover'"?>><a href="./prd_input.php?menucode=PRODUCT" class="menu">상품등록</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'prd_category') !== false) echo "class='clickover'"?>><a href="./prd_category.php?menucode=PRODUCT" class="menu">상품분류</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'prd_brand') !== false) echo "class='clickover'"?>><a href="./prd_brand.php?menucode=PRODUCT" class="menu">브랜드관리</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'prd_option') !== false) echo "class='clickover'"?>><a href="./prd_option.php?menucode=PRODUCT" class="menu">옵션항목</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'prd_shortage') !== false) echo "class='clickover'"?>><a href="./prd_shortage.php?menucode=PRODUCT" class="menu">재고관리</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'prd_estimate') !== false) echo "class='clickover'"?>><a href="./prd_estimate.php?menucode=PRODUCT" class="menu">제품후기</a></td>
							</tr>
							<tr><td class="line"></td></tr>
							<?php
							if($oper_info['deliveryType'] == "P") {
							?>
							<tr>
								<th><a href="./order_list_p.php?menucode=PRODUCT">주문관리</a></td>
							</tr>
							<?php } else { ?>
							<tr>
								<th><a href="./order_list.php?menucode=PRODUCT">주문관리</a></td>
							</tr>
							<?php } ?>
							<tr><td class="pad"></td></tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'order_list') !== false && $s_status != 'RD' && $s_status != 'CD') echo "class='clickover'"?>><a href="./order_list.php?menucode=PRODUCT" class="menu">주문목록</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(!strcmp($s_status ?? ' ' , 'RD')) echo "class='clickover'"; ?>><a href="./order_list.php?s_status=RD&menucode=PRODUCT" class="menu">주문취소요청 목록</a></td>
							</tr>
							<!-- <tr class="leftMenu"> 
								<td <? if(!strcmp($s_status, 'CD')) echo "class='clickover'"?>><a href="./order_list.php?s_status=CD&menucode=PRODUCT" class="menu">교환요청 목록</a></td>
							</tr> -->
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'cancel_list') !== false) echo "class='clickover'"?>><a href="./cancel_list.php?menucode=PRODUCT" class="menu">개별 주문취소목록</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(!strcmp($tax_type ?? ' ' , 'T')) echo "class='clickover'"; ?>><a href="./tax_list.php?tax_type=T&menucode=PRODUCT" class="menu">세금계산서</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(!strcmp($tax_type ?? ' ' , 'C')) echo "class='clickover'"; ?>><a href="./tax_list.php?tax_type=C&menucode=PRODUCT" class="menu">현금영수증</a></td>
							</tr>
							<tr><td class="line"></td></tr>
							<?php
							if($wiz_admin['designer'] == "Y") {
							?>
							<tr>
								<th><a href="./bankda_account_list.php?menucode=PRODUCT">자동입금확인 서비스</a></td>
							</tr>
							<tr><td class="pad"></td></tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'bankda_account_list') !== false) echo "class='clickover'"?>><a href="./bankda_account_list.php?menucode=PRODUCT" class="menu">계좌현황</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'bankda_match') !== false) echo "class='clickover'"?>><a href="./bankda_match.php?menucode=PRODUCT" class="menu">입금조회/실시간입금확인</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'bankda_transaction_list') !== false) echo "class='clickover'"?>><a href="./bankda_transaction_list.php?menucode=PRODUCT" class="menu">통장입금내역 실시간조회</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'bankda_manual_match') !== false) echo "class='clickover'"?>><a href="./bankda_manual_match.php?menucode=PRODUCT" class="menu">입금내역 주문서 수동매칭</a></td>
							</tr>
							<tr><td class="line"></td></tr>
							<?php } ?>
							<tr>
								<th><a href="./analy_paymethod.php?menucode=PRODUCT">통계분석</a></th>
							</tr>
							<tr><td class="pad"></td></tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, '_total_month_analy') !== false) echo "class='clickover'"?>><a href="./_total_month_analy.php?menucode=PRODUCT" class="menu">월별 매출통계분석</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, '_total_day_analy') !== false) echo "class='clickover'"?>><a href="./_total_day_analy.php?menucode=PRODUCT" class="menu">일별 매출통계분석</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, '_total_time_analy') !== false) echo "class='clickover'"?>><a href="./_total_time_analy.php?menucode=PRODUCT" class="menu">시간별 매출통계분석</a></td>
							</tr>
							<!--<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'analy_paymethod') !== false) echo "class='clickover'"?>><a href="./analy_paymethod.php?menucode=PRODUCT" class="menu">매출통계분석</a></td>
							</tr> -->
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'analy_prd') !== false) echo "class='clickover'"?>><a href="./analy_prd.php?menucode=PRODUCT" class="menu">상품통계분석</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, '_total_cart') !== false) echo "class='clickover'"?>><a href="./_total_cart.php?menucode=PRODUCT" class="menu">장바구니통계</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, '_total_wishlist') !== false) echo "class='clickover'"?>><a href="./_total_wishlist.php?menucode=PRODUCT" class="menu">위시리스트통계</a></td>
							</tr>
							<tr><td class="line"></td></tr>
							<tr class="leftMenu_th"> 
								<th <? if(strpos($PHP_SELF, 'shop_coupon') !== false) echo "class='clickover'"?>><a href="./shop_coupon.php?menucode=PRODUCT" class="menu">쿠폰관리</a></th>
							</tr>
						</table>
						</span>
					</td>
				</tr>
			</table>