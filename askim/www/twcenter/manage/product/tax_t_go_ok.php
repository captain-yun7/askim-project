<? include_once "../../common.php"; ?>

<?
/*echo"<pre>";
print_r($_REQUEST);
echo"</pre>";*/

$result_ok=urldecode($_POST['result_data']);



if(eregi("SUCCESS",trim($result_ok)) ) { //세금계산서 발행-> 공인인증서  성공여부

	$xml_parser=xml2array($result_ok);
	
	/*echo"<pre>";
	print_r($xml_parser);
	echo"<pre>";*/

	$tax_no=$xml_parser['root']['items']['list_code'];

	$sql="update wiz_tax set tax_pub='Y' where tax_no='$tax_no'";
	query($sql) or die("sql error");

	echo "<script>document.location='./tax_list.php?tax_type=T';</script>";

}else{
	echo"발행에 문제가 발행되었습니다. ".$result_data;
	exit;
}




?>