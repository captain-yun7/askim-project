<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if($wiz_session['id'] == "") {
	error("로그인 후 이용하세요");
	exit;
}

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

?>
<script language="javascript">
<!--
function saveBasket(idx){
   var frm = eval("document.wishList_" + idx);
   frm.submit();
}

function delWish(idx){
   document.location = "my_save.php?mode=my_wishdel&idx=" + idx;
}

// 체크박스 전체선택
function selectAll(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = true;
			}
		}
	}
	return;
}

// 체크박스 선택해제
function selectCancel(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].idx != null){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

// 체크박스선택 반전
function selectReverse(form){

	if(form.select_tmp.checked){
		selectAll();
	}else{
		selectCancel();
	}
}

// 체크박스 선택리스트
function selectValue(){
	var i;
	var selprd = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selprd = selprd + document.forms[i].idx.value + "|";
				}
			}
	}
	return selprd;
}

//선택상품 삭제
function delPrd(){

	selprd = selectValue();

	if(selprd == ""){
		alert("삭제할 게시물을 선택하세요.");
		return false;
	}else{
		if(confirm("정말 삭제하시겠습니까?")){
			document.location = "/twcenter/product/prd_save.php?mode=my_wishdel&selprd=" + selprd + "&prev=<?=urlencode($PHP_SELF)?>";
		}
	}
}

//선택상품 장바구니담기
function basketPrd() {

	selprd = selectValue();

	if(selprd == ""){
		alert("장바구니에 담을 관심상품을 선택하세요.");
		return false;
	}else{
		document.location = "/twcenter/product/prd_save.php?mode=insert&direct=basket&selprd=" + selprd;
	}
}

-->
</script>

<!--관심상품리스트-->
<?
// 정렬순서
if(empty($orderby)) $order_sql = "order by ww.wdate desc";
else $order_sql = "order by $orderby";

$sql = "select ww.idx from wiz_wishlist ww, wiz_product wp where ww.memid = '{$wiz_session['id']}' and ww.prdcode = wp.prdcode";
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);

$no = 0;
$rows = 10;
$lists = 5;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="padding:0px;">

    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="bpad_30">

<div class="wish_top">
	<h6>총 관심상품은<span class="point_txt"> <?=$total?></span>개 입니다.</h6>
	<div class="array">
		<form action="<?=$PHP_SELF?>" method="get">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="rpad_10">상품정렬방식</td>
					<td>
						<label for="select_type1" class="blind">상품정렬방식</label>
						<select name="orderby" onChange="this.form.submit();" id="select_type1" class="select_type1">
						<option value="">상품정렬방식</option>
						<option value="viewcnt desc" <? if($orderby == "viewcnt desc") echo "selected"; ?>>조회수 순</option>
						<option value="wp.prdcode desc" <? if($orderby == "wp.prdcode desc") echo "selected"; ?>>최근등록순 순</option>
						<option value="sellprice asc" <? if($orderby == "sellprice asc") echo "selected"; ?>>최저가격 순</option>
						<option value="sellprice desc" <? if($orderby == "sellprice desc") echo "selected"; ?>>최고가격 순</option>
						</select>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>

        </td>
      </tr>
      <tr>
        <td valign="top">

           <table width="100%" border="0" cellpadding="0" cellspacing="0" class="order_table">
             <tr>
                <td width="8%" class="table_tit">선택</td>
                <td class="table_tit">상품명</td>
                <td width="18%" class="table_tit">상품가격</td>
                <td width="18%" class="table_tit">저장일시</td>
              </tr>

<?
$sql = "select ww.*, wp.prdcode, wp.prdname, wp.sellprice, wp.strprice, wp.prdimg_R, wp.popular, wp.recom, wp.new, wp.sale, wp.shortage, wp.stock, wp.conprice from wiz_wishlist ww, wiz_product wp where ww.memid = '{$wiz_session['id']}' and ww.prdcode = wp.prdcode $order_sql limit $start, $rows";
$result = query($sql) or error("sql error");

