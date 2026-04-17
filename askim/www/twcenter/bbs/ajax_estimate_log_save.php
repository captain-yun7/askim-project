<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbs_info.php";


if($mode == "insert"){
	$bidx = $_POST['bidx'];
	$name = $_POST['name'];
	$subject = $_POST['subject'];
	$content = $_POST['inquiry_memo'];
	if($_POST['code']) $code = $_POST['code']; else $code = "estimate";


	$subject = addslashes($subject);
	//if(!get_magic_quotes_gpc()) $subject     = addslashes($subject); php8 부터 완전히 삭제된 함수
	//if(!get_magic_quotes_gpc()) $content     = addslashes($content);


	$sql = "insert into wiz_estimate_log set
					bidx = '".$bidx."',
					name = '".$name."', 
					subject = '".$subject."',
					content = '".$content."',
					wdate = now()";

	@query($sql);

	echo "<script>document.location='/twcenter/manage/bbs/list.php?ptype=view&idx=".$bidx."&code=".$code."';</script>";

}else if($mode == "update"){
	$idx = $_POST['idx'];
	$name = $_POST['name'];
	$subject = $_POST['subject'];
	$content = $_POST['inquiry_memo'];

	$sql = "update wiz_estimate_log set name='".$name."', subject='".$subject."', content='".$content."' 
	where idx = '".$idx."' ";

	@query($sql);

?>
<script> 
alert('수정되었습니다.'); 
//window.opener.location.href = "/twcenter/manage/listup/index2.php?ptype=view2&mode=view&idx=<?=$mms_idx?>#memo";
window.close(); 
window.opener.location.reload(true);
</script> 

<?php
}else if($mode == "delete"){

	if($_GET['code']) $code = $_GET['code']; else $code = "estimate";

	$sql = "delete from wiz_estimate_log where idx = '$idx'";
	@query($sql);

	echo "<script>alert('삭제되었습니다.');document.location='/twcenter/manage/bbs/list.php?ptype=view&idx=".$bidx."&code=".$code."';</script>";

}
?>