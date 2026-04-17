<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

/*
if(strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
	error("잘못된 접근입니다.");
}
*/

session_destroy();

/* 
 * ssl일경우 크로스도메인때문에 부모창으로 사용가능한 아이디값을 전달하지 못하므로
 * 세션에 저장 후 리다이렉트하는 방식으로 크로스도메인을 방지한다.
 * ==============================================
 */

if($_POST['id'] != ""){
	$_SESSION['id'] = $_POST['id'];
} else {
	$id = $_SESSION['id'];
}

/*
if($_SERVER['HTTPS'] == "on"){

	if($_SERVER['HTTPS'] == "on"){
		$host_url = substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],":"));
		echo "<script>document.location='http://".$host_url."/twcenter/member/id_check.php';</script>";
		exit();
	}
}
*/
/* 
 * ==============================================
 */

$sql = "select count(id) as cnt from wiz_member where id='$id'";
$result = query($sql);
$row = sql_fetch_arr($result);
$total = $row['cnt'];

$sql = "select count(id) as cnt from wiz_admin where id = '$id'";
$result = query($sql);
$row2 = sql_fetch_arr($result);
$total2 = $row2['cnt'];

$sql = "select count(designer_id) as cnt from wiz_siteinfo where designer_id  = '$id' or anywiz_id = '".md5($id)."'";
$result = query($sql);
$row3 = sql_fetch_arr($result);
$total3 = $row3['cnt'];

if($id != ""){
	if($total > 0){
		$checkmsg = "<span><font color=#00BCBC><b>".$id."</b></font> 는 이미 사용중인 아이디 입니다.</span>";
	} else if($total2 + $total3 > 0) {
		$checkmsg = "<span><font color=#00BCBC><b>".$id."</b></font> 는 사용할 수 없는 아이디 입니다.</span>";
	} else {

	//	check_prohibit($id);
		$id = strip_tags(strtolower($id));
		$filterid = explode(",", trim($mem_info['prohibit_id']));

		for($i=0; $i<count($filterid); $i++) {
			$f_string = $filterid[$i];
			$pos = strpos($id, $f_string);

			if($pos !== false) {
				$res = 'true';
			}
		}

		if($res == "true"){
			$checkmsg = "<span><font color=#00BCBC><b>".$id."</b></font> 는 사용할 수 없는 아이디 입니다.</span>";
		} else {
			$checkmsg = "<span><font color=#00BCBC><b>".$id."</b></font> 는 사용가능한 아이디 입니다.</span><a href='javascript:setId();' class='btn_wb'>아이디 사용하기</a>";
		}

	}

}else{
	$checkmsg = "사용하고자 하는 아이디를 입력하세요";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>:: 아이디 중복 체크 ::</title>
<link href="<?php echo $skin_dir ?>/style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/twcenter/js/lib.js"></script>
<script language="JavaScript">
<!--
// 입력값 체크
function idCheck(frm){

	var idchk       = frm.id.value;
	var idReg       = /^[a-z]+[a-z0-9]{2,11}$/g;

	if(!idReg.test(idchk)) {
		alert("아이디는 영문 또는 영문+숫자조합으로 3~12자리만 가능합니다.");
		return false;
	}

	if(idchk.search(/\s/) != -1){
		alert("아이디는 공백없이 입력해주세요.");
		return false;
	}
	
	if(!check_Char(frm.id.value)){
		alert("아이디는 특수문자를 사용할수 없습니다.");
		frm.id.value = "";
		frm.id.focus();
		return false;
	}

}

// 아이디 입력폼으로 전송
function setId(){
	opener.joinFrm.id.value = '<?php echo $id ?>';
	opener.joinFrm.passwd1.focus();
	self.close();
}
//-->
</script>
</head>
<body onLoad="document.frm.id.focus();" topmargin="0" leftmargin="0">

<?php include $_SERVER['DOCUMENT_ROOT']."/".$skin_dir."/id_check.php"; ?>
</body>
</html>