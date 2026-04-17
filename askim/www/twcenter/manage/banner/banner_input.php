<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include "../head.php"; ?>

<?
if($mode == "")	$mode = "ban_insert";

if($mode == "ban_update" || $mode == "ban_insert") {
	if($mode == "ban_update"){
	  $sql = "select * from wiz_bannerinfo where idx='$idx'";
	  $result = query($sql) or error("sql error");
	  $ban_info = sql_fetch_obj($result);
	}
?>
<script language="JavaScript">
<!--
function inputCheck(frm){
	try
	{
		if(frm.code.value == ""){
			alert("코드를 입력하세요.");
			frm.code.focus();
			return false;
		} else if(!check_Char(frm.code.value)) {
			alert('코드는 특수문자를 사용할 수 없습니다.');
			frm.code.focus();
			return false;
	   }
		if(frm.title.value == ""){
			alert("그룹이름을 입력하세요.");
			frm.code.focus();
			return false;
		}
		if(frm.use_skin.checked == true && frm.skin.value.trim() == "") {
			alert("스킨 코드를 입력하세요.");
			return false;
		} else {
			let skin_html = frm.skin.value;
			skin_html = skin_html.replace(/</gi, '<||<');
			skin_html = skin_html.replace(/>/gi, '>||>');
			$("[name=skin]").css("width","0");
			frm.skin.value = skin_html;
		}
	}
	catch (e)
	{
		alert("오류가 발생하였습니다.");
		console.log(e);
		return false;
	}
}
$(document).on("click, change", "[name=use_skin]", function() {
	if($(this).is(":checked")) {
		$("[name=skin]").css("width","99%");
		$("#skin_input").show();
	} else {
		$("#skin_input").hide();
	}
});

$(document).on("click", "#limit_chk", function() {
	if($(this).is(":checked")) {
		$("[name=limit_rows]").prop("disabled", false).focus();
	} else {
		$("[name=limit_rows]").val('').prop("disabled", true);
	}
});

$(document).on("click", "#basic_skin", function() {
	let basic_skin_set = function() {
		let html = '<dl>\n[LOOP]\n  <dd>{LINK}<img src="{IMG1}">{TXT1}{/LINK}</dd>\n[/LOOP]\n</dl>';
		$("textarea[name=skin]").val(html);
	};
	if($("textarea[name=skin]").val() != "") {
		if(confirm("기본 스킨을 적용하시겠습니까? 현재 적용된 스킨이 삭제됩니다.")){ 
			basic_skin_set();
		} else {
			$(this).prop("checked", false);
		}
	} else basic_skin_set();
});


function popGrp() {
	var url = "group.php";
	window.open(url,"BBSGroup","height=250, width=350, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

function grp_reload() {
	let obj = $("select[name=grp]");
	let old_grp = obj.find("option:selected").val();
	console.log(old_grp);
	$.get("./banner_save.php", "mode=get_grplist", function(res) {
		obj.find("option").not("[value='']").remove();
		for(let i = 0; i < res.length; i++) {
			selected = (old_grp == res[i]['idx']) ? " selected" : "";
			obj.append("<option value='"+res[i]['idx']+"'"+selected+">"+res[i]['grpname']+"</option>");
		}
	}, "json");
}
//-->
</script>

	<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">디자인관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">디자인를 추가/수정/삭제 관리합니다.</td>
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
          <td width="15%" class="t_name">코드(영문) <font color="red">*</font></td>
          <td class="t_value"><input type="text" name="code" value="<?=$ban_info->code?>" size="30" class="input" <? if(!strcmp($mode, 'ban_update')) echo "readonly" ?>> <span style="color:#ff0000;">※ 반드시 영문으로 작성 / 변경 불가능</span></td>
		 </tr>
		 <tr>
          <td width="15%" class="t_name">한글명 <font color="red">*</font></td>
          <td class="t_value"><input type="text" name="title" value="<?=$ban_info->title?>" size="30" class="input" ></td>
        </tr>
        <!-- <tr>
          <td class="t_name">디자인형태</td>
          <td class="t_value">&nbsp;
            <span style="vertical-align: middle"><input type="radio" name="types" value="H" size="80" <? if($ban_info->types == "H" || $ban_info->types == "") echo "checked"; ?>></span> 세로형 &nbsp;
            <span style="vertical-align: middle"><input type="radio" name="types" value="W" size="80" <? if($ban_info->types == "W") echo "checked"; ?>></span> 가로형 &nbsp;
          </td>
          <td class="t_name">디자인간격</td>
          <td class="t_value"><input type="text" name="padding" value="<?=$ban_info->padding?>" size="30" class="input">
          </td>
        </tr> -->


<!--	@@ 디자인그룹관리 -->
				<tr>
					<td height="10" align="left" class="t_name">디자인그룹</td>
					<td class="t_value" colspan="3">
					<?php
					$grplist = get_grplist("banner");
					?>
						<select name="grp" id="grpno" class="select">
							<option value="">:: 디자인그룹 ::</option>
							<? foreach($grplist as $grp) { ?>
							<option value="<?=$grp['idx']?>" <? if($ban_info->grp == $grp['idx']) echo "selected" ?>><?=$grp['grpname']?></option>
							<? } ?>
						</select>
						<input type="button" value="그룹관리" class="base_btm reg" onclick="popGrp()">&nbsp;&nbsp;
						그룹내우선순위
						<select name="prior" class="select">
						<? for($ii = 1; $ii <= 10; $ii++) { ?>
							<option value="<?=$ii?>" <? if($ban_info->prior == $ii) echo "selected"; ?>><?=$ii?></option>
						<? } ?>
						</select> <span class="tip_br5"></span>
						
						<div class="sub_tit_alt2"> 디자인그룹은 디자인가 많은 경우 게시판을 그룹별로 효과적으로 관리하기 위한 기능입니다.</div>
						<span class="tip_br5"></span>
						<div class="sub_tit_alt2"> 그룹 내에서 디자인의 우선순위는 작을수록 순위가 높습니다.</div>
					</td>
				</tr>
<!-- @@ 디자인그룹관리 END -->


        <tr>
          <td class="t_name">사용여부</td>
          <td class="t_value">
            <span style="vertical-align: middle"><input type="radio" name="isuse" value="Y" size="80" <? if($ban_info->isuse == "Y" || $ban_info->isuse == "") echo "checked"; ?>></span> 사용함 &nbsp;
            <span style="vertical-align: middle"><input type="radio" name="isuse" value="N" size="80" <? if($ban_info->isuse == "N") echo "checked"; ?>></span> 사용안함
          </td>
          <!-- <td class="t_name">디자인개수</td>
			  <td class="t_value">
			  <select name="types_num" class="select">
			  <option value="1" <? if($ban_info->types_num == "1") echo "selected"; ?>>1
			  <option value="2" <? if($ban_info->types_num == "2") echo "selected"; ?>>2
			  <option value="3" <? if($ban_info->types_num == "3") echo "selected"; ?>>3
			  <option value="4" <? if($ban_info->types_num == "4") echo "selected"; ?>>4
			  <option value="5" <? if($ban_info->types_num == "5") echo "selected"; ?>>5
			  <option value="6" <? if($ban_info->types_num == "6") echo "selected"; ?>>6
			  <option value="7" <? if($ban_info->types_num == "7") echo "selected"; ?>>7
			  <option value="8" <? if($ban_info->types_num == "8") echo "selected"; ?>>8
			  <option value="9" <? if($ban_info->types_num == "9") echo "selected"; ?>>9
			  </select>
			  </td> -->
        </tr>
		<tr>
          <td class="t_name">출력개수 지정</td>
          <td class="t_value">
		  	<input type="checkbox" name="limit_chk" id="limit_chk" value="Y"<?if($ban_info->limit_chk == "Y") echo " checked";?>> 제한 &nbsp;
			<input type="text" class="input Onum" name="limit_rows" size="3" maxlength="3" value="<?=$ban_info->limit_rows?>" <?if($ban_info->limit_chk != "Y") echo "disabled";?>><br>
			<font color="red">* 출력 개수 제한 시 등록된 디자인 중 일부만 출력됩니다. (지정된 우선순위 및 등록 순)</font>
          </td>
        </tr>
		<tr>
          <td class="t_name">디자인당 이미지 개수</td>
          <td class="t_value">
			<table border=0 width="100%">
			  <tr>
			  	<td width="80">
					<select name="imgs" class="select">
					<? for($i=1; $i<=10; $i++) { ?>
					<option value="<?=$i?>"<?if($ban_info->imgs == $i) echo " selected";?>><?=$i?></option>
					<? } ?>
					</select>
				</td>
				<td width="100" style="background-color:#f5f5f5;text-align:center">노이미지 경로</td>
				<td style="padding-left: 5px;"><?=SSL?><?=$HTTP_HOST?>/<input type="text" name="noimg" class="input" size="40" value="<?=$ban_info->noimg?>"><br><font color="red">* 파일 위치 입력, 미입력시 기본 경로 /img/banner_noimg.gif</font></td>
			  </tr>
			</table>
          </td>
        </tr>
		<tr>
          <td class="t_name">디자인당 텍스트 개수</td>
          <td class="t_value">
			<select name="txts" class="select">
			<? for($i=1; $i<=10; $i++) { ?>
			<option value="<?=$i?>"<?if($ban_info->txts == $i) echo " selected";?>><?=$i?></option>
			<? } ?>
			</select>
          </td>
        </tr>
        <tr>
          <td class="t_name">스킨 </td>
          <td class="t_value">
            <span style="vertical-align: middle"><input type="checkbox" name="use_skin" value="Y" <? if($ban_info->use_skin == "Y") echo "checked"; ?>></span> 사용 &nbsp;
			<div id="skin_input" style="margin-top:10px;<? if($ban_info->use_skin != "Y") echo " display:none;";?>">
				<p class="tit_sub"><img src="../image/ics_tit.gif">  스킨형 디자인 적용 코드</p>
				  <div style="border:1px solid #ddd;background-color:#eee">
				  <font color="red">&lt;?php<br>&nbsp;&nbsp;&nbsp;&nbsp;$banner_code = "<?echo ($ban_info->code) ? $ban_info->code : "디자인코드";?>";<br>&nbsp;&nbsp;&nbsp;&nbsp;include $_SERVER['DOCUMENT_ROOT']."/twcenter/module/banner_skin.php";     // 디자인 스킨 적용<br>?&gt;</font></div>
				<P style="margin:5px 0"><input type="button" id="basic_skin" class="base_btm reg" value="기본스킨 적용"></P>
				<textarea class="textarea" name="skin" style="width:100%;height:200px"><?=$ban_info->skin?></textarea>
				<div id="skin_guide">
                  <font color="red">* 반복되는 부분을 [LOOP]와 [/LOOP]사이에 위치하게 합니다. (디자인 그룹 내에 등록된 디자인 반복 출력)</font><br><br>
                  <table width="100%" border="0" cellpadding="5" cellspacing="3" class="">
                    <tr>
                      <td bgcolor="#FFFFFF"<?// class="helpTip"?>>
						<!--h4>사용방법</h4-->
                        <table width="100%" border="0">
						<colgroup>
							<col width="100"/>
							<col width="5"/>
							<col width="100"/>
							<col style="font-weight:400"/>
						</colgroup>
                        <tr>
							<td width="100"><b>{IMG1}~{IMG10}</b></td>
							<td width="5">:</td>
							<td width="80">이미지1~10</td>
							<td>&lt;img src="{IMG1}"&gt; 태그 또는 style="background-image:url({IMG1})" 스타일 이용해 이미지 삽입</td>
						</tr>
                        <tr><td><b>{TXT1}~{TXT10}</b></td><td>:</td><td>텍스트1~10</td><td></td></tr>
                        <tr><td><b>{LINK} {/LINK}</b></td><td>:</td><td>링크주소 (a 태그)</td><td><font color='red'>{LINK}</font><u>링크포인트</u><font><font color='red'>{/LINK}</font> 형식으로 사용</td></tr>
                        </table>
                      </td>
                    </tr>
                  </table>

				</div>
			</div>
          </td>
		</tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr>
      		<td align="center">
				<input type="submit" value="확인" class="base_btn reg">&nbsp;
				<input type="button" value="목록" class="base_btn gray" onClick="document.location='banner_list.php?page=<?=$page?>&<?=$menucodeParam?>';">
      		</td>
        </tr>
      </table>
	  </form>


	  
<?
  }
?>

<? include "../foot.php"; ?>