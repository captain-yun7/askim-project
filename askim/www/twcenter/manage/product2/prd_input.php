<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/prd_info.php"; ?>
<?
// 업로드 위치
$imgpath = "../../data/product2";

// 검색 파라미터
$param = "page=$page&dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code&searchopt=$searchopt&searchkey=$searchkey&$menucodeParam";

// 상품정보
if($mode == "") $mode = "insert";
if($mode == "insert"){

	$catcode01 = $dep_code;
	$catcode02 = $dep_code.$dep2_code;
	$catcode03 = $dep_code.$dep2_code.$dep3_code;
	$catcode04 = $dep_code.$dep2_code.$dep3_code.$dep4_code;

}else if($mode == "update"){

	$sql = "select wp.*, wc.idx, wc.catcode from wiz_product2 wp, wiz_cprelation2 wc where wp.prdcode = '$prdcode' and wp.prdcode = wc.prdcode";
	$result = query($sql) or error("sql error");
	$prd_info = sql_fetch_arr($result);
	
	if(!isset($page_info['shortexp'])) $page_info['shortexp'] = '';
	$page_info['shortexp'] = stripslashes($page_info['shortexp']);
	
	if(!isset($page_info['content'])) $page_info['content'] = '';
	$page_info['content'] = stripslashes($page_info['content']);

	$relidx = $prd_info['idx'];
	$catcode01 = substr($prd_info['catcode'],0,2);
	$catcode02 = substr($prd_info['catcode'],0,4);
	$catcode03 = substr($prd_info['catcode'],0,6);
	$catcode04 = substr($prd_info['catcode'],0,8);
}
?>
<? include "../head.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
  var loding = false;
  var prd_class = new Array();
<?
	$no = 0;
	$sql = "select catcode, catname, depthno from wiz_category2 order by priorno01, priorno02, priorno03, priorno04 asc";
	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);
	while($row = sql_fetch_obj($result)){

		$code01 = substr($row->catcode,0,2);
		$code02 = substr($row->catcode,0,4);
		$code03 = substr($row->catcode,0,6);
		$code04 = substr($row->catcode,0,8);

		if($row->depthno == 1){ $catcode = $code01; $parent = 0; }
		if($row->depthno == 2){ $catcode = $code02; $parent = $code01; }
		if($row->depthno == 3){ $catcode = $code03; $parent = $code02; }
		if($row->depthno == 4){ $catcode = $code04; $parent = $code03; }
?>

	prd_class[<?=$no?>] = new Array();
	prd_class[<?=$no?>][0] = "<?=$catcode?>";
	prd_class[<?=$no?>][1] = "<?=$row->catname?>";
	prd_class[<?=$no?>][2] = "<?=$parent?>";
	prd_class[<?=$no?>][3] = "<?=$row->depthno?>";

<?
	$no++;
	}
?>
var tno = <?=$total?>;

function setClass01(){

	var arrayClass = eval("document.frm.class01");
	var arrayClass1 = eval("document.frm.class02");
	var arrayClass2 = eval("document.frm.class03");
	var arrayClass3 = eval("document.frm.class04");

	arrayClass.options[0]  = new Option(":: 1차분류 ::","");
	arrayClass1.options[0] = new Option(":: 2차분류 ::","");
	arrayClass2.options[0] = new Option(":: 3차분류 ::","");
	arrayClass3.options[0] = new Option(":: 4차분류 ::","");

	for(no=0,sno=1 ; no < tno ; no++){
		if(prd_class[no][3]=='1'){
			arrayClass.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
			sno++;
		}
	}
}

function changeClass01(){

	var arrayClass = eval("document.frm.class01");
	var arrayClass1 = eval("document.frm.class02");
	var arrayClass2 = eval("document.frm.class03");
	var arrayClass3 = eval("document.frm.class04");

	var selidx = arrayClass.selectedIndex;
	var selvalue = arrayClass.options[selidx].value;

	arrayClass1.options.length=0;
	arrayClass2.options.length=0;
	arrayClass3.options.length=0;
	arrayClass1.options[0] = new Option(":: 2차분류 ::","");
	arrayClass2.options[0] = new Option(":: 3차분류 ::","");
	arrayClass3.options[0] = new Option(":: 4차분류 ::","");

	for(no=0,sno=1 ; no < tno ; no++){
		if(prd_class[no][3]=='2' && prd_class[no][2]==selvalue){
			arrayClass1.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
			sno++;
		}
	}

}

