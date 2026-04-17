<?php include_once "../../common.php"; ?>
<?php include_once "../../inc/twcenter_check.php"; ?>
<?php
if($mode == "") $mode = "insert";

if($mode == "insert"){

	$bbs_info['simgsize'] = "680";
	$bbs_info['mimgsize'] = "1350";
	$bbs_info['permsg'] = "권한이 없습니다.";
	$bbs_info['line'] = "4";

	$bbs_info['upfile'] = "1";
	$bbs_info['movie'] = "0";

}else if($mode == "update"){
	$sql = "select * from wiz_bbsinfo where code = '$code'";
	$result = query($sql) or error("sql error");
	$bbs_info = sql_fetch_arr($result);
}
?>
<?php include "../head.php"; ?>
<script language="JavaScript" type="text/javascript">
<!--
function inputCheck(frm){

	if(frm.code.value == ""){
		alert('게시판 영문명(db명)을 입력하세요.');
		frm.code.focus();
		return false;
	}

	if(frm.title.value == ""){
		alert('게시판 한글명 입력하세요.');
		frm.title.focus();
		return false;
	}
	if(frm.skin.value == ""){
		alert('게시판스킨을 선택하세요.');
		frm.skin.focus();
		return false;
	}
	<? if($site_info['mobile_use'] == "Y"){ ?>
	if(frm.skin_m.value == ""){
		alert('모바일 게시판스킨을 선택하세요.');
		frm.skin_m.focus();
		return false;
	}
	<? } ?>

	if($("#rssY").prop("checked") == true){
		if(frm.page_url.value == ""){
			alert("RSS를 사용하려면 게시판 주소(PC)를 입력하시기 바랍니다.");
			frm.page_url.focus();
			return false;
		}
	}

	if(frm.bbs_rows.value == ""){
		alert('페이지출력수 입력하세요.');
		frm.bbs_rows.focus();
		return false;
	}
	if(frm.lists.value == ""){
		alert('리스트출력수 입력하세요.');
		frm.lists.focus();
		return false;
	}

	if(frm.bbs_rows.value != "" && !check_Num(frm.bbs_rows.value)) {
		alert("페이지출력수는 숫자만 입력하세요.");
		frm.bbs_rows.focus();
		return false;
	}
	if(frm.lists.value != "" && !check_Num(frm.lists.value)) {
		alert("리스트출력수는 숫자만 입력하세요.");
		frm.lists.focus();
		return false;
	}
	if(frm.newc.value != "" && !check_Num(frm.newc.value)) {
		alert("new 기간설정은 숫자만 입력하세요.");
		frm.newc.focus();
		return false;
	}
	if(frm.hotc.value != "" && !check_Num(frm.hotc.value)) {
		alert("hot 조회수설정은 숫자만 입력하세요.");
		frm.hotc.focus();
		return false;
	}
	if(frm.subject_len.value != "" && !check_Num(frm.subject_len.value)) {
		alert("제목 글자수는 숫자만 입력하세요.");
		frm.subject_len.focus();
		return false;
	}
	if(frm.line.value != "" && !check_Num(frm.line.value)) {
		alert("줄바꿈 게시물수는 숫자만 입력하세요.");
		frm.line.focus();
		return false;
	}

}

