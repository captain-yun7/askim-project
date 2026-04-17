<script>
	$(function() {
		$("form[name='frm']").validate({
			rules : {
				text : { required: true, minlength: 10 }
			}, messages : {
				text : {required : "내용을 입력해주세요.", minlength: "내용을 입력해 주세요." },
			}
		});
	});
	function termsSave(frm) {
		var frm = $("form[name='frm']");

		if(!frm.valid()){
			return false;
		}

		if(!confirm("저장하시겠습니까?")) {
			return false;
		}

		frm.submit();
	}

	$('#leftmenu >ul > li:nth-of-type(2)').addClass('on').find('ul li:nth-of-type(3)').addClass('active');
</script>
<div id="contents">
	<div class="main_tit">
		<h2>약관 및 개인정보정책</h2>
		<div class="btn_right btn_num2">
			<a href="terms_list" class="btn gray">목록</a>
			<a href="javascript://" onclick="termsSave(document.frm);" class="btn point">수정</a>
		</div>
	</div>
	<?=form_open("", array("name" => "frm"));?>
		<input type="hidden" name="mode" value="<?=$mode?>" />
		<div class="table_write">
			<table cellpadding="0" cellspacing="0" border="0">
				<colgroup>
					<col width="14%" />
					<col width="*" />
				</colgroup>
				<tbody>
					<tr>
						<th scope="col">항목명</th>
						<td>
							<input type="hidden" name="code" value="<?=$this->input->get("code", true)?>" />
							<input type="text" name="title" value="<?=$terms["title"]?>" />
							<input type="hidden" name="language" value = "<?=$this->input->get("language",true)?>" />
						</td>
					</tr>
					<tr>
						<th scope="col">내용</th>
						<td><textarea name="text" id="terms"><?=$terms["text"]?></textarea></td>
					</tr>
				</tbody>
			</table>
		</div>
		<? if($this->input->get("code", true) == "usePolicy") : ?>
			<div class="terms_privecy_box">
				<dl>
					<dt>- 개인정보보호 의무조치</dt>
					<dd>
					웹사이트를 운영하는 모든 사업자와 기타 영리를 목적으로 홈페이지를 운영하는 자 중 개인정보를 취급하면 반드시 아래와 같은 개인정보 보호조치를 해야 합니다.<br/><br/>
					* '개인정보취급방침'을 마련하고 이용자에게 공개합니다.<br/>
					* 개인정보 수집 이용에 대한 이용자 동의를 받습니다.<br/>
					* 개인정보를 제3자에게 제공하거나 업무 위탁할 때 이에 대한 동의를 받아야 합니다.<br/>
					* 이용자의 회원탈퇴 권리 및 방법을 안내하고 이를 이행해야 합니다.<br/>
					* 개인정보를 전송하는 구간에 보안서버를 적용해야 합니다.<br/><br/>
					</dd>
				</dl>
				<dl>
					<dt>- 개인정보취급방침 구축절차</dt>
					<dd>
					1. ‘개인정보보호 종합지원 포털’에서 ‘맞춤서비스’ 메뉴를 이용하여 개인정보처리방침을 만듭니다. <a href="http://www.privacy.go.kr/a3sc/per/inf/perInfStep01.do" target="_blank">( 개인정보처리방침 생성하러 바로가기 )</a><br/>
					2. 만드신 개인정보처리방침을 '내용'에 기입하시고 '수정'버튼을 눌러 저장합니다.<br/><br/>
					</dd>
				</dl>
				<dl>
					<dt>- 개인정보취급방침에 회사정보 기입</dt>
					<dd>
					각 항목에, 아래 기입된 태그, 중괄호를 복사하여 넣어서 사용합니다. ( 마우스로 드래그를 { 부터 } 까지 한 후, 복사합니다. )<br/>
					- 상호명 : {$compName}<br/>
					- 개인정보보호 책임자 : <br/>
					- 담당자 이메일 : <br/>
					</dd>
				</dl>
			</div>
		<? endif ?>
	<?=form_close();?>
</div>