<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/twcenter/common.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/twcenter/inc/twcenter_check.php";
/**
 * 업로드 최대 실행시간 무제한, 메모리제한 30M (무제한 -1)
 */
set_time_limit(0);
ini_set('memory_limit','-1');

require_once $_SERVER["DOCUMENT_ROOT"]."/comm/plugin/PHPExcel/Classes/PHPExcel.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/comm/plugin/PHPExcel/Classes/PHPExcel/IOFactory.php";
$objPHPExcel = new PHPExcel();


$UpFile	         = $_FILES["upfile"];
$tmpUPfilename   = $UpFile["name"];

list($fname, $fext) = explode(".", $tmpUPfilename);

if(preg_match("/[\xE0-\xFF][\x80-\xFF][\x80-\xFF]/", $fname)) {
	$fname = time();
} else {
	$fname = $fname;
}

$UpFileExt		= strtolower($fext);
$LastColumn     = "T";

if($UpFileExt != "xls") {
	echo json_encode(json_result("00","엑셀파일만 업로드 가능합니다."));
	exit;
}

$UpFileName  = $fname.".".$fext;
$UpLoadPath = $_SERVER["DOCUMENT_ROOT"]."/twcenter/data/upload";
if(!is_dir($UpLoadPath)) mkdir($UpLoadPath, 0707);
$UpFilePath = $UpLoadPath."/".date("Ymd_His")."_".$UpFileName;

$upfile_path = $_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg";		// 파일이미지 업로드 경로
$img_path    = $_SERVER['DOCUMENT_ROOT']."/upload";					// 업로드된 파일이미지 경로

