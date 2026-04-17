<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/site_info.php';
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/prd_info.php';
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/oper_info.php';
?>
<?
if($_GET['catcode'] != "") $catcode = $_GET['catcode'];

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";
echo "<script language='javascript' src='/twcenter/js/lib.js'></script>";
echo "<script src=\"//developers.kakao.com/sdk/js/kakao.min.js\"></script>";
if($oper_info['nhn_chkout_use'] == "Y"){
echo "<script type=\"text/javascript\" src=\"http://checkout.naver.com/customer/js/checkoutButton2.js\" charset=\"UTF-8\"></script>";
}

$param = "grp=$grp&brand=$brand&orderby=$orderby&searchopt=$searchopt&searchkey=$searchkey";

// 상품정보 가져오기 (이동하지 말것)
$sql = "select *, new as newc from wiz_product wp, wiz_cprelation wc where wp.prdcode='$prdcode' and wc.prdcode = wp.prdcode";
$result = query($sql);
$total = sql_fetch_rows($sql);
$prd_row = sql_fetch_arr($result);

if($prdcode == "" || $total <= 0) error("존재하지 않는 상품입니다.");
if($catcode == "") $catcode = $prd_row['catcode'];

// 상품 조회수 업데이트
$sql = "update wiz_product set viewcnt = viewcnt + 1 where prdcode = '$prdcode'";
query($sql) or error("sql error");

include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/cat_info.php'; 		// 카테고리정보

$shortexp    = nl2br($prd_row['shortexp']);
$content     = $prd_row['content'];
$prdname     = $prd_row['prdname'];
$sns_prdname = strip_tags($prdname);
$sns_content = strip_tags($content);

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

if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$prd_row['prdimg_L1'])) $prdimg_L = "/twcenter/images/noimg_M.gif";
else $prdimg_L = "/twcenter/data/prdimg/".$prd_row['prdimg_L1'];

$prdimg_S1		= $prd_row['prdimg_S1'];
$prdimg_S2		= $prd_row['prdimg_S2'];
$prdimg_S3		= $prd_row['prdimg_S3'];
$prdimg_S4		= $prd_row['prdimg_S4'];
$prdimg_S5		= $prd_row['prdimg_S5'];

$prdimg_M1		= $prd_row['prdimg_M1'];
$prdimg_M2		= $prd_row['prdimg_M2'];
$prdimg_M3		= $prd_row['prdimg_M3'];
$prdimg_M4		= $prd_row['prdimg_M4'];
$prdimg_M5		= $prd_row['prdimg_M5'];

$prdimg_L1		= $prd_row['prdimg_L1'];
$prdimg_L2		= $prd_row['prdimg_L2'];
$prdimg_L3		= $prd_row['prdimg_L3'];
$prdimg_L4		= $prd_row['prdimg_L4'];
$prdimg_L5		= $prd_row['prdimg_L5'];

$info_name1		= $prd_row['info_name1'];
$info_value1	= $prd_row['info_value1'];
$info_name2		= $prd_row['info_name2'];
$info_value2	= $prd_row['info_value2'];
$info_name3		= $prd_row['info_name3'];
$info_value3	= $prd_row['info_value3'];
$info_name4		= $prd_row['info_name4'];
$info_value4	= $prd_row['info_value4'];
$info_name5		= $prd_row['info_name5'];
$info_value5	= $prd_row['info_value5'];
$info_name6		= $prd_row['info_name6'];
$info_value6	= $prd_row['info_value6'];

$opt_use		= $prd_row['opt_use'];

$opttitle		= $prd_row['opttitle'];
$opttitle2		= $prd_row['opttitle2'];
$opttitle3		= $prd_row['opttitle3'];
$opttitle4		= $prd_row['opttitle4'];
$opttitle5		= $prd_row['opttitle5'];
$opttitle6		= $prd_row['opttitle6'];
$opttitle7		= $prd_row['opttitle7'];
$opttitle8		= $prd_row['opttitle8'];
$opttitle9		= $prd_row['opttitle9'];
$opttitle10 	= $prd_row['opttitle10'];
$opttitle11 	= $prd_row['opttitle11'];

$optcode		= $prd_row['optcode'];
$optcode2		= $prd_row['optcode2'];
$optcode3		= $prd_row['optcode3'];
$optcode4		= $prd_row['optcode4'];
$optcode5		= $prd_row['optcode5'];
$optcode6		= $prd_row['optcode6'];
$optcode7		= $prd_row['optcode7'];
$optcode8		= $prd_row['optcode8'];
$optcode9		= $prd_row['optcode9'];
$optcode10		= $prd_row['optcode10'];
$optcode11		= $prd_row['optcode11'];


$optvalue		= $prd_row['optvalue'];

$sellprice		= $prd_row['sellprice'];
$strprice		= $prd_row['strprice'];
$conprice		= $prd_row['conprice'];
$reserve 		= $prd_row['reserve'];

$coupon_use		= $prd_row['coupon_use'];
$coupon_sdate	= $prd_row['coupon_sdate'];
$coupon_edate	= $prd_row['coupon_edate'];
$coupon_limit	= $prd_row['coupon_limit'];
$coupon_amount	= $prd_row['coupon_amount'];
$coupon_type	= $prd_row['coupon_type'];
$coupon_dis		= $prd_row['coupon_dis'];

$prdcom			= $prd_row['prdcom'];
$origin			= $prd_row['origin'];

$info_use		= $prd_row['info_use'];
$info_name1		= $prd_row['info_name1'];
$info_value1	= $prd_row['info_value1'];
$info_name2		= $prd_row['info_name2'];
$info_value2	= $prd_row['info_value2'];
$info_name3		= $prd_row['info_name3'];
$info_value3	= $prd_row['info_value3'];
$info_name4		= $prd_row['info_name4'];
$info_value4	= $prd_row['info_value4'];
$info_name5		= $prd_row['info_name5'];
$info_value5	= $prd_row['info_value5'];
$info_name6		= $prd_row['info_name6'];
$info_value6	= $prd_row['info_value6'];

$prd_stock		= $prd_row['stock'];

// 상품아이콘
if($prd_row['popular'] == "Y") 	$sp_img .= "<img src='/twcenter/images/icon_hit.gif'>&nbsp;";
if($prd_row['recom'] == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_rec.gif'>&nbsp;";
if($prd_row['newc'] == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_new.gif'>&nbsp;";
if($prd_row['best'] == "Y") 		$sp_img .= "<img src='/twcenter/images/icon_best.gif'>&nbsp;";
if($prd_row['sale'] == "Y")		$sp_img .= "<img src='/twcenter/images/icon_sale.gif'>&nbsp;";

if($prd_row['shortage'] == "Y" || (!strcmp($prd_row['shortage'], "S") && $prd_row['stock'] <= 0)) $sp_img .= "<img src='/twcenter/images/icon_not.gif'>&nbsp;";

$prdicon_list = explode("/",$prd_row['prdicon']);
for($ii=0; $ii<count($prdicon_list)-1; $ii++){
  $sp_img .= "<img src='/twcenter/data/prdicon/".$prdicon_list[$ii]."'> ";
}

if(!empty($prd_row['strprice'])) $sellprice = $prd_row['strprice'];
else $sellprice = number_format($prd_row['sellprice'])."원";

if($prdimg_max < 12) $prdimg_hide_max = 12;
else $prdimg_hide_max = $prdimg_max;
for($ii = 1; $ii <= $prdimg_hide_max; $ii++) {

	if(!is_file("$_SERVER['DOCUMENT_ROOT']/twcenter/data/prdimg/".${prdimg_S.$ii})){
		${prdimg_hide_start.$ii} = "<!--"; ${prdimg_hide_end.$ii} = "-->";
	}

}

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
<script type="text/javascript" src="/twcenter/js/simple/jquery.simpleGallery.js"></script>
<script type="text/javascript" src="/twcenter/js/simple/jquery.simpleLens.js"></script>
<script>
$(document).ready(function(){
	$('#ZOOM .simpleLens-thumbnails-container img').simpleGallery({
		loading_image: '/twcenter/product/image/loading.gif'
	});

	$('#ZOOM .simpleLens-big-image').simpleLens({
		loading_image: '/twcenter/product/image/loading.gif'
	});
});
</script>

<script language="javascript">
<!--
var prdimg = "<?=$prd_row['prdimg_L1']?>";

/*뒤로가기버튼 누를경우 select값 초기화*/
$( document ).ready(function() {
	$("#optcode").val("");
})

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
	var opt_price1    = document.prdForm.opt_price1.value;
	var opt_price2    = document.prdForm.opt_price2.value;
	var opt_price3    = document.prdForm.opt_price3.value;
	var opt_price8    = document.prdForm.opt_price8.value;
	var opt_price9    = document.prdForm.opt_price9.value;
	var opt_price10   = document.prdForm.opt_price10.value;
	var opt_price11   = document.prdForm.opt_price11.value;

	var tmp_reserve   = document.prdForm.tmp_reserve.value;
	var opt_reserve1  = document.prdForm.opt_reserve1.value;
	var opt_reserve2  = document.prdForm.opt_reserve2.value;
	var opt_reserve3  = document.prdForm.opt_reserve3.value;

	var opt_reserve8  = document.prdForm.opt_reserve8.value;
	var opt_reserve9  = document.prdForm.opt_reserve9.value;
	var opt_reserve10 = document.prdForm.opt_reserve10.value;
	var opt_reserve11 = document.prdForm.opt_reserve11.value;

	var amount        = document.prdForm.amount.value;

	if(tmp_sellprice == "") tmp_sellprice = 0;
	if(opt_price1    == "") opt_price1    = 0;
	if(opt_price2    == "") opt_price2    = 0;
	if(opt_price3    == "") opt_price3    = 0;

	if(opt_price8    == "") opt_price8    = 0;
	if(opt_price9    == "") opt_price9    = 0;
	if(opt_price10   == "") opt_price10   = 0;
	if(opt_price11   == "") opt_price11   = 0;

	if(tmp_reserve   == "") tmp_reserve   = 0;
	if(opt_reserve1  == "") opt_reserve1  = 0;
	if(opt_reserve2  == "") opt_reserve2  = 0;
	if(opt_reserve3  == "") opt_reserve3  = 0;

	if(opt_reserve8  == "") opt_reserve8  = 0;
	if(opt_reserve9  == "") opt_reserve9  = 0;
	if(opt_reserve10 == "") opt_reserve10 = 0;
	if(opt_reserve11 == "") opt_reserve11 = 0;

	<?
	if($opttitle == "" && $opttitle2 == "" && $opttitle3 == "" && $opttitle4 == "" && $opttitle5 == "" && $opttitle6 == "" && $opttitle7 == "" && $opttitle8 == "" && $opttitle9 == "" && $opttitle10 == "" && $opttitle11 == ""){
	?>
		var sellprice = String(eval(tmp_sellprice) + eval(opt_price1) + eval(opt_price2) + eval(opt_price3) + eval(opt_price8) + eval(opt_price9) + eval(opt_price10) + eval(opt_price11));
		var reserve = String(eval(tmp_reserve) + eval(opt_reserve1) + eval(opt_reserve2) + eval(opt_reserve3) + eval(opt_reserve8) + eval(opt_reserve9) + eval(opt_reserve10) + eval(opt_reserve11));

	<? } ?>

	<?php
	if(
	$coupon_use == "Y" &&
	$coupon_sdate <= date('Y-m-d') &&
	$coupon_edate >= date('Y-m-d') &&
	($coupon_limit == "N" || ($coupon_limit == "" && $coupon_amount > 0))
	){
	?>

	sellprice = eval(tmp_sellprice) + eval(opt_price1) + eval(opt_price2) + eval(opt_price3) + eval(opt_price8) + eval(opt_price9) + eval(opt_price10) + eval(opt_price11);

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

	<?php
	}
	?>

	document.prdForm.tmp_sellprice.value = tmp_sellprice;
	var total_sell_price   = tmp_sellprice * amount;
	var total_sell_reserve = tmp_reserve * amount;
	$("#sellprice_default").html(commaNum(total_sell_price));
	<? if($oper_info['reserve_use'] == "Y" && empty($strprice)){ ?>
		$("#reserve_default").html(commaNum(total_sell_reserve));
	<? } ?>

}

// 수량 증가
function incAmount(){
	var amount = $("#amount").val();
		document.prdForm.amount.value = ++amount;
		checkAmount();
		setSellprice();
}

// 수량 감소
function decAmount(){
	var amount = $("#amount").val();
	if(amount > 1)
		document.prdForm.amount.value = --amount;
		checkAmount();
		setSellprice();
}

function default_amount(){
	var amount = $("#amount").val();
	if(amount == 0 || amount == ''){
		$("#amount").val('1');
	}
	checkAmount();
	setSellprice();
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
			alert("재고량이 부족합니다.");
			document.prdForm.amount.value = "1";
			return false;
		}else{
			return true;
		}
	}
	<? }else if(!strcmp($prd_row['shortage'], "S")) { ?>

	if( document.prdForm.amount != null){
			if( amount > <?=$prd_row['stock']?>){
				alert("재고량이 부족합니다.");
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
		add_option();
	}

	return checkAmount();

}

// 가격변동 체크
function checkOpt03(){

	$("#optcode4, #optcode8, #optcode9, #optcode10, #optcode11, #optcode").val('');
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
		add_option();
	}

}

