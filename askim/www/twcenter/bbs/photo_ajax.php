<?
	include $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
	include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbs_info.php";


	$sql = "select idx from wiz_bbs where code = '$code' order by prino desc";
	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);

	if($pos != "")       $param = "&pos=$pos&code_page=$code_page";

	$rows = $bbs_info['rows'];
	$lists = $bbs_info['lists'];
	if($rows == "") $rows = "20";
	if($lists == "") $lists = "5";

	$page_count = ceil($total/$rows);
	if(!$page || $page > $page_count) $page = 1;
	$start = ($page-1)*$rows;

	if(empty($bbs_idx)) {
		$sql = "SELECT * FROM wiz_bbs WHERE code = '".$code."' ORDER BY prino DESC LIMIT $start, $rows ";
	} else {
		$sql = "SELECT * FROM wiz_bbs WHERE code = '".$code."' AND idx = '".$bbs_idx."'";
	}
	$result = query($sql) or error("sql error");
	$bbs_row = sql_fetch_arr($result);	
	$upimg_m1_view = $bbs_row['upfile1'];
	/*
	if(img_type(WIZHOME_PATH."/data/bbs/$code/L".$bbs_row['upfile1'])) $upimg_m1 = "/twcenter/data/bbs/$code/L".$bbs_row['upfile1'];
	else if(img_type(WIZHOME_PATH."/data/bbs/$code/".$bbs_row['upfile1'])) $upimg_m1 = "/twcenter/data/bbs/$code/".$bbs_row['upfile1'];
	*/
	if(strpos($HTTP_REFERER,"/m/")){
		if(img_type(WIZHOME_PATH."/data/bbs/$code/M".$bbs_row['upfile1'])) $upimg_m1 = "/twcenter/data/bbs/$code/M".$bbs_row['upfile1'];
	}else{
		if(img_type(WIZHOME_PATH."/data/bbs/$code/".$bbs_row['upfile1'])) $upimg_m1 = "/twcenter/data/bbs/$code/".$bbs_row['upfile1'];
	}

	// 이전글
	$sql = "select idx,prino,subject, privacy, memid from wiz_bbs where code = '$code' and prino > '$bbs_row['prino']' $my_sql order by prino asc limit 1";
	$result = query($sql) or error("sql error");
	if($row = sql_fetch_arr($result)) {
		
		$psql = "SELECT * FROM wiz_bbs WHERE code = '".$code."' ORDER BY prino DESC LIMIT $start, $rows";
		//echo $psql."<br>";
		$prs = query($psql) or error("sql error");
		$prw = sql_fetch_arr($prs);
		//echo $prw['prino']."=>".$bbs_row['prino']."<br>";
		if($prw['prino'] == $bbs_row['prino']){
			$spage = $page - 1;
		} else {
			$spage = $page;
		}
		//$prev = "<a href='$PHP_SELF?code=$code&bbs_idx=$row['idx']&page=$spage$param'><img src='$skin_dir/image/q002.png' name='Image5' border='0'></a>";	
		$prev = "<a href=\"javascript:changeImg('".$row['idx']."','".$spage."')\"><img src='/twcenter/bbs/skin/photoList1/image/q002.png' name='Image5' border='0'></a>";	
	}else{
		$prev = "<img src='/twcenter/bbs/skin/photoList1/image/q002.png' name='Image5' border='0'>";	
	}
	
	//다음글
	$sql = "select idx,prino,subject, privacy, memid from wiz_bbs where code = '$code' and prino < '$bbs_row['prino']' $my_sql order by prino desc limit 1";
	$asdasd = $sql;
	$result = query($sql) or error("sql error");
	if($row = sql_fetch_arr($result)) {
		
		$psql = "SELECT * FROM wiz_bbs WHERE code = '".$code."' ORDER BY prino DESC LIMIT $start, $rows";
		//echo $psql."<br>";
		$prs = query($psql) or error("sql error");
		$prw = sql_fetch_arr($prs);
		//echo $prw['prino']."=>".$bbs_row['prino'];
		if(($prw['prino'] - ($rows - 1)) >= $bbs_row['prino']){
			$spage = $page + 1;
		} else {
			$spage = $page;
		}
		
		//$next = "<a href='$PHP_SELF?code=$code&bbs_idx=$row['idx']&page=$spage$param'><img src='$skin_dir/image/q001.png' name='Image5' border='0'></a>";	
		$next = "<a href=\"javascript:changeImg('".$row['idx']."','".$spage."')\"><img src='/twcenter/bbs/skin/photoList1/image/q001.png' name='Image5' border='0'></a>";	
	}else{
		$next = "<img src='/twcenter/bbs/skin/photoList1/image/q001.png' name='Image5' border='0'>";	
	}


