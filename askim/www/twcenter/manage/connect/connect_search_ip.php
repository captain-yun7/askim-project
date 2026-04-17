<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include $_SERVER['DOCUMENT_ROOT'].'/comm/API/json/JSON.php'; ?>
<?
$json = new Services_JSON();
$url = "http://whois.kisa.or.kr/openapi/whois.jsp?query=".$query."&key=".$site_info['ipkisakey']."&answer=json";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($ch);
$obj  = json_decode($data);

curl_close($ch);

$s_ip           = $obj->whois->query;
$s_queryType    = $obj->whois->queryType;
$s_registry     = $obj->whois->registry;
$s_countryCode  = $obj->whois->countryCode;

$_country = sql_fetch("SELECT kname FROM wiz_country WHERE isoalpha2='{$s_countryCode}' ");

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/twcenter/js/jquery-1.8.3.min.js"></script>
</head>
<body>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td></td>
	</tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">접근아이피 분석</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">인터넷진흥원(KISA)에서 제공되는 API연동모듈입니다.</td>
	</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="1" class="t_style">
	<tr>
		<td width="20%" align="center" class="t_name">검색IP</td>
		<td width="15%" align="center" class="t_name">QueryType</td>
		<td width="20%" align="center" class="t_name">아이피등록기관</td>
		<td width="15%" align="center" class="t_name">국가코드</td>
		<td width="30%" align="center" class="t_name">국가명</td>
	</tr>
	<tr height="30">
		<td align="center"><?=$s_ip?></td>
		<td align="center"><?=$s_queryType?></td>
		<td align="center"><?=$s_registry?></td>
		<td align="center"><?=$s_countryCode?></td>
		<td align="center"><?=$_country['kname']?></td>
	</tr>
</table>
<br>
<table width="100%" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td colspan="2" align="center">
				<img src="../image/btn_close_l.gif" style="cursor:pointer" onClick="self.close();">
		</td>
	</tr>
</table>

</body>
</html>