<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<?
$sql = "select * from wiz_mailsms where code = '$code'";
$result = query($sql) or error("sql error");
$row = sql_fetch_obj($result);

$email_msg = info_replace($site_info, $re_info, $row->email_msg);
                
?>
<form name="frm">
<textarea name="email_msg"><?=$email_msg?></textarea>
</form>
