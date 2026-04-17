<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/prd_info.php"; ?>
<?

// 검색 파라미터
$param = "page=$page&dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code&dep4_code=$dep4_code&searchopt=$searchopt&searchkey=$searchkey&$menucodeParam";


/*
작업자	: 정나혜 
작업일시	: 2024-01-05
작업내용	: 입력시 db에 영향을 줄 수 있는 어퍼스트로피나 쌍따옴표 같은 입력 치환 (입력, 수정 모두 확인 할 것)
subject, content, mcontent, addinfo1~10, info_name1~10, info_value1~10, shortexp
*/

if($mode == "insert"){

	// 상품넘버 만들기
	$sql = "select max(prdcode) as prdcode from wiz_product2";
	$result = query($sql);
	if($row = sql_fetch_obj($result)){

	  $datenum = substr($row->prdcode,0,6);
	  $tmp_prdnum = substr($row->prdcode,6,4);
	  $tmp_prdnum = substr("000".(++$tmp_prdnum),-4);

	  if($datenum == date('ymd')) $prdcode = $datenum.$tmp_prdnum;
	  else $prdcode = date('ymd')."0001";

	}else{
	  $prdcode = date('ymd')."0001";
	}

	// 상품아이콘
	if($prdicon) {
		for($ii=0; $ii<count($prdicon); $ii++){
		  $prdicon_list .= $prdicon[$ii]."/";
		}
	}
	// 상품이미지 저장
	include "./prd_imgup.php";

	// 첨부파일 저장
	include "./prd_upfile.php";

	$prdname = str_replace("'","′",$prdname);

	// 상품진열 순서
	$prior = date('ymdHis');

	$info_name_sql  = "";
	$info_value_sql = "";

	for($i=1; $i<=10; $i++) {
		$info_name_sql  .= " , info_name".$i."      = '".get_text("input", ${'info_name'.$i})."'    ";
		$info_value_sql .= " , info_value".$i."     = '".get_text("input", ${'info_value'.$i})."'   ";
	}

	$addinfo_sql  = "";

	for($i=1; $i<=10; $i++) {
		$addinfo_sql  .= " , addinfo".$i."      = '".get_text("input", ${'addinfo'.$i})."'          ";
	}

	$prdimg_L_sql  = "";
	$prdimg_M_sql = "";
	$prdimg_S_sql = "";

	for($i=1; $i<=12; $i++) {
		$prdimg_L_sql .= " , prdimg_L".$i."         = '".${'prdimg_L'.$i.'_name'}."'       ";
		$prdimg_M_sql .= " , prdimg_M".$i."         = '".${'prdimg_M'.$i.'_name'}."'       ";
		$prdimg_S_sql .= " , prdimg_S".$i."         = '".${'prdimg_S'.$i.'_name'}."'       ";
	}

	$upfile_sql     = "";
	$upfie_name_sql = "";

	for($i=1; $i<=5; $i++) {
		$upfile_sql     .= " , upfile".$i."         = '".${'upfile'.$i.'_tmp'}."'          ";
		$upfie_name_sql .= " , upfile".$i."_name    = '".${'upfile'.$i.'_name'}."'         ";
	}

	$prdname = get_text("input", $prdname);
	$shortexp = get_text("textarea", $shortexp);
	$content = get_text("textarea", $content);
	$mcontent = get_text("textarea", $mcontent);

	// 상품정보 저장
	$sql_com = "";
	$sql_com .= " prdcode                    = '$prdcode'                        ";
	$sql_com .= " , prdnum                   = '$prdnum'                         ";
	$sql_com .= " , prdname                  = '$prdname'                        ";
	$sql_com .= " , prdprice                 = '$prdprice'                       ";
	$sql_com .= " , showset                  = '$showset'                        ";
	$sql_com .= " , prior                    = '$prior'                          ";
	$sql_com .= " , prdicon                  = '$prdicon'                        ";
	$sql_com .= " , recom                    = '$recom'                          ";
	$sql_com .= " {$info_name_sql}                                               ";
	$sql_com .= " {$info_value_sql}                                              ";
	$sql_com .= " {$addinfo_sql}                                                 ";
	$sql_com .= " , prdimg_R                 = '$prdimg_R_name'                  ";
	$sql_com .= " {$prdimg_L_sql}                                                ";
	$sql_com .= " {$prdimg_M_sql}                                                ";
	$sql_com .= " {$prdimg_S_sql}                                                ";
	$sql_com .= " {$upfile_sql}                                                  ";
	$sql_com .= " {$upfie_name_sql}                                              ";
	$sql_com .= " , shortexp                 = '$shortexp'                       ";
	$sql_com .= " , content                  = '$content'                        ";
	$sql_com .= " , mobileShow               = '$mobileShow'                     ";
	$sql_com .= " , mcontent                 = '$mcontent'                       ";
	$sql_com .= " , prd_seo_use              = '$prd_seo_use'                    ";
	$sql_com .= " , prd_br_title             = '$prd_br_title'                   ";
	$sql_com .= " , prd_descript             = '$prd_descript'                   ";
	$sql_com .= " , prd_classification       = '$prd_classification'             ";
	$sql_com .= " , prd_keywords             = '$prd_keywords'                   ";
	$sql_com .= " , wdate                    = now()                             ";

	$sql = "INSERT INTO wiz_product2 SET {$sql_com} ";
	query($sql);


	// 카테고리 정보 저장
	if(!empty($class04)) {
		$catcode = $class04;
	} else {
		if(!empty($class03)) $catcode = $class03."00";
		else if(!empty($class02)) $catcode = $class02."0000";
		else {
			if(empty($class01)) $class01 = "0000";
			$catcode = $class01."000000";
		}
	}

	$sql = "insert into wiz_cprelation2(idx,prdcode,catcode) values('', '$prdcode', '$catcode')";
	$result = query($sql);

	complete("상품이 입력되었습니다.","prd_input.php?mode=update&prdcode=$prdcode&$param");


}else if($mode == "update"){

	// 상품이미지 삭제
	/*
	작업자		: 이상민
	작업일시	: 2019-08-19
	작업내용	: 파일삭제 checkbox 이름 구분가능하도록 필드명 추가에따른 상품이미지 삭제기능 주석

	for($ii=0; $ii<count($delimg); $ii++){
		if($delimg[$ii] != "") @unlink($imgpath."/".$delimg[$ii]);
	}
	*/

	// 상품이미지 저장
	include "./prd_imgup.php";

	// 첨부파일 저장
	include "./prd_upfile.php";

	$prdname = str_replace("'","′",$prdname);


	// 상품아이콘
	if($prdicon) {
		for($ii=0; $ii<count($prdicon); $ii++){
		  $prdicon_list .= $prdicon[$ii]."/";
		}
	}
	$info_name_sql  = "";
	$info_value_sql = "";

	for($i=1; $i<=10; $i++) {
		$info_name_sql  .= " , info_name".$i."      = '".get_text("input", ${'info_name'.$i})."'    ";
		$info_value_sql .= " , info_value".$i."     = '".get_text("input", ${'info_value'.$i})."'   ";
	}

	$addinfo_sql  = "";

	for($i=1; $i<=10; $i++) {
		$addinfo_sql  .= " , addinfo".$i."      = '".get_text("input", ${'addinfo'.$i})."'          ";
	}

	$prdimg_L_sql  = "";
	$prdimg_M_sql = "";
	$prdimg_S_sql = "";

	for($i=1; $i<=12; $i++) {
		$prdimg_L_sql .= " , prdimg_L".$i."         = '".${'prdimg_L'.$i.'_name'}."'       ";
		$prdimg_M_sql .= " , prdimg_M".$i."         = '".${'prdimg_M'.$i.'_name'}."'       ";
		$prdimg_S_sql .= " , prdimg_S".$i."         = '".${'prdimg_S'.$i.'_name'}."'       ";
	}

	$prdname = get_text("input", $prdname);
	$shortexp = get_text("textarea", $shortexp);
	$content = get_text("textarea", $content);
	$mcontent = get_text("textarea", $mcontent);

	// 상품정보 저장
	$sql_com = "";
	$sql_com .= " prdnum                           = '$prdnum'                   ";
	$sql_com .= " , prdname                        = '$prdname'                  ";
	$sql_com .= " , prdprice                       = '$prdprice'                 ";
	$sql_com .= " , showset                        = '$showset'                  ";
	$sql_com .= " , prdicon                        = '$prdicon'                  ";
	$sql_com .= " , recom                          = '$recom'                    ";
	$sql_com .= " {$info_name_sql}                                               ";
	$sql_com .= " {$info_value_sql}                                              ";
	$sql_com .= " {$addinfo_sql}                                                 ";
	$sql_com .= " , prdimg_R='$prdimg_R_name'                                    ";
	$sql_com .= " {$prdimg_L_sql}                                                ";
	$sql_com .= " {$prdimg_M_sql}                                                ";
	$sql_com .= " {$prdimg_S_sql}                                                ";
	$sql_com .= " , shortexp                       = '$shortexp'                 ";
	$sql_com .= " , content                        = '$content'                  ";
	$sql_com .= " , mobileShow                     = '$mobileShow'               ";
	$sql_com .= " , mcontent                       = '$mcontent'                 ";
	$sql_com .= " , prd_seo_use                    = '$prd_seo_use'              ";
	$sql_com .= " , prd_br_title                   = '$prd_br_title'             ";
	$sql_com .= " , prd_descript                   = '$prd_descript'             ";
	$sql_com .= " , prd_classification             = '$prd_classification'       ";
	$sql_com .= " , prd_keywords                   = '$prd_keywords'             ";	
	$sql_com .= " , mdate                          = now()                       ";
	$sql_com .= " {$upfile_sql}                                                  ";
	
	$sql = "UPDATE wiz_product2 SET {$sql_com} WHERE prdcode = '$prdcode' ";
	query($sql);
	
	// 카테고리 정보 저장
	if(!empty($class04)) {
		$catcode = $class04;
	} else {
		if(!empty($class03)) $catcode = $class03."00";
		else if(!empty($class02)) $catcode = $class02."0000";
		else {
			if(empty($class01)) $class01 = "0000";
			$catcode = $class01."000000";
		}
	}

	$sql = "update wiz_cprelation2 set catcode = '$catcode' where prdcode = '$prdcode' and idx = '$relidx'";
	$result = query($sql);

	complete("상품정보가 수정되었습니다.","prd_input.php?mode=update&prdcode=$prdcode&$param");

}else if($mode == "delete"){

	if($prdcode){

		// 카테고리 연관 삭제
		$sql = "delete from wiz_cprelation2 where prdcode = '$prdcode'";
		$result = query($sql);

		// 상품데이타 삭제
		foreach (glob(WIZHOME_PATH."/data/product2/".$prdcode."*") as $filename) {
   		@unlink($filename);
		}

		$sql = "delete from wiz_product2 where prdcode = '$prdcode'";
		$result = query($sql);

	}else{

		$array_selected = explode("|",$selvalue);
		$i=0;
		while($array_selected[$i]){

			$tmp_prdcode = $array_selected[$i];

			if($tmp_prdcode){

				// 카테고리 연관 삭제
				$sql = "delete from wiz_cprelation2 where prdcode = '$tmp_prdcode'";
				$result = query($sql);

				// 상품데이타 삭제
				foreach (glob(WIZHOME_PATH."/data/product2/".$tmp_prdcode."*") as $filename) {
		   		@unlink($filename);
				}

				$sql = "delete from wiz_product2 where prdcode = '$tmp_prdcode'";
				$result = query($sql);

			}

			$i++;
		}

	}

	complete("선택한 상품을 삭제하였습니다.","prd_list.php?$param");

// 진열순서
}else if($mode == "prior"){

	if(!empty($dep_code)) $catcode_sql = "wc.catcode like '$dep_code$dep2_code$dep3_code$dep4_code%' and ";
	if(!empty($special)) $special_sql = "wp.$special = 'Y' and ";
	if(!empty($display)) $display_sql = "wp.showset = '$display' and ";
	//if(!empty($searchopt)) $search_sql = "wp.$searchopt like '%$searchkey%' and ";

	if($posi == "maximum"){ //최상위
		$sql = "
			SELECT
				max(wp.prior) as max_prior,
				wp.prdname
				
			FROM
			
				wiz_product2 wp,
				wiz_cprelation2 wc

			WHERE
			
				$catcode_sql
				$special_sql
				$display_sql
				$search_sql
				wc.prdcode = wp.prdcode
				
		";
		$result = sql_fetch($sql);
		$prior_update_sql = "update wiz_product2 set prior='".($result['max_prior'] + 1)."' where prdcode='".$prdcode."'";
		query($prior_update_sql);

	}else if($posi == "lowest"){ //최하위
		$sql = "
			SELECT 
				min(wp.prior) as min_prior,
				wp.prdname
				
			FROM
			
				wiz_product2 wp,
				wiz_cprelation2 wc

			WHERE
			
				$catcode_sql
				$special_sql
				$display_sql
				$search_sql
				wc.prdcode = wp.prdcode
				
		";
		$result = sql_fetch($sql);
		$prior_update_sql = "update wiz_product2 set prior='".($result['min_prior'] - 1)."' where prdcode='".$prdcode."'";
		query($prior_update_sql);

	}else{ //기존 1, 10단계씩 이동

		$sql = "
			SELECT DISTINCT 
			
				wp.prdcode,
				wp.prdname,
				wp.prior
				
			FROM
			
				wiz_product2 wp,
				wiz_cprelation2 wc

			WHERE
			
				$catcode_sql
				$special_sql
				$display_sql
				$search_sql
				wc.prdcode = wp.prdcode
				
		";

		// 1단계위로
		if($posi == "up"){

			$sql .= " AND wp.prior >= '".$prior."' AND wp.prdcode != '".$prdcode."' ORDER BY wp.prior ASC limit 1";
			$result = query($sql);

			if($row = sql_fetch_obj($result)){

				$change_prior = $row->prior;
				$change_prdcode = $row->prdcode;

				$prior_sql_1 = "UPDATE wiz_product2 SET prior=(prior+1) WHERE prior>'".$change_prior."'";
				$prior_result = query($prior_sql_1);

				$prior_sql_2 = "UPDATE wiz_product2 SET prior = '".($change_prior+1)."' WHERE prdcode = '".$prdcode."'";
				$prior_result_2 = query($prior_sql_2);

			}

		// 1단계아래로
		} else if($posi == "down") {

			$sql .= " AND wp.prior <= '".$prior."' AND wp.prdcode != '".$prdcode."' ORDER BY wp.prior DESC limit 1";
			$result = query($sql);

			if($row = sql_fetch_obj($result)){

				$change_prior = $row->prior;
				$change_prdcode = $row->prdcode;

				$prior_sql_1 = "UPDATE wiz_product2 SET prior=(prior-1) WHERE prior<'".$change_prior."'";
				$prior_result_1 = query($prior_sql_1);

				$prior_sql_2 = "UPDATE wiz_product2 SET prior = '".($change_prior-1)."' WHERE prdcode = '".$prdcode."'";
				$prior_result_2 = query($prior_sql_2);
			}

		// 10단계위로
		}else if($posi == "upup"){

			$count=1;

			$sql .= " AND wp.prior >= '".$prior."' AND wp.prdcode != '".$prdcode."' ORDER BY wp.prior ASC limit 10";
			$result = query($sql);
			$total = sql_fetch_row($result);

			while($row = sql_fetch_obj($result)){

				if($total==$count){
					$change_prior = $row->prior;
					$change_prdcode = $row->prdcode;
					$change_name = $row->prdname;
				}
				$count++;
			}

			if($total > 0){

				$prior_sql_1 = "UPDATE wiz_product2 SET prior=(prior+1) WHERE prior>'".$change_prior."'";
				$prior_result_1 = query($prior_sql_1);

				$prior_sql_2 = "UPDATE wiz_product2 SET prior = '".($change_prior+1)."' WHERE prdcode = '".$prdcode."'";
				$prior_result_2 = query($prior_sql_2);

			}

		// 10단계아래로
		}else if($posi == "downdown"){

			$count=1;

			$sql .= " AND wp.prior <= '".$prior."' AND wp.prdcode != '".$prdcode."' ORDER BY wp.prior DESC limit 10";
			$result = query($sql);
			$total = sql_fetch_row($result);

			while($row = sql_fetch_obj($result)){

				if($total==$count){
					$change_prior = $row->prior;
					$change_prdcode = $row->prdcode;
					$change_name = $row->prdname;
				}
				$count++;
			}

			if($total > 0){

				$prior_sql_1 = "UPDATE wiz_product2 SET prior=(prior-1) WHERE prior<'".$change_prior."'";
				$prior_result_1 = query($prior_sql_1);

				$prior_sql_2 = "UPDATE wiz_product2 SET prior = '".($change_prior-1)."' WHERE prdcode = '".$prdcode."'";
				$prior_result_2 = query($prior_sql_2);

			}
		}
	}

	complete("진열순서를 변경하였습니다.","prd_list.php?$param");

// 상품아이콘
}else if($mode == "prdicon"){

	if($upfile_size > 0){
		copy($upfile, "../../data/product2/".$upfile_name);
		chmod("../../data/product2/".$upfile_name, 0606);
	}

	complete('등록되었습니다.','prd_icon.php&$menucodeParam');


// 아이콘삭제
}else if($mode == "icondel"){

	@unlink("../../data/product2/".$prdicon);

	complete('삭제되었습니다.','prd_icon.php&$menucodeParam');

// 관련상품 등록
}else if($mode == "reladd"){

	$array_selected = explode("|",$selected);
	$i=0;
	while($array_selected[$i]){

		$tmp_prdcode = $array_selected[$i];

		$sql = "insert into wiz_prdrelation2(idx,prdcode,relcode) values('','$prdcode','$tmp_prdcode')";
		query($sql);

		$i++;
	}

	echo "<script>opener.document.location.reload();</script>";
	complete("등록되었습니다.","prd_rellist.php?$param");

// 관련상품 삭제
}else if($mode == "reldel"){

	$sql = "delete from wiz_prdrelation2 where idx = '".$idx."'";
	query($sql);

	complete("삭제되었습니다.","prd_input.php?prdcode=".$prdcode."&mode=update&".$menucodeParam);

// 관련상품(다중체크)
}else if($mode == "multireldel"){

	$relIdx = explode(",",$chkval);
	if($relIdx) {
		for($ii=0;$ii<count($relIdx);$ii++){
			$sql = "delete from wiz_prdrelation2 where idx = '".$relIdx[$ii]."'";
			query($sql);
		}
	}
	print "delok";

} else if($mode == "set_common_seo"){
	// 공통 SEO 등록
	$str = array();
	$browser_title = get_text("input", $_POST['browser_title']);
	$searchkey_de = get_text("textarea", $_POST['searchkey_de']);
	$searchkey_cl = get_text("textarea", $_POST['searchkey_cl']);
	$searchkey = get_text("textarea", $_POST['searchkey']);

	$sql = " 
			update 
				wiz_siteinfo 
			set 
				prd2_browser_title = '".$browser_title."'
				, prd2_searchkey_de = '".$searchkey_de."'
				, prd2_searchkey_cl= '".$searchkey_cl."'
				, prd2_searchkey = '".$searchkey."'
	";
	$result = query($sql);

	if(!$result){
		$str['result'] = "100";
		$str['msg'] = "DB저장 오류";
	} else {
		$str['result'] = "000";
		$str['msg'] = "상품 공통 SEO정보가 저장되었습니다.";
	}

	echo json_encode($str);
}
?>
