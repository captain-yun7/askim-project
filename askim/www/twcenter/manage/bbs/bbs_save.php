<?php include_once "../../common.php"; ?>
<?php include_once "../../inc/twcenter_check.php"; ?>
<?php

$upfile_path = "../../data/category/".$code;				// 업로드파일 위치
$upfile_idx = date('ymdhis').rand(1,9);						// 업로드파일명

// 업로드 디렉토리 생성
if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);
$grpmap = "GRP".$grp;

if($mode == "insert") {

	$bbsinfo_cnt = sql_fetch("select count(code) as code_cnt from wiz_bbsinfo where code='".$_POST['code']."' ");
	if($bbsinfo_cnt['code_cnt'] > 0) error('이미등록된 게시판입니다');
	if($usetype == '') $usetype = 'N';

	$sql_com = "";
	$sql_com .= " code                       = '$code'                      ";
	$sql_com .= " , type                     = 'BBS'                        ";
	$sql_com .= " , title                    = '$title'                     ";
	$sql_com .= " , titleimg                 = '$titleimg'                  ";
	$sql_com .= " , header                   = '$header'                    ";
	$sql_com .= " , footer                   = '$footer'                    ";
	$sql_com .= " , category                 = '$category'                  ";
	$sql_com .= " , bbsadmin                 = '$bbsadmin'                  ";
	$sql_com .= " , lpermi                   = '$lpermi'                    ";
	$sql_com .= " , rpermi                   = '$rpermi'                    ";
	$sql_com .= " , wpermi                   = '$wpermi'                    ";
	$sql_com .= " , apermi                   = '$apermi'                    ";
	$sql_com .= " , cpermi                   = '$cpermi'                    ";
	$sql_com .= " , datetype_list            = '$datetype_list'             ";
	$sql_com .= " , datetype_view            = '$datetype_view'             ";
	$sql_com .= " , skin                     = '$skin'                      ";
	$sql_com .= " , skin_m                   = '$skin_m'                    ";
	$sql_com .= " , permsg                   = '$permsg'                    ";
	$sql_com .= " , perurl                   = '$perurl'                    ";
	$sql_com .= " , perurl_m                 = '$perurl_m'                  ";
	$sql_com .= " , pageurl                  = '$pageurl'                   ";
	$sql_com .= " , editor                   = '$editor'                    ";
	$sql_com .= " , usetype                  = '$usetype'                   ";
	$sql_com .= " , privacy                  = '$privacy'                   ";
	$sql_com .= " , sms                      = '$sms'                       ";
	$sql_com .= " , upfile                   = '$upfile'                    ";
	$sql_com .= " , movie                    = '$movie'                     ";
	$sql_com .= " , comment                  = '$comment'                   ";
	$sql_com .= " , remail                   = '$remail'                    ";
	$sql_com .= " , imgview                  = '$imgview'                   ";
	$sql_com .= " , recom                    = '$recom'                     ";
	$sql_com .= " , reply                    = '$reply'                     ";
	$sql_com .= " , rss                      = '$rss'                       ";
	$sql_com .= " , abuse                    = '$abuse'                     ";
	$sql_com .= " , abtxt                    = '$abtxt'                     ";
	$sql_com .= " , simgsize                 = '$simgsize'                  ";
	$sql_com .= " , mimgsize                 = '$mimgsize'                  ";
	$sql_com .= " , bbs_rows                 = '$bbs_rows'                  ";
	$sql_com .= " , lists                    = '$lists'                     ";
	$sql_com .= " , newc                     = '$newc'                      ";
	$sql_com .= " , hotc                     = '$hotc'                      ";
	$sql_com .= " , line                     = '$line'                      ";
	$sql_com .= " , subject_len              = '$subject_len'               ";
	$sql_com .= " , view_point               = '$view_point'                ";
	$sql_com .= " , write_point              = '$write_point'               ";
	$sql_com .= " , down_point               = '$down_point'                ";
	$sql_com .= " , comment_point            = '$comment_point'             ";
	$sql_com .= " , recom_point              = '$recom_point'               ";
	$sql_com .= " , point_msg                = '$point_msg'                 ";
	$sql_com .= " , img_align                = '$img_align'                 ";
	$sql_com .= " , btn_view                 = '$btn_view'                  ";
	$sql_com .= " , spam_check               = '$spam_check'                ";
	$sql_com .= " , view_list                = '$view_list'                 ";
	$sql_com .= " , name_type                = '$name_type'                 ";
	$sql_com .= " , grp                      = '$grp'                       ";
	$sql_com .= " , grpmap                   = '$grpmap'                    ";
	$sql_com .= " , prior                    = '$prior'                     ";
	$sql_com .= " , posdir                   = '$posdir'                    ";
	$sql_com .= " , boardshow                = '$boardshow'                 ";
	$sql_com .= " , posfile                  = '$posfile'                   ";
	$sql_com .= " , email                    = '$email'                     ";
	$sql_com .= " , browser_title            = '$browser_title'             ";
	$sql_com .= " , searchkey_de             = '$searchkey_de'              ";
	$sql_com .= " , searchkey_cl             = '$searchkey_cl'              ";
	$sql_com .= " , searchkey                = '$searchkey'                 ";
	$sql_com .= " , browser_dir              = '$browser_dir'               ";
	$sql_com .= " , page_url                 = '$page_url'                  ";
	$sql_com .= " , page_url_m               = '$page_url_m'                ";
	$sql_com .= " , use_vimeo               = '$use_vimeo'                ";
	$sql_com .= " , use_drag               = '$use_drag'                ";

	$sql = "INSERT INTO wiz_bbsinfo SET {$sql_com} ";
	query($sql);

	if(!isset($name_type)) $name_type = '';

	if(strcmp($name_type, "name")) {

		$sql = "select infouse from wiz_meminfo";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		$infouse = $row['infouse'];

		if(!strcmp($name_type, "icon") || !strcmp($name_type, "iname") || !strcmp($name_type, "inick")) {
			if(strpos($infouse,"icon") == false) $infouse = $infouse."icon/";
		}

		if(!strcmp($name_type, "nick") || !strcmp($name_type, "inick")) {
			if(strpos($infouse,"nick") == false) $infouse = $infouse."nick/";
		}

		$sql = "update wiz_meminfo set infouse='$infouse'";
		query($sql);

	}

$skin = "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
[LOOP]
<tr>
  <td width=\"5\" height=\"20\"><img src=\"/twcenter/bbsmain/image/point.gif\" width=\"3\" height=\"3\"></td>
  <td width=\"5\"></td>
  <td align=\"left\"><a href=\"{LINK}\">{SUBJECT}</a>{NEW}</td>
  <td align=\"right\">{DATE}</td>
</tr>
[/LOOP]
</table>
";

	$sql_com = "";
	$sql_com .= " code                       = '$code'                      ";
	$sql_com .= " , btype                    = ''                           ";
	$sql_com .= " , purl                     = ''                           ";
	$sql_com .= " , cnt                      = '5'                          ";
	$sql_com .= " , line                     = '0'                          ";
	$sql_com .= " , skin                     = '$skin'                      ";
	$sql_com .= " , subject_len              = '30'                         ";
	$sql_com .= " , content_len              = '80'                         ";

	$sql = "INSERT INTO wiz_bbsmain SET {$sql_com} ";
	query($sql);

	//전체 카테고리 추가
	if(empty($catname)) $catname = "전체";
	$sql_com = "";
	$sql_com .= " gubun                      = 'A'                          ";
	$sql_com .= " , code                     = '$code'                      ";
	$sql_com .= " , catname                  = '$catname'                   ";
	$sql_com .= " , catimg                   = '$catimg_tmp'                ";
	$sql_com .= " , catimg_over              = '$catimg_over_tmp'           ";
	$sql_com .= " , caticon                  = '$caticon_tmp'               ";
	$sql_com .= " , prior                    = '1'                          ";

	$sql = "INSERT INTO wiz_bbscat SET {$sql_com} ";
	query($sql);

	complete("게시판을 추가 하였습니다.","bbs_list.php?$menucodeParam");


} else if($mode == "update") {

	$sql_com = "";
	$sql_com .= " title                      = '$title'                      ";
	$sql_com .= " , titleimg                 = '$titleimg'                   ";
	$sql_com .= " , header                   = '$header'                     ";
	$sql_com .= " , footer                   = '$footer'                     ";
	$sql_com .= " , category                 = '$category'                   ";
	$sql_com .= " , bbsadmin                 = '$bbsadmin'                   ";
	$sql_com .= " , lpermi                   = '$lpermi'                     ";
	$sql_com .= " , rpermi                   = '$rpermi'                     ";
	$sql_com .= " , wpermi                   = '$wpermi'                     ";
	$sql_com .= " , apermi                   = '$apermi'                     ";
	$sql_com .= " , cpermi                   = '$cpermi'                     ";
	$sql_com .= " , datetype_list            = '$datetype_list'              ";
	$sql_com .= " , datetype_view            = '$datetype_view'              ";
	$sql_com .= " , skin                     = '$skin'                       ";
	$sql_com .= " , skin_m                   = '$skin_m'                     ";
	$sql_com .= " , permsg                   = '$permsg'                     ";
	$sql_com .= " , perurl                   = '$perurl'                     ";
	$sql_com .= " , perurl_m                 = '$perurl_m'                  ";
	$sql_com .= " , pageurl                  = '$pageurl'                    ";
	$sql_com .= " , editor                   = '$editor'                     ";
	$sql_com .= " , usetype                  = '$usetype'                    ";
	$sql_com .= " , privacy                  = '$privacy'                    ";
	$sql_com .= " , sms                      = '$sms'                        ";
	$sql_com .= " , upfile                   = '$upfile'                     ";
	$sql_com .= " , movie                    = '$movie'                      ";
	$sql_com .= " , comment                  = '$comment'                    ";
	$sql_com .= " , remail                   = '$remail'                     ";
	$sql_com .= " , imgview                  = '$imgview'                    ";
	$sql_com .= " , recom                    = '$recom'                      ";
	$sql_com .= " , reply                    = '$reply'                      ";
	$sql_com .= " , rss                      = '$rss'                       ";
	$sql_com .= " , abuse                    = '$abuse'                      ";
	$sql_com .= " , abtxt                    = '$abtxt'                      ";
	$sql_com .= " , simgsize                 = '$simgsize'                   ";
	$sql_com .= " , mimgsize                 = '$mimgsize'                   ";
	$sql_com .= " , bbs_rows                 = '$bbs_rows'                   ";
	$sql_com .= " , lists                    = '$lists'                      ";
	$sql_com .= " , newc                     = '$newc'                       ";
	$sql_com .= " , hotc                     = '$hotc'                       ";
	$sql_com .= " , line                     = '$line'                       ";
	$sql_com .= " , subject_len              = '$subject_len'                ";
	$sql_com .= " , view_point               = '$view_point'                 ";
	$sql_com .= " , write_point              = '$write_point'                ";
	$sql_com .= " , down_point               = '$down_point'                 ";
	$sql_com .= " , comment_point            = '$comment_point'              ";
	$sql_com .= " , recom_point              = '$recom_point'                ";
	$sql_com .= " , point_msg                = '$point_msg'                  ";
	$sql_com .= " , img_align                = '$img_align'                  ";
	$sql_com .= " , btn_view                 = '$btn_view'                   ";
	$sql_com .= " , spam_check               = '$spam_check'                 ";
	$sql_com .= " , view_list                = '$list_view'                  ";
	$sql_com .= " , name_type                = '$name_type'                  ";
	$sql_com .= " , grp                      = '$grp'                        ";
	$sql_com .= " , grpmap                   = '$grpmap'                     ";
	$sql_com .= " , prior                    = '$prior'                      ";
	$sql_com .= " , posdir                   = '$posdir'                     ";
	$sql_com .= " , boardshow                = '$boardshow'                  ";
	$sql_com .= " , posfile                  = '$posfile'                    ";
	$sql_com .= " , email                    = '$email'                      ";
	$sql_com .= " , browser_title            = '$browser_title'              ";
	$sql_com .= " , searchkey_de             = '$searchkey_de'              ";
	$sql_com .= " , searchkey_cl             = '$searchkey_cl'              ";
	$sql_com .= " , searchkey                = '$searchkey'                 ";
	$sql_com .= " , browser_dir              = '$browser_dir'                ";
	$sql_com .= " , page_url                 = '$page_url'                  ";
	$sql_com .= " , page_url_m               = '$page_url_m'                ";
	$sql_com .= " , use_vimeo               = '$use_vimeo'                ";
	$sql_com .= " , use_drag               = '$use_drag'                ";

	$sql = "UPDATE wiz_bbsinfo SET {$sql_com} WHERE code = '$code' ";
	//echo $sql;
	//exit;
	query($sql);

	if(strcmp($name_type, "name")) {

		$sql = "select infouse from wiz_meminfo";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		$infouse = $row['infouse'];

		if(!strcmp($name_type, "icon") || !strcmp($name_type, "iname") || !strcmp($name_type, "inick")) {
			if(strpos($infouse,"icon") == false) $infouse = $infouse."icon/";
		}

		if(!strcmp($name_type, "nick") || !strcmp($name_type, "inick")) {
			if(strpos($infouse,"nick") == false) $infouse = $infouse."nick/";
		}

		$sql = "update wiz_meminfo set infouse='$infouse'";
		query($sql);

	}

	complete("게시판 정보를 수정하였습니다.","bbs_input.php?mode=update&code=$code&page=$page&$menucodeParam");

} else if($mode == "delete") {

	if($code == "qna" || $code == "review"){
		alert("해당 게시판은 삭제할 수 없습니다.");
		exit;
	}

	$sql = "delete from wiz_bbsinfo where code = '$code'";
	query($sql);

	$sql = "delete from wiz_bbsmain where code = '$code'";
	query($sql);

	$sql = "delete from wiz_bbscat where code = '$code'";
	query($sql);

	$sql = "delete from wiz_bbs where code = '$code'";
	query($sql);

	// 첨부파일, 카테고리 디렉토리 삭제
	rm_dir("../../data/bbs/".$code);
	rm_dir("../../data/category/".$code);

	complete("해당게시판을 삭제하였습니다.","bbs_list.php?$menucodeParam");

// 카테고리 입력
} else if(!strcmp($mode, "catinsert")) {

	if(!strcmp($gubun, "A")) {

		$sql = "select gubun from wiz_bbscat where code = '$code' and gubun = 'A'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		if(!empty($row['gubun'])) {
			error("이미 전체분류가 등록되어 있습니다.", "");
			exit;
		}

	}

	if(is_uploaded_file($_FILES['catimg']['tmp_name'])) {

		$catimg['size']     = $_FILES['catimg']['size'];
		$catimg['name']     = $_FILES['catimg']['name'];
		$catimg['tmp_name'] = $_FILES['catimg']['tmp_name'];

		if($catimg['size'] > 0){

			ImageResize_Upload_check($catimg['name']);

			$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $catimg['name']);
			$len = (strlen($ext) == 3) ? "-3" : "-4";
			$cate_ext = strtolower(substr($catimg['name'],$len));
			$catimg_tmp = $upfile_idx."_img.".$cate_ext;

			copy($catimg['tmp_name'], $upfile_path."/".$catimg_tmp);
			chmod($upfile_path."/".$catimg_tmp, 0606);

		}

	}

	if(is_uploaded_file($_FILES['catimg_over']['tmp_name'])) {

		$catimg_over['size']     = $_FILES['catimg_over']['size'];
		$catimg_over['name']     = $_FILES['catimg_over']['name'];
		$catimg_over['tmp_name'] = $_FILES['catimg_over']['tmp_name'];

		if($catimg_over['size'] > 0){

			ImageResize_Upload_check($catimg_over['name']);

			$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $catimg_over['name']);
			$len = (strlen($ext) == 3) ? "-3" : "-4";
			$cate_ext = strtolower(substr($catimg_over['name'],$len));
			$catimg_over_tmp = $upfile_idx."_img_over.".$cate_ext;

			copy($catimg_over['tmp_name'], $upfile_path."/".$catimg_over_tmp);
			chmod($upfile_path."/".$catimg_over_tmp, 0606);

		}

	}

	if(is_uploaded_file($_FILES['caticon']['tmp_name'])) {

		$caticon['size']     = $_FILES['caticon']['size'];
		$caticon['name']     = $_FILES['caticon']['name'];
		$caticon['tmp_name'] = $_FILES['caticon']['tmp_name'];

		if($caticon['size'] > 0){

			ImageResize_Upload_check($caticon['name']);

			$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $caticon['name']);
			$len = (strlen($ext) == 3) ? "-3" : "-4";
			$cate_ext = strtolower(substr($caticon['name'],$len));
			$caticon_tmp = $upfile_idx."_icon.".$cate_ext;

			copy($caticon['tmp_name'], $upfile_path."/".$caticon_tmp);
			chmod($upfile_path."/".$caticon_tmp, 0606);

		}

	}

	$sql_com = "";
	$sql_com .= " gubun                      = '$gubun'                     ";
	$sql_com .= " , code                     = '$code'                      ";
	$sql_com .= " , catname                  = '$catname'                   ";
	$sql_com .= " , catimg                   = '$catimg_tmp'                ";
	$sql_com .= " , catimg_over              = '$catimg_over_tmp'           ";
	$sql_com .= " , caticon                  = '$caticon_tmp'               ";
	$sql_com .= " , prior                    = '$prior'                     ";

	$sql = "INSERT INTO wiz_bbscat SET {$sql_com} ";
	query($sql);

	$idx = mysqli_insert_id($connect);

	echo "<script>window.opener.document.location.href = window.opener.document.URL;</script>";
	complete("저장되었습니다.","category_input.php?code=$code&title=$title&idx=$idx&mode=catupdate&$menucodeParam");

