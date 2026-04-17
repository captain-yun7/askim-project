<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>
<?
$param = "searchopt=$searchopt&keyword=$keyword";
?>
<script type="text/javascript">
//체크박스선택 반전
function onSelect(form){
	if(form.select_tmp.checked){
		selectAll();
	}else{
		selectEmpty();
	}
}

//체크박스 전체선택
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

//체크박스 선택해제
function selectEmpty(){

	var i;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

//선택상품 삭제
function basketDelete(num){

	var i;
	var selorder = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selorder = selorder + document.forms[i].idx.value + "|";
				}
			}
	}

	if(selorder == ""){
		alert("장바구니에 담겨진 상품을 선택하지 않았습니다.");
		return;
	}else{
		if(confirm("선택한 장바구니상품을 정말 삭제하시겠습니까?")){
			document.location = "prd_save.php?mode=basketDel&selorder=" + selorder + "&page="+num+"&<?=$param?>";
		}else{
			return;
		}
	}
	return;

}
</script>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">장바구니 통계</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">장바구니에 담긴 전체상품을 관리합니다.</td>
	</tr>
</table>
<br>

<form name="frm" action="<?=$PHP_SELF?>" method="get">
<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
	<tr>
		<td width="15%" class="t_name">&nbsp; 조건</td>
		<td width="85%" class="t_value">
			<select name="searchopt" class="select">
				<option value="">:: 조건선택 ::
				<option value="wp.prdname" <? if($searchopt == "wp.prdname") echo "selected"; ?>>상품명
				<option value="wb.memid" <? if($searchopt == "wb.memid") echo "selected"; ?>>회원아이디
			</select>
			<input type="text" name="keyword" value="<?=$keyword?>" class="input">
		</td>
	</tr>
</table>
<br>
<table width="100%" cellspacing="1" cellpadding="3" border="0">
	<tr>
		<td align="center">
			<input type="submit" value="검색" class="search_btn2">&nbsp;
			<input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>'">
		</td>
	</tr>
