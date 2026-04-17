<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>

<?

	$content = trim((string)($_POST['content'] ?? ''));
	
	// 추가
	if($mode == "insert"){
	
	$sql = "insert into wiz_popup(idx,isuse,scroll,posi_x,posi_y,size_x,size_y,sdate,edate,linkurl,popup_type,title,content,wdate,branch)
									values('','$isuse', '$scroll', '$posi_x', '$posi_y', '$size_x', '$size_y', '$sdate', '$edate', '$linkurl', '$popup_type', '$title', '$content',now(),'$branch')";
	
	$result = query($sql) or error("sql error");
	
	complete("추가되었습니다.","popup_list.php?menucode=$menucode");
	
	
	// 수정
	}else if($mode == "update"){
	
	$sql = "update wiz_popup set isuse='$isuse', scroll='$scroll', posi_x='$posi_x', posi_y='$posi_y', size_x='$size_x', size_y='$size_y',
					branch='$branch',
					sdate='$sdate', edate='$edate', linkurl='$linkurl', popup_type='$popup_type', title='$title', content='$content' where idx = '$idx'";

	$result = query($sql) or error("sql error");
	
	complete("수정되었습니다.","popup_input.php?mode=update&idx=$idx&page=$page&menucode=$menucode");
	
	
	// 삭제
	}else if($mode == "delete"){
	
	$sql = "delete from wiz_popup where idx = '$idx'";
	
	$result = query($sql) or error("sql error");

	complete("삭제되었습니다.","popup_list.php?menucode=$menucode");
	
	}

?>