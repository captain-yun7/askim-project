<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/design_info.php";

echo "<script Language=\"JavaScript\" src=\"/twcenter/js/lib.js\"></script>";

echo "<table width=100% border=0 cellpadding=0 cellspacing=0 align=center>\n";

$no = 1;
$sql = "select catcode, catname, catimg, catimg_over, purl, depthno from wiz_category where depthno = 1 and catuse != 'N' order by depthno asc, priorno01 asc";
$result = query($sql) or error("sql_error");
while($row = sql_fetch_obj($result)){

	if($row->depthno < 1) {

		if($row->catimg == ""){
			echo "<tr><td><a href='/".$row->purl."?catcode=".$row->catcode."'>".$row->catname."</a></td></tr>\n";
		}else{
			if($row->catimg_over == "") $row->catimg_over = $row->catimg;
			echo "<tr><td onMouseOver=WIZ_swapImage('c_0','','/twcenter/data/catimg/".$row->catimg_over."',1) onMouseOut=WIZ_swapImgRestore();><a href='/".$row->purl."?catcode=".$row->catcode."'><img src='/twcenter/data/catimg/".$row->catimg."' border=0 id='c_0'></a></td></tr>\n";
		}

	} else {

		if($row->catimg == ""){
			echo "<tr><td onmouseover=displayLay('".($no-1)."')  onmouseout=disableLay('".($no-1)."')><a href='/".$row->purl."?catcode=".$row->catcode."'>".$row->catname."</a></td></tr>\n";
		}else{
			if($row->catimg_over == "") $row->catimg_over = $row->catimg;
			echo "<tr><td onMouseOver=displayLay('".($no-1)."');WIZ_swapImage('c_".$no."','','/twcenter/data/catimg/".$row->catimg_over."',1) onMouseOut=WIZ_swapImgRestore();disableLay('".($no-1)."')><a href='/".$row->purl."?catcode=".$row->catcode."'><img src='/twcenter/data/catimg/".$row->catimg."' name='c_".$no."' border=0 id='c_".$no."'></a></td></tr>\n";
		}

		$no++;
	}

}

echo "</table>\n";

////////////////////////////////////////////////////////////////////////////////////////////////////
// 하위분류 출력
////////////////////////////////////////////////////////////////////////////////////////////////////

if($design_info['cate_sub'] == "Y"){

	$no = 0;
	$layer_y = $design_info['cate_suby'];					// 레이어 시작Y
	$layer_x = $design_info['cate_subx'];					// 레이어 시작X
	$menu_height = $design_info['cate_menuh'];		// 각 메뉴높이

	if($design_info['site_align'] == "CENTER"){
		$site_width = ceil($design_info['site_width']/2)-$layer_x;
		$layer_x = "expression(document.body.clientWidth/2-".$site_width.")";
	}

	$sql = "select depthno, catcode, catname, catimg, purl from wiz_category where depthno != 3 and catuse != 'N' order by priorno01 asc,priorno02 asc;";
	$result = query($sql) or error("sql_error");
	$total = sql_fetch_row($result);

	while($row = sql_fetch_obj($result)){

		if($row->depthno == 1){

		//if($i!=0 and $i%2==1)  echo "<td bgcolor='#ffffff'> </td>";
		if($no != 0) echo "</table></div>\n";

			echo "<div id=\"displayer\" style=\"display:none;position:absolute;left:".$layer_x."; top:".($no*$menu_height + $layer_y)."px; z-index:7; background:url('/twcenter/images/sub_cate_bg.gif') top right no-repeat;\">
						<table width=\"150\" cellpadding=\"2\" cellspacing=\"0\"  style=\"background:url('/twcenter/images/sub_cate_line.gif') bottom right no-repeat;\" onmouseover=\"displayLay('".$no."')\"; onmouseout=\"disableLay('".$no."')\";>\n";
			$no++;
			$i=0;

		}else if($row->depthno == 2){
			echo "<tr><td style=\"background:url('/twcenter/images/news_dott.gif') 20px 9px no-repeat; padding-left:30px;\" align=\"left\"><a href=\"/".$row->purl."?catcode=".$row->catcode."\" onfocus=\"blur()\">".$row->catname."</a></td></tr>\n";
			$i++;
		}
	}
	if($total > 0) echo "</tr></table></div>\n";

}
?>