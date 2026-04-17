<?php include_once "../../common.php"; ?>
<?php include_once "../../inc/admin_check.php"; ?>
<?php

$param = "page=".$page."&searchopt=".$searchopt."&searchkey=".$searchkey."&".$menucodeParam;

// 설문입력
if($mode == "insert"){
	
	$mainskin = "	
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
	<tr><td><b>{SUBJECT}</b></td></tr>
	<tr><td>{CONTENT}</td></tr>
	[LOOP]
	<tr><td><img src=\"/twcenter/bbsmain/image/point.gif\" align=\"absmiddle\"> {QUESTION}</td></tr>
	[LOOP2]
	<tr><td> {ANSWER} </td></tr>
	[/LOOP2]
	[/LOOP]
	<tr><td height=5></td></tr>
	<tr><td align=center>{VOTE_BTN} {VIEW_BTN}</td></tr>
	</table>";

	$sql_com = "";
	$sql_com .= " code                 = '$code'                      ";
	$sql_com .= " , title              = '$title'                     ";
	$sql_com .= " , lpermi             = '$lpermi'                    ";
	$sql_com .= " , rpermi             = '$rpermi'                    ";
	$sql_com .= " , apermi             = '$apermi'                    ";
	$sql_com .= " , cpermi             = '$cpermi'                    ";
	$sql_com .= " , skin               = '$skin'                      ";
	$sql_com .= " , permsg             = '$permsg'                    ";
	$sql_com .= " , perurl             = '$perurl'                    ";
	$sql_com .= " , mainskin           = '$mainskin'                  ";
	$sql_com .= " , purl               = '$purl'                      ";
	$sql_com .= " , wdate              = now()                        ";
	$sql_com .= " , datetype_list      = '$datetype_list'             ";
	$sql_com .= " , datetype_view      = '$datetype_view'             ";
	$sql_com .= " , poll_rows               = '$poll_rows'                      ";
	$sql_com .= " , lists              = '$lists'                     ";
	$sql_com .= " , newc               = '$newc'                      ";
	$sql_com .= " , subject_len        = '$subject_len'               ";
	$sql_com .= " , spam_check         = '$spam_check'                ";
	$sql_com .= " , comment            = '$comment'                   ";
	$sql_com .= " , abuse              = '$abuse'                     ";
	$sql_com .= " , abtxt              = '$abtxt'                     ";
	$sql_com .= " , browser_title      = '$browser_title'             ";
	$sql_com .= " , searchkey_de       = '$searchkey_de'              ";
	$sql_com .= " , searchkey_cl       = '$searchkey_cl'              ";
	$sql_com .= " , searchkey          = '$searchkey'                 ";

	$sql = "INSERT INTO wiz_pollinfo SET {$sql_com} ";
	query($sql);

	complete("설문을 추가 하였습니다.","pollinfo_input.php?mode=update&code=$code&menucode=$menucode");

// 설문수정
}else if($mode == "update"){

	$sql_com = "";
	$sql_com .= " title                = '$title'                     ";
	$sql_com .= " , lpermi             = '$lpermi'                    ";
	$sql_com .= " , rpermi             = '$rpermi'                    ";
	$sql_com .= " , apermi             = '$apermi'                    ";
	$sql_com .= " , cpermi             = '$cpermi'                    ";
	$sql_com .= " , skin               = '$skin'                      ";
	$sql_com .= " , permsg             = '$permsg'                    ";
	$sql_com .= " , perurl             = '$perurl'                    ";
	$sql_com .= " , datetype_list      = '$datetype_list'             ";
	$sql_com .= " , datetype_view      = '$datetype_view'             ";
	$sql_com .= " , poll_rows               = '$poll_rows'                      ";
	$sql_com .= " , lists              = '$lists'                     ";
	$sql_com .= " , newc               = '$newc'                      ";
	$sql_com .= " , subject_len        = '$subject_len'               ";
	$sql_com .= " , spam_check         = '$spam_check'                ";
	$sql_com .= " , comment            = '$comment'                   ";
	$sql_com .= " , abuse              = '$abuse'                     ";
	$sql_com .= " , abtxt              = '$abtxt'                     ";
	$sql_com .= " , browser_title      = '$browser_title'             ";
	$sql_com .= " , searchkey_de       = '$searchkey_de'              ";
	$sql_com .= " , searchkey_cl       = '$searchkey_cl'              ";
	$sql_com .= " , searchkey          = '$searchkey'                 ";

	$sql = "UPDATE wiz_pollinfo SET {$sql_com} WHERE code = '$code' ";
	query($sql);

	complete("설문을 수정 하였습니다.","pollinfo_input.php?mode=$mode&code=$code&$param");

// 설문삭제
}else if($mode == "delete"){

	$sql = "delete from wiz_pollinfo where code = '$code'";
	query($sql);

	complete("설문을 삭제 하였습니다.","pollinfo_list.php?$param");

}

?>