// 가격변동 체크
function checkOpt04(){

	$("#optcode8, #optcode9, #optcode10, #optcode11, #optcode").val('');
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
		add_option();
	}

}

function checkOpt08(){

	$("#optcode9, #optcode10, #optcode11, #optcode").val('');
	if(document.prdForm.optcode8 != null){

		if(document.prdForm.optcode8.value == ""){

			document.prdForm.opt_price8.value = "";
			document.prdForm.opt_reserve8.value = "";
			setSellprice();

		}else{

			var optval = document.prdForm.optcode8.value;
			var optlist = optval.split("^");

			document.prdForm.opt_price8.value = optlist[1];
			document.prdForm.opt_reserve8.value = optlist[2];
			setSellprice();

		}
		add_option();
	}

}

function checkOpt09(){

	$("#optcode10, #optcode11, #optcode").val('');
	if(document.prdForm.optcode9 != null){

		if(document.prdForm.optcode9.value == ""){

			document.prdForm.opt_price9.value = "";
			document.prdForm.opt_reserve9.value = "";
			setSellprice();

		}else{

			var optval = document.prdForm.optcode9.value;
			var optlist = optval.split("^");

			document.prdForm.opt_price9.value = optlist[1];
			document.prdForm.opt_reserve9.value = optlist[2];
			setSellprice();

		}
		add_option();
	}

}

function checkOpt10(){

	$("#optcode11, #optcode").val('');
	if(document.prdForm.optcode10 != null){

		if(document.prdForm.optcode10.value == ""){

			document.prdForm.opt_price10.value = "";
			document.prdForm.opt_reserve10.value = "";
			setSellprice();

		}else{

			var optval = document.prdForm.optcode10.value;
			var optlist = optval.split("^");

			document.prdForm.opt_price10.value = optlist[1];
			document.prdForm.opt_reserve10.value = optlist[2];
			setSellprice();

		}
		add_option();
	}

}

function checkOpt11(){

	$("#optcode").val('');
	if(document.prdForm.optcode11 != null){

		if(document.prdForm.optcode11.value == ""){

			document.prdForm.opt_price11.value = "";
			document.prdForm.opt_reserve11.value = "";
			setSellprice();

		}else{

			var optval = document.prdForm.optcode11.value;
			var optlist = optval.split("^");

			document.prdForm.opt_price11.value = optlist[1];
			document.prdForm.opt_reserve11.value = optlist[2];
			setSellprice();

		}
		add_option();
	}

}

// 옵션체크
function checkOption(){

	if( document.prdForm.optcode5 != null){
		if(document.prdForm.optcode5.value == ""){
			alert("옵션을 선택하세요111");
			document.prdForm.optcode5.focus();
			return false;
		}
	}
	if( document.prdForm.optcode6 != null){
		if(document.prdForm.optcode6.value == ""){
			alert("옵션을 선택하세요2");
			document.prdForm.optcode6.focus();
			return false;
		}
	}
	if( document.prdForm.optcode7 != null){
		if(document.prdForm.optcode7.value == ""){
			alert("옵션을 선택하세요3");
			document.prdForm.optcode7.focus();
			return false;
		}
	}

	if( document.prdForm.optcode3 != null){
		if(document.prdForm.optcode3.value == ""){
			alert("옵션을 선택하세요4");
			document.prdForm.optcode3.focus();
			return false;
		}
	}
	if( document.prdForm.optcode4 != null){
		if(document.prdForm.optcode4.value == ""){
			alert("옵션을 선택하세요5");
			document.prdForm.optcode4.focus();
			return false;
		}
	}
	if( document.prdForm.optcode8 != null){
		if(document.prdForm.optcode8.value == ""){
			alert("옵션을 선택하세요6");
			document.prdForm.optcode8.focus();
			return false;
		}
	}
	if( document.prdForm.optcode9 != null){
		if(document.prdForm.optcode9.value == ""){
			alert("옵션을 선택하세요7");
			document.prdForm.optcode9.focus();
			return false;
		}
	}
	if( document.prdForm.optcode10 != null){
		if(document.prdForm.optcode10.value == ""){
			alert("옵션을 선택하세요8");
			document.prdForm.optcode10.focus();
			return false;
		}
	}
	if( document.prdForm.optcode11 != null){
		if(document.prdForm.optcode11.value == ""){
			alert("옵션을 선택하세요9");
			document.prdForm.optcode11.focus();
			return false;
		}
	}

	if( document.prdForm.optcode != null){
		if(document.prdForm.optcode.value == ""){
			alert("옵션을 선택하세요10");
			document.prdForm.optcode.focus();
			return false;
		}
	}
	if( document.prdForm.optcode2 != null){
		if(document.prdForm.optcode2.value == ""){
			alert("옵션을 선택하세요11");
			document.prdForm.optcode2.focus();
			return false;
		}
	}
	return true;
}

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

//장바구니에 담기
function ajax_insert(type){

	var basket_cnt = $("#basket_cnt").val();
	var bb_cnt = basket_check();

	if(bb_cnt == "0" || bb_cnt == "") {
		  alert('옵션을 선택해주세요');
    }else{
		for(var i=1;i<=basket_cnt;i++){
			$.ajax({
				type:"post"
				, async: false
				, url:  "/twcenter/product/ajax_basket.php"
				, data: $("#bottom_form"+i).serialize()
				, success: function(html) {
				}
				, error: function(){
					alert("연동페이지를 확인하시기 바랍니다.");
				}
			});
		}
		if(type == "buy"){
			document.location="/<?=$prd_info['order_url']?>";
		}else if(type=="basket"){
			document.location="/<?=$prd_info['basket_url']?>";
		}
	}

}

// 관심상품 등록
function saveWish(){

	if(checkOption()){
		var frm = document.prdForm;
		frm.mode.value = "my_wish";
		frm.submit();
	}

}

