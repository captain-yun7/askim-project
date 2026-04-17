<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/site_info.php";
include_once "../../inc/oper_info.php";
include "../head.php";

if(!isset($site_info['googleAnalyticsScript'])) {
//	query("ALTER TABLE wiz_siteinfo ADD `googleAnalyticsScript` TEXT NOT NULL AFTER `google_tracking` ", true);
}

## 아이코드 잔여코인체크
if ($site_info['sms_use'] == 'Y' && ($site_info['sms_token'] || ($site_info['sms_id'] && $site_info['sms_pw']))) {
	
	$icodeinfo = get_icode_info($site_info['sms_token']);
	
	if($icodeinfo['plan']) {

		if ($icodeinfo['plan'] == 'A') {
			$sms_coin = '<span id="sms_coin">충전식' . ' / ' . number_format($icodeinfo['coin']).' 원 ';
			$sms_coin .= '<a href="https://icodekorea.com/company/credit_card_input.php?icode_id='.$site_info['sms_id'].'&icode_passwd='.$site_info['sms_pw'].'" target="_blank"><input type="button" value="충전하기" class="base_btn4 icode"></a></span>';
			$sms_join  = '';

		} else if ($icodeinfo['plan'] == 'C') {
			$sms_coin = '<span id="sms_coin">정액제' . ' / ' . number_format($icodeinfo['coin']).' 원</span><span class="sp_tab"></span>';
			$sms_join  = '';

		} else {
			$sms_coin  = '';
			$sms_join  = '';
		}

	} else {

		// 충전식 형태로만 할경우 밖으로 빼냄
		$sms_coin = '<span id="sms_coin">충전식' . ' / ' . number_format($icodeinfo['coin']).' 원 ';
		$sms_coin .= '<a href="https://icodekorea.com/company/credit_card_input.php?icode_id='.$site_info['sms_id'].'&icode_passwd='.$site_info['sms_pw'].'" target="_blank"><input type="button" value="충전하기" class="base_btn4 icode"></a></span>';
		$sms_join  = '';

	}

} else {
	$sms_coin = '';
	$sms_join = '<a href="https://www.icodekorea.com/?ctl=user_sign_on&act=agree&type=A&sellid=" target="_blank"><input type="button" value="회원가입" class="base_btn4 icode"></a>';
}

$googleAnalyticsScript = str_replace("\\", "", $site_info['googleAnalyticsScript']);
?>

<script language="javascript">
<!--
function inputCheck(frm){
	if(frm.designer_id.value == ""){
		alert("디자이너 아이디를 입력하세요.");
		frm.designer_id.focus();
		return false;
	}
	/*if(frm.designer_pw.value == ""){
		alert("디자이너 비밀번호를 입력하세요.");
		frm.designer_pw.focus();
		return false;
	}*/
}

// 아이디 중복확인
function idCheck(){
	var id = document.frm.designer_id.value;
	var url = "../member/id_check.php?name=designer_id&id=" + id;
	window.open(url, "idCheck", "width=450, height=200, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=150, top=150");
}


-->
</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">기본설정</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">사이트운영에 필요한 기본정보를 설정합니다.</td>
	</tr>
