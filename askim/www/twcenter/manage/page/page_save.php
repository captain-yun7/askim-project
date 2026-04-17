<?php include_once "../../common.php"; ?>
<?php include_once "../../inc/twcenter_check.php"; ?>
<?php
//if(!get_magic_quotes_gpc()) $content= addslashes($content);

if(!empty($_SERVER['HTTP_REFERER'])) {
	$pos = strpos($_SERVER['HTTP_REFERER'], SSL.$_SERVER['HTTP_HOST']);
	if($pos === false) {
		alert("올바른 접근이 아닙니다.");
		exit;
	}
}else{
	alert("올바른 접근이 아닙니다.");
	exit;
}


if($mode == "insert"){

	$pageinfo_cnt = sql_fetch("select count(code) as code_cnt from wiz_page where code='".$_POST['code']."' ");
	if($pageinfo_cnt['code_cnt'] > 0) error('이미등록된 페이지 코드입니다');

	$sql_com = "";
	$sql_com .= " code                 = '$code'                      ";
	$sql_com .= " , title              = '$title'                     ";
	$sql_com .= " , menu               = '$menu'                      ";
	$sql_com .= " , url                = '$url'                       ";
	$sql_com .= " , level              = '$level'                     ";
	$sql_com .= " , content            = '$content'                   ";
	$sql_com .= " , prior              = '$prior'                     ";
	$sql_com .= " , searchkey          = '$searchkey'                 ";
	$sql_com .= " , searchkey_de       = '$searchkey_de'              ";
	$sql_com .= " , searchkey_cl       = '$searchkey_cl'              ";
	$sql_com .= " , browser_title      = '$browser_title'             ";
	$sql_com .= " , wdate              = now()                        ";

	$sql = "INSERT INTO wiz_page SET {$sql_com} ";
	query($sql);

	complete("추가되었습니다.","page_list.php?$menucodeParam");

// 수정
}else if($mode == "update"){

	$sql_com = "";
	$sql_com .= " title                = '$title'                     ";
	$sql_com .= " , menu               = '$menu'                      ";
	$sql_com .= " , url                = '$url'                       ";
	$sql_com .= " , level              = '$level'                     ";
	$sql_com .= " , content            = '$content'                   ";
	$sql_com .= " , prior              = '$prior'                     ";
	$sql_com .= " , searchkey          = '$searchkey'                 ";
	$sql_com .= " , searchkey_de       = '$searchkey_de'              ";
	$sql_com .= " , searchkey_cl       = '$searchkey_cl'              ";
	$sql_com .= " , browser_title      = '$browser_title'             ";

	$sql = "UPDATE wiz_page SET {$sql_com} WHERE idx = '$idx' ";

	query($sql);

	complete("수정되었습니다.","page_input.php?mode=update&idx=$idx&page=$page&$menucodeParam");

// 삭제
}else if($mode == "delete"){

	$sql = "delete from wiz_page where idx = '$idx'";
	query($sql);

	complete("삭제되었습니다.","page_list.php?$menucodeParam");

} else if(!strcmp($mode, "grpinsert")) {
	$sql = "insert into wiz_page_grp set grpname='$grpname', prior='$prior'";
	if(query($sql)) {
		grp_update("페이지그룹 정보가 등록되었습니다", "group.php");
	} else {
		error("오류가 발생하였습니다.");
	}

} else if(!strcmp($mode, "grpupdate") && $idx) {
	$sql = "update wiz_page_grp set grpname='$grpname', prior='$prior' where idx='$idx'";
	if(query($sql)) {
		grp_update("페이지그룹 정보가 수정되었습니다", $_SERVER['HTTP_REFERER']);
	} else {
		error("오류가 발생하였습니다.");
	}

} else if(!strcmp($mode, "grpdelete")) {
	$sql = "delete from wiz_page_grp where idx='$idx' ";
	if(query($sql)) {
		grp_update("페이지그룹 정보가 삭제되었습니다", "group.php");
	} else {
		error("오류가 발생하였습니다.");
	}

} else if ($mode == "grp_prior" && $idx && $prior) {
	$sql = "update wiz_page_grp set prior='$prior' where idx='$idx'";
	if(query($sql)) {
		grp_update("우선순위가 변경되었습니다.", "group.php");
	} else {
		error("오류가 발생하였습니다.");
	}

} else if ($mode == "set_grp_prior") {
	$json_data = json_decode(stripslashes($_POST['json_data']),1);
	foreach($json_data as $data) {
		list($idx, $prior) = $data;
		$sql = "update wiz_page_grp set prior='$prior' where idx='$idx'";
		if(!query($sql)) {
			error("우선순위 변경 중 오류가 발생하였습니다.", $_SERVER['HTTP_REFERER']);
		}
	}
	grp_update("우선순위가 변경되었습니다.", "group.php");

} else if($mode == "get_grplist") {
	echo json_encode(get_grplist("page"));
}

?>