<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";

$param = "code=$code&$menucodeParam";

include "../head.php";
 ?>
<script language="JavaScript" type="text/javascript">
<!--
function deleteBanner( idx ){
	if(confirm('선택한 컨텐츠를 삭제하시겠습니까?\n\n삭제한 데이타는 복구할수 없습니다.')){
		document.location = 'banner_save.php?mode=delete&idx=' + idx + '&code=<?php echo $code ?>&page=<?php echo $page ?>&<?php echo $menucodeParam ?>';
	}
}

$(function() {

	var xOffset = 10;
	var yOffset = 30;

	$(document).on("mouseover",".thumbnail",function(e){ //--마우스 오버시
		 
		$("body").append("<p id='preview'><img src='"+ $(this).attr("src") +"' width='980'></p>"); //--보여줄 이미지를 선언
		$("#preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast"); //미리보기 화면 설정 셋팅
	});
	 
	$(document).on("mousemove",".thumbnail",function(e){ //--마우스 이동시
		$("#preview")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});
	 
	$(document).on("mouseout",".thumbnail",function(){ //--마우스 아웃시
		$("#preview").remove();
	});
	
});
//-->
</script>
<style>
/** 미리보기 스타일 **/
#preview{
	z-index: 9999;
	position:absolute;
	border:0px solid #ccc;
	background:#ccc;
	padding:1px;
	display:none;
	color:#fff;
}
</style>
<?php
$sql = "
	select * 
	  from wiz_banner 
	 where code = '$code' 
	 order by prior, idx asc
";
$result = query($sql);
$total = sql_fetch_row($result);

$rows = 20;
$lists = 5;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;
?>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">컨텐츠관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">컨텐츠를 추가/수정/삭제 관리합니다.</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td><span class="title_msg">총 컨텐츠수 : <strong id="total_prd_cnt"><?php echo $total ?></strong></span></td>
		<td align="right">

			<input type="button" value="컨텐츠추가" class="btnListchk3" onClick="document.location='input.php?mode=insert&code=<?php echo $code ?>&<?php echo $menucodeParam ?>';">

		</td>
	</tr>
	<tr><td height="3"></td></tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="t_rd" colspan=20></td></tr>
	<tr class="t_th">
		<th width="5%">번호</th>
		<th width="10%">코드</th>
		<th width="15%">이미지</th>
		<th width="44%">텍스트</th>
		<th width="8%">우선순위</th>
		<th width="8%">사용여부</th>
		<th width="10%">기능</th>
	</tr>
	<tr><td class="t_rd" colspan=20></td></tr>
<?php
$sql = "
	select * 
	  from wiz_banner 
	 where code = '$code' 
	 order by prior, idx asc 
	 limit $start, $rows
";
$result = query($sql);
while($row = sql_fetch_obj($result)){

	if($row->isuse == "N") $row->isuse = "사용안함";
	else $row->isuse = "사용함";

 ?>
	<tr>
		<td height="30" align="center"><?php echo $no ?></td>
		<td align="center"><?php echo $row->code ?></td>
		<td align="center" style="padding:8px 0;">
<?php
	 if(!empty($row->de_img)) { 
	 $img_path = "../../data/banner/".$row->de_img;
	if(file_exists($img_path)) {
		$img_size = getimagesize($img_path);
		list($width,$height) = $img_size;
		if($width > 200 || $height > 200){
		$rewidth  = round(($width*200)/$height);
		$reheight = round(($height*200)/$width);

			//$size = " width='".$rewidth."' height='".$reheight."' ";
			$size = " width='85' height='85' ";
		} else {
			$size = "";
		}
	}
 }
// 25.05.28 유준호 
//
//	if($row->de_img != "") $img_path = "../../data/banner/".$row->de_img;
//	if(@file($img_path)) {
//		$img_size = getimagesize($img_path);
//		list($width,$height) = $img_size;
//		if($width > 200 || $height > 200){
//		$rewidth  = round(($width*200)/$height);
//		$reheight = round(($height*200)/$width);
//
//			//$size = " width='".$rewidth."' height='".$reheight."' ";
//			$size = " width='85' height='85' ";
//		} else {
//			$size = "";
//		}
// }

	if($row->de_type == "IMG" || $row->de_type == "SKIN") {
		if($row->de_img) echo "<p><img src=/twcenter/data/banner/$row->de_img width='120' class='thumbnail'></p>";
		for($i=2; $i<=10; $i++) {
			if($row->{'de_img'.$i}) echo "<p><img src='/twcenter/data/banner/".$row->{'de_img'.$i}."' width='120' class='thumbnail'></p>";
		}		
	}
	else echo "<table><tr><td>".stripslashes($row->de_html)."</td></tr></table>";
 ?>
		</td>
		<td align="center"><?php echo $row->txt1 ?></td>
		<td align="center"><?php echo $row->prior ?></td>
		<td align="center"><?php echo $row->isuse ?></td>
		<td align="center">
			<img src="../image/btn_edit_s.gif" style="cursor:hand" onClick="document.location='input.php?mode=update&idx=<?php echo $row->idx ?>&code=<?php echo $code ?>&page=<?php echo $page ?>&<?php echo $menucodeParam ?>'">
			<img src="../image/btn_delete_s.gif" style="cursor:hand" onClick="deleteBanner('<?php echo $row->idx ?>');">
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?php
	$no--;
	}

if($total <= 0){
 ?>
	<tr><td height="30" colspan="10" align="center">등록된 컨텐츠가 없습니다.</td></tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?php
}
?>
</table>
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="5"></td></tr>
	<tr>
		<td align="center"><?php echo print_pagelist($page, $lists, $page_count, $param); ?></td>
	</tr>
</table>

<?php include "../foot.php";  ?>