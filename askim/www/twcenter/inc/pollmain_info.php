<?
$sql = "select * from wiz_pollinfo";
$result = query($sql);
if($row = sql_fetch_arr($result)){

	$purl = "/".$row['purl'];
	$skin = $row['mainskin'];
	
	// 스킨위치
	$skin_dir = "/twcenter/poll/skin/".$row['skin'];
	
}else{
	
	$purl = "#";
	$skin = "
	<table width='100%' cellspacing='0' cellpadding='0' border='0'>
		<tr><td><b>{SUBJECT}</b></td></tr>
		<tr><td>{CONTENT}</td></tr>
		[LOOP]
		<tr><td><img src='/twcenter/bbsmain/image/point.gif' align='absmiddle'> {QUESTION}</td></tr>
		[LOOP2]
		<tr><td> {ANSWER} </td></tr>
		[/LOOP2]
		[/LOOP]
		<tr><td height=5></td></tr>
		<tr><td align=center>{VOTE} {RESULT}</td></tr>
	</table>";

	// 스킨위치
	$skin_dir = "/twcenter/poll/skin/pollBasic";
}

?>