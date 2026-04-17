<?
$sql = "select * from wiz_prdmain where idx = '$pidx'";
$row = sql_fetch($sql);

if(!empty($row['idx'])){	
	$prdcnt = $row['prdcnt'];
	$prdline = $row['prdline'];
	$maintype = $row['maintype'];
	$mainskin = $row['mainskin'];
	$prdname_len = $row['prdname_len'];
	$prdexp_len = $row['prdexp_len'];

}else{
	
	$prdcnt = 5;
	$prdline = 0;
	$maintype = "wdate";
	$mainskin = "<table width='100%' cellspacing='0' cellpadding='0' border='0'>
	[LOOP]
	<td>
	  <table>
	    <tr><td align='center'><a href='{PRDLINK}'><img src='{PRDIMG}' width='100' height='100' border='0'></a></td></tr>
	    <tr><td align='center'>{PRDNAME}</td></tr>
	    <tr><td align='center'>{PRDPRICE}원</td></tr>
	  </table>
	</td>
	[/LOOP]
	</table>";	
	$prdname_len = 30;
	$prdexp_len = 30;
	
}

?>