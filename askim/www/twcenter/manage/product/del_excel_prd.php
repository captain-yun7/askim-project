<?php
include $_SERVER["DOCUMENT_ROOT"]."/twcenter/common.php";
include $_SERVER["DOCUMENT_ROOT"]."/twcenter/inc/twcenter_check.php";
include $_SERVER["DOCUMENT_ROOT"]."/twcenter/inc/site_info.php";

set_time_limit(0);
ini_set('memory_limit','128M');

require_once $_SERVER["DOCUMENT_ROOT"]."/comm/plugin/PHPExcel/Classes/PHPExcel.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/comm/plugin/PHPExcel/Classes/PHPExcel/IOFactory.php";
$objPHPExcel = new PHPExcel();

$sheet      = $objPHPExcel->getActiveSheet();
$sheetIndex = $objPHPExcel->setActiveSheetIndex(0);

$sheetIndex->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheetIndex->getStyle('A:G')->getFont()->setName('맑은 고딕')->setSize(10)->setBold(false);
$sheetIndex->getRowDimension('A:G')->setRowHeight(20);

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
	'A2:G2'
);

$t_deli_arr = array();
$deli_sql = "select del_com from wiz_delivery_company where del_com2 = 'Y' ";
$deli_res = query($deli_sql);
while($deli_row = sql_fetch_arr($deli_res)) {
	$t_deli_arr[] = $deli_row['del_com'];
}
$deli_info = "※ 등록된 배송업체 : ".implode(",", $t_deli_arr);

$sheetIndex->getColumnDimension('A')->setWidth(20);
$sheetIndex->getColumnDimension('B')->setWidth(20);
$sheetIndex->getColumnDimension('C')->setWidth(20);
$sheetIndex->getColumnDimension('D')->setWidth(20);
$sheetIndex->getColumnDimension('E')->setWidth(20);
$sheetIndex->getColumnDimension('F')->setWidth(20);
$sheetIndex->getColumnDimension('G')->setWidth(20);

$objPHPExcel->setActiveSheetIndex(0)
			->mergeCells("A1:G1")->setCellValue("A1", "$deli_info")
            ->setCellValue("A2", "주문번호")
			->setCellValue("B2", "상품코드")
            ->setCellValue("C2", "주문자명")
            ->setCellValue("D2", "주문금액")
            ->setCellValue("E2", "배송업체")
            ->setCellValue("F2", "운송장번호")
            ->setCellValue("G2", "발송일자");

/*
$sql = "
	select *
	  from wiz_order
	 where orderid !='' 
	   and status = 'OY'
	   and deliver_num = ''
	 order by orderid desc
";
*/

$sql = "SELECT wo.*					
			 , wb.prdname		
			 , wb.prdprice		
			 , wb.prdcode		
			 , wb.amount		
			 , wb.opttitle3		
			 , wb.optcode3		
			 , wb.opttitle4		
			 , wb.optcode4		
			 , wb.opttitle5		
			 , wb.optcode5		
			 , wb.opttitle6		
			 , wb.optcode6		
			 , wb.opttitle7		
			 , wb.optcode7		
			 , wb.opttitle8		
			 , wb.optcode8		
			 , wb.opttitle9		
			 , wb.optcode9		
			 , wb.opttitle10		
			 , wb.optcode10		
			 , wb.opttitle11		
			 , wb.optcode11		
			 , wb.opttitle		
			 , wb.optcode		
			 , wb.opttitle2		
			 , wb.optcode2		
			 , wb.del_com	
			 , wb.del_num	
			 , wb.del_type		
			 , wb.del_price		
		FROM wiz_order AS wo			
		LEFT JOIN wiz_basket AS wb 			
		  ON wo.orderid = wb.orderid			
		WHERE wo.orderid != ''			
		 AND wo.status = 'OY'			
	 ORDER BY wo.orderid DESC		
";

$result = query($sql);

$idx = 1;
$start = 3;
for ($i=$start; $row=sql_fetch_arr($result); $i++) {

	$orderid     = $row['orderid'];
	$send_name   = $row['send_name'];
	$prdcode = $row['prdcode'];
	$total_price = $row['prdprice'];

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit("A$i", "$orderid", PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("B$i", "$prdcode")
		        ->setCellValue("C$i", "$send_name")
                ->setCellValue("D$i", "$total_price")
                ->setCellValue("E$i", "")
                ->setCellValue("F$i", "")
                ->setCellValue("G$i", "");


	$idx++;
}

/* 전체테두리 설정 --------------------- -- */
$sheetIndex->getStyle(sprintf('A1:G%s', $idx+1))->getBorders()->applyFromArray(
	array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('rgb' => '353944')
		)
	)
);
/* 전체데이터 가운데정렬 --------------------- -- */
$sheetIndex->getStyle(sprintf('A'.$start.':G%s', $idx+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setTitle("운송장번호업데이트");
$objPHPExcel->setActiveSheetIndex(0);

$filename = iconv("UTF-8", "EUC-KR", "운송장번호업데이트")."_".date('Ymd');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

echo $idx;
exit;

?>