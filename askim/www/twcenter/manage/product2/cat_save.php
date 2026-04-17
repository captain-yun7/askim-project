<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?

$upfile_path = "../../data/product";						// 업로드파일 위치

if($mode == "insert"){


	$catname = trim($catname);
	$catname = str_replace("\"","”",$catname);
	$catname = str_replace("'","′",$catname);
	if($catname == "") error("분류명을 입력하세요.");

	

	// 카테고리넘버,깊이
	$parent_catcode = $catcode;

	$catnum1 = substr($catcode,0,2);
	$catnum2 = substr($catcode,2,2);
	$catnum3 = substr($catcode,4,2);
	$catnum4 = substr($catcode,6,2);

	if(empty($depthno)) $depthno = 0;

	if($depthno == 0){ $sposi = 1; $tmpcode = ""; }
	else if($depthno == 1){  $sposi = 3;  $tmpcode = $catnum1; }
	else if($depthno == 2){  $sposi = 5;  $tmpcode = $catnum1.$catnum2; }
	else if($depthno == 3){  $sposi = 7;  $tmpcode = $catnum1.$catnum2.$catnum3; }

	$sql = "select max(substring(catcode,$sposi,2)) as catnum from wiz_category2 where catcode like '$tmpcode%'";
	$result = query($sql);
	$row = sql_fetch_obj($result);
	$row->catnum = substr(++$row->catnum."0",0,2);

	


	if($depthno == 0){ $catcode = $row->catnum."000000";}
	else if($depthno == 1){  $catcode = $catnum1.$row->catnum."0000";}
	else if($depthno == 2){  $catcode = $catnum1.$catnum2.$row->catnum."00";}
	else if($depthno == 3){  $catcode = $catnum1.$catnum2.$catnum3.$row->catnum;}

	// 우선순위 설정
	$sql = "select * from wiz_category2 where catcode like '$tmpcode%' order by priorno01 desc, priorno02 desc, priorno03 desc, priorno04 desc";
	$result = query($sql);
	$row = sql_fetch_obj($result);
	$priorno01 = $row->priorno01;
	$priorno02 = $row->priorno02;
	$priorno03 = $row->priorno03;
	$priorno04 = $row->priorno04;


	if($depthno == 0){ ++$priorno01; }
	else if($depthno == 1){  ++$priorno02;}
	else if($depthno == 2){  ++$priorno03;}
	else if($depthno == 3){  ++$priorno04;}

	$depthno++;

	// 메뉴이미지 저장
	
//	if($catimg['size'] > 0){
//		file_check($catimg['name']);
//		$catimg_ext = strtolower(substr($catimg['name'],-3));
//		$catimg_name = $catcode."_cat.".$catimg_ext;
//			copy($catimg['tmp_name'], $upfile_path."/".$catimg_name);
//			chmod($upfile_path."/".$catimg_name,0606);
//		}

	if (isset($catimg['size'], $catimg['name'], $catimg['tmp_name']) && $catimg['size'] > 0) {
    file_check($catimg['name']);

    $catimg_ext = strtolower(substr($catimg['name'], -3));
    $catimg_name = $catcode . "_cat." . $catimg_ext;

    if (is_uploaded_file($catimg['tmp_name'])) {
        copy($catimg['tmp_name'], $upfile_path . "/" . $catimg_name);
        chmod($upfile_path . "/" . $catimg_name, 0644);
    }
}

	// 카테고리명에 따음표 들어가면 상품등록시 스크립트 에러
//	$catname = str_replace("\"","”",$catname);
//	$catname = str_replace("'","′",$catname);
	$catname = get_text("input", $catname);
	$browser_title = get_text("input", $browser_title);
	$searchkey_de = get_text("textarea", $searchkey_de);
	$searchkey_cl = get_text("textarea", $searchkey_cl);
	$searchkey = get_text("textarea", $searchkey);

	//  카테고리 저장
	$sql = "insert into wiz_category2(catcode,depthno,priorno01,priorno02,priorno03,priorno04,catname,catuse,catimg,subimg,subimg_type,prd_skin,prd_num,prd_width,prd_height,purl,browser_title,searchkey_de,searchkey_cl,searchkey)values('$catcode','$depthno','$priorno01','$priorno02','$priorno03','$priorno04','$catname','$catuse','$catimg_name','$subimg','$subimg_type','$prd_skin','$prd_num','$prd_width','$prd_height','$purl','$browser_title','$searchkey_de','$searchkey_cl','$searchkey')";
	$result = query($sql);

	$depthno--;

	echo "ok|".$mode."|".$depthno."|".$parent_catcode;

//	complete("상품분류를 추가하였습니다.","prd_cat.php?mode=$mode&catcode=$parent_catcode&depthno=$depthno");


}else if($mode == "update"){

	// 메뉴이미지 저장
	if($delimg != ""){
		unlink($upfile_path."/".$delimg);
	}
	
	/*
	if($catimg['size'] > 0){
		file_check($catimg['name']);
		$catimg_ext = strtolower(substr($catimg['name'],-3));
		$catimg_name = $catcode."_cat.".$catimg_ext;
		copy($catimg['tmp_name'], $upfile_path."/".$catimg_name);
		chmod($upfile_path."/".$catimg_name,0606);
		$catimg_sql = " , catimg='$catimg_name' ";
	}*/

	if (isset($catimg['size'], $catimg['name'], $catimg['tmp_name']) && $catimg['size'] > 0) {
	    file_check($catimg['name']);

	    $catimg_ext = strtolower(substr($catimg['name'], -3));
	    $catimg_name = $catcode . "_cat." . $catimg_ext;

	    if (is_uploaded_file($catimg['tmp_name'])) {
	        copy($catimg['tmp_name'], $upfile_path . "/" . $catimg_name);
	        chmod($upfile_path . "/" . $catimg_name, 0644);
			$catimg_sql = " , catimg='$catimg_name' ";
	    }
	}

//	$catname = str_replace("\"","”",$catname);
//	$catname = str_replace("'","′",$catname);
	$catname = get_text("input", $catname);
	$browser_title = get_text("input", $browser_title);
	$searchkey_de = get_text("textarea", $searchkey_de);
	$searchkey_cl = get_text("textarea", $searchkey_cl);
	$searchkey = get_text("textarea", $searchkey);

	$sql = "update wiz_category2 set catcode='$catcode',depthno='$depthno',catname='$catname',catuse='$catuse'".$catimg_sql.", subimg='$subimg',subimg_type='$subimg_type',prd_skin='$prd_skin',prd_num='$prd_num',prd_width='$prd_width',prd_height='$prd_height',purl='$purl',browser_title='$browser_title',searchkey_de='$searchkey_de',searchkey_cl='$searchkey_cl',searchkey='$searchkey' where catcode = '$catcode'";

	query($sql) or error("sql error");

	// 분류숨김시 하위분류도 모두 숨김
	if ($catuse === "N" || (empty($catuse) && isset($catuse) && strcmp($org_catuse, $catuse) !== 0)) {
		if($depthno == "1") $tmp_catcode = substr($catcode,0,2);
		else if($depthno == "2") $tmp_catcode = substr($catcode,0,4);
		else if($depthno == "3") $tmp_catcode = substr($catcode,0,6);

		if($tmp_catcode != ""){
			$sql = "update wiz_category2 set catuse='$catuse' where catcode like '$tmp_catcode%'";
			//echo $sql;
			query($sql) or error("sql error");
		}
	}

	echo "ok|".$mode."|".$depthno."|".$catcode;
//	complete("분류정보를 수정하였습니다.","prd_cat.php?mode=$mode&catcode=$catcode&depthno=$depthno");

}else if($mode == "delete"){

	if($depthno == 1){ $tmpcode = substr($catcode,0,2); }
	else if($depthno == 2){ $tmpcode = substr($catcode,0,4); }
	else if($depthno == 3){ $tmpcode = substr($catcode,0,6); }
	else if($depthno == 4){ $tmpcode = $catcode; }

	//$depthno = $depthno-1;
	// 하위분류가 존재하면 삭제하지 못함
	$sql = "select catcode from wiz_category2 where catcode != '$catcode' and catcode like '$tmpcode%'";
	$result = query($sql) or error("sql error");
	if($row = sql_fetch_obj($result)){
		$level = "1";
	}


	// 현재 또는 하위분류에 상품이 존재하면 삭제하지 못함
	$sql = "select wp.prdcode from wiz_cprelation2 wc, wiz_product2 wp where wc.catcode = '$catcode' and wc.prdcode = wp.prdcode";
	$result = query($sql) or error("sql error");
	if($row = sql_fetch_obj($result)){
		$level = "2";
	}


	if($level == 1){
		echo "1|update|".$catcode."|".$depthno;
	} else if($level == 2) {
		echo "2|update|".$catcode."|".$depthno;
	} else {

		$sql = "delete from wiz_category2 where catcode = '$catcode'";
		$result = query($sql) or error("sql error");

		echo "ok|".$mode."|".$catcode."|".$depthno;

	}

	//complete("선택하신 분류를 삭제하였습니다.","prd_cat.php?mode=$mode&catcode=$catcode&depthno=$depthno");

// 카테고리 우선순위
}else if($mode == "updateprior"){

	if($catcode != ""){

		$break = false;
		$sel_row = "";
		$chg_row = "";
		$tmp_row = "";

		$sql = "select * from wiz_category2 where depthno = '$depthno' order by priorno01, priorno02, priorno03, priorno04 asc";
		$result = query($sql) or error("sql error");
		while($row = sql_fetch_obj($result)){
	
			if($break == true) { $chg_row = $row; break;}
	
			if($row->catcode == $catcode){
				$sel_row = $row;
				if($posi == "up"){
					$chg_row = $tmp_row;
				}else if($posi == "down"){
					$break = true;
				}
			}
	
			$tmp_row = $row;
			
		}

		$sel_priorno = $sel_row->{"priorno0".$depthno};
		$chg_priorno = $chg_row->{"priorno0".$depthno};

		if($chg_priorno == $sel_priorno) {			//변경할 두개의 priorno가 같을 경우, 한번 일괄 업데이트
			if($posi == 'up') {
				$chg_row->{"priorno0".$depthno} = $chg_row->{"priorno0".$depthno}+1;
				$sql_up = "update wiz_category2 set priorno0".$depthno." = priorno0".$depthno."+1 where priorno0".$depthno." >= ".$chg_row->{"priorno0".$depthno};
			} else if($posi == 'down') {
				$sel_row->{"priorno0".$depthno} = $sel_row->{"priorno0".$depthno}+1;
				$sql_up = "update wiz_category2 set priorno0".$depthno." = priorno0".$depthno."+1 where priorno0".$depthno." >= ".$sel_row->{"priorno0".$depthno};
			}
			if($sql_up) {
				query($sql_up);
			}
		} 

		if($depthno == 1){
			
			$sel_catcode = substr($sel_row->catcode,0,2);
			$chg_catcode = substr($chg_row->catcode,0,2);

			$sel_sql = " priorno01='$chg_row->priorno01' ";
			$chg_sql = " priorno01='$sel_row->priorno01' ";

		}else if($depthno == 2){
			
			$sel_catcode = substr($sel_row->catcode,0,4);
			$chg_catcode = substr($chg_row->catcode,0,4);

			$sel_sql = " priorno02='$chg_row->priorno02' ";
			$chg_sql = " priorno02='$sel_row->priorno02' ";

		}else if($depthno == 3){
			
			$sel_catcode = substr($sel_row->catcode,0,6);
			$chg_catcode = substr($chg_row->catcode,0,6);

			$sel_sql = " priorno03='$chg_row->priorno03' ";
			$chg_sql = " priorno03='$sel_row->priorno03' ";
			
		}else if($depthno == 4){
			$sel_catcode = $sel_row->catcode;
			$chg_catcode = $chg_row->catcode;

			$sel_sql = " priorno04='$chg_row->priorno04' ";
			$chg_sql = " priorno04='$sel_row->priorno04' ";
		}

		if($chg_row->catcode != ""){

			$sql = "update wiz_category2 set $sel_sql where catcode like '$sel_catcode%'";
			query($sql) or error("sql error");

			$sql = "update wiz_category2 set $chg_sql where catcode like '$chg_catcode%'";
			query($sql) or error("sql error");

		}

	}

	echo "ok|update|".$catcode."|".$depthno;

}else if($mode == "delsubimg"){

	exec("rm -f ../../images/subimg/".$catcode."_sub.*");
	$sql = "update wiz_category2 set subimg = '' where catcode = '$catcode'";
	query($sql) or error("sql error");
	Header("Location: prd_cat.php?mode=update&catcode=$catcode&depthno=$depthno");

}else if($mode == "delcatimg"){

	exec("rm -f ../../images/catimg/".$catcode."_cat.*");
	$sql = "update wiz_category2 set catimg = '' where catcode = '$catcode'";
	query($sql) or error("sql error");
	Header("Location: prd_cat.php?mode=update&catcode=$catcode&depthno=$depthno");

}else if($mode == "delcatimg_over"){

	exec("rm -f ../../images/catimg/".$catcode."_cat_over.*");
	$sql = "update wiz_category2 set catimg_over = '' where catcode = '$catcode'";
	query($sql) or error("sql error");
	Header("Location: prd_cat.php?mode=update&catcode=$catcode&depthno=$depthno");

} else if ($mode == "reorder") {

	$backup_tbl = "wiz_category2_".date("ymd");
	$sql_backup_chk = "SELECT COUNT(*) as cnt FROM Information_schema.tables
		WHERE table_schema = '$db_name'
		AND table_name = '".$backup_tbl."'";
	$backup_chk = sql_fetch($sql_backup_chk);
	if($backup_chk['cnt'] < 1) {
		$sql_backup = "create table ".$backup_tbl." select * from wiz_category2";
		query($sql_backup);
	}

	$sql1 = "select * from wiz_category2 where catcode!='00000000' and depthno='1' order by priorno01 asc";
	$res1 = query($sql1);
	$priorno01 = 1;
	while($row1= sql_fetch_obj($res1)) {
		$sql1_up = "update wiz_category2 set priorno01='$priorno01', priorno02='0', priorno03='0', priorno04='0' where catcode='".$row1->catcode."'";
		if(query($sql1_up)) {
			$sql2 = "select * from wiz_category2 where depthno='2' and catcode like '".substr($row1->catcode, 0, 2)."%' order by priorno02 asc";
			$res2 = query($sql2);
			$priorno02 = 1;
			while($row2 = sql_fetch_obj($res2)) {
				$sql2_up = "update wiz_category2 set priorno01='$priorno01', priorno02='$priorno02', priorno03='0', priorno04='0' where catcode='".$row2->catcode."'";
				if(query($sql2_up)) {
					$sql3 = "select * from wiz_category2 where depthno='3' and catcode like '".substr($row2->catcode, 0, 4)."%' order by priorno03 asc";
					$res3 = query($sql3);
					$priorno03 = 1;
					while($row3 = sql_fetch_obj($res3)) {
						$sql3_up = "update wiz_category2 set priorno01='$priorno01', priorno02='$priorno02', priorno03='$priorno03', priorno04='0' where catcode='".$row3->catcode."'";
						if(query($sql3_up)) {
							$sql4 = "select * from wiz_category2 where depthno='4' and catcode like '".substr($row2->catcode, 0, 6)."%' order by priorno04 asc";
							$res4 = query($sql4);
							$priorno04 = 1;
							while($row4 = sql_fetch_obj($res4)) {
								$sql4_up = "update wiz_category2 set priorno01='$priorno01', priorno02='$priorno02', priorno03='$priorno03', priorno04='$priorno04' where catcode='".$row4->catcode."'";
								if(query($sql4_up)) {
									$priorno04++;
								}
							}
							$priorno03++;
						}
					}
					$priorno02++;
				}
			}
			$priorno01++;
		}
	}
	complete("상품분류를 재정렬했습니다.", $_SERVER['HTTP_REFERER']);
}

?>
