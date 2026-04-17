<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?
$subimg_path = "../../data/subimg";
$catimg_path = "../../data/catimg";

// 업로드 디렉토리 생성
if(!is_dir($subimg_path)) mkdir($subimg_path, 0707);
if(!is_dir($catimg_path)) mkdir($catimg_path, 0707);

if($mode == "insert"){

	// 카테고리명에 따음표 들어가면 상품등록시 스크립트 에러
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

	$sql = "select max(substring(catcode,$sposi,2)) as catnum from wiz_category where catcode like '$tmpcode%'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_obj($result);
	$row->catnum = substr(++$row->catnum."0",0,2);

	if($depthno == 0){ $catcode = $row->catnum."000000";}
	else if($depthno == 1){  $catcode = $catnum1.$row->catnum."0000";}
	else if($depthno == 2){  $catcode = $catnum1.$catnum2.$row->catnum."00";}
	else if($depthno == 3){  $catcode = $catnum1.$catnum2.$catnum3.$row->catnum;}

	// 우선순위 설정
	$sql = "select * from wiz_category where catcode like '$tmpcode%' order by priorno01 desc, priorno02 desc, priorno03 desc, priorno04 desc";
	$result = query($sql) or error("sql error");
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

	// 카테고리 타이틀 저장
	if($subimg_type == "FIL"){
		if($subimg['size'] > 0){
			file_check($subimg['name']);
			$subimg_ext = strtolower(substr($subimg['name'],-3));
			$subimg_name = $catcode."_sub.".$subimg_ext;
			copy($subimg['tmp_name'], $subimg_path."/".$subimg_name);
			chmod($subimg_path."/".$subimg_name, 0606);
		}
	}else{
		$subimg_name = $subimg02;
	}

	// 메뉴이미지 저장
	$catimg = $_FILES['catimg'] ?? null;
	if(is_array($catimg) && isset($catimg['size']) && $catimg['size'] > 0){
		file_check($catimg['name']);
		$catimg_ext = strtolower(substr($catimg['name'],-3));
		$catimg_name = $catcode."_cat.".$catimg_ext;
		copy($catimg['tmp_name'], $catimg_path."/".$catimg_name);
		chmod($catimg_path."/".$catimg_name, 0606);
	}
	
	$catimg_over = $_FILES['catimg_over'] ?? null;
	if(is_array($catimg_over) && isset($catimg_over['size']) && $catimg_over['size'] > 0){
		file_check($catimg_over['name']);
		$catimg_over_ext = strtolower(substr($catimg_over['name'],-3));
		$catimg_over_name = $catcode."_cat_over.".$catimg_over_ext;
		copy($catimg_over['tmp_name'], $catimg_path."/".$catimg_over_name);
		chmod($catimg_path."/".$catimg_over_name, 0606);
	}

	//  카테고리 저장
	$sql = "insert into wiz_category(catcode,depthno,priorno01,priorno02,priorno03,priorno04,catname,catuse,catimg,catimg_over,subimg,subimg_type,
								prd_tema,prd_num,prd_width,prd_height,recom_use,recom_tema,recom_num,purl,browser_title,searchkey_de,searchkey_cl,searchkey)
								values('$catcode','$depthno','$priorno01','$priorno02','$priorno03','$priorno04',
								'$catname','$catuse','$catimg_name','$catimg_over_name','$subimg_name','$subimg_type','$prd_tema','$prd_num','$prd_width','$prd_height','$recom_use','$recom_tema','$recom_num','$purl','$browser_title','$searchkey_de','$searchkey_cl','$searchkey')";
	$result = query($sql) or error("sql error");

	$depthno--;

	echo "ok|".$mode."|".$depthno."|".$parent_catcode;


}else if($mode == "update"){

	$catname = trim($catname);
	$catname = str_replace("\"","”",$catname);
	$catname = str_replace("'","′",$catname);
	//if($catname == "") error("분류명을 입력하세요.");

	// 카테고리 타이틀 저장
	if($subimg_type == "FIL"){
		if($subimg['size'] > 0){
			file_check($subimg['name']);
			$subimg_ext = strtolower(substr($subimg['name'],-3));
			$subimg_name = $catcode."_sub.".$subimg_ext;
			copy($subimg['tmp_name'], $subimg_path."/".$subimg_name);
			chmod($subimg_path."/".$subimg_name, 0606);

			$subimg_sql = " subimg='$subimg_name', ";
		}
	}else if($subimg_type == "HTM"){
		$subimg_sql = " subimg='$subimg02', ";
	}else{
		$subimg_sql = " subimg='', ";
	}

	$catimg_sql = $catimg_over_sql = ""; // 초기화
	$catimg = [];

	if (
	    isset($_FILES['catimg']) &&
	    is_array($_FILES['catimg']) &&
	    isset($_FILES['catimg']['error']) &&
	    $_FILES['catimg']['error'] === UPLOAD_ERR_OK
	) {
	    $catimg['size'] = $_FILES['catimg']['size'];
	    $catimg['name'] = $_FILES['catimg']['name'];
	    $catimg['tmp_name'] = $_FILES['catimg']['tmp_name'];

	    // 메뉴이미지 저장
	    if ($catimg['size'] > 0) {
	        file_check($catimg['name']);
	        $catimg_ext = strtolower(substr($catimg['name'], -3));
	        $catimg_name = $catcode."_cat.".$catimg_ext;
	        copy($catimg['tmp_name'], $catimg_path."/".$catimg_name);
	        chmod($catimg_path."/".$catimg_name, 0606);
	        $catimg_sql = " catimg='$catimg_name', ";
	    }
	}

	if (
    isset($_FILES['catimg_over']) &&
    is_array($_FILES['catimg_over']) &&
    $_FILES['catimg_over']['error'] === UPLOAD_ERR_OK
	) {
	    $catimg_over['size'] = $_FILES['catimg_over']['size'];
	    $catimg_over['name'] = $_FILES['catimg_over']['name'];
	    $catimg_over['tmp_name'] = $_FILES['catimg_over']['tmp_name'];

	    if ($catimg_over['size'] > 0) {
	        file_check($catimg_over['name']);
	        $catimg_over_ext = strtolower(substr($catimg_over['name'], -3));
	        $catimg_over_name = $catcode."_cat_over.".$catimg_over_ext;
	        copy($catimg_over['tmp_name'], $catimg_path."/".$catimg_over_name);
	        chmod($catimg_path."/".$catimg_over_name, 0606);
	        $catimg_over_sql = " catimg_over='$catimg_over_name', ";
	    }
	}
	$sql = "update wiz_category set catname='$catname', catuse='$catuse', $catimg_sql $catimg_over_sql
					$subimg_sql  subimg_type='$subimg_type',prd_tema='$prd_tema', prd_num='$prd_num',
					prd_width='$prd_width', prd_height='$prd_height', recom_use='$recom_use',
					recom_tema='$recom_tema', recom_num='$recom_num', purl='$purl',
					browser_title='$browser_title',searchkey_de='$searchkey_de',searchkey_cl='$searchkey_cl',searchkey='$searchkey'
					where catcode = '$catcode'";
	query($sql) or error("sql error");

	// 분류숨김시 하위분류도 모두 숨김
	if ($catuse === "N" || (empty($catuse) && isset($catuse) && strcmp($org_catuse, $catuse) !== 0)) {
		if($depthno == "1") $tmp_catcode = substr($catcode,0,2);
		else if($depthno == "2") $tmp_catcode = substr($catcode,0,4);
		else if($depthno == "3") $tmp_catcode = substr($catcode,0,6);
		if($tmp_catcode != ""){
			$sql = "update wiz_category set catuse='$catuse' where catcode like '$tmp_catcode%'";
			//echo $sql;
			query($sql) or error("sql error");
		}
	}

	echo "ok|".$mode."|".$depthno."|".$catcode;

}else if($mode == "delete"){

	if($depthno == 1){ $tmpcode = substr($catcode,0,2); }
	else if($depthno == 2){ $tmpcode = substr($catcode,0,4); }
	else if($depthno == 3){ $tmpcode = substr($catcode,0,6); }
	else if($depthno == 4){ $tmpcode = $catcode; }

	//$depthno = $depthno-1;
	// 하위분류가 존재하면 삭제하지 못함
	$sql = "select catcode from wiz_category where catcode != '$catcode' and catcode like '$tmpcode%'";
	$result = query($sql) or error("sql error");
	if($row = sql_fetch_obj($result)){
		$level = "1";
	}

	// 현재 또는 하위분류에 상품이 존재하면 삭제하지 못함
	$sql = "select wp.prdcode from wiz_cprelation wc, wiz_product wp where wc.catcode = '$catcode' and wc.prdcode = wp.prdcode";
	$result = query($sql) or error("sql error");
	if($row = sql_fetch_obj($result)){
		$level = "2";
	} 

	if($level == 1){
		echo "1|update|".$catcode."|".$depthno;
	} else if($level == 2) {
		echo "2|update|".$catcode."|".$depthno;
	} else {

		$sql = "delete from wiz_category where catcode = '$catcode'";
		$result = query($sql) or error("sql error");

		$sql_prior = "update wiz_category set priorno0{$depthno}=priorno0{$depthno}-1 where depthno='$depthno' and priorno0{$depthno}>$priorno";
		query($sql_prior);

		echo "ok|".$mode."|".$catcode."|".$depthno;

	}

// 카테고리 우선순위
}else if($mode == "updateprior"){

	if($catcode != ""){
		$tmp_catcode = substr($catcode,0, 2*($depthno-1));
		$row_cat = sql_fetch("select * from wiz_category where catcode='$catcode'");
		if($posi == "up"){
			$sql = "select count(*) as cnt from wiz_category where catcode like '".$tmp_catcode."%' and depthno='$depthno' and priorno0".$depthno." < ".$row_cat['priorno0'.$depthno];
			$row_cnt = sql_fetch($sql);
			if($row_cnt['cnt'] < 1){
				echo "error|첫번째 카테고리 입니다";
				exit;
			}
		}else if($posi == "down"){
			$sql = "select count(*) as cnt from wiz_category where catcode like '".$tmp_catcode."%' and depthno='$depthno' and priorno0".$depthno." > ".$row_cat['priorno0'.$depthno];
			$row_cnt = sql_fetch($sql);
			if($row_cnt['cnt'] < 1){
				echo "error|마지막 카테고리 입니다";
				exit;
			}
		}

		$break = false;
		$sel_row = "";
		$chg_row = "";
		$tmp_row = "";
		$sql = "select * from wiz_category where depthno = '$depthno' order by priorno01, priorno02, priorno03, priorno04 asc";
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
			$sql = "update wiz_category set $sel_sql where catcode like '$sel_catcode%'";
			$result = query($sql) or error("sql error");

			$sql = "update wiz_category set $chg_sql where catcode like '$chg_catcode%'";
			$result = query($sql) or error("sql error");
		}

	}

	echo "ok|update|".$catcode."|".$depthno;

}else if($mode == "delsubimg"){
	/*
	foreach (glob($subimg_path."/".$catcode."_sub.*") as $filename) {
		@unlink($filename);
	}
	*/
	$sql = "select subimg from wiz_category where catcode = '$catcode'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_arr($result);

	@unlink($subimg_path."/".$row['subimg']);

	$sql = "update wiz_category set subimg = '' where catcode = '$catcode'";
	$result = query($sql) or error("sql error");
	Header("Location: prd_category.php?mode=update&catcode=$catcode&depthno=$depthno&$menucodeParam");

}else if($mode == "delcatimg"){
	/*
	foreach (glob($catimg_path."/".$catcode."_cat.*") as $filename) {
		echo $filename."<br>";
		@unlink($filename);
	}
	*/
	$sql = "select catimg from wiz_category where catcode = '$catcode'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_arr($result);

	@unlink($catimg_path."/".$row['catimg']);

	$sql = "update wiz_category set catimg = '' where catcode = '$catcode'";
	$result = query($sql) or error("sql error");
	Header("Location: prd_category.php?mode=update&catcode=$catcode&depthno=$depthno&$menucodeParam");

}else if($mode == "delcatimg_over"){
	/*
	foreach (glob($catimg_path."/".$catcode."_cat_over.*") as $filename) {
		@unlink($filename);
	}
	*/
	$sql = "select catimg_over from wiz_category where catcode = '$catcode'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_arr($result);

	@unlink($catimg_path."/".$row['catimg_over']);

	$sql = "update wiz_category set catimg_over = '' where catcode = '$catcode'";
	$result = query($sql) or error("sql error");
	Header("Location: prd_category.php?mode=update&catcode=$catcode&depthno=$depthno&$menucodeParam");

}else if($mode == "delsubimg"){
	$sql = "select subimg from wiz_category where catcode = '$catcode'";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_arr($result);

	@unlink($subimg_path."/".$row['subimg']);

	$sql = "update wiz_category set subimg = '' where catcode = '$catcode'";
	$result = query($sql) or error("sql error");
	Header("Location: prd_category.php?mode=update&catcode=$catcode&depthno=$depthno&$menucodeParam");

} else if ($mode == "reorder") {

	$backup_tbl = "wiz_category_".date("ymd");
	$sql_backup_chk = "SELECT COUNT(*) as cnt FROM Information_schema.tables
		WHERE table_schema = '$db_name'
		AND table_name = '".$backup_tbl."'";
	$backup_chk = sql_fetch($sql_backup_chk);
	if($backup_chk['cnt'] < 1) {
		$sql_backup = "create table ".$backup_tbl." select * from wiz_category";
		query($sql_backup);
	}

	$sql1 = "select * from wiz_category where catcode!='00000000' and depthno='1' order by priorno01 asc";
	$res1 = query($sql1);
	$priorno01 = 1;
	while($row1= sql_fetch_obj($res1)) {
		$sql1_up = "update wiz_category set priorno01='$priorno01', priorno02='0', priorno03='0', priorno04='0' where catcode='".$row1->catcode."'";
		if(query($sql1_up)) {
			$sql2 = "select * from wiz_category where depthno='2' and catcode like '".substr($row1->catcode, 0, 2)."%' order by priorno02 asc";
			$res2 = query($sql2);
			$priorno02 = 1;
			while($row2 = sql_fetch_obj($res2)) {
				$sql2_up = "update wiz_category set priorno01='$priorno01', priorno02='$priorno02', priorno03='0', priorno04='0' where catcode='".$row2->catcode."'";
				if(query($sql2_up)) {
					$sql3 = "select * from wiz_category where depthno='3' and catcode like '".substr($row2->catcode, 0, 4)."%' order by priorno03 asc";
					$res3 = query($sql3);
					$priorno03 = 1;
					while($row3 = sql_fetch_obj($res3)) {
						$sql3_up = "update wiz_category set priorno01='$priorno01', priorno02='$priorno02', priorno03='$priorno03', priorno04='0' where catcode='".$row3->catcode."'";
						if(query($sql3_up)) {
							$sql4 = "select * from wiz_category where depthno='4' and catcode like '".substr($row2->catcode, 0, 6)."%' order by priorno04 asc";
							$res4 = query($sql4);
							$priorno04 = 1;
							while($row4 = sql_fetch_obj($res4)) {
								$sql4_up = "update wiz_category set priorno01='$priorno01', priorno02='$priorno02', priorno03='$priorno03', priorno04='$priorno04' where catcode='".$row4->catcode."'";
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