// 카테고리 수정
}  else if(!strcmp($mode, "catupdate")) {

	if(!strcmp($gubun, "A")) {

		$sql = "select gubun from wiz_bbscat where code = '$code' and gubun = 'A' and idx != '$idx'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		if(!empty($row['gubun'])) {
			error("이미 전체분류가 등록되어 있습니다.", "");
			exit;
		}

	}

	$sql = "select catimg,catimg_over,caticon from wiz_bbscat where idx = '$idx'";
	$result = query($sql);
	$cat_row = sql_fetch_arr($result);
	if(!empty($delfile)) {
		for($ii = 0; $ii < count($delfile); $ii++) {

			if($cat_row[$delfile[$ii]] != ""){
				@unlink($upfile_path."/".$cat_row[$delfile[$ii]]);
				${$delfile[$ii]."_sql"} = " , ".$delfile[$ii]." = '' ";
			}
		}
	}
	if($catimg['size'] > 0 || $catimg_over['size'] > 0 || $caticon['size']) {
		if(fileperms($upfile_path) != 16837 && fileperms($upfile_path) != 16839 && fileperms($upfile_path) != 16895){
			error("파일업로드시 문제가 발생하였습니다.\\n\\ndata 디렉토리 이하는 모두 쓰기권한이 있어야합니다.","");
		}
	}

	if(is_uploaded_file($_FILES['catimg']['tmp_name'])) {

		$catimg['size']     = $_FILES['catimg']['size'];
		$catimg['name']     = $_FILES['catimg']['name'];
		$catimg['tmp_name'] = $_FILES['catimg']['tmp_name'];

		if($catimg['size'] > 0){

			ImageResize_Upload_check($catimg['name']);

			$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $catimg['name']);
			$len = (strlen($ext) == 3) ? "-3" : "-4";
			$cate_ext = strtolower(substr($catimg['name'],$len));
			$catimg_tmp = $upfile_idx."_img.".$cate_ext;

			copy($catimg['tmp_name'], $upfile_path."/".$catimg_tmp);
			chmod($upfile_path."/".$catimg_tmp, 0606);

			if($cat_row['catimg'] != ""){
				@unlink($upfile_path."/".$cat_row['catimg']);
			}
			$catimg_sql = " , catimg='$catimg_tmp' ";

		}

	}

	if(is_uploaded_file($_FILES['catimg_over']['tmp_name'])) {

		$catimg_over['size']     = $_FILES['catimg_over']['size'];
		$catimg_over['name']     = $_FILES['catimg_over']['name'];
		$catimg_over['tmp_name'] = $_FILES['catimg_over']['tmp_name'];

		if($catimg_over['size'] > 0){

			ImageResize_Upload_check($catimg_over['name']);
			$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $catimg_over['name']);
			$len = (strlen($ext) == 3) ? "-3" : "-4";
			$cate_ext = strtolower(substr($catimg_over['name'],$len));
			$catimg_over_tmp = $upfile_idx."_img_over.".$cate_ext;

			copy($catimg_over['tmp_name'], $upfile_path."/".$catimg_over_tmp);
			chmod($upfile_path."/".$catimg_over_tmp, 0606);

			if($cat_row['catimg_over'] != ""){
				@unlink($upfile_path."/".$cat_row['catimg_over']);
			}
			$catimg_over_sql = " , catimg_over='$catimg_over_tmp' ";

		}

	}

	if(is_uploaded_file($_FILES['caticon']['tmp_name'])) {

		$caticon['size']     = $_FILES['caticon']['size'];
		$caticon['name']     = $_FILES['caticon']['name'];
		$caticon['tmp_name'] = $_FILES['caticon']['tmp_name'];

		if($caticon['size'] > 0){

			ImageResize_Upload_check($caticon['name']);
			$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $caticon['name']);
			$len = (strlen($ext) == 3) ? "-3" : "-4";
			$cate_ext = strtolower(substr($caticon['name'],$len));
			$caticon_tmp = $upfile_idx."_icon.".$cate_ext;

			copy($caticon['tmp_name'], $upfile_path."/".$caticon_tmp);
			chmod($upfile_path."/".$caticon_tmp, 0606);

			if($cat_row['caticon'] != ""){
				@unlink($upfile_path."/".$cat_row['caticon']);
			}
			$caticon_sql = " , caticon='$caticon_tmp' ";

		}

	}

	$sql = "update wiz_bbscat set gubun='$gubun', catname='$catname' $catimg_sql $catimg_over_sql $caticon_sql ,prior='$prior' where idx = '$idx'";
	query($sql);

	echo "<script>window.opener.document.location.href = window.opener.document.URL;</script>";
	complete("수정되었습니다.","category_input.php?code=$code&title=$title&idx=$idx&mode=catupdate&$menucodeParam");

