<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/api_info.php"; ?>
<? include "../head.php"; ?>
<?
if(!empty($mode) || $api_cnt > 0) $mode = "update"; else $mode = "insert";
?>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">외부 API 연동</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">SNS 로그인연동을 위한 각 키값을 설정합니다. 각 사이트 개발자센터에서 해당키값을 발급받으시기 바랍니다.</td>
	</tr>
</table>

<br>
<form name="frm" action="basic_api_save.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="<?=$mode?>">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<?
			/*
			작업자	: 임서연
			작업일시	: 2020-03-05
			작업내용	: SNS로그인/회원가입 시 네이버,카카오톡만 이용하기 위해 주석(수정 반영 작업)
			*/
			?>
			<!--
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 페이스북 로그인 설정</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">App ID</td>
					<td width="85%" class="t_value">
						<input name="facebook_appid" type="text" value="<?php echo $api_info['facebook_appid'] ?>" size="65" class="input">
					</td>
				</tr>
				<tr>
					<td class="t_name">App Secret</td>
					<td class="t_value">
						<input name="facebook_appsecret" type="text" value="<?php echo $api_info['facebook_appsecret'] ?>" size="65" class="input">
					</td>
				</tr>
				<tr>
					<td class="t_name">Redirect URL</td>
					<td class="t_value">
						<input name="facebook_redirect_url" type="text" value="<?php echo $api_info['facebook_redirect_url'] ?>" size="65" class="input">
					</td>
				</tr>
			</table>
			<div class="helpTip2">
			  <div class="content">
				<div class="title">
				  <strong>[페이스북 App ID 및 App Secret 설정방법]</strong>
				</div>
				<div class="explain">
				  예시 1) http://<?php echo $_SERVER["HTTP_HOST"] ?><br>
				  예시 2) http://<?php echo $_SERVER["HTTP_HOST"] ?>/comm/API/facebook.callback.php<br>
				  - <a href="https://developers.facebook.com/" target="_blank"><font color="#2DA7FE">https://developers.facebook.com/</font></a></font> 접속 후 로그인<br>
				  - 우측 상단에 내앱 > 새앱추가<br>
				  - 표시이름 : 업체명 입력후 앱ID만들기 선택<br>
				  - 좌측 메뉴에 제품 추가 > Facebook 로그인 선택<br>
				  - 웹 선택후 사이트 URL 입력 ("예시 1" 참조). 후 Save클릭.<br>
				  - 좌측 메뉴에 추가된 "Facebook 로그인" 클릭 > 콜백 URL : http://<?php echo $_SERVER["HTTP_HOST"] ?>/comm/API/facebook.callback.php ("예시 2" 참조)<br>
				  - 좌측 메뉴에 앱 검수 > 개발모드에서 공개모드로 변경<br>
				  - 좌측 메뉴에 대시보드에서 KEY 확인가능

				</div>

			  </div>
			</div>
			-->

			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 카카오톡 로그인 설정</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">REST API Key (SNS로그인)</td>
					<td width="85%" class="t_value">
						<input name="kakao_api_key" type="text" value="<?php echo $api_info['kakao_api_key'] ?>" size="65" class="input">
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">Javascript Key (공유하기)</td>
					<td width="85%" class="t_value">
						<input name="kakao_api_key2" type="text" value="<?php echo $api_info['kakao_api_key2'] ?>" size="65" class="input">
					</td>
				</tr>
				<tr>
					<td class="t_name">Redirect URL</td>
					<td class="t_value">
						<input name="kakao_redirect_url" type="text" value="<?php echo $api_info['kakao_redirect_url'] ?>" size="65" class="input">
					</td>
				</tr>
			</table>
			<div class="helpTip2">
			  <div class="content">
				<div class="title">
				  <strong>[카카오톡 Javascript key 설정방법]</strong>
				</div>
				<div class="explain">
				  예시 1) http://<?php echo $_SERVER["HTTP_HOST"] ?><br>
				  예시 2) http://<?php echo $_SERVER["HTTP_HOST"] ?>/comm/API/kakao.callback.php<br>
				  - <a href="https://developers.kakao.com/" target="_blank"><font color="#2DA7FE">https://developers.kakao.com</font></a></font> 접속 후 로그인<br>
				  - 우측 상단 로그인한 계정의 이름 > 내 애플리케이션<br>
				  - 좌측 메뉴에서 앱 만들기 클릭.<br>
				  - 앱이름 : 업체명 입력, 아이콘은 추후에도 설정가능.<br>
				  - 좌측 메뉴에서 일반 > 플랫폼 추가 > 웹 선택 > 도메인 입력 ("예시 1" 참조)<br>
				  - Redirect Path 항목에 "/comm/API/kakao.callback.php" 입력<br>
				  - 좌측 메뉴에서 개요 > 카카오 계정 로그인 옆에 사용자관리 클릭 > 사용 > 수집목적(필수항목)에 "웹 회원가입" 입력 후 저장.<br>
				  - 개요 > 앱정보 클릭해서 KEY 확인.
				</div>

			  </div>
			</div>
		<!--
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 트위터 로그인 설정</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">Consumer Key</td>
					<td width="85%" class="t_value">
						<input name="twitter_consumer_key" type="text" value="<?php echo $api_info['twitter_consumer_key'] ?>" size="65" class="input">
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">Consumer Secret</td>
					<td width="85%" class="t_value">
						<input name="twitter_consumer_secret" type="text" value="<?php echo $api_info['twitter_consumer_secret'] ?>" size="75" class="input">
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">Access Token</td>
					<td width="85%" class="t_value">
						<input name="twitter_access_token" type="text" value="<?php echo $api_info['twitter_access_token'] ?>" size="75" class="input">
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">Access Token Secret</td>
					<td width="85%" class="t_value">
						<input name="twitter_access_token_secret" type="text" value="<?php echo $api_info['twitter_access_token_secret'] ?>" size="75" class="input">
					</td>
				</tr>
				<tr>
					<td class="t_name">Redirect URL</td>
					<td class="t_value">
						<input name="twitter_redirect_url" type="text" value="<?php echo $api_info['twitter_redirect_url'] ?>" size="65" class="input">
					</td>
				</tr>
			</table>
			<div class="helpTip2">
			  <div class="content">
				<div class="title">
				  <strong>[트위터 설정방법]</strong>
				</div>
				<div class="explain">
				  예시 1) http://<?php echo $_SERVER["HTTP_HOST"] ?><br>
				  예시 2) http://<?php echo $_SERVER["HTTP_HOST"] ?>/comm/API/twitter.callback.php<br>
				  - <a href="https://apps.twitter.com/" target="_blank"><font color="#2DA7FE">https://apps.twitter.com</font></a></font> 접속 후<br>
				  - 상단에 My apps > Create New App 클릭<br>
				  - 회사명, 설명(최소 10글자 이상), 웹사이트 ("예시 1" 참조), 콜백URL("예시 2" 참조) 순으로 작성.<br>
				  - 계정이 만들어졌다면 Keys and Access Tokens 탭 > 하단에 Token Actions의 버튼을 눌러서 Access token 및 key를 생성.<br>
				  - Keys and Access Tokens에서 KEY 확인. Twitter Consumer (key,secret), Twitter Access (token,secret) 총 4개<br>
				</div>

			  </div>
			</div>
			-->
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 네이버 로그인 설정</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">Client ID</td>
					<td width="85%" class="t_value">
						<input name="naver_client_id" type="text" value="<?php echo $api_info['naver_client_id'] ?>" size="65" class="input"> 
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">Client Secret</td>
					<td width="85%" class="t_value">
						<input name="naver_client_secret" type="text" value="<?php echo $api_info['naver_client_secret'] ?>" size="65" class="input">
					</td>
				</tr>
				<tr>
					<td class="t_name">Redirect URL</td>
					<td class="t_value">
						<input name="naver_redirect_url" type="text" value="<?php echo $api_info['naver_redirect_url'] ?>" size="65" class="input">
					</td>
				</tr>
			</table>
			<div class="helpTip2">
			  <div class="content">
				<div class="title">
				  <strong>[네이버 Client ID 및 Secret 설정방법]</strong>
				</div>
				<div class="explain">
				  예시 1) http://<?php echo $_SERVER["HTTP_HOST"] ?><br>
				  예시 2) http://<?php echo $_SERVER["HTTP_HOST"] ?>/comm/API/naver.callback.php<br>
				  - <a href="https://developers.naver.com/main/" target="_blank"><font color="#2DA7FE">https://developers.naver.com/main</font></a></font> 접속 후 로그인<br>
				  - 상단 메뉴 Application > 애플리케이션등록 선택<br>
				  - 애플리케이션이름 : 업체명 입력<br>
				  - 사용API : 네아로 선택 후 이메일 , 회원이름 체크박스 선택<br>
				  - 로그인 오픈 API 서비스 환경 : PC웹 선택 , 모바일 사이트 있는경우 Mobile웹 추가선택<br>
				  - 서비스 URL예시 : 도메인명 입력  ("예시 1" 참조)<br>
				  - Callback URl : 도메인명/comm/API/naver.callback.php ("예시 2" 참조)<br>
				  - 내 애플리케이션에서 KEY확인 가능
				</div>

			  </div>
			</div>
		
			
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 구글 로그인 설정</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">Client ID</td>
					<td width="85%" class="t_value">
						<input name="google_client_id" type="text" value="<?php echo $api_info['google_client_id'] ?>" size="65" class="input"> 
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">Client Secret</td>
					<td width="85%" class="t_value">
						<input name="google_client_secret" type="text" value="<?php echo $api_info['google_client_secret'] ?>" size="65" class="input">
					</td>
				</tr>
				<!--
				<tr>
					<td class="t_name">Redirect URL</td>
					<td class="t_value">
						<input name="google_redirect_url" type="text" value="<?php echo $api_info['google_redirect_url'] ?>" size="65" class="input">
					</td>
				</tr>
				-->
			</table>
			<div class="helpTip2">
			  <div class="content">
				<div class="title">
				  <strong>[구글 Client ID 및 Secret 설정방법]</strong>
				</div>
				<div class="explain">