if(is_uploaded_file($UpFile["tmp_name"])) {

	if(!move_uploaded_file($UpFile["tmp_name"],$UpFilePath)) {
		echo json_encode(json_result("00","업로드된 파일을 옮기는 중 에러가 발생했습니다."));
		exit;
	}

	$objReader = PHPExcel_IOFactory::createReaderForFile($UpFilePath);
	$objReader->setReadDataOnly(true);
	
	$objExcel = $objReader->load($UpFilePath);
	$objExcel->setActiveSheetIndex(0);

	$objWorksheet = $objExcel->getActiveSheet();
	$rowIterator = $objWorksheet->getRowIterator();

	foreach ($rowIterator as $row) { // 모든 행에 대해서
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false); 
	}

	$maxRow = $objWorksheet->getHighestRow();
	$maxCell = $objWorksheet->getHighestColumn();

	if($maxCell != $LastColumn) {
		echo json_encode(json_result("00","업로드하려는 양식이 틀립니다\n확인후 다시 업로드하세요."));
		@unlink($UpFilePath);
		exit;
	}


	for ($i = 2 ; $i <= $maxRow ; $i++) {

		// 상품넘버 만들기, 우선순위
		$sql = "select max(prdcode) as prdcode, max(prior) as prior from wiz_product";
		$result = query($sql);
		if($row = sql_fetch_obj($result)){

			$datenum = substr($row->prdcode,0,6);
			$prdnum = substr($row->prdcode,6,4);
			$prdnum = substr("000000".(++$prdnum),-4);

			if($datenum == date('ymd')) $prdcode = $datenum.$prdnum;
			else $prdcode = date('ymd')."0001";

			$prior = $row->prior + 1;

		}else{
			$prdcode = date('ymd')."0001";
			$prior = date('ymdHis');
		}

		$prdname               = trim(str_replace("\"", "", $objWorksheet->getCell('A' . $i)->getValue()));
		$prdname               = str_replace("'", "\'", $prdname);

		/*
		작업일시	: 2020-08-25
		작업자명	: 이상민
		작업내용	: 상품 엑셀 업로드 시 분류명 중복에 따른 오류해결을 위해 분류코드를 넣도록 수정
		*/
		$catcode               = trim($objWorksheet->getCell('B' . $i)->getValue());
		$prdgrp                = trim($objWorksheet->getCell('C' . $i)->getValue());

		$new = ""; $popular = ""; $recom = ""; $sale = "";
		$prd_grp = explode("/", $prdgrp);
		for($jj = 0; $jj < count($prd_grp); $jj++) {
			if(!strcmp($prd_grp[$jj], "신상품"))  $new = "Y";
			if(!strcmp($prd_grp[$jj], "인기상품")) $popular = "Y";
			if(!strcmp($prd_grp[$jj], "추천상품")) $recom = "Y";
			if(!strcmp($prd_grp[$jj], "세일상품")) $sale = "Y";
		}

		$brand                 = trim($objWorksheet->getCell('D' . $i)->getValue());

		$sql = "select idx from wiz_brand where brdname = '".$brand."'";
		$result = query($sql);
		$row = sql_fetch_arr($result);
		$brand   = $row['idx'];

		$prdcom                = trim($objWorksheet->getCell('E' . $i)->getValue());	// 제조사
		$origin                = trim($objWorksheet->getCell('F' . $i)->getValue());	// 원산지
		$showset               = trim($objWorksheet->getCell('G' . $i)->getValue());	// 상품진열
		$shortage              = trim($objWorksheet->getCell('H' . $i)->getValue());	// 품절여부
		$stock                 = trim($objWorksheet->getCell('I' . $i)->getValue());	// 재고량
		$sellprice             = trim($objWorksheet->getCell('J' . $i)->getValue());	// 판매가
		$conprice              = trim($objWorksheet->getCell('K' . $i)->getValue());	// 정가
		$reserve               = trim($objWorksheet->getCell('L' . $i)->getValue());	// 적립금

		$prdimg_path = "../../data/prdimg";

		$prdimg_R              = trim($objWorksheet->getCell('M' . $i)->getValue());
		$ext = end(explode('.', $prdimg_R)); 
		$prdimg_R_name = $prdcode."_R.".$ext;

		$prdimg_1              = trim($objWorksheet->getCell('N' . $i)->getValue());
		$prdimg_2              = trim($objWorksheet->getCell('O' . $i)->getValue());
		$prdimg_3              = trim($objWorksheet->getCell('P' . $i)->getValue());
		$prdimg_4              = trim($objWorksheet->getCell('Q' . $i)->getValue());
		$prdimg_5              = trim($objWorksheet->getCell('R' . $i)->getValue());

		if(!strcmp(substr($prdimg_1, 0, 1), "/")) $prdimg_1 = substr($prdimg_1, 1, strlen($prdimg_1));
		if(!strcmp(substr($prdimg_2, 0, 1), "/")) $prdimg_2 = substr($prdimg_2, 1, strlen($prdimg_2));
		if(!strcmp(substr($prdimg_3, 0, 1), "/")) $prdimg_3 = substr($prdimg_3, 1, strlen($prdimg_3));
		if(!strcmp(substr($prdimg_4, 0, 1), "/")) $prdimg_4 = substr($prdimg_4, 1, strlen($prdimg_4));
		if(!strcmp(substr($prdimg_5, 0, 1), "/")) $prdimg_5 = substr($prdimg_5, 1, strlen($prdimg_5));

		list($prdimg_L1, $prdimg_M1, $prdimg_S1) = explode("/", $prdimg_1);
		list($prdimg_L2, $prdimg_M2, $prdimg_S2) = explode("/", $prdimg_2);
		list($prdimg_L3, $prdimg_M3, $prdimg_S3) = explode("/", $prdimg_3);
		list($prdimg_L4, $prdimg_M4, $prdimg_S4) = explode("/", $prdimg_4);
		list($prdimg_L5, $prdimg_M5, $prdimg_S5) = explode("/", $prdimg_5);

		if(@file($img_path."/".$prdimg_R)) copy($img_path."/".$prdimg_R, $upfile_path."/".$prdimg_R_name);
		@unlink($img_path."/".$prdimg_R);

		for($j=1; $j<=5; $j++) {

			${'Lext'.$j} = end(explode('.', ${'prdimg_L'.$j}));
			${'Mext'.$j} = end(explode('.', ${'prdimg_M'.$j}));
			${'Sext'.$j} = end(explode('.', ${'prdimg_S'.$j}));

			${'prdimg_L'.$j.'_name'} = $prdcode."_L1.".${'Lext'.$j};
			${'prdimg_M'.$j.'_name'} = $prdcode."_M1.".${'Mext'.$j};
			${'prdimg_S'.$j.'_name'} = $prdcode."_S1.".${'Sext'.$j};


			if(@file($img_path."/".${'prdimg_L'.$j})) copy($img_path."/".${'prdimg_L'.$j}, $upfile_path."/".${'prdimg_L'.$j.'_name'});
			if(@file($img_path."/".${'prdimg_M'.$j})) copy($img_path."/".${'prdimg_M'.$j}, $upfile_path."/".${'prdimg_M'.$j.'_name'});
			if(@file($img_path."/".${'prdimg_S'.$j})) copy($img_path."/".${'prdimg_S'.$j}, $upfile_path."/".${'prdimg_S'.$j.'_name'});

			@unlink($img_path."/".${'prdimg_L'.$j});
			@unlink($img_path."/".${'prdimg_M'.$j});
			@unlink($img_path."/".${'prdimg_S'.$j});

		}

		$stortexp             = trim(addslashes($objWorksheet->getCell('S' . $i)->getValue()));	// 관리자주석
		$content              = trim(addslashes($objWorksheet->getCell('T' . $i)->getValue()));	// 상세설명

		$prdimg_L_sql = "";
		$prdimg_M_sql = "";
		$prdimg_S_sql = "";

		for($j=1; $j<=5; $j++) {
			if(${'Lext'.$j}) $prdimg_L_sql .= " , prdimg_L".$j."         = '".${'prdimg_L'.$j.'_name'}."'                   ";
			if(${'Mext'.$j}) $prdimg_M_sql .= " , prdimg_M".$j."         = '".${'prdimg_M'.$j.'_name'}."'                   ";
			if(${'Sext'.$j}) $prdimg_S_sql .= " , prdimg_S".$j."         = '".${'prdimg_S'.$j.'_name'}."'                   ";
		}

		$sql_com = "";
		$sql_com .= " prdcode                = '".$prdcode."'                 ";
		$sql_com .= " , prdname              = '".$prdname."'                 ";
		$sql_com .= " , new                  = '".$new."'                     ";
		$sql_com .= " , best                 = '".$best."'                    ";
		$sql_com .= " , popular              = '".$popular."'                 ";
		$sql_com .= " , recom                = '".$recom."'                   ";
		$sql_com .= " , sale                 = '".$sale."'                    ";
		$sql_com .= " , brand                = '".$brand."'                   ";
		$sql_com .= " , prdcom               = '".$prdcom."'                  ";
		$sql_com .= " , origin               = '".$origin."'                  ";
		$sql_com .= " , showset              = '".$showset."'                 ";
		$sql_com .= " , shortage             = '".$shortage."'                ";
		$sql_com .= " , stock                = '".$stock."'                   ";
		$sql_com .= " , prior                = '".$prior."'                   ";
		$sql_com .= " , sellprice            = '".$sellprice."'               ";
		$sql_com .= " , conprice             = '".$conprice."'                ";
		$sql_com .= " , reserve              = '".$reserve."'                 ";
		$sql_com .= " , del_type              = 'DA'                 ";
		$sql_com .= " , prdimg_R             = '".$prdimg_R_name."'           ";
		$sql_com .= " {$prdimg_L_sql}                                         ";
		$sql_com .= " {$prdimg_M_sql}                                         ";
		$sql_com .= " {$prdimg_S_sql}                                         ";
		$sql_com .= " , stortexp             = '".$stortexp."'                ";
		$sql_com .= " , content              = '".$content."'                 ";
		$sql_com .= " , wdate                = now()                          ";

		$sql = "insert into wiz_product set {$sql_com} ";
		query($sql);

		// 카테고리
		$sql = "select catcode from wiz_category where catcode = '".$catcode."'";
		$result = query($sql);
		$row = sql_fetch_arr($result);

		if($row["catcode"] != ""){
			$sql = "insert into wiz_cprelation(prdcode, catcode) values ('".$prdcode."', '".$catcode."')";
			if(!query($sql)) {
				echo json_encode(json_result("00","상품분류 입력 중 오류가 발생하였습니다."));
				exit;
			}
		}

	}

	/* 업로드된 파일삭제 */
	@unlink($UpFilePath);

	echo json_encode(json_result("00","엑셀등록이 되었습니다.\n정상적으로 등록이 되었는지 반드시 확인하세요."));
	exit;

} else {

	echo json_encode(json_result("00","엑셀업로드가 정상적으로 이루어지지 않았습니다.\n다시 시도해주세요."));
	exit;

}
?>