</table>
<br>
<form name="frm" action="basic_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)">
<input type="hidden" name="tmp">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<!-- <table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td align="right">
						<img src="../image/btn_manual.gif" style="cursor:hand" onClick="window.open('http://anywiz.co.kr/man/wizhome/index.html','','');">
						<img src="../image/btn_dbdesc.gif" style="cursor:hand" onClick="window.open('/twcenter/desc_table.php?mode=print','','');">
						<img src="../image/btn_filedesc.gif" style="cursor:hand" onClick="window.open('/twcenter/desc_file.php?mode=print','','');">
					</td>
				</tr>
			</table>

			<br> -->
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 최종 업데이트 날짜</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">최종 업데이트 날짜</td>
					<td width="85%" class="t_value"><?php echo $site_info['up_date'] ?></td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub">
						<img src="../image/ics_tit.gif" align="absmiddle"> 라이센스키 등록&nbsp; 
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 라이센스키 를 등록하지 않을경우 데모 설치 2주일 후 부터 관리자 기능을 사용할 수 없습니다.</li>
									<li>- 도메인이 변경될 경우 라이센스 키를 다시 발급받아야 합니다.</li>
									<li>- 도메인이 여러개인경우 한라인에 하나씩 추가할 수 있습니다.</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">라이센스키 <a href="http://www.anywiz.co.kr" target="_blank"><input type="button" value="라이센스키 구입" class="base_btn_s2 gray" style="vertical-align:middle;"></a></td>
					<td width="85%" class="t_value">
						<textarea name="site_key" rows="2" cols="50" class="textarea"><?php echo $site_info['site_key'] ?></textarea>
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 관리자 로고 및 타이틀</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">관리자 로고</td>
					<td width="85%" class="t_value">
						<?php if(is_file(WIZHOME_PATH."/data/config/twcenter_logo.gif")) echo "<br><img src='/twcenter/data/config/twcenter_logo.gif'><br><br>"; ?>
						<div class="filebox preview-image">
							<input class="input upload-name" value="파일선택" disabled="disabled">
							<label for="input-file">파일 업로드</label>
							<input type="file" name="twcenter_logo" id="input-file" class="upload-hidden">
						</div>
					</td>
				</tr>
				<tr>
					<td class="t_name">관리자 타이틀</td>
					<td class="t_value"><input name="admin_title" type="text" value="<?php echo $site_info['admin_title'] ?>" size="80" class="input"></td>
				</tr>
				<tr>
					<td class="t_name">관리자 카피라잇</td>
					<td class="t_value padd"><textarea name="admin_copyright" rows="3" cols="80" class="textarea"><?php echo $site_info['admin_copyright'] ?></textarea></td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub">
						<img src="../image/ics_tit.gif" align="absmiddle"> 디자이너 아이디/비밀번호&nbsp;
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 디자이너 아이디/비밀번호로 로그인시에만 환경설정 메뉴가 나타나며 일반관리자는 보이지 않습니다.</li>
									<li>- 사이트 제작 완료후 관리자 비번이 변경되었더라도 디자이너 정보로 접속하므로 관리자 접속에 편리합니다.</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">디자이너 아이디</td>
					<td width="85%" class="t_value"><input name="designer_id" type="text" value="<?php echo $site_info['designer_id'] ?>" maxlength="20" class="input" readonly> <input type="button" value="중복체크" class="base_btn2 gray" onclick="idCheck()"></td>
				</tr>
				<tr>
					<td class="t_name">디자이너 비밀번호</td>
					<td class="t_value"><input name="designer_pw" type="text" value="" maxlength="20" class="input"></td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub">
						<img src="../image/ics_tit.gif" align="absmiddle"> 메뉴 사용여부
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 사용여부에 따라 메뉴을 보이거나 숨길 수 있습니다</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">메뉴 선택</td>
					<td width="85%" class="t_value">
						<table cellpadding="0" cellspacing="0">
							<tr>
							<?
								$ii=1;
								foreach($admin_menu as $menu=>$str) { 
							?>
								<td align="left" width="120"><input type="checkbox" name="menu_use[]" value="<?=$menu?>" <?php if($menu_arr[$menu]==true) echo "checked";?>> <?=$str?></td>
							<?
									if($ii%6 == 0) echo "</tr><tr>";
									$ii++;
								}
							?>
							</tr>
	
						</table>
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%">
						<img src="../image/ics_tit.gif" align="absmiddle"> 게시판추가 사용여부
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 게시판추가를 사용하지 않는 경우 <font color="#ED253C"><strong>"게시판관리 > 게시판목록"</strong></font> 에서 게시판을 추가할 수 없습니다.</li>
								</ul>
							</div>
						</div>
					</td>
					<td class="tit_sub">
						<img src="../image/ics_tit.gif"> 견적서 사용여부
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- <font color="#ED253C"><strong>"견적서"</strong></font> 를 사용하는 경우 장바구니에서 견적서를 출력할 수 있습니다.</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="addbbs_use" value="Y" <?php if(!strcmp($site_info['addbbs_use'], "Y") || empty($site_info['addbbs_use'])) echo "checked" ?>></span> 사용
						<span style="vertical-align: middle"><input type="radio" name="addbbs_use" value="N" <?php if(!strcmp($site_info['addbbs_use'], "N")) echo "checked" ?>></span> 사용안함
					</td>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="estimate_use" value="Y" <?php if(!strcmp($site_info['estimate_use'], "Y") || empty($site_info['estimate_use'])) echo "checked" ?>></span> 사용
						<span style="vertical-align: middle"><input type="radio" name="estimate_use" value="N" <?php if(!strcmp($site_info['estimate_use'], "N")) echo "checked" ?>></span> 사용안함
					</td>
				</tr>
			</table>

			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="">
						<img src="../image/ics_tit.gif" align="absmiddle"> 게시판 첨부파일 설정
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">파일 확장자 설정</td>
					<td width="35%" class="t_value padd">
						<input type="radio" name="ext_config" value="U" <?php if($site_info['ext_config'] == "U") echo "checked";?>> 허용 확장자 설정 &nbsp;
						<input type="radio" name="ext_config" value="D" <?php if($site_info['ext_config'] == "D") echo "checked";?>> 차단 확장자 설정<br>
						<textarea name="use_ext" rows="7" style="width:95%" class="textarea"><?php echo $site_info['use_ext']?></textarea>
					</td>
					<td width="50%" class="t_value padd">
								<ul>
									<li>- 엔터로 구분해주세요</li>
									<li>- 허용 확장자를 설정하면 해당 확장자 파일만 업로드 됩니다.</li>
									<li>- 차단 확장자를 설정하면 해당 확장자 파일 업로드가 차단 됩니다.</li>
								</ul>
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%"><img src="../image/ics_tit.gif" align="absmiddle"> 쪽지 사용</td>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 포인트 사용</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="msg_use" value="Y" <? if($site_info['msg_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="msg_use" value="N" <? if($site_info['msg_use'] == "N") echo "checked"; ?>></span>사용안함&nbsp;&nbsp;<span style="vertical-align: middle"><input type="checkbox" name="msg_editor_use" value="Y"  <? if($site_info['msg_editor_use'] == "Y") echo "checked"; ?>></span>에디터 사용
					</td>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value" colspan=3>
						<span style="vertical-align: middle"><input type="radio" name="point_use" value="Y" <? if($site_info['point_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="point_use" value="N" <? if($site_info['point_use'] == "N") echo "checked"; ?>></span>사용안함
					</td>
				</tr>
			</table>
			
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="100%"><img src="../image/ics_tit.gif" align="absmiddle"> 이벤트 할인쿠폰 사용</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="85%" class="t_value" colspan=3>
						<span style="vertical-align: middle"><input type="radio" name="event_coupon_use" value="Y" <? if($site_info['event_coupon_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="event_coupon_use" value="N" <? if($site_info['event_coupon_use'] == "N") echo "checked"; ?>></span>사용안함
					</td>
				</tr>
			</table>

			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%"><img src="../image/ics_tit.gif" align="absmiddle"> 미니홈피 사용</td>
					<td class="tit_sub">
						<img src="../image/ics_tit.gif" align="absmiddle"> SSL 사용
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- SSL을 사용하는 경우 기본적으로 서버에 SSL이 적용이 되어있어야합니다.</li>
									<li>- 확인 방법 https://해당도메인 ex) https://demo.web2002.co.kr</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value" colspan=3>
						<span style="vertical-align: middle"><input type="radio" name="mini_use" value="Y" <? if($site_info['mini_use'] == "Y") echo "checked"; ?> disabled></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="mini_use" value="N" <? if($site_info['mini_use'] == "N") echo "checked"; ?> disabled></span>사용안함
					</td>
					<td width="15%" class="t_name">사용여부 / 포트번호</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="ssl_use" value="Y" <? if($site_info['ssl_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="ssl_use" value="N" <? if($site_info['ssl_use'] == "N") echo "checked"; ?>></span>사용안함&nbsp;/&nbsp;<input type="text" name="ssl_port" value="<?php echo $site_info['ssl_port']?>" class="input">
					</td>

				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 모바일웹 / APP 제작여부</td>
					<td class="tit_sub" width="50%"><img src="../image/ics_tit.gif" align="absmiddle"> 쇼핑몰 관리 > 모바일 진열여부기능 사용</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">모바일웹 / APP (PUSH발송)</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="mobile_use" value="Y" <? if($site_info['mobile_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="mobile_use" value="N" <? if($site_info['mobile_use'] == "N") echo "checked"; ?>></span>사용안함&nbsp;/&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="app_use" value="Y" <? if($site_info['app_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="app_use" value="N" <? if($site_info['app_use'] == "N") echo "checked"; ?>></span>사용안함

					</td>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="mobile_show_use" value="Y" <? if($site_info['mobile_show_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="mobile_show_use" value="N" <? if($site_info['mobile_show_use'] == "N") echo "checked"; ?>></span>사용안함
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>

			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%"><img src="../image/ics_tit.gif"> 쇼핑몰 상세페이지 설정</td>
					<td class="tit_sub"><img src="../image/ics_tit.gif"> 쇼핑몰 배송업체 선택방법</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">SHOP상세페이지 설정</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="viewType" value="M" <?php if($site_info['viewType'] == "M") echo "checked"; ?>></span>상품옵션 조합형
						<span style="vertical-align: middle"><input type="radio" name="viewType" value="I" <?php if($site_info['viewType'] == "I") echo "checked"; ?>></span>상품옵션 개별형
					</td>
					<td width="15%" class="t_name">배송방법 결정</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="deliveryType" value="O" <?php if($oper_info['deliveryType'] == "O") echo "checked"; ?>></span>하나의 배송업체만 이용
						<span style="vertical-align: middle"><input type="radio" name="deliveryType" value="P" <?php if($oper_info['deliveryType'] == "P") echo "checked"; ?>></span>상품별 배송업체 선택
						<span style="vertical-align: middle"><input type="radio" name="deliveryType" value="M" <?php if($oper_info['deliveryType'] == "M") echo "checked"; ?>></span>주문내역에서 주문별 배송업체 선택
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%">
						<img src="../image/ics_tit.gif" align="absmiddle"> Browscap 사용
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- PHP 5.3.0 버전 이후 제공되는 모듈입니다.</li>
									<li>- 상기버전과 사용함체크시 /twcenter/data/cache폴더내에 browscap.ini 및 cache.php파일이 생성됩니다.</li>
								</ul>
							</div>
						</div>
					</td>
					<td class="tit_sub" width="50%">
						<img src="../image/ics_tit.gif" align="absmiddle"> 아이피분석키
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 인터넷진흥원(KISA)에서 Key를 발급받아 등록하시기 바랍니다. <a href="http://whois.kisa.or.kr/kor/whois/openAPI_KeyCre.jsp" target="_blank">[키발급]</a></li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="browscap" value="Y" <?php if($site_info['browscap'] == "Y") echo "checked"; ?>></span>사용함
						<span style="vertical-align: middle"><input type="radio" name="browscap" value="N" <?php if($site_info['browscap'] == "N") echo "checked"; ?>></span>사용안함
					</td>
					<td width="15%" class="t_name">아이피분석키 등록</td>
					<td width="35%" class="t_value">
						<input type="text" name="ipkisakey" value="<?php echo $site_info['ipkisakey'] ?>" class="input" size="50">&nbsp;
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="100%"><img src="../image/ics_tit.gif" align="absmiddle"> 자동로그인 사용</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="85%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="autologin_use" value="Y" <? if($site_info['autologin_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="autologin_use" value="N" <? if($site_info['autologin_use'] == "N") echo "checked"; ?>></span>사용안함
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%">
						<img src="../image/ics_tit.gif" align="absmiddle"> 로그인 중복체크
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 로그인 중복체크는 개발요청이 있을때 사용을 권장드립니다.</li>
									<li>- 해당메뉴는 DB wiz_session에 세션이 담기게 되어있습니다. 이에 공간사용량의 부담율을 높일수있습니다.</li>
									<li>- 다른아이피 적용체크시 관리자 아이피대역과 다른대역에서만 적용됩니다.</li>
								</ul>
							</div>
						</div>
					</td>
					<td class="tit_sub" width="50%">
						<img src="../image/ics_tit.gif" align="absmiddle"> 로그인 접속제한
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 접속제한수만큼 로그인오류시 접속제한시간동안 로그인정보가 정상이라 하더라도 접근이 불가능합니다.</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="sesschk" value="Y" <?php if($site_info['sesschk'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="sesschk" value="N" <?php if($site_info['sesschk'] == "N") echo "checked"; ?>></span>사용안함
					</td>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="login_limit_use" value="Y" <?php if($site_info['login_limit_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="login_limit_use" value="N" <?php if($site_info['login_limit_use'] == "N") echo "checked"; ?>></span>사용안함
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">적용범위</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="coverage" value="1" <?php if($site_info['coverage'] == "1") echo "checked"; ?>></span>다른아이피 적용&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="coverage" value="2" <?php if($site_info['coverage'] == "2") echo "checked"; ?>></span>동일아이피 적용
					</td>
					<td width="15%" class="t_name">접속제한수 / 접속제한시간(초)</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="text" name="login_limit_count" value="<?php echo $site_info['login_limit_count']?>" class="input" size="5"></span> 회 &nbsp;/&nbsp;
						<span style="vertical-align: middle"><input type="text" name="login_limit_time" value="<?php echo $site_info['login_limit_time']?>" class="input" size="10"> 초</span>
					</td>

				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%"><img src="../image/ics_tit.gif" align="absmiddle"> 로그인 아이디 차단관리</td>
					<td class="tit_sub" width="50%"><img src="../image/ics_tit.gif" align="absmiddle"> 로그인 비밀번호 변경관리</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="checkbox" name="denyiduse" value="Y" <?php if($site_info['denyiduse'] == "Y") echo "checked";?>> 사용함&nbsp;
					</td>
					<td width="15%" class="t_name">사용여부</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="login_pw_chg" value="Y" <?php if($site_info['login_pw_chg'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="login_pw_chg" value="N" <?php if($site_info['login_pw_chg'] == "N") echo "checked"; ?>></span>사용안함
					</td>
				</tr>




				<tr>
					<td width="15%" class="t_name">아이디 차단</td>
					<td width="35%" class="t_value padd">
						<textarea name="denyid" rows="3" style="width:97%" class="textarea"><?php echo $site_info['denyid']?></textarea>
						<br>
						* 엔터로 구분해주세요.
					</td>
					<td width="15%" class="t_name">로그인 비밀번호 변경(일)</td>
					<td width="35%" class="t_value">
						<span style="vertical-align: middle"><input type="text" name="login_pwchg_day" value="<?php echo $site_info['login_pwchg_day']?>" class="input" size="10"> 일</span>
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%">
						<img src="../image/ics_tit.gif" align="absmiddle"> 세션 자동삭제
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 접속세션로그가 많은경우 로그인시 로딩속도가 지연되는것을 1차적으로 방지합니다.</li>
									<li>- 현재시간을 기준으로 적용시간 전 까지의 세션을 삭제합니다.</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="85%" class="t_value">
						<span style="vertical-align: middle"><input type="radio" name="sess_del_use" value="Y" <?php if($site_info['sess_del_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="sess_del_use" value="N" <?php if($site_info['sess_del_use'] == "N") echo "checked"; ?>></span>사용안함
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">적용시간</td>
					<td width="85%" class="t_value">
						<span style="vertical-align: middle"><input type="text" name="sess_del_time" value="<?php echo $site_info['sess_del_time']?>" class="input" size="5"></span> 시간
					</td>

				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%"><img src="../image/ics_tit.gif" align="absmiddle"> Google Analytics 사용</td>
					<td class="tit_sub"></td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사용여부</td>
					<td width="85%" class="t_value" colspan="3">
						<span style="vertical-align: middle"><input type="radio" name="google_an_use" value="Y" <?php if($site_info['google_an_use'] == "Y") echo "checked"; ?>></span>사용함&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="google_an_use" value="N" <?php if($site_info['google_an_use'] == "N") echo "checked"; ?>></span>사용안함
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">방문자분석 스크립트</td>
					<td width="85%" class="t_value padd" colspan="3">
						<textarea name="googleAnalyticsScript" rows="7" style="width:98%" class="textarea"><?php echo $googleAnalyticsScript ?></textarea>
					</td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%"><img src="../image/ics_tit.gif" align="absmiddle"> Google reCAPTCHA</td>
					<td class="tit_sub"></td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">사이트키</td>
					<td width="35%" class="t_value"><input type="text" name="recaptcha_sitekey" value="<?=$site_info['recaptcha_sitekey']?>" class="input" size="50"></td>
					<td width="15%" class="t_name">시크릿키</td>
					<td width="35%" class="t_value"><input type="text" name="recaptcha_secretkey" value="<?=$site_info['recaptcha_secretkey']?>" class="input" size="50"></td>
				</tr>
			</table>
			<div class="helpTip2"></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub" width="50%">
						<img src="../image/ics_tit.gif" align="absmiddle"> 접근아이피 차단
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 엔터로 구분해주세요</li>
									<li>- 단일아이피 및 아이피대역을 등록할수있습니다. 아이피대역은 123.456.789.* 로 등록해주세요.</li>
								</ul>
							</div>
						</div>
					</td>
					<td class="tit_sub">
						<img src="../image/ics_tit.gif" align="absmiddle"> 접근아이피 허용
						<div class="advice">
							<button type="button" class="btn-advice">?</button>
							<div class="content">
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
										<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
									</tr>
								</table>
								<ul>
									<li>- 아이피차단은 웹상에서 아이피허용은 관리자모드에서 기본적으로 운영되도록 설정이 되어있습니다.</li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">차단아이피/대역</td>
					<td width="35%" class="t_value padd">
						<input type="checkbox" name="denyipuse" value="Y" <?php if($site_info['denyipuse'] == "Y") echo "checked";?>> 사용함
						<textarea name="denyip" rows="7" style="width:95%" class="textarea"><?php echo $site_info['denyip']?></textarea>
					</td>
					<td width="15%" class="t_name">허용아이피/대역</td>
					<td width="35%" class="t_value padd">
						<input type="checkbox" name="permitipuse" value="Y" <?php if($site_info['permitipuse'] == "Y") echo "checked";?>> 사용함
						<textarea name="permitip" rows="7" style="width:95%" class="textarea"><?php echo $site_info['permitip']?></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table width="100%" align=center border="0" cellpadding=3 cellspacing=1>
	<tr>
		<td align="center">
			<input type="submit" value="확 인" class="base_btn reg">
		</td>
	</tr>
</table>
</form>
<script>
	(function($){
		$(document).on('click','.btn-advice', function(){
			$(this).parent().addClass('show')
		});
		$(document).on('click','.advice .btn-close', function(){
			$(this).parents().removeClass('show');
		});
	})(jQuery);
</script>

<?php include "../foot.php"; ?>
