<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/api_info.php";

if($_GET['catcode'] != "") $catcode = $_GET['catcode'];

echo "<link href=\"".$skin_dir."style.css\" rel=\"stylesheet\" type=\"text/css\">".PHP_EOL;
echo "<script language='javascript' src='/comm/js/common.lib.js'></script>".PHP_EOL;
echo "<script src=\"//developers.kakao.com/sdk/js/kakao.min.js\"></script>".PHP_EOL;

/*
작업자		: 이상민
작업일시	: 2019-08-06
작업내용	: 네이버페이 스크립트 로드 URL http 제거
*/
if($oper_info['nhn_chkout_use'] == "Y"){
	if($oper_info['nhn_host_chk'] == "T"){
		echo "<script type=\"text/javascript\" src=\"//test-pay.naver.com/customer/js/naverPayButton.js\" charset=\"UTF-8\"></script>";
	} else if($oper_info['nhn_host_chk'] == "S") {
		echo "<script type=\"text/javascript\" src=\"//pay.naver.com/customer/js/naverPayButton.js\" charset=\"UTF-8\"></script>";
	}

	/*
	작업자		: 이상민
	작업일시	: 2019-09-05
	작업내용	: 모바일에서 네이버페이를 통해 PC버전으로 접근시 모바일로 리다이렉트
	*/
	if(mobile_check()){
		echo "<script>location.href='/m/sub/prdview.php?prdcode=".$prdcode."';</script>";
	}
}

$param = "grp=$grp&brand=$brand&orderby=$orderby&searchopt=$searchopt&searchkey=$searchkey";

/* 즐겨찾기상태에서 미진열상품 클릭시 메인으로 이동처리 */
$show_row = sql_fetch("select showset from wiz_product where prdcode='".$prdcode."' ");
if($show_row['showset'] != 'Y') {
	alert_gourl('해당상품은 진열중이 아닙니다.','/');
}

// 상품정보 가져오기 (이동하지 말것)
$sql = "
	select *
		 , new as newc 
	  from wiz_product wp
		 , wiz_cprelation wc 
	 where wp.prdcode = '".$prdcode."' 
	   and wc.prdcode = wp.prdcode
";
$result = query($sql);
$total = sql_fetch_rows($sql);
$prd_row = sql_fetch_arr($result);

if($prdcode == "" || $total <= 0) error("존재하지 않는 상품입니다.");
if($catcode == "") $catcode = $prd_row['catcode'];

// 상품 조회수 업데이트
$sql = "
	update wiz_product 
	   set viewcnt = viewcnt + 1 
	 where prdcode = '".$prdcode."'
";
query($sql);

// 상품평 갯수
$sql_recnt = "select count(*) as cnt from wiz_bbs where code='review' and prdcode='$prdcode'";
$row_recnt = sql_fetch($sql_recnt);
$review_count = $row_recnt['cnt'];

// 상품Q&A
$sql = "select count(*) as cnt from wiz_bbs where code = 'qna' and prdcode = '$prdcode'";
$result = query($sql) or error("sql error");
$row = sql_fetch_arr($result);
$qna_cnt = number_format($row['cnt']);

include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/cat_info.php"; 		// 카테고리정보

$shortexp    = nl2br($prd_row['shortexp']);
$content     = $prd_row['content'];
$prdname     = $prd_row['prdname'];
$sns_prdname = strip_tags($prdname);
$sns_content = strip_tags($content);

