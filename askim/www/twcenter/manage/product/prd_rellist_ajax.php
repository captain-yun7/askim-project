<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

	$array_selected = explode("|",$selected);
	$i=0;
	while($array_selected[$i]){

		$tmp_prdcode = $array_selected[$i];

		if($prdcode == $tmp_prdcode) {
			$i++;
			continue;			//현재상품과 동일한 상품을 관련상품으로 선택할 경우 skip
		}

		$sql0 = "select count(*) as cnt from wiz_prdrelation where prdcode='$prdcode' and relcode='$tmp_prdcode'";
		$row0 = sql_fetch($sql0);

		if($row0['cnt'] < 1) {			// 이미 등록되어있으면 등록하지 않도록
			$sql = "insert into wiz_prdrelation(idx,prdcode,relcode) values('','$prdcode','$tmp_prdcode')";
			query($sql);
		}

		$i++;
	}

	print "ok";

?>