function changeClass02(){

	var arrayClass1 = eval("document.frm.class02");
	var arrayClass2 = eval("document.frm.class03");
	var arrayClass3 = eval("document.frm.class04");

	var selidx = arrayClass1.selectedIndex;
	var selvalue = arrayClass1.options[selidx].value;

	arrayClass2.options.length=0;
	arrayClass3.options.length=0;
	arrayClass2.options[0] = new Option(":: 3차분류 ::","");
	arrayClass3.options[0] = new Option(":: 4차분류 ::","");

	for(no=0,sno=1 ; no < tno ; no++){
		if(prd_class[no][3]=='3' && prd_class[no][2]==selvalue){
			arrayClass2.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
			sno++;
		}
	}
}

function changeClass03(){

	var arrayClass2 = eval("document.frm.class03");
	var arrayClass3 = eval("document.frm.class04");

	var selidx = arrayClass2.selectedIndex;
	var selvalue = arrayClass2.options[selidx].value;

	arrayClass3.options.length=0;
	arrayClass3.options[0] = new Option(":: 4차분류 ::","");

	for(no=0,sno=1 ; no < tno ; no++){
		if(prd_class[no][3]=='4' && prd_class[no][2]==selvalue){
			arrayClass3.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
			sno++;
		}
	}

}

function changeClass04(){
}
// 상품카테고리 설정
function setCategory(){

	var arrayClass01 = eval("document.frm.class01");
	var arrayClass02 = eval("document.frm.class02");
	var arrayClass03 = eval("document.frm.class03");
	var arrayClass04 = eval("document.frm.class04");

	for(no=1; no < arrayClass01.length; no++){
		if(arrayClass01.options[no].value == '<?=$catcode01?>'){
			arrayClass01.options[no].selected = true;
			changeClass01();
		}
	}

	for(no=1; no < arrayClass02.length; no++){
		if(arrayClass02.options[no].value == '<?=$catcode02?>'){
			arrayClass02.options[no].selected = true;
			changeClass02();
		}
	}

	for(no=1; no < arrayClass03.length; no++){
		if(arrayClass03.options[no].value == '<?=$catcode03?>'){
			arrayClass03.options[no].selected = true;
			changeClass03();
		}
	}

	for(no=1; no < arrayClass04.length; no++){
		if(arrayClass04.options[no].value == '<?=$catcode04?>')
		arrayClass04.options[no].selected = true;
	}

}
function inputCheck(frm){

	if(loding == false){
		alert("상품정보를 가져오고 있습니다. 잠시후 재시도 하세요");
		return false;
	}
	if(frm.prdname.value == ""){
		alert("상품명을 입력하세요");
		frm.prdname.focus();
		return false;
	}
	content.outputBodyHTML();
	mcontent.outputBodyHTML();
}

//해당 이미지를 삭제한다.
function deleteImage(prdcode, prdimg, imgpath){
	if(imgpath == ""){
		alert("삭제할 이미지가 없습니다.");
		return;
	}else{
	if(confirm("이미지를 삭제하시겠습니까?"))
		document.location = "prd_save.php?mode=delete_image&prdcode="+prdcode+"&<?=$menucodeParam?>&prdimg="+prdimg+"&imgpath="+imgpath;
	}
	return;
}

function prdlayCheck(){
	<?
	for($ii = 2; $ii <= $prdimg_max; $ii++) {
		if(
			@file($imgpath."/".$prd_info['prdimg_S'.$ii]) ||
			@file($imgpath."/".$prd_info['prdimg_M'.$ii]) ||
			@file($imgpath."/".$prd_info['prdimg_L'.$ii])
		){
			echo "document.frm.prdlay_check".$ii.".checked = true; prdlay".$ii.".style.display='';";
		}
	}
	?>
}

function lodingComplete(){
	loding = true;
}

function prdCategory(){
  var url = "prd_catlist.php?prdcode=<?=$prdcode?>";
  window.open(url, "prdCategory", "height=330, width=560, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=150, top=100");
}

