<?php
include_once "../../common.php";

$bkcode = $_POST['bkcode'];

include "../../../comm/API/xml/xml.php";
$get_url = "http://www.bankda.com/bankda_requestinfo.xml";

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $get_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$xml = curl_exec($curl);

$parser = new XMLParser($xml);
$parser->Parse();

foreach($parser->document->bank as $k=>$bank) {

	$bank_code = $bank->tagAttrs['code']."|";
	if(strpos($bank_code, $bkcode) !== false) {

		$bank_name    = "<strong>".$bank->tagAttrs['name']."</strong>";
		$bank_comment = $bank->tagAttrs['comment'];

		foreach($bank->requestinfo as $k=>$banks) {
			$type = $banks->tagAttrs['type'];
			if($type == 'C') {
				$type_c = $banks->tagData;
			} else if ($type == 'P') {
				$type_p = $banks->tagData;
			}
		}

	}

}

echo json_encode(array("bank_name"=>$bank_name, "COPR"=>$type_c, "INDI"=>$type_p, "comment"=>$bank_comment));
exit;


?>