// 우측리스트

		$sql = "select idx from wiz_bbs where code = '$code' and notice != 'Y' $my_sql $category_sql $search_sql $address_sql $sub_category_sql $process_sql order by prino desc";
		$result = query($sql) or error("sql error");
		$total = sql_fetch_row($result);

		$idx = 0;
		$rows = $bbs_info['rows'];
		$lists = $bbs_info['lists'];
		if($rows == "") $rows = "20";
		if($lists == "") $lists = "5";

		$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$page_count = ceil($total/$rows);
		if(!$page || $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;
		$no = $total-$start;



		$sql = "select wb.*,wb.wdate as wtime,from_unixtime(wb.wdate, '".$bbs_info['datetype_list']."') as wdate, wc.catname, wc.caticon
						from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
						where wb.code = '$code' and wb.notice != 'Y' $category_sql $search_sql $my_sql $address_sql
						order by wb.prino desc limit $start, $rows";

		$result = query($sql) or error("sql error");
		while($row = sql_fetch_arr($result)){

			//$wdate = str_replace("-",".",$row['wdate']);

			$content = $row['content'];
			if($row['ctype'] != "H"){
				$content = str_replace("\n", "<br>", $content);
			}

			if($bbs_info['subject_len'] > 0) $row['subject'] = cut_str($row['subject'], $bbs_info['subject_len']);

			if(file_exists(WIZHOME_PATH."/data/bbs/".$code."/S".$row['upfile1'])) $upimg_s = "/twcenter/data/bbs/$code/S".$row['upfile1'];
			else $upimg_s = "$skin_dir/image/noimg.gif";

			// 목록 체크박스
			if((strpos($HTTP_REFERER,"/manage/")) || ($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)) {
				$checkbox_body = "<form style='margin:0;'><input type='hidden' name='idx' value='".$row['idx']."'><input type='checkbox' name='select_checkbox'></form>";
			}

			$subject    = xss_check($subject);
			$content    = xss_check($content);

			$no--;
			$idx++;

			// 수정권한 체크
			if(strpos($HTTP_REFERER,"/manage/")){
			$prev_url   = explode("?",$HTTP_REFERER);
			$prev_url   = $prev_url[0];
			$modify_btn = "<a href='$prev_url?ptype=input&mode=modify&idx=".$row['idx']."&code=$code&page=$page'><image src='$skin_dir/image/ic_modify.gif' border='0'></a>";
			$delete_btn = "<a href='$prev_url?ptype=passwd&mode=delete&idx=".$row['idx']."&code=$code&page=$page'><image src='$skin_dir/image/ic_del.gif' border='0'></a>";
			} else {
			$modify_btn = "";
			$delete_btn = "";
			}
			

			// 우측리스트
			$right_list .= "$checkbox_body";
			$right_list .= "<p style=\"width:120px; height:92px; background:url('$upimg_s') center center/cover;\">";
			$right_list .= "<a href=\"javascript:changeImg('".$row['idx']."','".$page."')\">";
			$right_list .= "<img src=\"$skin_dir/image/blank_img.png\" border=\"0\" width=\"120\"  style='cursor:pointer;' ></a>";
			$right_list .= $modify_btn." ".$delete_btn;
			$right_list .= "</p>";


			if(empty($bbs_idx)) $bbs_idx = $row['idx'];

		}
	$return = array("upimg_m1"=> $upimg_m1,"next"=> $next, "prev" => $prev, "right_list" => $right_list, "upimg_m1_view" => $upimg_m1_view, "page"=>$page, "subject"=>$bbs_row['subject']);
	echo json_encode($return);

?>