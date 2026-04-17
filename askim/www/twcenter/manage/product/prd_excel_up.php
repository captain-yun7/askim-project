<?php
include "../../common.php";
include "../../inc/twcenter_check.php";

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<title>:: 상품정보 업로드 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../js/jquery-1.11.3.min.js"></script>
<script Language="Javascript">
<!--
$(function() {
	var fileTarget = $('.filebox .upload-hidden');

	fileTarget.on('change', function(){		// 값이 변경되면
		if(window.FileReader){				// modern browser
			var filename = $(this)[0].files[0].name;
		} else {  // old IE
			var filename = $(this).val().split('/').pop().split('\\').pop();  // 파일명만 추출
		}

		// 추출한 파일명 삽입
		$(this).siblings('.upload-name').val(filename);
	});
});

$(document).on('submit', '#frm', function(e) {

	e.preventDefault();

	if($("#input-file").val() == ""){
		alert("엑셀파일을 업로드하세요.");
		$("#input-file").focus();
		return false;
	}

	if($("#input-file").val() != ""){

		var fileObj, pathH, pathM, fileName;

		fileObj   = $("#input-file").val();
		pathH     = fileObj.lastIndexOf("\\");
		pathM     = fileObj.lastIndexOf(".");
		fileName  = fileObj.substring(pathH+1, pathM);
		var chk = fileName.charCodeAt();
		if(chk > 128){
			alert("한글파일명은 업로드 할 수 없습니다.");
			return;
		}

		var ext = fileObj.split('.').pop().toLowerCase();
		if($.inArray(ext, ['xls']) == -1) {
			alert('엑셀파일만 업로드 할 수 있습니다.');
			return;
		}

	}

	if(confirm('엑셀파일을 업로드 하시겠습니까?\n데이터양이 많은경우 시간이 지연될수 있습니다.')) {

		var maskHeight = $(document).height();
		var maskWidth = $(document).width();

		var mask = "<div id='mask' style='position:absolute; z-index:9000; background-color:#000000; display:none; left:0; top:0;'></div>";

		var loading2 = '';

		loading2 += "<div id='loading2' style='position:absolute; left:50%; top:50%; display:none; z-index:10000;'>";
		loading2 += " <img src='../image/loading_1.gif'/>"; 
		loading2 += "</div>";

		$('body').append(mask).append(loading2);

		$('#mask').css({
			'width' : maskWidth,
			'height': maskHeight,
			'opacity' : '0.1'
		});

		$('#mask').show();
		$('#loading2').show();

		var excel = $("#excelup").val();
		var img_path = $("[name=img_path]").val();
		var params = new FormData(this); 
		params.append("excelup", excel);
		params.append("img_path", img_path);

		$.ajax({
			url: '/comm/ajax/AjaxPrdExlUp.php'
			,type: 'POST'
			,data: params
			,dataType: 'json'
			,processData: false
			,contentType: false
		}).done(function(data) {
			//console.log(data.msg);
			if(data.result == '00') {
				$('#loading2').hide();
				alert(data.msg);
				opener.parent.location.reload(true);
				window.close();
			}
		}).fail(function(request, status, error){
			console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
		});

	} else {
		return false;
	}

});
//-->
</script>
</head>

<body>
<center>
<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">상품 엑셀업로드</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>
<form name="frm" id="frm" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="excelup" id="excelup" value="ok">

<table width="100%" cellpadding="10" cellspacing="0">
	<tr>
		<td align="center">
			<table width="99%" align="center" border="0" cellpadding="1" cellspacing="1" class="t_style">
				<tr>
					<td height="30" width="25%" align="center" class="t_name">파일첨부</td>
					<td>&nbsp;
						<span class="filebox">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file">파일 업로드</label>
							<input type="file" name="upfile" id="input-file" class="upload-hidden"> 
							<input type="button" value="샘플다운" class="base_btn2" onclick="document.location='prd_sample.xls'">
						</span>
					</td>
				</tr>
				<!-- <tr>
					<td height="30" width="25%" align="center" class="t_name">이미지경로</td>
					<td>&nbsp;
						http://<?=$_SERVER['HTTP_HOST']?>/<input type="text" name="img_path" class="input">/상품이미지.gif
					</td>
				</tr> -->
			</table>
			<table align="center" width="100%">
				<tr><td height="5"></td></tr>
				<tr>
					<td align="center">
						<input type="submit" value="확 인" class="base_btn reg" style="cursor:pointer">&nbsp;
						<input type="button" value="닫 기" class="base_btn gray" onClick="self.close();" style="cursor:pointer">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
</center>
<div class="helpTip">
	<h4>체크사항</h4>
	<div class="content">
	<div class="explain">
	1. <a href="prd_sample.xls"><b><font color="black">[샘플다운로드]</font></b></a>를 클릭하여 샘플파일을 다운받은후 양식에 맞게 업로드해주세요.<br>
	2. 각 항목에 값을 입력합니다.<br>
	&nbsp;&nbsp;- 상품그룹 : 해당하는 상품그룹을 "/"로 구분하여 입력합니다. <br>&nbsp;&nbsp;&nbsp;&nbsp;예) /신상품/인기상품/추천상품/세일상품<br>
	&nbsp;&nbsp;- 상품진열 : 진열함 = Y, 진열안함 = N<br>
	3. 업로드 파일의 확장자는 <font color='red'><strong>'xls'</strong></font> 만 허용됩니다.
	&nbsp;&nbsp;- 품절여부 : 품절상품 = Y, 무제한 = N, 수량 = S<br>
	&nbsp;&nbsp;- 상품사진1~5 : 축소, 제품상세, 확대이미지를 "/"로 구분하여 입력합니다.<br>&nbsp;&nbsp;&nbsp;&nbsp;예) /test1.jpg/test2.jpg/test3.jpg<br>
	3. FTP에 접속하여 상품이미지를 업로드합니다(upload폴더).<br>
	<u>4. 브랜드를 입력할 경우 브랜드관리에서 동일한 브랜드명으로 브랜드를 생성한 후 업로드하세요.<br></u>
	<?php
	/*
	작업일시	: 2020-08-25
	작업자명	: 이상민
	작업내용	: 상품 엑셀 업로드 시 분류명 중복에 따른 오류해결을 위해 분류코드를 넣도록 수정
	*/
	?>
	<u>5. 상품카테고리를 입력할 경우 상품분류관리에서 상품분류를 생성한 후 분류코드를 입력하여 업로드하세요.<br></u>
	6. 파일첨부를 하시고 FTP에 업로드한 이미지경로를 입력하고 확인버튼을 누릅니다.<br>
	</div>
	</div>
</div>
</body>
</html>
