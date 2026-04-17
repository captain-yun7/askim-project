<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>

<?

// 등급추가
if($mode == "insert"){
	if(!empty($permi)) {
		for($ii=0; $ii<count($permi); $ii++){
			$tmp_permi .= $permi[$ii]."/";
		}
	}
   
	$sql = "insert into wiz_level(idx,level,icon,name,distype,discount,permi,memo) values('','$level','$icon','$name','$distype','$discount','$tmp_permi','$memo')";
	$result = query($sql) or error("sql error");

	complete("등록되었습니다.","level_list.php?$menucodeParam");


// 등급수정
}else if($mode == "update"){
	if(!empty($permi)) {
		for($ii=0; $ii<count($permi); $ii++){
		  $tmp_permi .= $permi[$ii]."/";
	   }
	}
   
	$sql = "update wiz_level set level='$level', icon='$icon', name='$name',distype='$distype', discount='$discount', permi='$tmp_permi', memo='$memo' where idx = '$idx'";
	$result = query($sql) or error("sql error");
	
	complete("수정되었습니다","level_input.php?mode=update&idx=$idx&$menucodeParam");
	
	
// 등급삭제
}else if($mode == "delete"){
	
	$sql = "select idx from wiz_level where level > $level order by idx asc limit 1";
	$result = query($sql) or error("sql error");
	$row = sql_fetch_obj($result);
	$chg_level = $row->idx;
	
	$sql = "update wiz_member set level = '$chg_level' where level = '$idx'";
	$result = query($sql) or error("sql error");
	
	$sql = "update wiz_bbsinfo set lpermi = '$chg_level' where lpermi = '$idx'";
	$result = query($sql) or error("sql error");
	
	$sql = "update wiz_bbsinfo set rpermi = '$chg_level' where rpermi = '$idx'";
	$result = query($sql) or error("sql error");
	
	$sql = "update wiz_bbsinfo set wpermi = '$chg_level' where wpermi = '$idx'";
	$result = query($sql) or error("sql error");
	
	$sql = "update wiz_bbsinfo set apermi = '$chg_level' where apermi = '$idx'";
	$result = query($sql) or error("sql error");
	
	$sql = "update wiz_bbsinfo set cpermi = '$chg_level' where cpermi = '$idx'";
	$result = query($sql) or error("sql error");
	
	$sql = "delete from wiz_level where idx = '$idx'";
	$result = query($sql) or error("sql error");
	
	complete("삭제되었습니다.","level_list.php?$menucodeParam");
	
}

?>