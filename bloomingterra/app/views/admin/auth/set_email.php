<link rel="stylesheet" href="/lib/admin/css/admin_css.css">
<link rel="stylesheet" href="/lib/admin/css/skin.css">
<style>
button { padding: 3px 20px; background: #000; color: #fff; cursor: pointer; border: 0; }
.center { text-align: center; margin-top: 5px; }
.show-passowrd { padding: 3px; margin-left: 3px; vertical-align: top; height: 30px; }
.send-test { background: #f00; color: #fff; }
</style>
<script src="/lib/js/jquery-2.2.4.min.js"></script>
<script>
$(function() {
	$(".show-passowrd").on("click", function() {
		var type = $("#userPassword").attr("type") == "password" ? "text" : "password";
		$("#userPassword").attr("type", type);
	});

	$(".send-test").on("click", function() {
		$(location).attr("href", "test_mail");
	});
});
</script>
<div id="contents" style="padding: 10px;">
	<form method="post">
		<div class="table_write">
			<table class="tableB" style="width: 100%;">
				<colgroup>
					<col style="width: 100px;">
				</colgroup>
				<tr>
					<th scope="row">보내는 사람</th>
					<td><input type="text" name="sender" value="<?=$sender?>"></td>
				</tr>
				<!-- tr>
					<th scope="row">서비스</th>
					<td>
						<select name="service">
							<option value="naver">네이버</option>
							<option value="google"<?=$service == 'google' ? ' selected' : ''?>>구글</option>
						</select>
					</td>
				</tr -->
				<tr>
					<th scope="row">보안 연결</th>
					<td>
						<select name="secure">
							<option value="ssl">SSL</option>
							<option value="tls"<?=$secure == 'tls' ? ' selected' : ''?>>TLS</option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">포트</th>
					<td><input type="text" name="port" value="<?=$port?>" style="width: 50px;" required></td>
				</tr>
				<tr>
					<th scope="row">호스트</th>
					<td><input type="text" name="host" value="<?=$host?>" required></td>
				</tr>
				<tr>
					<th scope="row">아이디</th>
					<td><input type="text" name="userid" value="<?=$userid?>" required></td>
				</tr>
				<tr>
					<th scope="row">패스워드</th>
					<td><input type="password" name="userpassword" id="userPassword" value="<?=$userpassword?>" required><button type="button" class="show-passowrd">보기</button></td>
				</tr>
			</table>
		</div>
		<div class="center">
			<button>확인</button>
			<button type="button" class="send-test">테스트 메일 발송</button>
		</div>
	</form>
</div>