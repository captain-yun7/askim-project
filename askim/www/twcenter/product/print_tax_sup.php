<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

// 주문정보
$sql = "select status from wiz_order where orderid = '$orderid'";
$result = query($sql) or error("sql error");
$order_info = sql_fetch_arr($result);

$shop_name 		= $site_info['com_name'];
$shop_owner 	= $site_info['com_owner'];
$shop_num			= $site_info['com_num'];
$shop_address	= $site_info['com_address'];
$shop_kind 		= $site_info['com_kind'];
$shop_class		= $site_info['com_class'];
$shop_tel			= $site_info['com_tel'];
$shop_email		= $site_info['site_email'];

// 세금계산서 승인
if($agree_tax == "ok"){
	$sql = "update wiz_tax set tax_pub='Y', wdate = now(),
					shop_name='$shop_name',shop_owner='$shop_owner',shop_num='$shop_num',shop_address='$shop_address',shop_kind='$shop_kind',shop_class='$shop_class',shop_tel='$shop_tel',shop_email='$shop_email'
					where orderid='$orderid'";
	query($sql) or error("sql error");
}

// 세금계산서 정보
$sql = "select * from wiz_tax where orderid = '$orderid'";
$result = query($sql) or error("sql error");
$tax_info = sql_fetch_arr($result);

// 쇼핑몰 사업자 정보
if(!strcmp($tax_info['tax_pub'], "Y")) {
	$shop_name 		= $tax_info['shop_name'];
	$shop_owner 	= $tax_info['shop_owner'];
	$shop_num			= $tax_info['shop_num'];
	$shop_address	= $tax_info['shop_address'];
	$shop_kind 		= $tax_info['shop_kind'];
	$shop_class		= $tax_info['shop_class'];
	$shop_tel			= $tax_info['shop_tel'];
	$shop_email		= $tax_info['shop_email'];
}

// 고객 사업자정보
if($tax_info['com_num'] != ""){
	$com_num = str_replace("-","",$tax_info['com_num']);
	$com_num[0] = substr($com_num,0,1);
	$com_num[1] = substr($com_num,1,1);
	$com_num[2] = substr($com_num,2,1);
	$com_num[3] = substr($com_num,3,1);
	$com_num[4] = substr($com_num,4,1);
	$com_num[5] = substr($com_num,5,1);
	$com_num[6] = substr($com_num,6,1);
	$com_num[7] = substr($com_num,7,1);
	$com_num[8] = substr($com_num,8,1);
	$com_num[9] = substr($com_num,9,1);
}

$supp_price_len = strlen($tax_info['supp_price']);
$supp_price_blank = 11-$supp_price_len;
for($ii=1;$ii<=11&&$ii<=$supp_price_len;$ii++){
	$supp_price_arr[$ii] = substr($tax_info['supp_price'],$supp_price_len-$ii,1);
}

$tax_price_len = strlen($tax_info['tax_price']);
for($ii=1;$ii<=10&&$ii<=$tax_price_len;$ii++){
	$tax_price_arr[$ii] = substr($tax_info['tax_price'],$tax_price_len-$ii,1);
}

$tax_info['sum_price'] = $tax_info['supp_price']+$tax_info['tax_price'];			// 합계금액
list($edate_year,$edate_month,$edate_day) = explode("-",$tax_info['tax_date']);

?>
<html>
<head>
<title>전자세금계산서 - 공인인증</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script language="javascript">
<!--
function agreeTax(){

	<?
	if($tax_info['com_num'] == "" ||
		$tax_info['com_name'] == "" ||
		$tax_info['com_owner'] == "" ||
		$tax_info['com_address'] == "" ||
		$tax_info['com_kind'] == "" ||
		$tax_info['com_class'] == ""){
	?>
	alert("사업자정보가 정확하지 않습니다.\n\n회원정보에서 사업자정보를 정확히 입력 후 발급받으세요.");
	return;
	<?
	}
	?>

	<?
	if($tax_info['tax_price'] <= 0){
	?>
	alert("부가세가 없습니다.\n\n세금계산서를 발급받으시려면 관리자에게 문의하세요.");
	return;
	<?
	}
	?>

<?php
$print_tax_check = false;
$status = order_status($order_info['status']);
$tax_status = order_status($oper_info['tax_status']);

if(!strcmp($order_info['status'], "OC") || !strcmp($order_info['status'], "RD") || !strcmp($order_info['status'], "RC")) {
?>
	alert("주문취소로 세금계산서가 폐기되었습니다.");
<?php
} else {

	if(!strcmp($oper_info['tax_status'], "OY")) {
		if(strcmp($order_info['status'], "OR")) {
			$print_tax_check = true;
		}
	} else if(!strcmp($oper_info['tax_status'], "DC")) {
		if(!strcmp($order_info['status'], "DC") || !strcmp($order_info['status'], "CC")) {
			$print_tax_check = true;
		}
	}

	if($print_tax_check) {
?>
	
	if(confirm('세금계산서를 승인 하시겠습니까?')){
		document.location = '<?=$PHP_SELF?>?orderid=<?=$orderid?>&agree_tax=ok';
	}

<?php
	} else {
?>
	alert("현재 주문상태(<?=$status?>)에서는 세금계산서를 승인할 수 없습니다. \n\n<?=$tax_status?> 이후에 세금계산서 승인이 가능합니다.");
<?php
	}
}
?>

}
function printTax(){
<?php
$print_tax_check = false;
$status = order_status($order_info['status']);
$tax_status = order_status($oper_info['tax_status']);

if(!strcmp($order_info['status'], "OC") || !strcmp($order_info['status'], "RD") || !strcmp($order_info['status'], "RC")) {
?>
	alert("주문취소로 세금계산서가 폐기되었습니다.");
<?php
} else {
?>
	var url = "print_tax_sup.php?orderid=<?=$orderid?>&print_tax=ok";
	window.open(url, "printTax", "height=550, width=660, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=50, top=50");
<?php
}
?>
}
-->
</script>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" oncontextmenu="return false"
ondragstart="return false" onselectstart="return false" <? if($print_tax == "ok") echo "onLoad='window.print()'"; ?>>

