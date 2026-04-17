<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$img       = $_POST["img"];
$code      = $_POST["code"];
$tmp_token = $_POST["tmp_token"];

$file_url_ar = explode("/",$img);
$file_source = $file_url_ar[sizeof($file_url_ar) - 1];

if($_POST["idx"] != "" && $_POST["mode"] == "modify"){
	$idx = $_POST["idx"];
} else {
	$idx = "";
}

$create_date = date("ym");


?>
