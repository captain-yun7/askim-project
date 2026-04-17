<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
$de_img_dir = WAY_DATA_DIR2."/banner/";
$de_img_path = WAY_DATA_PATH."/banner/";

/*
작업자명	: 이상민
작업일시	: 2024-04-05
작업내용	: 모듈의 코드를 변경
*/
if(empty($banner_code)) {
	echo "배너코드를 확인할 수 없습니다.";
} else {
	$sql_binfo = "select * from wiz_bannerinfo where code='$banner_code'";
	$ban_info = sql_fetch_obj(query($sql_binfo));
	if(empty($ban_info->code)) {
		echo "배너그룹이 생성되지 않았습니다.";
	} else if ($ban_info->isuse != "Y") {		//미사용배너 - 미출력
	} else if ($ban_info->use_skin != "Y") {
		echo "스킨 기능을 사용할 수 없는 배너그룹입니다. 설정을 확인해 주십시오.";
	} else if (trim($ban_info->skin) == "") {
		echo "배너스킨이 설정되지 않았습니다. 설정을 확인해 주십시오.";
	} else {
		$banner_skin = $ban_info->skin;
		if($ban_info->limit_chk == "Y" && $ban_info->limit_rows)	$limit_sql = " limit ".$ban_info->limit_rows;
		else	$limit_sql = "";
		if($ban_info->noimg) $noimg = "/".$ban_info->noimg;
		else $noimg = "/img/banner_noimg.gif";
		
		$banner_skin = str_replace("[/LOOP]","[LOOP]",$banner_skin);
		list($header,$body,$footer) = explode("[LOOP]",$banner_skin);
		echo $header;
		$sql = "select * from wiz_banner where code='$banner_code' and isuse!='N' order by prior, idx asc $limit_sql";
		$res = query($sql);
		while($row = sql_fetch_arr($res)) {
			if($row['link_url']) { 
				if($row['link_target']) {
					$link = "<a href=\"".$row['link_url']."\" target=\"".$row['link_target']."\">";
				} else {
					$link = "<a href=\"".$row['link_url']."\">";
				}
			} else {
				$link = "";
			}
			if($row['de_img'] && @is_file($de_img_path.$row['de_img'])) {
				$de_img1 = $de_img_dir.$row['de_img'];
			} else {
				$de_img1 = $noimg;
			}
			$body_tmp = stripslashes($body);
			$body_tmp = str_replace("{LINK}", $link, $body_tmp);
			$body_tmp = str_replace("{/LINK}", "</a>", $body_tmp);
			$body_tmp = str_replace("{IMG1}", $de_img1, $body_tmp);
			$body_tmp = str_replace("{TXT1}", $row['txt1'], $body_tmp);
			for($ii = 2; $ii<=10; $ii++) { 
				if($row['de_img'.$ii] && @is_file($de_img_path.$row['de_img'.$ii])) {
					$de_img = $de_img_dir.$row['de_img'.$ii];
				} else {
					$de_img = $noimg;
				}
				$body_tmp = str_replace("{IMG".$ii."}", $de_img, $body_tmp);
				$body_tmp = str_replace("{TXT".$ii."}", $row['txt'.$ii], $body_tmp);
			}
			echo $body_tmp;
		}

		echo $footer;
	}
}
?>