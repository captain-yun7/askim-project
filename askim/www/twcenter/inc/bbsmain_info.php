<?
$sql = "select * from wiz_bbsmain where code='$code' and idx = '$bidx'";
$result = query($sql);
if($row = sql_fetch_arr($result)){

	$cnt = $row['cnt'];
	$line = $row['line'];
	$btype = $row['btype'];
	$purl = "/".$row['purl'];
	$skin = $row['skin'];
	$subject_len = $row['subject_len'];
	$content_len = $row['content_len'];
	
}else{
	
	$cnt = 5;
	$line = 0;
	$btype = "BBS";
	$purl = "#";
	$skin = "
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
  [LOOP]
	<tr>
	<td height=\"22\"><img src=\"/twcenter/bbsmain/image/point.gif\" width=\"3\" height=\"3\"></td>
	<td width=\"5\"></td>
	<td><a href=\"{LINK}\">{SUBJECT}</a>{NEW}</td>
	<td align=\"right\">{DATE}</td>
	</tr>
	[/LOOP]
	</table>
	";
	$subject_len = 30;
	$content_len = 100;

}

?>