<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/oper_info.php"; ?>
<? include "../head.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function delCoupon(idx){
   if(confirm('해당쿠폰을 삭제하시겠습니까?')){
      document.location = "shop_save.php?mode=shop_coupon&sub_mode=delete&idx=" + idx;
   }
}

// 상품별쿠폰 발급회원
function popMycoupon(prdcode){
	var url = "shop_mycoupon.php?prdcode=" + prdcode;
	window.open(url,"MyCouponList","height=400, width=600, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}
//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">쿠폰관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">쿠폰을 등록,수정합니다.</td>
        </tr>
      </table>

			<br>
      <form name="frm" action="shop_save.php" method="post">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="coupon_use">
	  <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">쿠폰사용여부</td>
          <td width="85%" class="t_value">
            <label style="vertical-align: middle"><input type="radio" name="coupon_use" value="Y" <? if($oper_info['coupon_use'] == "Y") echo "checked"; ?>></label>사용함
            <label style="vertical-align: middle"><input type="radio" name="coupon_use" value="N" <? if($oper_info['coupon_use'] == "N") echo "checked"; ?>></label>사용안함 &nbsp;
            <input type="submit" value="확인" align="absmiddle" title="확인" class="btnListchk3" />
          </td>
        </tr>
      </table>
	  </form>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 이벤트쿠폰</td>
			    <td align="right">
					<input type="button" value="쿠폰등록" class="btnListchk3" onClick="document.location='shop_coupon_input.php?sub_mode=insert&menucode=PRODUCT';">
				</td>
			  </tr>
			  <tr><td height="8" colspan=2></td></tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th>쿠폰명</th>
          <th width="220">기간</th>
          <th width="100">할인</th>
          <th width="100">수량</th>
          <th width="90">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
	      <?
	      $sql = "select * from wiz_coupon order by idx desc";
	      $result = query($sql) or error("sql error");
	      $total = sql_fetch_row($result);
				$no = $total;
	      while($row = sql_fetch_arr($result)){
	      	if($row['coupon_limit'] == "N") $row['coupon_amount'] = "수량제한없음";
	      ?>
        <tr>
          <td height="40" align="center"><?=$no?></td>
          <td><?=$row['coupon_name']?></td>
          <td align="center"><?=$row['coupon_sdate']?> ~ <?=$row['coupon_edate']?></td>
          <td align="center"><?=$row['coupon_dis']?><?=$row['coupon_type']?></td>
          <td align="center"><?=$row['coupon_amount']?></td>
          <td align="center">
            <input type="button" value="수정" align="absmiddle" title="수정" class="optadd" onclick="document.location='shop_coupon_input.php?sub_mode=update&idx=<?=$row['idx']?>&menucode=PRODUCT'" />
			<input type="button" value="삭제" align="absmiddle" title="삭제" class="optdel" onclick="delCoupon('<?=$row['idx']?>');" />
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
	      <?
	      		$no--;
	      }
	      if($total <= 0){
	      ?>
	        <tr align="center"><td height="30" colspan="10" align="center">등록된 쿠폰이 없습니다.</td></tr>
	        <tr><td colspan="20" class="t_line"></td></tr>
	      <?
	      }
	      ?>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 상품별쿠폰</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th>상품명</th>
          <th width="220">기간</th>
          <th width="100">할인</th>
          <th width="100">수량</th>
          <th width="140">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
      <?
      $sql = "select prdcode from wiz_product where coupon_use='Y' order by prior desc";
      $result = query($sql) or error("sql error");
      $total = sql_fetch_row($result);

      $rows = 12;
      $lists = 5;
    	$page_count = ceil($total/$rows);
    	if($page < 1 || $page > $page_count) $page = 1;
    	$start = ($page-1)*$rows;
    	$no = $total-$start;

      $sql = "select prdcode,prdname,coupon_dis,coupon_type,coupon_sdate,coupon_edate,coupon_amount,coupon_limit from wiz_product where coupon_use='Y' order by prior desc limit $start, $rows";
      $result = query($sql) or error("sql error");

      while(($row = sql_fetch_arr($result)) && $rows){
      	if($row['coupon_limit'] == "N") $row['coupon_amount'] = "수량제한없음";
      ?>
        <tr>
          <td height="40" align="center"><?=$no?></td>
          <td><a href="../product/prd_input.php?mode=update&prdcode=<?=$row['prdcode']?>&menucode=PRODUCT"><?=$row['prdname']?></a></td>
          <td align="center"><?=$row['coupon_sdate']?> ~ <?=$row['coupon_edate']?></td>
          <td align="center"><?=$row['coupon_dis']?><?=$row['coupon_type']?></td>
          <td align="center"><?=$row['coupon_amount']?></td>
          <td align="center">
          	<input type="button" value="수정" align="absmiddle" title="수정" class="optadd" onclick="document.location='../product/prd_input.php?mode=update&prdcode=<?=$row['prdcode']?>&menucode=PRODUCT'" />
			<input type="button" value="쿠폰발급회원" align="absmiddle" title="쿠폰발급회원" class="optdel" onclick="popMycoupon('<?=$row['prdcode']?>')"/>
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
      <?
      		$no--;
					$rows--;
      }
      if($total <= 0){
      ?>
        <tr align="center">
          <td height="30" colspan="10" align="center">등록된 쿠폰적용 상품이 없습니다.</td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
      <?
      }
      ?>
      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="5"></td></tr>
      	<tr>
      		<td><? print_pagelist($page, $lists, $page_count, ""); ?></td>
      	</tr>
      </table>

		<br>
		<table width="100%" border="0" cellspacing="10" cellpadding="8" bgcolor="9d9d9d" align="center">
			<tr>
				<td align="center">
					<table width="100%" border="0" cellspacing="0" cellpadding="6">
						<tr>
							<td><img src="../image/check_tit.gif" width="75" height="19" /></td>
						</tr>
						<tr>
							<td class="chk_alt">
							  - 쿠폰사용시 : 이벤트쿠폰,상품별쿠폰 발행이 가능합니다.<br>
							  &nbsp; 관리자 > 상품관리 > 상품등록시 상품별 쿠폰발행기능 생성<br>
							  &nbsp; 쇼핑몰 > 상품상세페이지 에서 상품별 할인쿠폰 다운로드 기능<br>
							  &nbsp; 쇼핑몰 > 주문정보입력 페이지에서 쿠폰조회 및 사용기능<br>
							  &nbsp; 쇼핑몰 > 마이페이지에서 쿠폰 사용내역 조회기능<br><br>

							  - 이벤트쿠폰 : 기간과 할인액등을 설정하여 쿠폰을 생성합니다. 다운받은 쿠폰은 모든 상품구입시 사용 가능합니다.<br>
							  &nbsp; 특정상품 구입시만 또는 특정상품을 제외하고 사용하는 기능은 제공하지 않습니다.<br><br>

							  - 상품별쿠폰 : 특정상품에 대해서만 쿠폰을 발행하며 다운받은 쿠폰은 해당 상품 구입시에만 사용 가능합니다.<br>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>


<? include "../foot.php"; ?>