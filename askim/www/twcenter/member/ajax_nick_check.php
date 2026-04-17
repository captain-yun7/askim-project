<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT'].'/twcenter/inc/mem_info.php';

if($_POST['ckuse'] == "CHK"){

	if(!empty($_POST['nick'])){
		$nick = $nick;
	}
	$nick_count = trim(strlen($nick));

	if(!empty($nick)){
		$sql = "select nick from wiz_member where nick='$nick'";
		$result = query($sql) or error("sql error");
		$total = sql_fetch_row($result);
	}
	$nick_val = strip_tags(strtolower($nick));
	$filterid = explode(",", trim($mem_info['prohibit_id']));

	for($i=0; $i<count($filterid); $i++) {
		$f_string = $filterid[$i];
		$pos = strpos($nick_val, $f_string);

		if($pos !== false) {
			$res = 'true';
		}
	}

	if($res == "true"){
		$checkNick = "F";
	} else {
		$checkNick = "O";
	}

}

echo $nick."|".$total."|".$nick_count."|".$checkNick;

?>
