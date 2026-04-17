<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/form_info.php"; ?>
<?
$param = "code=".$code."&searchopt=".$searchopt."&searchkey=".$searchkey."&searchstatus=".$searchstatus."&".$menucodeParam;

// 폼메일 수정
if($mode == "update"){

	$sql = "select code, reply, content from wiz_form where idx = '$idx'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_arr($result);
	
	$content= addslashes($content);
	$reply = $content;
	
	if($smail == "Y" && $reply != "") {
		
		include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";
		
		$sql2 = "select idx from wiz_forminfo where code = '".$row['code']."'";	
		$result2 = query($sql2) or error("sql error");
		$row2 = sql_fetch_arr($result2);
		$fidx = $row2['idx'];

		if(strcmp(substr($row['content'], 0, 3), "|^|")) $question = $row['content'];
		else {

			$form_content = explode("|^|", $row['content']);
			
			for($ii = 0; $ii < count($form_content); $ii++) {
				list($frm_idx, $frm_data) = explode("||", $form_content[$ii]);
				$form_data[$frm_idx] = $frm_data;
			}
			
			$no = 1;
			$question = "<table width=100% border=0 cellspacing=1 cellpadding=3 style='font-size:12px; border :solid #b8d9e1 1px; border-bottom:none; '>";
			$sql = "select * from wiz_formfield where fidx = '$fidx' and ftype != 'spamcheck' order by fprior asc, idx asc";
			$result = query($sql);
			while($row = sql_fetch_arr($result)){		
		  		$question .= "<tr>";
		  		$question .= "<td width=80 style='color: #11809f; background: #e8f3f7; line-height: 15px; padding-left:10px; height:30px; border-bottom:1px solid #b8d9e1;'>".$row['fname']."</td>";
		  		$question .= "<td style='color: #555555; background: #ffffff; line-height: 20px; padding-left:10px; border-bottom:1px solid #b8d9e1;'>".$form_data[$row['idx']]."&nbsp;</td>";
		  		$question .= "</tr>";
		  		$no++;
			}
			$question .= "</table>";
			
		}
		
		$re_name = $name;
		$re_email = $email;
		$re_info['re_name'] = $re_name;
		$re_info['re_email'] = $re_email;

		$mail_info = get_table("wiz_mailsms", "code = 'form'");
		
		$content = "<table width=100% cellpadding=2>
									<tr><td bgcolor=#efefef>&nbsp; <b>작성내용</b></td></tr>
									<tr><td>".$question."</td></tr>
									<tr><td bgcolor=#efefef>&nbsp; <b>답변내용</b></td></tr>
									<tr><td>".$reply."</td></tr>
								</table>";

		$email_subj = "[".$site_info['site_name']."] 문의하신 내용의 답변입니다.";
		$email_msg = str_replace("{MESSAGE}",$content,$mail_info['email_msg']);
		$email_msg = info_replace($site_info, $re_info, $email_msg);
		
		$email_msg = stripslashes($email_msg);
		send_mail($site_info['site_name'], $site_info['site_email'], $re_name, $re_email, $email_subj, $email_msg);

		$send_msg = "답변 메일을 발송하였습니다.";

	} else {
		$send_msg = "수정 하였습니다";
	}

	$sql = "update wiz_form set name='$name',phone='$phone',email='$email',subject='$subject',reply='$reply',status='$status' where idx = '$idx'";
	query($sql) or error("sql error");
		
	complete($send_msg,"form_input.php?".$param."&idx=".$idx."&page=".$page);


// 폼메일 삭제
}else if($mode == "delete"){
  
  if($idx != "") $selform = $idx;
  
  $array_selform = explode("|",$selform);
	for($ii=0;$ii<count($array_selform);$ii++){
		
		$idx = $array_selform[$ii];
		$sql = "select upfile1,upfile2,upfile3,upfile4,upfile5 from wiz_form where idx = '$idx'";
		$result = query($sql) or error("sql error");
		$form_row = sql_fetch_arr($result);
		
		$upfile_path = WIZHOME_PATH."/data/form";
		if($form_row['upfile1'] != ""){
			@unlink($upfile_path."/".$form_row['upfile1']);
		}
		if($form_row['upfile2'] != ""){
			@unlink($upfile_path."/".$form_row['upfile2']);
		}
		if($form_row['upfile3'] != ""){
			@unlink($upfile_path."/".$form_row['upfile3']);
		}
		if($form_row['upfile4'] != ""){
			@unlink($upfile_path."/".$form_row['upfile4']);
		}
		if($form_row['upfile5'] != ""){
			@unlink($upfile_path."/".$form_row['upfile5']);
		}

		$sql = "delete from wiz_form where idx='$idx'";
		query($sql) or error("sql error");

	}

	complete("삭제되었습니다.","form_list.php?".$param."&page=".$page);
   
}
?>