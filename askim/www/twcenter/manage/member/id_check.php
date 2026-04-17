<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

$sql = "
	select count(id) as total 
	  from wiz_member 
	 where id='".$id."'
";
$t_row = sql_fetch($sql);
$total = $t_row['total'];

$sql = "
	select count(id) as total2 
	  from wiz_admin 
	 where id = '".$id."'
";
$t_row = sql_fetch($sql);
$total2 = $t_row['total2'];

$sql = "
	select count(designer_id) as total3 
	  from wiz_siteinfo 
	 where designer_id  = '".$id."' 
	    or anywiz_id = '".md5($id)."'
";
$t_row = sql_fetch($sql);
$total3 = $t_row['total3'];

if($id != ""){
	if($total > 0){
		$checkmsg = "<font color=#A90329><b>".$id."</b></font> 는 이미 사용중인 아이디 입니다.";
	} else if($total2 + $total3 > 0) {
		$checkmsg = "<font color=#A90329><b>".$id."</b></font> 는 사용할 수 없는 아이디 입니다.";
	} else{

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
			$checkmsg = "<font color=#A90329><b>".$id."</b></font> 는 사용할 수 없는 아이디 입니다.";
		} else {
			$checkmsg = "<font color=#2DA7FE><b>".$id."</b></font> 는 사용가능한 아이디 입니다. <input type='button' value='확인' class='base_btm reg' onClick='setId();'>";
		}
	
	}

}else{
	$checkmsg = "사용하고자 하는 아이디를 입력하세요";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>아이디 중복체크</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>
<script language="JavaScript" src="../../js/lib.js"></script>
<script language="JavaScript">
<!--

// 입력값 체크
function inputCheck(frm){

	var idchk   = frm.id.value;
	var alpha   = idchk.search(/^[a-z]/ig);
	var alpha2  = idchk.search(/[A-Z]/ig);
	var number  = idchk.search(/[0-9]/g);

	if(idchk.length < 3 || idchk.length > 12){
		alert("아이디는 3~12자리의 영문, 숫자만 가능합니다.");
		return false;
	}

	if(idchk.search(/\s/) != -1){
		alert("아이디는 공백없이 입력해주세요.");
		return false;
	}
	
	/*
	if(number < 0 || alpha < 0 || alpha2 < 0){
		alert("영대문/소문자, 숫자조합으로 입력해주세요.");
		return false;
	}
	*/

	if(!check_Char(frm.id.value)){
		alert("아이디는 특수문자를 사용할수 없습니다.");
		frm.id.value = "";
		frm.id.focus();
		return false;
	}

}
// 아이디 입력폼으로 전송
function setId(){
	opener.frm.<?=$name?>.value = '<?php echo $id ?>';
	self.close();
}
//-->
</script>
</head>

<body onLoad="document.frm.id.focus();">

<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">아이디검색</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>	
<table width="100%" cellpadding=10 cellspacing=0>
	<tr>
		<td>
			<form name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return inputCheck(this)">
			<input type=hidden name=name value="<?php echo $name ?>">
			<table width="100%"cellpadding=2 cellspacing=1 class="t_style">
				<tr>
					<td width="110" class="t_name">아이디</td>
					<td class="t_value">
						<input type="text" name="id" class="input" size="28" value="<?=$id?>">
						<input type="submit" value="검색" class="base_btn4 blue">
					</td>
				</tr>
			</table>
			</form>
			<br>
			<table border=0 cellpadding=2 cellspacing=0 width=100% bgcolor=#ffffff align=center>
				<tr>
					<td colspan="2" align="center"><?php echo $checkmsg ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>