// 옵션체크
function newcheckOption(){

	if( document.prdForm.optcode5 != null){
		if(document.prdForm.optcode5.value == ""){
			return false;
		}
	}
	if( document.prdForm.optcode6 != null){
		if(document.prdForm.optcode6.value == ""){
			return false;
		}
	}
	if( document.prdForm.optcode7 != null){
		if(document.prdForm.optcode7.value == ""){
			return false;
		}
	}

	if( document.prdForm.optcode3 != null){
		if(document.prdForm.optcode3.value == ""){
			return false;
		}
	}
	if( document.prdForm.optcode4 != null){
		if(document.prdForm.optcode4.value == ""){
			return false;
		}
	}
	if( document.prdForm.optcode8 != null){
		if(document.prdForm.optcode8.value == ""){
			return false;
		}
	}
	if( document.prdForm.optcode9 != null){
		if(document.prdForm.optcode9.value == ""){
			return false;
		}
	}
	if( document.prdForm.optcode10 != null){
		if(document.prdForm.optcode10.value == ""){
			return false;
		}
	}
	if( document.prdForm.optcode11 != null){
		if(document.prdForm.optcode11.value == ""){
			return false;
		}
	}
	if( document.prdForm.optcode != null){
		if(document.prdForm.optcode.value == ""){
			return false;
		}
	}
	if( document.prdForm.optcode2 != null){
		if(document.prdForm.optcode2.value == ""){
			return false;
		}
	}
	return true;
}