while(($row = sql_fetch_obj($result)) && $rows){

	$sp_img = "";
	$optcode = "";

	if($row->popular == "Y") $sp_img .= "<img src='/twcenter/images/icon_hit.gif'>&nbsp;";
	if($row->recom == "Y")   $sp_img .= "<img src='/twcenter/images/icon_rec.gif'>&nbsp;";
	if($row->new == "Y")     $sp_img .= "<img src='/twcenter/images/icon_new.gif'>&nbsp;";
	if($row->sale == "Y"){   $sp_img .= "<img src='/twcenter/images/icon_sale.gif'>&nbsp;"; $sellprice = "<s>".number_format($row->conprice ?? 0)." 원</s> → "; }
	if($row->shortage == "Y" || ($row->shortage == "S" && $row->stock <= 0)) $sp_img .= "<img src='/twcenter/images/icon_not.gif'>&nbsp;";

	$optcode = $opt = $opt3 = $opt5 = $opt6 = $opt7 = $opt8 = $opt9 = $opt10 = $opt11 = $opt12 = $opt13 = "";

	if(strpos($row->optcode5 ?? '',"&&") !== false){
		$opt5_val = explode("&&",$row->optcode5);
		for($i=0; $i<count($opt5_val)-1; $i++){
			$exp = $opt5_val[$i];
			list($optcode5_v,$t_optcode5_v2,$t_optcode5_v3,$t_optcode5_v4) = explode("^",$exp);
			$optcode5_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode5_v2 ?? 0)."원 / ".$t_optcode5_v4."개)</span>";
			$opt5 .= $row->opttitle5." : ".$optcode5_v." ".$optcode5_v2."<br>";
		}
	} else {
		list($optcode5_v,$t_optcode5_v2) = explode("/",$row->optcode5);
		$optcode5_v2 = "";
		$opt5 = $row->opttitle5." : ".$optcode5_v." ".$optcode5_v2."<br>";
	}

	if($row->opttitle5 != '' && $row->optcode5 != '')  $optcode .= $opt5;


	if(strpos($row->optcode6 ?? '',"&&") !== false){
		$opt6_val = explode("&&",$row->optcode6);
		for($i=0; $i<count($opt6_val)-1; $i++){
			$exp = $opt6_val[$i];
			list($optcode6_v,$t_optcode6_v2,$t_optcode6_v3,$t_optcode6_v4) = explode("^",$exp);
			$optcode6_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode6_v2 ?? 0)."원 / ".$t_optcode6_v4."개)</span>";
			$opt6 .= $row->opttitle6." : ".$optcode6_v." ".$optcode6_v2."<br>";
		}
	} else {
		list($optcode6_v,$t_optcode6_v2) = explode("/",$row->optcode6);
		$optcode6_v2 = "";
		$opt6 = $row->opttitle6." : ".$optcode6_v." ".$optcode6_v2."<br>";
	}

	if($row->opttitle6 != '' && $row->optcode6 != '')  $optcode .= $opt6;


	if(strpos($row->optcode7 ?? '',"&&") !== false){
		$opt7_val = explode("&&",$row->optcode7);
		for($i=0; $i<count($opt7_val)-1; $i++){
			$exp = $opt7_val[$i];
			list($optcode7_v,$t_optcode7_v2,$t_optcode7_v3,$t_optcode7_v4) = explode("^",$exp);
			$optcode7_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode7_v2 ?? 0)."원 / ".$t_optcode7_v4."개)</span>";
			$opt7 .= $row->opttitle7." : ".$optcode7_v." ".$optcode7_v2."<br>";
		}
	} else {
		list($optcode7_v,$t_optcode7_v2) = explode("/",$row->optcode7);
		$optcode7_v2 = "";
		$opt7 = $row->opttitle7." : ".$optcode7_v." ".$optcode7_v2."<br>";
	}

	if($row->opttitle7 != '' && $row->optcode7 != '')  $optcode .= $opt7;



	if(strpos($row->optcode3 ?? '',"&&") !== false){
		$opt3_val = explode("&&",$row->optcode3);
		for($i=0; $i<count($opt3_val)-1; $i++){
			$exp = $opt3_val[$i];
			list($optcode3_v,$t_optcode3_v2,$t_optcode3_v3,$t_optcode3_v4) = explode("^",$exp);
			$optcode3_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode3_v2 ?? 0)."원 / ".$t_optcode3_v4."개)</span>";
			$opt3 .= $row->opttitle3." : ".$optcode3_v." ".$optcode3_v2."<br> ";
		}
	} else {
		list($optcode3_v,$t_optcode3_v2) = explode("/",$row->optcode3 ?? '');
		$optcode3_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode3_v2 ?? 0)."원)</span>";
		$opt3 = $row->opttitle3." : ".$optcode3_v." ".$optcode3_v2."<br>";
	}

	if(strpos($row->optcode4 ?? '',"&&") !== false){
		$opt4_val = explode("&&",$row->optcode4);
		for($i=0; $i<count($opt4_val)-1; $i++){
			$exp = $opt4_val[$i];
			list($optcode4_v,$t_optcode4_v2,$t_optcode4_v3,$t_optcode4_v4) = explode("^",$exp);
			$optcode4_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode4_v2 ?? 0)."원 / ".$t_optcode4_v4."개)</span>";
			$opt4 .= $row->opttitle4." : ".$optcode4_v." ".$optcode4_v2."<br> ";
		}
	} else {
		list($optcode4_v,$t_optcode4_v2) = explode("/",$row->optcode4 ?? '');
		$optcode4_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode4_v2 ?? 0)."원)</span>";
		$opt4 = $row->opttitle4." : ".$optcode4_v." ".$optcode4_v2."<br>";
	}

	if(strpos($row->optcode8 ?? '',"&&") !== false){
		$opt8_val = explode("&&",$row->optcode8);
		for($i=0; $i<count($opt8_val)-1; $i++){
			$exp = $opt8_val[$i];
			list($optcode8_v,$t_optcode8_v2,$t_optcode8_v3,$t_optcode8_v4) = explode("^",$exp);
			$optcode8_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode8_v2 ?? 0)."원 / ".$t_optcode8_v4."개)</span>";
			$opt8 .= $row->opttitle8." : ".$optcode8_v." ".$optcode8_v2."<br> ";
		}
	} else {
		list($optcode8_v,$t_optcode8_v2) = explode("/",$row->optcode8 ?? '');
		$optcode8_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode8_v2 ?? 0)."원)</span>";
		$opt8 = $row->opttitle8." : ".$optcode8_v." ".$optcode8_v2."<br>";
	}

	if(strpos($row->optcode9 ?? '',"&&") !== false){
		$opt9_val = explode("&&",$row->optcode9);
		for($i=0; $i<count($opt9_val)-1; $i++){
			$exp = $opt9_val[$i];
			list($optcode9_v,$t_optcode9_v2,$t_optcode9_v3,$t_optcode9_v4) = explode("^",$exp);
			$optcode9_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode9_v2 ?? 0)."원 / ".$t_optcode9_v4."개)</span>";
			$opt9 .= $row->opttitle9." : ".$optcode9_v." ".$optcode9_v2."<br> ";
		}
	} else {
		list($optcode9_v,$t_optcode9_v2) = explode("/",$row->optcode9 ?? '');
		$optcode9_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode9_v2 ?? 0)."원)</span>";
		$opt9 = $row->opttitle9." : ".$optcode9_v." ".$optcode9_v2."<br>";
	}

	if(strpos($row->optcode10 ?? '',"&&") !== false){
		$opt10_val = explode("&&",$row->optcode10);
		for($i=0; $i<count($opt10_val)-1; $i++){
			$exp = $opt10_val[$i];
			list($optcode10_v,$t_optcode10_v2,$t_optcode10_v3,$t_optcode10_v4) = explode("^",$exp);
			$optcode10_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode10_v2 ?? 0)."원 / ".$t_optcode10_v4."개)</span>";
			$opt10 .= $row->opttitle10." : ".$optcode10_v." ".$optcode10_v2."<br> ";
		}
	} else {
		list($optcode10_v,$t_optcode10_v2) = explode("/",$row->optcode10 ?? '');
		$optcode10_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode10_v2 ?? 0)."원)</span>";
		$opt10 = $row->opttitle10." : ".$optcode10_v." ".$optcode10_v2."<br>";
	}

	if(strpos($row->optcode11 ?? '',"&&") !== false){
		$opt11_val = explode("&&",$row->optcode11);
		for($i=0; $i<count($opt11_val)-1; $i++){
			$exp = $opt11_val[$i];
			list($optcode11_v,$t_optcode11_v2,$t_optcode11_v3,$t_optcode11_v4) = explode("^",$exp);
			$optcode11_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode11_v2 ?? 0)."원 / ".$t_optcode11_v4."개)</span>";
			$opt11 .= $row->opttitle11." : ".$optcode11_v." ".$optcode11_v2."<br> ";
		}
	} else {
		list($optcode11_v,$t_optcode11_v2) = explode("/",$row->optcode11 ?? '');
		$optcode11_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode11_v2 ?? 0)."원)</span>";
		$opt11 = $row->opttitle11." : ".$optcode11_v." ".$optcode11_v2."<br>";
	}

	if(strpos($row->optcode12 ?? '',"&&") !== false){
		$opt12_val = explode("&&",$row->optcode12);
		for($i=0; $i<count($opt12_val)-1; $i++){
			$exp = $opt12_val[$i];
			list($optcode12_v,$t_optcode12_v2,$t_optcode12_v3,$t_optcode12_v4) = explode("^",$exp);
			$optcode12_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode12_v2 ?? 0)."원 / ".$t_optcode12_v4."개)</span>";
			$opt12 .= $row->opttitle12." : ".$optcode12_v." ".$optcode12_v2."<br> ";
		}
	} else {
		list($optcode12_v,$t_optcode12_v2) = explode("/",$row->optcode12 ?? '');
		$optcode12_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode12_v2 ?? 0)."원)</span>";
		$opt12 = $row->opttitle12." : ".$optcode12_v." ".$optcode12_v2."<br>";
	}

	if(strpos($row->optcode13 ?? '',"&&") !== false){
		$opt13_val = explode("&&",$row->optcode13);
		for($i=0; $i<count($opt13_val)-1; $i++){
			$exp = $opt13_val[$i];
			list($optcode13_v,$t_optcode13_v2,$t_optcode13_v3,$t_optcode13_v4) = explode("^",$exp);
			$optcode13_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode13_v2 ?? 0)."원 / ".$t_optcode13_v4."개)</span>";
			$opt13 .= $row->opttitle13." : ".$optcode13_v." ".$optcode13_v2."<br> ";
		}
	} else {
		list($optcode13_v,$t_optcode13_v2) = explode("/",$row->optcode13 ?? '');
		$optcode13_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode13_v2 ?? 0)."원)</span>";
		$opt13 = $row->opttitle13." : ".$optcode13_v." ".$optcode13_v2."<br>";
	}


	if($row->opttitle3 != ''  && $row->optcode3 != '')  $optcode .= $opt3;
	if($row->opttitle4 != ''  && $row->optcode4 != '')  $optcode .= $opt4;
	if($row->opttitle8 != ''  && $row->optcode8 != '')  $optcode .= $opt8;
	if($row->opttitle9 != ''  && $row->optcode9 != '')  $optcode .= $opt9;
	if($row->opttitle10 != '' && $row->optcode10 != '') $optcode .= $opt10;
	if($row->opttitle11 != '' && $row->optcode11 != '') $optcode .= $opt11;
	if($row->opttitle12 != '' && $row->optcode12 != '') $optcode .= $opt12;
	if($row->opttitle13 != '' && $row->optcode13 != '') $optcode .= $opt13;

	if(strpos($row->optcode,"&&") !== false){
		$opt_val = explode("&&",$row->optcode);
		for($i=0; $i<count($opt_val)-1; $i++){
			$exp = $opt_val[$i];
			list($optcode_v,$t_optcode_v2,$t_optcode_v3,$t_optcode_v4) = explode("^",$exp);
			$optcode_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode_v2)."원 / ".$t_optcode_v4."개)</span>";

			if($row->opttitle != '') $topttitle = $row->opttitle;
			if($row->opttitle != '' && $row->opttitle2 != '') $topttitle .= "/";
			if($row->opttitle2 != '') $topttitle .= $row->opttitle2;

			$opt .= $topttitle." : ".$optcode_v." ".$optcode_v2."<br> ";
		}
	} else {
		list($optcode_v,$t_optcode_v2) = explode("^",$row->optcode);
		if($t_optcode_v2 != 0){
			$optcode_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode_v2)."원)</span>";
		} else {
			$optcode_v2 = "";
		}

		if($row->opttitle != '') $topttitle = $row->opttitle;
		if($row->opttitle != '' && $row->opttitle2 != '') $topttitle .= "/";
		if($row->opttitle2 != '') $topttitle .= $row->opttitle2;

		$opt .= $topttitle." : ".$optcode_v." ".$optcode_v2."<br> ";
	}


	if($row->opttitle != '' || $row->opttitle2 != '') $optcode .= $opt;

	$optcode = (substr(trim($optcode), -1) == ',') ? substr_replace(trim($optcode), '', -1) : $optcode;
	$optcode = "<span class='pay_add_tit'>".$optcode."</span>";


	if(!empty($row->strprice)) $row->sellprice = $row->strprice;
	//else $row->sellprice = number_format($row->sellprice)."원";
	else $row->sellprice = number_format($row->price)."원";

	$c_sql = "select wc.purl
						from wiz_product as wp left join wiz_cprelation as wcp on wp.prdcode = wcp.prdcode
						left join wiz_category as wc on wcp.catcode = wc.catcode
						where wp.prdcode = '$row->prdcode'";
	$c_result = query($c_sql) or error("sql error");
	$c_row = sql_fetch_arr($c_result);

	// 상품 이미지
	if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/twcenter/images/noimg_R.gif";
	else $row->prdimg_R = "/twcenter/data/prdimg/".$row->prdimg_R;

	$prd_view_page = "/".$c_row['purl']."?ptype=view&prdcode=".$row->prdcode."&catcode=".$catcode."&page=".$page;

