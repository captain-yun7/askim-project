<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?
if(!get_magic_quotes_gpc()) $mainskin = addslashes($mainskin);

$sql = "select code from wiz_pollinfo where code = '$code'";
$result = query($sql) or error("sql_error");
$total = sql_fetch_row($result);

if($total <= 0) $mode = "insert";
else $mode = "update";

if($mode == "insert"){

	$sql = "insert into wiz_pollinfo(code,purl,mainskin,wdate) 
							 values('$code','$purl','$mainskin',now())";
				
	$result = query($sql);
	
}else if($mode == "update"){
	
	$sql = "update wiz_pollinfo set purl='$purl',mainskin='$mainskin' where code = '$code'";

	$result = query($sql) or error("sql_error");

}

complete("수정 되었습니다.","pollmain_input.php?code=$code");

?>