// 카테고리 삭제
} else if(!strcmp($mode, "catdelete")) {

	$sql = "select gubun, code, catimg, catimg_over, caticon from wiz_bbscat where idx = '$idx'";
	$result = query($sql);
	$cat_row = sql_fetch_arr($result);

	if(!strcmp($cat_row['gubun'], "A")) {
		error("전체분류는 삭제할 수 없습니다.", "");
		exit;
	}

	@unlink($upfile_path."/".$cat_row['catimg']);
	@unlink($upfile_path."/".$cat_row['catimg_over']);
	@unlink($upfile_path."/".$cat_row['caticon']);

	$sql = "delete from wiz_bbscat where idx = '".$idx."'";
	query($sql);

	echo "<script>window.opener.document.location.href = window.opener.document.URL;</script>";
	complete("삭제되었습니다.","category.php?code=$code&title=$title&$menucodeParam");

} else if(!strcmp($mode, "grpinsert")) {
	$sql = "insert into wiz_bbs_grp set grpname='$grpname', prior='$prior'";
	if(query($sql)) {
		grp_update("게시판그룹 정보가 등록되었습니다", "group.php");
	} else {
		error("오류가 발생하였습니다.");
	}

} else if(!strcmp($mode, "grpupdate") && $idx) {
	$sql = "update wiz_bbs_grp set grpname='$grpname', prior='$prior' where idx='$idx'";
	if(query($sql)) {
		grp_update("게시판그룹 정보가 수정되었습니다", $_SERVER['HTTP_REFERER']);
	} else {
		error("오류가 발생하였습니다.");
	}

} else if(!strcmp($mode, "grpdelete")) {
	$sql = "delete from wiz_bbs_grp where idx='$idx' ";
	if(query($sql)) {
		grp_update("게시판그룹 정보가 삭제되었습니다", "group.php");
	} else {
		error("오류가 발생하였습니다.");
	}

} else if ($mode == "grp_prior" && $idx && $prior) {
	$sql = "update wiz_bbs_grp set prior='$prior' where idx='$idx'";
	if(query($sql)) {
		grp_update("우선순위가 변경되었습니다.", "group.php");
	} else {
		error("오류가 발생하였습니다.");
	}

} else if ($mode == "set_grp_prior") {
	$json_data = json_decode(stripslashes($_POST['json_data']),1);
	foreach($json_data as $data) {
		list($idx, $prior) = $data;
		$sql = "update wiz_bbs_grp set prior='$prior' where idx='$idx'";
		if(!query($sql)) {
			error("우선순위 변경 중 오류가 발생하였습니다.", $_SERVER['HTTP_REFERER']);
		}
	}
	grp_update("우선순위가 변경되었습니다.", "group.php");

} else if($mode == "get_grplist") {
	echo json_encode(get_grplist("bbs"));
}
?>