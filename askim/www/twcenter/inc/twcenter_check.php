<?
//if($wiz_admin['id'] == ""){
if($_SESSION['wiz_admin']['id'] == ""){
?>
<script>document.location = "/twcenter";</script>
<?
	exit;
}
?>