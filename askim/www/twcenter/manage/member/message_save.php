<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/msg_info.php"; ?>
<?

$param = "page=".$page."&searchopt=".$searchopt."&searchkey=".$searchkey."&".$menucodeParam;

// 쪽지등록
if($mode == "insert"){

// 쪽지정보 수정
}else if($mode == "update"){
	// 첨부파일등록
	if($upfile['size'] > 0){
	
		// 파일업로드 설정
		$upfile_path = WIZHOME_PATH."/data/message/".$se_id;		// 업로드파일 위치
		
		// 업로드 디렉토리 생성
		if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);

		if(fileperms($upfile_path) != 16837 && fileperms($upfile_path) != 16839 && fileperms($upfile_path) != 16895){
			error("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.","");
		}
		
		$upfile_idx = date('ymdhis').rand(1,9);						// 업로드파일명
		$imgsize_s = $bbs_info['simgsize'];
		$imgsize_m = $bbs_info['mimgsize'];

		// 업로드 디렉토리 생성
		if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);
		
		if($idx != ""){
			$sql = "select upfile,upfile_name from wiz_message where idx = '$idx'";
			$result = query($sql) or error("sql error");
			$msg_row = sql_fetch_arr($result);
		}
		
		if($upfile['size'] > 0){
			
			file_check($upfile['name']);
	
			$upfile_tmp = $upfile_idx.".".substr($upfile['name'],-3);
			copy($upfile['tmp_name'], $upfile_path."/".$upfile_tmp);
			chmod($upfile_path."/".$upfile_tmp, 0606);
			if($msg_row['upfile'] != ""){
				@unlink($upfile_path."/".$msg_row['upfile']);
			}
			$upfile_sql = " , upfile='$upfile_tmp', upfile_name='$upfile['name']' ";
		
			// 썸네일 만들기
			if(img_type($upfile_path."/".$upfile_tmp)){
				$srcimg = $upfile_tmp;
				$dstimg = "S".$upfile_tmp;
				img_resize($srcimg, $dstimg, $upfile_path, $imgsize_s, $imgsize_s);
			
				$dstimg = "M".$upfile_tmp;
				img_resize($srcimg, $dstimg, $upfile_path, $imgsize_m, $imgsize_m);
			}
			
		}

	}

	if($msg_info['msg_editor_use'] == "Y") $ctype = "H";

	$sql = "update wiz_message set se_id='$se_id',se_name='$se_name',re_id='$re_id',re_name='$re_name',
			subject='$subject',content='$content' $upfile_sql ,addinfo1='$addinfo1',addinfo2='$addinfo2',
			addinfo3='$addinfo3', status = '$status', ctype='$ctype' where idx = '$idx'"; 
	query($sql) or error("sql error");

	complete("정보를 수정하였습니다.","message_input.php?mode=$mode&idx=$idx&$param");

// 쪽지 삭제
}else if($mode == "deluser"){
	
	$i=0;
	$upfile_path = WIZHOME_PATH."/data/message/";
	$array_seluser = explode("|",$seluser);

	while($array_seluser[$i]){
		
		$idx = $array_seluser[$i];
		
		//첨부파일 삭제 
		$sql = "select se_id, upfile from wiz_message where idx = '$idx'";
		$result = query($sql) or error("sql error");
		$row = sql_fetch_arr($result);
		@unlink($upfile_path."/".$row['se_id']."/".$row['upfile']);
		
		// 쪽지테이블에서 삭제
		$sql = "delete from wiz_message where idx = '$idx'";
		$result = query($sql) or error("sql error");
		
		
		$i++;
	}

	complete("삭제하였습니다.","message_list.php?$param");


// 첨부파일 삭제
}else if($mode == "delfile"){
	
	$upfile_path = WIZHOME_PATH."/data/message/";
	
	$sql = "update wiz_message set upfile = '', upfile_name = '' where idx = '$idx'";
	query($sql) or error("sql error");
	
	@unlink($upfile_path."/".$se_id."/".$file);
	 
	complete("삭제되었습니다.","message_input.php?mode=update&idx=$idx&$param");

// 쪽지 발송
} else if(!strcmp($mode, "send")) {	
	
	if($subject == "") error("보내는 제목이 없습니다.");
	if($content == "") error("보내는 내용이 없습니다.");
	
	$se_id = $wiz_admin['id'];
	$se_name = $wiz_admin['name'];
	
	$msgsql = base64_decode($msgsql);
	$msgsql = str_replace("\\","",$msgsql);
	$result = query($msgsql);
	while($row = sql_fetch_arr($result)){
		
		// 첨부파일등록
		if($upfile['size'] > 0){
			
			// 파일업로드 설정
			$upfile_path = WIZHOME_PATH."/data/message/".$se_id;		// 업로드파일 위치
			$upfile_idx = date('ymdhis').rand(1,9);						// 업로드파일명
			$imgsize_s = $bbs_info['simgsize'];
			$imgsize_m = $bbs_info['mimgsize'];

			// 업로드 디렉토리 생성
			if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);
			
			if($upfile['size'] > 0){
				
				$upfile_tmp = $upfile_idx.".".substr($upfile['name'],-3);
				copy($upfile['tmp_name'], $upfile_path."/".$upfile_tmp);
				chmod($upfile_path."/".$upfile_tmp, 0606);
				if($msg_row['upfile'] != ""){
					@unlink($upfile_path."/".$msg_row['upfile']);
				}
				$upfile_name = $upfile['name'];
				$upfile_sql = " , upfile='$upfile_tmp', upfile_name='$upfile_name' ";
			
				// 썸네일 만들기
				if(img_type($upfile_path."/".$upfile_tmp)){
					$srcimg = $upfile_tmp;
					$dstimg = "S".$upfile_tmp;
					img_resize($srcimg, $dstimg, $upfile_path, $imgsize_s, $imgsize_s);
				
					$dstimg = "M".$upfile_tmp;
					img_resize($srcimg, $dstimg, $upfile_path, $imgsize_m, $imgsize_m);
				}
				
			}
	
		}
		
		$re_id = $row['id'];
		$re_name = $row['name'];
		if($msg_info['msg_editor_use'] == "Y") $ctype = "H";
		
		$sql = "insert into wiz_message (idx,se_id,se_name,re_id,re_name,subject,content,upfile,upfile_name,addinfo1,addinfo2,addinfo3,wdate,status,re_status,se_status,ctype) 
						values('','$se_id','$se_name','$re_id','$re_name','$subject','$content','$upfile_tmp','$upfile_name','$addinfo1','$addinfo2','$addinfo3',now(),'N','Y','Y','$ctype')"; 
		query($sql) or error("sql error");
	}
	
	alert("쪽지발송을 정상적으로 완료하였습니다.","message_send.php?$menucodeParam");
	
}
?>