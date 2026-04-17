<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/mem_info.php';

if(strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
	error("잘못된 접근입니다.");
}
$sql = "select nick from wiz_member where nick='$nick'";
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);

if($nick != ""){
	if($total > 0){
		$checkmsg = "<span><strong>".$nick."</strong> 는 이미 사용중인 닉네임 입니다.</span>";
	} else{

		$nick = strip_tags(strtolower($nick));
		$filterid = explode(",", trim($mem_info['prohibit_id']));

		for($i=0; $i<count($filterid); $i++) {
			$f_string = $filterid[$i];
			$pos = strpos($nick, $f_string);

			if($pos !== false) {
				$res = 'true';
			}
		}

		if($res == "true"){
			$checkmsg = "<span><strong>".$nick."</strong> 는 사용할 수 없는 닉네임 입니다.</span>";
		} else {
			$checkmsg = "<span><strong>".$nick."</strong> 는 사용가능한 닉네임 입니다.</span> <a href='javascript:setNick();' class='btn_wb'>닉네임 사용하기</a>";
		}

	}
}else{
	$checkmsg = "사용하고자 하는 닉네임을 입력하세요";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>:: 닉네임 중복 체크 ::</title>
<link href="<?=$skin_dir?>/style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/twcenter/js/lib.js"></script>
<script language="JavaScript">
<!--
// 입력값 체크
function nickCheck(frm){
	
	if(frm.nick.value.length < 3 || frm.nick.value.length > 12){
		alert("닉네임은 3 ~ 12자리만 가능합니다.");
		frm.nick.focus();
		return false;
	}

}

// 닉네임 입력폼으로 전송
function setNick(){

	var frm;
	for(i=0;i<opener.document.forms.length;i++){
		frm = opener.document.forms[i];
		if(frm.nick){
			frm.nick.value = '<?=$nick?>';
		}
	}
	self.close();
	
}
//-->
</script>
</head>
<body onLoad="document.frm.nick.focus();" topmargin="0" leftmargin="0">

<? include $_SERVER['DOCUMENT_ROOT'].'/'.$skin_dir.'/nick_check.php'; ?>

</body>
</html>