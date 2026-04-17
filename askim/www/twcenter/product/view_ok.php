<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/site_info.php';
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/prd_info.php';
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/oper_info.php';

$current_url = $_SERVER['PHP_SELF'];

if($_GET['catcode'] != "") $catcode = $_GET['catcode'];

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";
echo "<script language='javascript' src='/twcenter/js/lib.js'></script>";

$param = "grp=$grp&brand=$brand&orderby=$orderby&searchopt=$searchopt&searchkey=$searchkey";

// 상품정보 가져오기 (이동하지 말것)
$sql = "select *, new as newc from wiz_product wp, wiz_cprelation wc where wp.prdcode='$prdcode' and wc.prdcode = wp.prdcode";
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);
$prd_row = sql_fetch_arr($result);
if($prdcode == "" || $total <= 0) error("존재하지 않는 상품입니다.");
if($catcode == "") $catcode = $prd_row['catcode'];

// 상품 조회수 업데이트
$sql = "update wiz_product set viewcnt = viewcnt + 1 where prdcode = '$prdcode'";
query($sql) or error("sql error");

include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/cat_info.php'; 		// 카테고리정보

$shortexp = nl2br($prd_row['shortexp']);
$content = $prd_row['content'];
$prdname = $prd_row['prdname'];

// 오늘본 상품목록에 추가
$view_exist = false;
$view_idx = 0;
for($ii=0;$ii<100;$ii++){
	if($_SESSION["view_list"][$ii][prdcode]) $view_idx++;
}
for($ii = 0; $ii < $view_idx; $ii++){
	if($_SESSION["view_list"][$ii][prdcode] == $prdcode){ $view_exist = true; break; }
}
if(!$view_exist){
	$_SESSION["view_list"][$view_idx][prdcode] = $prdcode;
	$_SESSION["view_list"][$view_idx][prdimg] = $prd_row['prdimg_R'];
	$_SESSION["view_list"][$view_idx][prdurl] = $_SERVER['PHP_SELF'];
}

// 상품 이미지
if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$prd_row['prdimg_M1'])) $prdimg = "/twcenter/images/noimg_M.gif";
else $prdimg = "/twcenter/data/prdimg/".$prd_row['prdimg_M1'];

$prdimg_S1 = $prd_row['prdimg_S1'];
$prdimg_S2 = $prd_row['prdimg_S2'];
$prdimg_S3 = $prd_row['prdimg_S3'];
$prdimg_S4 = $prd_row['prdimg_S4'];
$prdimg_S5 = $prd_row['prdimg_S5'];

$prdimg_M1 = $prd_row['prdimg_M1'];
$prdimg_M2 = $prd_row['prdimg_M2'];
$prdimg_M3 = $prd_row['prdimg_M3'];
$prdimg_M4 = $prd_row['prdimg_M4'];
$prdimg_M5 = $prd_row['prdimg_M5'];

$info_name1 = $prd_row['info_name1'];
$info_value1 = $prd_row['info_value1'];
$info_name2 = $prd_row['info_name2'];
$info_value2 = $prd_row['info_value2'];
$info_name3 = $prd_row['info_name3'];
$info_value3 = $prd_row['info_value3'];
$info_name4 = $prd_row['info_name4'];
$info_value4 = $prd_row['info_value4'];
$info_name5 = $prd_row['info_name5'];
$info_value5 = $prd_row['info_value5'];
$info_name6 = $prd_row['info_name6'];
$info_value6 = $prd_row['info_value6'];

$opt_use = $prd_row['opt_use'];

$opttitle 	= $prd_row['opttitle'];
$opttitle2 	= $prd_row['opttitle2'];
$opttitle3 	= $prd_row['opttitle3'];
$opttitle4 	= $prd_row['opttitle4'];
$opttitle5 	= $prd_row['opttitle5'];
$opttitle6 	= $prd_row['opttitle6'];
$opttitle7 	= $prd_row['opttitle7'];

$optcode		= $prd_row['optcode'];
$optcode2		= $prd_row['optcode2'];
$optcode3		= $prd_row['optcode3'];
$optcode4		= $prd_row['optcode4'];
$optcode5		= $prd_row['optcode5'];
$optcode6		= $prd_row['optcode6'];
$optcode7		= $prd_row['optcode7'];

$optvalue		= $prd_row['optvalue'];

$sellprice 	= $prd_row['sellprice'];
$strprice 	= $prd_row['strprice'];
$conprice 	= $prd_row['conprice'];
$reserve 		= $prd_row['reserve'];