<? if($print_tax == "ok"){ ?>
<div style='position:absolute; left:260px;top: 110px;'><img src=/twcenter/data/config/com_seal.gif width=70 height=70></div>
<? } ?>

<div style='DISPLAY:block; position:relative; left:10;top:10;'>
<table align="center" width="97%" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td align="center">	<style>
	<!--
	 /* Font Definitions */
	@font-face
		{font-family:굴림;
		panose-1:2 11 6 0 0 1 1 1 1 1;
		mso-font-alt:Gulim;
		mso-font-charset:129;
		mso-generic-font-family:modern;
		mso-font-pitch:variable;
		mso-font-signature:-1342176593 1775729915 48 0 524447 0;}
	@font-face
		{font-family:굴림체;
		panose-1:2 11 6 9 0 1 1 1 1 1;
		mso-font-charset:129;
		mso-generic-font-family:modern;
		mso-font-pitch:fixed;
		mso-font-signature:-1342176593 1775729915 48 0 524447 0;}
	@font-face
		{font-family:"\@굴림";
		panose-1:2 11 6 0 0 1 1 1 1 1;
		mso-font-charset:129;
		mso-generic-font-family:modern;
		mso-font-pitch:variable;
		mso-font-signature:-1342176593 1775729915 48 0 524447 0;}
	@font-face
		{font-family:"\@굴림체";
		panose-1:2 11 6 9 0 1 1 1 1 1;
		mso-font-charset:129;
		mso-generic-font-family:modern;
		mso-font-pitch:fixed;
		mso-font-signature:-1342176593 1775729915 48 0 524447 0;}
	 /* Style Definitions */
	p.MsoNormal, li.MsoNormal, div.MsoNormal
		{mso-style-parent:"";
		margin:0cm;
		margin-bottom:.0001pt;
		text-align:justify;
		text-justify:inter-ideograph;
		mso-pagination:none;
		text-autospace:none;
		word-break:break-hangul;
		font-size:9.0pt;
		mso-bidi-font-size:12.0pt;
		font-family:굴림;
		mso-hansi-font-family:"Times New Roman";
		mso-bidi-font-family:"Times New Roman";
		mso-font-kerning:1.0pt;}
	 /* Page Definitions */
	@page
		{mso-page-border-surround-header:no;
		mso-page-border-surround-footer:no;}
	@page Section1
		{size:595.3pt 841.9pt;
		margin:99.25pt 3.0cm 3.0cm 3.0cm;
		mso-header-margin:42.55pt;
		mso-footer-margin:49.6pt;
		mso-paper-source:0;
		layout-grid:18.0pt;}
	div.Section1
		{page:Section1;}
	-->
	</style>

	<div class=Section1 style='layout-grid:18.0pt'>

	<table border=1 cellspacing=0 cellpadding=0 align=left width=621
	 style='width:465.45pt;border-collapse:collapse;border:none;mso-border-alt:
	 solid red .5pt;mso-table-overlap:never;mso-table-lspace:7.1pt;mso-table-rspace:
	 7.1pt;mso-table-anchor-vertical:paragraph;mso-table-anchor-horizontal:margin;
	 mso-table-left:-5.3pt;mso-table-top:9.2pt;mso-padding-alt:2px 2px 0px 0px'>
	 <tr style='height:9.35pt'>
	  <td width=334 colspan=26 style='width:250.2pt;border:none;border-bottom:solid red 2.25pt;
	  padding:2px 2px 0px 0px;height:9.35pt'>	  <p class=MsoNormal style='layout-grid-mode:char;mso-layout-grid-align:none;
	  mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>별지
	  제<span lang=EN-US>11호서식</span></span></p>	  </td>
	  <td width=287 colspan=36 style='width:215.25pt;border:none;border-bottom:
	  solid red 2.25pt;padding:2px 2px 0px 0px;height:9.35pt'>
	  <p class=MsoNormal align=right style='text-align:right;layout-grid-mode:char;
	  mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:7.1pt;
	  mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:
	  margin;mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  lang=EN-US style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;
	  color:red'>(적색)</span></p>
	  </td>
	 </tr>
	 <tr>
	  <td width=399 colspan=33 rowspan=2 style='width:299.2pt;border-top:none;
	  border-left:solid red 2.25pt;border-bottom:solid red .5pt;border-right:
	  none;mso-border-top-alt:solid red 2.25pt;padding:2px 2px 0px 0px'>	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><b><span
	  style='font-size:18.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>세금계산서</span></b><span
	  lang=EN-US style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;
	  color:red'>(공급자보관용)</span></p>	  </td>
	  <td width=62 colspan=8 style='width:46.5pt;border:none;border-right:solid red .5pt;
	  mso-border-top-alt:solid red 2.25pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>책
	  번 호</span></p>
	  </td>
	  <td width=70 colspan=10 style='width:52.65pt;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red 2.25pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=right style='text-align:right;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><font color='#000000'></font><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>권<span
	  lang=EN-US></span></span></p>
	  </td>
	  <td width=89 colspan=11 style='width:67.1pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red 2.25pt;mso-border-top-alt:
	  solid red 2.25pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=right style='text-align:right;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><font color='#000000'></font><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>호<span
	  lang=EN-US></span></span></p>
	  </td>
	 </tr>
	 <tr align="center">
	  <td width=62 colspan=8 style='width:46.5pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>일련번호<span
	  lang=EN-US></span></span></p>
	  </td>
	  <td width=23 colspan=4 style='width:17.55pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td width=23 colspan=4 style='width:17.55pt;border:solid red .5pt;
	  border-left:none;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td width=23 colspan=2 style='width:17.55pt;border:solid red .5pt;
	  border-left:none;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  lang=EN-US style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;
	  color:red'>-</span></p>
	  </td>
	  <td width=22 colspan=4 style='width:16.75pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td width=22 colspan=2 style='width:16.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	 <font color='#000000'></font>
	  </td>
	  <td width=22 colspan=3 style='width:16.75pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td width=22 colspan=2 style='width:16.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red 2.25pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	 </tr>
	 <tr align='center'>
	  <td width=24 colspan=2 rowspan=4 style='width:17.75pt;border-top:none;
	  border-left:solid red 2.25pt;border-bottom:solid red .5pt;border-right:
	  solid red .5pt;mso-border-top-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>공</span></p>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span lang=EN-US style='font-size:8.0pt;
	  mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'></span></p>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>급</span></p>	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span lang=EN-US style='font-size:8.0pt;
	  mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'></span></p>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>자</span></p>
	  </td>
	  <td width=55 colspan=3 style='width:41.0pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>등록번호<span
	  lang=EN-US></span></span></p>
	  </td>
	  <td width=214 colspan=16 align="center" style='width:160.8pt;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=$shop_num?></font>
	  </td>
	  <td width=22 colspan=3 rowspan=4 style='width:16.45pt;border-top:none;
	  border-left:none;border-bottom:solid red .5pt;border-right:solid red .5pt;
	  mso-border-top-alt:solid red .5pt;mso-border-left-alt:solid red .5pt;
	  padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>공급</span></p>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>받</span></p>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>는</span></p>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>자</span></p>
	  </td>
	  <td width=53 colspan=5 style='width:39.75pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>등록번호<span
	  lang=EN-US></span></span></p>
	  </td>
	  <td width=21 colspan=2 style='width:15.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=$com_num[0]?></font>
	  </td>
	  <td width=21 colspan=3 style='width:15.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	 <font color='#000000'><?=$com_num[1]?></font>
	  </td>
	  <td width=21 colspan=2 style='width:15.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=$com_num[2]?></font>
	  </td>
	  <td width=21 colspan=4 style='width:15.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  lang=EN-US style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;
	  color:red'>-</span></p>
	  </td>
	  <td width=21 colspan=3 style='width:15.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=$com_num[3]?></font>
	  </td>
	  <td width=21 colspan=4 style='width:15.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=$com_num[4]?></font>
	  </td>
	  <td width=21 colspan=3 style='width:15.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  lang=EN-US style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;
	  color:red'>-</span></p>
	  </td>
	  <td width=21 colspan=2 style='width:15.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=$com_num[5]?></font>
	  </td>
	  <td width=21 colspan=4 style='width:15.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=$com_num[6]?></font>
	  </td>
	  <td width=21 colspan=2 style='width:15.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=$com_num[7]?></font>
	  </td>
	  <td width=21 colspan=3 style='width:15.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=$com_num[8]?></font>
	  </td>
	  <td width=21 style='width:15.9pt;border-top:none;border-left:none;border-bottom:
	  solid red .5pt;border-right:solid red 2.25pt;mso-border-top-alt:solid red .5pt;
	  mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=$com_num[9]?></font>
	  </td>
	 </tr>
	 <tr>
	  <td width=55 colspan=3 style='width:41.0pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>상<span lang=EN-US><span
	  style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp; </span>호</span></span></p>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span lang=EN-US style='font-size:6.0pt;
	  mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>(법인명)</span></p>
	  </td>
	  <td align="center" width=111 colspan=8 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly;width:82.9pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:4px'>
	  <font color='#000000'><?=$shop_name?></font>
	  </td>
	  <td width=36 colspan=3 style='width:27.2pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>성명</span></p>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span lang=EN-US style='font-size:6.0pt;
	  mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>(대표자)</span></p>
	  </td>
	  <td align="left" width=68 colspan=5 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly;width:50.7pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:4px'>
	  <font color='#000000'><?=$shop_owner?></font>
	  </td>
	  <td width=53 colspan=5 style='width:39.75pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>상<span lang=EN-US><span
	  style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp; </span>호</span></span></p>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span lang=EN-US style='font-size:6.0pt;
	  mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>(법인명)</span></p>
	  </td>
	  <td align="center" width=134 colspan=19 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly;width:100.45pt;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:4px'>
	  <font color='#000000'><?=$tax_info['com_name']?></font>
	  </td>
	  <td width=38 colspan=6 style='width:28.7pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>성명</span></p>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span lang=EN-US style='font-size:6.0pt;
	  mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>(대표자)</span></p>
	  </td>
	  <td align="center" width=81 colspan=8 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly;width:60.55pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red 2.25pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:4px'>
	  <font color='#000000'><?=$tax_info['com_owner']?></font>
	  </td>
	 </tr>
	 <tr>
	  <td width=55 colspan=3 style='width:41.0pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>사 업 장</span></p>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>소 재 지</span></p>
	  </td>
	  <td width=214 colspan=16 style='width:160.8pt;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:4px'>
	  <p class=MsoNormal style='layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$shop_address?></font></p>
	  </td>
	  <td width=53 colspan=5 style='width:39.75pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>사 업 장</span></p>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>소 재 지</span></p>
	  </td>
	  <td width=253 colspan=33 style='width:189.7pt;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red 2.25pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:4px'>
	  <p class=MsoNormal style='layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_info['com_address']?></font></p>
	  </td>
	 </tr>
	 <tr style='height:17.6pt'>
	  <td width=55 colspan=3 style='width:41.0pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:17.6pt'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>업<span
	  lang=EN-US><span style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp; </span>태</span></span></p>
	  </td>
	  <td width=74 colspan=6 style='width:66.5pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:4px;
	  height:17.6pt'>
	  <p class=MsoNormal style='layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$shop_kind?></font></p>
	  </td>
	  <td width=37 colspan=3 style='width:27.4pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:17.6pt'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>종목<span
	  lang=EN-US></span></span></p>
	  </td>
	  <td width=104 colspan=7 style='width:66.9pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:4px;
	  height:17.6pt'>
	  <p class=MsoNormal style='layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$shop_class?></font></p>
	  </td>
	  <td width=53 colspan=5 style='width:39.75pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:17.6pt'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>업<span
	  lang=EN-US><span style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp; </span>태</span></span></p>
	  </td>
	  <td width=75 colspan=15 style='width:56.55pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:4px;
	  height:17.6pt'>
	  <p class=MsoNormal style='layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_info['com_kind']?></font></p>
	  </td>
	  <td width=34 colspan=4 style='width:25.65pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:17.6pt'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>종목<span
	  lang=EN-US></span></span></p>
	  </td>
	  <td width=143 colspan=14 style='width:107.5pt;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red 2.25pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:4px;
	  height:17.6pt'>
	  <p class=MsoNormal style='layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_info['com_class']?></font></p>
	  </td>
	 </tr>
	 <tr style='height:20.15pt'>
	  <td width=87 colspan=6 style='width:64.95pt;border-top:none;border-left:solid red 2.25pt;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;padding:2px 2px 0px 0px;height:20.15pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>작<span lang=EN-US><span
	  style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp; </span>성</span></span></p>
	  </td>
	  <td width=223 colspan=17 style='width:167.0pt;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:20.15pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>공<span lang=EN-US><span
	  style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp;&nbsp; </span>급<span
	  style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp;&nbsp; </span>가<span
	  style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp;&nbsp; </span>액</span></span></p>
	  </td>	  <td width=177 colspan=23 style='width:132.65pt;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:20.15pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>세<span lang=EN-US><span
	  style="mso-spacerun:
	  yes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>액</span></span></p>
	  </td>
	  <td width=134 colspan=16 style='width:100.85pt;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red 2.25pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:20.15pt'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>비<span
	  lang=EN-US><span style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp;&nbsp; </span>고</span></span></p>
	  </td>	 </tr>
	 <tr style='height:14.9pt'>
	  <td width=40 colspan=3 style='width:29.95pt;border-top:none;border-left:solid red 2.25pt;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;padding:2px 2px 0px 0px;height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>년</span></p>
	  </td>
	  <td width=23 style='width:17.5pt;border-top:none;border-left:none;border-bottom:
	  solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:solid red .5pt;
	  mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>월</span></p>
	  </td>
	  <td width=23 colspan=2 style='width:17.5pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>일</span></p>
	  </td>
	  <td width=28 style='width:21.1pt;border-top:none;border-left:none;border-bottom:
	  solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:solid red .5pt;
	  mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:6.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>공란수</span></p>
	  </td>
	  <td width=18 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>백</span></p>
	  </td>
	  <td width=18 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>십</span></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>억</span></p>
	  </td>
	  <td width=18 style='width:13.3pt;border-top:none;border-left:none;border-bottom:
	  solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:solid red .5pt;
	  mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>천</span></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>백</span></p>
	  </td>
	  <td width=18 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>십</span></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.3pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>만</span></p>
	  </td>
	  <td width=18 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>천</span></p>
	  </td>
	  <td width=18 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>백</span></p>
	  </td>
	  <td width=18 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>십</span></p>
	  </td>
	  <td width=18 colspan=3 style='width:13.3pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal style='layout-grid-mode:char;mso-layout-grid-align:none;
	  mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>일<span
	  lang=EN-US></span></span></p>
	  </td>	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>십</span></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>억</span></p>
	  </td>
	  <td width=18 style='width:13.3pt;border-top:none;border-left:none;border-bottom:
	  solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:solid red .5pt;
	  mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>천</span></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>백</span></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>십</span></p>
	  </td>
	  <td width=18 colspan=3 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>만</span></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.3pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>천</span></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>백</span></p>
	  </td>
	  <td width=18 colspan=3 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>십</span></p>
	  </td>
	  <td width=18 colspan=4 style='width:13.3pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:14.9pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>일</span></p>
	  </td>
	  <td width=134 colspan=16 rowspan=2 style='width:100.85pt;border-top:none;
	  border-left:none;border-bottom:solid red .5pt;border-right:solid red 2.25pt;
	  mso-border-top-alt:solid red .5pt;mso-border-left-alt:solid red .5pt;
	  padding:2px 2px 0px 0px;height:14.9pt'>
	  <p class=MsoNormal align=center style='layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly;text-align:center;'><font color='#000000'></font></p>
	  </td>	 </tr>
	 <tr style='height:26.5pt'>
	  <td width=40 colspan=3 style='width:29.95pt;border-top:none;border-left:solid red 2.25pt;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;padding:2px 2px 0px 0px;height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$edate_year?></font></p>
	  </td>
	  <td width=23 style='width:17.5pt;border-top:none;border-left:none;border-bottom:
	  solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:solid red .5pt;
	  mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$edate_month?></font></p>
	  </td>
	  <td width=23 colspan=2 style='width:17.5pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$edate_day?></font></p>
	  </td>
	  <td width=28 style='width:21.1pt;border-top:none;border-left:none;border-bottom:
	  solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:solid red .5pt;
	  mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$supp_price_blank?></font><span
	  lang=EN-US style='font-size:6.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;
	  color:red'></span></p>
	  </td>
	  <td width=18 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$supp_price_arr[11]?></font></p>
	  </td>
	  <td width=18 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$supp_price_arr[10]?></font></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$supp_price_arr[9]?></font></p>
	  </td>
	  <td width=18 style='width:13.3pt;border-top:none;border-left:none;border-bottom:
	  solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:solid red .5pt;
	  mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$supp_price_arr[8]?></font></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$supp_price_arr[7]?></font></p>
	  </td>
	  <td width=18 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$supp_price_arr[6]?></font></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.3pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$supp_price_arr[5]?></font></p>
	  </td>
	  <td width=18 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$supp_price_arr[4]?></font></p>
	  </td>
	  <td width=18 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$supp_price_arr[3]?></font></p>
	  </td>
	  <td width=18 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$supp_price_arr[2]?></font></p>
	  </td>
	  <td width=18 colspan=3 style='width:13.3pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$supp_price_arr[1]?></font></p>
	  </td>	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_price_arr[10]?></font></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_price_arr[9]?></font></p>
	  </td>
	  <td width=18 style='width:13.3pt;border-top:none;border-left:none;border-bottom:
	  solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:solid red .5pt;
	  mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_price_arr[8]?></font></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_price_arr[7]?></font></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_price_arr[6]?></font></p>
	  </td>
	  <td width=18 colspan=3 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_price_arr[5]?></font></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.3pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_price_arr[4]?></font></p>
	  </td>
	  <td width=18 colspan=2 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_price_arr[3]?></font></p>
	  </td>
	  <td width=18 colspan=3 style='width:13.25pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_price_arr[2]?></font></p>
	  </td>
	  <td width=18 colspan=4 style='width:13.3pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:26.5pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><font color='#000000'><?=$tax_price_arr[1]?></font></p>
	  </td>	 </tr>
	 <tr style='height:17.85pt'>
	  <td width=20 style='width:14.95pt;border-top:none;border-left:solid red 2.25pt;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;padding:2px 2px 0px 0px;height:17.85pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>월</span></p>
	  </td>
	  <td width=20 colspan=2 style='width:15.0pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:17.85pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>일</span></p>
	  </td>
	  <td width=146 colspan=9 style='width:109.15pt;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:17.85pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>품<span lang=EN-US><span
	  style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp;&nbsp; </span>목</span></span></p>
	  </td>
	  <td width=53 colspan=5 style='width:39.8pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:17.85pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>규<span lang=EN-US><span
	  style="mso-spacerun: yes">&nbsp;&nbsp; </span>격</span></span></p>
	  </td>
	  <td width=35 colspan=2 style='width:26.5pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:17.85pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>수량</span></p>
	  </td>
	  <td width=71 colspan=8 style='width:53.05pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:17.85pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>단<span lang=EN-US><span
	  style="mso-spacerun: yes">&nbsp; </span>가</span></span></p>
	  </td>
	  <td width=106 colspan=12 style='width:79.6pt;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:17.85pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>공급가액</span></p>
	  </td>	  <td width=85 colspan=13 style='width:64.1pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:17.85pt'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>세<span
	  lang=EN-US><span style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp; </span>액</span></span></p>
	  </td>
	  <td width=84 colspan=10 style='width:63.3pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red 2.25pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:17.85pt'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>비<span
	  lang=EN-US><span style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp; </span>고</span></span></p>
	  </td>	 </tr>
