<? if(!strcmp($oper_info['prdrel_use'], "Y")) { ?>
<!-- 관련상품 -->
<div class="prd_tab">
  <ul>
	<li><a href="#info">상품정보</a></li>
	<? if(!strcmp($oper_info['prdrel_use'], "Y")) { ?><li><a href="#rel" class="prd_tab_up">관련상품</a></li><? } ?>
	<? if(!strcmp($oper_info['qna_usetype'], "Y")) { ?><li><a href="#qna">상품 Q&amp;A <span class="review_num">(<?=$qna_cnt?>)</span></a></li><? } ?>
	<? if(!strcmp($oper_info['review_usetype'], "Y")) { ?><li><a href="#review">상품후기 <span class="review_num">(<?=@number_format($review_count)?>)</span></a></li><? } ?>
  </ul>
</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="5" style="padding:0 5px;">

	  	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr><td width="20%"></td><td width="20%"></td><td width="20%"></td><td width="20%"></td><td width="20%"></td></tr>
	      <tr valign="top">
<?
$no = 0;
$rel_sql = "select wr.idx,wp.prdcode,wp.prdname,wp.prdimg_R,wp.sellprice,wp.strprice,
						wp.coupon_use, wp.coupon_edate, wp.coupon_limit, wp.coupon_amount, wp.coupon_dis, wp.coupon_type
						from wiz_prdrelation wr, wiz_product wp
						where wr.prdcode = '$prdcode' and wr.relcode = wp.prdcode and wp.showset != 'N'";
$rel_result = query($rel_sql);
while($rel_row = sql_fetch_obj($rel_result)){
	if($no%5 == 0) echo "<tr>";

	if(!empty($rel_row->strprice)) $rel_row->sellprice = $rel_row->strprice;
	else $rel_row->sellprice = number_format($rel_row->sellprice)."원";

	// 상품 이미지
	if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$rel_row->prdimg_R)) $rel_row->prdimg_R = "/twcenter/images/noimg_R.gif";
	else $rel_row->prdimg_R = "/twcenter/data/prdimg/".$rel_row->prdimg_R;

	// 쿠폰아이콘
	$coupon_img = "";
	if(
	$rel_row->coupon_use == "Y" &&
	$rel_row->coupon_edate >= date('Y-m-d') &&
	($rel_row->coupon_limit == "N" || ($rel_row->coupon_limit == "" && $rel_row->coupon_amount > 0))
	){

		$coupon_img = "<img src='/twcenter/images/icon_coupon.gif' border='0' align='absbottom'>&nbsp;<font class=coupon>".number_format($rel_row->coupon_dis).$rel_row->coupon_type."</font>";
	}

	$rel_prd_view_page = $_SERVER['PHP_SELF']."?ptype=view&prdcode=".$rel_row->prdcode;

?>
					<td width="20%" align="center" valign="top">
	           <table width="180" border="0" cellpadding="0" cellspacing="0" class="pro_list">
	              <tr>
	                <td class="prd"><a href="<?=$rel_prd_view_page?>"><img src="<?=$rel_row->prdimg_R?>" width="180" height="180" border="0"></a></td>
	              </tr>
	              <tr>
	                <td height="15"></td>
	              </tr>
	              <tr>
	                <td class="subject"><a href="<?=$rel_prd_view_page?>"><?=cut_str($rel_row->prdname,32)?></a></td>
	              </tr>
	              <tr>
	                <td class="price"><?=$rel_row->sellprice?></td>
	              </tr>
	              <tr>
	                <td class="coupon"><?=$coupon_img?></td>
	              </tr>
	              <tr>
	                <td height="20"></td>
	              </tr>
	            </table>
	          </td>
<?
	$no++;
}
?>
					</tr>
				</table>

    </td>
	</tr>
</table>
<? } ?>