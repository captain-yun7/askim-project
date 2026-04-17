<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

if($wiz_session['id'] == "") error("로그인 후 이용하세요");

if(!$type) $type = "i";

if($type == "u") {

	$sql = "
		SELECT *
		  FROM wiz_delivery_set
		 WHERE idx='{$idx}'
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
?>
<!DOCTYPE html>
<html lang="ko" xml:lang="ko">
<head>
<title>:: 배송지선택 ::</title>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="/comm/css/sub.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script language="JavaScript" type="text/JavaScript" src="/comm/js/script.js"></script>
<?php echo $DAUM_POSTCODE.PHP_EOL ?>
<script>
function postSearch(kind) {

	if(kind == undefined) kind = "";
	new daum.Postcode({
		oncomplete: function(data) {

			var frm = document.frm;

			var extraAddr = '';
			var fullAddr = '';

			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;
			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}

			if(data.userSelectedType === 'R'){

				if(data.bname !== ''){
					extraAddr += data.bname;
				}

				if(data.buildingName !== ''){
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
		}
	}).open();

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
		var uType    = $("#uType").val();
		var sid      = $("#sid").val();

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
			params += "&uType="+uType;
			params += "&sid="+sid;
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
					document.location.replace("/twcenter/module/delivery_pop.php?uType=<?php echo $_GET['uType'] ?>&sid=<?php echo $_GET['sid'] ?>");
				}
			}
			,error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}

		});

	});

});

</script>
</head>
<body>
<div id="myDelivery_pop">
	<form name="frm" id="frm">
	<input type="hidden" name="type" id="type" value="<?php echo $type ?>">
	<input type="hidden" name="bucode" id="bucode">
	<input type="hidden" name="idx" id="idx" value="<?php echo $idx ?>">
	<input type="hidden" name="uType" id="uType" value="<?php echo $_GET['uType'] ?>">
	<input type="hidden" name="sid" id="sid" value="<?php echo $_GET['sid'] ?>">
	<h1>
		배송지 선택
		<a href="#" class="close" onClick="self.close();"><img src="/twcenter/product//image/id_check_close.gif" width="21" height="21" border="0"></a>
	</h1>
	<div class="cont">
		<table class="add_table">
			<colgroup>
				<col width="140" />
				<col width="" />
			</colgroup>
			<tr>
				<th>기본배송지</th>
				<td><label for="basicdelivery"><input name="basicdelivery" type="checkbox" value="Y" <?php if($basicdelivery == "Y") echo "checked" ?> class='checkbox'> 기본배송지 저장</a></td>
			</tr>
			<tr>
				<th>받는사람 *</th>
				<td><input name="re_name" type="text" class="input_m" value="<?php echo $re_name ?>"></td>
			</tr>
			<tr>
				<th>주소 *</th>
				<td>
					<input type="text" name="re_post" size="5" class="input_s add_num" value="<?php echo $re_post ?>" readonly>
					<input type="button" onClick="postSearch('')" value="우편번호 검색" style="position:relative; vertical-align:top;" readonly>
					<input type="text" name="re_addr" maxlength="80" class="input_l address" onClick="postSearch('')" readonly value="<?php echo $re_addr ?>"><br />
					<input type="text" name="re_addr2" maxlength="100" class="input_l address_en" value="<?php echo $re_addr2 ?>">
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
			<input type="button" onClick="history.go(-1);" value="취소하기" class="btn_wb"/>
			<input type="button" value="저장하기" class="btn_b DeliverySubmit">
		</div>
	</div>
	</form>
</div>
</body>
</html>