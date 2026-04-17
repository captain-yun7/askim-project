<?php
include "../../common.php";
include "../../inc/twcenter_check.php";

if($excelup != "ok"){
?>
<html>
<head>
<title>:: 상품정보 업로드 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../js/jquery-1.11.3.min.js"></script>
<script Language="Javascript">
<!--
$(function() {
	var fileTarget = $('.filebox .upload-hidden');

	fileTarget.on('change', function(){		// 값이 변경되면
		if(window.FileReader){				// modern browser
			var filename = $(this)[0].files[0].name;
		} else {  // old IE
			var filename = $(this).val().split('/').pop().split('\\').pop();  // 파일명만 추출
		}

		// 추출한 파일명 삽입
		$(this).siblings('.upload-name').val(filename);
	});
});

function inputCheck(frm) {
	if(frm.upfile.value == "") {
		alert("파일을 첨부해주세요.");
		frm.upfile.focus();
		return false;
	}
}


//-->
</script>
</head>

<body>
<center>
<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">운송장번호 엑셀일괄등록</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<form name="frm" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)">
<input type="hidden" name="excelup" value="ok">
<table width="100%" cellpadding="10" cellspacing="0">
	<tr>
		<td align="center">
			<table width="99%" align="center" border="0" cellpadding="1" cellspacing="1" class="t_style">
				<tr>
					<td height="30" width="25%" align="center" class="t_name">파일첨부</td>
					<td>&nbsp;
						<span class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file">파일 업로드</label>
							<input type="file" name="upfile" id="input-file" class="upload-hidden"> <a href="del_excel.php"><img src="../image/orderlist_down.gif" border="0" align="absmiddle"></a>
						</span>
					</td>
				</tr>
			</table>
			<table width="99%" align="center" border="0" cellpadding="3" cellspacing="6">
				<tr><td height="5"></td></tr>
				<tr>
					<td align="center">
						<input type="submit" value="확 인" class="base_btn reg" style="cursor:pointer">&nbsp;
						<input type="button" value="닫 기" class="base_btn gray" onClick="self.close();" style="cursor:pointer">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
</center>
<div class="helpTip">
	<h4>체크사항</h4>
	<div class="content">
		<div class="explain">
		1. <a href="del_excel.php"><b><font color="black">[주문내역다운로드]</font></b></a>를 클릭하여 주문내역을 다운로드합니다.<br>
		&nbsp&nbsp&nbsp&nbsp<font color="red">다운로드 받은 샘플 형식에 맞춰서 입력하시기 바랍니다.</font><br>
		&nbsp&nbsp&nbsp&nbsp주문내역은 <b style='color:#000000'>`결제완료`</b> 된 건만 다운로드 됩니다.<br />
		2. 운송장번호, 발송날짜에 각각 값을 입력합니다.<br>
		&nbsp&nbsp&nbsp&nbsp발송일자 입력형식(년월일시분)<br />
		&nbsp&nbsp&nbsp&nbsp예) <?=date("Y")?>년 <?=date("m")?>월 <?=date("d")?>일 <?=date("H")?>시 <?=date("i")?>분 = <b style='color:black'><?=date("YmdHi")?></b> <br />
		3. 파일 > 다른이름으로저장 > 파일이름 입력후 > 파일형식을 <br />&nbsp&nbsp&nbsp&nbsp<b><font color="black">"Excel 97 - 2003 통합문서 (*.xls)"</font></b>로 저장합니다.<br>
		4. 저장이된 파일을 파일첨부 창에 넣으신후 확인버튼을 눌러주시면 업데이트가 됩니다.
		</div>
	</div>
</div>

</body>
</html>
<?php
} else {

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
	if($UpFileExt != "xls" && $UpFileExt != "xlsx") {
		error("엑셀파일만 업로드 가능합니다.");
		exit;
	}

	$UpFileName  = $fname.".".$fext;
	$UpLoadPath = $_SERVER["DOCUMENT_ROOT"]."/twcenter/data/upload";
	if(!is_dir($UpLoadPath)) mkdir($UpLoadPath, 0707);
	$UpFilePath = $UpLoadPath."/".date("Ymd_His")."_".$UpFileName;

	if(is_uploaded_file($UpFile["tmp_name"])) {

		if(!move_uploaded_file($UpFile["tmp_name"],$UpFilePath)) {
			error("업로드된 파일을 옮기는 중 에러가 발생했습니다.");
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

		for ($i = 3 ; $i <= $maxRow ; $i++) {

			$orderid           = $objWorksheet->getCell('A' . $i)->getValue();
			$totalprice        = $objWorksheet->getCell('C' . $i)->getValue();
			$delcom            = $objWorksheet->getCell('D' . $i)->getValue();
			$delnum            = $objWorksheet->getCell('E' . $i)->getValue();
			$deldate           = $objWorksheet->getCell('F' . $i)->getValue();

			$deli_sql = "select idx from wiz_delivery_company where del_com='".$delcom."' ";
			$deli_res = query($deli_sql);
			$deli_row = sql_fetch_arr($deli_res);

			$deli_join = $delcom."|".$deli_row['idx'];

			$sql = "update wiz_order
					set deliver_num = '$delnum', deliver_date = '$deldate', del_com = '$deli_join'
					where orderid = '$orderid' and total_price = '$totalprice'";

			query($sql) or error("상품정보 입력 중 오류가 발생하였습니다.");
		}

		unlink($UpFilePath);

	}

	echo "<script>alert('입력되었습니다.');window.opener.document.location.reload();self.close();</script>";

}
?>