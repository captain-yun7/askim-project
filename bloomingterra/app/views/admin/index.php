<style>
/* body, #wrap {background:#fff;overflow:hidden;width:100%;min-width:500px !important;} */
/* #wrapper, #container {width:100% !important;height:100%;text-align:Center;min-height:500px !important;min-width:500px !important;padding:0 !important;margin:0 auto !important;} */
#wrap{width:100%;height:100vh;position: relative;background:url('/lib/admin/images/login_bg.jpg')no-repeat center;background-size:cover;overflow:hidden;}
#container{background:transparent;padding:0;}
#footer {display:none;}
</style>
<script>
	$(function() {
		var adminid = localStorage.getItem("adminid");
		if(adminid) {
			$(":input[name='userid']").val(adminid);
			$(":input[name='idsave']").prop("checked", true);
		}
		setFocus();
	});


	function setFocus(){
		if ( document.forms[0].userid.value == "" ) {
			document.forms[0].userid.focus();
		} else {
			document.forms[0].password.focus();
		}
	}
	function loginCommit(frm) {
		if(!frm.userid) {
			alert("한글/영문/숫자 4자리 이상 30자리 이하로 입력");
			frm.userid.focus();
			return false;
	 	}

		if(frm.userid.value.length < 4 || frm.userid.value.length > 30) {
			alert("한글/영문/숫자 4자리 이상 30자리 이하로 입력");
			frm.userid.focus();
			return false;
	 	}

		if(!frm.password) {
			alert("4자리 이상 20자리 이하로 입력");
			frm.userid.focus();
			return false;
	 	}

		if(frm.password.value.length < 4 || frm.password.value.length > 20) {
			alert("4자리 이상 20자리 이하로 입력");
			frm.userid.focus();
			return false;
	 	}
		
	}
</script>
<div id="adminLog">
	<div class="adminLog-box">
		<!-- <h1><?=$this->_cfg_site["nameKor"]?>관리자 로그인</h1> -->
		<div class="log-form">
			<form name="frm" action="/admin/login" method="POST" onsubmit="return loginCommit(this);">
				<input type="hidden" name="encrypt" value="p2" />
				<input type="hidden" name="return_url" value="<?=urlencode($this->input->get("return_url", true))?>" />
				<fieldset>
					<div class="logo_box">
						<h2>Welcome to <img src="/lib/admin/images/admin-logo.png" alt="디자인아트 로고"> <p>통합관리자시스템</p></h2>
						<a href="//www.designart.co.kr/new/index.php" target="_blank">www.designart.co.kr</a>
					</div>
					<div class="login_box">
						<h3>LOGIN</h3>
						<div class="log-idPwd">
							<dl>
								<dt>아이디</dt>
								<dd><input type="text" size="25" name="userid" maxlength="20" value="" tabindex=1 placeholder="관리자 아이디"/></dd>
							</dl>
							<dl>
								<dt>비밀번호</dt>
								<dd><input type="password" size="25"  name="password" maxlength="20"  tabindex=2 placeholder="관리자 비밀번호"/></dd>
							</dl>
							<!-- <div class="id-save">
								<input type="checkbox" name='idsave' value='ok' id='idsave'/><label for="idsave">아이디저장</label>
							</div> -->
						</div>
						<div class="log-okBtn">
							<input type="submit" value="로그인" class="log-okSubmit" />
						</div>	
						<ul class="login_intro">
							<li>제작 완료 후 발급받으신 계정정보로 로그인하시면 됩니다.</li>
							<li>디자인아트플러스 고객센터 : 02-6953-6045 <br>계정정보를 잊으셨거나 문의사항이 있는 경우 연락주세요 ^^</li>
						</ul>
					</div>
				</fieldset>
			</form>
		</div>
		<!-- <p>COPYRIGHT &copy; <?=$this->_cfg_site["nameEng"]?> SYSTEM ALL RIGHT RESERVED.</p> -->
	</div>
</div>