function popCategory() {
<?
if(!strcmp($mode, "insert")) {
?>
	alert("게시판 추가 후 카테고리를 수정할 수 있습니다.");
<?
} else {
?>
	var url = "category.php?code=<?=$code?>";
	window.open(url,"BBSCategory","height=340, width=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
<?
}
?>
}

function popGrp() {
	var url = "group.php";
	window.open(url,"BBSGroup","height=250, width=350, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

function product_model(){
	var url = "product_inquiry.php";
	window.open(url,"model","height=500, width=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

function grp_reload() {
	let obj = $("select[name=grp]");
	let old_grp = obj.find("option:selected").val();
	$.get("./bbs_save.php", "mode=get_grplist", function(res) {
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
		<td valign="bottom" class="tit">게시판관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">게시판 상세기능을 설정합니다.</td>
	</tr>
</table>

<br>
<form name="frm" action="bbs_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="menucode" value="<?=$menucode?>">
<input type="hidden" name="usetype" value="<?=$bbs_info['usetype']?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td height="10" align="left" class="t_name">영문명(db명) <font color=red>*</font></td>
					<td class="t_value" colspan="3"><input name="code" type="text" size="30" value="<?=$bbs_info['code']?>" maxlength="30" <? if($mode == "update") echo "readonly"; ?> class="input"></td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">한글명 <font color=red>*</font></td>
					<td class="t_value" colspan="3"><input name="title" type="text" size="30" value="<?=$bbs_info['title']?>" class="input"></td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">게시판그룹</td>
					<td class="t_value" colspan="3">
					<?php
						$grp_list = get_grplist("bbs");
					?>
						<select name="grp" id="bbs_grp" class="select">
							<option value="">:: 게시판그룹 ::</option>
							<? if(!isset($bbs_info['grp'])) $bbs_info['grp'] =''; ?>
							<? foreach($grp_list as $grp) { ?>
							<option value="<?=$grp['idx']?>" <? if(!strcmp($bbs_info['grp'], $grp['idx'])) echo "selected" ?>><?=$grp['grpname']?></option>
							<? } ?>
						</select>
						<input type="button" value="그룹관리" class="base_btm reg" onclick="popGrp()">&nbsp;&nbsp;
						우선순위
						<select name="prior" class="select">
						<? if(!isset($bbs_info['prior'])) $bbs_info['prior'] = ''; ?>
						<? for($ii = 1; $ii < 11; $ii++) { ?>
							<option value="<?=$ii?>" <? if(!strcmp($ii, $bbs_info['prior'])) echo "selected"; ?>><?=$ii?></option>
						<? } ?>
						</select> <span class="tip_br5"></span>
						
						<div class="sub_tit_alt2"> 게시판 그룹은 게시판이 많은 경우 게시판을 그룹별로 효과적으로 관리하기 위한 기능입니다.</div>
						<span class="tip_br5"></span>
						<div class="sub_tit_alt2"> 그룹 내에서 게시판 우선순위는 작을수록 순위가 높습니다.</div>
					</td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">카테고리</td>
					<td class="t_value" colspan="3">
						<select name="bbs_cat" class="select">
							<option value="">:: 카테고리 ::</option>
							<?
							$sql = "select * from wiz_bbscat where code = '$code' order by gubun desc, prior asc, idx asc";
							$result = query($sql) or error("sql error");
							while($cat_row = sql_fetch_arr($result)) {
							?>
							<option value="<?=$cat_row['idx']?>"><?=$cat_row['catname']?></option>
							<?
							}
							?>
						</select>
						<input type="button" value="카테고리관리" class="base_btm reg" onclick="popCategory()">&nbsp;
						<? if($mode == "insert"){ ?> 게시판 추가 후 카테고리를 수정할 수 있습니다.<? } ?>
					</td>

					<!-- <td height="10" align="left" class="t_name">게시판그룹 매핑</td>
					<td class="t_value">
					<?php
					$bbs_grp2 = explode("\n", $site_info['bbs_grp']);
					$no = 0;
					for($ii = 0; $ii < count($bbs_grp2); $ii++) {
						if(!empty($bbs_grp2[$ii])) {
							$tmp_grp2 = explode("^", $bbs_grp2[$ii]);
							$grp_list2[$no]['no'] = $tmp_grp2[0];
							$grp_list2[$no]['grp'] = $tmp_grp2[1];
							$no++;
						}
					}
					?>
						<select name="grpmap" id="bbs_grp" class="select">
							<option value="">:: 게시판그룹매핑 ::</option>
							<? for($ii = 0; $ii < count($grp_list2); $ii++) { ?>
							<option value="GRP<?=$grp_list[$ii]['no']?>" <? if(!strcmp($bbs_info['grp'], $grp_list2[$ii]['no'])) echo "selected" ?>><?=$grp_list2[$ii]['grp']?></option>
							<? } ?>
						</select>
					</td> -->

				</tr>
				<!-- <?if($code="inquiry"){?>
				<tr>
					<td height="10" align="left" class="t_name">제품 모델 관리</td>
					<td class="t_value" colspan="3">
						<img src="../image/btn_product_model1.gif" align="absmiddle" style="cursor:hand" onclick="product_model()">
					</td>
				</tr>
				<?}?> -->
				<tr>
					<td width="15%" height="10" align="left" class="t_name">브라우저 타이틀</td>
					<td width="85%" class="t_value" colspan="3"><input name="browser_title" type="text" size="60" value="<?=$bbs_info['browser_title']?>" class="input"></td>
				</tr>
				<tr>
					<td width="15%" align="left" class="t_name">메타네임(Description)</td>
					<td class="t_value padd" colspan="3">
						<textarea name="searchkey_de" rows="3" cols="120" class="textarea"><?=$bbs_info['searchkey_de']?></textarea>
						<div class="sub_tit_alt2"> 네이버 검색결과에 사이트 설명으로 노출되는 부분입니다.</div>
						<div class="sub_tit_alt2"> 메타태그 설명은 30~45자를 유지하는 게 좋습니다.</div>
					</td>
				  </tr>
				  <tr>
					<td width="15%" align="left" class="t_name">메타네임(Classification)</td>
					<td class="t_value padd" colspan="3">
						<textarea name="searchkey_cl" rows="3" cols="120" class="textarea"><?=$bbs_info['searchkey_cl']?></textarea>
						<div class="sub_tit_alt2"> 사이트의 분류(카테고리)를 작성합니다.</div>
					</td>
				  </tr>

				  <tr>
					<td width="15%" align="left" class="t_name">메타네임(keywords)</td>
					<td class="t_value padd" colspan="3">
						<textarea name="searchkey" rows="3" cols="120" class="textarea"><?=$bbs_info['searchkey']?></textarea>
						<div class="sub_tit_alt_red">※ 해당 항목은 네이버 검색에만 적용되며, 구글에선 참고용으로만 활용합니다.</div>
						<div class="sub_tit_alt_red">※ 검색엔진 검색시 키워드를 활용해 검색된다고 명시되어 있으나, 검색엔진반영에는 일정 시간이 소요되며, 일부 반영이 안될 수 있습니다.</div>
						<div class="sub_tit_alt2"> 키워드나 키워드 구문을 콤마(,)로 구분하세요.</div>
						<div class="sub_tit_alt2"> 목록의 처음에 가장 중요한 단어나 구문을 넣으세요.</div>
					</td>
				  </tr>
				<tr>
					<td width="15%" height="10" align="left" class="t_name">게시판 주소(PC)</td>
					<td width="85%" class="t_value" colspan="3"><input name="page_url" type="text" size="120" value="<?=$bbs_info['page_url']?>" class="input"><div class="sub_tit_alt2"> 첫 슬래쉬를 제외한 주소를 입력하세요 ex) community/notice.php</div></td>
				</tr>
				<tr>
					<td width="15%" height="10" align="left" class="t_name">게시판 주소(MO)</td>
					<td width="85%" class="t_value" colspan="3"><input name="page_url_m" type="text" size="120" value="<?=$bbs_info['page_url_m']?>" class="input"><div class="sub_tit_alt2"> 첫 슬래쉬를 제외한 주소를 입력하세요 ex) m/sub/notice.php</div></td>
				</tr>

				<tr>
					<td height="10" align="left" class="t_name">게시판관리자</td>
					<td class="t_value" colspan="3"><input name="bbsadmin" type="text" size="60" value="<?=$bbs_info['bbsadmin']?>" class="input"><div class="sub_tit_alt2"> 일반회원 중 관리자 권한을 주고싶은 아이디를 입력하시면 됩니다.<br>아이디를 쉼표로 분리 예)admin,admin1,admin3</div></td>
				</tr>
				<tr>
					<td width="15%" height="10" align="left" class="t_name">자동 비밀글</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="checkbox" name="privacy" value="Y" <? if($bbs_info['privacy'] == "Y") echo "checked"; ?>></span>작성자와 운영자만 열람가능
					</td>
					<td width="15%" height="10" align="left" class="t_name">게시판스킨 <font color=red>*</font></td>
					<? if($site_info['mobile_use'] == "Y"){ ?>
					<td width="35%" class="t_value">
						PC Skin  <span style="display:inline-block; width:26px;">&nbsp;</span>
						<select name="skin" class="select">
							<option value="">스킨선택</option>
						<?
						$dh = opendir("../../bbs/skin");
						while(($file = readdir($dh)) !== false){
							if($file != "." && $file != ".."){
								$file_list[] = $file;
							}
						}
						sort ($file_list);
						reset ($file_list);

						for($ii=0;$ii<count($file_list);$ii++){
						?>
							<option value="<?=$file_list[$ii]?>"><?=$file_list[$ii]?></option>
						<?
						}
						?>
						</select><br />
						MOBILE Skin 
						<select name="skin_m" class="select">
							<option value="">스킨선택</option>
						<?
						$dh2 = opendir("../../bbs/skin_m");
						while(($file2 = readdir($dh2)) !== false){
							if($file2 != "." && $file2 != ".."){
								$file_list2[] = $file2;
							}
						}
						sort ($file_list2);
						reset ($file_list2);
						for($ii=0;$ii<count($file_list2);$ii++){
						?>
							<option value="<?=$file_list2[$ii]?>"><?=$file_list2[$ii]?></option>
						<?
						}
						?>
						</select>
					</td>
					<? } else { ?>
					<td width="35%" class="t_value">
						<select name="skin" class="select">
							<option value="">스킨선택</option>
						<?
						$dh = opendir("../../bbs/skin");
						while(($file = readdir($dh)) !== false){
							if($file != "." && $file != ".."){
								$file_list[] = $file;
							}
						}
						sort ($file_list);
						reset ($file_list);

						for($ii=0;$ii<count($file_list);$ii++){
						?>
							<option value="<?=$file_list[$ii]?>"><?=$file_list[$ii]?></option>
						<?
						}
						?>
						</select>
					</td>
					<? } ?>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">권한설정</td>
					<td class="t_value" colspan="3">
						<table width="98%" border="0" cellspacing="1" cellpadding="6" class="t_style">
							<tr class="t_name">
								<td width="20%" align="center" class="t_name">목록보기</td>
								<td width="20%" align="center" class="t_name">내용보기</td>
								<td width="20%" align="center" class="t_name">글쓰기</td>
								<td width="20%" align="center" class="t_name">답글쓰기</td>
								<td width="20%" align="center" class="t_name">코멘트쓰기</td>
							</tr>
							<tr>
								<td align="center" style="padding:5px 0">
									<select name="lpermi" class="select">
										<option value="">전체</option>
										<?=level_list();?>
										<option value="0">관리자</option>
									</select>
								</td>
								<td align="center">
									<select name="rpermi" class="select">
										<option value="">전체</option>
										<?=level_list();?>
										<option value="0">관리자</option>
									</select>
								</td>
								<td align="center">
									<select name="wpermi" class="select">
										<option value="">전체</option>
										<?=level_list();?>
										<? if($code == "review") { ?>
										<option value="-1">구매회원</option>
										<? } ?>
										<option value="0">관리자</option>
									</select>
								</td>
								<td align="center">
									<select name="apermi" class="select">
										<option value="">전체</option>
										<?=level_list();?>
										<option value="0">관리자</option>
									</select>
								</td>
								<td align="center">
									<select name="cpermi" class="select">
										<option value="">전체</option>
										<?=level_list();?>
										<option value="0">관리자</option>
									</select>
								</td>
							</tr>
						</table>
						<script language="javascript">
						<!--
						$(function(){
							var skin = "<?=$bbs_info['skin']?>";
							$("select[name=skin]").val(skin).attr("selected", "selected");

						  <? if($site_info['mobile_use'] == "Y"){ ?>
							var skin_m = "<?=$bbs_info['skin_m']?>";
							$("select[name=skin_m]").val(skin_m).attr("selected", "selected");
						  <? } ?>	

							var lpermi = "<?=$bbs_info['lpermi']?>";
							var rpermi = "<?=$bbs_info['rpermi']?>";
							var wpermi = "<?=$bbs_info['wpermi']?>";
							var apermi = "<?=$bbs_info['apermi']?>";
							var cpermi = "<?=$bbs_info['cpermi']?>";

							$("select[name=lpermi]").val(lpermi).attr("selected", "selected");
							$("select[name=rpermi]").val(rpermi).attr("selected", "selected");
							$("select[name=wpermi]").val(wpermi).attr("selected", "selected");
							$("select[name=apermi]").val(apermi).attr("selected", "selected");
							$("select[name=cpermi]").val(cpermi).attr("selected", "selected");

						});

						-->
						</script>
					</td>
				</tr>
				<tr>
					<td rowspan="2" height="10" align="left" class="t_name">권한이 없을경우</td>
					<td class="t_value" colspan="3">
					<span style="display:inline-block; width:150px;">경고메세지 : </span><input name="permsg" type="text" size="30" value="<?=$bbs_info['permsg']?>" class="input"><br>
					<span style="display:inline-block; width:150px;">경고후 이동페이지(PC) : </span><input name="perurl" type="text" value="<?=$bbs_info['perurl']?>" class="input" style="margin:3px 0; width:50%"><br>
					<span style="display:inline-block; width:150px;">경고후 이동페이지(mobile) : </span><input name="perurl_m" type="text" value="<?=$bbs_info['perurl_m']?>" class="input"style="width:50%">
					</td>
				</tr>
				<tr>
					<td class="t_value" colspan="3">
					<span style="vertical-align: middle"><input type="radio" name="btn_view" value="N" <? if($bbs_info['btn_view'] == "N" || $bbs_info['btn_view'] == "") echo "checked"; ?>></span> 글쓰기 버튼이 보이지 않음
					<span style="vertical-align: middle"><input type="radio" name="btn_view" value="Y" <? if($bbs_info['btn_view'] == "Y") echo "checked"; ?>></span> 글쓰기 버튼이 보이고 클릭 시 경고창
					</td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">이미지크기</td>
					<td class="t_value" colspan="3">
					목록페이지  : <input name="simgsize" type="text" size="9" value="<?=$bbs_info['simgsize']?>" class="input">픽셀 &nbsp;
					보기페이지  : <input name="mimgsize" type="text" size="9" value="<?=$bbs_info['mimgsize']?>" class="input">픽셀
					</td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">이미지파일</td>
					<td class="t_value" colspan="3">
					<span style="vertical-align: middle"><input type="checkbox" name="imgview" value="Y" <? if($bbs_info['imgview'] == "Y" || $mode=="insert") echo "checked"; ?>></span>첨부파일이 이미지인 경우 보기 페이지에서 이미지 감추기
					</td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">이미지 첨부파일 정렬</td>
					<td class="t_value" colspan="3">
					<span style="vertical-align: middle"><input type="radio" name="img_align" value="LEFT" <? if($bbs_info['img_align'] == "LEFT" || $bbs_info['img_align'] == "") echo "checked"; ?>></span> 좌측정렬
					<span style="vertical-align: middle"><input type="radio" name="img_align" value="CENTER" <? if($bbs_info['img_align'] == "CENTER") echo "checked"; ?>></span> 중앙정렬
					<span style="vertical-align: middle"><input type="radio" name="img_align" value="RIGHT" <? if($bbs_info['img_align'] == "RIGHT") echo "checked"; ?>></span> 우측정렬
					</td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">SMS 발송</td>
					<td class="t_value" colspan="3">
					<span style="vertical-align: middle"><input type="checkbox" name="sms" value="Y" <? if($bbs_info['sms'] == "Y") echo "checked"; ?>></span>
					글작성 시 관리자에게 SMS 발송 (기본설정 > 사이트 정보 > 관리자 휴대폰)
					</td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">E-MAIL 발송</td>
					<td class="t_value" colspan="3">
					<span style="vertical-align: middle"><input type="checkbox" name="email" value="Y" <? if($bbs_info['email'] == "Y") echo "checked"; ?>></span>
					글작성 시 관리자에게 EMAIL 발송 (기본설정 > 사이트 정보 > 게시판관리자 이메일) / 해당 기능은 기본내용만 발송되어집니다. (글제목 , 내용 , 작성자)
					</td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">보기 하단에 목록보기</td>
					<td class="t_value">
					<span style="vertical-align: middle"><input type="radio" name="list_view" value="Y" <? if($bbs_info['view_list'] == "Y") echo "checked"; ?>></span> 사용
					<span style="vertical-align: middle"><input type="radio" name="list_view" value="N" <? if($bbs_info['view_list'] == "N" || $bbs_info['view_list'] == "") echo "checked"; ?>></span> 미사용
					</td>
					<td height="10" align="left" class="t_name">게시판 노출여부</td>
					<td class="t_value">
					<span style="vertical-align: middle"><input type="radio" name="boardshow" value="Y" <? if($bbs_info['boardshow'] == "Y") echo "checked"; ?>></span> 노출
					<span style="vertical-align: middle"><input type="radio" name="boardshow" value="N" <? if($bbs_info['boardshow'] == "N" || $bbs_info['boardshow'] == "") echo "checked"; ?>></span> 미노출
					</td>

				</tr>
				<tr>
					<td height="10" align="left" class="t_name">스팸글체크기능</td>
					<td class="t_value">
					<span style="vertical-align: middle"><input type="radio" name="spam_check" value="Y" <? if($bbs_info['spam_check'] == "Y" || $bbs_info['spam_check'] == "") echo "checked"; ?>></span> 사용
					<span style="vertical-align: middle"><input type="radio" name="spam_check" value="N" <? if($bbs_info['spam_check'] == "N") echo "checked"; ?>></span> 미사용
					</td>
					<td align="left" class="t_name">글쓴이 형식</td>
					<td class="t_value">
						<select name="name_type" class="select">
							<option value="name">이름만 사용</option>
							<option value="nick">닉네임만 사용</option>
							<option value="icon">아이콘만 사용</option>
							<option value="iname">아이콘+이름 사용</option>
							<option value="inick">아이콘+닉네임 사용</option>
						</select>
						<script language="javascript">
						<!--
						$(function(){
							var name_type = "<?=$bbs_info['name_type']?>";
							$("select[name=name_type]").val(name_type).attr("selected", "selected");
						});
						-->
						</script>
					</td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">날짜형식(목록페이지)</td>
					<td class="t_value">
						<select name="datetype_list" class="select">
							<option value="">:: 목록페이지 :: </option>
							<option value="%y.%m.%d"><?= date('y.m.d') ?></option>
							<option value="%y/%m/%d"><?= date('y/m/d') ?></option>
							<option value="%y-%m-%d"><?= date('y-m-d') ?></option>
							<option value="%Y.%m.%d"><?= date('Y.m.d') ?></option>
							<option value="%Y/%m/%d"><?= date('Y/m/d') ?></option>
							<option value="%Y-%m-%d"><?= date('Y-m-d') ?></option>
							<option value="%Y년 %m월 %d일"><?= date('Y년 m월 d일') ?></option>
							<option value="%Y-%m-%d %H:%i"><?= date('Y-m-d H:i') ?></option>
							<option value="%Y-%m-%d %H:%i %p"><?= date('Y-m-d h:i A') ?></option>
							<option value="%Y.%m.%d %H:%i:%s"><?= date('Y.m.d H:i:s') ?></option>
						</select>
						<script language="javascript">
						<!--
						$(function(){
							var datetype_list = "<?=$bbs_info['datetype_list']?>";
							$("select[name=datetype_list]").val(datetype_list).attr("selected", "selected");
						});
						-->
						</script>
					</td>
					<td height="10" align="left" class="t_name">날짜형식(보기페이지)</td>
					<td class="t_value">
						<select name="datetype_view" class="select">
							<option value="">:: 보기페이지 :: </option>
							<option value="%y.%m.%d"><?= date('y.m.d') ?></option>
							<option value="%y/%m/%d"><?= date('y/m/d') ?></option>
							<option value="%y-%m-%d"><?= date('y-m-d') ?></option>
							<option value="%Y.%m.%d"><?= date('Y.m.d') ?></option>
							<option value="%Y/%m/%d"><?= date('Y/m/d') ?></option>
							<option value="%Y-%m-%d"><?= date('Y-m-d') ?></option>
							<option value="%Y년 %m월 %d일"><?= date('Y년 m월 d일') ?></option>
							<option value="%Y-%m-%d %H:%i"><?= date('Y-m-d H:i') ?></option>
							<option value="%Y-%m-%d %H:%i %p"><?= date('Y-m-d h:i A') ?></option>
							<option value="%Y.%m.%d %H:%i:%s"><?= date('Y.m.d H:i:s') ?></option>
						</select>
						<script language="javascript">
						<!--
						$(function(){
							var datetype_view = "<?=$bbs_info['datetype_view']?>";
							$("select[name=datetype_view]").val(datetype_view).attr("selected", "selected");
						});
						-->
						</script>
					</td>
				</tr>
				<tr>
					<td align="left" class="t_name">웹에디터</td>
					<td class="t_value">
					<span style="vertical-align: middle"><input type="radio" name="editor" value="Y" <? if($bbs_info['editor'] == "Y") echo "checked"; ?>></span>사용함
					<span style="vertical-align: middle"><input type="radio" name="editor" value="N" <? if($bbs_info['editor'] == "N" || $bbs_info['editor'] == "") echo "checked"; ?>></span>사용안함
					</td>
					<td align="left" class="t_name">답글 메일알림</td>
					<td class="t_value">
					<span style="vertical-align: middle"><input type="radio" name="remail" value="Y" <? if($bbs_info['remail'] == "Y") echo "checked"; ?>></span>허용함
					<span style="vertical-align: middle"><input type="radio" name="remail" value="N" <? if($bbs_info['remail'] == "N" || empty($bbs_info['remail'])) echo "checked"; ?>></span>허용안함
					</td>
				</tr>
				<tr>
					<td align="left" class="t_name">추천기능</td>
					<td class="t_value">
					<span style="vertical-align: middle"><input type="radio" name="recom" value="Y" <? if($bbs_info['recom'] == "Y") echo "checked"; ?>></span>사용함
					<span style="vertical-align: middle"><input type="radio" name="recom" value="N" <? if($bbs_info['recom'] == "N" || empty($bbs_info['recom'])) echo "checked"; ?>></span>사용안함
					</td>
					<td align="left" class="t_name">코멘트 허용</td>
					<td class="t_value">
					<span style="vertical-align: middle"><input type="radio" name="comment" value="Y" <? if($bbs_info['comment'] == "Y") echo "checked"; ?>></span>허용함
					<span style="vertical-align: middle"><input type="radio" name="comment" value="N" <? if($bbs_info['comment'] == "N" || empty($bbs_info['comment'])) echo "checked"; ?>></span>허용안함
					</td>
				</tr>
				<tr>
					<td align="left" class="t_name">답글 허용</td>
					<td class="t_value">
					<span style="vertical-align: middle"><input type="radio" name="reply" value="Y" <? if($bbs_info['reply'] == "Y") echo "checked"; ?>></span>허용함
					<span style="vertical-align: middle"><input type="radio" name="reply" value="N" <? if($bbs_info['reply'] == "N" || empty($bbs_info['recom'])) echo "checked"; ?>></span>허용안함
					</td>
					<td align="left" class="t_name">RSS 노출</td>
					<td class="t_value">
					<span style="vertical-align: middle"><input type="radio" name="rss" id="rssY" value="Y" <? if($bbs_info['rss'] == "Y") echo "checked"; ?>></span>허용함
					<span style="vertical-align: middle"><input type="radio" name="rss" id="rssN" value="N" <? if($bbs_info['rss'] == "N" || empty($bbs_info['rss'])) echo "checked"; ?>></span>허용안함
					</td>
				</tr>
				<tr>
					<td align="left" class="t_name">파일업로드</td>
					<td class="t_value">
						<select name="upfile" class="select">
							<option value="0">사용안함</option>
							<? for($i=1; $i<=12; $i++) { ?>
							<option value="<?=$i?>"><?=$i?>개</option>
							<? } ?>
						</select>
						<input type="checkbox" name="use_drag" value="Y"<?if($bbs_info['use_drag']=='Y') echo " checked";?>>드래그앤드롭 업로드 (모바일 및 Safari 불가)
						<script language="javascript">
						<!--
						$(function(){
							var upfile = "<?=$bbs_info['upfile']?>";
							$("select[name=upfile]").val(upfile).attr("selected", "selected");
						});
						-->
						</script>
					</td>
					<td align="left" class="t_name">동영상 <font color='red'>(avi, mp4, wmv 업로드 가능)</font></td>
					<td class="t_value">
						<select name="movie" class="select">
							<option value="0">사용안함</option>
							<option value="1">1개(파일업로드)</option>
							<option value="2">2개(링크)</option>
							<option value="3">3개(링크)</option>
						</select>
						<input type="checkbox" name="use_vimeo" value="Y"<?if($bbs_info['use_vimeo']=='Y') echo " checked";?>>Vimeo로 업로드 (1개만 가능)<br/>
						
						<script language="javascript">
						<!--
						$(function(){
							var movie = "<?=$bbs_info['movie']?>";
							$("select[name=movie]").val(movie).attr("selected", "selected");
						});
						-->
						</script>
					</td>
				</tr>
				<tr>
					<td align="left" class="t_name">페이지출력수 <font color=red>*</font></td>
					<td class="t_value"><input name="bbs_rows" type="text" value="<? if($bbs_info['bbs_rows'] == "") echo "20"; else echo $bbs_info['bbs_rows']; ?>" class="input"></td>
					<td align="left" class="t_name">리스트출력수 <font color=red>*</font></td>
					<td class="t_value"><input name="lists" type="text" value="<? if($bbs_info['lists'] == "") echo "10"; else echo $bbs_info['lists']; ?>" class="input"></td>
				</tr>
				<tr>
					<td align="left" class="t_name">new 기간설정</td>
					<td class="t_value"><input name="newc" type="text" value="<? if($bbs_info['newc'] == "") echo "2"; else echo $bbs_info['newc']; ?>" class="input"></td>
					<td align="left" class="t_name">hot 조회수설정</td>
					<td class="t_value"><input name="hotc" type="text" value="<? if($bbs_info['hotc'] == "") echo "600"; else echo $bbs_info['hotc']; ?>" class="input"></td>
				</tr>
				<?php
				if($wiz_admin['designer'] == "Y") {
				?>
				<tr>
					<td align="left" class="t_name">제목 글자수</td>
					<td class="t_value"><input name="subject_len" type="text" value="<?=$bbs_info['subject_len'];?>" class="input"></td>
					<td align="left" class="t_name">줄바꿈 게시물수</td>
					<td class="t_value"><input name="line" type="text" value="<?= $bbs_info['line']; ?>" class="input"><br>(포토갤러리 형식 스킨인 경우 적용)</td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">욕설,비방글 필터링</td>
					<td class="t_value" colspan="3">
						<span style="vertical-align: middle;"><input type="checkbox" name="abuse" value="Y" <? if($bbs_info['abuse'] == "Y") echo "checked"; ?>></span>사용함 <br />
						<textarea name="abtxt" rows="3" cols="80" class="textarea txt txtfullp2 tip_br5"><?=$bbs_info['abtxt']?></textarea>
						<div class="sub_tit_alt2"> 공백없이 단어를 입력하시고, 단어와 단어사이에는 콤마(,)로 구분하세요.</div>
					</td>
				</tr>
				<?php
				}
				?>
			</table>
			<?php
			if($wiz_admin["designer"] != "Y"){
				echo "<input type='hidden' name='subject_len' value='".$bbs_info["subject_len"]."'>";
				echo "<input type='hidden' name='line' value='".$bbs_info["line"]."'>";
				echo "<input type='hidden' name='abuse' value='".$bbs_info["abuse"]."'>";
				echo "<input type='hidden' name='abtxt' value='".$bbs_info["abtxt"]."'>";
			}
			?>
		</td>
	</tr>
</table>
<br>

<?
if(!strcmp($site_info['point_use'], "Y")) {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 포인트정보</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" align="left" class="t_name">글보기 포인트</td>
					<td width="35%" class="t_value">
						<input name="view_point" type="text" value="<? if($bbs_info['view_point'] == "" && $mode != "update") echo $site_info['view_point']; else echo $bbs_info['view_point']; ?>" class="input">
					</td>
					<td width="15%" align="left" class="t_name">글쓰기 포인트</td>
					<td width="35%" class="t_value">
						<input name="write_point" type="text" value="<? if($bbs_info['write_point'] == "" && $mode != "update") echo $site_info['write_point']; else echo $bbs_info['write_point']; ?>" class="input">
					</td>
				</tr>
				<tr>
					<td align="left" class="t_name">다운로드 포인트</td>
					<td class="t_value">
						<input name="down_point" type="text" value="<? if($bbs_info['down_point'] == "" && $mode != "update") echo $site_info['down_point']; else echo $bbs_info['down_point']; ?>" class="input">
					</td>
					<td align="left" class="t_name">코멘트 포인트</td>
					<td class="t_value">
						<input name="comment_point" type="text" value="<? if($bbs_info['comment_point'] == "" && $mode != "update") echo $site_info['comment_point']; else echo $bbs_info['comment_point']; ?>" class="input">
					</td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">추천 포인트</td>
					<td colspan="3" class="t_value">
						<input name="recom_point" type="text" value="<? if($bbs_info['recom_point'] == "" && $mode != "update") echo $site_info['recom_point']; else echo $bbs_info['recom_point']; ?>" class="input">
					</td>
				</tr>
				<tr>
					<td height="10" align="left" class="t_name">포인트가 없을경우</td>
					<td class="t_value" colspan="3">
					경고메세지 : <input name="point_msg" type="text" size="30" value="<? if($bbs_info['point_msg'] == "" && $mode != "update") echo $site_info['point_msg']; else echo $bbs_info['point_msg']?>" class="input">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?
}
?>

<br>
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
			<input type="submit" value="확인" class="base_btn reg">&nbsp;
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='bbs_list.php?page=<?=$page?>&<?=$menucodeParam?>';">
		</td>
	</tr>
</table>
</form>

<div class="helpTip">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="title">
	  </div>
	  <div class="explain">
		- 영문명은 반드시 영문으로 작성하고 변경이 불가합니다.<br>
		- 권한설정은 각 상황별 회원분류에따라 접근권한을 설정합니다.<br>
		- 욕설,비방글 설정을 통하여 글 작성시 욕설 비방글을 방지할 수 있습니다.<br>
		- 제목 글자수는 게시판 목록에서 보여지는 제목의 글자수를 지정하며 그 이상은 ... 으로 표시됩니다.<br>
		- 제목 글자수를 지정하지 않거나 0인 경우에는 글자수 노출에 제한이 없습니다.<br>
		- 줄바꿈 게시물수는 게시판 스킨이 포토갤러리 형식인 경우 한 줄에 나오는 게시물 수를 지정합니다.<br>
		- 줄바꿈 게시물수를 지정하지 않거나 0인 경우에는 적용되지 않습니다.<br>
		<? if(!strcmp($site_info['point_use'], "Y")) { ?>
		- 포인트를 차감하고 싶은 경우 숫자 앞에 - 를 붙여 주세요. (예 : -10)
		<? } ?>
	  </div>
	</div>
</div>


<? include "../foot.php"; ?>