function prdIcon(){
	var url = "prd_icon.php";
	window.open(url, "prdIcon", "height=250, width=450, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=150, top=100");
}

function setImgsize(){
	var url = "prd_imgsize.php";
   window.open(url, "setImgsize", "height=230, width=300, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=150, top=100");
}

//상품이동
function movePrd(){

	selvalue = "<?=$prdcode?>";

	if(selvalue == ""){
		alert("이동할 상품을 선택하세요.");
		return false;
	}else{
		var uri = "prd_move.php?selvalue=" + selvalue + "&<?=$menucodeParam?>";
		window.open(uri,"movePrd","width=300,height=150");
	}
}

// 상품복사
function copyPrd(){
	selvalue = "<?=$prdcode?>";

	if(selvalue == ""){
		alert("복사할 상품을 선택하세요.");
		return false;
	}else{
		var uri = "prd_copy.php?selvalue=" + selvalue + "&<?=$menucodeParam?>";
		window.open(uri,"copyPrd","width=300,height=150,resizable=yes");
	}
}

//관련상품등록
function addReation(){
	<? if($mode == "insert"){ ?>
		alert("상품등록 후 관련상품을 등록하세요.");
	<? }else{ ?>
		var url = "prd_rellist.php?prdcode=<?=$prdcode?>";
		window.open(url, "addReation", "height=900, width=800, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, left=150, top=100");
	<? } ?>
}

//관련상품삭제
function relDel(n){
	if(confirm("관련상품을 삭제하시겠습니까?")){
		var url = "prd_save.php?prdcode=<?=$prdcode?>&idx="+n+"&mode=reldel&<?=$menucodeParam?>";
		document.location = url;
	}
}

function selectAll(){

	if($("#checkAll").prop("checked")) {
		$("input[name=select_checkbox]:checkbox").prop("checked", true);
	} else {
		$("input[name=select_checkbox]:checkbox").prop("checked", false);
	}
}