<?php
$prd_info = explode("^^", $tax_info['prd_info']);
$no = 0;
for($ii = 0; $ii < count($prd_info); $ii++) {

	if(!empty($prd_info[$ii])) {
		$tmp_prd = explode("^", $prd_info[$ii]);
		if($ii < 1) $prd_name = $tmp_prd[0];
		$no++;
	}
}

if($no > 1) {
	$prd_name .= " 외 ".($no-1)."건";
}
?>
	 <tr>
	  <td align='center' style='border-top:none;border-left:solid red 2.25pt;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;padding:2px 2px 0px 0px; height:24px'>
	  <font color='#000000'><?=$edate_month?></font>
	  </td>
	  <td align='center' colspan=2 style='border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=$edate_day?></font>
	  </td>
	  <td width=146 colspan=9 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=$prd_name?></font>
	  </td>
	  <td colspan=5 width=53 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=2 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=8 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=12 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=number_format($tax_info['supp_price'])?></font>
	  </td>	  <td colspan=13 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'><?=number_format($tax_info['tax_price'])?></font>
	  </td>
	  <td colspan=10 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red 2.25pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>	 </tr>
	 <tr>
	  <td align='center' style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:solid red 2.25pt;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;padding:2px 2px 0px 0px; height:24px'>
	  <font color='#000000'></font>
	  </td>
	  <td align='center' colspan=2 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td width=146 colspan=9 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=5 width=53 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=2 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=8 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=12 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>	  <td colspan=13 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=10 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red 2.25pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>	 </tr>
	 <tr>
	  <td align='center' style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:solid red 2.25pt;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;padding:2px 2px 0px 0px; height:24px'>
	  <font color='#000000'></font>
	  </td>
	  <td align='center' colspan=2 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td width=146 colspan=9 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=5 width=53 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=2 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=8 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>

	  </td>
	  <td colspan=12 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>	  <td colspan=13 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=10 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red 2.25pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>	 </tr>
	 <tr>
	  <td align='center' style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:solid red 2.25pt;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;padding:2px 2px 0px 0px; height:24px'>
	  <font color='#000000'></font>
	  </td>
	  <td align='center' colspan=2 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td width=146 colspan=9 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=5 width=53 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=2 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=8 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=12 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>	  <td colspan=13 align=right style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>
	  <td colspan=10 style='layout-grid-mode:
	  char;mso-layout-grid-align:none;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red 2.25pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px'>
	  <font color='#000000'></font>
	  </td>	 </tr>
	 <tr>
	  <td width=115 colspan=7 style='width:86.05pt;border-top:none;border-left:
	  solid red 2.25pt;border-bottom:solid red .5pt;border-right:solid red .5pt;
	  mso-border-top-alt:solid red .5pt;padding:2px 2px 0px 0px;height:11.6pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>합 계 금 액</span></p>
	  </td>
	  <td width=88 colspan=7 style='width:66.3pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:11.6pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>현<span lang=EN-US><span
	  style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>금</span></span></p>
	  </td>
	  <td width=88 colspan=6 style='width:66.3pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:11.6pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>수<span lang=EN-US><span
	  style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>표</span></span></p>
	  </td>
	  <td width=88 colspan=10 style='width:66.35pt;border-top:none;border-left:
	  none;border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:11.6pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>어<span lang=EN-US><span
	  style="mso-spacerun: yes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>음</span></span></p>
	  </td>
	  <td width=88 colspan=12 style='width:66.3pt;border-top:none;border-left:none;
	  border-bottom:solid red .5pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:11.6pt'>
	  <p class=MsoNormal align=center style='text-align:center;layout-grid-mode:
	  char;mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:
	  7.1pt;mso-element-wrap:around;mso-element-anchor-vertical:paragraph;
	  mso-element-anchor-horizontal:margin;mso-element-left:-5.25pt;mso-element-top:
	  9.2pt;mso-height-rule:exactly'><span style='font-size:8.0pt;mso-bidi-font-size:
	  12.0pt;font-family:굴림체;color:red'>외상미수금</span></p>
	  </td>
	  <td width=84 colspan=12 rowspan=2 style='width:62.65pt;border:none;
	  border-bottom:solid red 2.25pt;mso-border-top-alt:solid red .5pt;
	  mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;height:11.6pt'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>위
	  금액을</span></p>
	  </td>
	  <td width=37 colspan=5 rowspan=2 style='width:27.65pt;border:none;border-bottom:solid red 2.25pt;
	  padding:2px 2px 0px 0px;height:10.7pt'>
	  <p class=MsoNormal align=right style='text-align:right;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>영수<br><b><u>청구</u></b><span
	  lang=EN-US></span></span></p>
	  </td>
	  <td width=32 colspan=3 rowspan=2 style='width:23.85pt;border-top:none;
	  border-left:none;border-bottom:solid red 2.25pt;border-right:solid red 2.25pt;
	  mso-border-top-alt:solid red .5pt;padding:2px 2px 0px 0px;height:11.6pt'>
	  <p class=MsoNormal align=center style='text-align:center;mso-layout-grid-align:
	  none;mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;color:red'>함<span
	  lang=EN-US>.</span></span></p>
	  </td>
	 </tr>
	 <tr>
	  <td width=115 colspan=7 style='text-align:right;width:86.05pt;border-top:none;border-left:
	  solid red 2.25pt;border-bottom:solid red 2.25pt;border-right:solid red .5pt;
	  mso-border-top-alt:solid red .5pt;padding:2px 2px 0px 0px;height:10.7pt'>
	  <font color='#000000'><?=number_format($tax_info['sum_price'])?></font>
	  </td>
	  <td width=88 colspan=7 style='text-align:right;width:66.3pt;border-top:none;border-left:none;
	  border-bottom:solid red 2.25pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:10.7pt'>
	  <font color='#000000'><?=number_format($tax_info['sum_price'])?></font>
	  </td>
	  <td width=88 colspan=6 style='text-align:right;width:66.3pt;border-top:none;border-left:none;
	  border-bottom:solid red 2.25pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:10.7pt'>
	  <font color='#000000'></font>
	  </td>
	  <td width=88 colspan=10 style='text-align:right;width:66.3pt;border-top:none;border-left:none;
	  border-bottom:solid red 2.25pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:10.7pt'>
	  <font color='#000000'></font>
	  </td>
	  <td width=88 colspan=12 style='text-align:right;width:66.3pt;border-top:none;border-left:none;
	  border-bottom:solid red 2.25pt;border-right:solid red .5pt;mso-border-top-alt:
	  solid red .5pt;mso-border-left-alt:solid red .5pt;padding:2px 2px 0px 0px;
	  height:10.7pt'>
	  <font color='#000000'></font>
	  </td>
	 </tr>
	 <tr>
	  <td width=298 colspan=22 style='width:223.35pt;border:none;mso-border-top-alt:
	  solid red 2.25pt;padding:2px 2px 0px 0px;height:16.75pt'>	  <p class=MsoNormal style='layout-grid-mode:char;mso-layout-grid-align:none;
	  mso-element:frame;mso-element-frame-hspace:7.1pt;mso-element-wrap:around;
	  mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:margin;
	  mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  lang=EN-US style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;
	  color:red'>22226-28132일 96. 3. 27 승인</span></p>	  </td>
	  <td width=323 colspan=40 style='width:242.1pt;border:none;mso-border-top-alt:
	  solid red 2.25pt;padding:2px 2px 0px 0px;height:16.75pt'>	  <p class=MsoNormal align=right style='text-align:right;layout-grid-mode:char;
	  mso-layout-grid-align:none;mso-element:frame;mso-element-frame-hspace:7.1pt;
	  mso-element-wrap:around;mso-element-anchor-vertical:paragraph;mso-element-anchor-horizontal:
	  margin;mso-element-left:-5.25pt;mso-element-top:9.2pt;mso-height-rule:exactly'><span
	  lang=EN-US style='font-size:8.0pt;mso-bidi-font-size:12.0pt;font-family:굴림체;
	  color:red'>182mm x 128mm 인쇄용지특급 34g/㎡</span></p>	  </td>
	 </tr>
	 <![if !supportMisalignedColumns]>
	 <tr height=0>
	  <td width=20 style='border:none'></td>
	  <td width=4 style='border:none'></td>
	  <td width=16 style='border:none'></td>
	  <td width=23 style='border:none'></td>
	  <td width=15 style='border:none'></td>
	  <td width=8 style='border:none'></td>
	  <td width=28 style='border:none'></td>
	  <td width=18 style='border:none'></td>
	  <td width=18 style='border:none'></td>
	  <td width=2 style='border:none'></td>
	  <td width=15 style='border:none'></td>
	  <td width=18 style='border:none'></td>
	  <td width=3 style='border:none'></td>
	  <td width=14 style='border:none'></td>
	  <td width=18 style='border:none'></td>
	  <td width=4 style='border:none'></td>
	  <td width=13 style='border:none'></td>
	  <td width=18 style='border:none'></td>
	  <td width=18 style='border:none'></td>
	  <td width=18 style='border:none'></td>
	  <td width=1 style='border:none'></td>
	  <td width=5 style='border:none'></td>
	  <td width=11 style='border:none'></td>
	  <td width=5 style='border:none'></td>
	  <td width=12 style='border:none'></td>
	  <td width=7 style='border:none'></td>
	  <td width=11 style='border:none'></td>
	  <td width=18 style='border:none'></td>
	  <td width=5 style='border:none'></td>
	  <td width=12 style='border:none'></td>
	  <td width=9 style='border:none'></td>
	  <td width=9 style='border:none'></td>
	  <td width=1 style='border:none'></td>
	  <td width=11 style='border:none'></td>
	  <td width=6 style='border:none'></td>
	  <td width=16 style='border:none'></td>
	  <td width=2 style='border:none'></td>
	  <td width=10 style='border:none'></td>
	  <td width=8 style='border:none'></td>
	  <td width=1 style='border:none'></td>
	  <td width=9 style='border:none'></td>
	  <td width=7 style='border:none'></td>
	  <td width=5 style='border:none'></td>
	  <td width=4 style='border:none'></td>
	  <td width=7 style='border:none'></td>
	  <td width=2 style='border:none'></td>
	  <td width=8 style='border:none'></td>
	  <td width=8 style='border:none'></td>
	  <td width=6 style='border:none'></td>
	  <td width=7 style='border:none'></td>
	  <td width=16 style='border:none'></td>
	  <td width=5 style='border:none'></td>
	  <td width=4 style='border:none'></td>
	  <td width=12 style='border:none'></td>
	  <td width=2 style='border:none'></td>
	  <td width=4 style='border:none'></td>
	  <td width=19 style='border:none'></td>
	  <td width=2 style='border:none'></td>
	  <td width=10 style='border:none'></td>
	  <td width=9 style='border:none'></td>
	  <td width=1 style='border:none'></td>
	  <td width=21 style='border:none'></td>
	 </tr>
	 <![endif]>
	</table>
	</div></td>
