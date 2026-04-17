<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/form_info.php"; ?>
<?

	$filename = $form_info['title']."[".date('Ymd')."].xls";

	header( "Content-type: application/vnd.ms-excel" );
	header( "Content-Disposition: attachment; filename=$filename" );
	header( "Content-Description: PHP4 Generated Data" );

	$fidx = $form_info['idx'];

	$sql = "select * from wiz_formfield where fidx = '$fidx' and ftype != 'spamcheck' and ftype != 'file' order by fprior asc, idx asc";
	$result = query($sql);
	$no = 0;
	while($row = sql_fetch_arr($result)){

		if(empty($excel_title)) $excel_title = $row['fname']."	";
		else $excel_title .= $row['fname']."	";

		$title_arr[$no][idx] = $row['idx'];
		$title_arr[$no][ftype] = $row['ftype'];

		$no++;

	}

	$excel_title .= "첨부파일1	";
	$excel_title .= "첨부파일2	";
	$excel_title .= "첨부파일3	";
	$excel_title .= "처리상태	";
	$excel_title .= "등록일";

	echo $excel_title."\n";

	$sql = "select * from wiz_form where code = '$code' order by idx asc";
	$result = query($sql);
	while($row = sql_fetch_arr($result)){

		$content = explode("|^|", $row['content']);

		for($ii = 0; $ii < count($content); $ii++) {
			list($frm_idx, $frm_data) = explode("||", $content[$ii]);
			$data_arr[$frm_idx] = $frm_data;
		}

		$excel_data = "";
		for($ii = 0; $ii < count($title_arr); $ii++) {
			if(strcmp($title_arr[$ii][ftype], "file")) $excel_data .= strip_tags(str_replace("	", " ", str_replace("\r\n", " ", str_replace("&nbsp;", " ", $data_arr[$title_arr[$ii][idx]]))))."	";
		}

		if(!empty($row['upfile1'])) $row['upfile1'] = "http://".$_SERVER["HTTP_HOST"]."/twcenter/data/form/".$row['upfile1'];
		if(!empty($row['upfile2'])) $row['upfile1'] = "http://".$_SERVER["HTTP_HOST"]."/twcenter/data/form/".$row['upfile2'];
		if(!empty($row['upfile3'])) $row['upfile1'] = "http://".$_SERVER["HTTP_HOST"]."/twcenter/data/form/".$row['upfile3'];

		$excel_data .= $row['upfile1']."	";
		$excel_data .= $row['upfile2']."	";
		$excel_data .= $row['upfile3']."	";
		$excel_data .= $row['status']."	";
		$excel_data .= $row['wdate'];

		echo $excel_data."\n";
	}

?>