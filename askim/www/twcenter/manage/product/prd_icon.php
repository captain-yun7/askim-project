<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<title>:: 상품 아이콘 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../js/jquery-1.11.3.min.js"></script>
<script language="javascript">
<!--
function iconDel(){
	var selicon = "";
	var prdicon = document.frm.prdicon;
	if(prdicon.length == undefined) {
		if(prdicon.checked == true) selicon = prdicon.value;
	} else {
		for(ii=0;ii < prdicon.length;ii++){
			if(prdicon[ii].checked == true){
				selicon = prdicon[ii].value;
			}
		}
	}
	if(selicon == "") {
		alert("삭제할 아이콘을 선택하세요.");
	} else {
		document.location = "prd_save.php?mode=icondel&prdicon=" + selicon;
	}
}
function inputCheck(frm){
	if(frm.upfile.value == ""){
		alert("등록할 아이콘이 없습니다.");
		return false;
	}
}
-->
</script>
</head>
<body topmargin="0" leftmargin="0">
<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%"><?=$product?>상품 아이콘</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<table width="100%" cellpadding="10" cellspacing="0">
	<tr>
		<td align="center">
			<form name="frm" action="prd_save.php" method="post" onSubmit="return inputCheck(this);" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="prdicon">
			<table width="99%" align="center" border="0" cellspacing="1" cellpadding="2" class="t_style">
			  <tr>
				<td align="center" class="t_value">
				<table>
					<?
					$no = 0;
					if($handle = opendir('../../data/prdicon')){
						while(false !== ($file_name = readdir($handle))){
							if($file_name != "." && $file_name != ".."){
								if($no%5 == 0) echo "<tr>";
					?>
					<td>
						<input type="radio" name="prdicon" value="<?=$file_name?>" <? if($prdicon_list["$file_name"]==true) echo "checked";?>> <img src="/twcenter/data/prdicon/<?=$file_name?>" border="0" style="vertical-align:middle">&nbsp;
					</td>
					<?
								$no++;
							}
						}
						closedir($handle);
					}
					?>
				  </table>
				  </td>
			  </tr>
			</table>
			<span class="tip_br2"></span>
			<table width="98%" align="center" border="0" cellspacing="0" cellpadding="0">
			  <? if($no > 0) { ?>
			  <tr>
				<td align="right" style="padding:0 0 0 5px">
					<input type="button" value="선택아이콘 삭제" class="base_btm gray" onclick="iconDel()">
				</td>
			  </tr>
			  <? } ?>
			</table><br>
			<div class="filebox preview-image">
				<input class="input upload-name" value="아이콘선택" disabled="disabled">
				<label for="input-file">아이콘 업로드</label>
				<input type="file" name="upfile" id="input-file" class="upload-hidden"> 
				<input type="submit" value="등 록" class="base_btn4 blue">
			</div> 
			</form>
		</td>
	</tr>
</table>
</body>
</html>