function newcheckOpt01(){

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


function checkOpt05(){

	$("#optcode6, #optcode7, #optcode3, #optcode4, #optcode8, #optcode9, #optcode10, #optcode11, #optcode").val('');
	if($("#optcode5").length > 0){
		if($("#optcode5").val() != ""){
			add_option();
		}
	}
}

function checkOpt06(){

	$("#optcode7, #optcode3, #optcode4, #optcode8, #optcode9, #optcode10, #optcode11, #optcode").val('');
	if($("#optcode6").length > 0){
		if($("#optcode6").val() != ""){
			add_option();
		}
	}

}

function checkOpt07(){

	$("#optcode3, #optcode4, #optcode8, #optcode9, #optcode10, #optcode11, #optcode").val('');
	if($("#optcode7").length > 0){
		if($("#optcode7").val() != ""){
			add_option();
		}
	}

}

function add_option(){

	if(newcheckOption() && newcheckOpt01()){

		var basket_cnt = $("#basket_cnt").val();
		var no = ++basket_cnt;

		var opttitle   = "<?=$prd_row['opttitle']?>";
		var opttitle2  = "<?=$prd_row['opttitle2']?>";
		var opttitle3  = "<?=$prd_row['opttitle3']?>";
		var opttitle4  = "<?=$prd_row['opttitle4']?>";
		var opttitle5  = "<?=$prd_row['opttitle5']?>";
		var opttitle6  = "<?=$prd_row['opttitle6']?>";
		var opttitle7  = "<?=$prd_row['opttitle7']?>";
		var opttitle8  = "<?=$prd_row['opttitle8']?>";
		var opttitle9  = "<?=$prd_row['opttitle9']?>";
		var opttitle10 = "<?=$prd_row['opttitle10']?>";
		var opttitle11 = "<?=$prd_row['opttitle11']?>";

		// 옵션 추가 테이블에 같은 옵션이 있는제 체크
		var duplicate_chk = "N";

		var chk_optcode_value   = "";
		var chk_optcode_value2  = "";
		var chk_optcode_value3  = "";
		var chk_optcode_value4  = "";
		var chk_optcode_value5  = "";
		var chk_optcode_value6  = "";
		var chk_optcode_value7  = "";
		var chk_optcode_value8  = "";
		var chk_optcode_value9  = "";
		var chk_optcode_value10 = "";
		var chk_optcode_value11 = "";

		var opt_stock = "<?=$prd_row['stock']?>";
		var shortage  = "<?=$prd_row['shortage']?>";

		if(shortage=="N"){
			opt_stock = "99999999";
		}

		if($("#basket_cnt").val() >= 1){

			for(var i=1; i<=basket_cnt; i++){

				var duplicate_chk_num = 0;
				var duplicate_chk_num_normal = 0;

				if(opttitle!=""){
					chk_optcode_value       = $("#optcode").val().replace(/ /g, '');
					var basket_op1          = $("#basket_op1_"+i).val();

					if(basket_op1 == chk_optcode_value){
						duplicate_chk_num++;
					}
					duplicate_chk_num_normal++;
				}

				if(opttitle2!=""){
					chk_optcode_value2      = $("#optcode").val().replace(/ /g, '');
					var basket_op2          = $("#basket_op2_"+i).val();
					if(basket_op2 == chk_optcode_value2){
						duplicate_chk_num++;
					}
					duplicate_chk_num_normal++;
				}
				if(opttitle3!=""){
					chk_optcode_value3      = $("#optcode3").val().replace(/ /g, '');
					var basket_op3          = $("#basket_op3_"+i).val();
					if(basket_op3 == chk_optcode_value3){
						duplicate_chk_num++;
					}
					duplicate_chk_num_normal++;
				}
				if(opttitle4!=""){
					chk_optcode_value4      = $("#optcode4").val().replace(/ /g, '');
					var basket_op4          = $("#basket_op4_"+i).val();
					if(basket_op4 == chk_optcode_value4){
						duplicate_chk_num++;
					}
					duplicate_chk_num_normal++;
				}
				if(opttitle5!=""){
					chk_optcode_value5      = $("#optcode5").val().replace(/ /g, '');
					var basket_op5          = $("#basket_op5_"+i).val();
					if(basket_op5 == chk_optcode_value5){
						duplicate_chk_num++;
					}
					duplicate_chk_num_normal++;
				}
				if(opttitle6!=""){
					chk_optcode_value6      = $("#optcode6").val().replace(/ /g, '');
					var basket_op6          = $("#basket_op6_"+i).val();
					if(basket_op6 == chk_optcode_value6){
						duplicate_chk_num++;
					}
					duplicate_chk_num_normal++;
				}
				if(opttitle7!=""){
					chk_optcode_value7      = $("#optcode7").val().replace(/ /g, '');
					var basket_op7          = $("#basket_op7_"+i).val();
					if(basket_op7 == chk_optcode_value7){
						duplicate_chk_num++;
					}
					duplicate_chk_num_normal++;
				}
				if(opttitle8!=""){
					chk_optcode_value8      = $("#optcode8").val().replace(/ /g, '');
					var basket_op8          = $("#basket_op8_"+i).val();
					if(basket_op8 == chk_optcode_value8){
						duplicate_chk_num++;
					}
					duplicate_chk_num_normal++;
				}
				if(opttitle9!=""){
					chk_optcode_value9      = $("#optcode9").val().replace(/ /g, '');
					var basket_op9          = $("#basket_op9_"+i).val();
					if(basket_op9 == chk_optcode_value9){
						duplicate_chk_num++;
					}
					duplicate_chk_num_normal++;
				}
				if(opttitle10!=""){
					chk_optcode_value10      = $("#optcode10").val().replace(/ /g, '');
					var basket_op10          = $("#basket_op10_"+i).val();
					if(basket_op10 == chk_optcode_value10){
						duplicate_chk_num++;
					}
					duplicate_chk_num_normal++;
				}
				if(opttitle11!=""){
					chk_optcode_value11      = $("#optcode11").val().replace(/ /g, '');
					var basket_op11          = $("#basket_op11_"+i).val();
					if(basket_op11 == chk_optcode_value11){
						duplicate_chk_num++;
					}
					duplicate_chk_num_normal++;
				}
				
				if(duplicate_chk_num == duplicate_chk_num_normal){
					duplicate_chk="Y";
				}
			}
		}

		if(duplicate_chk == "N" || $("#basket_cnt").val() == "0"){

			var html     = "";
			var tmp_html = "";
			var html3    = "";

			var price    = "<?=$prd_row['sellprice']?>";
			var reserve  = "<?=$prd_row['reserve']?>";

			html += "<tr id=basket_tr"+no+"><td align=left width='45%'>";

			//opttitle5 , opttitle6, opttitle7 은 일반 옵션
			if(opttitle5!=""){

				var optcode_value5     = $("#optcode5").val();
				var slice_val5         = optcode_value5+"/";
				tmp_html += slice_val5;
				html += "<input type='hidden' id=basket_op5_"+no+" value="+optcode_value5+">";
			}

			if(opttitle6!=""){

				var optcode_value6     = $("#optcode6").val();
				var slice_val6         = optcode_value6+"/";
				tmp_html += slice_val6;
				html += "<input type='hidden' id=basket_op6_"+no+" value="+optcode_value6+">";
			}

			if(opttitle7!=""){

				var optcode_value7     = $("#optcode7").val();
				var slice_val7         = optcode_value7+"/";
				tmp_html += slice_val7;
				html += "<input type='hidden' id=basket_op7_"+no+" value="+optcode_value7+">";
			}

			//opttitle3 , opttitle4 는 가격 추가 옵션
			if(opttitle3!=""){

				var optcode_value3              = $("#optcode3").val().replace(/ /g, '');
				var optcode_value_cut3          = optcode_value3.split("^");
				var optcode_value_cut3_val      = optcode_value_cut3[0]+"/";
				var optcode_val_cut3_hidden     = optcode_value_cut3[0]+"/"+optcode_value_cut3[1];

				tmp_html += optcode_value_cut3_val;
				html += "<input type='hidden' id=basket_op3_"+no+" value="+optcode_value3+">";

				price = parseInt(price)+ parseInt(optcode_value_cut3[1]);
				reserve = parseInt(reserve)+ parseInt(optcode_value_cut3[2]);
				var opt_price = parseInt(optcode_value_cut3[1]);
				if(optcode_value_cut3[1] > 0){
					alert("a");
					var opt_price_3 = parseInt(optcode_value_cut3[1]);
				} else {
					alert("b");
					var opt_price_3 = 0;
				}
			}

			if(opttitle4!=""){

				var optcode_value4              = $("#optcode4").val().replace(/ /g, '');
				var optcode_value_cut4          = optcode_value4.split("^");
				var optcode_value_cut4_val      = optcode_value_cut4[0]+"/";
				var optcode_val_cut4_hidden     = optcode_value_cut4[0]+"/"+optcode_value_cut4[1];

				tmp_html += optcode_value_cut4_val;
				html += "<input type='hidden' id=basket_op4_"+no+" value="+optcode_value4+">";

				price = parseInt(price)+ parseInt(optcode_value_cut4[1]);
				reserve = parseInt(reserve)+ parseInt(optcode_value_cut4[2]);
				var opt_price = parseInt(optcode_value_cut4[1]);
				if(optcode_value_cut4[1] > 0){
					var opt_price_4 = parseInt(optcode_value_cut4[1]);
				} else {
					var opt_price_4 = 0;
				}
			}

			if(opttitle8!=""){
 
				var optcode_value8              = $("#optcode8").val().replace(/ /g, '');
				var optcode_value_cut8          = optcode_value8.split("^");
				var optcode_value_cut8_val      = optcode_value_cut8[0]+"/";
				var optcode_val_cut8_hidden     = optcode_value_cut8[0]+"/"+optcode_value_cut8[1];

				tmp_html += optcode_value_cut8_val;
				html += "<input type='hidden' id=basket_op8_"+no+" value="+optcode_value8+">";

				price = parseInt(price)+ parseInt(optcode_value_cut8[1]);
				reserve = parseInt(reserve)+ parseInt(optcode_value_cut8[2]);
				var opt_price = parseInt(optcode_value_cut8[1]);
				if(optcode_value_cut8[1] > 0){
					var opt_price_8 = parseInt(optcode_value_cut8[1]);
				} else {
					var opt_price_8 = 0;
				}
			}

			if(opttitle9!=""){

				var optcode_value9              = $("#optcode9").val().replace(/ /g, '');
				var optcode_value_cut9          = optcode_value9.split("^");
				var optcode_value_cut9_val      = optcode_value_cut9[0]+"/";
				var optcode_val_cut9_hidden     = optcode_value_cut9[0]+"/"+optcode_value_cut9[1];

				tmp_html += optcode_value_cut9_val;
				html += "<input type='hidden' id=basket_op9_"+no+" value="+optcode_value9+">";

				price = parseInt(price)+ parseInt(optcode_value_cut9[1]);
				reserve = parseInt(reserve)+ parseInt(optcode_value_cut9[2]);
				var opt_price = parseInt(optcode_value_cut9[1]);
				if(optcode_value_cut9[1] > 0){
					var opt_price_9 = parseInt(optcode_value_cut9[1]);
				} else {
					var opt_price_9 = 0;
				}
			}

			if(opttitle10!=""){

				var optcode_value10              = $("#optcode10").val().replace(/ /g, '');
				var optcode_value_cut10          = optcode_value10.split("^");
				var optcode_value_cut10_val      = optcode_value_cut10[0]+"/";
				var optcode_val_cut10_hidden     = optcode_value_cut10[0]+"/"+optcode_value_cut10[1];

				tmp_html += optcode_value_cut10_val;
				html += "<input type='hidden' id=basket_op10_"+no+" value="+optcode_value10+">";

				price = parseInt(price)+ parseInt(optcode_value_cut10[1]);
				reserve = parseInt(reserve)+ parseInt(optcode_value_cut10[2]);
				var opt_price = parseInt(optcode_value_cut10[1]);
				if(optcode_value_cut10[1] > 0){
					var opt_price_10 = parseInt(optcode_value_cut10[1]);
				} else {
					var opt_price_10 = 0;
				}
			}

			if(opttitle11!=""){

				var optcode_value11              = $("#optcode11").val().replace(/ /g, '');
				var optcode_value_cut11          = optcode_value11.split("^");
				var optcode_value_cut11_val      = optcode_value_cut11[0]+"/";
				var optcode_val_cut11_hidden     = optcode_value_cut11[0]+"/"+optcode_value_cut11[1];

				tmp_html += optcode_value_cut11_val;
				html += "<input type='hidden' id=basket_op11_"+no+" value="+optcode_value11+">";

				price = parseInt(price)+ parseInt(optcode_value_cut11[1]);
				reserve = parseInt(reserve)+ parseInt(optcode_value_cut11[2]);
				var opt_price = parseInt(optcode_value_cut11[1]);
				if(optcode_value_cut11[1] > 0){
					var opt_price_11 = parseInt(optcode_value_cut11[1]);
				} else {
					var opt_price_11 = 0;
				}

			}

			//opttitle , opttitle2 는 가격 추가&재고 옵션
			if(opttitle != ""){
				var optcode_value1           = $("#optcode").val().replace(/ /g, '');
				var optcode_value_cut1       = optcode_value1.split("^");
				var optcode_value_cut1_val   = optcode_value_cut1[0]+"/";

				tmp_html += optcode_value_cut1_val;
				html += "<input type='hidden' id=basket_op1_"+no+" value="+optcode_value1+">";

				price = parseInt(price)+ parseInt(optcode_value_cut1[1]);
				reserve = parseInt(reserve)+ parseInt(optcode_value_cut1[2]);

				opt_stock = parseInt(optcode_value_cut1[3]);
				var opt_price = parseInt(optcode_value_cut1[1]);
				var opt_price_optcode = parseInt(optcode_value_cut1[1]);
				if(optcode_value_cut1[1] > 0){
					var opt_price_optcode = parseInt(optcode_value_cut1[1]);
				} else {
					var opt_price_optcode = 0;
				}

			}

			if(opttitle2!=""){

				var optcode_value2           = $("#optcode").val().replace(/ /g, '');
				var optcode_value_cut2       = optcode_value2.split("^");
				var optcode_value_cut2_val   = optcode_value_cut2[1]+"/";

				tmp_html += optcode_value_cut2_val;
				html += "<input type='hidden' id=basket_op2_"+no+" value="+optcode_value2+">";
			}

			if(opttitle3  != "") opt_price_3       = opt_price_3;       else opt_price_3       = 0;
			if(opttitle4  != "") opt_price_4       = opt_price_4;       else opt_price_4       = 0;
			if(opttitle8  != "") opt_price_8       = opt_price_8;       else opt_price_8       = 0;
			if(opttitle9  != "") opt_price_9       = opt_price_9;       else opt_price_9       = 0;
			if(opttitle10 != "") opt_price_10      = opt_price_10;      else opt_price_10      = 0;
			if(opttitle11 != "") opt_price_11      = opt_price_11;      else opt_price_11      = 0;
			if(opttitle   != "") opt_price_optcode = opt_price_optcode; else opt_price_optcode = 0;

			var total_optprice = opt_price_3 + opt_price_4 + opt_price_8 + opt_price_9 + opt_price_10 + opt_price_11 + opt_price_optcode;

			html += "<input type='hidden' id=d_optprice"+no+" value="+total_optprice+">";

			tmp_html = tmp_html.slice(0,-1);
			html += tmp_html;

			html += "<td align=center width='10%'>";
			html += "<div style='float:left;'>";
			html += "<input name='amount_sel_"+no+"' id='amount_sel_"+no+"'type='text' size='3' value='1' class='input' onkeyup='inputAmount("+no+")'>";
			html += "</div>";

			html += "<div class='amount_arrow' >";
			html += "<img src='/twcenter/product/image/but_vol_up.gif' onclick='javascript:upAmount("+no+");' style='cursor:pointer'><br>";
			html += "<img src='/twcenter/product/image/but_vol_down.gif' onclick='javascript:downAmount("+no+");' style='cursor:pointer'>";
			html += "</div>";
			html += "</td>";

			html += "<td align=right width='15%'>";
			html += "<span id='span_price_"+no+"'>"+commaNum(total_optprice)+"</span> 원";
			html += "</td>";

			<? if($oper_info['reserve_use'] == "Y" && empty($strprice)){ ?>
			html += "<td align=right>";
			html += "<span id='span_reserve_"+no+"'>"+commaNum(reserve)+"</span> 원";
			html += "</td>";
			<? } ?>

			html += "<td align=center width='15%'>";
			html += "<div style='clear:both'>";
			html += "<img src='/twcenter/product/image/delete_s.gif' onclick='trremove("+no+")' style='cursor:pointer;'/>";
			html += "</div>";
			html += "</td>";

			html += "<input type=\"hidden\"  name='opt_stock_sel_"+no+"' id='opt_stock_sel_"+no+"'type='text' size='5' value='"+opt_stock+"' class='input'>";

			html += "</tr>";



			html3 += "<tr id=\"form_tr"+no+"\">";
			html3 += "<td><form name=\"bottom_form"+no+"\" id=\"bottom_form"+no+"\">";

			html3 += "<input type=\"hidden\" name=\"prdcode\"				id=\"prdcode"+no+"\"		value=\"<?=$prdcode?>\">	";

			if(opttitle!=""){
				html3 += "<input type=\"hidden\" name=\"opttitle_opt\"		id=\"opttitle_"+no+"\"		value=\""+opttitle+"\">	";
				html3 += "<input type=\"hidden\" name=\"optcode_opt\"		id=\"optcode_"+no+"\"		value=\""+optcode_value_cut1[0]+"\">	";
			}
			if(opttitle2!=""){
				html3 += "<input type=\"hidden\" name=\"opttitle2_opt\"		id=\"opttitle2_"+no+"\"		value=\""+opttitle2+"\">	";
				html3 += "<input type=\"hidden\" name=\"optcode2_opt\"		id=\"optcode2_"+no+"\"		value=\""+optcode_value_cut2[0]+"\">	";
			}

			if(opttitle3!=""){
				html3 += "<input type=\"hidden\" name=\"opttitle3_opt\"		id=\"opttitle3_"+no+"\"		value=\""+opttitle3+"\">	";
				html3 += "<input type=\"hidden\" name=\"optcode3_opt\"		id=\"optcode3_"+no+"\"		value=\""+optcode_val_cut3_hidden+"\">	";
			}

			if(opttitle4!=""){
				html3 += "<input type=\"hidden\" name=\"opttitle4_opt\"		id=\"opttitle4_"+no+"\"		value=\""+opttitle4+"\">	";
				html3 += "<input type=\"hidden\" name=\"optcode4_opt\"		id=\"optcode4_"+no+"\"		value=\""+optcode_val_cut4_hidden+"\">	";
			}

			if(opttitle5!=""){
				html3 += "<input type=\"hidden\" name=\"opttitle5_opt\"		id=\"opttitle5_"+no+"\"		value=\""+opttitle5+"\">	";
				html3 += "<input type=\"hidden\" name=\"optcode5_opt\"		id=\"optcode5_"+no+"\"		value=\""+optcode_value5+"\">	";
			}

			if(opttitle6!=""){
				html3 += "<input type=\"hidden\" name=\"opttitle6_opt\"		id=\"opttitle6_"+no+"\"		value=\""+opttitle6+"\">	";
				html3 += "<input type=\"hidden\" name=\"optcode6_opt\"		id=\"optcode6_"+no+"\"		value=\""+optcode_value6+"\">	";
			}

			if(opttitle7!=""){
				html3 += "<input type=\"hidden\" name=\"opttitle7_opt\"		id=\"opttitle7_"+no+"\"		value=\""+opttitle7+"\">	";
				html3 += "<input type=\"hidden\" name=\"optcode7_opt\"		id=\"optcode7_"+no+"\"		value=\""+optcode_value7+"\">	";
			}

			if(opttitle8!=""){
				html3 += "<input type=\"hidden\" name=\"opttitle8_opt\"		id=\"opttitle8_"+no+"\"		value=\""+opttitle8+"\">	";
				html3 += "<input type=\"hidden\" name=\"optcode8_opt\"		id=\"optcode8_"+no+"\"		value=\""+optcode_val_cut8_hidden+"\">	";
			}

			if(opttitle9!=""){
				html3 += "<input type=\"hidden\" name=\"opttitle9_opt\"		id=\"opttitle9_"+no+"\"		value=\""+opttitle9+"\">	";
				html3 += "<input type=\"hidden\" name=\"optcode9_opt\"		id=\"optcode9_"+no+"\"		value=\""+optcode_val_cut9_hidden+"\">	";
			}

			if(opttitle10!=""){
				html3 += "<input type=\"hidden\" name=\"opttitle10_opt\"	id=\"opttitle10_"+no+"\"	value=\""+opttitle10+"\">	";
				html3 += "<input type=\"hidden\" name=\"optcode10_opt\"		id=\"optcode10_"+no+"\"		value=\""+optcode_val_cut10_hidden+"\">	";
			}

			if(opttitle11!=""){
				html3 += "<input type=\"hidden\" name=\"opttitle11_opt\"	id=\"opttitle11_"+no+"\"	value=\""+opttitle11+"\">	";
				html3 += "<input type=\"hidden\" name=\"optcode11_opt\"		id=\"optcode11_"+no+"\"		value=\""+optcode_val_cut11_hidden+"\">	";
			}

			html3 += "<input type=\"hidden\" name=\"tmp_sellprice_opt\"		id=\"tmp_sellprice"+no+"\"	value=\""+price+"\">";
			html3 += "<input type=\"hidden\" name=\"tmp_reserve_opt\"		id=\"tmp_reserve"+no+"\"	value=\""+reserve+"\">";
			html3 += "<input type=\"hidden\" name=\"amount_opt\"			id=\"amount2_sel_"+no+"\"	value=\"1\">";
			html3 += "</td></tr></form>";

			$("#basket_table").append(html);
			$("#form_table").append(html3);
			$("#basket_cnt").val(no);
			total_price();


		} else {
			alert("이미 추가된 상품입니다.")
		}

	}
}

$("body").click(function(e){

	var tr_cnt = $("#form_table tr").length;

	for(var i=1; i<=tr_cnt; i++){
		var basket_id = "";
		basket_id = "#amount_sel_"+i;
		if (!$(basket_id).is(e.target)) {
			if($(basket_id).length>0){
				key_check(i);
			//	upAmount(i);
			//	downAmount(i);
			}
		}
	}

});

function key_check(no){
	var tt = "t";
	var ss = "f";
	var id = "amount_sel_"+no;

	var id_ch = document.getElementById(id).value;
	if(id_ch>1){
		return tt;
	}else{
		 document.getElementById(id).value = 1;
		 return ss;
	}
}

//TR 삭제
function trremove(no,idx){
	var rm_id_f = "#form_tr"+no;
	var rm_id_b = "#basket_tr"+no;
	$(rm_id_f).remove();
	$(rm_id_b).remove();

	total_price();
}

//수량 1 증가
function upAmount(no){
	var amount_sel_idx = "#amount_sel_"+no;
	var amount_sel_idx2 = "#amount2_sel_"+no;
	var amount_sel_value = $(amount_sel_idx).val();

	amount_sel_value = ++amount_sel_value;
	$(amount_sel_idx).val(amount_sel_value);
	$(amount_sel_idx2).val(amount_sel_value);

	numCheckAmount(no);

}

//수량 1 감소
function downAmount(no){
	var amount_sel_idx = "#amount_sel_"+no;
	var amount_sel_idx2 = "#amount2_sel_"+no;
	var amount_sel_value = $(amount_sel_idx).val();

	if(amount_sel_value>1){
	amount_sel_value = --amount_sel_value;
	}
	$(amount_sel_idx).val(amount_sel_value);
	$(amount_sel_idx2).val(amount_sel_value);

	numCheckAmount(no);

}

//수량 직접입력
function inputAmount(no){
	var opt_stock    = "#opt_stock_sel_"+no;
	var amount_sel_idx = "#amount_sel_"+no;
	var amount_sel_value = $(amount_sel_idx).val();

	if(parseInt($(opt_stock).val()) < parseInt($(amount_sel_idx).val())){
		alert("구매수량이 재고량보다 많습니다");
		var opt_stock_value = parseInt($(opt_stock).val());
		$(amount_sel_idx).val(opt_stock_value);
	}

	numCheckAmount(no);

}

//구매수량 체크
function numCheckAmount(no){

	var opt_stock    = "#opt_stock_sel_"+no;

	var amount_sel_idx = "#amount_sel_"+no;
	var amount_sel_idx2 = "#amount2_sel_"+no;

	if(parseInt($(opt_stock).val()) < parseInt($(amount_sel_idx).val())){	//재고량보다 주문수량이 많은경우
		alert("구매수량이 재고량보다 많습니다");
		var opt_stock_value = parseInt($(opt_stock).val());
		$(amount_sel_idx).val(opt_stock_value);
		$(amount_sel_idx2).val(opt_stock_value);
	}

	total_price();

}


//총 판매 가격 변경
function total_price(){
	var i =0;
	var total_price   = 0;
	var total_reserve = 0;
	var tr_cnt = $("#form_table tr").length;

	for(i=1;i<=tr_cnt;i++){
		var tmp_sellprice2 = "#tmp_sellprice"+i;
		var tmp_reserve2   = "#tmp_reserve"+i;
		var amount_sel_idx2 = "#amount2_sel_"+i;

		var price       = 0;
		var reserve     = 0;
		var amount      = 0;
		var d_optprice  = 0;

		if ($(tmp_sellprice2).length > 0) {
			price      = $(tmp_sellprice2).val();
			d_optprice = $("#d_optprice"+i).val();

			reserve = $(tmp_reserve2).val();
			amount  = $(amount_sel_idx2).val();
			total_price += (price*amount);
			total_reserve += (reserve*amount);
			$("#span_price_"+i).html(commaNum(d_optprice*amount));
			$("#span_reserve_"+i).html(commaNum(reserve*amount));
		}

	}
	$("#total_price_h").html(commaNum(total_price));
}

function commaNum(num) {
	var len, point, str;

	num = num + "";
	point = num.length % 3
	len = num.length;

	str = num.substring(0, point);
	while (point < len) {
		if (str != "") str += ",";
		str += num.substring(point, point + 3);
		point += 3;
	}
	return str;
}

//선택 상품 갯수 확인
function basket_check(){
	var id = "#basket_tr";
	var tr_cnt = $("#form_table tr").length;
	var count = "";

	for(var i=1;i<=tr_cnt;i++){
		var id_check = id+i;
		if ($(id_check).length > 0) {
			count++;
		}
	}
	return count;
}

//function sendMail(idx,code,prev){
//	var url = "/twcenter/bbs/mail_chk.php?idx="+idx+"&code="+code+"&prev="+prev;
//	window.open(url, "mail", "height=280, width=600, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no");
//}

function sendSns(sns, url, txt)
{
	var o;
	var _url = encodeURIComponent(url);
	var _txt = encodeURIComponent(txt);
	var _br  = encodeURIComponent('\r\n');

	switch(sns)
	{
		case 'facebook':
			o = {
				method:'popup',
				url:'http://www.facebook.com/sharer/sharer.php?u=' + _url
			};
			break;

		case 'twitter':
			o = {
				method:'popup',
				url:'http://twitter.com/intent/tweet?text=' + _txt + '&url=' + _url
			};
			break;

		case 'me2day':
			o = {
				method:'popup',
				url:'http://me2day.net/posts/new?new_post[body]=' + _txt + _br + _url + '&new_post[tags]=epiloum'
			};
			break;

		default:
			alert('지원하지 않는 SNS입니다.');
			return false;
	}

	switch(o.method)
	{
		case 'popup':
			window.open(o.url);
			break;

		case 'web2app':
			if(navigator.userAgent.match(/android/i))
			{
				// Android
				setTimeout(function(){ location.href = 'intent://' + o.param + '#Intent;' + o.g_proto + ';end'}, 100);
			}
			else if(navigator.userAgent.match(/(iphone)|(ipod)|(ipad)/i))
			{
				// Apple
				setTimeout(function(){ location.href = o.a_store; }, 200);
				setTimeout(function(){ location.href = o.a_proto + o.param }, 100);
			}
			else
			{
				alert('이 기능은 모바일에서만 사용할 수 있습니다.');
			}
			break;
	}
}

// 사용할 앱의 JavaScript 키를 설정
Kakao.init('<?=$oper_info['kakao_appid']?>');
function sendLink(type) {

	var _url = 'http://<?=$HTTP_HOST?><?=$REQUEST_URI?>';
	var _txt = encodeURIComponent('<?=$sns_prdname?>');

	switch(type)
	{

		case 'kakaotalk':

			if(navigator.userAgent.match(/android/i) || navigator.userAgent.match(/(iphone)|(ipod)|(ipad)/i)){

				Kakao.Link.sendTalkLink({
					label: '<?=$sns_prdname?>',
					image: {
						src: 'http://<?=$HTTP_HOST?>/twcenter/data/prdimg/<?=$prdimg_S1?>',
						width: '81',
						height: '81'
					},
					webButton: {
						text: '링크연동',
						url: _url 
						 // 앱설정의 웹 플랫폼에 등록한 도메인 URL.
					}
				});

			} else {
				alert('이 기능은 모바일에서만 사용할 수 있습니다.');
			}


		break;

		case 'kakaostory':

			if(navigator.userAgent.match(/android/i) || navigator.userAgent.match(/(iphone)|(ipod)|(ipad)/i)){

				Kakao.Story.open({
					url: _url,
					text: _txt,
					urlInfo: {
						title: '<?=$sns_prdname?>',
						images: ['http://<?=$HTTP_HOST?>/twcenter/data/prdimg/<?=$prdimg_S1?>']
					}
				});

			} else {
				alert('이 기능은 모바일에서만 사용할 수 있습니다.');
			}

		break;

	}
}
<?
if($oper_info['nhn_chkout_use'] == "Y"){
?>
function buy_nc(url) {

	var check = false;
	if(checkOption() && checkOpt01()){
		check = true;
	}
	if ( check ) {
		//네이버 체크아웃으로 주문 정보를 등록하는 가맹점 페이지로 이동.
		//해당 페이지에서 주문 정보 등록 후 네이버 체크아웃 주문서 페이지로 이동.
		//location.href=url;
		window.open("","CheckOutOrder");

		var frm = document.prdForm;

		frm.direct.value = "checkout";
		frm.target = "CheckOutOrder";
		frm.submit();
	}
	return false;
}
function wishlist_nc(url) {
	// 네이버 체크아웃으로 찜 정보를 등록하는 가맹점 페이지 팝업 창 생성.
	// 해당 페이지에서 찜 정보 등록 후 네이버 체크아웃 찜 페이지로 이동.
	window.open(url,"","scrollbars=yes,width=400,height=267");
	return false;
}
function not_buy_nc() {
	alert("죄송합니다. NAVER Checkout으로 구매가 불가한 상품입니다.");
	//return false;
}
<? } ?>
-->
</script>
<?

// 다음이전 상품
$catcode01 = str_replace("00","",substr($catcode,0,2));
$catcode02 = str_replace("00","",substr($catcode,2,2));
$catcode03 = str_replace("00","",substr($catcode,4,2));
$catcode04 = str_replace("00","",substr($catcode,6,2));

$tmp_catcode = $catcode01.$catcode02.$catcode03.$catcode04;
$sql = "select wp.prdcode from wiz_cprelation wc, wiz_product wp where wc.catcode like '$tmp_catcode%' and wc.prdcode = wp.prdcode and wp.showset != 'N' and wp.prdcode > '$prdcode' order by wp.prdcode asc limit 1";
$result = query($sql);
if($row = sql_fetch_obj($result)) {
	$prev = "<a href='$PHP_SELF?ptype=view&prdcode=$row->prdcode&catcode=$catcode&$param'>이전</a>";
	$prev_prdcode = "$PHP_SELF?ptype=view&prdcode=$row->prdcode&catcode=$catcode&$param";
} else {
	$prev = "<a href=javascript:prevAlert();>이전</a>";
	$prev_prdcode = "javascript:prevAlert();";
}

$sql = "select wc.prdcode from wiz_cprelation wc, wiz_product wp where wc.catcode like '$tmp_catcode%' and wc.prdcode = wp.prdcode and wp.showset != 'N' and wp.prdcode < '$prdcode' order by wp.prdcode desc limit 1";
$result = query($sql);
if($row = sql_fetch_obj($result)) {
	$next = "<a href='$PHP_SELF?ptype=view&prdcode=$row->prdcode&catcode=$catcode&$param'>다음</a>";
	$next_prdcode = "$PHP_SELF?ptype=view&prdcode=$row->prdcode&catcode=$catcode&$param";
} else {
	$next = "<a href=javascript:nextAlert();>다음</a>";
	$next_prdcode = "javascript:nextAlert();";
}

$sns_go_Link  = $HTTP_HOST.$REQUEST_URI;
$facebook_btn = "<a href=\"javascript:sendSns('facebook','http://$sns_go_Link','$sns_prdname');\"><img src='/twcenter/product/image/facebook_icon.gif' border='0' title='To FaceBook'></a>";
$twitter_btn  = "<a href=\"javascript:sendSns('twitter','http://$sns_go_Link','$sns_prdname');\"><img src='/twcenter/product/image/twitter_icon.gif' border='0' title='To Twitter'></a>";
$katalk_btn   = "<a href=\"javascript:sendLink('kakaotalk');\"><img src='/twcenter/product/image/kakao_icon.gif' border='0' title='To KakaoTalk'></a>";
$kastory_btn  = "<a href=\"javascript:sendLink('kakaostory');\"><img src='/twcenter/product/image/kakaostory_icon.gif' border='0' title='To KakaoStory'></a>";

?>

<!--제품 상세보기 시작-->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="35" align="right" class="here">HOME >&nbsp;<strong>제품 상세보기</strong></td>
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
								<td width="45%" align="center">
								<!-- 상품 이미지 -->
								<style>
								.prdimg img{max-height:450px;}
								</style>
								<?php

								if(!strcmp($oper_info['chk_readglass'], "Y")) {
								?>
									<div class="simpleLens-gallery-container" id="ZOOM">
										<div class="simpleLens-container">
											<div class="simpleLens-big-image-container" style="border:1px solid #cdcdcd; width:450px; height:450px;">
												<a class="simpleLens-lens-image" data-lens-image="<?=$prdimg_L?>">
													<img src="<?=$prdimg?>" class="simpleLens-big-image">
												</a>
											</div>
										</div>
										<div>&nbsp;</div>
										<div>
											<a href="<?=$prev_prdcode?>"><img src="/twcenter/images/but_view_prev.gif" border=0></a>
											<img src="/twcenter/images/but_view_zoom.gif" border=0 onClick="prdZoom();" style="cursor:pointer">
											<a href="<?=$next_prdcode?>"><img src="/twcenter/images/but_view_next.gif" border=0></a>
										</div>
										<div class="simpleLens-thumbnails-container">

											<? $imgpath = $_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg"; ?>
											<?php
											for($ii = 1; $ii <= 5; $ii++) {
												if(@file($imgpath."/".${"prdimg_L".$ii})){
											?>

											<a href="#" class="simpleLens-thumbnail-wrapper" data-lens-image="/twcenter/data/prdimg/<?=${"prdimg_L".$ii}?>"
														 data-big-image="/twcenter/data/prdimg/<?=${"prdimg_L".$ii}?>">
												<table width=50 height=50 cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd">
													<tr>
														<td>
															<img src="/twcenter/data/prdimg/<?=${"prdimg_S".$ii}?>">
														</td>
													</tr>
												</table></a>
											<?
												}
											}
											?>
										</div>
									</div>
									<!-- <div class="targetarea prdimg" style="border:1px solid #cdcdcd; ">
										<img id="multizoom1" alt="zoomable" title="" src="<?=$prdimg?>" width=350 height=350 >
									</div>
										<div>&nbsp;</div>
										<div>
											<a href="<?=$prev_prdcode?>"><img src="/twcenter/images/but_view_prev.gif" border=0></a>
											<img src="/twcenter/images/but_view_zoom.gif" border=0 onClick="prdZoom();" style="cursor:pointer">
											<a href="<?=$next_prdcode?>"><img src="/twcenter/images/but_view_next.gif" border=0></a>
										</div>
									<div class="multizoom1 thumbs">
									<? $imgpath = $_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg"; ?>
									<?php
									for($ii = 1; $ii <= 5; $ii++) {
										if(@file($imgpath."/".${"prdimg_L".$ii})){
									?>
										<a href="#" data-large="/twcenter/data/prdimg/<?=${"prdimg_L".$ii}?>"><img src="/twcenter/data/prdimg/<?=${"prdimg_S".$ii}?>" title=""/></a>
									<?
										}
									}
									?>
									</div> -->

								<? } else { ?>
									<table border=0 cellpadding=0 cellspacing=0>
										<tr>
											<td align=center style="border:1px solid #cdcdcd; width:450px; height:450px;" class="prdimg"><img src="<?=$prdimg?>" name="prdimg"></td>
										</tr>
										<tr>
											<td align=center style="padding:10px 0px;">
												<table border=0 cellpadding=0 cellspacing=0>
													<tr>
														<td><a href="<?=$prev_prdcode?>"><img src="/twcenter/images/but_view_prev.gif" border=0></a></td>
														<td><img src="/twcenter/images/but_view_zoom.gif" border=0 onClick="prdZoom();" style="cursor:pointer"></td>
														<td><a href="<?=$next_prdcode?>"><img src="/twcenter/images/but_view_next.gif" border=0></a></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td align=center>
												<table border=0 cellpadding=1 cellspacing=0>
													<tr>
													<? $imgpath = $_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg"; ?>
													<?php
													for($ii = 1; $ii <= 5; $ii++) {
														if(@file($imgpath."/".${"prdimg_S".$ii})){
													?>
														<td>
															<table width=50 height=50 cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd">
																<tr>
																	<td align=center><img src="/twcenter/data/prdimg/<?=${"prdimg_S".$ii}?>" onMouseOver="document.prdimg.src='/twcenter/data/prdimg/<?=${"prdimg_M".$ii}?>'"></td>
																</tr>
															</table>
														</td>
													<?php
														}
													}
													?>
													</tr>
												</table>
											</td>
										</tr>
									</table>

								<? } ?>
								</td>
								<td width="5%"></td>
								<td width="50%" valign="top" style="text-align:left;">

								<!-- 제품정보 -->
								<form name="prdForm" action="/twcenter/product/prd_save.php" method="post">
								<input type="hidden" name="mode"                             value="insert">
								<input type="hidden" name="direct"                           value="">
								<input type="hidden" name="prdcode"                          value="<?=$prdcode?>">
								<? if(!strcmp($opt_use, "Y") && (!empty($opttitle) || !empty($opttitle2))) { ?>
								<input type="hidden" name="opttitle"      id="opttitle"      value="<?=$opttitle?>">
								<input type="hidden" name="opttitle2"     id="opttitle2"     value="<?=$opttitle2?>">
								<? } ?>
								<input type="hidden" name="opttitle3"     id="opttitle3"     value="<?=$opttitle3?>">
								<input type="hidden" name="opttitle4"     id="opttitle4"     value="<?=$opttitle4?>">
								<input type="hidden" name="opttitle5"     id="opttitle5"     value="<?=$opttitle5?>">
								<input type="hidden" name="opttitle6"     id="opttitle6"     value="<?=$opttitle6?>">
								<input type="hidden" name="opttitle7"     id="opttitle7"     value="<?=$opttitle7?>">

								<input type="hidden" name="opttitle8"     id="opttitle8"     value="<?=$opttitle8?>">
								<input type="hidden" name="opttitle9"     id="opttitle9"     value="<?=$opttitle9?>">
								<input type="hidden" name="opttitle10"    id="opttitle10"    value="<?=$opttitle10?>">
								<input type="hidden" name="opttitle11"    id="opttitle11"    value="<?=$opttitle11?>">

								<input type="hidden" name="tmp_sellprice" id="tmp_sellprice" value="<?=$prd_row['sellprice']?>">
								<input type="hidden" name="opt_price1"    id="opt_price1"    value="">
								<input type="hidden" name="opt_price2"    id="opt_price2"    value="">
								<input type="hidden" name="opt_price3"    id="opt_price3"    value="">
								<input type="hidden" name="opt_price8"    id="opt_price8"    value="">
								<input type="hidden" name="opt_price9"    id="opt_price9"    value="">
								<input type="hidden" name="opt_price10"   id="opt_price10"   value="">
								<input type="hidden" name="opt_price11"   id="opt_price11"   value="">

								<input type="hidden" name="tmp_reserve"                      value="<?=$reserve?>">
								<input type="hidden" name="opt_reserve1"  id="opt_reserve1"  value="">
								<input type="hidden" name="opt_reserve2"  id="opt_reserve2"  value="">
								<input type="hidden" name="opt_reserve3"  id="opt_reserve3"  value="">
								<input type="hidden" name="opt_reserve8"  id="opt_reserve8"  value="">
								<input type="hidden" name="opt_reserve9"  id="opt_reserve9"  value="">
								<input type="hidden" name="opt_reserve10" id="opt_reserve10" value="">
								<input type="hidden" name="opt_reserve11" id="opt_reserve11" value="">

								<input type="hidden" name="basket_cnt"    id="basket_cnt"    value="0">

									<table width="100%" border=0 cellpadding=0 cellspacing=0>
										<tr>
											<td class="p_name" style="padding-bottom:13px;"><?=$prdname?></td>
										</tr>
										<tr>
											<td style="background:url('/twcenter/images/prdinfo_bg.gif') repeat-x; padding:5px 10px;" height="100">
												<table border=0 cellpadding=5 cellspacing=0 width=90%>
													<? if($prd_row['conprice'] > $prd_row['sellprice']){ ?>
													<tr>
														<td class="p_tit">정상가</td>
														<td style="padding-left:20px;"><span style="text-decoration:line-through;"><?=number_format($conprice)?>원</span></td>
													</tr>
													<? } ?>
													<tr>
														<td width="25%" class="p_tit">판매가격</td>
														<td style="padding-left:20px;"><span class="price_b"><?=$sellprice?></span></td>
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
																$lev_row->distype = "원";
																$member_price = $lev_row->discount;
															} else {
																$lev_row->distype = "%";
																$member_dis = $lev_row->discount/100;
																$member_price = $prd_row['sellprice']*$member_dis;
															}
													?>
													<tr>
														<td class="p_tit">등급할인액</td>
														<td style="padding-left:20px;"><?=number_format($member_price)?>원 &nbsp;<?=number_format($lev_row->discount)?><?=$lev_row->distype?> [<?=$level?>]</td>
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
														<td class="p_tit">쿠폰할인액</td>
														<td id="coupon" style="padding-left:20px;"><?=number_format($coupon_price)?>원 &nbsp;<?=number_format($prd_row['coupon_dis'])?><?=$coupon_type?>&nbsp;<a href="/twcenter/product/coupon_down.php?prdcode=<?=$prdcode?>"><img src="/twcenter/images/coupon_down.gif" border="0"></a></td>
													</tr>
													<? } ?>

													<? if($oper_info['reserve_use'] == "Y" && empty($strprice)){ ?>
													<? if($opttitle == "" && $opttitle2 == "" && $opttitle3 == "" && $opttitle4 == "" && $opttitle5 == "" && $opttitle6 == "" && $opttitle7 == "" && $opttitle8 == "" && $opttitle9 == "" && $opttitle10 == "" && $opttitle11 == ""){ ?>

													<tr>
														<td class="p_tit">적립금</td>
														<td style="padding-left:20px;"><span id="reserve_default"><?=number_format($reserve)?></span>원</td>
													</tr>
													<?
													  }
													}
													?>
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
														<td class="p_tit">제품상태</td>
														<td style="padding-left:20px;"><?=$sp_img?></td>
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
														<td width="25%"></td>
														<td></td>
													</tr>

													<?
													if(
														$opttitle  == "" && $opttitle2  == "" && $opttitle3  == "" && $opttitle4 == "" && 
														$opttitle5 == "" && $opttitle6  == "" && $opttitle7  == "" && $opttitle8 == "" && 
														$opttitle9 == "" && $opttitle10 == "" && $opttitle11 == ""){
													?>
													<tr>
														<td class="p_tit">수 &nbsp;량</td>
														<td style="padding-left:20px;">
															<table border=0 cellpadding=0 cellspacing=0>
																<tr>
																	<td rowspan=3><input type=text name="amount" id="amount" value="1" size="2" class="amout_num" onkeyup="default_amount();">&nbsp;&nbsp;</td>
																	<td><a href="javascript:incAmount();"><img src="/twcenter/images/but_vol_up.gif" border=0></a></td>
																</tr>
																<tr>
																	<td><a href="javascript:decAmount();"><img src="/twcenter/images/but_vol_down.gif" border=0></a></td>
																</tr>
															</table>
														</td>
													</tr>
													<? } ?>

													<?
													if(
														$opttitle  != "" || $opttitle2  != "" || $opttitle3  != "" || $opttitle4 != "" || 
														$opttitle5 != "" || $opttitle6  != "" || $opttitle7  != "" || $opttitle8 != "" || 
														$opttitle9 != "" || $opttitle10 != "" || $opttitle11 != ""){
													?>
													<input type="hidden" name="amount" value="1" onChange="checkAmount();" onKeyUp="checkAmount()" class="amout_num">
													<? } ?>

													<? if($opttitle5 != ""){ ?>
													<tr>
														<td class="p_tit"><?=$opttitle5?></td>
														<td style="padding-left:20px;">
															<select name="optcode5"  id="optcode5" onChange="checkOpt05()">
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
															<select name="optcode6" id="optcode6" onChange="checkOpt06()">
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
															<select name="optcode7"  id="optcode7" onChange="checkOpt07()">
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
													<tr>
														<td class="p_tit"><?=$opttitle3?></td>
														<td style="padding-left:20px;">
															<select name="optcode3" id="optcode3" onChange="checkOpt03()">
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
															<select name="optcode4" id="optcode4" onChange="checkOpt04()">
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

													<? if($opttitle8 != ""){ ?>
													<tr>
														<td class="p_tit"><?=$opttitle8?></td>
														<td style="padding-left:20px;">
															<select name="optcode8" id="optcode8" onChange="checkOpt08()">
																<option value=""> 선택하세요 </option>
																<?
																$opt_list = explode("^^",$optcode8);
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

													<? if($opttitle9 != ""){ ?>
													<tr>
														<td class="p_tit"><?=$opttitle9?></td>
														<td style="padding-left:20px;">
															<select name="optcode9" id="optcode9" onChange="checkOpt09()">
																<option value=""> 선택하세요 </option>
																<?
																$opt_list = explode("^^",$optcode9);
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

													<? if($opttitle10 != ""){ ?>
													<tr>
														<td class="p_tit"><?=$opttitle10?></td>
														<td style="padding-left:20px;">
															<select name="optcode10" id="optcode10" onChange="checkOpt10()">
																<option value=""> 선택하세요 </option>
																<?
																$opt_list = explode("^^",$optcode10);
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

													<? if($opttitle11 != ""){ ?>
													<tr>
														<td class="p_tit"><?=$opttitle11?></td>
														<td style="padding-left:20px;">
															<select name="optcode11" id="optcode11" onChange="checkOpt11()">
																<option value=""> 선택하세요 </option>
																<?
																$opt_list = explode("^^",$optcode11);
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
														$opt_tmp  = explode("^^", $optvalue);

														if(count($opt1_arr)-1 < 1) $opt1_cnt = 1;
														else $opt1_cnt = count($opt1_arr) - 1;

														if(count($opt2_arr)-1 < 1) $opt2_cnt = 1;
														else $opt2_cnt = count($opt2_arr) - 1;

														$no = 0;
														$optcode = "";
														for($ii = 0; $ii < $opt1_cnt; $ii++) {
															for($jj = 0; $jj < $opt2_cnt; $jj++) {
																list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

																$optcode[$no][optcode]  = $opt1_arr[$ii];
																if(!empty($opt1_arr[$ii]) && !empty($opt2_arr[$jj])) $optcode[$no][optcode] .= "/";
																$optcode[$no][optcode] .= $opt2_arr[$jj];
																$optcode[$no][price]    = $price;
																$optcode[$no][reserve]  = $reserve;
																$optcode[$no][stock]    = $stock;
																$no++;
															}
														}

														?>
															<select  name="optcode" id="optcode" onChange="checkOpt01();">
																<option value=""> 선택하세요 </option>
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
														<td class="p_tit">고객선호도</td>
														<td style="padding-left:20px;"> <img src="/twcenter/images/icon_star_<?=$prefer?>.gif"></td>
													</tr>
													<? }
													*/ ?>
												</table>

											</td>
										</tr>
										<tr>
											<td height=1 bgcolor=#d9d9d9></td>
										</tr>
										<? if($oper_info['sns_use'] == "Y"){ ?>
										<tr>
											<td style="padding:7px;">
												<table border=0 cellpadding=0 cellspacing=0 width=90%>
													<tr>
														<td class="p_tit" width="25%">SNS 스크랩</td>
														<td style="padding-left:20px;">
														<?=$facebook_btn?> <?=$twitter_btn?> <?=$katalk_btn?> <?=$kastory_btn?>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td height=1 bgcolor=#d9d9d9></td>
										</tr>

										<? } ?>
										<tr>
											<td style="padding:7px 0;">
												<table width="100%" cellpadding="0" cellspacing="0" border="3" class="view_table2" id="basket_table">
												</table>

												<div>
												<table id="form_table">

												</table>
												</div>
											</td>
										</tr>

										<tr>
											<td style="padding:7px 0;">
												<table border=0 cellpadding=5 cellspacing=0 width="100%">
												<?
												if( $prd_row['opttitle']   == "" && $prd_row['opttitle2']  == "" && $prd_row['opttitle3'] == "" && 
													$prd_row['opttitle4']  == "" && $prd_row['opttitle5']  == "" && $prd_row['opttitle6'] == "" && 
													$prd_row['opttitle7']  == "" && $prd_row['opttitle8']  == "" && $prd_row['opttitle9'] == "" &&
													$prd_row['opttitle10'] == "" && $prd_row['opttitle11'] == "" ){
												?>
													<tr>
														<td align="right">
															<span style="font-size:15px; font-weight:bold; color:#d43d60">총 상품 금액 : </span>
															<span id="sellprice_default" style="font-size:15px; font-weight:bold; color:#d43d60"><?=$sellprice?></span>
															<span style="font-size:15px; font-weight:bold; color:#d43d60"></span><span class='txt_11pt'></span>
														</td>
													</tr>
												<?
												}else{
												?>
													<tr>
														<td align="right">
															<span style="font-size:15px; font-weight:bold; color:#d43d60">총 상품 금액 : </span>
															<span id="total_price_h" style="font-size:15px; font-weight:bold; color:#d43d60"><?=$sellprice?></span>
															<span style="font-size:15px; font-weight:bold; color:#d43d60"></span><span class='txt_11pt'></span>
														</td>
													</tr>
												<?}?>
												</table>
											</td>
										</tr>

										<tr>
											<td height=1 bgcolor=#d9d9d9></td>
										</tr>


										<tr>
											<td style="padding:10px 0 0 0">
												<table width="100%" border=0 cellpadding=2 cellspacing=0>
													<tr>
														<td style="padding:4px;" align="right">
													<?
													if(empty($strprice)) {
													
														if($opttitle == "" && $opttitle2 == "" && $opttitle3 == "" && $opttitle4 == "" && $opttitle5 == "" && $opttitle6 == "" && $opttitle7 == "" && $opttitle8 == "" && $opttitle9 == "" && $opttitle10 == "" && $opttitle11 == ""){
													?>
															<a href="javascript:saveBasket('buy');"><img src="/twcenter/product/image/but_view_buy.gif" border=0></a> 
															<a href="javascript:saveBasket('basket');"><img src="/twcenter/product/image/but_view_cart.gif" border=0></a> 
															<a href="javascript:saveWish();"><img src="/twcenter/product/image/but_view_keeping.gif" border=0></a>
													<? } else { ?>
															<a href="javascript:ajax_insert('buy');"><img src="/twcenter/product/image/but_view_buy.gif" border=0></a> 
															<a href="javascript:ajax_insert('basket');"><img src="/twcenter/product/image/but_view_cart.gif" border=0></a> 
															<a href="javascript:saveWish();"><img src="/twcenter/product/image/but_view_keeping.gif" border=0></a>
													<?
														}
													}
													?>
															<a href="<?=$PHP_SELF?>?catcode=<?=$catcode?>&brand=<?=$brand?>&page=<?=$page?>&<?=$param?>"><img src="/twcenter/product/image/but_view_list.gif" border=0></a>
														</td>
													</tr>
												</table>
											</td>
										</tr>

										<?
										if($oper_info['nhn_chkout_use'] == "Y"){
										
										$ENABLE = "Y";
										$BUY_BUTTON_HANDLER = "buy_nc";

										if($prd_info->strprice != "") $ENABLE = "N";	// 구매금액 설정 X
										if($prd_info->shortage == "Y" || (!strcmp($prd_info->shortage, "S") && $prd_info->stock <= 0)) $ENABLE = "N";	// 품절

										if($ENABLE == "N") $BUY_BUTTON_HANDLER = "not_buy_nc";
										//① 체크아웃에서 할당받은 버튼 KEY 를 입력
										//② 템플릿을 확인하시고 원하는 타입의 버튼을 선택
										//③ 버튼의 색 설정
										//④ 버튼 개수 설정. 구매하기 버튼(장바구니 페이지)만 있으면 1, 찜하기 버튼(상품 상세 페이지)과 함께 있으면
										//   2 를 입력
										//⑤ 품절등과 같은 이유에 따라 버튼을 비활성화할 필요가 있을 경우
										//⑥ 구매하기 버튼 이벤트 Handler 함수 등록, 품절인 경우 not_buy_nc 함수 사용
										//⑦ 링크 주소 (필요한 경우만 사용)
										//⑧ 찜하기 버튼 이벤트 Handler 함수 등록
										//⑨ 찜하기 팝업 링크 주소
										?>
										<tr>
											<td style="padding:7px;">
												<table border=0 width=100% cellpadding=3 cellspacing=0>
													<tr>
														<td align="right">

											<script type="text/javascript" >//<![CDATA[
												nhn.CheckoutButton.apply({
													BUTTON_KEY:"<?=$oper_info['nhn_chkout_key']?>", //①
													TYPE: "A",									  //② 
													COLOR: 1,									  //③
													COUNT: 2,									  //④
													ENABLE: "<?=$ENABLE?>",						  //⑤
													BUY_BUTTON_HANDLER: <?=$BUY_BUTTON_HANDLER?>, //⑥
													BUY_BUTTON_LINK_URL:"",						  //⑦
													WISHLIST_BUTTON_HANDLER:wishlist_nc,		  //⑧
													WISHLIST_BUTTON_LINK_URL:"http://<?=$_SERVER['HTTP_HOST']?>/twcenter/product/nhn_wish.php?prdcode=<?=$prdcode?>", //⑨
													"":""
												});
											//]]></script>
														</td>
													</tr>
												</table>
											</td>
										</tr>



										<? } ?>

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
								<td width="168"><a href="#info"><img src="/twcenter/product/image/bar_view_detailinfo_up.gif" width="168" height="43" border="0" /></a></td>
								<? if(!strcmp($oper_info['prdrel_use'], "Y")) { ?>
								<td width="167"><a href="#rel"><img src="/twcenter/product/image/bar_prdrel.gif" width="167" height="43" border="0" /></a></td>
								<? } ?>
								<? if(!strcmp($oper_info['qna_usetype'], "Y")) { ?>
								<td width="167"><a href="#qna"><img src="/twcenter/product/image/bar_prdqna.gif" width="167" height="43" border="0" /></a></td>
								<? } ?>
								<? if(!strcmp($oper_info['review_usetype'], "Y")) { ?>
								<td width="167"><a href="#review"><img src="/twcenter/product/image/bar_view_review.gif" width="167" height="43" border="0" /></a></td>
								<? } ?>
								<td width="450" style="background:url('/twcenter/product/image/bar_tab_bg.gif') repeat-x;">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="5" height="30"></td>
							</tr>
							<tr>
								<td colspan="5" style="padding:0 5px;"><?=$content?></td>
							</tr>
						</table>
					</td>
				</tr>

				<!--     구매가이드         -->
				<? //$page_type = "prdview"; include "../inc/page_info.inc"; ?>
				<tr><td colspan="5" align=center valign=top><?=$page_info->content?></td></tr>

				<tr><td colspan="5" height="50"></td></tr>

				<tr>
					<td colspan="5" ><a name="rel">
					<!-- 관련상품 -->
					<? include $_SERVER['DOCUMENT_ROOT'].'/twcenter/product/prd_rel.php'; ?>
					</a></td>
				</tr>
				<tr>
					<td height="40"></td>
				</tr>

				<tr>
					<td><a name="qna">
					<!-- 상품 QnA -->
					<? include $_SERVER['DOCUMENT_ROOT'].'/twcenter/product/prd_qna.php'; ?>
					</a></td>
				</tr>

				<tr>
					<td><a name="review" >
					<!-- 상품리뷰 -->
					<? include $_SERVER['DOCUMENT_ROOT'].'/twcenter/product/prd_review.php'; ?>
				</a></td>
				</tr>
			</table>

<!-- 실제 컨텐츠 끝 -->

		</td>
	</tr>
</table>