<?
if(($catcode != "" && $cat_info['recom_use'] == "Y") || (!empty($brand) && !strcmp($brd_info['recom_use'], "Y"))){
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="10"></td></tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td style="border:3px solid #eaeaea;">

			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td height="37" style="border-bottom:1px solid #e5e5e5; text-align:left;"><img src="<?=$skin_dir?>/image/recom_tit.gif" /></td>
			  </tr>
			  <tr>
			    <td style="padding-top:8px;" align="center">
					<?php

						if(!empty($brand)) $brand_sql = " and wp.brand = '$brand' ";

						$tmpcode = substr($catcode,0,2);
						$sql = "select distinct wp.prdcode, wp.prdname, wp.stortexp, wp.prdcom, wp.reserve, wp.sellprice, wp.prdimg_M1, wp.prdimg_R, wp.popular, wp.recom, wp.new, wp.sale, wp.best, wp.stock, wp.conprice,
										wp.conprice, wp.coupon_use, wp.coupon_type, wp.coupon_dis, wp.coupon_amount, wp.coupon_limit, wp.coupon_edate
										from wiz_cprelation wc, wiz_product wp, wiz_category wcat where wc.catcode like '$tmpcode%' and wp.recom = 'Y' and wc.prdcode = wp.prdcode and wp.showset != 'N' and wc.catcode = wcat.catcode and wcat.catuse != 'N' $brand_sql
										order by wp.prior desc, prdcode desc limit 5";
						$result = query($sql) or error("sql error");
						$total = sql_fetch_row($result);

						if($total > 0) {

					?>
			    	<table width="99%" border="0" cellpadding="0" cellspacing="0">
			        <tr valign="top">
					<?
					$no = 0;
					while($row = sql_fetch_obj($result)){

						// 상품아이콘
						$sp_img = "";
						if($row->popular == "Y") 	$sp_img .= "<img src='/twcenter/images/icon_hit.gif'>&nbsp;";
						if($row->recom == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_rec.gif'>&nbsp;";
						if($row->new == "Y") 			$sp_img .= "<img src='/twcenter/images/icon_new.gif'>&nbsp;";
						if($row->sale == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_sale.gif'>&nbsp;";
						if($row->best == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_best.gif'>&nbsp;";
						if($row->shortage == "Y" || $row->stock <= 0) $sp_img .= "<img src='/twcenter/images/icon_not.gif'>&nbsp;";

						$prdicon_list = explode("/",$row->prdicon);
						for($ii=0; $ii<count($prdicon_list)-1; $ii++){
							$sp_img .= "<img src='/twcenter/data/prdicon/".$prdicon_list[$ii]."'> ";
						}

						// 쿠폰아이콘
						$coupon_img = "";
						if(
						$row->coupon_use == "Y" &&
						$row->coupon_edate >= date('Y-m-d') &&
						($row->coupon_limit == "N" || ($row->coupon_limit == "" && $row->coupon_amount > 0))
						){

							$coupon_img = "<font class=coupon>".$row->coupon_dis.$row->coupon_type."</font> <img src='/twcenter/images/icon_coupon.gif' align='absmiddle'>";
						}

						// 정상가(판매가보다 높을경우 할인표시)
						$conprice = "";
						if($row->conprice > $row->sellprice){
							$conprice = "<s>".number_format($row->conprice)."원</s> → ";
						}

						$sellprice = "<font class=price>".number_format($row->sellprice)."원</font>";

						// 상품 이미지
						if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/twcenter/images/noimg_R.gif";
						else $row->prdimg_R = "/twcenter/data/prdimg/".$row->prdimg_R;

						//if($no != 0) echo "<td width='1' background='$skin_dir/image/prdline_dot_bg.gif' valign='top'></td>";

						$prd_view_page = "/".$cat_info['purl']."?ptype=view&prdcode=".$row->prdcode."&catcode=".$catcode."&brand=".$brand."&page=".$page;

					?>
			          <td width="20%" align="center">
			          	<table border="0" cellpadding="0" cellspacing="0" class="pro_list">
			              <tr>
			                <td class="prd"><a href="<?=$prd_view_page?>"><img src="<?=$row->prdimg_R?>" border="0" width="<?=$prd_width?>" height="<?=$prd_height?>"></a></td>
			              </tr>
			              <tr>
			                <td height="15"></td>
			              </tr>
			              <tr>
			                <td align="center"><a href="<?=$prd_view_page?>"><?=cut_str($row->prdname,25)?></a></td>
			              </tr>
			              <tr>
			                <td class="price"><?=$conprice?><?=$sellprice?></td>
			              </tr>
			              <tr>
			                <td class="coupon"><?=$sp_img?> <?=$coupon_img?></td>
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
				<?
					}else{
				?>
						<table width="99%" border="0" cellpadding="0" cellspacing="0">
			        <tr><td align="center" height="40">등록된 추천상품이 없습니다.</td></tr>
			      </table>
				<?
					}
				?>
					</td>
				</tr>
			</table>

		</td>
	</tr>
</table>
<?
}
?>