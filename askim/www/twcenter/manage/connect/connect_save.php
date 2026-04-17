<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>

<?
if($mode == "dellist"){
	
	$sql = "delete from wiz_contime";
	query($sql);
	
	complete("초기화 되었습니다.","connect_list.php");
	
}else if($mode == "delrefer"){
	
	$sql = "delete from wiz_conrefer";
	query($sql);
	
	complete("초기화 되었습니다.","connect_refer.php");
	
}else if($mode == "delos"){
	
	$sql = "delete from wiz_conother";
	query($sql);
	
	complete("초기화 되었습니다.","connect_osbrowser.php");
	
}else if($mode == "deltotal"){
	
	$sql = "delete from wiz_con_total";
	query($sql);
	
	complete("초기화 되었습니다.","connect_ip.php");
	
}
?>