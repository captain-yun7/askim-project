<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$msg = strip_tags($msg);

echo "<script language=\"javascript\">";
echo "var confirm=confirm(\"다른 컴퓨터에 로그인 되어 있습니다.\\n강제종료후 로그인 하시겠습니까?\");";
echo "if(confirm){ aa(); } else { bb(); }";
echo "</script>";



?>
<script>
function aa() {
<?php
	query("delete from wiz_session where user_id = '".$u_id."' ");

	$_SESSION['wiz_admin']['id']          = $u_id;


?>
	document.location = "../main/main.php";
}

function bb() {
	return false;
}

</script>