function prdDelete(){

	if($('input[name=select_checkbox]:checked').length == 0){
		alert("삭제할 관련상품을 선택하세요.");
		return false;

	} else {

		var select_checkbox = [];

		$('input[name=select_checkbox]:checked').each(function(){
			select_checkbox.push(this.value);
		});

		if(confirm("관련상품을 삭제하시겠습니까?\n삭제이후 복구할수 없습니다.")){

			var chkval = select_checkbox.join(',');
			var mode = "multireldel";
			$.ajax({
				type: "post",
				url: "prd_save.php",
				data : {mode:mode,chkval:chkval},
				success: function (data) {
					data = data.trim();
					if(data == "delok"){
						alert("관련상품이 삭제되었습니다.");
						location.reload();
					}
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

//-->
</script>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="setClass01();setCategory();prdlayCheck();lodingComplete();">

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">상품관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">상품 검색/추가/수정/삭제 관리합니다.</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 기본정보</td>
	</tr>
</table>

<form name="frm" action="prd_save.php?<?=$param?>" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="relidx" value="<?=$relidx?>">
<input type="hidden" name="prdcode" value="<?=$prdcode?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">

	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" class="t_name">상품분류</td>
					<td width="85%" class="t_value" colspan="3">
						<select name="class01" onChange="changeClass01();" class="mul_select" style="width:220px;" size="4" multiple></select>
						<select name="class02" onChange="changeClass02();" class="mul_select" style="width:220px;" size="4" multiple></select>
						<select name="class03" onChange="changeClass03();" class="mul_select" style="width:220px;" size="4" multiple></select>
						<select name="class04" onChange="changeClass04();" class="mul_select" style="width:220px;" size="4" multiple></select>&nbsp;
					</td>
				</tr>
				<tr>
					<td class="t_name">상품명</td>
					<td colspan="3" class="t_value">
						<input name="prdname" type="text" value="<?=$prd_info['prdname']?>" size="60" class="input">&nbsp;
						<span style="vertical-align: middle"><input type="checkbox" name="recom" value="Y" <? if($prd_info['recom'] == "Y") echo "checked"; ?>></span>추천상품
					</td>
				</tr>
				<!-- <tr>
					<td class="t_name">구매하기</td>
					<td colspan="3" class="t_value">
						http://<input name="info_name1" type="text" value="<?=$prd_info['info_name1']?>" size="60" class="input"> <span style="color:#ff0000;">(쇼핑몰에 동일한 상품을 등록하신 후 생성된 URL을 입력해주세요.)</span>
					</td>
				</tr> -->
				<tr>
					<td class="t_name">상품진열</td>
					<td colspan="3" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="showset" value="Y" <? if($prd_info['showset'] == "Y" || empty($prd_info['showset'])) echo "checked"; ?>></span>진열함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="showset" value="N" <? if($prd_info['showset'] == "N") echo "checked"; ?>></span>진열안함&nbsp;&nbsp;
						<? if($site_info['mobile_use'] == 'Y' && $site_info['mobile_show_use']=="Y"){ ?>
						<span style="vertical-align: middle"><input type="checkbox" name="mobileShow" value="Y" <? if($prd_info['mobileShow'] == "Y") echo "checked"; ?>></span><font color='red'>모바일 진열</font>
						<? }else{ ?>
							<input type="hidden" name="mobileShow" value="<?=$prd_info['mobileShow']?>">
						<?}?>
					</td>
				</tr>

				<!-- <tr>
					<td class="t_name">영문상품명</td>
					<td colspan="3" class="t_value">
						<input name="prdnum" type="text" value="<?=$prd_info['prdnum']?>" size="30" class="input">
					</td>
				</tr> -->
				<!-- <tr>
					<td class="t_name">상품가격</td>
					<td colspan="3" class="t_value">
						<input name="prdprice" type="text" value="<?=$prd_info['prdprice']?>" size="30" class="input">
					</td>
				</tr> -->
				<tr>
					<td class="t_name">상품정보</td>
					<td colspan="3" class="t_value">
						<table border="0" cellspacing="5" cellpadding="0">
							<tr>
								<td></td>
								<td>상품가격</td>
								<td>1,000원 (예시)</td>
							</tr>
							<tr>
								<td>1.</td>
								<td><input name="info_name1" type="text" value="<?=$prd_info['info_name1']?>" size="15" class="input"></td>
								<td><input name="info_value1" type="text" value="<?=$prd_info['info_value1']?>" size="20" class="input"></td>
								<td width="60" align="right">6.</td>
								<td><input name="info_name6" type="text" value="<?=$prd_info['info_name6']?>" size="15" class="input"></td>
								<td><input name="info_value6" type="text" value="<?=$prd_info['info_value6']?>" size="20" class="input"></td>
							</tr>
							<tr>
								<td>2.</td>
								<td><input name="info_name2" type="text" value="<?=$prd_info['info_name2']?>" size="15" class="input"></td>
								<td><input name="info_value2" type="text" value="<?=$prd_info['info_value2']?>" size="20" class="input"></td>
								<td align="right">7.</td>
								<td><input name="info_name7" type="text" value="<?=$prd_info['info_name7']?>" size="15" class="input"></td>
								<td><input name="info_value7" type="text" value="<?=$prd_info['info_value7']?>" size="20" class="input"></td>
							</tr>
							<tr>
								<td>3.</td>
								<td><input name="info_name3" type="text" value="<?=$prd_info['info_name3']?>" size="15" class="input"></td>
								<td><input name="info_value3" type="text" value="<?=$prd_info['info_value3']?>" size="20" class="input"></td>
								<td align="right">8.</td>
								<td><input name="info_name8" type="text" value="<?=$prd_info['info_name8']?>" size="15" class="input"></td>
								<td><input name="info_value8" type="text" value="<?=$prd_info['info_value8']?>" size="20" class="input"></td>
							</tr>
							<tr>
								<td>4.</td>
								<td><input name="info_name4" type="text" value="<?=$prd_info['info_name4']?>" size="15" class="input"></td>
								<td><input name="info_value4" type="text" value="<?=$prd_info['info_value4']?>" size="20" class="input"></td>
								<td align="right">9.</td>
								<td><input name="info_name9" type="text" value="<?=$prd_info['info_name9']?>" size="15" class="input"></td>
								<td><input name="info_value9" type="text" value="<?=$prd_info['info_value9']?>" size="20" class="input"></td>
							</tr>
							<tr>
								<td>5.</td>
								<td><input name="info_name5" type="text" value="<?=$prd_info['info_name5']?>" size="15" class="input"></td>
								<td><input name="info_value5" type="text" value="<?=$prd_info['info_value5']?>" size="20" class="input"></td>
								<td align="right">10.</td>
								<td><input name="info_name10" type="text" value="<?=$prd_info['info_name10']?>" size="15" class="input"></td>
								<td><input name="info_value10" type="text" value="<?=$prd_info['info_value10']?>" size="20" class="input"></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td class="t_name">상품별 검색엔진 최적화(SEO)</td>
					<td colspan="3" class="t_value">
						<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
							<tr>
								<td width="15%" class="t_name">노출여부</td>
								<td width="85%" class="t_value">
									<label><span style="vertical-align: middle"><input type="radio" name="prd_seo_use" value="Y" <?php if($prd_info['prd_seo_use'] == 'Y') echo "checked" ?>></span>노출</label>
									<label><span style="vertical-align: middle"><input type="radio" name="prd_seo_use" value="N" <?php if($prd_info['prd_seo_use'] == 'N') echo "checked" ?>></span>미노출</label>
								</td>
							</tr>
							<tr>
								<td align="left" class="t_name">브라우저타이틀</td>
								<td class="t_value padd">
									<input type="text" name="prd_br_title" value="<?=$prd_info['prd_br_title']?>" size="80" class="input">
								</td>
							</tr>
							<tr>
								<td align="left" class="t_name">메타네임(Description)</td>
								<td class="t_value padd">
									<textarea name="prd_descript" rows="3" cols="120" class="textarea"><?php echo $prd_info['prd_descript'] ?></textarea>
									<div class="sub_tit_alt2"> 네이버 검색결과에 사이트 설명으로 노출되는 부분입니다.</div>
									<div class="sub_tit_alt2"> 메타태그 설명은 30~45자를 유지하는 게 좋습니다.</div>
								</td>
							</tr>
							<tr>
								<td align="left" class="t_name">메타네임(Classification)</td>
								<td class="t_value padd">
									<textarea name="prd_classification" rows="3" cols="120" class="textarea"><?php echo $prd_info['prd_classification'] ?></textarea>
									<div class="sub_tit_alt2"> 사이트의 분류(카테고리)를 작성합니다.</div>
								</td>
							</tr>
							<tr>
								<td align="left" class="t_name">메타네임(keywords)</td>
								<td class="t_value padd">
									<textarea name="prd_keywords" rows="3" cols="120" class="textarea"><?php echo $prd_info['prd_keywords'] ?></textarea>
									<div class="sub_tit_alt_red">※ 해당 항목은 네이버 및 구글에선 참고용으로만 활용합니다.</div>
									<div class="sub_tit_alt_red">※ 검색엔진 검색시 키워드를 사이트의 접근성을 향상에 활용한다고 명시되어 있으나, 검색엔진반영에는 일정 시간이 소요되며 일부 반영이 안될 수 있습니다.</div>
									<div class="sub_tit_alt2"> 키워드나 키워드 구문을 콤마(,)로 구분하세요.</div>
									<div class="sub_tit_alt2"> 목록의 처음에 가장 중요한 단어나 구문을 넣으세요.</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td class="t_name">상품사진</td>
					<td colspan="3" class="t_value">
						<? include "./prd_img.php"; ?>
					</td>
				</tr>

				<tr>
					<td height="25" class="t_name">첨부파일</td>
					<td class="t_value" colspan="3">
						<table border="0" cellspacing="5" cellpadding="0">
							<?
							for($j=1; $j<=5; $j++){
							?>
							<tr>
								<td><?=$j?>.</td>
								<td>
									<div class="filebox preview-image">
									<input class="input upload-name" value="파일선택" disabled="disabled">
									<label for="cinput-file<?=$j?>">첨부파일 업로드</label>
									<input type="file" name="upfile<?=$j?>" id="cinput-file<?=$j?>" class="upload-hidden">
									<? if(@file($imgpath."/".$prd_info['upfile'.$j])){ ?>
									<input type="checkbox" name="delupfile[]" value="upfile<?=$j?>">삭제 (<a href="<?=$imgpath?>/<?=$prd_info['upfile'.$j]?>" target="_blank"><?=$prd_info['upfile'.$j.'_name']?></a>)
									<? } ?>
								</td>
							</tr>
							<?
							}
							?>
						</table>
					</td>
				</tr>
				<tr>
					<td height="25" class="t_name">관련상품</td>
					<td colspan="10" align="center">
						<table width="100%" border="0" cellspacing="1" cellpadding="2">
							<tr>
								<td width="100%" align="center">
									<table width="99%" border="1" cellpadding="0" cellspacing="0">
										<tr><td width="100%" height="40" align="right" colspan="8"><input type="button" value="관련상품등록" class="btnListchk3" onClick="addReation();"></td></tr>
										<tr class="t_th">
											<th width="5%"><input type="checkbox" id="checkAll" onclick="selectAll()"></th>
											<th width="5%">번호</th>
											<th width="10%">이미지</th>
											<th>상품명</th>
											<th width="10%">진열여부</th>
											<th width="15%">기능</th>
										</tr>
										<tr><td class="t_rd" colspan=20></td></tr>
										<?
										$rel_sql = "
											
											select 
											
												wr.idx               ,
												wp.prdcode           ,
												wp.prdname           ,
												wp.prdimg_R
												
											from
											
												wiz_prdrelation2 wr  ,
												wiz_product2 wp 
												
											where 
											
												wr.prdcode = '$prdcode' and 
												wr.relcode = wp.prdcode
												
											";
										$rel_result = query($rel_sql);
										$no = 1;
										while($rel_row = sql_fetch_obj($rel_result)){

										?>
										<tr>
											<td align="center" height="55"><input type="checkbox" name="select_checkbox" id="select_checkbox" value="<?=$rel_row->idx?>"></td>
											<td align="center"><?=$no?></td>
											<td align="center"><a href="prd_input.php?mode=update&prdcode=<?=$rel_row->prdcode?>" target="_blank"><img src="/twcenter/data/product2/<?=$rel_row->prdimg_R?>" width="50" height="50" border="0"></a></td>
											<td align="lefnt" style="padding: 0 0 0 10px"><?=$rel_row->prdname?></td>
											<td align="center"><font color="red">진열</font></td>
											<td align="center"><input type="button" value="삭제" class="suboptdel" onclick="relDel(<?=$rel_row->idx?>)"></td>
										</tr>
										<tr><td colspan="20" class="t_line"></td></tr>
										<?
										$no++;
										}
										?>
									</table>
									<table width="99%" height="10" border="0" cellpadding="0" cellspacing="0">
										<tr><td height="5"></td></tr>
										<tr>
											<td width="33%">
												<input type="button" value="선택삭제" class="btnListchk" onclick="prdDelete()">
											</td>
										</tr>
									</table>
								
								</td>
							</tr>
							<tr><td height="5"></td></tr>
						</table>
					</td>
				</tr>
				
			</table>
		</td>
	</tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 상품설명</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>

					<td width="15%" height="25" class="t_name">상품간단설명</td>
					<td width="85%" class="t_value" colspan="3">
						<textarea name="shortexp" rows="5" cols="50" style="width:99%" class="textarea"><?=$prd_info['shortexp']?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="t_name">상품상세정보</td>
				</tr>
				<tr>
					<td colspan="3" align="center">
						<?
						$edit_content = $prd_info['content'];
						include "../../webedit/WIZEditor.html";
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<? if($site_info['mobile_use'] == 'Y'){ ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 모바일 상품설명</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
				<tr>
					<td colspan="3" class="t_name">모바일 상세정보</td>
				</tr>
				<tr>
					<td colspan="3" align="center">
					<?
					$edit_name	  = "mcontent";
					$edit_content = $prd_info['mcontent'];
					include "../../webedit/WIZEditor.html";
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<? } ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='prd_list.php?<?=$param?>';">
		</td>
<!--td width="33%" align="right">
<input type="button" value="상품이동" onClick="movePrd();" class="sbtn">
<input type="button" value="상품복사" onClick="copyPrd();" class="sbtn">
</td-->
	</tr>
</table>
</form>

<? include "../foot.php"; ?>
