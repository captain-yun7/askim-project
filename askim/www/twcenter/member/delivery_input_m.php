<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if($wiz_session['id'] == "") {
	error("로그인 후 이용하세요");
	exit;
}

if(!$type) $type = "i";

if($type == "u") {

	$sql = "
			SELECT 
				*
			FROM 
				wiz_delivery_set
			WHERE 
				idx='{$_GET['idx']}'
	";
	$drs = query($sql);
	$drw = sql_fetch_arr($drs);

	$re_name       = xss_clean($drw['re_name']);
	$re_post       = xss_clean($drw['re_post']);
	$re_addr       = xss_clean($drw['re_addr']);
	$re_addr2      = xss_clean($drw['re_addr2']);
	$re_hphone     = xss_clean($drw['re_hphone']);
	$re_tphone     = xss_clean($drw['re_tphone']);
	$basicdelivery = xss_clean($drw['basicdelivery']);

	list($hphone0, $hphone1, $hphone2) = explode("-", $re_hphone);
	list($tphone0, $tphone1, $tphone2) = explode("-", $re_tphone);

}

echo $DAUM_POSTCODE.PHP_EOL;
?>

<div id="myDelivery">
<form name="frm" id="frm">
<input type="hidden" name="type" id="type" value="<?php echo $type ?>">
<input type="hidden" name="bucode" id="bucode">
<input type="hidden" name="idx" id="idx" value="<?php echo $_GET['idx'] ?>">
	<table class="add_table">
		<colgroup>
			<col width="90" />
			<col width="" />
		</colgroup>
		<tr>
			<th>기본배송지</th>
			<td><label for="basicdelivery"><input name="basicdelivery" id="basicdelivery" type="checkbox" value="Y" <?php if($basicdelivery == "Y") echo "checked" ?> class='checkbox'> 기본배송지 저장</label></td>
		</tr>
		<tr>
			<th>받는사람 *</th>
			<td><input name="re_name" type="text" class="input_m" value="<?php echo $re_name ?>"></td>
		</tr>
		<tr>
			<th>주소 *</th>
			<td>
				<input type="text" name="re_post" size="5" class="input_s add_num" value="<?php echo $re_post ?>" readonly>
				<input type="button" onClick="postSearch('')" value="우편번호 검색" style="position:relative; vertical-align:top;" readonly><br />
				<input type="text" name="re_addr" maxlength="80" class="input_l address" onClick="postSearch('')" readonly value="<?php echo $re_addr ?>"><br />
				<input type="text" name="re_addr2" maxlength="100" class="input_l address_en" value="<?php echo $re_addr2 ?>">
				<div id="wrap" style="display:none;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
					<img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
				</div>
			</td>
		</tr>
		<tr>
			<th>전화번호 *</th>
			<td>
				<input name="tphone[]" type="text" class="input_num" id="tphone0" value="<?php echo $tphone0 ?>" maxlength="3" Onlynum="true" style="ime-mode: disabled">
				-
				<input name="tphone[]" type="text" class="input_num" id="tphone1" value="<?php echo $tphone1 ?>" maxlength="4" Onlynum="true" style="ime-mode: disabled">
				-
				<input name="tphone[]" type="text" class="input_num" id="tphone2" value="<?php echo $tphone2 ?>" maxlength="4" Onlynum="true" style="ime-mode: disabled">
			</td>
		</tr>
		<tr>
			<th>휴대 전화번호 *</th>
			<td>
				<input name="hphone[]" type="text" class="input_num" id="hphone0" value="<?php echo $hphone0 ?>" maxlength="3" Onlynum="true" style="ime-mode: disabled">
				-
				<input name="hphone[]" type="text" class="input_num" id="hphone1" value="<?php echo $hphone1 ?>" maxlength="4" Onlynum="true" style="ime-mode: disabled">
				-
				<input name="hphone[]" type="text" class="input_num" id="hphone2" value="<?php echo $hphone2 ?>" maxlength="4" Onlynum="true" style="ime-mode: disabled">
			</td>
		</tr>
	</table>

	<div class="btn_area">
		<input type="button" onClick="location.href='<?php echo $_SERVER["PHP_SELF"]; ?>';" value="취소하기" class="btn_wb"/>
		<input type="button" value="저장하기" class="btn_b DeliverySubmit">
	</div>