<!-- 				  예시 2) http://<?php echo $_SERVER["HTTP_HOST"] ?>/comm/API/google.callback.php<br> -->
				  1. <a href="https://console.developers.google.com/" target="_blank"><font color="#2DA7FE">https://console.developers.google.com/</font></a></font> 접속 후 로그인<br>
				  2. 대시보드 > 프로젝트 만들기 > 프로젝트명 : 회사명<br>
				  3. 좌측상단 : 프로젝트 > 생성한 프로젝트명 클릭<br>
				  4. 좌측메뉴 : API 및 서비스 > 라이브러리 클릭 > 소셜 - Google People API 클릭 >  Google People API 사용 클릭  <br>
				  5. 좌측 상단 햄버거 메뉴 : API 및 서비스 > 사용자 인증 정보 > 우측 동의 화면 구성 버튼 클릭 <br>
				&nbsp;&nbsp;ⓐ User Type : 외부<br>
				&nbsp;&nbsp;ⓑ 앱 정보 : 필수입력사항 입력 <br>
				&nbsp;&nbsp;ⓒ 범위 > 범위 추가 또는 삭제 > 선택한 범위 업데이트 창에서 people API 검색 후 정확한 생년월일 확인 및 다운로드 체크박스 후 저장 <br>
				  * 저장시 게시상태는 테스트로 저장되며, 테스트계정 추가 하여 테스트, 테스트 완료 후 앱 게시로 전환<br>
				  6. 좌측메뉴 : API 및 서비스 > 사용자 인증정보 > 상단 : 사용자 인증정보 만들기 > OAuth 클라이언트 ID 만들기  <br>
				&nbsp;&nbsp;ⓐ 애플리케이션 유형 : 웹 애플리케이션 <br>
				&nbsp;&nbsp;ⓑ 이름 : 회사명  <br>
				&nbsp;&nbsp;ⓒ 승인된 자바스크립트 원본 URI : 사용할 사이트의 URL 입력 (예시) http://<?php echo $_SERVER["HTTP_HOST"] ?>)<br>
					* 앱 게시로 전환시 입력된 URL은 SSL이 적용되어 있어야합니다.<br>
