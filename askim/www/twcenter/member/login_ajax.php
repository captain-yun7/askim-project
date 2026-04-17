<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

if($_POST['auto_login']=="Y"){

	$id				= $_POST['id'];
	$passwd			= $_POST['passwd'];
	$auto_login		= $_POST['auto_login'];

	$sql = "select *from wiz_member where id='$id' and passwd='$passwd'";
	$result = query($sql) or error("sql error");
	$row_num = sql_fetch_row($result);


	if($row_num > 0){

		$row = sql_fetch_arr($result);

		$level_info  = level_info();
		$level       = $row['level'];
		$level_value = $level_info[$level][level];

		$_SESSION['wiz_session']['id']               = $id;
		$_SESSION['wiz_session']['passwd']           = $passwd;
		$_SESSION['wiz_session']['name']             = $row['name'];
		$_SESSION['wiz_session']['email']            = $row['email'];
		$_SESSION['wiz_session']['hphone']           = $row['hphone'];
		$_SESSION['wiz_session']['tphone']           = $row['tphone'];
		$_SESSION['wiz_session']['level']            = $row['level'];
		$_SESSION['wiz_session']['level']            = $row['level'];
		$_SESSION['wiz_session']['level_value']      = $level_value;

	}else{
	
	}

}else{

}

?>