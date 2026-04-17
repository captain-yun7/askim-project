<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>

<?

if($mode == "insert"){

	$sql = "insert into wiz_option(idx,opttitle,optcode) values('', '$opttitle', '$optcode')";
	$result = query($sql) or error("sql error");
	echo "<script>alert('등록되었습니다.');self.close();opener.location='prd_option.php?$menucodeParam';</script>";
	
}else if($mode == "update"){
	
	$sql = "update wiz_option set opttitle='$opttitle', optcode='$optcode' where idx = '$idx'";

	$result = query($sql) or error("sql error");
	
	echo "<script>alert('수정되었습니다..');self.close();opener.location='prd_option.php?$menucodeParam';</script>";
	
}else if($mode == "delete"){
	
	$sql = "delete from wiz_option where idx = '$idx'";

	$result = query($sql) or error("sql error");
	
	echo "<script>alert('삭제되었습니다..');document.location='prd_option.php?$menucodeParam';</script>";
	
}

?>