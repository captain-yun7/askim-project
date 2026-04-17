<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include "../head.php"; ?>

<script type="text/javascript">
<!--
function selectAll(){

	if($("#checkAll").prop("checked")) {
		$("input[name=select_checkbox]:checkbox").prop("checked", true);
	} else {
		$("input[name=select_checkbox]:checkbox").prop("checked", false);
	}
}

function AllBbsDelete(){

	if($('input[name=select_checkbox]:checked').length == 0){
		alert("삭제할 게시물을 선택하세요.");
		return false;

	} else {

		var select_checkbox = [];

		$('input[name=select_checkbox]:checked').each(function(){
			select_checkbox.push(this.value);
		});

		if(confirm("선택체크된 게시물은 게시물 확인없이 곧바로 삭제가됩니다.\n게시물을 삭제하시겠습니까?\n삭제된 데이터는 복구가 불가능합니다.")){

			var chkval = select_checkbox.join('|');
			var mode = "multiBbsdel";
			$.ajax({
				type: "post",
				url: "./save.php",
				data : {mode:mode,chkval:chkval},
				success: function () {
					alert("게시물이 삭제되었습니다.");
					location.reload();
				},
				error: function (data, status, err) {
					alert("서버와의 통신이 실패했습니다.");
					return;
				}
			});

		} else {
			return;
		}
	
	}

}
//--
</script>
<?

$sql = "select * from wiz_bbs";
$result = query($sql) or error("sql error");
$total = sql_fetch_row($result);

$rows  = 20;
$lists = 5;
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;
?>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">게시물통합관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">게시물의 모든데이터를 통합관리합니다.</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td><span class="title_msg">총 게시물 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
	</tr>
	<tr><td height="3"></td></tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="t_rd" colspan=20></td></tr>
	<tr class="t_th">
		<th width="5%"><input type="checkbox" id="checkAll" onclick="selectAll()"></th>
		<th width="8%">번호</th>
		<th width="10%">게시판명</th>
		<th>제목</th>
		<th width="10%">작성자</th>
		<th width="10%">작성일</th>
		<th width="10%">조회수</th>
		<th width="10%">기능</th>
	</tr>
	<tr><td class="t_rd" colspan=20></td></tr>
<?
$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));

$sql = "

select 

	wb.idx                                                        ,
	wb.code                                                       ,
	wb.subject                                                    ,
	wb.name                                                       ,
	wb.count                                                      ,
	date_format(from_unixtime(wb.wdate), '%Y-%m-%d') as wdate     ,
	wi.title                                                      ,
	wi.type
	
from

	wiz_bbs wb,
	wiz_bbsinfo wi
	
where

	wb.code = wi.code order by wb.idx desc limit $start, $rows
	
";
$result = query($sql) or error("sql error");

while($row = sql_fetch_obj($result)){

	$new = "";
	$wtime = mktime(0,0,0,substr($row->wdate,5,2),substr($row->wdate,8,2),substr($row->wdate,0,4));
	if(($ttime-$wtime)/86400 <= 2) $new = "<img src='../image/new.gif' border='0' align='absmiddle'>";	// new
	$row->wdate = str_replace("-","/",$row->wdate);

	if(!strcmp($row->type, "SCH")) {
		$purl = "../schedule/input.php?mode=update&code=$row->code&idx=$row->idx&page=$page&menucode=SCHEGUAL";
		$durl = "../schedule/view.php?code=$row->code&idx=$row->idx&page=$page&menucode=SCHEGUAL";
	} else {
		$purl = "../bbs/list.php?ptype=input&mode=modify&code=$row->code&idx=$row->idx&page=$page&menucode=BBS";
		$durl = "../bbs/list.php?ptype=view&code=$row->code&idx=$row->idx&page=$page&menucode=BBS";
	}

?>
	<tr>
		<td align="center"><input type="checkbox" name="select_checkbox" id="select_checkbox" value="<?=$row->idx?>"></td>
		<td height="38" align="center"><?=$no?></td>
		<td align="center"><strong>[<?=$row->title?>]</strong></td>
		<td style="padding:0 0 0 15px"><?=$row->subject?></td>
		<td align="center"><?=$row->name?></td>
		<td align="center"><?=$row->wdate?></td>
		<td align="center"><?=$row->count?></td>
		<td align="center">
			<img src="../image/btn_edit_s.gif" style="cursor:pointer" onClick="document.location='<?=$purl?>'">
			<img src="../image/btn_delete_s.gif" style="cursor:pointer" onClick="document.location='<?=$durl?>'" title="내용확인후 삭제가 가능합니다.">
		</td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?
$no--;
}

if($total <= 0){
?>
	<tr><td height="30" colspan="10" align="center">등록된 게시물이 없습니다.</td></tr>
	<tr><td colspan="20" class="t_line"></td></tr>
<?
}
?>
</table>

<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td></td>
	</tr>
</table>
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="33%">
			<input type="button" value="선택삭제" class="btnListchk" onclick="AllBbsDelete()">
		</td>
		<td width="33%" align="center"><? print_pagelist($page, $lists, $page_count, $menucodeParam); ?></td>
		<td width="33%"></td>
	</tr>
</table>

<? include "../foot.php"; ?>