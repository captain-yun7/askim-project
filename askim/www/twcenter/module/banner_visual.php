<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

$sql = "select code, types, types_num, padding, isuse from wiz_bannerinfo where code = '$banner_code'";
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);
$ban_info = sql_fetch_obj($result);

if($total <= 0){
	$msg = "<font color=red><b>".$banner_code."</b></font> 배너는 아직 생성되지 않았습니다.";
	echo "<table align=center><tr><td height=25>&nbsp;&nbsp;".$msg."&nbsp;&nbsp;</td></tr></table>";
}
if($ban_info->isuse != 'N'){
?>
<?php
	if($ban_info->limit_chk == "Y" && $ban_info->limit_rows)	$limit_sql = " limit ".$ban_info->limit_rows;
	else	$limit_sql = "";
	$sql = "select * from wiz_banner where code = '$ban_info->code' and isuse != 'N' order by prior asc, idx asc".$limit_sql;
	$result2 = query($sql);
	$total = sql_fetch_row($result2);
	$no = 0;
	$num = 1;
	while ($row = sql_fetch_obj($result2)) {

		//$row->link_url = str_replace("{HTTP_HOST}",$_http_host,$row->link_url);
		$onClick = "";
		if(!empty($row->link_url)) {
			if(!strcmp($row->link_target, "_SELF") || empty($row->link_target)) $onClick = " onClick=\"self.location.href='".$row->link_url."'\" style='cursor:pointer' ";
			if(!strcmp($row->link_target, "_BLANK")) $onClick = " onClick=\"window.open('".$row->link_url."')\" style='cursor:pointer' ";
		}

    if($row->de_type == "IMG")
      $ban_content = "<div style='display: none;'><img src=/twcenter/data/banner/".$row->de_img." border=0 ".$onClick." alt='배너 이미지' ></div>";
    else
      $ban_content = "<table cellpadding=0 cellspacing=0 border=0><tr><td ".$onClick.">".$row->de_html."</td></tr></table>";

    if($ban_info->types == "H") {

?>
			<?=$ban_content?>
<?php

			if($no < ($total - 1) && $ban_info->padding > 0) echo "<tr><td height=".$ban_info->padding."></td></tr>";

		} else {

	if($ban_info->types_num) {
		$mod = ($num%$ban_info->types_num);
	} else {
		$mod = 0;
	}

?>
          <?=$ban_content?>
<?php
      if($mod == 0) {
?>
				</tr>
<?php
				if($total > ($ban_info->types_num * $tr) && $total != $ban_info->types_num && $ban_info->padding > 0) {
?>
      	<tr><td colspan="<?=$ban_info->types_num * 2?>" height="<?=$ban_info->padding?>"></td></tr>
<?php
				}
?>
		
<?php
      	$tr++;
			}
      if($mod > 0 && $mod < $total && $ban_info->padding > 0) echo "<td width=".$ban_info->padding."></td>";

		}

		$no++;
		$num++;
	}
?>
<?
}
?>