<!-- 				&nbsp;&nbsp;ⓓ 승인된 리디렉션 URL : 도메인명/comm/API/google.callback.php(예시 2) > 저장 <br> -->
				  7. API 및 서비스 > 사용자 인증정보 > OAuth 2.0 클라이언트 ID > 수정 페이지에서 클라이언트 ID, 클라이언트 보안 비밀번호 확인 가능
				</div>

			  </div>
			</div>

			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 다음지도 키값 설정</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
					<td width="15%" class="t_name">JavaScript 키</td>
					<td width="85%" class="t_value">
						<input name="daum_map_key" type="text" value="<?php echo $api_info['daum_map_key'] ?>" size="85" class="input">
					</td>
				</tr>
				<tr>
					<td width="15%" class="t_name">REST API 키</td>
					<td width="85%" class="t_value">
						<input name="daum_map_api_key" type="text" value="<?php echo $api_info['daum_map_api_key'] ?>" size="85" class="input">
					</td>
				</tr>
			</table>
			<div class="helpTip2">
			  <div class="content">
				<div class="title">
				  <strong>[다음맵 API 설정방법]</strong>
				</div>
				<div class="explain">
				  키발급) <a href="https://developers.kakao.com/" target="_blank"><font color="#2DA7FE">https://developers.kakao.com</font></a></font> 접속 후 로그인<br>
				  - 앱만들기 이후 <br>
				  - REST API 키 및 JavaScript 키를 입력해주세요
				</div>

			  </div>
			</div>

			
			


		</td>
	</tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center">
			<input type="submit" value="확 인" class="base_btn reg">
		</td>
	</tr>
</table>
</form>

<? include "../foot.php"; ?>