// 오늘본 상품목록에 추가
$view_exist = false;
$view_idx = 0;
for($ii=0;$ii<100;$ii++){
	if($_SESSION["view_list"][$ii]['prdcode']) $view_idx++;
}
for($ii = 0; $ii < $view_idx; $ii++){
	if($_SESSION["view_list"][$ii]['prdcode'] == $prdcode){ $view_exist = true; break; }
}
if(!$view_exist){
	$_SESSION["view_list"][$view_idx]['prdcode'] = $prdcode;
	$_SESSION["view_list"][$view_idx]['prdimg']  = $prd_row['prdimg_R'];
	$_SESSION["view_list"][$view_idx]['prdurl']  = $_SERVER['PHP_SELF'];
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

$opt3_req		= $prd_row['opt3_req'];
$opt4_req		= $prd_row['opt4_req'];
$opt5_req		= $prd_row['opt5_req'];
$opt6_req		= $prd_row['opt6_req'];
$opt7_req		= $prd_row['opt7_req'];
$opt8_req		= $prd_row['opt8_req'];
$opt9_req		= $prd_row['opt9_req'];
$opt10_req		= $prd_row['opt10_req'];
$opt11_req		= $prd_row['opt11_req'];

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

$eventcouponuse = $prd_row['eventcouponuse'];
$eventcouponlink = $prd_row['eventcouponlink'];
$eventcouponidx = $prd_row['eventcouponidx'];

$prdcom			= $prd_row['prdcom'];
$origin			= $prd_row['origin'];
$prd_stock		= $prd_row['stock'];

$stortexp	= $prd_row["stortexp"];

// 상품아이콘
if($prd_row['popular'] == "Y") 	$sp_img .= "<img src='/twcenter/images/icon_hit.gif'>&nbsp;";
if($prd_row['recom'] == "Y") 	$sp_img .= "<img src='/twcenter/images/icon_rec.gif'>&nbsp;";
if($prd_row['newc'] == "Y") 	$sp_img .= "<img src='/twcenter/images/icon_new.gif'>&nbsp;";
if($prd_row['best'] == "Y") 	$sp_img .= "<img src='/twcenter/images/icon_best.gif'>&nbsp;";
if($prd_row['sale'] == "Y")		$sp_img .= "<img src='/twcenter/images/icon_sale.gif'>&nbsp;";

if($prd_row['shortage'] == "Y" || (!strcmp($prd_row['shortage'], "S") && $prd_row['stock'] <= 0)) 
	$sp_img .= "<img src='/twcenter/images/icon_not.gif'>&nbsp;";

$prdicon_list = explode("/",$prd_row['prdicon']);
for($ii=0; $ii<count($prdicon_list)-1; $ii++){
  $sp_img .= "<img src='/twcenter/data/prdicon/".$prdicon_list[$ii]."'> ";
}

if(!empty($prd_row['strprice'])) $sellprice = $prd_row['strprice'];
else $sellprice = number_format($prd_row['sellprice']);

if($prdimg_max < 12) $prdimg_hide_max = 12;
else $prdimg_hide_max = $prdimg_max;
for($ii = 1; $ii <= $prdimg_hide_max; $ii++) {

	if(!is_file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".${'prdimg_S'.$ii})){
		${'prdimg_hide_start'.$ii} = "<!--"; ${'prdimg_hide_end'.$ii} = "-->";
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

$list_btn = "<a href='".$_SERVER['PHP_SELF']."?ptype=list&page=".$page."&catcode=".$catcode."&".$param."'><img src='".$skin_dir."/image/btn_list.gif' border='0'></a>";

if($oper_info['chk_readglass'] == 'Y') {
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
<?php } ?>
<script language="javascript">
<!--
var prdimg = "<?php echo $prd_row['prdimg_L1'] ?>";
var imgno = 1;

/*뒤로가기버튼 누를경우 select값 초기화*/
$( document ).ready(function() {
	$("select[name*=optcode]").val('');
})

function chgImage(idx){
<?php
for($ii = 1; $ii <= $prdimg_max; $ii++) {
?>
	if(idx == "<?php echo $ii ?>"){
		prdimg = "<?php echo $prd_row['prdimg_L'.$ii] ?>";
		document.prdimg.src = "/twcenter/data/prdimg/<?php echo $prd_row['prdimg_M'.$ii] ?>&imgno="+imgno;
	}
<?php
}
?>

}

// 상품이미지 팝업
function prdZoom(){
	var url = "/twcenter/product/prd_zoom.php?prdcode=<?php echo $prdcode ?>";
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
	var opt_price4    = document.prdForm.opt_price4.value;
	var opt_price8    = document.prdForm.opt_price8.value;
	var opt_price9    = document.prdForm.opt_price9.value;
	var opt_price10   = document.prdForm.opt_price10.value;
	var opt_price11   = document.prdForm.opt_price11.value;

	var tmp_reserve   = document.prdForm.tmp_reserve.value;
	var opt_reserve1  = document.prdForm.opt_reserve1.value;
	var opt_reserve2  = document.prdForm.opt_reserve2.value;

	var opt_reserve3  = document.prdForm.opt_reserve3.value;
	var opt_reserve4  = document.prdForm.opt_reserve4.value;
	var opt_reserve8  = document.prdForm.opt_reserve8.value;
	var opt_reserve9  = document.prdForm.opt_reserve9.value;
	var opt_reserve10 = document.prdForm.opt_reserve10.value;
	var opt_reserve11 = document.prdForm.opt_reserve11.value;

	var amount        = document.prdForm.amount.value;

	if(tmp_sellprice == "") tmp_sellprice = 0;
	if(opt_price1    == "") opt_price1    = 0;
	if(opt_price2    == "") opt_price2    = 0;

	if(opt_price3    == "") opt_price3    = 0;
	if(opt_price4    == "") opt_price4    = 0;
	if(opt_price8    == "") opt_price8    = 0;
	if(opt_price9    == "") opt_price9    = 0;
	if(opt_price10   == "") opt_price10   = 0;
	if(opt_price11   == "") opt_price11   = 0;

	if(tmp_reserve   == "") tmp_reserve   = 0;
	if(opt_reserve1  == "") opt_reserve1  = 0;
	if(opt_reserve2  == "") opt_reserve2  = 0;

	if(opt_reserve3  == "") opt_reserve3  = 0;
	if(opt_reserve4  == "") opt_reserve4  = 0;
	if(opt_reserve8  == "") opt_reserve8  = 0;
	if(opt_reserve9  == "") opt_reserve9  = 0;
	if(opt_reserve10 == "") opt_reserve10 = 0;
	if(opt_reserve11 == "") opt_reserve11 = 0;

	<?php
	if($opttitle == "" && $opttitle2 == "" && $opttitle3 == "" && $opttitle4 == "" && $opttitle5 == "" && $opttitle6 == "" && $opttitle7 == "" && $opttitle8 == "" && $opttitle9 == "" && $opttitle10 == "" && $opttitle11 == ""){
	?>
		var sellprice = String(eval(tmp_sellprice) + eval(opt_price1) + eval(opt_price2) + eval(opt_price3) + eval(opt_price4) + eval(opt_price8) + eval(opt_price9) + eval(opt_price10) + eval(opt_price11));
		var reserve = String(eval(tmp_reserve) + eval(opt_reserve1) + eval(opt_reserve2) + eval(opt_reserve3) + eval(opt_reserve4) + eval(opt_reserve8) + eval(opt_reserve9) + eval(opt_reserve10) + eval(opt_reserve11));

	<?php } ?>

	<?php
	if(!empty($wiz_session['id']) && empty($strprice)) {
	
		if(
		$coupon_use == "Y" &&
		$coupon_sdate <= date('Y-m-d') &&
		$coupon_edate >= date('Y-m-d') &&
		($coupon_limit == "N" || ($coupon_limit == "" && $coupon_amount > 0))
		){
	?>

	sellprice = eval(tmp_sellprice) + eval(opt_price1) + eval(opt_price2) + eval(opt_price3) + eval(opt_price4) + eval(opt_price8) + eval(opt_price9) + eval(opt_price10) + eval(opt_price11);

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

	}
	?>

	document.prdForm.tmp_sellprice.value = tmp_sellprice;
	var total_sell_price   = tmp_sellprice * amount;
	var total_sell_reserve = tmp_reserve * amount;
	$("#sellprice_default").html(addCommas(total_sell_price));
	<?php if($oper_info['reserve_use'] == "Y" && empty($strprice)){ ?>
		$("#reserve_default").html(addCommas(total_sell_reserve));
	<?php } ?>

}

// 수량 증가
function incAmount(){

	var amount = document.prdForm.amount.value;
	document.prdForm.amount.value = ++amount;
	checkAmount();
		setSellprice();

}

// 수량 감소
function decAmount(){

   var amount = document.prdForm.amount.value;
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
   <?php if($prd_row['opt_use'] == "Y" && (!empty($prd_row['opttitle']) || !empty($prd_row['opttitle2']))){ ?>
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
   <?php }else if(!strcmp($prd_row['shortage'], "S")) { ?>
		if( document.prdForm.amount != null){
			if( amount > <?=$prd_row['stock']?>){
				 alert("재고량이 부족합니다.");
				 document.prdForm.amount.value = "1";
				 return false;
			}else{
				return true;
			}
		}
   <?php } else { ?>

   		return true;

   <?php } ?>

	}

}

// 가격변동,품절옵션 체크
function checkOptex(){

	var optcode = $("#bottom_optcode option:selected").val();
	
	$("#optcode").val(optcode);


	return checkOpt01();

}

function basketChk(){
	var ks = 0;
	for(var i=0;i<=50;i++){
		if($("#form_tr"+i).length > 0){
			ks++;
		}
	}
	return ks;
}
// 가격변동,품절옵션 체크
function checkOpt01(){
	
	if(document.prdForm.optcode != null){

		var optval = document.prdForm.optcode.value;
		var optlist = optval.split("^");
		var optlist_opt = optlist[3].split("||");
		var addok = "Y";
		var basket_cnt = $("#basket_cnt").val();

		if(basket_cnt >= "99999"){
			alert("옵션항목이 너무많습니다.");
		} else {
			
			var opt_sum = document.prdForm.opt_sum.value;
			var o_list  = optlist[0];

			var _arr = opt_sum.split("^^");
			for(var i=0; i<_arr.length; i++){
				if(_arr[i] == o_list){
					addok="N";
				}
			}

			if(addok == "Y"){

				document.prdForm.opt_sum.value += o_list + "^^";
				
				if(optval == ""){

					document.prdForm.opt_price1.value = "";
					document.prdForm.opt_reserve1.value = "";
					setSellprice();

				}else{

					//optlist[0] : 옵션명 optlist[1] : 가격 optlist[2] : 적립금 optlist[3] 재고

					if(optlist_opt[0] == "0" || optlist_opt[0] == ""){

						alert('품절된 상품입니다.');
						document.prdForm.optcode[0].selected = true;
						document.prdForm.opt_price1.value = "";
						document.prdForm.opt_reserve1.value = "";
						$("select[name=optcode]").val('');
						setSellprice();

						return false;

					// 옵션별 가격 적용
					}else{
						document.prdForm.opt_price1.value = optlist[1];
						document.prdForm.opt_reserve1.value = optlist[2];
						setSellprice();

						add_option(2,optval);

					}
				}

			} else {
				alert("이미 추가된 옵션입니다.");
				$("select[name=optcode]").val('');
			}

		}

	}

	return checkAmount();

}

function OptionAdd(name, num) {

	var optval  = $("select[name=" + name + "]").val();
	if(optval) {

		var price   = $("input[id=opt_price" + num + "]");
		var reserve = $("input[id=opt_reserve" + num + "]");

		var optlist = optval.split("^");

		if(optlist[3] != '' && optlist[3] != undefined) {
			var optlist_opt = optlist[3].split("||");
		}

		var addok = "Y";
		var basket_cnt = $("#basket_cnt").val();

		var frmc = $("select[name="+name+"]");

		if(basket_cnt >= "99999"){
			alert("옵션항목이 너무많습니다.");

		} else {
			
			var opt_sum = document.prdForm.opt_sum.value;
			var o_list  = optlist[0];

			var _arr = opt_sum.split("^^");
			for(var i=0; i<_arr.length; i++){
				if(_arr[i] == o_list){
					addok = "N";
				}
			}

			if(addok == "Y"){
				document.prdForm.opt_sum.value += o_list + "^^";
				if(frmc != null){

					if(frmc.val() == "") {
						price.val('');
						reserve.val('');
						setSellprice();

					} else {

						var optval = frmc.val();
						var optlist = optval.split("^");

						price.val(optlist[1]);
						reserve.val(optlist[2]);

						setSellprice();
						add_option(num,optval);

					}
				}
			}else{
				alert("이미 추가된 옵션입니다.");
				$("select[name=" + name + "]").val('');
			}

			return checkAmount();
		}

	}

}

// 옵션체크
function checkOption(){
	
	if( document.prdForm.optcode != null){
		if(document.prdForm.optcode.value == ""){
			alert("옵션을 선택해주세요");
			document.prdForm.optcode.focus();
			return false;
		}
	}
	if( document.prdForm.optcode2 != null){
		if(document.prdForm.optcode2.value == ""){
			alert("옵션을 선택해주세요");
			document.prdForm.optcode2.focus();
			return false;
		}
	}

	return true;
}

function saveBasket(direct){
	<?php
	if($prd_row['shortage'] == "Y" || (!strcmp($prd_row['shortage'], "S") && $prd_row['stock'] <= 0)) echo "alert('죄송합니다. 품절상품 입니다.');";
	else {
		if(empty($prd_info['basket_url']) || empty($prd_info['order_url'])) {
			echo "alert('장바구니 또는 주문페이지가 설정되지 않았습니다.');";
		} else {
			echo "if(checkOption() && checkOpt01()){";
			echo "  if(direct == 'buy'){";
			echo "    document.prdForm.direct.value = direct; document.prdForm.mode.value='insert'; document.prdForm.submit();";
			echo "  } else { ";
			echo "    ajax_nonopt(direct); ";
			echo "  } ";
			echo "}";
		}
	}
	?>
}

function ajax_nonopt(type){

	var ajaxParam = $("#prdForm").serialize()+"&direct="+type;
	$.ajax({
		type:"post"
		, async: false
		, url:  "/twcenter/product/prd_save.php"
		, data: ajaxParam
		, success: function(data) {
			if(type=="basket"){
				if(confirm("장바구니에 담겼습니다. \n장바구니로 이동하시겠습니까?")){
					document.location="/<?=$prd_info['basket_url']?>";
				}
			}
		}
		, error: function(){
			alert("연동페이지를 확인하시기 바랍니다.");
		}
	});

}

// 관심상품 등록
function saveWish(){

	if(checkOption()){
		var frm = document.prdForm;
		frm.mode.value = "my_wish";
		frm.submit();
	}

}

function trremove(no,opt,opname){
	//console.log("no="+no, "opt="+opt, "opname="+opname);
	var basket_cnt = $("#basket_cnt").val();

	var opt_sum = document.prdForm.opt_sum.value;
	var optcode_val = document.prdForm.optcode_val.value;
	var optcode_list = optcode_val.split("&&");

	var optcode3_val = document.prdForm.optcode3_val.value;
	var optcode_list3 = optcode3_val.split("&&");

	var optcode4_val = document.prdForm.optcode4_val.value;
	var optcode_list4 = optcode4_val.split("&&");

	var optcode5_val = document.prdForm.optcode5_val.value;
	var optcode_list5 = optcode5_val.split("&&");

	var optcode6_val = document.prdForm.optcode6_val.value;
	var optcode_list6 = optcode6_val.split("&&");

	var optcode7_val = document.prdForm.optcode7_val.value;
	var optcode_list7 = optcode7_val.split("&&");

	var optcode8_val = document.prdForm.optcode8_val.value;
	var optcode_list8 = optcode8_val.split("&&");

	var optcode9_val = document.prdForm.optcode9_val.value;
	var optcode_list9 = optcode9_val.split("&&");

	var optcode10_val = document.prdForm.optcode10_val.value;
	var optcode_list10 = optcode10_val.split("&&");

	var optcode11_val = document.prdForm.optcode11_val.value;
	var optcode_list11 = optcode11_val.split("&&");

	//var tmp_optcode_list = optcode_list[0];
	//alert(opname);

	for(var k=0;k<basket_cnt;k++){
		if(opname==optcode_list[k]){
			var tmp_optcode_list = optcode_list[k];
		}
	}

	if(optcode_list3!=""){
		for(var k=0;k<basket_cnt;k++){
			if(optcode_list3[k]!='' && typeof optcode_list3[k]!='undefined'){
				var optcode_list3_cut = optcode_list3[k].split("/");
				if(opname==optcode_list3_cut[0]){
					var tmp_optcode_list3 = optcode_list3[k];
				}
			}
		}
	}

	if(optcode_list4!=""){
		for(var k=0;k<basket_cnt;k++){
			if(optcode_list4[k]!='' && typeof optcode_list4[k]!='undefined'){
				var optcode_list4_cut = optcode_list4[k].split("/");
				if(opname==optcode_list4_cut[0]){
					var tmp_optcode_list4 = optcode_list4[k];
				}
			}
		}
	}

	if(optcode_list5!=""){
		for(var k=0;k<basket_cnt;k++){
			if(optcode_list5[k]!='' && typeof optcode_list5[k]!='undefined'){
				var optcode_list5_cut = optcode_list5[k].split("/");
				if(opname==optcode_list5_cut[0]){
					var tmp_optcode_list5 = optcode_list5[k];
				}
			}
		}
	}

	if(optcode_list6!=""){
		for(var k=0;k<basket_cnt;k++){
			if(optcode_list6[k]!='' && typeof optcode_list6[k]!='undefined'){
				var optcode_list6_cut = optcode_list6[k].split("/");
				if(opname==optcode_list6_cut[0]){
					var tmp_optcode_list6 = optcode_list6[k];
				}
			}
		}
	}

	if(optcode_list7!=""){
		for(var k=0;k<basket_cnt;k++){
			if(optcode_list7[k]!='' && typeof optcode_list7[k]!='undefined'){
				var optcode_list7_cut = optcode_list7[k].split("/");
				if(opname==optcode_list7_cut[0]){
					var tmp_optcode_list7 = optcode_list7[k];
				}
			}
		}
	}

	if(optcode_list8!=""){
		for(var k=0;k<basket_cnt;k++){
			if(optcode_list8[k]!='' && typeof optcode_list8[k]!='undefined'){
				var optcode_list8_cut = optcode_list8[k].split("/");
				if(opname==optcode_list8_cut[0]){
					var tmp_optcode_list8 = optcode_list8[k];
				}
			}
		}
	}

	if(optcode_list9!=""){
		for(var k=0;k<basket_cnt;k++){
			if(optcode_list9[k]!='' && typeof optcode_list9[k]!='undefined'){
				var optcode_list9_cut = optcode_list9[k].split("/");
				if(opname==optcode_list9_cut[0]){
					var tmp_optcode_list9 = optcode_list9[k];
				}
			}
		}
	}

	if(optcode_list10!=""){
		for(var k=0;k<basket_cnt;k++){
			if(optcode_list10[k]!='' && typeof optcode_list10[k]!='undefined'){
				var optcode_list10_cut = optcode_list10[k].split("/");
				if(opname==optcode_list10_cut[0]){
					var tmp_optcode_list10 = optcode_list10[k];
				}
			}
		}
	}

	if(optcode_list11!=""){
		for(var k=0;k<basket_cnt;k++){
			if(optcode_list11[k]!='' && typeof optcode_list11[k]!='undefined'){
				var optcode_list11_cut = optcode_list11[k].split("/");
				if(opname==optcode_list11_cut[0]){
					var tmp_optcode_list11 = optcode_list11[k];
				}
			}
		}
	}
	
	if(opt == 2){
		var optcode = $("#tmpopt"+no).val();
		var optlist = optcode.split("^");
		var tmp_optlist = optlist[0];
	} else if(opt == 5 || opt == 6 || opt == 7) {	
		var optcode = $("#tmpopt"+opt+""+no).val();
		var optlist = optcode.split("/");
		var tmp_optlist = optlist[0];
	} else if(opt == 3 || opt == 4 || opt == 8 || opt == 9 || opt == 10 || opt == 11) {	
		var optcode = $("#tmpopt"+opt+""+no).val();
		var optlist = optcode.split("/");
		var tmp_optlist = optlist[0];
	}
	//document.prdForm.opt_sum.value     = opt_sum.replace(tmp_optlist + "^^", "");
	//document.prdForm.optcode_val.value = optcode_val.replace(tmp_optcode_list + "&&", "");
	$("input[name=opt_sum]").val(opt_sum.replace(tmp_optlist + "^^", ""));
	$("input[name=optcode_val]").val(optcode_val.replace(tmp_optcode_list + "&&", ""));


/*	document.prdForm.optcode3_val.value = optcode3_val.replace(tmp_optcode_list3 + "&&", "");
	document.prdForm.optcode4_val.value = optcode4_val.replace(tmp_optcode_list4 + "&&", "");
	document.prdForm.optcode5_val.value = optcode5_val.replace(tmp_optcode_list5 + "&&", "");
	document.prdForm.optcode6_val.value = optcode6_val.replace(tmp_optcode_list6 + "&&", "");
	document.prdForm.optcode7_val.value = optcode7_val.replace(tmp_optcode_list7 + "&&", "");
	document.prdForm.optcode8_val.value = optcode8_val.replace(tmp_optcode_list8 + "&&", "");
	document.prdForm.optcode9_val.value = optcode9_val.replace(tmp_optcode_list9 + "&&", "");
	document.prdForm.optcode10_val.value = optcode10_val.replace(tmp_optcode_list10 + "&&", "");
	document.prdForm.optcode11_val.value = optcode11_val.replace(tmp_optcode_list11 + "&&", "");
*/
	$("input[name=optcode3_val]").val(optcode3_val.replace(tmp_optcode_list3 + "&&", ""));
	$("input[name=optcode4_val]").val(optcode4_val.replace(tmp_optcode_list4 + "&&", ""));
	$("input[name=optcode5_val]").val(optcode5_val.replace(tmp_optcode_list5 + "&&", ""));
	$("input[name=optcode6_val]").val(optcode6_val.replace(tmp_optcode_list6 + "&&", ""));
	$("input[name=optcode7_val]").val(optcode7_val.replace(tmp_optcode_list7 + "&&", ""));
	$("input[name=optcode8_val]").val(optcode8_val.replace(tmp_optcode_list8 + "&&", ""));
	$("input[name=optcode9_val]").val(optcode9_val.replace(tmp_optcode_list9 + "&&", ""));
	$("input[name=optcode10_val]").val(optcode10_val.replace(tmp_optcode_list10 + "&&", ""));
	$("input[name=optcode11_val]").val(optcode11_val.replace(tmp_optcode_list11 + "&&", ""));

	//console.log(document.prdForm.optcode_val.value);
	//$("#tmp_optcode_val").val(document.prdForm.optcode_val.value);
	$("#basket_tr"+no).remove(); 
	$("#form_tr"+no).remove();
	$("#basket_cnt").val(basket_cnt);

	total_prd_price();

	//$("#optcode").val('');
	//alert(opt);
	//$("#id_optcode"+opt).val('');
	if(opt == 2) {
		$('#optcode').find('option:first').attr('selected', 'selected');
	} else {
		$('[name=optcode' + opt + ']').val('');
	}
	//$('#tmpopt' + opt + no).find('option:first').attr('selected', 'selected');
}

function add_option(opt,optval){

	var basket_cnt = $("#basket_cnt").val();
	var optcode = optval;
	var optlist = optcode.split("^");
	var tmp_sellprice = $("#tmp_sellprice").val();
	var tmp_reserve   = $("#tmp_reserve").val();
	
	switch(opt){
		case "5":
		case "6":
		case "7":
			optlist[2] = parseInt(optlist[2]) + parseInt(tmp_reserve);
			break;
	}

	var opt_res_use = '<?php echo $opt_use ?>';

	var html  = "";
	var html2 = "";
	var num   = ++basket_cnt;
	var no    = ++basket_cnt;
	if(no == 1) no = no+2;

	if(opt == 2){

		var t_price    = parseInt(tmp_sellprice) + parseInt(optlist[1]);
		var t_reserve  = parseInt(tmp_reserve) + parseInt(optlist[2]);
		var t_sort_num = optcode.split("||");
		var sort_num   = t_sort_num[1];
		var optcode_v  = optlist[0]+"^"+optlist[1]+"^"+optlist[2];
		var optcode_real_value = t_price;


		document.prdForm.optcode_val.value  += optlist[0]+"&&";
		document.prdForm.optcode2_val.value += optcode_v+"&&";
		
		html += "<tr id='basket_tr"+no+"' style='border: 1px solid #f0f0f0;background-color: #fafafa;'>\n";
		html += "	<td style='padding:0 0 0 10px'><strong>"+optlist[0]+"</strong></td>\n";
		html += "	<td align=center width='25%'>\n";
		html += "	<div>\n";
		html += "<img src='/twcenter/images/but_vol_down.gif' onclick='javascript:decAmount2("+no+","+opt+");' style='cursor:pointer'>";
		html += "<input ntype='text' name='basket_amount"+no+"' id='basket_amount"+no+"' value='1' size='2' class='amout_num2' onChange='checkAmount2("+no+","+opt+")' onkeyup='inputAmount("+no+","+opt+")'>";
		html += "<img src='/twcenter/images/but_vol_up.gif' onclick='javascript:incAmount2("+no+","+opt+");' style='cursor:pointer'>\n";
		html += "	</div>\n";
		html += "	</td>\n";
		html += "	<td align='right' width='14%'><span id='basket_price"+no+"'>"+addCommas(t_price)+"</span>원</td>\n";
		html += "	<input type='hidden' id='basket_price_hidden"+no+"' value='"+t_price+"'>\n";
		<?php if($oper_info['reserve_use'] == "Y" && empty($strprice)){ ?>
		html += "	<td align='right' width='14%'><span id='basket_reserve"+no+"'>"+addCommas(t_reserve)+"</span>원</td>\n";
		html += "	<input type='hidden' id='basket_reserve_hidden"+no+"' value='"+t_reserve+"'>\n";
		<?php } ?>
		html += "	<td align=center width='10%'>\n";
		html += "		<div style='clear:both'>\n";
		html += "			<input class='btn_type4' onclick='trremove("+no+","+opt+",\""+optlist[0]+"\")' type='button' value='삭제'>\n";
		html += "		</div>\n";
		html += "	</td>\n";
		html += "</tr>\n";

		html2 += "<tr id='form_tr"+no+"'>\n";
		html2 += "	<td>\n";
		html2 += "		<form class='sform' name='bottom_form"+no+"' id='bottom_form"+no+"'>";
		html2 += "		<input type='hidden' name='mode'				id='mode"+no+"'				value='insert'>\n";
		html2 += "		<input type='hidden' name='direct'				id='direct"+no+"'			value=''>\n";
		html2 += "		<input type='hidden' name='prdcode'				id='prdcode"+no+"'			value='<?=$prdcode?>'>\n";
		html2 += "		<input type='hidden' name='optcode'				id='tmpopt"+no+"'			value='"+optcode_v+"'>\n";
		html2 += "		<input type='hidden' name='optcode2'			id='tmpopt2"+no+"'			value='"+optcode_v+"'>\n";
		<?php if(!strcmp($opt_use, "Y") && (!empty($opttitle) || !empty($opttitle2))) { ?>
		html2 += "		<input type='hidden' name='opttitle'			id='opttitle"+no+"'			value='<?=$opttitle?>'>\n";
		html2 += "		<input type='hidden' name='opttitle2'			id='opttitle2"+no+"'		value='<?=$opttitle2?>'>\n";	
		<?php } ?>
		html2 += "		<input type='hidden' name='tmp_sellprice'		id='tmp_sellprice"+no+"'	value='"+tmp_sellprice+"'>\n";
		html2 += "		<input type='hidden' name='opt_price1'			id='opt_price1"+no+"'		value='"+optlist[1]+"'>\n";
		html2 += "		<input type='hidden' name='ajax_opt_price[]'	id='ajax_opt_price"+no+"'	value='"+optlist[1]+"'>\n";
		html2 += "		<input type='hidden' name='ajax_opt_reserve"+opt+"[]'	id='opt_reserve1"+no+"'	value='"+optlist[2]+"'>\n";
		html2 += "		<input type='hidden' name='tmp_reserve'			id='tmp_reserve"+no+"'		value='"+tmp_reserve+"'>\n";
		html2 += "		<input type='hidden' name='opt_reserve1'		id='opt_reserve1"+no+"'		value='"+optlist[2]+"'>\n";
		html2 += "		<input type='hidden' name='opt_reserve2'		id='opt_reserve2"+no+"'		value=''>\n";
		html2 += "		<input type='hidden' name='opt_stock'			id='opt_stock"+no+"'		value='"+optlist[3]+"'>\n";
		html2 += "		<input type='hidden' name='amount[]'			id='amount"+no+"'			value='1'>\n";
		html2 += "		<input type='hidden' name='optcode_val'			id='optcode_val'			value='"+document.prdForm.optcode_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode_real_value'								value='"+optcode_real_value+"'>\n";
		html2 += "		<input type='hidden' name='optcode2_val'		id='optcode2_val'			value='"+document.prdForm.optcode2_val.value+"'>\n";
		html2 += "		</form>\n";
		html2 += "	</td>\n";
		html2 += "</tr>\n";

	} else {

//		var t_price    = parseInt(optlist[1]);
		var t_reserve  = parseInt(optlist[2]);
		var t_sort_num = optcode.split("||");
		var sort_num   = t_sort_num[1];

		var opttitle   = "<?php echo $opttitle ?>";
		var opttitle3  = "<?php echo $opttitle3 ?>";
		var opttitle4  = "<?php echo $opttitle4 ?>";
		var opttitle5  = "<?php echo $opttitle5 ?>";
		var opttitle6  = "<?php echo $opttitle6 ?>";
		var opttitle7  = "<?php echo $opttitle7 ?>";
		var opttitle8  = "<?php echo $opttitle8 ?>";
		var opttitle9  = "<?php echo $opttitle9 ?>";
		var opttitle10 = "<?php echo $opttitle10 ?>";
		var opttitle11 = "<?php echo $opttitle11 ?>";


		if(opttitle != ""){
			var t_price    = parseInt(optlist[1]);
			var t_price22    = parseInt(optlist[1]);
			switch(opt){
				case "5":
				case "6":
				case "7":
					var t_price    = parseInt(tmp_sellprice) + parseInt(optlist[1]);
					var t_price22  = parseInt(tmp_sellprice) + parseInt(optlist[1]);
					break;
			}
		} else if(opttitle == "") {
			if(
				(opttitle5 != "" || opttitle6 != "" || opttitle7 != "") && 
				(opttitle3 != "" || opttitle4 != "" || opttitle8 != "" || opttitle9 != "" || opttitle10 != "" || opttitle11 != ""))
				{
				//var t_price    = parseInt(optlist[1]); /*옵션가격만 추가되어 기본가격 더해줌*/
				//var t_price22  = parseInt(optlist[1]);
				var t_price    = parseInt(tmp_sellprice) + parseInt(optlist[1]);
				var t_price22  = parseInt(tmp_sellprice) + parseInt(optlist[1]);
			} else if(opttitle5 != "" || opttitle6 != "" || opttitle7 != "") {
				var t_price    = parseInt(tmp_sellprice) + parseInt(optlist[1]);
				var t_price22  = parseInt(tmp_sellprice) + parseInt(optlist[1]);
			} else {
				//var t_price    = parseInt(optlist[1]); /*옵션가격만 추가되어 기본가격 더해줌*/
				//var t_price22  = parseInt(optlist[1]);
				var t_price    = parseInt(tmp_sellprice) + parseInt(optlist[1]);
				var t_price22  = parseInt(tmp_sellprice) + parseInt(optlist[1]);
			}

		}

		if(opt == 5){
			var optcode5_val  = optlist[0]+"/"+optlist[1]+"/"+optlist[2];
			document.prdForm.optcode5_val.value += optcode5_val+"&&";
		}
		
		if(opt == 6){
			var optcode6_val  = optlist[0]+"/"+optlist[1]+"/"+optlist[2];
			document.prdForm.optcode6_val.value += optcode6_val+"&&";
		}

		if(opt == 7){
			var optcode7_val  = optlist[0]+"/"+optlist[1]+"/"+optlist[2];
			document.prdForm.optcode7_val.value += optcode7_val+"&&";
		}

		if(opt == 3){
			var optcode3_val  = optlist[0]+"/"+optlist[1]+"/"+optlist[2];
			document.prdForm.optcode3_val.value += optcode3_val+"&&";
		}

		if(opt == 4){
			var optcode4_val  = optlist[0]+"/"+optlist[1]+"/"+optlist[2];
			document.prdForm.optcode4_val.value += optcode4_val+"&&";
		}

		if(opt == 8){
			var optcode8_val  = optlist[0]+"/"+optlist[1]+"/"+optlist[2];
			document.prdForm.optcode8_val.value += optcode8_val+"&&";
		}

		if(opt == 9){
			var optcode9_val  = optlist[0]+"/"+optlist[1]+"/"+optlist[2];
			document.prdForm.optcode9_val.value += optcode9_val+"&&";
		}

		if(opt == 10){
			var optcode10_val  = optlist[0]+"/"+optlist[1]+"/"+optlist[2];
			document.prdForm.optcode10_val.value += optcode10_val+"&&";
		}

		if(opt == 11){
			var optcode11_val  = optlist[0]+"/"+optlist[1]+"/"+optlist[2];
			document.prdForm.optcode11_val.value += optcode11_val+"&&";
		}

		if(optcode3_val  == "" || optcode3_val  == undefined) optcode3_val  = "";
		if(optcode4_val  == "" || optcode4_val  == undefined) optcode4_val  = "";
		if(optcode5_val  == "" || optcode5_val  == undefined) optcode5_val  = "";
		if(optcode6_val  == "" || optcode6_val  == undefined) optcode6_val  = "";
		if(optcode7_val  == "" || optcode7_val  == undefined) optcode7_val  = "";
		if(optcode8_val  == "" || optcode8_val  == undefined) optcode8_val  = "";
		if(optcode9_val  == "" || optcode9_val  == undefined) optcode9_val  = "";
		if(optcode10_val == "" || optcode10_val == undefined) optcode10_val = "";
		if(optcode11_val == "" || optcode11_val == undefined) optcode11_val = "";

		if(opt == 3)       prd_opt_title = "<?=$opttitle3?>";
		else if(opt == 4)  prd_opt_title = "<?=$opttitle4?>";
		else if(opt == 5)  prd_opt_title = "<?=$opttitle5?>";
		else if(opt == 6)  prd_opt_title = "<?=$opttitle6?>";
		else if(opt == 7)  prd_opt_title = "<?=$opttitle7?>";
		else if(opt == 8)  prd_opt_title = "<?=$opttitle8?>";
		else if(opt == 9)  prd_opt_title = "<?=$opttitle9?>";
		else if(opt == 10) prd_opt_title = "<?=$opttitle10?>";
		else if(opt == 11) prd_opt_title = "<?=$opttitle11?>";

		if(opttitle != ""){
			switch(opt){
				case "5":
				case "6":
				case "7":
					break;
				default:
					tmp_sellprice = 0;
			}
		}

		html += "<tr id='basket_tr"+no+"' style='border: 1px solid #f0f0f0;background-color: #fafafa;'>\n";
		html += "	<td style='padding:0 0 0 10px'><strong>"+optlist[0]+"</strong></td>\n";
		html +=	"	<td align=center width='25%'>\n";
		html += "	<div>\n";
		html += "<img src='/twcenter/images/but_vol_down.gif' onclick='javascript:decAmount2("+no+","+opt+");' style='cursor:pointer'>";
		html += "<input ntype='text' name='basket_amount"+no+"' id='basket_amount"+no+"' value='1' size='2' class='amout_num2' onChange='checkAmount2("+no+","+opt+")' onkeyup='inputAmount("+no+","+opt+")'>";
		html += "<img src='/twcenter/images/but_vol_up.gif' onclick='javascript:incAmount2("+no+","+opt+");' style='cursor:pointer'>\n";
		html += "	</div>\n";
		html += "	</td>\n";
		html += "	<td align=right width='14%'><span id='basket_price"+no+"'>"+addCommas(t_price)+"</span>원</td>\n";
		html += "	<input type='hidden' id='basket_price_hidden"+no+"' value='"+t_price+"'>\n";
		<?php if($oper_info['reserve_use'] == "Y" && empty($strprice)){ ?>
		html += "	<td align=right width='14%'><span id='basket_reserve"+no+"'>"+addCommas(t_reserve)+"</span>원</td>\n";
		html += "	<input type='hidden' id='basket_reserve_hidden"+no+"' value='"+t_reserve+"'>\n";
		<?php } ?>
		html += "	<td align=center width='10%'>\n";
		html += "		<div style='clear:both'>\n";
		html += "			<input class='btn_type4' onclick='trremove("+no+","+opt+",\""+optlist[0]+"\")' type='button' value='삭제'>\n";
		html += "		</div>\n";
		html += "	</td>\n";
		html += "</tr>";

		html2 += "<tr id='form_tr"+no+"'>\n";
		html2 += "	<td>\n";
		html2 += "		<form class='sform' name='bottom_form"+no+"' id='bottom_form"+no+"'>\n";

		if(opt_res_use == 'Y') {

		html2 += "		<input type='hidden' name='optcode3'			id='tmpopt3"+no+"'			value='"+optcode3_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode4'			id='tmpopt4"+no+"'			value='"+optcode4_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode5'			id='tmpopt5"+no+"'			value='"+optcode5_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode6'			id='tmpopt6"+no+"'			value='"+optcode6_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode7'			id='tmpopt7"+no+"'			value='"+optcode7_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode8'			id='tmpopt8"+no+"'			value='"+optcode8_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode9'			id='tmpopt9"+no+"'			value='"+optcode9_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode10'			id='tmpopt10"+no+"'			value='"+optcode10_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode11'			id='tmpopt11"+no+"'			value='"+optcode11_val+"'>\n";
		html2 += "		<input type='hidden' name='opttitle"+opt+"'		id='opttitle"+opt+""+no+"'	value='"+prd_opt_title+"'>\n";
		html2 += "		<input type='hidden' name='tmp_sellprice'		id='tmp_sellprice"+no+"'	value='"+tmp_sellprice+"'>\n";
		html2 += "		<input type='hidden' name='opt_price"+opt+"'	id='opt_price1"+no+"'		value='"+optlist[1]+"'>\n";
		html2 += "		<input type='hidden' name='ajax_opt_price"+opt+"[]'	id='opt_price1"+no+"'	value='"+t_price22+"'>\n";
		html2 += "		<input type='hidden' name='ajax_opt_reserve"+opt+"[]'	id='opt_reserve1"+no+"'	value='"+t_reserve+"'>\n";
		html2 += "		<input type='hidden' name='tmp_reserve'			id='tmp_reserve"+no+"'		value='"+tmp_reserve+"'>\n";
		html2 += "		<input type='hidden' name='opt_reserve"+opt+"'	id='opt_reserve"+no+"'		value='"+optlist[2]+"'>\n";
		html2 += "		<input type='hidden' name='opt_stock'			id='opt_stock"+no+"'		value='"+optlist[3]+"'>\n";
		html2 += "		<input type='hidden' name='amount"+opt+"[]'			id='amount"+no+"'			value='1'>\n";
		html2 += "		<input type='hidden' name='optcode3_val'		id='optcode3_val'			value='"+document.prdForm.optcode3_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode4_val'		id='optcode4_val'			value='"+document.prdForm.optcode4_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode5_val'		id='optcode5_val'			value='"+document.prdForm.optcode5_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode6_val'		id='optcode6_val'			value='"+document.prdForm.optcode6_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode7_val'		id='optcode7_val'			value='"+document.prdForm.optcode7_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode8_val'		id='optcode8_val'			value='"+document.prdForm.optcode8_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode9_val'		id='optcode9_val'			value='"+document.prdForm.optcode9_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode10_val'		id='optcode10_val'			value='"+document.prdForm.optcode10_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode11_val'		id='optcode11_val'			value='"+document.prdForm.optcode11_val.value+"'>\n";

		} else {

		html2 += "		<input type='hidden' name='mode'				id='mode"+no+"'				value='insert'>\n";
		html2 += "		<input type='hidden' name='direct'				id='direct"+no+"'			value=''>\n";
		html2 += "		<input type='hidden' name='prdcode'				id='prdcode"+no+"'			value='<?=$prdcode?>'>\n";
		html2 += "		<input type='hidden' name='optcode3'			id='tmpopt3"+no+"'			value='"+optcode3_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode4'			id='tmpopt4"+no+"'			value='"+optcode4_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode5'			id='tmpopt5"+no+"'			value='"+optcode5_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode6'			id='tmpopt6"+no+"'			value='"+optcode6_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode7'			id='tmpopt7"+no+"'			value='"+optcode7_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode8'			id='tmpopt8"+no+"'			value='"+optcode8_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode9'			id='tmpopt9"+no+"'			value='"+optcode9_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode10'			id='tmpopt10"+no+"'			value='"+optcode10_val+"'>\n";
		html2 += "		<input type='hidden' name='optcode11'			id='tmpopt11"+no+"'			value='"+optcode11_val+"'>\n";
		html2 += "		<input type='hidden' name='opttitle"+opt+"'		id='opttitle"+opt+""+no+"'	value='"+prd_opt_title+"'>\n";
		html2 += "		<input type='hidden' name='tmp_sellprice'		id='tmp_sellprice"+no+"'	value='"+tmp_sellprice+"'>\n";
		html2 += "		<input type='hidden' name='opt_price"+opt+"'	id='opt_price1"+no+"'		value='"+optlist[1]+"'>\n";
		html2 += "		<input type='hidden' name='ajax_opt_price"+opt+"[]'	id='opt_price1"+no+"'	value='"+t_price22+"'>\n";
		html2 += "		<input type='hidden' name='ajax_opt_reserve"+opt+"[]'	id='opt_reserve1"+no+"'	value='"+t_reserve+"'>\n";
		html2 += "		<input type='hidden' name='tmp_reserve'			id='tmp_reserve"+no+"'		value='"+tmp_reserve+"'>\n";
		html2 += "		<input type='hidden' name='opt_reserve"+opt+"'	id='opt_reserve"+no+"'		value='"+optlist[2]+"'>\n";
		html2 += "		<input type='hidden' name='opt_stock'			id='opt_stock"+no+"'		value='"+optlist[3]+"'>\n";
		html2 += "		<input type='hidden' name='amount"+opt+"[]'			id='amount"+no+"'			value='1'>\n";
		html2 += "		<input type='hidden' name='optcode3_val'		id='optcode3_val'			value='"+document.prdForm.optcode3_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode4_val'		id='optcode4_val'			value='"+document.prdForm.optcode4_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode5_val'		id='optcode5_val'			value='"+document.prdForm.optcode5_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode6_val'		id='optcode6_val'			value='"+document.prdForm.optcode6_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode7_val'		id='optcode7_val'			value='"+document.prdForm.optcode7_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode8_val'		id='optcode8_val'			value='"+document.prdForm.optcode8_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode9_val'		id='optcode9_val'			value='"+document.prdForm.optcode9_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode10_val'		id='optcode10_val'			value='"+document.prdForm.optcode10_val.value+"'>\n";
		html2 += "		<input type='hidden' name='optcode11_val'		id='optcode11_val'			value='"+document.prdForm.optcode11_val.value+"'>\n";

		}
		//console.log(html2);
		html2 += "		</form>\n";
		html2 += "	</td>\n";
		html2 += "</tr>\n";

	}

	$("#basket_table").append(html);
	$("#form_table").append(html2);
	$("#basket_cnt").val(num);
	total_prd_price();

}

function total_prd_price(){
	
	var basket_cnt    = parseInt($("#basket_cnt").val());
	var total_price   = 0;
	var total_reserve = 0;
	var tmp_sellprice = 0;
	var tmp_reserve = 0;
	var opt_price     = 0;
	var amount        = 1;
	
	for(var i=2; i<=11; i++) {
		if($("#basket_reserve"+i)) {
			tmp_reserve = $("#basket_reserve"+i).text().replace(/,/gi, "");
			total_reserve += Number( tmp_reserve );
		}
	}

	for(var i=1;i<=basket_cnt+1;i++){

		tmp_sellprice = $("#tmp_sellprice"+i).val();
		opt_price     = $("#opt_price1"+i).val();
		amount        = parseInt($("#amount"+i).val());
		if(opt_price == ""){
			opt_price=0;
		}

		if(typeof(tmp_sellprice) === undefined) return;
		if(tmp_sellprice && opt_price){	

			total_price += (parseInt(tmp_sellprice)+parseInt(opt_price))*amount;
			/*<?php if($opttitle != "") { ?>

			total_price += (parseInt(tmp_sellprice)+parseInt(opt_price))*amount;

			<?php } else if($opttitle == "") { ?>

				<?php if(($opttitle5 != "" || $opttitle6 != "" || $opttitle7 != "") && 
						 ($opttitle3 != "" || $opttitle4 != "" || $opttitle8 != "" || 
						  $opttitle9 != "" || $opttitle10 != "" || $opttitle11 != ""))
					  {
				?>
					alert('a');
					total_price += (parseInt(opt_price))*amount;
				<?php } else if($opttitle5 != "" || $opttitle6 != "" || $opttitle7 != "") { ?>
					total_price += (parseInt(tmp_sellprice) + parseInt(opt_price))*amount;
				<?php } else { ?>
					total_price += (parseInt(opt_price))*amount;
				<?php
				}
				?>

			<?php } ?>*/
			
		}
	
	}

	if(total_price == 0){
		<?php
		if(!empty($prd_row['strprice'])){
		?>
		total_price = parseInt("<?php echo $prd_row['strprice'] ?>");
		<?php
		}else{
		?>
		total_price = parseInt(0);
		<?php
		}
		?>

		$("#total_price_h").html(addCommas(total_price));
		$("#total_reserve").html(addCommas(total_reserve));
	}else{
		$("#total_price_h").html(addCommas(total_price));
		$("#total_reserve").html(addCommas(total_reserve));
	}
}
function addCommas( strValue ){ 
	strValue = ""+strValue;
	var objRegExp = new RegExp('(-?[0-9]+)([0-9]{3})'); 
		while(objRegExp.test(strValue)) { 
			strValue = strValue.replace(objRegExp, '$1,$2'); 
		} 
	return strValue; 
}

function basket_check(){
	var id = "#basket_tr";
	var count = "";
	for(var i=0;i<50;i++){
		var id_check = id+i;
		if ($(id_check).length > 0) {
			count++;
		}
	}
	return count;
}

/*장바구니 가기전 수량 체크*/
function basket_amount_check(){

	var stock = "#opt_stock";
	var amount = "#basket_amount";
	var count = 0;
	for(var i=0;i<50;i++){
		var stock_check = stock+i;
		var amount_check = amount+i;
		if ($(stock_check).length > 0) {
			var stock_val = $(stock_check).val();
			var amount_val = $(amount_check).val();
			stock_val = stock_val*1;
			amount_val = amount_val*1;

			if(stock_val < amount_val){
				count++;
			}
		}
	}
	//alert(count);
	return count;
}

function ajax_insert(type){

	var opt_use    = '<?php echo $opt_use ?>';
	var basket_cnt = parseInt($("#basket_cnt").val());
	var bb_cnt     = basket_check();
	var optcode    = $("[name=optcode_val]").val();

	if(basket_cnt == "0" || bb_cnt == "") {
		if(opt_use == 'Y') {
			alert('(필수) <?php echo $opttitle ?>/<?php echo $opttitle2 ?> 옵션을 선택해주세요.');
			return false;
		} else {
			alert("옵션을 선택해주세요.");
			return false;
		}
	} else if(opt_use == 'Y' && optcode == "") {
		alert('(필수) <?php echo $opttitle ?>/<?php echo $opttitle2 ?> 옵션을 선택해주세요.');
		return false;
	} else {
		<?php
		for($i=3; $i<=11; $i++) {
			if(${'opt'.$i.'_req'} == 'Y') {
		?>
		var optcode_value = $("#optcode<?php echo $i ?>_val").val();
		if(optcode_value == "") {
		alert("<?php echo ${'opttitle'.$i} ?> 옵션을 선택해주세요.");
		return false;
		}
		<?php
			}
		}
		?>
		var bottom_form_val = "";
			bottom_form_val = $(".sform").serialize();
			var tot_sortnum = $("#tot_sortnum").val();
			var ajaxParam = bottom_form_val+"&direct="+type+"&tot_sortnum="+tot_sortnum;
			$.ajax({
				type:"post"
				, async: false
				, url:  "/twcenter/product/ajax_basket_v.php"
				, data: ajaxParam
				, success: function(data) {
					//console.log(data);
					var data_result = data.split('^');
					if(data_result[1] == "buy"){
						document.location="/<?php echo $prd_info['order_url'] ?>?product_idx="+data_result[0];
					}
				}
				, error: function(){
					alert("연동페이지를 확인하시기 바랍니다.");
				}
			});
		
		if(type == 'basket'){
			if(confirm("장바구니에 담겼습니다. \n장바구니로 이동하시겠습니까?")){
				document.location="/<?php echo $prd_info['basket_url'] ?>";
			}
		}
	}

}

function ajax_insert_wish(type){

<?php
	if(empty($wiz_session['id'])) {
?>
	alert("로그인 후 이용해주세요.");
	return false;
<?php
	}
?>
	var opt_use    = '<?php echo $opt_use ?>';
	var basket_cnt = $("#basket_cnt").val();
	var bb_cnt = basket_check();
	var optcode    = $("[name=optcode_val]").val();

	if(bb_cnt == "0" || bb_cnt == "") {
		if(opt_use == 'Y') {
			alert('(필수) <?php echo $opttitle ?>/<?php echo $opttitle2 ?> 옵션을 선택해주세요.');
			return false;
		} else {
			alert("옵션을 선택해주세요.");
			return false;
		}
	} else if(opt_use == 'Y' && optcode == "") {
		alert('(필수) <?php echo $opttitle ?>/<?php echo $opttitle2 ?> 옵션을 선택해주세요.');
		return false;
	} else {

		<?php
		for($i=3; $i<=11; $i++) {
			if(${'opt'.$i.'_req'} == 'Y') {
		?>
		var optcode_value = $("#optcode<?php echo $i ?>_val").val();
		if(optcode_value == "") {
		alert("<?php echo ${'opttitle'.$i} ?> 옵션을 선택해주세요.");
		return false;
		}
		<?php
			}
		}
		?>

		var bottom_form_val = "";
			bottom_form_val = $(".sform").serialize();
		var ajaxParam = bottom_form_val;
		//console.log(ajaxParam);
			$.ajax({
				type:"post"
				, async: false
				, url:  "/twcenter/product/ajax_wish_v.php"
				, data: ajaxParam
				, success: function(data) {

					//console.log(data);
					//if(data == 9999) {
					//	if(confirm("선택상품의 같은 옵션이 관심상품으로 등록되어있습니다.\n관심상품으로 이동하시겠습니까?")){
					//		document.location="/<?php echo $prd_info['wish_url'] ?>";
					//	}
					//	go_return = true;
					//}
				}
				, error: function(){
					alert("연동페이지를 확인하시기 바랍니다.");
				}
			});
		}
		
		if(type=="wish"){
			if(confirm("관심상품에 담겼습니다. \n관심상품으로 이동하시겠습니까?") == true){
				document.location="/<?php echo $prd_info['wish_url'] ?>";
			} else {
				$("select[name*=optcode]").val('');
			}
		}
	
}


// 수량 직접 입력
function inputAmount(no,opt){

	var amount = $("#basket_amount"+no).val();
	
	$("#basket_amount"+no).val(amount);
	$("#bottom_amount"+no).val(amount);
	$("#amount"+no).val(amount);
	
	var price   = $("#basket_price_hidden"+no).val();
	var reserve = $("#basket_reserve_hidden"+no).val();
	var tot     = price*amount;
	var tot_res = reserve*amount;
	$("#basket_price"+no).html(addCommas(tot));
	$("#basket_reserve"+no).html(addCommas(tot_res));

	checkAmount2(no,opt);

}
// 수량 증가
function incAmount2(no,opt){

	var amount = $("#basket_amount"+no).val();
	var upamount = ++amount;
	
	$("#basket_amount"+no).val(upamount);
	$("#bottom_amount"+no).val(upamount);
	$("#amount"+no).val(upamount);
	
	var price   = $("#basket_price_hidden"+no).val();
	var reserve = $("#basket_reserve_hidden"+no).val();
	var tot     = price*upamount;
	var tot_res = reserve*upamount;
	$("#basket_price"+no).html(addCommas(tot));
	$("#basket_reserve"+no).html(addCommas(tot_res));

	checkAmount2(no,opt);

}

// 수량 감소
function decAmount2(no,opt){

	var amount = $("#basket_amount"+no).val();
	if(amount > 1){
		var downamount = --amount;
		$("#basket_amount"+no).val(downamount);
		$("#bottom_amount"+no).val(downamount);
		$("#amount"+no).val(downamount);

		var price   = $("#basket_price_hidden"+no).val();
		var reserve = $("#basket_reserve_hidden"+no).val();
		var tot     = price*downamount;
		var tot_res = reserve*downamount;
		$("#basket_price"+no).html(addCommas(tot));
		$("#basket_reserve"+no).html(addCommas(tot_res));

		checkAmount2(no,opt);
	}

}

function checkAmount2(no,opt){

	var amount = $("#amount"+no).val();
	if(!check_Num(amount) || amount < 1){

	$("#amount"+no).val(1);

	}else{
   <?php if($prd_row['opt_use'] == "Y" && (!empty($prd_row['opttitle']) || !empty($prd_row['opttitle2']))){ ?>
		
			var selvalue = $("#tmpopt"+opt+""+no).val();
			var optlist = selvalue.split("^");
			if( amount > eval(optlist[3])){
				 alert("재고량이 부족합니다.");
				 $("#amount"+no).val(1);
				 total_prd_price();
				 return false;
			}else{
				total_prd_price();
				return true;
			}
		
   <?php }else if(!strcmp($prd_row['shortage'], "S")) { ?>
		
			if( amount > <?php echo $prd_row['stock'] ?>){
				 alert("재고량이 부족합니다.");
				 $("#amount"+no).val(1);
				 total_prd_price();
				 return false;
			}else{
				total_prd_price();
				return true;
			}
		
   <?php } else { ?>
		total_prd_price();
   		return true;
		

   <?php } ?>
	}
   
	
}

function check_Num(tocheck)
{
	var isnum = true;
	
	if (tocheck == null || tocheck == "")
	{
		isnum = false;
		return isnum;
	}
	
	for (var j = 0 ; j < tocheck.length; j++)
	{
		if (tocheck.substring(j, j + 1) != "0"
			&&   tocheck.substring(j, j + 1) != "1"
			&&   tocheck.substring(j, j + 1) != "2"
			&&   tocheck.substring(j, j + 1) != "3"
			&&   tocheck.substring(j, j + 1) != "4"
			&&   tocheck.substring(j, j + 1) != "5"
			&&   tocheck.substring(j, j + 1) != "6"
			&&   tocheck.substring(j, j + 1) != "7"
			&&   tocheck.substring(j, j + 1) != "8"
			&&   tocheck.substring(j, j + 1) != "9" )
		{
			isnum = false;
		}
	}
	return isnum;
}


$("body").click(function(e){

	for(var i=1; i<50; i++){
		var basket_id = "";
		basket_id = "#basket_amount"+i;
		if (!$(basket_id).is(e.target)) {
			if($(basket_id).length>0){
				key_check(i);
			}
		}
	}

});

function key_check(no){
	var tt = "t";
	var ss = "f";
	var id = "basket_amount"+no;

	var id_ch = document.getElementById(id).value;
	if(id_ch>1){
		return tt;
	}else{
		document.getElementById(id).value = 1;
		return ss;
	}
}


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

$(document).ready(function(){
	
	<?php if( preg_match('/KT/',$oper_info['sns_method']) || preg_match('/KS/',$oper_info['sns_method'])){?>
	// 카카오톡, 카카오스토리 공유하기 API

	Kakao.init("<?php echo $api_info['kakao_api_key2'] ?>");
	var _url = "http://<?php echo $_SERVER['HTTP_HOST'] ?><?php echo $_SERVER['REQUEST_URI'] ?>";

	<?php } ?>


	<?php if( preg_match('/KS/',$oper_info['sns_method'])) {?>

		Kakao.Story.createShareButton({
		  container: "#kakaostory-share-button",
		  url: _url,
		  text: "<?php echo $sns_prdname ?>"
		});

	<?php } ?>

	<?php if( preg_match('/KT/',$oper_info['sns_method'])){?>
		Kakao.Link.createDefaultButton({
		  container: "#kakao-link-btn",
		  objectType: "feed",
		  content: {
			title: "<?php echo $site_info['site_name'] ?>",
			description: "<?php echo $sns_prdname ?>",
			imageUrl: "http://<?php echo $_SERVER['HTTP_HOST'] ?>/twcenter/data/prdimg/<?php echo $prdimg_S1 ?>",
			link: {
			  mobileWebUrl: _url,
			  webUrl: _url
			}
		  },
		  buttons: [
			{
			  title: '바로가기',
			  link: {
				mobileWebUrl: _url,
				webUrl: _url
			  }
			}
		  ]
		});
  <?php } ?>
});

<?php
if($oper_info['nhn_chkout_use'] == "Y"){
?>
function buy_nc(url) {

	<?php
		if(
			$opttitle  == "" && $opttitle2  == "" && $opttitle3  == "" && $opttitle4 == "" && 
			$opttitle5 == "" && $opttitle6  == "" && $opttitle7  == "" && $opttitle8 == "" && 
			$opttitle9 == "" && $opttitle10 == "" && $opttitle11 == ""){
	?>

		/* 미옵션상품의 경우 */
		<?php
		if($oper_info['nhn_chkout_key'] == '' || $oper_info['nhn_shopid'] == '' || $oper_info['nhn_certikey'] == ''){
		?>
			alert("네이버페이 사용을 위한 키값이 설정되어 있지 않습니다.");
			return false;
		<?php
		}
		?>
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

	<?php
		} else {
	?>

		/* 옵션상품의 경우 */
		var basket_cnt = $("#basket_cnt").val();
		var bb_cnt = basket_check();
		var basket_error = false;

		if(bb_cnt == "0" || bb_cnt == "") {
			alert('옵션을 선택해주세요');
			return false;
		}else{
			var bottom_form_val = "";
			bottom_form_val = $(".sform").serialize();
			var tot_sortnum = $("#tot_sortnum").val();
			var ajaxParam = bottom_form_val+"&direct=checkout&tot_sortnum="+tot_sortnum;

			$.ajax({
				type:"post"
				, async: false
				, url:  "/twcenter/product/ajax_basket_v.php"
				, data: ajaxParam
				, success: function(data) {
					console.log(data);
				}
				, error: function(){
					alert("연동페이지를 확인하시기 바랍니다.");
					basket_error = true;
				}
			});

			if(basket_error == false){
				window.open("/twcenter/product/nhn_order.php?prdcode=<?php echo $prdcode; ?>","CheckOutOrder");
			}
		}

	<?php
		}
	?>

}
function wishlist_nc(url) {

	<?php
	if($oper_info['nhn_chkout_key'] == '' || $oper_info['nhn_shopid'] == '' || $oper_info['nhn_certikey'] == ''){
	?>
		alert("네이버페이 사용을 위한 키값이 설정되어 있지 않습니다.");
		return false;
	<?php
	} else {
	?>
		// 네이버 체크아웃으로 찜 정보를 등록하는 가맹점 페이지 팝업 창 생성.
		// 해당 페이지에서 찜 정보 등록 후 네이버 체크아웃 찜 페이지로 이동.
		window.open(url,"","scrollbars=yes,width=400,height=267");
		return false;

	<?php } ?>
}

function not_buy_nc() {

	<?php
	if($oper_info['nhn_chkout_key'] == '' || $oper_info['nhn_shopid'] == '' || $oper_info['nhn_certikey'] == ''){
	?>
		alert("네이버페이 사용을 위한 키값이 설정되어 있지 않습니다.");
		return false;
	<?php
	} else {
	?>
		alert("죄송합니다. NAVER Checkout으로 구매가 불가한 상품입니다.");
		return false;
	<?php
	}
	?>
}
<?php } ?>

-->
</script>

<?php

// 다음이전 상품
$catcode01 = str_replace("00","",substr($catcode,0,2));
$catcode02 = str_replace("00","",substr($catcode,2,2));
$catcode03 = str_replace("00","",substr($catcode,4,2));
$catcode04 = str_replace("00","",substr($catcode,6,2));

$tmp_catcode = $catcode01.$catcode02.$catcode03.$catcode04;
$sql = "
	select wp.prdcode 
	  from wiz_cprelation wc
		 , wiz_product wp 
	 where wc.catcode like '".$tmp_catcode."%' 
	   and wc.prdcode = wp.prdcode 
	   and wp.showset != 'N' 
	   and wp.prdcode > '".$prdcode."' 
	 order by wp.prdcode asc 
	 limit 1
";
$result = query($sql);
if($row = sql_fetch_obj($result)) {
	$prev = "<a href='".$_SERVER['PHP_SELF']."?ptype=view&prdcode=".$row->prdcode."&catcode=".$catcode."&".$param."'>이전</a>";
	$prev_prdcode = "".$_SERVER['PHP_SELF']."?ptype=view&prdcode=".$row->prdcode."&catcode=".$catcode."&".$param;
} else {
	$prev = "<a href=javascript:prevAlert();>이전</a>";
	$prev_prdcode = "javascript:prevAlert();";
}

$sql = "
	select wc.prdcode 
	  from wiz_cprelation wc
		 , wiz_product wp 
	 where wc.catcode like '".$tmp_catcode."%' 
	   and wc.prdcode = wp.prdcode 
	   and wp.showset != 'N' 
	   and wp.prdcode < '".$prdcode."' 
	 order by wp.prdcode desc 
	 limit 1
";
$result = query($sql);
if($row = sql_fetch_obj($result)) {
	$next = "<a href='".$_SERVER['PHP_SELF']."?ptype=view&prdcode=".$row->prdcode."&catcode=".$catcode."&".$param."'>다음</a>";
	$next_prdcode = "".$_SERVER['PHP_SELF']."?ptype=view&prdcode=".$row->prdcode."&catcode=".$catcode."&".$param;
} else {
	$next = "<a href=javascript:nextAlert();>다음</a>";
	$next_prdcode = "javascript:nextAlert();";
}

/** 상품카테고리경로 **/
$cate_lo = "";
if($catcode != ""){

	$catcode1 = substr($catcode,0,2);
	$catcode2 = substr($catcode,0,4);
	$catcode3 = substr($catcode,0,6);
	$catcode4 = substr($catcode,0,8);

	$sql = "
		select *
		  from wiz_category
		 where
			(catcode like '$catcode1%' and depthno = 1) or
			(catcode like '$catcode2%' and depthno = 2) or
			(catcode like '$catcode3%' and depthno = 3) or
			(catcode like '$catcode4%' and depthno = 4) or
			(catcode = '$catcode')
			
			order by depthno asc
		";
	$result = query($sql) or error("sql error");

	while($prow = sql_fetch_obj($result)){
		$cate_lo .= " &gt; <a href=".$_SERVER['PHP_SELF']."?catcode=".$prow->catcode.">".$prow->catname."</a>";
	}

}


/** SNS **/
$sns_go_Link  = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

$sns_method = explode("/",$oper_info['sns_method']);
for($ii=0; $ii<count($sns_method)-1; $ii++){
	if($sns_method[$ii] == "FB"){
		$facebook_btn = "<a href=\"javascript:sendSns('facebook','http://$sns_go_Link','$sns_prdname');\"><img src='/twcenter/product/image/facebook_icon.gif' border='0' title='To FaceBook'></a>";
	}
	if($sns_method[$ii] == "TT"){
		$twitter_btn  = "<a href=\"javascript:sendSns('twitter','http://$sns_go_Link','$sns_prdname');\"><img src='/twcenter/product/image/twitter_icon.gif' border='0' title='To Twitter'></a>";
	}
	if($sns_method[$ii] == "KT"){
		$katalk_btn   = "<a id=\"kakao-link-btn\" href=\"javascript:;\"><img src='/twcenter/product/image/kakao_icon.gif' border='0' title='To KakaoTalk'></a>";
	}
	if($sns_method[$ii] == "KS"){
		//$kastory_btn  = "<a href=\"javascript:;\" id=\"kakaostory-share-button\"><img src='/twcenter/product/image/kakaostory_icon.gif' border='0' title='To KakaoStory'></a>";
		$kastory_btn  = "<a href=\"javascript:;\" id=\"kakaostory-share-button\"></a>";
	}
}


?>



<div class="pro_view">

	<!-- 상품 이미지 -->
	<div class="viewImg">
	<style>
	.prdimg img{max-height:500px;}
	</style>
	<?php
	if(!strcmp($oper_info['chk_readglass'], "Y")) {
	?>
	<div class="simpleLens-gallery-container" id="ZOOM">
		<div class="simpleLens-container">
			<div class="simpleLens-big-image-container" style="border:1px solid #cdcdcd; width:500px; height:500px;">
				<a class="simpleLens-lens-image" data-lens-image="<?php echo $prdimg_L ?>">
					<img src="<?php echo $prdimg ?>" class="simpleLens-big-image">
				</a>
			</div>
		</div>
		<div class="imgBtn">
			<button type="button" onClick="location.href='<?php echo $prev_prdcode ?>'" class="btn_type5">Prev</button>
			<button type="button" onClick="prdZoom();" class="btn_type5">+ &nbsp;Zoom</button>
			<button type="button" onClick="location.href='<?php echo $next_prdcode ?>'" class="btn_type5">Next</button>
		</div>
		<div class="simpleLens-thumbnails-container">

			<?php $imgpath = $_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg";  ?>
			<?php
			for($ii = 1; $ii <= 5; $ii++) {
				if(@file($imgpath."/".${"prdimg_L".$ii})){
			 ?>

			<a href="#" class="simpleLens-thumbnail-wrapper" data-lens-image="/twcenter/data/prdimg/<?php echo ${"prdimg_L".$ii} ?>"
						 data-big-image="/twcenter/data/prdimg/<?php echo ${"prdimg_L".$ii} ?>">
				<table width="70" height="70" cellpadding=0 cellspacing=0 style="border: 1px solid #cdcdcd">
					<tr>
						<td>
							<img src="/twcenter/data/prdimg/<?php echo ${"prdimg_S".$ii} ?>">
						</td>
					</tr>
				</table></a>
			<?
				}
			}
			 ?>
		</div>
	</div>
	<?php } else {  ?>
	<div class="viewImgNor">
		<div class="prdimg"><img src="<?php echo $prdimg ?>" name="prdimg"></div>
		<div class="imgBtn">
			<button type="button" onClick="location.href='<?=$prev_prdcode?>'" class="btn_type5">Prev</button>
			<button type="button" onClick="prdZoom();" class="btn_type5">+ &nbsp; Zoom</button>
			<button type="button" onClick="location.href='<?=$next_prdcode?>'" class="btn_type5">Next</button>
		</div>

		<ul class="smallImg">
		<?php $imgpath = $_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg"; ?>
		<?php
		for($ii = 1; $ii <= 5; $ii++) {
			if(@file($imgpath."/".${"prdimg_S".$ii})){
		?>
		<li><img src="/twcenter/data/prdimg/<?=${"prdimg_S".$ii}?>" onMouseOver="document.prdimg.src='/twcenter/data/prdimg/<?=${"prdimg_M".$ii}?>'"></li>
		<?php
			}
		}
		?>
		</ul>
	</div>
	<?php }  ?>
	</div>


	<!-- 상품정보 -->
	<div class="viewTxt">
	<form name="prdForm" id="prdForm" action="/twcenter/product/prd_save.php" method="post">
	<input type="hidden" name="mode"                             value="insert">
	<input type="hidden" name="direct"                           value="">
	<input type="hidden" name="prdcode"                          value="<?php echo $prdcode ?>">
	<?php if(!strcmp($opt_use, "Y") && (!empty($opttitle) || !empty($opttitle2))) { ?>
	<input type="hidden" name="opttitle"      id="opttitle"      value="<?php echo $opttitle ?>">
	<input type="hidden" name="opttitle2"     id="opttitle2"     value="<?php echo $opttitle2 ?>">
	<?php } ?>
	<input type="hidden" name="opttitle3"     id="opttitle3"     value="<?php echo $opttitle3 ?>">
	<input type="hidden" name="opttitle4"     id="opttitle4"     value="<?php echo $opttitle4 ?>">
	<input type="hidden" name="opttitle5"     id="opttitle5"     value="<?php echo $opttitle5 ?>">
	<input type="hidden" name="opttitle6"     id="opttitle6"     value="<?php echo $opttitle6 ?>">
	<input type="hidden" name="opttitle7"     id="opttitle7"     value="<?php echo $opttitle7 ?>">

	<input type="hidden" name="opttitle8"     id="opttitle8"     value="<?php echo $opttitle8 ?>">
	<input type="hidden" name="opttitle9"     id="opttitle9"     value="<?php echo $opttitle9 ?>">
	<input type="hidden" name="opttitle10"    id="opttitle10"    value="<?php echo $opttitle10 ?>">
	<input type="hidden" name="opttitle11"    id="opttitle11"    value="<?php echo $opttitle11 ?>">

	<input type="hidden" name="tmp_sellprice" id="tmp_sellprice" value="<?php echo $prd_row['sellprice'] ?>">
	<input type="hidden" name="opt_price1"    id="opt_price1"    value="">
	<input type="hidden" name="opt_price2"    id="opt_price2"    value="">
	<input type="hidden" name="opt_price3"    id="opt_price3"    value="">
	<input type="hidden" name="opt_price4"    id="opt_price4"    value="">
	<input type="hidden" name="opt_price8"    id="opt_price8"    value="">
	<input type="hidden" name="opt_price9"    id="opt_price9"    value="">
	<input type="hidden" name="opt_price10"   id="opt_price10"   value="">
	<input type="hidden" name="opt_price11"   id="opt_price11"   value="">

	<input type="hidden" name="tmp_reserve"   id="tmp_reserve"   value="<?php echo $reserve ?>">
	<input type="hidden" name="opt_reserve1"  id="opt_reserve1"  value="">
	<input type="hidden" name="opt_reserve2"  id="opt_reserve2"  value="">
	<input type="hidden" name="opt_reserve3"  id="opt_reserve3"  value="">
	<input type="hidden" name="opt_reserve4"  id="opt_reserve4"  value="">
	<input type="hidden" name="opt_reserve8"  id="opt_reserve8"  value="">
	<input type="hidden" name="opt_reserve9"  id="opt_reserve9"  value="">
	<input type="hidden" name="opt_reserve10" id="opt_reserve10" value="">
	<input type="hidden" name="opt_reserve11" id="opt_reserve11" value="">
	<input type="hidden" name="opt_sum"                          value="">
	<!-- <input type="hidden" name="tot_sortnum"   id="tot_sortnum"   value=""> -->
	<input type="hidden" name="optcode_val"    value="">
	<input type="hidden" name="optcode2_val"   id="optcode2_val"   value="">
	<input type="hidden" name="optcode3_val"   id="optcode3_val"   value="">
	<input type="hidden" name="optcode4_val"   id="optcode4_val"   value="">
	<input type="hidden" name="optcode5_val"   id="optcode5_val"   value="">
	<input type="hidden" name="optcode6_val"   id="optcode6_val"   value="">
	<input type="hidden" name="optcode7_val"   id="optcode7_val"   value="">
	<input type="hidden" name="optcode8_val"   id="optcode8_val"   value="">
	<input type="hidden" name="optcode9_val"   id="optcode9_val"   value="">
	<input type="hidden" name="optcode10_val"   id="optcode10_val"   value="">
	<input type="hidden" name="optcode11_val"   id="optcode11_val"   value="">

	<?php if($opttitle != ""){ ?>
	<input type="hidden" name="amount[]" value="">
	<?php } ?>
	<input type="hidden" name="basket_cnt"    id="basket_cnt"    value="0">

	<h4 class="p_name"><?php echo $prdname ?></h4>

	<div class="price_con">
	<table class="view_table" summary="판매가격정보">
		<caption>판매가격정보</caption>
		<tr>
			<th width="20%">판매가</th>
			<td>
				<?php if($prd_row['conprice'] > $prd_row['sellprice']){ ?>
				<span style="text-decoration:line-through;" class="price_j"><?php echo number_format($conprice) ?></span><span class="price_j2">원</span>&nbsp;
				<?php } ?>
				<span class="price_b"><?php echo $sellprice ?></span><span class="price_b2">원</span>
			</td>
		</tr>
		<?php
		if(!empty($wiz_session['id']) && empty($strprice)) {

		$level_info = level_info();
		$level = $level_info[$wiz_session['level']]['name'];

		$lev_sql = "select * from wiz_level where idx = '".$wiz_session['level']."'";
		$lev_result = query($lev_sql);
		$lev_row = sql_fetch_obj($lev_result);

			if($lev_row->discount > 0) {
				if($lev_row->distype == "W") {
					$lev_row->distype = "원";
					$member_price = $lev_row->discount;
				} else {
					//$lev_row->distype = "%";
					$lev_row->distype = "원";
					$member_dis = $lev_row->discount/100;
					$member_price = $prd_row['sellprice']*$member_dis;
				}
		?>
		<tr>
			<th>등급할인액</th>
			<td><?php echo number_format($member_price) ?><?php echo $lev_row->distype ?> [<?php echo $level ?>]<font color="red" size='1'> * 구매 및 장바구니에서 적용됩니다.</font></td>
		</tr>
		<?php
			}
		}
		?>

		<?php

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
				
				if(empty($wiz_session['id'])) {
					$coupon_link = "javascript:alert('로그인 후 쿠폰 발급 및 이용이 가능합니다.');";
				} else {
					$coupon_link = "/twcenter/product/coupon_down.php?prdcode=".$prdcode;
				}
		?>
		<input type="hidden" name="coupon_dis" value="<?php echo $prd_row['coupon_dis'] ?>">
		<input type="hidden" name="coupon_type" value="<?php echo $prd_row['coupon_type'] ?>">

		<tr>
			<th>쿠폰할인액</th>
			<td id="coupon"><?php echo number_format($coupon_price) ?>원 &nbsp;<?php if($coupon_type != '원') echo "(".number_format($prd_row['coupon_dis']).$coupon_type.')&nbsp;' ?><a href="<?php echo $coupon_link?>"><img src="/twcenter/images/coupon_down.gif" border="0"></a></td>
		</tr>
		<?php
			}
		?>

		<?php if($oper_info['reserve_use'] == "Y" && empty($strprice)){ ?>
		<?php /* 2020-05-26 | 임서연 | 옵션 있을때 적립금 항목 노출 가능하도록 주석처리
		if(
			$opttitle  == "" && $opttitle2  == "" && $opttitle3  == "" && $opttitle4 == "" && 
			$opttitle5 == "" && $opttitle6  == "" && $opttitle7  == "" && $opttitle8 == "" && 
			$opttitle9 == "" && $opttitle10 == "" && $opttitle11 == ""){*/
		?>

		<tr>
			<th>적립금</th>
			<td><span id="reserve_default"><?php echo number_format($reserve) ?></span>원</td>
		</tr>
		<?php
		/*	}*/
		}
		?>
	</table>
	</div>

	<div class="viewTable">
	<table class="view_table">
		<?php if($sp_img != ""){ ?>
		<tr>
			<th>상품상태</th>
			<td><?php echo $sp_img ?></td>
		</tr>
		<?php } ?>
		<?php if($prdcom != ""){ ?>
		<tr>
			<th>제조사</th>
			<td><?php echo $prdcom ?></td>
		</tr>
		<?php } ?>
		<?php if($origin != ""){ ?>
		<tr>
			<th>원산지</th>
			<td><?php echo $origin ?></td>
		</tr>
		<?php } ?>

		<?php
		if(!strcmp($info_use, "Y")) {
			for($ii = 1; $ii <= 6; $ii++) {
				if(!empty(${"info_name".$ii})) {
		?>
		<tr>
			<th><?php echo ${"info_name".$ii} ?></th>
			<td><?php echo ${"info_value".$ii} ?></td>
		</tr>
		<?php
				}
			}
		}
		?>

		<?php if(empty($strprice)) { ?>

		<?php
		if(
			$opttitle  == "" && $opttitle2  == "" && $opttitle3  == "" && $opttitle4 == "" && 
			$opttitle5 == "" && $opttitle6  == "" && $opttitle7  == "" && $opttitle8 == "" && 
			$opttitle9 == "" && $opttitle10 == "" && $opttitle11 == ""){
		?>
		<tr>
			<th>수량</th>
			<td><a href="javascript:decAmount();"><img src="/twcenter/images/but_vol_down.gif" alt="수량-" /></a>
			<input type=text name="amount" id="amount" value="1" size="2" class="amout_num2" onkeyup="default_amount();">
			<a href="javascript:incAmount();"><img src="/twcenter/images/but_vol_up.gif" alt="수량+" /></a>
			</td>
		</tr>
		<?php } ?>

		<?php
		if(
			$opttitle  != "" || $opttitle2  != "" || $opttitle3  != "" || $opttitle4 != "" || 
			$opttitle5 != "" || $opttitle6  != "" || $opttitle7  != "" || $opttitle8 != "" || 
			$opttitle9 != "" || $opttitle10 != "" || $opttitle11 != ""){
		?>
		<input type="hidden" name="amount" value="1" onChange="checkAmount();" onKeyUp="checkAmount()" class="amout_num">
		<?php } ?>

		<?php
		if($opt_use == "Y" && (!empty($opttitle) || !empty($opttitle2))){
			if(!empty($opttitle) && !empty($opttitle2)) $opttitle2 = "/".$opttitle2;
		?>
		<tr>
			<th><?php echo $opttitle ?><?php echo $opttitle2 ?></th>
			<td>
			<?php

			$opt1_arr = explode("^", $optcode);
			$opt2_arr = explode("^", $optcode2);
			$opt_tmp  = explode("^^", $optvalue);

			if(count($opt1_arr)-1 < 1) $opt1_cnt = 1;
			else $opt1_cnt = count($opt1_arr) - 1;

			if(count($opt2_arr)-1 < 1) $opt2_cnt = 1;
			else $opt2_cnt = count($opt2_arr) - 1;

			$no = 0;
			$optcode = array();
			$total_opt_stock = 0;
			for($ii = 0; $ii < $opt1_cnt; $ii++) {
				for($jj = 0; $jj < $opt2_cnt; $jj++) {
					list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

					$optcode[$no]['optcode']  = $opt1_arr[$ii];
					if(!empty($opt1_arr[$ii]) && !empty($opt2_arr[$jj])) $optcode[$no]['optcode'] .= "/";
					$optcode[$no]['optcode'] .= $opt2_arr[$jj];
					$optcode[$no]['price']    = $price;
					$optcode[$no]['reserve']  = $reserve;
					$optcode[$no]['stock']    = $stock;
					$total_opt_stock += $optcode[$no]["stock"];
					$no++;
				}
			}

			?>
				<select  name="optcode" id="optcode" onChange="checkOpt01();">
					<option value=""> (필수) 선택하세요 </option>
					<?php
					for($ii=0; $ii<count($optcode); $ii++){

						$sort_num = $ii+1;
						$opt_sub_value = $optcode[$ii]['optcode']."^".$optcode[$ii]['price']."^".$optcode[$ii]['reserve']."^".$optcode[$ii]['stock']."||".$sort_num;

						if($optcode[$ii]['stock'] <= 0) $optcode[$ii]['stock'] = " [품절]";
						else $optcode[$ii]['stock'] = "";

						if($optcode[$ii]['price'] > 0) $optcode[$ii]['price'] = " : ".number_format($optcode[$ii]['price'])."원 추가  ";
						else $optcode[$ii]['price'] = "";

						$opt_sub_txt = $optcode[$ii]['optcode'].$optcode[$ii]['price'].$optcode[$ii]['stock'];

					echo "<option value='$opt_sub_value'>$opt_sub_txt\n";
					}
					?>
				</select>
			</td>
		</tr>
		<?php } ?>

		<?php if($opttitle5 != ""){ ?>
		<tr>
			<th><?php echo $opttitle5 ?></th>
			<td>
				<select name="optcode5"  id="id_optcode5" onChange="OptionAdd('optcode5','5')">
					<option value=""> 선택하세요 </option>
					<?php
					$opt_list = explode(",",$optcode5);
					for($ii=0; $ii<count($opt_list); $ii++){
						$sort_num = "5".$ii;
						$optval = $opt_list[$ii]."^0^0||".$sort_num;
						echo "<option value='".$optval."'>".$opt_list[$ii]."\n";
					}
					?>
				</select>
			</td>
		</tr>
		<?php } ?>

		<?php if($opttitle6 != ""){ ?>
		<tr>
			<th><?php echo $opttitle6 ?></th>
			<td>
				<select name="optcode6" id="id_optcode6" onChange="OptionAdd('optcode6','6')">
					<option value=""> 선택하세요 </option>
					<?php
					$opt_list = explode(",",$optcode6);
					for($ii=0; $ii<count($opt_list); $ii++){
						$sort_num = "6".$ii;
						$optval = $opt_list[$ii]."^0^0||".$sort_num;
						echo "<option value='".$optval."'>".$opt_list[$ii]."\n";
					}
					?>
				</select>
			</td>
		</tr>
		<?php } ?>

		<?php if($opttitle7 != ""){ ?>
		<tr>
			<th><?php echo $opttitle7 ?></th>
			<td>
				<select name="optcode7" id="id_optcode7" onChange="OptionAdd('optcode7','7')">
					<option value=""> 선택하세요 </option>
					<?php
					$opt_list = explode(",",$optcode7);
					for($ii=0; $ii<count($opt_list); $ii++){
						$sort_num = "7".$ii;
						$optval = $opt_list[$ii]."^0^0||".$sort_num;
						echo "<option value='".$optval."'>".$opt_list[$ii]."\n";
					}
					?>
				</select>
			</td>
		</tr>
		<?php } ?>

		<?php if($opttitle3 != ""){ ?>
		<tr>
			<th><?php echo $opttitle3 ?></th>
			<td>
				<select name="optcode3" id="id_optcode3" onChange="OptionAdd('optcode3','3')">
					<option value=""> 선택하세요 </option>
					<?php
					$opt_list = explode("^^",$optcode3);
					for($ii=0; $ii<count($opt_list) - 1; $ii++){
						$sort_num = "3".$ii;
						list($opt, $price, $reserve) = explode("^", $opt_list[$ii]);
						if($price > 0) $price_tmp = " : ".number_format($price)."원 추가";
						else if ($price == 0) $price_tmp = "";
						else if ($price < 0) $price_tmp = " : ".number_format($price * -1)."원 할인";

					echo "<option value='".$opt."^".$price."^".$reserve."||".$sort_num."'>".$opt.$price_tmp."\n";
					}
					?>
				</select>
			</td>
		</tr>
		<?php } ?>

		<?php if($opttitle4 != ""){ ?>
		<tr>
			<th><?php echo $opttitle4 ?></th>
			<td>
				<select name="optcode4" id="id_optcode4" onChange="OptionAdd('optcode4','4')">
					<option value=""> 선택하세요 </option>
					<?php
					$opt_list = explode("^^",$optcode4);
					for($ii=0; $ii<count($opt_list) - 1; $ii++){
						$sort_num = "4".$ii;
						list($opt, $price, $reserve) = explode("^", $opt_list[$ii]);

						if($price > 0) $price_tmp = " : ".number_format($price)."원 추가";
						else if ($price == 0) $price_tmp = "";
						else if ($price < 0) $price_tmp = " : ".number_format($price * -1)."원 할인";

					echo "<option value='".$opt."^".$price."^".$reserve."||".$sort_num."'>".$opt.$price_tmp."\n";
					}
					?>
				</select>
			</td>
		</tr>
		<?php } ?>

		<?php if($opttitle8 != ""){ ?>
		<tr>
			<th><?php echo $opttitle8 ?></th>
			<td>
				<select name="optcode8" id="id_optcode8" onChange="OptionAdd('optcode8','8')">
					<option value=""> 선택하세요 </option>
					<?php
					$opt_list = explode("^^",$optcode8);
					for($ii=0; $ii<count($opt_list) - 1; $ii++){
						$sort_num = "8".$ii;
						list($opt, $price, $reserve) = explode("^", $opt_list[$ii]);

						if($price > 0) $price_tmp = " : ".number_format($price)."원 추가";
						else if ($price == 0) $price_tmp = "";
						else if ($price < 0) $price_tmp = " : ".number_format($price * -1)."원 할인";

					echo "<option value='".$opt."^".$price."^".$reserve."||".$sort_num."'>".$opt.$price_tmp."\n";
					}
					?>
				</select>
			</td>
		</tr>
		<?php } ?>

		<?php if($opttitle9 != ""){ ?>
		<tr>
			<th><?php echo $opttitle9 ?></th>
			<td>
				<select name="optcode9" id="id_optcode9" onChange="OptionAdd('optcode9','9')">
					<option value=""> 선택하세요 </option>
					<?php
					$opt_list = explode("^^",$optcode9);
					for($ii=0; $ii<count($opt_list) - 1; $ii++){
						$sort_num = "9".$ii;
						list($opt, $price, $reserve) = explode("^", $opt_list[$ii]);

						if($price > 0) $price_tmp = " : ".number_format($price)."원 추가";
						else if ($price == 0) $price_tmp = "";
						else if ($price < 0) $price_tmp = " : ".number_format($price * -1)."원 할인";

					echo "<option value='".$opt."^".$price."^".$reserve."||".$sort_num."'>".$opt.$price_tmp."\n";
					}
					?>
				</select>
			</td>
		</tr>
		<?php } ?>

		<?php if($opttitle10 != ""){ ?>
		<tr>
			<th><?php echo $opttitle10 ?></th>
			<td>
				<select name="optcode10" id="id_optcode10" onChange="OptionAdd('optcode10','10')">
					<option value=""> 선택하세요 </option>
					<?php
					$opt_list = explode("^^",$optcode10);
					for($ii=0; $ii<count($opt_list) - 1; $ii++){
						$sort_num = "10".$ii;
						list($opt, $price, $reserve) = explode("^", $opt_list[$ii]);

						if($price > 0) $price_tmp = " : ".number_format($price)."원 추가";
						else if ($price == 0) $price_tmp = "";
						else if ($price < 0) $price_tmp = " : ".number_format($price * -1)."원 할인";

					echo "<option value='".$opt."^".$price."^".$reserve."||".$sort_num."'>".$opt.$price_tmp."\n";
					}
					?>
				</select>
			</td>
		</tr>
		<?php } ?>

		<?php if($opttitle11 != ""){ ?>
		<tr>
			<th><?php echo $opttitle11 ?></th>
			<td>
				<select name="optcode11" id="id_optcode11" onChange="OptionAdd('optcode11','11')">
					<option value=""> 선택하세요 </option>
					<?php
					$opt_list = explode("^^",$optcode11);
					for($ii=0; $ii<count($opt_list) - 1; $ii++){
						$sort_num = "10".$ii;
						list($opt, $price, $reserve) = explode("^", $opt_list[$ii]);

						if($price > 0) $price_tmp = " : ".number_format($price)."원 추가";
						else if ($price == 0) $price_tmp = "";
						else if ($price < 0) $price_tmp = " : ".number_format($price * -1)."원 할인";

					echo "<option value='".$opt."^".$price."^".$reserve."||".$sort_num."'>".$opt.$price_tmp."\n";
					}
					?>
				</select>
			</td>
		</tr>
		<?php } ?>

		<?php } ?>

		<?php
		/*if($prefer != ""){ ?>
		<tr>
			<th>고객선호도</th>
			<td> <img src="/twcenter/images/icon_star_<?php echo $prefer ?>.gif"></td>
		</tr>
		<?// }
		*/ ?>
	</table>
	</div>
	
	<?php if($oper_info['sns_use'] == "Y"){ ?>
	<div class="viewTable">
	<table class="view_table">
		<tr>
			<th width="20%">SNS 스크랩</th>
			<td>
			<?php echo $facebook_btn ?> <?php echo $twitter_btn ?> <?php echo $katalk_btn ?> <?php echo $kastory_btn ?>
			</td>
		</tr>
	</table>
	</div>
	<?php } ?>
	
			
			<table width="100%" border=0 cellpadding=0 cellspacing=0>
				<tr>
				<td style="padding:15px 0;">
					<?php
					if( $opttitle   != "" || $opttitle2  != "" || $opttitle3 != "" || 
						$opttitle4  != "" || $opttitle5  != "" || $opttitle6 != "" || 
						$opttitle7  != "" || $opttitle8  != "" || $opttitle9 != "" ||
						$opttitle10 != "" || $opttitle11 != "" ){
					?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="opt_table">
						<!-- <tr>
							<th align="center">옵션명</th>
							<th align="center" width="25%">수량</th>
							<th align="center" width="14%">옵션가</th>
							<?php if($oper_info['reserve_use'] == "Y" && empty($strprice)){ ?>
							<th align="center" width="14%">적립금</th>
							<?php } ?>
							<th align="center" width="10%"></th>
						</tr> -->
					</table>
					<?php } ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="opt_table" id="basket_table">
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
					<?php
					if( $prd_row['opttitle']   == "" && $prd_row['opttitle2']  == "" && $prd_row['opttitle3'] == "" && 
						$prd_row['opttitle4']  == "" && $prd_row['opttitle5']  == "" && $prd_row['opttitle6'] == "" && 
						$prd_row['opttitle7']  == "" && $prd_row['opttitle8']  == "" && $prd_row['opttitle9'] == "" &&
						$prd_row['opttitle10'] == "" && $prd_row['opttitle11'] == "" ){
					?>
						<tr>
							<td align="right">
								<span>총 상품 금액&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span id="sellprice_default" class="price_b3"><?php echo $sellprice ?></span><span class="price_b2"> 원</span>
								<span style="font-weight:bold; color:#333"></span><span class='txt_11pt'></span>
							</td>
						</tr>
					<?php
					}else{
					?>
						<tr>
							<td align="right">
								<span>총 상품 금액&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span id="total_price_h" class="price_b3">0</span><span class="price_b2"> 원</span>
								<span style="font-weight:bold; color:#333"></span><span class='txt_11pt'></span>
							</td>
						</tr>
					<?}?>
					</table>
				</td>
			</tr>

			<tr>
				<td height="1" bgcolor="#d9d9d9"></td>
			</tr>


			<tr>
			<td style="padding:35px 0 0 0">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td align="right">
							<input class="list_btn" onclick="location.href='<?php echo $_SERVER['PHP_SELF'] ?>?catcode=<?php echo $catcode ?>&brand=<?php echo $brand ?>&page=<?php echo $page ?>&<?php echo $param ?>'" type="button" value="목록으로">
						<?php
						if(empty($strprice)) {
						
							if($opttitle  == "" && $opttitle2 == "" && $opttitle3 == "" && $opttitle4 == "" && 
							   $opttitle5 == "" && $opttitle6 == "" && $opttitle7 == "" && $opttitle8 == "" && 
							   $opttitle9 == "" && $opttitle10 == "" && $opttitle11 == ""){
						?>
								<input class="wish_btn" onclick="javascript:saveWish();" type="button" value="관심상품"><input class="basket_btn" onclick="javascript:saveBasket('basket');" type="button" value="장바구니"><input class="buy_btn" onclick="javascript:saveBasket('buy');" type="button" value="바로구매">
								
						<?php } else { ?>
								<input class="wish_btn" onclick="javascript:ajax_insert_wish('wish');" type="button" value="관심상품"><input class="basket_btn" onclick="javascript:ajax_insert('basket');" type="button" value="장바구니"><input class="buy_btn" onclick="javascript:ajax_insert('buy');" type="button" value="바로구매">
						<?php
							}
						}
						?>	
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<?php
			if($oper_info['nhn_chkout_use'] == "Y"){
			
			$ENABLE = "Y";
			$BUY_BUTTON_HANDLER = "buy_nc";

			if($prd_row['strprice'] != "") $ENABLE = "N";	// 구매금액 설정 X
			if($prd_row['shortage'] == "Y" || (!strcmp($prd_row['shortage'], "S") && $prd_row['stock'] <= 0)) $ENABLE = "N";	// 품절

			if($opt_use == "Y" && (!empty($opttitle) || !empty($opttitle2))){
				if($total_opt_stock == 0) $ENABLE = "N";
			}

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
				<td style="padding:15px 7px;">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td align="right">

				<script type="text/javascript" >//<![CDATA[
					nhn.CheckoutButton.apply({
						BUTTON_KEY:"<?php echo $oper_info['nhn_chkout_key'] ?>", //①
						TYPE: "A",									  //② 
						COLOR: 1,									  //③
						COUNT: 2,									  //④
						ENABLE: "<?php echo $ENABLE ?>",			  //⑤
						BUY_BUTTON_HANDLER: <?=$BUY_BUTTON_HANDLER?>, //⑥
						BUY_BUTTON_LINK_URL:"",						  //⑦
						WISHLIST_BUTTON_HANDLER:wishlist_nc,		  //⑧
						WISHLIST_BUTTON_LINK_URL:"<?php echo WAY_HOST ?>/twcenter/product/nhn_wish.php?prdcode=<?php echo $prdcode ?>", //⑨
						"":""
					});
				//]]></script>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?php } ?>

		</table>
	</form>
</div>


	<!-- 상세 정보-->
	<div id="info" class="bpad_50">
	 <div class="prd_tab">
	  <ul>
		<li><a href="#info" class="prd_tab_up">상품정보</a></li>
		<?php if(!strcmp($oper_info['prdrel_use'], "Y")) { ?><li><a href="#rel">관련상품</a></li><? } ?>
		<?php if(!strcmp($oper_info['qna_usetype'], "Y")) { ?><li><a href="#qna">상품 Q&amp;A <span class="review_num">(<?=$qna_cnt?>)</span></a></li><? } ?>
		<?php if(!strcmp($oper_info['review_usetype'], "Y")) { ?><li><a href="#review">상품후기 <span class="review_num">(<?=@number_format($review_count)?>)</span></a></li><? } ?>
	  </ul>
	</div>
	<?php
	if($site_info['event_coupon_use'] == "Y"){
	?>
		<?php
		if(!empty($wiz_session['id']) && empty($strprice)) {
		
			if($eventcouponuse == "Y"){
				$coupon_sql = "select * from wiz_coupon where idx='$eventcouponidx' ";
				$coupon_result = query($coupon_sql);
				$coupon_info = sql_fetch_arr($coupon_result);

				if(
					$coupon_info['coupon_sdate'] <= date('Y-m-d') &&
					$coupon_info['coupon_edate'] >= date('Y-m-d') &&
					($coupon_info['coupon_limit'] == "N" || ($coupon_info['coupon_limit'] == "" && $coupon_info['coupon_amount'] > 0))
					&& empty($strprice)
				){
					if($coupon_info['coupon_type'] == "%"){
						$coupon_dis = $coupon_info['coupon_dis']/100;
						$coupon_price = $prd_row['sellprice']*$coupon_dis;
					}else{
						$coupon_price = $coupon_info['coupon_dis'];
					}
			?>
				<input type="hidden" name="coupon_dis" value="<?php echo $coupon_info['coupon_dis'] ?>">
				<input type="hidden" name="coupon_type" value="<?php echo $coupon_info['coupon_type'] ?>">

				<?php
				if(is_file("../twcenter/data/coupon/".$coupon_info['coupon_img'])){
				?>
					<a href="/twcenter/product/coupon_down.php?eventidx=<?php echo $eventcouponidx ?>"><img src="/twcenter/data/coupon/<?php echo $coupon_info['coupon_img'] ?>" align="absmiddle" width="200" border="0"></a><br>
		<?php
					}
				}
			}

		}
		?>
	<?php } ?>
	<?php echo $content ?>


	<!--     구매가이드         -->
	<?php //$page_type = "prdview"; include "../inc/page_info.inc"; ?>
	<?php echo $page_info->content ?>
	</div>


	<div id="rel" class="bpad_50">
	<!-- 관련상품 -->
	<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/prd_rel.php"; ?>
	</div>


	<div id="qna" class="bpad_50">
	<!-- 상품 QnA -->
	<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/prd_qna.php"; ?>
	</div>


	<div id="review" class="bpad_50">
	<!-- 상품리뷰 -->
	<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/prd_review.php"; ?>
	</div>

</div>