<?php
include $_SERVER["DOCUMENT_ROOT"]."/twcenter/common.php";
include $_SERVER["DOCUMENT_ROOT"]."/twcenter/inc/twcenter_check.php";
include $_SERVER["DOCUMENT_ROOT"]."/twcenter/inc/site_info.php";

switch($code){
	case "inquiry":
		$code = "online";
		break;
}

include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbs_info.php";

set_time_limit(0);
ini_set('memory_limit','128M');

require_once $_SERVER["DOCUMENT_ROOT"]."/comm/plugin/PHPExcel/Classes/PHPExcel.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/comm/plugin/PHPExcel/Classes/PHPExcel/IOFactory.php";
$objPHPExcel = new PHPExcel();

$sheet      = $objPHPExcel->getActiveSheet();
$sheetIndex = $objPHPExcel->setActiveSheetIndex(0);

$sheetIndex->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheetIndex->getStyle('A:L')->getFont()->setName('맑은 고딕')->setSize(10)->setBold(false);
$sheetIndex->getRowDimension('A:L')->setRowHeight(20);

$sheetIndex->duplicateStyleArray(
	array(
		'fill' => array(
			'type'  => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb'=>'CCCCCC')
		),
		'font' => array(
			'bold' => false,
			'size' => 10,
			'color' => array('rgb'=>'353944')
		),
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
		)
	),
	'A1:L1'
);

$sheetIndex->getColumnDimension('A')->setWidth(20);
$sheetIndex->getColumnDimension('B')->setWidth(20);
$sheetIndex->getColumnDimension('C')->setWidth(20);
$sheetIndex->getColumnDimension('D')->setWidth(20);
$sheetIndex->getColumnDimension('E')->setWidth(20);
$sheetIndex->getColumnDimension('F')->setWidth(20);
$sheetIndex->getColumnDimension('G')->setWidth(20);
$sheetIndex->getColumnDimension('H')->setWidth(20);
$sheetIndex->getColumnDimension('I')->setWidth(20);
$sheetIndex->getColumnDimension('J')->setWidth(20);
$sheetIndex->getColumnDimension('K')->setWidth(20);
$sheetIndex->getColumnDimension('L')->setWidth(20);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A1", "아이디")
            ->setCellValue("B1", "이름")
            ->setCellValue("C1", "휴대폰")
            ->setCellValue("D1", "이메일")
            ->setCellValue("E1", "주소")
            ->setCellValue("F1", "신청일")
            ->setCellValue("G1", "예약프로그램")
            ->setCellValue("H1", "예약일")
            ->setCellValue("I1", "예약시간")
            ->setCellValue("J1", "예약인원")
            ->setCellValue("K1", "전달사항")
            ->setCellValue("L1", "진행상태");

$itemsAr = explode("_", $items);
$day = $itemsAr[0];

$sql = "
	select wb.*
		 , wb.wdate as wtime
		 , from_unixtime(wb.wdate, '".$bbs_info['datetype_list']."') as wdate
		 , wc.catname
		 , wc.caticon
	  from wiz_bbs as wb 
	  left join wiz_bbscat as wc 
	    on wb.category = wc.idx
	 where wb.code = '$code' 
	   and wb.notice != 'Y' 
	   and addinfo1 = '".$itemsAr[1]."'
	   and addinfo2 = '".date("Y-m-d", strtotime($year."-".$month."-".$day))."' 
	   and addinfo3 = '".$times."'
	--   and status in (4, 5)
	 order by wb.prino desc 
";


$result = query($sql);
$start = 2;
$idx = 1;

for ($i=$start; $row=sql_fetch_arr($result); $i++) {
	switch($code){
		case "online":
			$asql = " select subject from wiz_bbs where code = 'inquiry' and idx = '".$row["addinfo1"]."' ";
			$arow = sql_fetch($asql);
			break;
	}



	switch($row["status"]){
		case "1":
			$status = "접수";
			break;
		case "2":
			$status = "승인";
			break;
		case "3":
			$status = "취소";
			break;
		case "4":
			$status = "완료";
			break;
		case "5":
			$status = "출석";
			break;
		case "6":
			$status = "노쇼";
			break;
		default:
			$status = "";
	}

	if($row["zipcode"] != "" && $row["address"] != ""){
		$address = "(".$row["zipcode"].") ".preg_replace("/\^\^/i", " ", $row["address"]);
	} else {
		$address = "";
	}

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit("A$i", $row['memid'], PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("B$i", $row['name'])
                ->setCellValue("C$i", $row['hphone'])
                ->setCellValue("D$i", $row['email'])
                ->setCellValue("E$i", $address)
                ->setCellValue("F$i", $row['wdate'])
                ->setCellValue("G$i", $arow['subject'])
                ->setCellValue("H$i", $row['addinfo2'])
                ->setCellValue("I$i", $row['addinfo3'])
                ->setCellValue("J$i", $row['addinfo4'])
                ->setCellValue("K$i", $row['subject'])
                ->setCellValue("L$i", $status);


	$idx++;
}

/* 전체테두리 설정 --------------------- -- */
$sheetIndex->getStyle(sprintf('A1:L%s', $idx))->getBorders()->applyFromArray(
	array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('rgb' => '353944')
		)
	)
);
/* 전체데이터 가운데정렬 --------------------- -- */
$sheetIndex->getStyle(sprintf('A'.$start.':L%s', $idx))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$excel_title = "(".$year.$month.$day."_".preg_replace("/\:/i", ";", $times).") ".$arow['subject'];

$objPHPExcel->getActiveSheet()->setTitle("sheet1");
$objPHPExcel->setActiveSheetIndex(0);

$filename = iconv("UTF-8", "EUC-KR", $excel_title)."_".date('Ymd');
//$filename = iconv("UTF-8", "EUC-KR", $excel_title);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
ob_end_clean();
$objWriter->save('php://output');

exit;
?>