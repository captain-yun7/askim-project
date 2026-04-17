<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

	$array_selected = explode("|",$selected);
	$i=0;
	while($array_selected[$i]){

		$tmp_prdcode = $array_selected[$i];

		$sql = "insert into wiz_prdrelation2(idx,prdcode,relcode) values('','$prdcode','$tmp_prdcode')";
		query($sql);

		$i++;
	}

	print "ok";

?>