?>
              <tr>
				<form name="wishList_<?=$row->idx?>" action="/twcenter/product/prd_save.php" method="post">
				<input type="hidden" name="mode" value="insert">
				<input type="hidden" name="direct" value="basket">
				<input type="hidden" name="idx" value="<?=$row->idx?>">
                <td align="center" class="con"><input type="checkbox" name="select_checkbox"></td>
                <td style="padding-left:10px;" class="con">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
					<td width="20%" align="center" style="padding:5px; border:none;"><a href="<?=$prd_view_page?>" target="_blank"><img src="<?=$row->prdimg_R?>" border="0" width="90" style="border:1px solid #ddd;"></a></td>
					<td align="left" style="border:none;" class="wish_prd"><a href="<?=$prd_view_page?>" target="_blank"><?=$row->prdname?></a> <?=$sp_img?> <br><?=$optcode?></td>
					</tr>
					</table>
                </td>
                <td align="center" class="price_bk"><?=$row->sellprice?></td>
                <td align="center" class="con"><?=$row->wdate?></td>
            	</form>
            	</tr>

<?
	$no++;
	$rows--;
}

if($total <= 0){
?>
							<tr><td align="center" colspan="10" height="50">관심상품 리스트가 없습니다.</td></tr>

<?
}
?>
            </table>

          	<table width="100%" border="0" cellpadding="0" cellspacing="0">
			 <tr><td colspan="3" height="30"></td></tr>
              <tr>
                <td width="30%">
					 <input type="button" onClick="delPrd()" value="선택삭제" class="btn_type2"><input type="button" onClick="basketPrd()" value="장바구니 담기" class="btn_type2">
							  	<!-- <img src="<?=$skin_dir?>/image/btn_seldel.gif" width="77" height="25" border="0" onClick="delPrd()" style="cursor:pointer;" />
							  	<img src="<?=$skin_dir?>/image/btn_selbasket.gif" width="87" height="25" border="0" onClick="basketPrd()" style="cursor:pointer;" /> -->
                </td>
                <td width="40%" align="center"><? print_pagelist($page, $lists, $page_count, "orderby=$orderby"); ?></td>
                <td width="30%"></td>
              </tr>
            </table>

        </td>
      </tr>
    </table>

    </td>
  </tr>
</table>