$coupon_use			= $prd_row['coupon_use'];
$coupon_sdate		= $prd_row['coupon_sdate'];
$coupon_edate		= $prd_row['coupon_edate'];
$coupon_limit		= $prd_row['coupon_limit'];
$coupon_amount	= $prd_row['coupon_amount'];
$coupon_type		= $prd_row['coupon_type'];
$coupon_dis			= $prd_row['coupon_dis'];

$prdcom					= $prd_row['prdcom'];
$origin					= $prd_row['origin'];

$info_use				= $prd_row['info_use'];
$info_name1			= $prd_row['info_name1'];
$info_value1		= $prd_row['info_value1'];
$info_name2			= $prd_row['info_name2'];
$info_value2		= $prd_row['info_value2'];
$info_name3			= $prd_row['info_name3'];
$info_value3		= $prd_row['info_value3'];
$info_name4			= $prd_row['info_name4'];
$info_value4		= $prd_row['info_value4'];
$info_name5			= $prd_row['info_name5'];
$info_value5		= $prd_row['info_value5'];
$info_name6			= $prd_row['info_name6'];
$info_value6		= $prd_row['info_value6'];

// 상품아이콘
if($prd_row['popular'] == "Y") 	$sp_img .= "<img src='/twcenter/images/icon_hit.gif'>&nbsp;";
if($prd_row['recom'] == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_rec.gif'>&nbsp;";
if($prd_row['newc'] == "Y") 			$sp_img .= "<img src='/twcenter/images/icon_new.gif'>&nbsp;";
if($prd_row['best'] == "Y") 			$sp_img .= "<img src='/twcenter/images/icon_best.gif'>&nbsp;";
if($prd_row['sale'] == "Y")			$sp_img .= "<img src='/twcenter/images/icon_sale.gif'>&nbsp;";

if($prd_row['shortage'] == "Y" || (!strcmp($prd_row['shortage'], "S") && $prd_row['stock'] <= 0)) $sp_img .= "<img src='/twcenter/images/icon_not.gif'>&nbsp;";

$prdicon_list = explode("/",$prd_row['prdicon']);
for($ii=0; $ii<count($prdicon_list)-1; $ii++){
  $sp_img .= "<img src='/twcenter/data/prdicon/".$prdicon_list[$ii]."'> ";
}

if(!empty($prd_row['strprice'])) $sellprice = $prd_row['strprice'];
else $sellprice = number_format($prd_row['sellprice'])."";

if($prdimg_max < 12) $prdimg_hide_max = 12;
else $prdimg_hide_max = $prdimg_max;
for($ii = 1; $ii <= $prdimg_hide_max; $ii++) {

	if(!is_file("$_SERVER['DOCUMENT_ROOT']/twcenter/data/prdimg/".${prdimg_S.$ii})){
		${prdimg_hide_start.$ii} = "<!--"; ${prdimg_hide_end.$ii} = "-->";
	}

}

//include $_SERVER['DOCUMENT_ROOT'].'/twcenter/product/category.php';					// 카테고리

if($info_name1 == ""){
	$info_hide_start1 = "<!--"; $info_hide_end1 = "-->";
}
if($info_name2 == ""){
	$info_hide_start2 = "<!--"; $info_hide_end2 = "-->";
}
if($info_name3 == ""){
	$info_hide_start3 = "<!--"; $info_hide_end3 = "-->";
}
if($info_name4 == ""){
	$info_hide_start4 = "<!--"; $info_hide_end4 = "-->";
}
if($info_name5 == ""){
	$info_hide_start5 = "<!--"; $info_hide_end5 = "-->";
}
if($info_name6 == ""){
	$info_hide_start6 = "<!--"; $info_hide_end6 = "-->";
}

$list_btn = "<a href='".$PHP_SELF."?ptype=list&page=".$page."&catcode=".$catcode."&".$param."'><img src='".$skin_dir."/image/btn_list.gif' border='0'></a>";
?>

<script language="javascript">
<!--

var prdimg = "<?=$prd_row['prdimg_L1']?>";

function chgImage(idx){
<?php
for($ii = 1; $ii <= $prdimg_max; $ii++) {
?>
	if(idx == "<?=$ii?>"){
		prdimg = "<?=$prd_row[prdimg_L.$ii]?>";
		document.prdimg.src = "/twcenter/data/prdimg/<?=$prd_row[prdimg_M.$ii]?>";
	}
<?php
}
?>

}

// 상품이미지 팝업
function prdZoom(){

   var url = "/twcenter/product/prd_zoom.php?prdcode=<?=$prdcode?>";
   window.open(url,"prdZoom","width=798,height=600,scrollbars=no");

}
function prevAlert(){
	alert("이전상품이 없습니다.");
}

function nextAlert(){
	alert("다음상품이 없습니다.");
}

// 가격설정
function setSellprice(){

	var tmp_sellprice = document.prdForm.tmp_sellprice.value;
	var opt_price1 = document.prdForm.opt_price1.value;
	var opt_price2 = document.prdForm.opt_price2.value;
	var opt_price3 = document.prdForm.opt_price3.value;

	var tmp_reserve = document.prdForm.tmp_reserve.value;
	var opt_reserve1 = document.prdForm.opt_reserve1.value;
	var opt_reserve2 = document.prdForm.opt_reserve2.value;
	var opt_reserve3 = document.prdForm.opt_reserve3.value;

	if(tmp_sellprice == "") tmp_sellprice = 0;
	if(opt_price1 == "") opt_price1 = 0;
	if(opt_price2 == "") opt_price2 = 0;
	if(opt_price3 == "") opt_price3 = 0;

	if(tmp_reserve == "") tmp_reserve = 0;
	if(opt_reserve1 == "") opt_reserve1 = 0;
	if(opt_reserve2 == "") opt_reserve2 = 0;
	if(opt_reserve3 == "") opt_reserve3 = 0;

	var sellprice = String(eval(tmp_sellprice) + eval(opt_price1) + eval(opt_price2) + eval(opt_price3));
	var reserve = String(eval(tmp_reserve) + eval(opt_reserve1) + eval(opt_reserve2) + eval(opt_reserve3));

	document.getElementById("sellprice").innerHTML = "<span class='price_b'>"+ won_Comma(sellprice) +"원</span>";
	document.getElementById("reserve").innerHTML = won_Comma(reserve) +"원";

	<?php
	if(
	$coupon_use == "Y" &&
	$coupon_sdate <= date('Y-m-d') &&
	$coupon_edate >= date('Y-m-d') &&
	($coupon_limit == "N" || ($coupon_limit == "" && $coupon_amount > 0))
	){
	?>

	sellprice = eval(tmp_sellprice) + eval(opt_price1) + eval(opt_price2) + eval(opt_price3);

	var coupon_dis = document.prdForm.coupon_dis.value;
	var coupon_type = document.prdForm.coupon_type.value;

	var coupon_price;

	if(coupon_type == "%") {
		coupon_dis = coupon_dis/100;
		coupon_price = sellprice - (sellprice*coupon_dis);
	} else {
		coupon_price = sellprice - coupon_dis;
	}

	coupon_price = String(coupon_price);

	//document.getElementById("coupon").innerHTML = ":&nbsp;&nbsp; <font class='coupon'>"+ won_Comma(coupon_price) +"원 &nbsp;<?=number_format($prd_info->coupon_dis)?><?=$prd_info->coupon_type?></font>";

	<?php
	}
	?>

	document.prdForm.tmp_sellprice.value = tmp_sellprice;

}

// 수량 증가
function incAmount(){

	var amount = document.prdForm.amount.value;
	document.prdForm.amount.value = ++amount;
	checkAmount();

}

// 수량 감소
function decAmount(){

   var amount = document.prdForm.amount.value;
	if(amount > 1)
		document.prdForm.amount.value = --amount;
	checkAmount();

}

// 수량체크
function checkAmount(){

	var amount = document.prdForm.amount.value;
	if(!check_Num(amount) || amount < 1){

	document.prdForm.amount.value = "1";

	}else{
   <? if($prd_row['opt_use'] == "Y" && (!empty($prd_row['opttitle']) || !empty($prd_row['opttitle2']))){ ?>
   	if( document.prdForm.amount != null){
   		var selvalue = document.prdForm.optcode.value;
   		var optlist = selvalue.split("^");
	   	if( amount > eval(optlist[3])){
	   		 alert("재고량이 부족합니다. 1544-9701번으로 문의해주시기 바랍니다.");
	   		 document.prdForm.amount.value = "1";
	   		 return false;
	   	}else{
	   		return true;
	   	}
   	}
   <? }else if(!strcmp($prd_row['shortage'], "S")) { ?>
		if( document.prdForm.amount != null){
			if( amount > <?=$prd_row['stock']?>){
				 alert("재고량이 부족합니다. 1544-9701번으로 문의해주시기 바랍니다.");
				 document.prdForm.amount.value = "1";
				 return false;
			}else{
				return true;
			}
		}
   <? } else { ?>

   		return true;

   <? } ?>
	}

}

// 가격변동,품절옵션 체크
function checkOpt01(){

	if(document.prdForm.optcode != null){

		var optval = document.prdForm.optcode.value;
		var optlist = optval.split("^");

		if(optval == ""){

			document.prdForm.opt_price1.value = "";
			document.prdForm.opt_reserve1.value = "";
			setSellprice();

		}else{

			//optlist[0] : 옵션명 optlist[1] : 가격 optlist[2] : 적립금 optlist[3] 재고

			if(optlist[3] == "0"){
				alert('품절된 상품입니다.');
				document.prdForm.optcode[0].selected = true;
				document.prdForm.opt_price1.value = "";
				document.prdForm.opt_reserve1.value = "";
				setSellprice();

				return false;

			// 옵션별 가격 적용
			}else{
				document.prdForm.opt_price1.value = optlist[1];
				document.prdForm.opt_reserve1.value = optlist[2];
				setSellprice();
			}

		}

	}

	return checkAmount();

}

// 가격변동 체크
function checkOpt03(){

	if(document.prdForm.optcode3 != null){

		if(document.prdForm.optcode3.value == ""){

			document.prdForm.opt_price2.value = "";
			document.prdForm.opt_reserve2.value = "";
			setSellprice();

		}else{

			var optval = document.prdForm.optcode3.value;
			var optlist = optval.split("^");

			document.prdForm.opt_price2.value = optlist[1];
			document.prdForm.opt_reserve2.value = optlist[2];
			setSellprice();

		}
	}

}

// 가격변동 체크
function checkOpt04(){

	if(document.prdForm.optcode4 != null){

		if(document.prdForm.optcode4.value == ""){

			document.prdForm.opt_price3.value = "";
			document.prdForm.opt_reserve3.value = "";
			setSellprice();

		}else{

			var optval = document.prdForm.optcode4.value;
			var optlist = optval.split("^");

			document.prdForm.opt_price3.value = optlist[1];
			document.prdForm.opt_reserve3.value = optlist[2];
			setSellprice();

		}
	}

}


// 옵션체크
function checkOption(){

   if( document.prdForm.optcode5 != null){
      if(document.prdForm.optcode5.value == ""){
		 //alert("옵션을 선택하세요");
         document.prdForm.optcode5.focus();
		 //location.href='http://imuz.web2002.kr'+'<?=$current_url?>'+'?ptype=view&prdcode='+'<?=$prdcode?>';
		 location.href='http://imuz.com'+'<?=$current_url?>'+'?ptype=view&prdcode='+'<?=$prdcode?>';
         //return false;
      }
   }
   if( document.prdForm.optcode6 != null){
      if(document.prdForm.optcode6.value == ""){
         alert("옵션을 선택하세요1");
         document.prdForm.optcode6.focus();
		 location.href='http://imuz.com'+'<?=$current_url?>'+'?ptype=view&prdcode='+'<?=$prdcode?>';
         return false;
      }
   }
   if( document.prdForm.optcode7 != null){
      if(document.prdForm.optcode7.value == ""){
         alert("옵션을 선택하세요2");
         document.prdForm.optcode7.focus();
		 location.href='http://imuz.com'+'<?=$current_url?>'+'?ptype=view&prdcode='+'<?=$prdcode?>';
         return false;
      }
   }

   if( document.prdForm.optcode3 != null){
      if(document.prdForm.optcode3.value == ""){
         alert("옵션을 선택하세요3");
         document.prdForm.optcode3.focus();
		 location.href='http://imuz.com'+'<?=$current_url?>'+'?ptype=view&prdcode='+'<?=$prdcode?>';
         return false;
      }
   }
   if( document.prdForm.optcode4 != null){
      if(document.prdForm.optcode4.value == ""){
         alert("옵션을 선택하세요4");
         document.prdForm.optcode4.focus();
		 location.href='http://imuz.com'+'<?=$current_url?>'+'?ptype=view&prdcode='+'<?=$prdcode?>';
         return false;
      }
   }

	if( document.prdForm.optcode != null){
      if(document.prdForm.optcode.value == ""){
         alert("옵션을 선택하세요5");
         document.prdForm.optcode.focus();
		 location.href='http://imuz.com'+'<?=$current_url?>'+'?ptype=view&prdcode='+'<?=$prdcode?>';
         return false;
      }
   }
   if( document.prdForm.optcode2 != null){
      if(document.prdForm.optcode2.value == ""){
       alert("옵션을 선택하세요6");
         document.prdForm.optcode2.focus();
		 location.href='http://imuz.com'+'<?=$current_url?>'+'?ptype=view&prdcode='+'<?=$prdcode?>';
         return false;
      }
   }
   return true;
}

function onLoadEvent() {
       saveBasket('buy');
}
window.onload = onLoadEvent


// 장바구니에 담기
function saveBasket(direct){
  <?
  if($prd_row['shortage'] == "Y" || (!strcmp($prd_row['shortage'], "S") && $prd_row['stock'] <= 0)) echo "alert('죄송합니다. 품절상품 입니다.');";
  else {
  	if(empty($prd_info['basket_url']) || empty($prd_info['order_url'])) {
  		echo "alert('장바구니 또는 주문페이지가 설정되지 않았습니다.');";
  	} else {
  		echo "if(checkOption() && checkOpt01()){ document.prdForm.direct.value = direct; document.prdForm.mode.value='insert'; document.prdForm.submit(); }";
  	}
  }
  ?>
}


// 관심상품 등록
function saveWish(){

	if(checkOption()){
		var frm = document.prdForm;
		frm.mode.value = "my_wish";
		frm.submit();
	}

}
-->
</script>

<?

// 다음이전 상품
$catcode01 = str_replace("00","",substr($catcode,0,2));
$catcode02 = str_replace("00","",substr($catcode,2,2));
$catcode03 = str_replace("00","",substr($catcode,4,2));
$tmp_catcode = $catcode01.$catcode02.$catcode03;
$sql = "select wp.prdcode from wiz_cprelation wc, wiz_product wp where wc.catcode like '$tmp_catcode%' and wc.prdcode = wp.prdcode and wp.showset != 'N' and wp.prdcode > '$prdcode' order by wp.prdcode asc limit 1";
$result = query($sql) or error("sql error");
if($row = sql_fetch_obj($result)) {
	$prev = "<a href='$PHP_SELF?ptype=view&prdcode=$row->prdcode&catcode=$catcode&$param'>이전</a>";
	$prev_prdcode = "$PHP_SELF?ptype=view&prdcode=$row->prdcode&catcode=$catcode&$param";
} else {
	$prev = "<a href=javascript:prevAlert();>이전</a>";
	$prev_prdcode = "javascript:prevAlert();";
}

$sql = "select wc.prdcode from wiz_cprelation wc, wiz_product wp where wc.catcode like '$tmp_catcode%' and wc.prdcode = wp.prdcode and wp.showset != 'N' and wp.prdcode < '$prdcode' order by wp.prdcode desc limit 1";
$result = query($sql) or error("sql error");
if($row = sql_fetch_obj($result)) {
	$next = "<a href='$PHP_SELF?ptype=view&prdcode=$row->prdcode&catcode=$catcode&$param'>다음</a>";
	$next_prdcode = "$PHP_SELF?ptype=view&prdcode=$row->prdcode&catcode=$catcode&$param";
} else {
	$next = "<a href=javascript:nextAlert();>다음</a>";
	$next_prdcode = "javascript:nextAlert();";
}
?>

<!--제품 상세보기 시작-->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	  
	</tr>
	<tr><td height="2" bgcolor="#949494"></td></tr>
	<tr>
	  <td align="center" style="padding:30px 0px;">

    <!-- 실제 컨텐츠 부분 -->
   	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>

          <!-- 상품 간략 설명 -->
          	<table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="40%" align="center">

                <!-- 상품 이미지 -->


                </td>
                <td width="5%"></td>
                <td width="55%" valign="top" style="text-align:left;">

                	<!-- 제품정보 -->
					<form name="prdForm" action="/twcenter/product/prd_save.php" method="post">
					<input type="hidden" name="mode" value="insert">
					<input type="hidden" name="direct" value="">
					<input type="hidden" name="prdcode" value="<?=$prdcode?>">
					<? if(!strcmp($opt_use, "Y") && (!empty($opttitle) || !empty($opttitle2))) { ?>
					<input type="hidden" name="opttitle" value="<?=$opttitle?>">
					<input type="hidden" name="opttitle2" value="<?=$opttitle2?>">
					<? } ?>
					<input type="hidden" name="opttitle3" value="<?=$opttitle3?>">
					<input type="hidden" name="opttitle4" value="<?=$opttitle4?>">
					<input type="hidden" name="opttitle5" value="<?=$opttitle5?>">
					<input type="hidden" name="opttitle6" value="<?=$opttitle6?>">
					<input type="hidden" name="opttitle7" value="<?=$opttitle7?>">

					<input type="hidden" name="tmp_sellprice" value="<?=$prd_row['sellprice']?>">
					<input type="hidden" name="opt_price1" value="">
					<input type="hidden" name="opt_price2" value="">
					<input type="hidden" name="opt_price3" value="">

					<input type="hidden" name="tmp_reserve" value="<?=$reserve?>">
					<input type="hidden" name="opt_reserve1" value="">
					<input type="hidden" name="opt_reserve2" value="">
					<input type="hidden" name="opt_reserve3" value="">
                    <table width="100%" border=0 cellpadding=0 cellspacing=0>
                      <tr>
                      </tr>
                      <tr>
                      <td >

                        <table border=0 cellpadding=5 cellspacing=0 width=90%>
                        	<? if($prd_row['conprice'] > $prd_row['sellprice']){ ?>
                          <tr>
                            <td class="p_tit"></td>
                            <td style="padding-left:20px;"><span style="text-decoration:line-through;"><?=number_format($conprice)?></span></td>
                          </tr>
                          <? } ?>
                          <tr>
                            <td width="25%" class="p_tit"></td>
                            <td id="sellprice" style="padding-left:20px;"><span class="price_b"></span></td>
                          </tr>
													<?php
													if(!empty($wiz_session['id']) && empty($strprice)) {

														$level_info = level_info();
														$level = $level_info[$wiz_session['level']][name];

														$lev_sql = "select * from wiz_level where idx = '$wiz_session['level']'";
														$lev_result = query($lev_sql) or error("sql error");
														$lev_row = sql_fetch_obj($lev_result);

														if($lev_row->discount > 0) {
															if($lev_row->distype == "W") {
																
																$member_price = $lev_row->discount;
															} else {
																$lev_row->distype = "%";
																$member_dis = $lev_row->discount/100;
																$member_price = $prd_row['sellprice']*$member_dis;
															}
													?>
                          <tr>
                            <td class="p_tit"></td>
                            <td style="padding-left:20px;"> &nbsp;<?=number_format($lev_row->discount)?><?=$lev_row->distype?> [<?=$level?>]</td>
                          </tr>
													<?php
														}
													}
													?>

													<?
													if(
													$coupon_use == "Y" &&
													$coupon_sdate <= date('Y-m-d') &&
													$coupon_edate >= date('Y-m-d') &&
													($coupon_limit == "N" || ($coupon_limit == "" && $coupon_amount > 0))
													&& empty($strprice)
													){
														if($coupon_type == "%"){
															$coupon_dis = $coupon_dis/100;
															$coupon_price = $prd_row['sellprice']*$coupon_dis;
														}else{
															$coupon_price = $coupon_dis;
														}
													?>
													<input type="hidden" name="coupon_dis" value="<?=$prd_row['coupon_dis']?>">
													<input type="hidden" name="coupon_type" value="<?=$prd_row['coupon_type']?>">

                          <tr>
                            <td class="p_tit"></td>
                            <td id="coupon" style="padding-left:20px;">&nbsp;<?=number_format($prd_row['coupon_dis'])?><?=$coupon_type?>&nbsp;<a href="/twcenter/product/coupon_down.php?prdcode=<?=$prdcode?>"><img src="/twcenter/images/coupon_down.gif" border="0"></a></td>
                          </tr>
													<? } ?>

													<? if($oper_info['reserve_use'] == "Y" && empty($strprice)){ ?>
                          <tr>
                            <td class="p_tit"></td>
                            <td id="reserve" style="padding-left:20px;"></td>
                          </tr>
                          <? } ?>
                        </table>

                        </td>
											</tr>
                      <tr>
                        <td style="padding:5px 10px;">

                            <table border=0 cellpadding=5 cellspacing=0 width=90%>
                            	<tr>
                            		<td width="25%"></td>
                            		<td></td>
                            	</tr>
                            	<? if($sp_img != ""){ ?>
                              <tr>

                              </tr>
                              <? } ?>
                              <? if($prdcom != ""){ ?>
                              <tr>
                                <td class="p_tit">제조사</td>
                                <td style="padding-left:20px;"><?=$prdcom?></td>
                              </tr>
                              <? } ?>
                              <? if($origin != ""){ ?>
                              <tr>
                                <td class="p_tit">원산지</td>
                                <td style="padding-left:20px;"><?=$origin?></td>
                              </tr>
                              <? } ?>

														  <?php
														  if(!strcmp($info_use, "Y")) {
														  	for($ii = 1; $ii <= 6; $ii++) {
														  		if(!empty(${"info_name".$ii})) {
														  ?>
                              <tr>
                                <td class="p_tit"><?=${"info_name".$ii}?></td>
                                <td style="padding-left:20px;"><?=${"info_value".$ii}?></td>
                              </tr>
														  <?php
														  		}
														  	}
														  }
														  ?>

															<? if(empty($strprice)) { ?>
                              <tr>
                            	<tr>
                            		<td width="25%"></td>
                            		<td></td>
                            	</tr>
                            	<tr>
                                <td style="padding-left:20px;">

                                  <table border=0 cellpadding=0 cellspacing=0>
                                    <tr>
                                      <td rowspan=3><input type=hidden name=amount value=1 size=2 onChange="checkAmount();" onKeyUp="checkAmount()" class="amout_num">&nbsp;&nbsp;</td>
                                      
                                    </tr>
                                    <tr>
                                      
                                    </tr>
                                  </table>

                                </td>
                              </tr>
                              <? if($opttitle5 != ""){ ?>
                              <tr>
                                <td class="p_tit"><?=$opttitle5?></td>
                                <td style="padding-left:20px;">
																  <select name="optcode5">
																  <option value=""> 선택하세요 </option>
								                  <?
								                  $opt_list = explode(",",$optcode5);
								                	for($ii=0; $ii<count($opt_list); $ii++){
								                	echo "<option value='".$opt_list[$ii]."'>".$opt_list[$ii]."\n";
								                	}
								                  ?>
																  </select>
                                </td>
                              </tr>
                              <? } ?>

                              <? if($opttitle6 != ""){ ?>
                              <tr>
                                <td class="p_tit"><?=$opttitle6?></td>
                                <td style="padding-left:20px;">
																  <select name="optcode6">
																  <option value=""> 선택하세요 </option>
								                  <?
								                  $opt_list = explode(",",$optcode6);
								                	for($ii=0; $ii<count($opt_list); $ii++){
								                	echo "<option value='".$opt_list[$ii]."'>".$opt_list[$ii]."\n";
								                	}
								                  ?>
																  </select>
                                </td>
                              </tr>
                              <? } ?>

                              <? if($opttitle7 != ""){ ?>
                              <tr>
                                <td class="p_tit"><?=$opttitle7?></td>
                                <td style="padding-left:20px;">
																  <select name="optcode7">
																  <option value=""> 선택하세요 </option>
								                  <?
								                  $opt_list = explode(",",$optcode7);
								                	for($ii=0; $ii<count($opt_list); $ii++){
								                	echo "<option value='".$opt_list[$ii]."'>".$opt_list[$ii]."\n";
								                	}
								                  ?>
																  </select>
                                </td>
                              </tr>
                              <? } ?>

                              <? if($opttitle3 != ""){ ?>
                              <tr style="display:none">
                                <td class="p_tit"><?=$opttitle3?></td>
                                <td style="padding-left:20px;">
								
																  <select name="optcode3" onChange="checkOpt03()">
																  <option value=""> 선택하세요 </option>
								                  <?
								                  $opt_list = explode("^^",$optcode3);
								                	for($ii=0; $ii<count($opt_list) - 1; $ii++){
								                		list($opt, $price, $reserve) = explode("^", $opt_list[$ii]);

								                		if($price > 0) $price_tmp = " : ".number_format($price)."원 추가";
								                		else $price_tmp = "";

								                		echo "<option value='".$opt."^".$price."^".$reserve."'>".$opt.$price_tmp."\n";
								                	}
								                  ?>
																  </select>
																  
                                </td>
                              </tr>
                              <? } ?>

                              <? if($opttitle4 != ""){ ?>
                              <tr>
                                <td class="p_tit"><?=$opttitle4?></td>
                                <td style="padding-left:20px;">
																  <select name="optcode4" onChange="checkOpt04()">
																  <option value=""> 선택하세요 </option>
								                  <?
								                  $opt_list = explode("^^",$optcode4);
								                	for($ii=0; $ii<count($opt_list) - 1; $ii++){
								                		list($opt, $price, $reserve) = explode("^", $opt_list[$ii]);

								                		if($price > 0) $price_tmp = " : ".number_format($price)."원 추가";
								                		else $price_tmp = "";

								                		echo "<option value='".$opt."^".$price."^".$reserve."'>".$opt.$price_tmp."\n";
								                	}
								                  ?>
																  </select>
                                </td>
                              </tr>
                              <? } ?>

														  <?
														  if($opt_use == "Y" && (!empty($opttitle) || !empty($opttitle2))){
														  	if(!empty($opttitle) && !empty($opttitle2)) $opttitle2 = "/".$opttitle2;
														  ?>
                              <tr>
                                <td class="p_tit"><?=$opttitle?><?=$opttitle2?></td>
                                <td style="padding-left:20px;">
																<?php

																$opt1_arr = explode("^", $optcode);
																$opt2_arr = explode("^", $optcode2);
																$opt_tmp = explode("^^", $optvalue);

																if(count($opt1_arr)-1 < 1) $opt1_cnt = 1;
																else $opt1_cnt = count($opt1_arr) - 1;

																if(count($opt2_arr)-1 < 1) $opt2_cnt = 1;
																else $opt2_cnt = count($opt2_arr) - 1;

																$no = 0;
																$optcode = "";
																for($ii = 0; $ii < $opt1_cnt; $ii++) {
																	for($jj = 0; $jj < $opt2_cnt; $jj++) {
																		list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

																		$optcode[$no][optcode] = $opt1_arr[$ii];
																		if(!empty($opt1_arr[$ii]) && !empty($opt2_arr[$jj])) $optcode[$no][optcode] .= "/";
																		$optcode[$no][optcode] .= $opt2_arr[$jj];
																		$optcode[$no][price] = $price;
																		$optcode[$no][reserve] = $reserve;
																		$optcode[$no][stock] = $stock;
																		$no++;
																	}
																}

																?>
																  <select name="optcode" onChange="checkOpt01();">
																  <option value="">  </option>
																	<?
																		for($ii=0; $ii<count($optcode); $ii++){

																		$opt_sub_value = $optcode[$ii][optcode]."^".$optcode[$ii][price]."^".$optcode[$ii][reserve]."^".$optcode[$ii][stock];

																		if($optcode[$ii][stock] <= 0) $optcode[$ii][stock] = " [품절]";
																		else $optcode[$ii][stock] = "";

																		if($optcode[$ii][price] > 0) $optcode[$ii][price] = " : ".number_format($optcode[$ii][price])."원 추가  ";
																		else $optcode[$ii][price] = "";

																		$opt_sub_txt = $optcode[$ii][optcode].$optcode[$ii][price].$optcode[$ii][stock];

																		echo "<option value='$opt_sub_value'>$opt_sub_txt\n";
																		}
																	?>
																  </select>
                                </td>
                              </tr>
                              <? } ?>

                             <? } ?>

													  <?
													  /*if($prefer != ""){ ?>
                              <tr>
                                <td class="p_tit"></td>
                                <td style="padding-left:20px;"></td>
                              </tr>
												   	<? }
												   	*/ ?>
                          	</table>

                        </td>
                    	</tr>
                      <tr>

											</tr>
                      <tr>
                        <td style="padding:7px 0;">
                        <table border=0 cellpadding=5 cellspacing=0 width=90%>
                          <tr>
                            <td width="25%"><strong></strong></td>
                            <td style="padding-left:20px;">
					                  	
					                  	<!--img src="/twcenter/images/icon_cyworld.gif" border=0 style="cursor:pointer" onclick="snsTwitter('<?=$prd_info->prdname?>','http://<?=$HTTP_HOST?>/<?=$REQUEST_URI?>');"-->
                  					</td>
                          </tr>

                        </table></td>
                      </tr>
                      <tr>

                      </tr>
                      <tr>
                        <td style="padding:10px 0 0 0">
                          <table border=0 cellpadding=2 cellspacing=0>
                            <tr>
                            	<? if(empty($strprice)) { ?>
                              <td style="padding:4px;"><a href="javascript:saveBasket('buy');"></a></td>
                              <td style="padding:4px;"><a href="javascript:saveBasket('basket');"></a></td>
                              <td style="padding:4px;"><a href="javascript:saveWish();"></a></td>
                              <? } ?>
                              <td style="padding:4px;"></td>
                            </tr>
                          </table>
                        </td>
                      </tr>
               	   </table>
				  </form>
              </td>
            </tr>
          </table>

        </td>
      </tr>
      <tr>
        <td height="52"></td>
      </tr>

      <tr>
        <td colspan="5" ><a name="info">

	        <!-- 상세 정보-->
	        <table width="100%" border="0" cellpadding="0" cellspacing="0">
	          <tr>
	            <td width="168"></td>
	            <? if(!strcmp($oper_info['prdrel_use'], "Y")) { ?><td width="167"><a href="#rel"></a></td><? } ?>
	            <? if(!strcmp($oper_info['qna_usetype'], "Y")) { ?><td width="167"><a href="#qna"></a></td><? } ?>
	            <? if(!strcmp($oper_info['review_usetype'], "Y")) { ?><td width="167"><a href="#review"></a></td><? } ?>
	            <td width="450" style="background:url('/twcenter/product/image/bar_tab_bg.gif') repeat-x;">&nbsp;</td>
	          </tr>
	          <tr>
	            <td colspan="5" height="30"></td>
	          </tr>
	          <tr>
	            <td colspan="5" style="padding:0 5px;"></td>
	          </tr>
	        </table>

        </a></td>
      </tr>

			<!--     구매가이드         -->
			<? //$page_type = "prdview"; include "../inc/page_info.inc"; ?>
			<tr><td colspan="5" align=center valign=top><?=$page_info->content?></td></tr>

      <tr><td colspan="5" height="50"></td></tr>

      <tr>
        <td colspan="5" ><a name="rel">

					<!-- 관련상품 -->


				</a></td>
			</tr>
			<tr>
				<td height="40"></td>
			</tr>

      <tr>
        <td><a name="qna">

					<!-- 상품 QnA -->


        </a></td>
      </tr>

		  <tr>
		    <td><a name="review" >

					<!-- 상품리뷰 -->


		    </a></td>
		  </tr>
		</table>

    <!-- 실제 컨텐츠 끝 -->

    </td>
  </tr>
</table>