</tr>
</table>

<? if($print_tax != "ok"){ ?>

<table width="650" border="0" cellpadding="0" cellspacing="2" bgcolor="blue">
<tr>
	<td bgcolor="#FFFFFF">
		<table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="blue">
		<tr><td align="center" bgcolor="#FFFFFF" colspan="2">공급자</td><td align="center" bgcolor="#FFFFFF" colspan="2">공급받는자</td></tr>
		<tr align="center" bgcolor="#FFFFFF" height="22">
			<td width="15%" class="tax_blue">담&nbsp;당&nbsp;자</td>
			<td width="35%" align="left"><?=$shop_owner?></td>
			<td width="15%" class="tax_blue">담&nbsp;당&nbsp;자</td>
			<td width="35%" align="left"><?=$tax_info['com_owner']?></td>
		</tr>
		<tr align="center" bgcolor="#FFFFFF" height="22">
			<td class="tax_blue">전화번호</td>
			<td align="left"><?=$shop_tel?></td>
			<td class="tax_blue">전화번호</td>
			<td align="left"><?=$tax_info['com_tel']?></td>
		</tr>
		<tr align="center" bgcolor="#FFFFFF" height="22">
			<td class="tax_blue">이&nbsp;메&nbsp;일</td>
			<td align="left"><?=$shop_email?></td>
			<td class="tax_blue">이&nbsp;메&nbsp;일</td>
			<td align="left"><?=$tax_info['com_email']?></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<table width="621">
	<tr><td height="10"></td></tr>
	<tr>
			<? if($tax_info['tax_pub'] == "" || $tax_info['tax_pub'] == "N"){ ?>
			<td width="50%" align="right"><input type="button" value=" &nbsp; 승 &nbsp; 인 &nbsp; " onClick="agreeTax();"><td width="50%"><font color=red>(승인 후 <b>출력</b>하실 수 있습니다.)</font></td>
			<? }else{ ?>
			<td align="center"><input type="button" value=" &nbsp; 인 &nbsp; 쇄 &nbsp; " onClick="printTax();"></td>
			<? } ?>
		</td>

	</tr>
</table>

<? } ?>
</div>


</body>
</html>

<span style='filter:alpha(opacity=30, style=5, finishopacity=30); position:absolute; left:240px; top:250px;'>
<font color='red' style='font-size:40pt; font-family: 궁서'>
<b>
	<?
	if($tax_info['tax_pub'] == "" || $tax_info['tax_pub'] == "N"){
		echo "확인요청";
	}else if(!strcmp($order_info['status'], "OC") || !strcmp($order_info['status'], "RD") || !strcmp($order_info['status'], "RC")) {
		echo "폐 기";
	}else{
		if($print_tax != "ok") echo "승 인";
	}
	?>
</b>
</font>
</span>