</form>
</div>

<script>
var element_wrap = document.getElementById('wrap');

function foldDaumPostcode() {
	element_wrap.style.display = 'none';
}

function postSearch(kind) {

	var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
	new daum.Postcode({
		oncomplete: function(data) {
			var fullAddr = '';
			var extraAddr = '';

			if (data.userSelectedType === 'R') {
				fullAddr = data.roadAddress;
			} else {
				fullAddr = data.jibunAddress;
			}

			if(data.userSelectedType === 'R'){
				if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
					extraAddr += data.bname;
				}
				if(data.buildingName !== '' && data.apartment === 'Y'){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			var buildingCode = data.buildingCode;
			$("#bucode").val(buildingCode);

			eval('frm.'+kind+'re_post').value = data.zonecode;
			eval('frm.'+kind+'re_addr').value = fullAddr;

			if(eval('frm.'+kind+'re_addr') != null)
				eval('frm.'+kind+'re_addr').focus();

			element_wrap.style.display = 'none';

			document.body.scrollTop = currentScroll;
		},
		onresize : function(size) {
			element_wrap.style.height = size.height+'px';
		},
		width : '100%',
		height : '100%'
	}).embed(element_wrap);

	element_wrap.style.display = 'block';
}

$(function() {

	$(".DeliverySubmit").on("click", function() {

		var basicde  = $("input[name=basicdelivery]:checked").val();
		var re_name  = $("input[name=re_name]").val();
		var re_post  = $("input[name=re_post]").val();
		var re_addr  = $("input[name=re_addr]").val();
		var re_addr2 = $("input[name=re_addr2]").val();
		var bucode   = $("#bucode").val();
		var type     = $("#type").val();
		var idx      = $("#idx").val();

		if(basicde === undefined) var tbasicde = "";
		else                      var tbasicde = basicde;

		var hphone  = [];
		var hphone_input = $("input[name='hphone[]']");
		$(hphone_input).each(function(i){
			var tmp_hphone = $("input[id='hphone" + i + "']").val();
			    hphone.push(tmp_hphone);
		});
		var str_hphone = hphone.join("-");

		var tphone  = [];
		var tphone_input = $("input[name='tphone[]']");
		$(tphone_input).each(function(i){
			var tmp_tphone = $("input[id='tphone" + i + "']").val();
			    tphone.push(tmp_tphone);
		});
		var str_tphone = tphone.join("-");

		if(!re_name)  { alert('받는사람의 이름을 입력하세요.'); $("input[name=re_name]").focus(); return false; }
		if(!re_post)  { alert('우편번호를 입력하세요.'); $("input[name=re_post]").focus(); return false; }
		if(!re_addr2) { alert('상세주소를 입력하세요.'); $("input[name=re_addr2]").focus(); return false; }

		if(!check_Tphone3(str_tphone, "올바른 전화번호를 입력하세요.")) return false;
		if(!check_Hphone2(str_hphone, "올바른 휴대전화번호를 입력하세요.")) return false;

		var params = "";
			params += "basicde="+tbasicde;
			params += "&re_name="+encodeURIComponent(re_name);
			params += "&re_post="+re_post;
			params += "&re_addr="+encodeURIComponent(re_addr);
			params += "&re_addr2="+encodeURIComponent(re_addr2);
			params += "&re_hphone="+str_hphone;
			params += "&re_tphone="+str_tphone;
			params += "&bucode="+bucode;
			params += "&type="+type;
			params += "&idx="+idx;
			params += "&ptype=save";

		$.ajax({
			type: "POST"
			,url: "/twcenter/member/delivery_save.php"
			,cache: false
			,async: false
			,data: params
			,dataType: "json"
			,success: function(data) {
				if(data.result == '00') {
					alert(data.msg);
					document.location.replace("<?php echo $_SERVER['PHP_SELF']; ?>");
				}
			}
			,error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}

		});

	});

});

</script>