</table>
</form>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="10"></td></tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<form>
	<tr><td class="t_rd" colspan="20"></td></tr>
	<tr class="t_th">
		<th width="5%"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
		<th width="7%">No</th>
		<th width="7%"></th>
		<th>상품명</th>
		<th width="30%">옵션</th>
		<th width="9%">상품가격</th>
		<th width="9%">적립금</th>
		<th width="9%">회원명</th>
		<th width="9%">등록일</th>
	</tr>
	</form>
	<tr><td class="t_rd" colspan="20"></td></tr>
	<?
	if(!empty($searchopt)) $searchopt_sql = " and $searchopt like '%$keyword%' ";

	$sql = "SELECT count(*) as total 
		FROM wiz_basket_tmp as wb 
		inner join wiz_product as wp on wb.prdcode = wp.prdcode
		where 1 $searchopt_sql";
	$result = query($sql) or error("sql error");
	$row_c = sql_fetch_arr($result);
	$total = $row_c['total'];

	$rows = 12;
	$lists = 5;
	if(!$page) $page = 1;
	$page_count = ceil($total/$rows);
	if(!$page || $page > $page_count) $page = 1;
	$start = ($page-1)*$rows;
	$no = $total-$start;

	$sql = "SELECT wb.*, wp.del_type, wp.sellprice, wp.del_price, wc.purl
			FROM wiz_basket_tmp as wb 
			inner join wiz_product as wp on wb.prdcode = wp.prdcode 
			inner join (select * from wiz_cprelation group by prdcode) as wcp on wp.prdcode = wcp.prdcode
			inner join wiz_category as wc on wcp.catcode = wc.catcode
			where 1 $searchopt_sql 
			order by idx desc  limit $start, $rows";	
		$result = query($sql) or error("sql error");

	while(($brow = sql_fetch_arr($result)) && $rows){

		if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$brow['prdimg'])) $brow['prdimg_R'] = "/twcenter/images/noimg_S.gif";
		else $brow['prdimg_R'] = "/twcenter/data/prdimg/".$brow['prdimg'];

		$optcode          = "";
		$prdimg           = "";
		$del_type         = "";
		$prd_d_price      = $brow['price'] * $brow['amount'];
		$reserve_d_price  = $brow['reserve'] * $brow['amount'];
		$prd_price       += ($brow['price'] * $brow['amount']);

		$prdname      = "<strong>".$brow['prdname']."</strong>";

		// 상품 이미지
		if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$brow['prdimg'])) $prdimg = "/twcenter/images/noimg_S.gif";
		else $prdimg = "/twcenter/data/prdimg/".$brow['prdimg'];

		list($optcode3_v,$t_optcode3_v2) = explode("/",$brow['optcode3']);
		if(!isset($t_optcode3_v2)) $t_optcode3_v2 = 0.00;
		$optcode3_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode3_v2)."원)</span>";
		list($optcode4_v,$t_optcode4_v2) = explode("/",$brow['optcode4']);
		
		if(!isset($t_optcode4_v2)) $t_optcode4_v2 = 0.00;
		$optcode4_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode4_v2)."원)</span>";
		list($optcode8_v,$t_optcode8_v2) = explode("/",$brow['optcode8']);
		
		if(!isset($t_optcode8_v2)) $t_optcode8_v2 = 0.00;
		$optcode8_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode8_v2)."원)</span>";
		list($optcode9_v,$t_optcode9_v2) = explode("/",$brow['optcode9']);
		
		if(!isset($t_optcode9_v2)) $t_optcode9_v2 = 0.00;
		$optcode9_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode9_v2)."원)</span>";
		list($optcode10_v,$t_optcode10_v2) = explode("/",$brow['optcode10']);
		
		if(!isset($t_optcode10_v2)) $t_optcode10_v2 = 0.00;
		$optcode10_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode10_v2)."원)</span>";
		list($optcode11_v,$t_optcode11_v2) = explode("/",$brow['optcode11']);
		
		if(!isset($t_optcode11_v2)) $t_optcode11_v2 = 0.00;
		$optcode11_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode11_v2)."원)</span>";
		if(!isset($brow['optcode12'])) $brow['optcode12'] ='';
		list($optcode12_v,$t_optcode12_v2) = explode("/",$brow['optcode12']);
		
		if(!isset($t_optcode12_v2)) $t_optcode12_v2 = 0.00;
		$optcode12_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode12_v2)."원)</span>";
		if(!isset($brow['optcode13'])) $brow['optcode13'] = '';
		list($optcode13_v,$t_optcode13_v2) = explode("/",$brow['optcode13']);
		
		if(!isset($t_optcode13_v2)) $t_optcode13_v2 = 0.00;
		$optcode13_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode13_v2)."원)</span>";

		if($brow['opttitle5'] != '')  $optcode = $brow['opttitle5']." : ".$brow['optcode5']."," ;
		if($brow['opttitle6'] != '')  $optcode .= $brow['opttitle6']." : ".$brow['optcode6'].", ";
		if($brow['opttitle7'] != '')  $optcode .= $brow['opttitle7']." : ".$brow['optcode7'].",<br> ";

		if($brow['opttitle3'] != '')  $optcode .= $brow['opttitle3']." : ".$optcode3_v." ".$optcode3_v2.", ";
		if($brow['opttitle4'] != '')  $optcode .= $brow['opttitle4']." : ".$optcode4_v." ".$optcode4_v2.",<br> ";
		if($brow['opttitle8'] != '')  $optcode .= $brow['opttitle8']." : ".$optcode8_v." ".$optcode8_v2.", ";
		if($brow['opttitle9'] != '')  $optcode .= $brow['opttitle9']." : ".$optcode9_v." ".$optcode9_v2.",<br> ";
		if($brow['opttitle10'] != '') $optcode .= $brow['opttitle10']." : ".$optcode10_v." ".$optcode10_v2.", ";
		if($brow['opttitle11'] != '') $optcode .= $brow['opttitle11']." : ".$optcode11_v." ".$optcode11_v2.",<br> ";
		if($brow['opttitle12'] != '') $optcode .= $brow['opttitle12']." : ".$optcode12_v." ".$optcode12_v2.", ";
		if($brow['opttitle13'] != '') $optcode .= $brow['opttitle13']." : ".$optcode13_v." ".$optcode13_v2.",<br> ";

		list($optcode_v,$t_optcode_v2) = explode("^",$brow['optcode']);
		if($t_optcode_v2 != 0){
			$optcode_v2 = "<span class='pay_add_tit2'>(".number_format($t_optcode_v2)."원)</span>";
		} else {
			$optcode_v2 = "";
		}

		if($brow['opttitle'] != '') $optcode .= $brow['opttitle'];
		if($brow['opttitle'] != '' && $brow['opttitle2'] != '') $optcode .= "/";
		if($brow['opttitle2'] != '') $optcode .= $brow['opttitle2'];
		if($brow['opttitle'] != '' || $brow['opttitle2'] != '') $optcode .= " : ".$optcode_v."".$optcode_v2.", ";

		$optcode = (substr(trim($optcode), -1) == ',') ? substr_replace(trim($optcode), '', -1) : $optcode;
		$optcode = "<span class='pay_add_tit'>".$optcode."</span>";
			
		$purl = $brow['purl'] ? "/".$brow['purl'] : "/shop/shop.php";
			
	?>
	<form name="<?=$brow['idx']?>" method="get">
	<input type="hidden" name="idx"    value="<?=$brow['idx']?>">
	<input type="hidden" name="page"    value="<?=$page?>">
	<tr>
		<td align="center"><input type="checkbox" name="select_checkbox"></td>
		<td align="center" height="85"><?=$no?></td>
		<td><a href="<?=$purl?>?ptype=view&prdcode=<?=$brow['prdcode']?>" target="_blank">&nbsp; <span class="img_border2"><img src="<?=$brow['prdimg_R']?>" width="65" height="70" border="0" align="absmiddle"></span></a></td>
		<td><a href="<?=$purl?>?ptype=view&prdcode=<?=$brow['prdcode']?>" target="_blank"><?=$brow['prdname']?></a></td>
		<td><a href="<?=$purl?>?ptype=view&prdcode=<?=$brow['prdcode']?>" target="_blank"><?=$optcode?></td>
		<td align="center"><?=number_format($prd_d_price)?>원</td>
		<td align="center"><?=number_format($reserve_d_price)?>원</td>
		<td align="center"><?=$brow['memid']?></td>
		<td align="center"><?=substr($brow['wdate'],0,10)?></td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	</form>
	<?
	$no--;
	$rows--;
	}
	?>
</table>

<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="5"></td></tr>
	<tr>
		<td width="33%">
			<input type="button" value="선택삭제" class="btnListchk" onclick="basketDelete(<?=$page?>);">
		</td>
		<td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
		<td width="33%"></td>
	</tr>
</table>

<? include "../foot.php"; ?>