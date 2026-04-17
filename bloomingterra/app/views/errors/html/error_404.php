<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8" />
<title>페이지 오류안내</title>
<link rel="stylesheet" type="text/css" href="/lib/css/common.css" />
</head>
<body>
<div id="wrap">
	<div id="content" style="margin:0;">
		<div class="error_cont">
			<div class="error_form">
				<h2>죄송합니다. 요청하신 페이지를 찾을 수 없습니다.</h2>
				<p>방문하시려는 페이지의 주소가 잘못 입력되었거나, 변경 혹은 삭제되어 찾을 수 없습니다.<br/>입력하신 주소가 정확한지 다시 한번 확인해 주시기 바랍니다.</p>
				<h5><a href="<?=base_url()?>">메인으로</a></h5>
			</div><!-- .error_form -->
		</div><!-- .error_cont -->
	</div>
</div><!--wrap-->
</body>
</html>