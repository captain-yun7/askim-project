<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?
$skin = $_POST['skin'];

if(!isset($skin)) $skin = '';
$skin = str_replace("<||<", "<", $skin);
$skin = str_replace(">||>", ">", $skin);
//$skin = xss_clean($skin);
if($mode == "ban_insert"){
	$sql_chk = "select count(*) cnt from wiz_bannerinfo where code='$code'";
	$row_chk = sql_fetch($sql_chk);
	if($row_chk['cnt'] > 0) {
		error("이미 사용중인 code 입니다.");
	} else {
		
		$sql = "insert into wiz_bannerinfo (idx,title,code,grp,prior,types,types_num,padding,isuse,use_skin,imgs,noimg,txts,skin,limit_chk,limit_rows)
						values('','$title','$code','$grp','$prior','$types','$types_num','$padding','$isuse','$use_skin','$imgs','$noimg','$txts','$skin','$limit_chk','$limit_rows')";
		query($sql) or error("sql error");

		complete("디자인그룹을 추가 하였습니다.","banner_list.php?$menucodeParam");
	}
}else if($mode == "ban_update"){

	$sql = "update wiz_bannerinfo set title='$title',grp='$grp',prior='$prior',types='$types',types_num='$types_num',padding='$padding',isuse='$isuse'
	,use_skin='$use_skin',imgs='$imgs',noimg='$noimg',txts='$txts',skin='$skin',limit_chk='$limit_chk',limit_rows='$limit_rows'
	where idx = '$idx'";
	$result = query($sql) or error("sql error".$sql);

	complete("디자인그룹 정보를 수정하였습니다.","banner_input.php?mode=ban_update&idx=$idx&page=$page&$menucodeParam");

}else if($mode == "ban_delete"){

	$sql = "delete from wiz_bannerinfo where idx = '$idx'";
	query($sql) or error("sql error");
	
	$banner_path = "../../data/banner";

	$sql = "SELECT de_img FROM wiz_banner WHERE code = '$code'";
	$result = query($sql) or error("sql error");
	while ($row = sql_fetch_arr($result)) {
		if(!empty($row['de_img'])) {
			@unlink($banner_path."/".$row['de_img']);
		}
	}
	
	$sql = "delete from wiz_banner where code = '$code'";
	query($sql) or error("sql error");
	
	complete("해당디자인그룹을 삭제하였습니다.","banner_list.php?$menucodeParam");

} else if(!strcmp($mode, 'insert')) {

	$banner_path = "../../data/banner";
	$upfile_idx = date('ymdhis').rand(1,9);						// 업로드파일명
	
	// 업로드 디렉토리 생성
//	if(!is_dir($banner_path)) mkdir($banner_path, 0707);
	if(!is_dir($banner_path)) {	
		$oldmask = umask(0);
		mkdir($banner_path, DIR_PERM, true);
		umask($oldmask);
	}

	if($_FILES['de_img']['size'] > 0 || $_FILES['de_img2']['size'] > 0 || $_FILES['de_img3']['size'] > 0 || $_FILES['de_img4']['size'] > 0 || $_FILES['de_img5']['size'] > 0
			|| $_FILES['de_img6']['size'] > 0 || $_FILES['de_img7']['size'] > 0 || $_FILES['de_img8']['size'] > 0 || $_FILES['de_img9']['size'] > 0 || $_FILES['de_img10']['size'] > 0) {
		
		if(fileperms($banner_path) != 16837 && fileperms($banner_path) != 16839 && fileperms($banner_path) != 16895){
			error("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.","");
		}
		
	}

	if($de_img['size'] > 0){
		file_check($de_img['name']);
		
		$de_img_name = $code."_".$upfile_idx.".".getFileExt($de_img['name']);
		
		copy($de_img['tmp_name'], $banner_path."/".$de_img_name);
//		chmod($banner_path."/".$de_img_name, 0606);
	}
	for($i=2; $i<=10; $i++) {
		if($_FILES['de_img'.$i]['size'] > 0) {
			file_check($_FILES['de_img'.$i]['name']);
			
			${'de_img_name'.$i} = $code."_".$upfile_idx."_".$i.".".getFileExt($_FILES['de_img'.$i]['name']);
			//echo $banner_path."/".${'de_img_name'.$i}."\n";
			copy($_FILES['de_img'.$i]['tmp_name'], $banner_path."/".${'de_img_name'.$i});
//			chmod($banner_path."/".${'de_img_name'.$ii}, 0606);
		}
	}
	
	$content= get_text("textarea", $content);
	
	$sql = "insert into wiz_banner (
					idx,code,align,prior,isuse,link_url,link_target,de_type
					,de_img,de_img2,de_img3,de_img4,de_img5,de_img6,de_img7,de_img8,de_img9,de_img10
					,de_html
					, txt1, txt2, txt3, txt4, txt5, txt6, txt7, txt8, txt9, txt10
				)
					values('','$code','$align','$prior','$isuse','$link_url','$link_target','$de_type'
					,'$de_img_name','$de_img_name2','$de_img_name3','$de_img_name4','$de_img_name5','$de_img_name6','$de_img_name7','$de_img_name8','$de_img_name9','$de_img_name10'
					,'$content'
					, '$txt1', '$txt2', '$txt3', '$txt4', '$txt5', '$txt6', '$txt7', '$txt8', '$txt9', '$txt10')";
	query($sql) or error("sql error");

	complete("디자인를 추가 하였습니다.","list.php?code=$code&$menucodeParam");



} else if(!strcmp($mode, "update")) {
	
	$sql = "SELECT * FROM wiz_banner WHERE idx = '$idx'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_arr($result);

	$banner_path = "../../data/banner";
	$upfile_idx = date('ymdhis').rand(1,9);						// 업로드파일명

	// 업로드 디렉토리 생성
	if(!is_dir($banner_path)) mkdir($banner_path, 0707);

	///기존파일 삭제
	if($filedel == "Y" && !empty($row['de_img']) && empty($de_img['size'])) {
		@unlink($banner_path."/".$row['de_img']);
		$de_img_sql = " de_img='', ";
	} else if($de_img['size'] > 0){

		if(fileperms($banner_path) != 16837 && fileperms($banner_path) != 16839 && fileperms($banner_path) != 16895){
			error("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.","");
		}
		
		file_check($de_img['name']);
		if(!empty($row['de_img'])) {
			@unlink($banner_path."/".$row['de_img']);
		}

		$de_img_name = $code."_".$upfile_idx.".".getFileExt($de_img['name']);
		
		copy($de_img['tmp_name'], $banner_path."/".$de_img_name);
		chmod($banner_path."/".$de_img_name, 0606);
		
		$de_img_sql = " de_img='$de_img_name', ";

	}
	/*디자인이미지 2~10 업데이트*/
	for($i=2; $i<=10; $i++) {
		
		if(${'filedel'.$i} == "Y" && !empty($row['de_img'.$i]) && empty($_FILES['de_img'.$i]['size'])) {
			@unlink($banner_path."/".$row['de_img'.$i]);
			$de_img_sql .= " de_img".$i."='', ";
		} else if($_FILES['de_img'.$i]['size'] > 0) {
			file_check($_FILES['de_img'.$i]['name']);

			if(!empty($row['de_img'.$i])) {
				@unlink($banner_path."/".$row['de_img'.$i]);
			}
			
			${'de_img_name'.$i} = $code."_".$upfile_idx."_".$i.".".getFileExt($_FILES['de_img'.$i]['name']);
			//echo $banner_path."/".${'de_img_name'.$i}."\n";
			copy($_FILES['de_img'.$i]['tmp_name'], $banner_path."/".${'de_img_name'.$i});
			$de_img_sql .= " de_img".$i."='".${'de_img_name'.$i}."', ";
//			chmod($banner_path."/".${'de_img_name'.$ii}, 0606);
		}
	}
	
	$content= get_text("textarea", $content);

	$sql = "update wiz_banner set code='$code',align='$align', prior='$prior', isuse='$isuse', link_url='$link_url',
					link_target='$link_target', de_type='$de_type', $de_img_sql de_html='$content', txt1='$txt1', txt2='$txt2', txt3='$txt3', txt4='$txt4', txt5='$txt5', txt6='$txt6', txt7='$txt7' , txt8='$txt8', txt9='$txt9', txt10='$txt10' where idx = '$idx'";
	query($sql) or error("sql error");

	complete("디자인를 수정 하였습니다.","input.php?mode=update&idx=$idx&code=$code&page=$page&$menucodeParam");

} else if(!strcmp($mode, 'delete')) {
	
	$banner_path = "../../data/banner";

	$sql = "SELECT * FROM wiz_banner WHERE idx = '$idx'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_arr($result);

	if(!empty($row['de_img'])) {
		@unlink($banner_path."/".$row['de_img']);
	}
	/*디자인이미지 2~10 삭제*/
	for($i=2; $i<=10; $i++) {
		@unlink($banner_path."/".$row['de_img'.$i]);
	}

	$sql = "delete from wiz_banner where idx = '$idx'";
	query($sql) or error("sql error");

	complete("디자인를 삭제하였습니다.","list.php?code=$code&$menucodeParam");

} else if(!strcmp($mode, "grpinsert")) {
	$sql = "insert into wiz_banner_grp set grpname='$grpname', prior='$prior'";
	if(query($sql)) {
		grp_update("디자인그룹 정보가 등록되었습니다", "group.php");
	} else {
		error("오류가 발생하였습니다.");
	}

} else if(!strcmp($mode, "grpupdate") && $idx) {
	$sql = "update wiz_banner_grp set grpname='$grpname', prior='$prior' where idx='$idx'";
	if(query($sql)) {
		grp_update("디자인그룹 정보가 수정되었습니다", $_SERVER['HTTP_REFERER']);
	} else {
		error("오류가 발생하였습니다.");
	}

} else if(!strcmp($mode, "grpdelete")) {
	$sql = "delete from wiz_banner_grp where idx='$idx' ";
	if(query($sql)) {
		grp_update("디자인그룹 정보가 삭제되었습니다", "group.php");
	} else {
		error("오류가 발생하였습니다.");
	}

} else if ($mode == "grp_prior" && $idx && $prior) {
	$sql = "update wiz_banner_grp set prior='$prior' where idx='$idx'";
	if(query($sql)) {
		grp_update("우선순위가 변경되었습니다.", "group.php");
		//alert("우선순위가 변경되었습니다.", "group.php");
	} else {
		error("오류가 발생하였습니다.");
	}

} else if ($mode == "set_grp_prior") {
	$json_data = json_decode(stripslashes($_POST['json_data']),1);
	foreach($json_data as $data) {
		list($idx, $prior) = $data;
		$sql = "update wiz_banner_grp set prior='$prior' where idx='$idx'";
		if(!query($sql)) {
			error("우선순위 변경 중 오류가 발생하였습니다.", $_SERVER['HTTP_REFERER']);
		}
	}
	grp_update("우선순위가 변경되었습니다.", "group.php");

} else if($mode == "get_grplist") {
	echo json_encode(get_grplist("banner"));
}
?>