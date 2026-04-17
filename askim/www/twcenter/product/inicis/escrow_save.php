<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if(!strcmp($ctype, "CFRM")) {

	$sql = "
		update wiz_order 
		   set escrow_stats = 'US' 
		 where tno = '$tno'
	";
	$result = query($sql);

} else if(!strcmp($ctype, "CNCL")) {

	$sql = "
		update wiz_order 
		   set status = 'RD'
		     , cancelmsg='에스크로 구매취소 요청'
			 , escrow_stat = 'UX' 
		 where tno = '$tno'
	";
	$result = query($sql);

}
?>

<script Language="Javascript">
<!--

	alert("구매확인/거절이 성공적으로 완료되었습니다.");
	self.close();

//-->
</script>