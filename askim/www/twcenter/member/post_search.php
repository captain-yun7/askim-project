<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/mem_info.php';

$list = get_zipcode_list($address); $search_count = count($list);
?>
<html>
<head>
<title>:: 우편번호 검색 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="<?=$skin_dir?>/style.css">
<script language="JavaScript">
<!--
function setAddr(zipcode1, zipcode2 , addr){
	var frm;
	for(i=0;i<opener.document.forms.length;i++){
		frm = opener.document.forms[i];
		if(frm.<?=$kind?>post1){
			frm.<?=$kind?>post1.value = zipcode1;
			frm.<?=$kind?>post2.value = zipcode2;
			frm.<?=$kind?>address1.value = addr;
			if(frm.<?=$kind?>address2 != null) frm.<?=$kind?>address2.focus();
		}
		if(frm.<?=$kind?>address) {
			frm.<?=$kind?>address.value = addr;
		}
	}
	self.close();
}
//-->
</script>
</head>

<body onLoad="document.frm.address.focus();" topmargin="0" leftmargin="0">

<?
include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/post_head.php';

for ($i=0; $i<count($list); $i++) {

	$post1 		= $list[$i][zip1];
	$post2 		= $list[$i][zip2];
	$set_addr = $list[$i][set_addr];
	$address	= $list[$i][addr];

	include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/post_body.php';

}

if($address != "" && $search_count <= 0){
	echo "<table width='100%'><tr><td align=center>- 찾으시는 주소가 없습니다. 다시 입력하세요.</td></tr></table>";
}

include $_SERVER['DOCUMENT_ROOT'].'/$skin_dir/post_foot.php';

?>

</body>
</html>