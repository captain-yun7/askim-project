<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/twcenter_check.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";

	$server = $site_info['alimtalk_temp_url'];
	$senderKey = $site_info['alimtalk_senderkey'];
	$custGubun = $site_info['alimtalk_custgubun'];

if($mode == "insert"){

	$templateCode = $_alimtalk_id."_".$_POST['templateCode'];
	$templateName = "[".$site_info['site_name']."] ".$_POST['templateName'];
	$templateContent = $_POST['templateContent'];

	$templateName_conv = $templateName;
	$templateContent_conv = $templateContent;

	if($templateCode=="" || $templateName=="" || $templateContent==""){
		echo "<script>alert('필수값이 입력되지 않았습니다.');location.href='talk_list.php'</script>";
		exit;
	}

	//템플릿 등록
	$url = $server."/api/v1/".$custGubun."/template/create";

	$post_data = "";
	$post_data .= "senderKey=".$senderKey;
	$post_data .= "&templateCode=".$templateCode;
	//$post_data .= "&templateName=".$templateName;
	$post_data .= "&templateName=".$templateName_conv;
	$post_data .= "&templateContent=".$templateContent_conv;

	//버튼
	if($btn_name) {
		$post_data .= "&buttons[0].ordering=1";  /*버튼 표시 순서*/
		$post_data .= "&buttons[0].linkType=WL";
		$post_data .= "&buttons[0].name=".$btn_name;
		$post_data .= "&buttons[0].linkMo=".$linkMo;
		$post_data .= "&buttons[0].linkPc=".$linkPc; 
	}
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt ($ch, CURLOPT_HTTPHEADER,array ('Content-Type: application/x-www-form-urlencoded;charset=UTF-8')); 
	$ch_result = curl_exec ($ch);
	$json_response = json_decode($ch_result, true);

	$return_code = $json_response['code'];

	if($return_code=="200"){
		$sql_in = "";
		$sql_in .= " senderKey                        = '$senderKey'                        ";
		$sql_in .= " , templateCode                   = '$templateCode'                     ";
		$sql_in .= " , templateName                   = '$templateName'                     ";
		$sql_in .= " , templateContent                = '$templateContent'                  ";
		$sql_in .= " , templateShow                   = 'Y'                                 ";
		$sql_in .= " , btn_name                       = '$btn_name'                         ";
		$sql_in .= " , linkPc                         = '$linkPc'                           ";
		$sql_in .= " , linkMo                         = '$linkMo'                           ";
		$sql = "INSERT INTO wiz_talk SET {$sql_in} ";
		query($sql);

		echo "<script>alert('등록되었습니다.');location.href='talk_list.php'</script>";
	}else{
		if($json_response['message']) $return_msg = $json_response['message'];
		else $return_msg = templete_error_code($return_code);
		echo "<script>alert('error_code:".$return_msg."');history.back(-1)</script>";
	}

}else if($mode == "update"){
	$idx                = $_POST['idx'];
	$templateCode       = $_POST['templateCode'];
	$newTemplateCode    = $_POST['newTemplateCode'];
	$newTemplateName    = $_POST['newTemplateName'];
	$newTemplateContent = $_POST['newTemplateContent'];

	$newTemplateCode_conv    = $newTemplateCode;
	$newTemplateName_conv    = $newTemplateName;
	$newTemplateContent_conv = $newTemplateContent;

	if($newTemplateCode=="" || $newTemplateName=="" || $newTemplateContent==""){
		echo "<script>alert('필수값이 입력되지 않았습니다.');location.href='talk_list.php'</script>";
		exit;
	}

	//템플릿 수정
	$url = $server."/api/v1/".$custGubun."/template/update";

	$post_data = "";
	$post_data .= "senderKey=".$senderKey;
	$post_data .= "&templateCode=".$templateCode;
	$post_data .= "&newSenderKey=".$senderKey;
	$post_data .= "&newTemplateCode=".$newTemplateCode_conv;
	$post_data .= "&newTemplateName=".$newTemplateName_conv;
	$post_data .= "&newTemplateContent=".$newTemplateContent_conv;

	if($btn_name) {
		$post_data .= "&buttons[0].ordering=1";  //버튼 표시 순서
		$post_data .= "&buttons[0].linkType=WL";
		$post_data .= "&buttons[0].name=".$btn_name;
		$post_data .= "&buttons[0].linkMo=".$linkMo;
		$post_data .= "&buttons[0].linkPc=".$linkPc; 
	}

	$ch = curl_init ();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt ($ch, CURLOPT_HTTPHEADER,array ('Content-Type: application/x-www-form-urlencoded;charset=UTF-8')); 
	$ch_result = curl_exec ($ch);
	$json_response = json_decode($ch_result, true);

	$return_code = $json_response['code'];

	if($return_code=="200"){

		$sql_up = "";
		$sql_up .= " templateName               = '$newTemplateName'                        ";
		$sql_up .= " , templateContent          = '$newTemplateContent'                     ";
		$sql_up .= " , btn_name                 = '$btn_name'                               ";
		$sql_up .= " , linkPc                   = '$linkPc'                                 ";
		$sql_up .= " , linkMo                   = '$linkMo'                                 "; 

		$sql = "UPDATE wiz_talk SET {$sql_up} WHERE idx = '$idx' ";
		query($sql);

		echo "<script>alert('수정되었습니다.');location.href='talk_list.php'</script>";
	}else{
		echo "<script>alert('error_code:".templete_error_code($return_code)."');history.back(-1)</script>";
	}

}else if($mode=="delete"){

	$sql_talk = "select * from wiz_talk where idx='$idx' ";
	$result_talk = query($sql_talk);
	$row_talk = sql_fetch_arr($result_talk);

	//템플릿 승인
	$url = $server."/api/v1/".$custGubun."/template/delete";

	$post_data = "";
	$post_data .= "senderKey=".$senderKey;
	$post_data .= "&templateCode=".$row_talk['templateCode'];


	$ch = curl_init ();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt ($ch, CURLOPT_HTTPHEADER,array ('Content-Type: application/x-www-form-urlencoded;charset=UTF-8')); 
	$ch_result = curl_exec ($ch);
	$json_response = json_decode($ch_result, true);

	$return_code = $json_response['code'];

	if($return_code=="200"){
		$sql_del = "delete from wiz_talk where idx='$idx' ";
		query($sql_del);

		echo "<script>alert('삭제되었습니다.');location.href='talk_list.php'</script>";
	}else{
		echo "<script>alert('error_code:".templete_error_code($return_code)."');location.href='talk_list.php'</script>";
	}

}else if($mode=="tmpshow"){

	$sql_del = "update wiz_talk set templateShow = 'N' where idx='$idx' ";
	query($sql_del);

	echo "<script>alert('해당 템플릿이 미노출로 전환되었습니다.');location.href='talk_list.php'</script>";

}
?>