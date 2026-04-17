<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include "../head.php"; ?>
<?
  if($mode == "") $mode = "insert";
  if($code) {
	  $sql_binfo = "select * from wiz_bannerinfo where code='".$code."'";
	  $binfo = sql_fetch($sql_binfo);
  }
?>

<script language="JavaScript">
<!--
function inputCheck(frm){
	content.outputBodyHTML();
}
$(document).ready(function() {
	sel_code();
});

function sel_de_type() {
	let de_type = $("select[name=de_type] option:selected").val();
	if(de_type == "SKIN" || de_type == "IMG") {
		$("tr.img").show();
		$("tr.html").hide();
	} else {
		$("tr.html").show();
		$("tr.img").hide();
	}
}

function sel_code() {
	let sel_opt = $("select[name=code] option:selected");
	let use_skin = sel_opt.data("skin");
	let imgs = sel_opt.data("imgs");
	let txts = sel_opt.data("txts");
	if(imgs == "" || imgs < 1) imgs = 1;
	if(txts == "" || txts < 1) txts = 1;

	if(use_skin == "Y") {
		$("select[name=de_type] option[value='SKIN']").prop("selected", true).prop("disabled", false);
		$("select[name=de_type] option").not("[value='SKIN']").prop("selected", false).css("display", "none");
	} else {
		$("select[name=de_type] option[value='SKIN']").prop("selected", false).css("display", "none");
		$("select[name=de_type] option").not("[value='SKIN']").css("display", "unset");
	}
	for(let i = 2; i <= 10; i++) {
		if(i <= imgs) $("#img"+i).show();
		else $("#img"+i).hide();
	}
	for(let i = 2; i <= 10; i++) {
		if(i <= txts) $("#txt"+i).show();
		else $("#txt"+i).hide();
	}
	sel_de_type();
}
//-->
</script>

