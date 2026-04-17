<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/prd_info.php';

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

// 상품정보 가져오기
$sql = "select * from wiz_product where prdcode='$prdcode'";
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);
$prd_info = sql_fetch($sql);
if($prdcode == "" || $total <= 0) error("존재하지 않는 상품입니다.");

if(!empty($prd_info["strprice"])) $sellprice = $prd_info["strprice"];
else $sellprice = number_format($prd_info["sellprice"])."원";

// 인기,신상,추천...
if($prd_info["popular"] == "Y") $sp_img .= "<img src='/twcenter/images/icon_hit.gif'>&nbsp;";
if($prd_info["recom"] == "Y") $sp_img .= "<img src='/twcenter/images/icon_rec.gif'>&nbsp;";
if($prd_info["new"] == "Y") $sp_img .= "<img src='/twcenter/images/icon_new.gif'>&nbsp;";
if($prd_info["sale"] == "Y"){ $sp_img .= "<img src='/twcenter/images/icon_sale.gif'>&nbsp;"; }
if($prd_info["shortage"] == "Y" || (!strcmp($prd_info["shortage"], "S") && $prd_info["stock"] <= 0)) $sp_img .= "<img src='/twcenter/images/icon_not.gif'>&nbsp;";

// 상품상세 이미지 보기 증가
$sql = "update wiz_product set deimgcnt = if(deimgcnt is null, 1, deimgcnt + 1) where prdcode = '$prdcode'";
query($sql) or error("sql error");

// 상품 이미지
for($ii = 1; $ii <= 5; $ii++) {
	if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$prd_info["prdimg_L".$ii])) $prd_info["prdimg_L".$ii] = "/twcenter/images/noimg_M.gif";
	else $prd_info["prdimg_L".$ii] = "/twcenter/data/prdimg/".$prd_info["prdimg_L".$ii];
}

?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<title>상품 확대이미지 보기</title>
<link rel="stylesheet" type="text/css" href="/wiz_style.css">
</head>

<body topmargin="0" leftmargin="0">

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
	<tr>
		<td align="center"><img src="<?=$prd_info["prdimg_L".$imgno]?>" name="prdimg" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>

</body>
</html>