<?
if($mode == "update" || $mode == "insert") {
	if($mode == "update"){
	  $sql = "select * from wiz_banner where idx='$idx'";
	  $result = query($sql) or error("sql error");
	  $ban_info = sql_fetch_obj($result);
	  $ban_info->de_html = stripslashes($ban_info->de_html);
	}
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
      <form name="frm" action="banner_save.php" method="post" onSubmit="return inputCheck(this)" enctype="multipart/form-data">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="idx" value="<?=$idx?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
        <tr>
          <td width="15%" class="t_name">디자인방법</td>
          <td width="35%" class="t_value">
          <select name="de_type" class="select" onchange="sel_de_type()">
			  <option value="IMG" <? if($ban_info->de_type == "IMG" || $ban_info->de_type == "") echo "selected"; ?>>이미지</option>
			  <option value="HTML" <? if($ban_info->de_type == "HTML") echo "selected"; ?>>HTML</option>
			  <option value="SKIN" <?if($ban_info->de_type == "SKIN") echo "selected";?>>SKIN</option>
          </select>
          </td>
          <td width="15%" class="t_name">사용여부</td>
          <td width="35%" class="t_value">
            <span style="vertical-align: middle"><input type="radio" name="isuse" value="Y" size="80" <? if($ban_info->isuse == "Y" || $ban_info->align == "") echo "checked"; ?>></span> 사용함 &nbsp;
            <span style="vertical-align: middle"><input type="radio" name="isuse" value="N" size="80" <? if($ban_info->isuse == "N") echo "checked"; ?>></span> 사용안함
          </td>
        </tr>
        <tr>
          <td class="t_name">디자인명 <font color="red">*</font></td>
          <td class="t_value">
            <select name="code" class="select" onchange="sel_code()">
           <?
              $sql = "select * from wiz_bannerinfo";
	            $result = query($sql) or error("sql error");

	            while(($row = sql_fetch_obj($result))){
	         ?>
	           <option value="<?=$row->code?>" <? if($row->code == $code) echo "selected"; ?> data-skin="<?=$row->use_skin?>" data-imgs="<?=$row->imgs?>" data-txts="<?=$row->txts?>"><?=$row->title?>
	         <?
	            }
	         ?>
            </select>
          </td>
          <td class="t_name">우선순위</td>
          <td class="t_value">
          <select name="prior" class="select">
          <option value="1" <? if($ban_info->prior == "1") echo "selected"; ?>>1
          <option value="2" <? if($ban_info->prior == "2") echo "selected"; ?>>2
          <option value="3" <? if($ban_info->prior == "3") echo "selected"; ?>>3
          <option value="4" <? if($ban_info->prior == "4") echo "selected"; ?>>4
          <option value="5" <? if($ban_info->prior == "5") echo "selected"; ?>>5
          <option value="6" <? if($ban_info->prior == "6") echo "selected"; ?>>6
          <option value="7" <? if($ban_info->prior == "7") echo "selected"; ?>>7
          <option value="8" <? if($ban_info->prior == "8") echo "selected"; ?>>8
          <option value="9" <? if($ban_info->prior == "9") echo "selected"; ?>>9
          <option value="10" <? if($ban_info->prior == "10") echo "selected"; ?>>10
          <option value="11" <? if($ban_info->prior == "11") echo "selected"; ?>>11
          <option value="12" <? if($ban_info->prior == "12") echo "selected"; ?>>12
          <option value="13" <? if($ban_info->prior == "13") echo "selected"; ?>>13
          <option value="14" <? if($ban_info->prior == "14") echo "selected"; ?>>14
          <option value="15" <? if($ban_info->prior == "15") echo "selected"; ?>>15
          <option value="16" <? if($ban_info->prior == "16") echo "selected"; ?>>16
          <option value="17" <? if($ban_info->prior == "17") echo "selected"; ?>>17
          <option value="18" <? if($ban_info->prior == "18") echo "selected"; ?>>18
          <option value="19" <? if($ban_info->prior == "19") echo "selected"; ?>>19
          <option value="20" <? if($ban_info->prior == "20") echo "selected"; ?>>20
          </select>
          </td>
        </tr>
		<?php if($code != "visual" && $code != "popular_spot" && $code != "customized" && $code != "creativity" && $code != "main_brand1" && $code != "main_brand2" && $code != "main_brand3" && $code != "indicator") {?>
        <tr>
          <td class="t_name">링크주소</td>
          <td class="t_value" colspan="3">
		  <p class="banner_info">※ 주소 입력시,  http:// 또는 https:// 포함하여 입력 바랍니다.</p>
          <input type="text" name="link_url" value="<?=$ban_info->link_url?>" size="60" class="input"> &nbsp;
          <span style="vertical-align: middle"><input type="checkbox" name="link_target" value="_BLANK" <? if($ban_info->link_target == "_BLANK") echo "checked"; ?>></span> 새창으로
          </td>
        </tr>
		<?php }?>
<?php if($code != "indicator") {?>
		<tr class="img">
		  <td class="t_name">컨텐츠이미지</td>
		  <td class="t_value" colspan="3">
		  <?
			  if($ban_info->de_img != "") echo "<img src='/twcenter/data/banner/$ban_info->de_img'><br>";
		  ?>
			<div class="fileno">#1</div>
			<div class="filebox preview-image">
				<input class="input upload-name" value="파일선택" disabled="disabled">
				<label for="input-file">파일 업로드</label>
				<input type="file" name="de_img" id="input-file" class="upload-hidden">
				<? if($ban_info->de_img != "") { ?><input type="checkbox" name="filedel" value="Y"> 파일삭제 (<?=$ban_info->de_img ?>)<? } ?>
			</div>
<?
			for($i=2; $i<=10; $i++) {
?>
			<div id="img<?=$i?>"><?if($ban_info->{"de_img".$i}) echo "<br><img src='/twcenter/data/banner/".$ban_info->{"de_img".$i}."'><br>";?>
				<div class="fileno">#<?=$i?></div>
				<div class="filebox preview-image">
					<input class="input upload-name" value="파일선택" disabled="disabled">
					<label for="input-file<?=$i?>">파일 업로드</label>
					<input type="file" name="de_img<?=$i?>" id="input-file<?=$i?>" class="upload-hidden">
					<? if($ban_info->{"de_img".$i} != "") { ?><input type="checkbox" name="filedel<?=$i?>" value="Y"> 파일삭제 (<?=$ban_info->{"de_img".$i} ?>)<? } ?>
				</div>
			</div>
<?
			}
?>
	
<?php if($code == "visual") {?>
	<p class="banner_info">
	<br/>
	※ 최적화 사이즈 : 1920 * 1080px<br/>
	※ 이미지 권장 확장자 : *.jpg, *.png, *.gif<br/>
	※ 첨부파일 총 20MB 까지 일괄 업로드 가능 (20MB 초과 시 나눠서 등록 요망)<br/>
	</p>
<?php }else if($code == "popular_spot" || $code == "customized" || $code == "creativity") {?>
	<p class="banner_info">
	<br/>
	※ 최적화 사이즈 : 1075 * 672px (4*2.5 비율 권장)<br/>
	※ 이미지 권장 확장자 : *.jpg, *.png, *.gif<br/>
	※ 첨부파일 총 20MB 까지 일괄 업로드 가능 (20MB 초과 시 나눠서 등록 요망)<br/>
	</p>
<?php }else if($code == "main_brand1" || $code == "main_brand2" || $code == "main_brand3") {?>
	<p class="banner_info">
	<br/>
	※ 최적화 사이즈 : 540 * 240px<br/>
	※ 이미지 권장 확장자 : *.jpg, *.png, *.gif<br/>
	※ 첨부파일 총 20MB 까지 일괄 업로드 가능 (20MB 초과 시 나눠서 등록 요망)<br/>
	</p>
<?php }else{?>

	<p class="banner_info"><br/>※ 첨부파일 총 20MB 까지 일괄 업로드 가능 (20MB 초과 시 나눠서 등록 요망)</p>
<?php }?>
		  </td>
		</tr>
<?php }?>
		<tr class="img">
			<td class="t_name">텍스트 입력</td>
			<td class="t_value" colspan="3">
<?
			for($i=1; $i<=10; $i++) {
?>
				<p id="txt<?=$i?>"><span class="fileno">#<?=$i?></span><input type="text" name="txt<?=$i?>" value="<?=$ban_info->{'txt'.$i}?>" size="120" class="input">
<?
}
?>
<?php if($code == "visual") {?>
	<p class="banner_info">
	<br/>
	#1 - 제목을 입력해주세요.
	</p>
<?php }else if($code == "popular_spot" || $code == "customized" || $code == "creativity") {?>
	<p class="banner_info">
	<br/>
	#1<b>(필수)</b> - 제목을 입력해주세요.
	</p>

<?php }else if($code == "main_brand1" || $code == "main_brand2" || $code == "main_brand3") {?>
	<p class="banner_info">
	<br/>
	#1<b>(필수)</b> - 브랜드명을 입력해주세요.
	</p>
<?php }else if($code == "indicator") {?>
	<p class="banner_info">
	<br/>
	#1<b>(필수)</b> - 연간 집행 앰비언트 캠페인(건)을 입력해주세요. <br/>
	#2<b>(필수)</b> - 글로벌 앰비언트 전용 매체 보유(개)을 입력해주세요. <br/>
	#3<b>(필수)</b> - 누적 캠페인 집행 국가(개국)을 입력해주세요. <br/>
	#4<b>(필수)</b> - 글로벌 커뮤니케이션(언어권)을 입력해주세요.
	</p>
<?php }else{?>

<?php }?>
	
			</td>
		</tr>

        <tr class="html">
          <td class="t_name">컨텐츠 HTML</td>
          <td class="t_value" colspan="3">
			  <p class="banner_info">※ 컨텐츠 디자인방법이 HTML 타입일 때 사용합니다.</p>
          <?
          $edit_content = $ban_info->de_html;
          $edit_height = "300";
          include "../../webedit/WIZEditor.html";
          ?>
          </td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr>
          <td align="center">
				<input type="submit" value="확인" class="base_btn reg">&nbsp;
				<input type="button" value="목록" class="base_btn gray" onClick="document.location='list.php?code=<?=$code?>&page=<?=$page?>&<?=$menucodeParam?>';">
          </td>
        </tr>
      </table>
	  </form>

<?
  }
?>

<? include "../foot.php"; ?>