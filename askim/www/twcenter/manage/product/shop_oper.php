<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/site_info.php";
include_once "../../inc/oper_info.php";
include_once "../../inc/bankda_info.php";
include "../head.php";

$code = "review";
$sql = "select * from wiz_bbsinfo where code = '$code'";
$result = query($sql);
$review_info = sql_fetch_arr($result);

$code = "qna";
$sql = "select * from wiz_bbsinfo where code = '$code'";
$result = query($sql);
$qna_info = sql_fetch_arr($result);

//180309 PG사 클릭시 설명 레이어 노출 
if($oper_info['pay_agent'] == 'TOSS'){
	$lgEscrow      = ' style="display:none"';
	$kcpEscrow     = ' style="display:none"';
	$iniEscrow     = ' style="display:none"';
	$tossEscrow     = '';

	$lgViraccount  = ' style="display:none"';
	$kcpViraccount = ' style="display:none"';
	$iniViraccount = ' style="display:none"';
	$tossViraccount = '';

	$lgQuota       = ' style="display:none"';
	$kcpQuota      = ' style="display:none"';
	$iniQuota      = ' style="display:none"';
	$tossQuota      = '';

	$KeyId         = 'TOSS Client Key';
	$KeyValue      = 'TOSS Secret Key';
} else if($oper_info['pay_agent'] == 'KCP'){
	$lgEscrow      = ' style="display:none"';
	$kcpEscrow     = '';
	$iniEscrow     = ' style="display:none"';
	$tossEscrow     = ' style="display:none"';

	$lgViraccount  = ' style="display:none"';
	$kcpViraccount = '';
	$iniViraccount = ' style="display:none"';
	$tossViraccount = ' style="display:none"';

	$lgQuota       = ' style="display:none"';
	$kcpQuota      = '';
	$iniQuota      = ' style="display:none"';
	$tossQuota      = ' style="display:none"';

	$KeyId         = 'KCP 상점 사이트코드';
	$KeyValue      = 'KCP 상점 SITE KEY';

} else if($oper_info['pay_agent'] == 'INICIS'){
	$lgEscrow      = ' style="display:none"';
	$kcpEscrow     = ' style="display:none"';
	$iniEscrow     = '';
	$tossEscrow     = ' style="display:none"';

	$lgViraccount  = ' style="display:none"';
	$kcpViraccount = ' style="display:none"';
	$iniViraccount = '';
	$tossViraccount = ' style="display:none"';

	$lgQuota       = ' style="display:none"';
	$kcpQuota      = ' style="display:none"';
	$iniQuota      = '';
	$tossQuota      = ' style="display:none"';

	$KeyId         = 'INICIS 상점 아이디';
	$KeyValue      = 'INICIS 상점 SIGN KEY';
} else {

	$lgEscrow      = ' style="display:none"';
	$kcpEscrow     = ' style="display:none"';
	$iniEscrow     = ' style="display:none"';
	$tossEscrow     = '';

	$lgViraccount  = ' style="display:none"';
	$kcpViraccount = ' style="display:none"';
	$iniViraccount = ' style="display:none"';
	$tossViraccount = '';

	$lgQuota       = ' style="display:none"';
	$kcpQuota      = ' style="display:none"';
	$iniQuota      = ' style="display:none"';
	$tossQuota      = '';

	$KeyId         = 'TOSS Client Key';
	$KeyValue      = 'TOSS Secret Key';
}

$LGDACOM_PG_TEST_ID  = "threeway2016";
$LGDACOM_PG_TEST_KEY = "c71e4527a787d409b365c803bda7bf5b";
$NHNKCP_PG_TEST_ID   = "T0007";
$NHNKCP_PG_TEST_KEY  = "3CRB7XHFjUp6fjf1FLEM.g6__";
$INICIS_PG_TEST_ID   = "INIpayTest";
$INICIS_PG_TEST_KEY  = "SU5JTElURV9UUklQTEVERVNfS0VZU1RS";

$TOSS_PG_TEST_CLIENTKEY = "test_ck_OyL0qZ4G1VOvo91qQwn3oWb2MQYg";
$TOSS_PG_TEST_SECRETKEY = "test_sk_YPBal2vxj81ywEWO5GR85RQgOAND";

?>
<!-- <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> -->
<? echo $DAUM_POSTCODE.PHP_EOL; ?>
<script language="JavaScript" type="text/javascript">
<!--

// 적립금 비율 다시적용
function setReserve(){

	var frm = document.frm;
	var reserve_per = frm.reserve_per.value;

	if(!isNaN(reserve_per) && reserve_per != ""){
		if(confirm("모든 상품의 적립금이 상품가격의 "+reserve_per+"% 로 일괄적용 됩니다.\n\n진행하시겠습니까?")){
			document.location = "shop_save.php?mode=setreserve&reserve_per=" + reserve_per;
		}
	}else{
		alert("숫자를 입력하세요");
		frm.reserve_per.value = "";
		frm.reserve_per.focus();
	}

}

function snsuse(){

	var snsUse = $(':radio[name="sns_use"]:checked').val();
	if(snsUse == "Y"){
		$("#s_show1").attr("disabled",false);
		$("#s_show2").attr("disabled",false);
		$("#s_show3").attr("disabled",false);
		$("#s_show4").attr("disabled",false);
	}else{
		$("#s_show1").attr("disabled",true);
		$("#s_show2").attr("disabled",true);
		$("#s_show3").attr("disabled",true);
		$("#s_show4").attr("disabled",true);
	}

}


//PG사 클릭시 설명문구
/*function pg_dec(no){
	var pgdoc=document.getElementById("pgdec");
	if(no == "1"){
		//데이콤 설명
		pgdoc.innerHTML = "<b>데이콤 관리자 > 계약정보 > 상점정보관리 > '승인결과전송여부' 를 전송(웹전송)</b>으로 꼭 변경하세요.<br>반드시 변경해야 카드결제 연동이 정상적으로 이루어집니다.";
		$("#lgdacom").show();
		$("#kcp").hide();
		$("#inicis").hide();
	} else if(no =="2") {
		//KCP설명
		pgdoc.innerHTML = "<b>KCP에서 발급받은 아이디를 입력하세요.</b><br>반드시 변경해야 카드결제 연동이 정상적으로 이루어집니다.";
		$("#lgdacom").hide();
		$("#kcp").show();
		$("#inicis").hide();
	} else if(no =="3") {
		//이니시스 설명
		pgdoc.innerHTML = "<b>이니시스에서 받으신 키파일을 /twcenter/product/INICIS/key/아이디명</b>으로 업로드하시고<br>발급 받은 가맹점 아이디를 꼭입력하여주세요.<br>반드시 변경해야 카드결제 연동이 정상적으로 이루어집니다.";
		$("#lgdacom").hide();
		$("#kcp").hide();
		$("#inicis").show();
	} else if(no =="4") {
		//올더게이트 설명
		pgdoc.innerHTML = "<b>올더게이트에서 발급받은 아이디를 입력하세요.</b><br>실거래 아이디가 아닐경우 오류메세지가 나타날수 있습니다.<br>반드시 변경해야 카드결제 연동이 정상적으로 이루어집니다.";
	} else {
		$("#lgdacom").show();
		$("#kcp").hide();
		$("#inicis").hide();
	}
}*/

// 은행계좌번호 등록
function accIns() {
	var url = "pay_account.php";
  window.open(url,"pay_Account","height=300, width=500, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");
}

// 은행계좌번호 수정
function accMod(no) {
	var url = "pay_account.php?mode=update&no=" + no;
  window.open(url,"pay_Account","height=300, width=500, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");
}

// 은행계좌번호 삭제
function accDel(no, bkacctno) {

	<?php if($wiz_admin['designer'] == 'Y' && $oper_info['bankda_use'] == 'Y') { ?>
		var msg = "계좌삭제시 연동중인 뱅크다의 모든정보를 이용할수 없습니다.\n삭제하시겠습니까?";
	<?php } else { ?>
		var msg = "은행계좌번호를 삭제하시겠습니까?";
	<?php } ?>

	if(confirm(msg)) {

		<?php
		if($wiz_admin['designer'] == 'Y' && $oper_info['bankda_use'] == 'Y') {
		?>

		var service_type  = "<?php echo $oper_info['bankda_service'] ?>";
		var partner_id    = "<?php echo $oper_info['bankda_partner_id'] ?>";
		var reqvalue1     = "<?php echo $bankda_info['bankda_id'] ?>";
		var reqvalue2     = "<?php echo $bankda_info['bankda_pw'] ?>";

		var _params = "directAccess=y";
			_params += "&service_type=" + service_type;
			_params += "&partner_id=" + partner_id;
			_params += "&bkidx=" + no;
			_params += "&mode=deleteall";

		$.ajax({
			type: 'POST'
			,url: './bankda_account_ajaxload.php'
			,cache: false
			,data: _params
			,dataType: 'json'
		}).done(function(data){
			 if(data.result == '0000') {
				alert(data.msg);
				result = true;
			}
			if(result) {

				var $form = $("<form></form>");
				$form.attr("action","https://ssl.bankda.com/partnership/user/account_del.php");
				$form.attr("target", "frminfo");
				$form.attr("method","post");
				$form.appendTo("body");
				$form.append("<input type='hidden' name='directAccess' value='y'>");
				$form.append("<input type='hidden' name='service_type' value='" + service_type + "'>");
				$form.append("<input type='hidden' name='partner_id' value='" + partner_id + "'>");
				$form.append("<input type='hidden' name='user_id' value='" + reqvalue1 + "'>");
				$form.append("<input type='hidden' name='user_pw' value='" + reqvalue2 + "'>");
				$form.append("<input type='hidden' name='Command' value='update'>");
				$form.append("<input type='hidden' name='bkacctno' value='" + bkacctno + "'>");
				$form.submit();
				
			}

			document.location.reload();
		});
		<?php
		} else {
		?>
		document.location = "pay_account.php?save=true&mode=delete&no=" + no;
		<?php
		}
		?>
	}


}

// 뱅크다 등록페이지이동
function bankda() {

	var w = 800;
	var h = 650;

	var window_left = (screen.availWidth - w) / 2;
	var window_top  = (screen.availHeight - h) / 2;
	var url = "bankda_account_add.php";

	window.open(url,"bankda_account","width=" + w +", height=" + h + ", scrollbars=yes,top=" + window_top + ",left=" + window_left);

}

// 멀티배송업체 등록
function delivery_com() {
	var url = "delivery_com.php";
  window.open(url,"delivery_com","height=350, width=900, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");
}

// 배송업체정보 수정
function delMod(no) {
	var url = "delivery_com.php?mode=update&idx=" + no;
  window.open(url,"delivery_com","height=350, width=900, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");
}

// 배송업체 삭제
function delDel(no) {
	if(confirm("선택하신 배송업체를 삭제하시겠습니까?\n삭제된 배송사를 이용한 주문내역은 배송추적이 불가능합니다.")) {
		document.location = "delivery_com.php?save=true&mode=delete&idx=" + no;
	}
}

function postSearch(kind) {
	if(kind == undefined) kind = '';
	new daum.Postcode({
		oncomplete: function(data) {

			var frm;
			for(i=0;i<document.forms.length;i++){
				frm = document.forms[i];

				var post_val = data.zonecode;
				var buildingName = data.buildingName;

				if(kind == 1){
					if(eval('frm.del_extrapost1')){
					   eval('frm.del_extrapost1').value = post_val;
					   eval('frm.del_extrapost1_addr').value = data.address +" "+ data.buildingName;
					}
				} else if(kind == 2) {
					if(eval('frm.del_extrapost12')){
					   eval('frm.del_extrapost12').value = post_val;
					   eval('frm.del_extrapost12_addr').value = data.address +" "+ data.buildingName;
					}
				} else if(kind == 3) {
					if(eval('frm.del_extrapost2')){
					   eval('frm.del_extrapost2').value = post_val;
					   eval('frm.del_extrapost2_addr').value = data.address +" "+ data.buildingName;
					}
				} else if(kind == 4) {
					if(eval('frm.del_extrapost22')){
					   eval('frm.del_extrapost22').value = post_val;
					   eval('frm.del_extrapost22_addr').value = data.address +" "+ data.buildingName;
					}
				} else if(kind == 5) {
					if(eval('frm.del_extrapost3')){
					   eval('frm.del_extrapost3').value = post_val;
					   eval('frm.del_extrapost3_addr').value = data.address +" "+ data.buildingName;
					}
				} else if(kind == 6) {
					if(eval('frm.del_extrapost32')){
					   eval('frm.del_extrapost32').value = post_val;
					   eval('frm.del_extrapost32_addr').value = data.address +" "+ data.buildingName;
					}
				}
			}
		}
	}).open();
}

$(function(){

	<?php if($oper_info['card_quota_use'] == 'Y' && $oper_info['pay_agent'] != 'TOSS') { ?>
		$("#card_quotabase").prop("disabled", false);
	<?php } ?>
// 1회 최대사용 적립금 : 제한없음
	$("#id_unLimited").click(function(){
		$("#id_reserve_max").prop("disabled",this.checked);
	});

// 이니시스 에스크로 키 노출
	$("[name=pay_agent]").click(function(){

		if($(this).val() == "DACOM"){
			$("#lgEscrow").show();
			$("#kcpEscrow").hide();
			$("#iniEscrow").hide();
			$("#lgViraccount").show();
			$("#kcpViraccount").hide();
			$("#iniViraccount").hide();
			$("#tossViraccount").hide();
			$("#card_quota_set").show();
			$("#lgQuota").show();
			$("#kcpQuota").hide();
			$("#iniQuota").hide();

		} else if($(this).val() == "KCP"){
			$("#lgEscrow").hide();
			$("#kcpEscrow").show();
			$("#iniEscrow").hide();
			$("#lgViraccount").hide();
			$("#kcpViraccount").show();
			$("#iniViraccount").hide();
			$("#tossViraccount").hide();
			$("#lgQuota").hide();
			$("#kcpQuota").show();
			$("#iniQuota").hide();
			$("#card_quota_set").show();

		} else if($(this).val() == "INICIS"){
			$("#lgEscrow").hide();
			$("#kcpEscrow").hide();
			$("#iniEscrow").show();
			$("#lgViraccount").hide();
			$("#kcpViraccount").hide();
			$("#iniViraccount").show();
			$("#tossViraccount").hide();
			$("#lgQuota").hide();
			$("#kcpQuota").hide();
			$("#iniQuota").show();
			$("#card_quota_set").show();

		} else if($(this).val() == "TOSS"){
			$("#lgEscrow").hide();
			$("#kcpEscrow").hide();
			$("#iniEscrow").hide();
			$("#lgViraccount").hide();
			$("#kcpViraccount").hide();
			$("#iniViraccount").hide();
			$("#tossViraccount").show();
			$("#lgQuota").hide();
			$("#kcpQuota").hide();
			$("#iniQuota").hide();
			$("#card_quota_set").hide();
			$("#card_quota_use").hide();
		}

		if($(this).val() != "INICIS"){
			$("#pay_escrow_v").hide();
			$("#pay_id_escrow input").prop("disabled",true);
			$("#pay_key_escrow input").prop("disabled",true);

		}else{
			$("#pay_escrow_v").show();
			$("#pay_id_escrow input").prop("disabled",false);
			$("#pay_key_escrow input").prop("disabled",false);
		}

	});

});

//	if(pay_agent == "DACOM") {
//		$(".kk").prop('class','t_name_LG');
//	} else if(pay_agent == "KCP") {
//		$(".kk").prop('class','t_name_KCP');
//	} else if(pay_agent == "KCP") {
//		$(".kk").prop('class','t_name_INI');
//	}


//180309 pg업체 연동항목 클릭시 레이어 노출 숨김 
function pgchk(pg) {

	var pay_test = $("[name=pay_test]:checked").val();
	var pay_agent = "<?php echo $oper_info['pay_agent'] ?>";

	if(pg == 'DACOM') {
		$("#KeyId").text('LG U+ 상점 아이디');
		$("#KeyValue").text('LG U+ 상점 MERT KEY');
		$("[name=pay_id]").val('<?php echo $LGDACOM_PG_TEST_ID ?>');
		$("[name=pay_key]").val('<?php echo $LGDACOM_PG_TEST_KEY ?>');

		$(".kk").attr('class','t_name_LG');

		if(pg != pay_agent) {
			$("[name='card_quotabase[]']").prop("checked", false);
		}
		$("#kcp_receipts_tip").hide();

	} else if(pg == 'KCP') {
		$("#KeyId").text('KCP 상점 사이트코드');
		$("#KeyValue").text('KCP 상점 SITE KEY');
		$("[name=pay_id]").val('<?php echo $NHNKCP_PG_TEST_ID ?>');
		$("[name=pay_key]").val('<?php echo $NHNKCP_PG_TEST_KEY ?>');
		
		$(".kk").attr('class','t_name_KCP');

		if(pg != pay_agent) {
			$("[id=card_quotabase]").val('');
		}
		$("#kcp_receipts_tip").show();

	} else if(pg == 'INICIS') {
		$("#KeyId").text('INICIS 상점 아이디');
		$("#KeyValue").text('INICIS 상점 SIGN KEY');
		$("[name=pay_id]").val('<?php echo $INICIS_PG_TEST_ID ?>');
		$("[name=pay_key]").val('<?php echo $INICIS_PG_TEST_KEY ?>');

		if(pg != pay_agent) {
			$("[name='card_quotabase3[]']").prop("checked", false);
		}
		$("#kcp_receipts_tip").hide();

	} else if (pg == 'TOSS')	{
		$("#KeyId").text('TOSS Client Key');
		$("#KeyValue").text('TOSS Secret Key');
		$("[name=pay_id]").val('<?php echo $TOSS_PG_TEST_CLIENTKEY ?>');
		$("[name=pay_key]").val('<?php echo $TOSS_PG_TEST_SECRETKEY ?>');

		if(pg != pay_agent) {
			$("[name='card_quotabase3[]']").prop("checked", false);
		}
		$("#kcp_receipts_tip").hide();

	}

	if(pay_test == 'N') {
		$("[name=pay_id]").val('');
		$("[name=pay_key]").val('');
	}
}

function pgTest(type) {

	var pay_agent = $("[name=pay_agent]:checked").val();

	if(type == 'N') {
		$("[name=pay_id]").val('');
		$("[name=pay_key]").val('');
	}

	pgchk(pay_agent);
}

function quotaChk(v) {

	if(v == 'Y') {
		//$("#card_quotabase").prop("disabled", true);
		$("#card_quota_use").show();
	} else {
		//$("#card_quotabase").prop("disabled", false);
		$("#card_quota_use").hide();
	}

}

function recomShow(v) {

	if(v == 'Y') {
		$("#recomShow").show();
	} else {
		$("#recomShow").hide();
	}

}

$(function() {

	$("#del_method_type").on("change", function() {
		var del_method = $(this).val();
		if(del_method == "DC") {
			$("#del_method_DC").show();
			$("#del_method_DD").hide();
		} else if(del_method == "DD") {
			$("#del_method_DC").hide();
			$("#del_method_DD").show();
		} else {
			$("#del_method_DC").hide();
			$("#del_method_DD").hide();
		}
	});

	var set_del_method = "<?php echo $oper_info['del_method'] ?>";
	if(set_del_method == "DC") {
		$("#del_method_DC").show();
		$("#del_method_DD").hide();
	} else if(set_del_method == "DD") {
		$("#del_method_DC").hide();
		$("#del_method_DD").show();
	} else {
		$("#del_method_DC").hide();
		$("#del_method_DD").hide();
	}

});

-->
</script>
<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">운영정보설정</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">운영에 필요한 정보를 설정합니다.</td>
	</tr>
</table>

<a name="pay">
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 결제정보</td>
	</tr>
</table>
<form name="frm" action="shop_save.php" method="post">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="oper_info">
<table width="100%" border="0" cellpadding="5" cellspacing="1" class="t_style">
	<tr>
		<td width="15%" class="t_name">결제방법</td>
		<td width="85%" class="t_value" colspan="3">
			<?php
			$pay_list = explode("/",$oper_info['pay_method']);
			for($ii=0; $ii<count($pay_list); $ii++){
			$pay_method[$pay_list[$ii]] = true;
			}
			?>
			<span style="vertical-align: middle"><input type="checkbox" name="pay_method[]" value="PB" <?php if($pay_method["PB"]==true) echo "checked"; ?>></span>무통장입금&nbsp;
			<span style="vertical-align: middle"><input type="checkbox" name="pay_method[]" value="PC" <?php if($pay_method["PC"]==true) echo "checked"; ?>></span>카드결제&nbsp;
			<span style="vertical-align: middle"><input type="checkbox" name="pay_method[]" value="PN" <?php if($pay_method["PN"]==true) echo "checked"; ?>></span>계좌이체&nbsp;
			<span style="vertical-align: middle"><input type="checkbox" name="pay_method[]" value="PV" <?php if($pay_method["PV"]==true) echo "checked"; ?>></span>가상계좌&nbsp;
			<!-- <span style="vertical-align: middle"><input type="checkbox" name="pay_method[]" value="PH" <?php if($pay_method["PH"]==true) echo "checked"; ?>></span>휴대폰결제&nbsp; -->
			
		</td>
	</tr>

	<div class="checks">
	<tr>
		<td class="t_name">결제시스템</td>
		<td class="t_value" colspan="3" style="padding:4px">
			<table width="100%" border="0" cellspacing="1" cellpadding="5" class="t_style">
				<tr>
					<td class="t_name" width="15%">
						<span style="vertical-align: middle"><input type="radio" name="pay_test" value="Y" <?php if(!strcmp($oper_info['pay_test'], "Y")) echo "checked" ?> onclick="pgTest('Y')"></span> 테스트
					</td>
					<td class="t_value">
						결제모듈을 테스트로 이용합니다.  <br>
						<!--2. 일부 결제 테스트는 실제 결제가 이루어지므로 주의하시기 바랍니다.<br>
						3. 올더게이트 결제 테스트는 오류메세지를 출력할수도 있습니다.-->
					</td>
				</tr>
				<tr>
					<td width="120" class="t_name">
						<span style="vertical-align: middle"><input type="radio" name="pay_test" value="N" <?php if(!strcmp($oper_info['pay_test'], "N")) echo "checked" ?> onclick="pgTest('N')"></span> PG업체 연동
					</td>
					<td class="t_value">

						<table width="100%" border="0" cellspacing="1" cellpadding="0">
							<tr>
								<td width="10%" align="center">
									<img src="../image/logo_lguplus_b.gif">
									<p>
									<!-- <div class="advice">
										<button type="button" class="btn-advice">?</button>
										<div class="content">
											<table width="100%" border="0" cellpadding="0" cellspacing="0">
												<tr>
													<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
													<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
												</tr>
											</table>
											<ul>
												<li>- <font color="red">주의1 : 결제수수료 <s>일반가입시 3.7%</s> => 제휴가입시 3.5%</font> (위 제휴가입하기 버튼을 클릭해서 가입해야만 제휴가입이 이루어집니다.)</li>
												<li>- <font color="red">주의2 : 승인결과 전송 "결제창2.0"으로 변경</font></li>
												<li>- '결제창2.0' 을 선택하지 않으면 결제가 정상적으로 이루어지지 않습니다.</li>
											</ul>
										</div>
									</div> -->
									LG유플러스 (토스페이먼츠 구모듈)
									</p>
									<p>
									<span style="vertical-align: middle">
										<input name="pay_agent" value="DACOM" type="radio" <?php if($oper_info['pay_agent'] == "DACOM") echo "checked"; ?> onclick="pgchk('DACOM');">
										<a href="http://pgweb.uplus.co.kr/pg/wmp/Home/application/apply_testid.jsp?cooperativecode=wizshop" target="_blank"><input type="button" value="서비스신청" class="base_btm reg" style="vertical-align:middle;"></a>
									</span>
									</p>
								</td>
								<td width="10%" align="center">
									<img src="../image/logo_kcp_b.gif">
									<p>
									NHN KCP
									</p>
									<p>
									<span style="vertical-align: middle"><input name="pay_agent" value="KCP" type="radio" <?php if($oper_info['pay_agent'] == "KCP") echo "checked"; ?> onclick="pgchk('KCP');"></span>
									</p>
								</td>
								<td width="10%" align="center">
									<img src="../image/logo_inicis_b.gif">
									<p>
									<!-- <div class="advice">
										<button type="button" class="btn-advice">?</button>
										<div class="content">
											<table width="100%" border="0" cellpadding="0" cellspacing="0">
												<tr>
													<td width="50%"><div class="helpTip5"><h4>도움말</h4></div></td>
													<td align="right"><div class="helpTip5"><div class="btn-close"><img src="../image/btn_close.gif"></div></td>
												</tr>
											</table>
											<ul>
												<li>- <font color="red">주의1 : 키파일을 /twcenter/product/INICIS/key/아이디명으로 업로드</font> (발급 받은 가맹점 아이디를 꼭입력하여주세요.)</li>
												<li>- <font color="red">주의2 : 폴더 및 파일 접근/읽기/쓰기 권한 설정 : chmod -R 755 폴더명</font></li>
												<li>- /twcenter/product/INICIS/key, /twcenter/product/INICIS/log</li>
											</ul>
										</div>
									</div> -->
									이니시스
									</p>
									<p>
									<span style="vertical-align: middle"><input name="pay_agent" value="INICIS" type="radio" <?php if($oper_info['pay_agent'] == "INICIS") echo "checked"; ?> onclick="pgchk('INICIS');"></span>
									</p>
								</td>
								<td width="10%" align="center">
									<img src="../image/logo_toss.png" style="border:1px solid #ddd;width:118px;height:48px;">
									<p>
									토스페이먼츠(신모듈)
									</p>
									<p>
									<span style="vertical-align: middle"><input name="pay_agent" value="TOSS" type="radio" <?php if($oper_info['pay_agent'] == "TOSS") echo "checked"; ?> onclick="pgchk('TOSS');"></span>
									</p>
								</td>
								<td width="20%"></td>
							</tr>
						</table>


						<!-- <span style="vertical-align: middle"><input name="pay_agent" value="DACOM" type="radio" <?php if($oper_info['pay_agent'] == "DACOM") echo "checked"; ?> onclick="pgchk('DACOM');"></span> LG유플러스 (http://ecredit.uplus.co.kr) <a href="http://pgweb.uplus.co.kr/pg/wmp/Home/application/apply_testid.jsp?cooperativecode=wizshop" target="_blank"><input type="button" value="제휴가입하기" class="base_btm reg"></a>

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
									<li>- <font color="red">주의1 : 결제수수료 <s>일반가입시 3.7%</s> => 제휴가입시 3.5%</font> (위 제휴가입하기 버튼을 클릭해서 가입해야만 제휴가입이 이루어집니다.)</li>
									<li>- <font color="red">주의2 : 승인결과 전송 "결제창2.0"으로 변경</font></li>
									<li>- '결제창2.0' 을 선택하지 않으면 결제가 정상적으로 이루어지지 않습니다.</li>
								</ul>
							</div>
						</div><br>
						<span style="vertical-align: middle"><input name="pay_agent" value="KCP" type="radio" <?php if($oper_info['pay_agent'] == "KCP") echo "checked"; ?> onclick="pgchk('KCP');"></span> KCP (http://www.payplus.co.kr)<br>
						<span style="vertical-align: middle"><input name="pay_agent" value="INICIS" type="radio" <?php if($oper_info['pay_agent'] == "INICIS") echo "checked"; ?>  onclick="pgchk('INICIS');"></span> INICIS (http://www.inicis.com)
						
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
									<li>- <font color="red">주의1 : 키파일을 /twcenter/product/INICIS/key/아이디명으로 업로드</font> (발급 받은 가맹점 아이디를 꼭입력하여주세요.)</li>
									<li>- <font color="red">주의2 : 폴더 및 파일 접근/읽기/쓰기 권한 설정 : chmod -R 755 폴더명</font></li>
									<li>- /twcenter/product/INICIS/key, /twcenter/product/INICIS/log</li>
								</ul>
							</div>
						</div> -->
					
						<!-- <span style="vertical-align: middle"><input name="pay_agent" value="ALLTHEGATE" type="radio" <?php if($oper_info['pay_agent'] == "ALLTHEGATE") echo "checked"; ?> onclick="javascript:pg_dec('4')"></span> AlltheGate (올더게이트) -->
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table width="100%" border="0" cellpadding="5" cellspacing="1" class="t_style">
	<tr>
		<td class="t_name" width="15%"><span id="KeyId"><?php echo $KeyId ?></span></td>
		<td class="t_value" width="35%">
			<input name="pay_id" value="<?php echo $oper_info['pay_id'] ?>" type="text" class="input" size="50">
		</td>
		<td class="t_name" width="15%"><span id="KeyValue"><?php echo $KeyValue ?></span></td>
		<td class="t_value" width="35%">
			<input name="pay_key" value="<?php echo $oper_info['pay_key'] ?>" type="text" size="50" class="input">
		</td>
	</tr>

	<?php
	if($oper_info['pay_agent'] != "INICIS"){
		$escrow_style="style='display:none'";
	}
	?>
	<tr <?php echo $escrow_style ?> id="pay_escrow_v">
		<td class="t_name">에스크로 ID</td>
		<td class="t_value">
			<input name="pay_id_escrow" value="<?php echo $oper_info['pay_id_escrow'] ?>" size="50" type="text" class="input">
		</td>
		<td class="t_name">에스크로 Mertkey</td>
		<td class="t_value">
			<input name="pay_key_escrow" value="<?php echo $oper_info['pay_key_escrow'] ?>" type="text" size="50" class="input">
		</td>
	</tr>

	<tr id="card_quota_set" style="<?echo ($oper_info['pay_agent'] == "TOSS") ? "display:none;" : "";?>">
		<td width="15%" class="t_name">카드 할부 설정</td>
		<td width="85%" class="t_value" colspan="3">
			<span style="vertical-align: middle"><input name="card_quota_use" value="N" type="radio" <?php if($oper_info['card_quota_use'] == "N") echo "checked"; ?> onclick="quotaChk('N')"></span> 일시불
			<span style="vertical-align: middle"><input name="card_quota_use" value="Y" type="radio" <?php if($oper_info['card_quota_use'] == "Y") echo "checked"; ?> onclick="quotaChk('Y')"></span> 일반 할부 결제
		</td>
	</tr>
	<tr id="card_quota_use" style="<?php if($oper_info['card_quota_use'] == "N" || $oper_info['pay_agent'] == "TOSS") echo "display:none;";?>">
		<td width="10%" class="t_name">카드 할부 기간 설정</td>
		<td width="85%" class="t_value" colspan="3" class="checkbox-interbal">
			<span id="lgQuota" <?php echo $lgQuota ?> >
			<?php
			$chk_val = explode(":", $oper_info['card_quotabase']);
			for($ii=0; $ii<count($chk_val); $ii++){
				$chkbox[$chk_val[$ii]] = "checked";
			}
			for($i=2; $i<=12; $i++) {
			?>
			<label class="checkbox-inline checkbox-width">
				<input type="checkbox" name="card_quotabase[]" value="<?php echo $i ?>" <?php echo $chkbox[$i] ?>><?php echo $i ?>개월
			</label>
			<?php } ?>
			<p></p>
			<?php
			$chk_val = explode(":", $oper_info['card_quotabase']);
			if($chk_val2) {
				for($jj=0; $jj<count($chk_val2); $jj++){
					$chkbox[$chk_val[$jj]] = "checked";
				}
			}
			for($j=13; $j<=24; $j++) {
				
			?>
			<label class="checkbox-inline checkbox-width">
				<input type="checkbox" name="card_quotabase[]" value="<?php echo $j ?>" <?php echo $chkbox[$j] ?>><?php echo $j ?>개월
			</label>
			<?php } ?>
			<!-- <br>예) 0:2:3:4:5:6:7:8:9:10:11:12 (0:일시불, 2~12개월) -->
			</span>
			<span id="kcpQuota" <?php echo $kcpQuota ?>>
				<input name="card_quotabase2" id="card_quotabase" value="<?php echo $oper_info['card_quotabase'] ?>" type="text" size="7" class="input Onum"> 개월 까지 할부 설정
			<!-- <br>예) 일시불은 기본적으로 표시, 숫자로만 표시 3인경우 (일시불, 2~3개월) -->
			</span>
			<span id="iniQuota" <?php echo $iniQuota ?>>

			<?php
			$chk_val = explode(":", $oper_info['card_quotabase']);
			for($ii=0; $ii<count($chk_val); $ii++){
				$chkbox[$chk_val[$ii]] = "checked";
			}
			for($i=2; $i<=12; $i++) {
			?>
			<label class="checkbox-inline checkbox-width">
				<input type="checkbox" name="card_quotabase3[]" value="<?php echo $i ?>" <?php echo $chkbox[$i] ?>><?php echo $i ?>개월
			</label>
			<?php } ?>
			<p></p>
			<?php
			$chk_val2 = explode(":", $oper_info['card_quotabase']);
			for($jj=0; $jj<count($chk_val2); $jj++){
				$chkbox[$chk_val[$jj]] = "checked";
			}
			for($j=13; $j<=24; $j++) {
				
			?>
			<label class="checkbox-inline checkbox-width">
				<input type="checkbox" name="card_quotabase3[]" value="<?php echo $j ?>" <?php echo $chkbox[$j] ?>><?php echo $j ?>개월
			</label>
			<?php } ?>

			<!-- <br>예) 일시불은 기본적으로 표시, 개월수를 : 로 구분 (2:3:4) -->
			</span>
		</td>
	</tr>

	<tr>
		<td width="15%" class="t_name">에스크로 사용여부</td>
		<td width="85%" class="t_value" colspan="3">
			<span style="vertical-align: middle"><input name="pay_escrow" value="Y" type="radio" <?php if($oper_info['pay_escrow'] == "Y") echo "checked"; ?> ></span> 사용함
			<span style="vertical-align: middle"><input name="pay_escrow" value="N" type="radio" <?php if($oper_info['pay_escrow'] == "N") echo "checked"; ?> ></span> 사용안함
		</td>
	</tr>
	<tr id="lgEscrow" <?php echo $lgEscrow ?>>
		<td width="15%" class="t_name">에스크로 수신 URL</td>
		<td width="85%"  style="padding:4px" colspan="3">
			<p>※ 에스크로 처리결과를 자동으로 처리시 <strong><font color="#3C75AB">'상점관리자 > 상점정보관리 > 시스템 연동정보 에스크로 처리결과 시스템 연동주소'</font></strong> 부분에 주소를 넣어주시기 바랍니다.</p>
			<p>※ 실제 연동처리를 하더라도 PG상점관리자에서 정상적으로 처리유무를 확인하시기 바랍니다.</p>
			<table width="100%" border="0" cellspacing="1" cellpadding="1" class="t_style">
				<tr>
					<td class="t_name" width="150">수신 URL</td>
					<td class="t_value"><?php echo SSL ?><?php echo $_SERVER['HTTP_HOST'] ?>/twcenter/product/dacom/escrow_save.php</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr id="kcpEscrow" <?php echo $kcpEscrow ?>>
		<td width="15%" class="t_name">에스크로 수신 URL</td>
		<td width="85%" class="t_value" style="padding:4px" colspan="3">
			<table width="100%" border="0" cellspacing="1" cellpadding="1" class="t_style">
				<tr>
					<td class="t_name" width="150">수신 URL</td>
					<td class="t_value"><?php echo SSL ?><?php echo $_SERVER['HTTP_HOST'] ?>/twcenter/product/nhnkcp/mod_escw/pp_cli_hub.php</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr id="iniEscrow" <?php echo $iniEscrow ?>>
		<td width="15%" class="t_name">에스크로 수신 URL</td>
		<td width="85%" class="t_value" style="padding:4px" colspan="3">
			<table width="100%" border="0" cellspacing="1" cellpadding="1" class="t_style">
				<tr>
					<td class="t_name" width="150">수신 URL</td>
					<td class="t_value"><?php echo SSL ?><?php echo $_SERVER['HTTP_HOST'] ?>/twcenter/product/inicis/escrow_save.php</td>
				</tr>
			</table>
		</td>
	</tr>
	<!--tr id="tossEscrow" <?php echo $tossEscrow ?>>
		<td width="15%" class="t_name">에스크로 수신 URL</td>
		<td width="85%" class="t_value" style="padding:4px" colspan="3">
			<table width="100%" border="0" cellspacing="1" cellpadding="1" class="t_style">
				<tr>
					<td class="t_name" width="150">수신 URL</td>
					<td class="t_value"><?php echo SSL ?><?php echo $_SERVER['HTTP_HOST'] ?>/twcenter/product/toss/escrow_save.php</td>
				</tr>
			</table>
		</td>
	</tr-->

	<tr id="lgViraccount" <?php echo $lgViraccount ?>>
		<td width="15%" class="t_name">가상계좌 수신 URL</td>
		<td width="85%" style="padding:4px" colspan="3">
			<p>※ 가상계좌(무통장) 입금통보를 자동으로 처리시 <strong><font color="#3C75AB">'상점관리자 > 상점정보관리 > 시스템 연동정보 연동주소'</font></strong> 부분에 주소를 넣어주시기 바랍니다.</p>
			<p>※ 실제 연동처리를 하더라도 PG상점관리자에서 정상적으로 처리유무를 확인하시기 바랍니다.</p>
			<table width="100%" border="0" cellspacing="1" cellpadding="1" class="t_style">
				<tr>
					<td class="t_name" width="150">수신 URL</td>
					<td class="t_value"><?php echo SSL ?><?php echo $_SERVER['HTTP_HOST'] ?>/twcenter/product/dacom/order_update_vir.php</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr id="kcpViraccount" <?php echo $kcpViraccount ?>>
		<td width="15%" class="t_name">가상계좌 수신 URL</td>
		<td width="85%" class="t_value" style="padding:4px" colspan="3">
			<table width="100%" border="0" cellspacing="1" cellpadding="1" class="t_style">
				<tr>
					<td class="t_name" width="150">수신 URL</td>
					<td class="t_value"><?php echo SSL ?><?php echo $_SERVER['HTTP_HOST'] ?>/twcenter/product/nhnkcp/common_return.php</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr id="iniViraccount" <?php echo $iniViraccount ?>>
		<td width="15%" class="t_name">가상계좌 수신 URL</td>
		<td width="85%" class="t_value" style="padding:4px" colspan="3">
			<p>※ 가상계좌(무통장) 입금통보를 자동으로 처리시 <strong><font color="#3C75AB">'상점관리자 > 상점정보관리 > 결제수단정보 > 가상계좌 > 입금통보URL'</font></strong> 부분에 주소를 넣어주시고 <font color="#3C75AB">통보전문</font>을 <font color="#3C75AB">URL수신사용(일반)</font>으로 설정바랍니다.</p>
			<p>※ 실제 연동처리를 하더라도 PG상점관리자에서 정상적으로 처리유무를 확인하시기 바랍니다.</p>
			<table width="100%" border="0" cellspacing="1" cellpadding="1" class="t_style">
				<tr>
					<td class="t_name" width="150">수신 URL</td>
					<td class="t_value"><?php echo SSL ?><?php echo $_SERVER['HTTP_HOST'] ?>/twcenter/product/inicis/order_update_vir.php</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr id="tossViraccount" <?php echo $tossViraccount ?>>
		<td width="15%" class="t_name">가상계좌(웹훅) 수신 URL</td>
		<td width="85%" class="t_value" style="padding:4px" colspan="3">
		※ 가상계좌(무통장) 입금통보를 자동으로 처리시 '토스페이먼츠 개발자센터 > 웹훅 > 웹훅 등록하기 부분에 아래 주소를 넣어주시기 바랍니다. (이벤트 타입 - DEPOSIT_CALLBACK 체크)<br/>
		※ 실제 연동처리를 하더라도 PG상점관리자에서 정상적으로 처리유무를 확인하시기 바랍니다.
			<table width="100%" border="0" cellspacing="1" cellpadding="1" class="t_style">
				<tr>
					<td class="t_name" width="150">수신 URL</td>
					<td class="t_value"><?php echo SSL ?><?php echo $_SERVER['HTTP_HOST'] ?>/twcenter/product/toss/order_update_vir.php</td>
				</tr>
			</table>
		</td>
	</tr>

	<?php
	if($wiz_admin['designer'] == "Y") {
	?>
	<tr>
		<td width="15%" class="t_name">현금영수증 신청</td>
		<td width="35%" class="t_value">
			<span style="vertical-align: middle"><input name="cash_receipts_use" value="Y" type="radio" <?php if($oper_info['cash_receipts_use'] == "Y") echo "checked"; ?>></span> 사용
			<span style="vertical-align: middle"><input name="cash_receipts_use" value="N" type="radio" <?php if($oper_info['cash_receipts_use'] == "N") echo "checked"; ?>></span> 사용안함
		</td>
		<td width="15%" class="t_name">현금영수증 발급방법</td>
		<td width="35%" class="t_value">
			<span style="vertical-align: middle"><input name="receipts_issue" value="A" type="radio" <?php if($oper_info['receipts_issue'] == "A") echo "checked"; ?>></span> 자동발급
			<span style="vertical-align: middle"><input name="receipts_issue" value="M" type="radio" <?php if($oper_info['receipts_issue'] == "M") echo "checked"; ?>></span> 관리자 발급
			<p id="kcp_receipts_tip" style="<?php if($oper_info['pay_agent']!="KCP") echo "display:none;"; ?>">※ 현금영수증 자동 등록을 사용하시려면 사전에 "가맹점 관리자 페이지 > 결제관리 > 현금영수증" 에서 현금영수증 사용 등록을 해주셔야 합니다 . 현금영수증 사용 옵션 "자진발급 자동 등록, 구매자 요청등록, 사용 안 함" 중 "구매자 요청등록"이 설정되어 있어야 합니다.</p>
		</td>
	</tr>
	<tr>
		<td width="15%" class="t_name">주문취소 방법</td>
		<td width="85%" class="t_value" colspan="3">
			<span style="vertical-align: middle"><input name="ord_cancel_type" value="M" type="radio" <?php if($oper_info['ord_cancel_type'] == "M") echo "checked"; ?>></span> 관리자 직접 취소
			<span style="vertical-align: middle"><input name="ord_cancel_type" value="C" type="radio" <?php if($oper_info['ord_cancel_type'] == "C") echo "checked"; ?>></span> 고객 직접 취소
		</td>
	</tr>
	<?php } ?>
</table>

<?php if($site_info['easypay_use'] == "Y") { ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 간편결제 사용</td>
	</tr>
</table>
<table width="100%" border="0" cellpadding="5" cellspacing="1" class="t_style">
	<tr>
		<td width="15%" class="t_name">간편결제</td>
		<td width="85%" class="t_value" style="padding:4px" colspan="3">
			<?php if($oper_info['kakao_pay_use'] == "Y" && !empty($oper_info['kakao_mid'])) { ?>
			<span style="vertical-align: middle">
				<img src="../image/payment_icon_yellow_small.png"><input type="checkbox" name="easy_pay_method[]" value="KK" checked readonly class="nocheck">
			</span>카카오페이
			<?php } ?>
		</td>
	</tr>
</table>
<?php } ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 은행계좌번호(무통장)</td>
	</tr>
</table>
<table width="100%" border="0" cellpadding="5" cellspacing="1" class="t_style">
	<tr>
		<td width="15%" class="t_name">은행계좌번호 <br>
			<input type="button" value="은행계좌번호등록" class="base_btm reg" onclick="accIns()">
			<?php
			if($wiz_admin['designer'] == 'Y' && $oper_info['bankda_use'] == 'Y') {
			?>
			<!-- <input type="button" value="뱅크다계좌추가" class="base_btm reg" onClick="bankda()"> -->
			<?php
			}
			?>
		
		</td>
		<td class="t_value" colspan="3" style="padding:4px;">

			<table width="100%" border="0" cellspacing="1" cellpadding="1" class="t_style">
				<tr>
					<td height="25" align="center" class="t_name">은행명</td>
					<td align="center" class="t_name">계좌번호</td>
					<td align="center" class="t_name">예금주</td>
					<td align="center" class="t_name">기능</td>
				</tr>
				<?php
				$sql = "
					select idx
						 , bkname
						 , bkacctholer
						 , bkacctno
						 , bkacctno2
					  from bank_account 
					 order by wdate desc
				";
				$res = query($sql);
				while($row = sql_fetch_arr($res)) {
				?>
				<tr>
					<td height="20" align="center" class="t_value"><?php echo $row['bkname'] ?></td>
					<td align="center" class="t_value"><?php echo $row['bkacctno2'] ?></td>
					<td align="center" class="t_value"><?php echo $row['bkacctholer'] ?></td>
					<td align="center" class="t_value" height="25">
						<input type="button" value="수정" class="base_btm blue2" onClick="accMod('<?php echo $row['idx'] ?>')"> 
						<input type="button" value="삭제" class="base_btm reg" onClick="accDel('<?php echo $row['idx'] ?>','<?php echo $row['bkacctno'] ?>')">
					</td>
				</tr>
				<?php
				//	}
				}
				?>
			</table>
		</td>
	</tr>
</table>
<br>
<a name="del">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 배송업체정보</td>
	</tr>
</table>
<?php
//if($oper_info['deliveryType'] == "O"){
?>
<!-- <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td class="t_name" width="15%">택배사</td>
		<td class="t_value">
			<?php
			$del_com_str = "대한통운,로젠택배,우체국택배,한진택배,현대택배,KG로지스택배,직접입력";
			$del_com_list = explode(",", $del_com_str);
			?>

			<select name="del_com" class="select">
			<?php for($ii = 0; $ii < count($del_com_list); $ii++) { ?>
				<option value="<?php echo $del_com_list[$ii] ?>" <?php if(!strcmp(trim($oper_info['del_com']), $del_com_list[$ii])) echo "selected" ?>><?php echo $del_com_list[$ii] ?></option>
			<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="t_name">배송추적URL</td>
		<td class="t_value" style="padding:5px 0 5px 10px">
			<input name="del_trace" value="<?php echo $oper_info['del_trace'] ?>" type="text" size="80" class="input"><br/>
			1. 대한통운 https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=<br/>
			2. 로젠택배 http://www.ilogen.com/d2d/delivery/invoice_search_popup.jsp?viewType=type1&invoiceNum=<br/>
			3. 우체국택배 http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal?sid1=<br/>
			4. 한진택배 http://www.hanjinexpress.hanjin.net/customer/plsql/hddcw07.result?wbl_num=<br/>
			5. 현대택배 http://www.hydex.net/ehydex/jsp/home/distribution/tracking/tracingView.jsp?InvNo=<br/>
			6. 로지스택배 http://www.kglogis.co.kr/delivery/delivery_result.jsp?item_no=
		</td>
	</tr>
</table> -->
<? // } else { ?>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" height="25" align="center" class="t_name">배송업체명 
		<input type="button" value="배송업체추가" class="base_btm reg" onClick="delivery_com()">
		<td width="10%" align="center" class="t_name">업체이용유무</td>
		<td width="15%" align="center" class="t_name">연락처</td>
		<td align="center" class="t_name">배송추적URL</td>
		<td width="%" align="center" class="t_name">기능</td>
	</tr>
	<?php
	$sql = "select * from wiz_delivery_company ";
	$result = query($sql);
	while($row = sql_fetch_arr($result)){
		if($row['del_com2'] == "Y") 
			 $del_use = "<font color='red'>이용</font>";
		else $del_use = "";
	?>
	<tr>
		<td align="center" height="35"><?php echo $row['del_com'] ?></td>
		<td align="center"><?php echo $del_use ?></td>
		<td align="center"><?php echo $row['del_phone'] ?></td>
		<td style="padding:0 0 0 10px"><a href="<?php echo $row['del_trace'] ?>" target="_blank"><?php echo $row['del_trace'] ?></a></td>
		<td align="center">
			<img src="../image/btn_edit_s.gif" style="cursor:pointer" onClick="delMod('<?php echo $row['idx'] ?>')" align="absmiddle">
			<img src="../image/btn_delete_s.gif" style="cursor:pointer" onClick="delDel('<?php echo $row['idx'] ?>')" align="absmiddle">
		</td>
	</tr>
	<?php
	}
	?>

</table>
<?// } ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 기본 배송정책</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">배송정책</td>
		<td width="85%" class="t_value">
			<select name="del_method" id="del_method_type" class="select">
			<?php foreach($_del_type AS $val){ ?>
				<option value="<?php echo $val[0] ?>" <?php if($oper_info['del_method'] == $val[0]) echo "selected";?>><?php echo $val[1] ?></option>
			<?php } ?>
			</select>
	</tr>

	<tr id="del_method_DC" style="display:none">
		<td class="t_name">배송비 상세설정</td>
		<td class="t_value">
			<input name="del_fixprice" type="text" value="<?php echo $oper_info['del_fixprice'] ?>" class="input">원</td>
	</tr>
	<tr id="del_method_DD" style="display:none">
		<td class="t_name">배송비 상세설정</td>
		<td class="t_value">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<input type="text" name="del_staprice" value="<?php echo $oper_info['del_staprice'] ?>" class="input">
					</td>
					<td>&nbsp;이상 구매시 <input type="text" name="del_staprice2" value="<?php echo $oper_info['del_staprice2'] ?>" class="input"> 원</td>
				</tr>
				<tr>
					<td></td>
					<td>&nbsp;미만 구매시 <input type="text" name="del_staprice3" value="<?php echo $oper_info['del_staprice3'] ?>" class="input"> 원</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="t_name">지역할증</td>
		<td class="t_value" style="padding:4px">
			<table width="99%" border="0" cellspacing="1" cellpadding="1" class="t_style">
				<tr>
					<td width="25%" height="25" align="center" class="t_name">우편번호 범위</td>
					<td align="center" class="t_name">상세주소 범위</td>
					<td width="15%" align="center" class="t_name">추가할증료</td>
				</tr>
				<tr>
					<td class="t_value" align="center">
						<input name="del_extrapost1" type="text" value="<?php echo $oper_info['del_extrapost1'] ?>" class="input" size="5" style="text-align:center">  <input type="button" value="우편번호" class="base_btm reg" onclick="postSearch('1')"> 부터&nbsp;
						<input name="del_extrapost12" type="text" value="<?php echo $oper_info['del_extrapost12'] ?>" class="input" size="5" style="text-align:center"> <input type="button" value="우편번호" class="base_btm reg" onclick="postSearch('2')"> 까지
					</td>
					<td class="t_value" align="center">
						<input type="text" name="del_extrapost1_addr" value="<?php echo $oper_info['del_extrapost1_addr'] ?>" class="input bmar_3" size="45" > 부터&nbsp;
						<input type="text" name="del_extrapost12_addr" value="<?php echo $oper_info['del_extrapost12_addr'] ?>" class="input" size="45"> 까지</td>
					<td class="t_value" align="center">
						<input name="del_extraprice1" type="text" value="<?php echo $oper_info['del_extraprice1'] ?>" class="input" size="10" style="text-align:center"> 원
					</td>
				</tr>
				<tr>
					<td class="t_value" align="center">
						<input name="del_extrapost2" type="text" value="<?php echo $oper_info['del_extrapost2'] ?>" class="input" size="5" style="text-align:center"> <input type="button" value="우편번호" class="base_btm reg" onclick="postSearch('3')"> 부터
						<input name="del_extrapost22" type="text" value="<?php echo $oper_info['del_extrapost22'] ?>" class="input" size="5" style="text-align:center"> <input type="button" value="우편번호" class="base_btm reg" onclick="postSearch('4')"> 까지
					</td>
					<td class="t_value" align="center"><input type="text" name="del_extrapost2_addr" value="<?php echo $oper_info['del_extrapost2_addr'] ?>" class="input bmar_3" size="45"> 부터&nbsp; <input type="text" name="del_extrapost22_addr" value="<?php echo $oper_info['del_extrapost22_addr'] ?>" class="input" size="45"> 까지</td>
					<td class="t_value" align="center">
						<input name="del_extraprice2" type="text" value="<?php echo $oper_info['del_extraprice2'] ?>" class="input" size="10" style="text-align:center"> 원
					</td>
				</tr>
				<tr>
					<td class="t_value" align="center">
						<input name="del_extrapost3" type="text" value="<?php echo $oper_info['del_extrapost3'] ?>" class="input" size="5" style="text-align:center"> <input type="button" value="우편번호" class="base_btm reg" onclick="postSearch('5')"> 부터&nbsp;
						<input name="del_extrapost32" type="text" value="<?php echo $oper_info['del_extrapost32'] ?>" class="input" size="5" style="text-align:center"> <input type="button" value="우편번호" class="base_btm reg" onclick="postSearch('6')"> 까지
					</td>
					<td class="t_value" align="center">
						<input type="text" name="del_extrapost3_addr" value="<?php echo $oper_info['del_extrapost3_addr'] ?>" class="input bmar_3" size="45"> 부터&nbsp;
						<input type="text" name="del_extrapost32_addr" value="<?php echo $oper_info['del_extrapost32_addr'] ?>" class="input" size="45"> 까지</td>
					<td class="t_value" align="center">
						<input name="del_extraprice3" type="text" value="<?php echo $oper_info['del_extraprice3'] ?>" class="input" size="10" style="text-align:center"> 원
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div class="helpTip2">
	<h4>도움말</h4>
	<div class="content">
		<div class="explain">
		  - 배송료를 4가지 형태로 구분하며 각 상황별 배송료 설정을 합니다.<br>
		  - 각지역별로 할증 배송료를 설정할수 있습니다. ex) 제주특별자치도 제주시 한경면인 경우, 우편번호 63008 전체 2,000원이 부과됩니다.
		</div>
	</div>
</div>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 상품별 배송정책</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">무료배송 상품</td>
		<td width="85%" class="t_value">
			<span style="vertical-align: middle"><input type="radio" name="del_prd" value="DA" <?php if($oper_info['del_prd'] == "DA") echo "checked"; ?>></span> 무료배송 상품과 함께 주문할 경우, 전체 배송비를 무료로합니다.<br>
			<span style="vertical-align: middle"><input type="radio" name="del_prd" value="DB" <?php if($oper_info['del_prd'] == "DB") echo "checked"; ?>></span> 무료배송 상품과 함께 주문할 경우, 무료배송 상품을 제외한 상품은 배송비를 부과합니다.
		</td>
	</tr>
	<tr>
		<td class="t_name">상품별 배송비</td>
		<td class="t_value">
			<span style="vertical-align: middle"><input type="radio" name="del_prd2" value="DA" <?php if($oper_info['del_prd2'] == "DA") echo "checked"; ?>></span> 상품을 2개 이상 주문할 경우, 상품별 배송비와 기본 배송비를 합산한 금액을 배송비로 지정합니다.<br>
			<span style="vertical-align: middle"><input type="radio" name="del_prd2" value="DB" <?php if($oper_info['del_prd2'] == "DB") echo "checked"; ?>></span> 상품을 2개 이상 주문할 경우, 상품별 배송비와 기본 배송비 중 더 큰 배송비를 전체 배송비로 지정합니다.
		</td>
	</tr>
</table>
<?php
if($wiz_admin['designer'] == 'Y') {
?>
<!-- <br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 상품 배송비 관리</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">일괄적용여부</td>
		<td width="85%" class="t_value" colspan="3">
			<span style="vertical-align: middle"><input type="checkbox" name="del_batch_use" value="Y" <?php if($oper_info['del_batch_use'] == "Y") echo "checked"; ?>></span>일괄적용
		</td>
	</tr>
</table> -->
<?php } ?>
<br>
<a name="res">
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 적립금정보</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td class="t_name">사용여부</td>
		<td class="t_value" colspan="3">
			<span style="vertical-align: middle"><input type="radio" name="reserve_use" value="Y" <?php if($oper_info['reserve_use'] == "Y") echo "checked"; ?> onclick="recomShow('Y')"></span>사용함
			<span style="vertical-align: middle"><input type="radio" name="reserve_use" value="N" <?php if($oper_info['reserve_use'] == "N") echo "checked"; ?> onclick="recomShow('N')"></span>사용안함</td>
	</tr>
	<tr>
		<td width="15%" class="t_name">회원가입 적립금</td>
		<td width="55%" class="t_value" colspan="3"><input name="reserve_join" type="text" value="<?php echo $oper_info['reserve_join'] ?>" class="input"></td>
	</tr>
	<tr>
		<td width="15%" class="t_name">최소사용 적립금</td>
		<td width="35%" class="t_value"><input name="reserve_min" type="text" value="<?php echo $oper_info['reserve_min'] ?>" class="input"></td>
		<td width="15%" class="t_name">1회 최대사용 적립금</td>
		<td width="35%" class="t_value">
			<input name="reserve_max" type="text" id="id_reserve_max" value="<?php echo $oper_info['reserve_max'] ?>" class="input" <?php echo $limit_disabled ?>>&nbsp;
			<input type="checkbox" name="unLimited" id="id_unLimited" value="Y" <?php if($oper_info['unLimited'] == 'Y') echo "checked";?>> 제한없음
		</td>
	</tr>
	<tr>
		<td class="t_name">상품구매시 적립금</td>
		<td class="t_value"><input name="reserve_buy" type="text" value="<?php echo $oper_info['reserve_buy'] ?>" class="input"> %</td>
		<td class="t_name">적립금 일괄적용</td>
		<td class="t_value">
			<input name="reserve_per" type="text" value="<?php echo $oper_info['reserve_per'] ?>" class="input" size="10"> % &nbsp;
			<input type="button" value="적용" class="base_btm reg" onclick="setReserve();">
		</td>
	</tr>
</table>
<div class="helpTip2">
	<h4>도움말</h4>
	<div class="content">
		<div class="explain">
		- 상품 구입시 적립금 누적/사용 , 회원가입시, 추천인인경우 등 적립금 사용이 가능합니다.<br />
		- 상품 등록시 판매금액에 작성한 퍼센트를 적용하여 적립금이 자동계산됩니다.<br />
		- 적립금 일괄적용 : 현재 등록되어 있는 상품에 적립금을 등록한 %로 다시 적용합니다.<br />
		- 추천인 적립금 제도는 회원가입시 추천인 아이디를 등록함으로서 회원이 주문을 했을시 입금완료 시에 추천인에게 적립금이 자동적립됩니다.
		</div>
	</div>
</div>
<div id="recomShow"<?  if($oper_info['reserve_use'] != "Y") echo 'style="display:none"';?>>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 추천인 적립금제도</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">사용여부</td>
		<td width="35%" class="t_value">
			<span style="vertical-align: middle"><input type="radio" name="recom_use" value="Y" <?php if($oper_info['recom_use'] == "Y") echo "checked"; ?>></span>사용함
			<span style="vertical-align: middle"><input type="radio" name="recom_use" value="N" <?php if($oper_info['recom_use'] == "N") echo "checked"; ?>></span>사용안함</td>
		<td width="15%" class="t_name">추천인 적립금</td>
		<td width="35%" class="t_value"><input name="reserve_recom" type="text" value="<?php echo $oper_info['reserve_recom'] ?>" class="input"></td>
	</tr>
</table>
<div class="helpTip2">
	<h4>도움말</h4>
	<div class="content">
		<div class="explain">
		- 추천인 적립금 제도는 회원가입시 추천인 아이디를 등록함으로서 회원이 주문을 했을시 입금완료 시에 추천인에게 적립금이 자동적립됩니다.<br>
		- 추천인 적립금은 1회성이 아닌 추천받은사람이 상품구매후 입금완료처리 될때마다 적립금이 자동적립됩니다.<br>
		- 추천인 적립금은 추천한 사람만 적립됩니다.
		</div>
	</div>
</div>
<br>
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 상품평 설정</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">사용여부</td>
		<td width="35%" class="t_value">
			<span style="vertical-align: middle"><input type="radio" name="review_usetype" value="Y" <?php if($review_info['usetype'] == "Y") echo "checked"; ?>></span>사용함
			<span style="vertical-align: middle"><input type="radio" name="review_usetype" value="N" <?php if($review_info['usetype'] == "N") echo "checked"; ?>></span>사용안함
		</td>
		<td width="15%" class="t_name">작성권한</td>
		<td width="35%" class="t_value">
			<select name="review_wpermi" class="select">
				<option value="">전체</option>
				<?php echo level_list(); ?>
				<option value="-1">구매회원</option>
				<option value="0">관리자</option>
			</select>
		</td>
	</tr>
</table>
<script language="javascript">
<!--
wpermi = document.frm.review_wpermi;
for(ii=0; ii<wpermi.length; ii++){
	if(wpermi.options[ii].value == "<?php echo $review_info['wpermi'] ?>")
	wpermi.options[ii].selected = true;
}
-->
</script>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 상품Q&A 설정</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">사용여부</td>
		<td width="35%" class="t_value">
			<span style="vertical-align: middle"><input type="radio" name="qna_usetype" value="Y" <?php if($qna_info['usetype'] == "Y") echo "checked"; ?>></span>사용함
			<span style="vertical-align: middle"><input type="radio" name="qna_usetype" value="N" <?php if($qna_info['usetype'] == "N") echo "checked"; ?>></span>사용안함
		</td>
		<td width="15%" class="t_name">작성권한</td>
		<td width="35%" class="t_value">
			<select name="qna_wpermi" class="select">
				<option value="">전체</option>
				<?=level_list();?>
				<option value="0">관리자</option>
			</select>
		</td>
	</tr>
</table>
<script language="javascript">
<!--
wpermi = document.frm.qna_wpermi;
for(ii=0; ii<wpermi.length; ii++){
	if(wpermi.options[ii].value == "<?=$qna_info['wpermi']?>")
		wpermi.options[ii].selected = true;
}
-->
</script>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 세금계산서 설정</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">사용여부</td>
		<td width="35%" class="t_value">
			<span style="vertical-align: middle"><input type="radio" name="tax_use" value="Y" <?php if($oper_info['tax_use'] == "Y") echo "checked"; ?>></span>사용함
			<span style="vertical-align: middle"><input type="radio" name="tax_use" value="N" <?php if($oper_info['tax_use'] == "N") echo "checked"; ?>></span>사용안함
		</td>
		<td width="15%" class="t_name">발급시점</td>
		<td width="35%" class="t_value">
			<span style="vertical-align: middle"><input type="radio" name="tax_status" value="OY" <?php if($oper_info['tax_status'] == "OY") echo "checked"; ?>></span>결제완료
			<span style="vertical-align: middle"><input type="radio" name="tax_status" value="DC" <?php if($oper_info['tax_status'] == "DC") echo "checked"; ?>></span>배송완료
		</td>
	</tr>
<?
		/* 
			바로빌 연동 설정 Start
			2020-03-18 update 김나연 
		*/
?>
	<tr>
		<td class="t_name">세금계산서 연동여부</td>
		<td class="t_value">
			<input name="tax_api" value="Y" <?php if($oper_info['tax_api']=="Y") echo"checked";?> type="radio" > 사용함
			<input name="tax_api" value="N" <?php if($oper_info['tax_api']=="N") echo"checked";?> type="radio" > 사용안함
		</td>
		<td class="t_name">세금계산서 연동방법</td>
		<td class="t_value">
			<input name="tax_type" value="T" <?php if($oper_info['tax_type']=="T") echo"checked";?> type="radio" > 테스트
			<input name="tax_type" value="R" <?php if($oper_info['tax_type']=="R") echo"checked";?> type="radio" > 실제연동
		</td>
	</tr>
	<tr>
		<td class="t_name">세금계산서 연동인증키</td>
		<td class="t_value"  colspan="3">
			<input name="tax_certkey" value="<?=$oper_info['tax_certkey']?>" type="text" size="50" class="input">
			&nbsp;*세금계산서 사용시 <a href="https://www.barobill.co.kr//">바로빌</a> 에서 가입하시고 입력하시면 됩니다.
		</td>
	</tr>
	<tr>
		<td class="t_name">세금계산서 ID</td>
		<td class="t_value"  colspan="3">
			<input name="tax_id" value="<?=$oper_info['tax_id']?>" type="text" class="input">
			&nbsp;*세금계산서 사용시 <a href="http://anywiz.freebill.co.kr/">프리빌</a> 에서 가입하시고 입력하시면 됩니다.
		</td>
	</tr>
<?
		/* 
			바로빌 연동 설정 End
			2020-03-18 update 김나연 
		*/
?>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 관련상품 사용여부</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">사용여부</td>
		<td width="85%" class="t_value">
			<span style="vertical-align: middle"><input type="radio" name="prdrel_use" value="Y" <?php if($oper_info['prdrel_use'] == "Y") echo "checked"; ?>></span>사용함
			<span style="vertical-align: middle"><input type="radio" name="prdrel_use" value="N" <?php if($oper_info['prdrel_use'] == "N") echo "checked"; ?>></span>사용안함
		</td>
	</tr>
</table>

<br>


<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub" width="50%"><img src="../image/ics_tit.gif"> 리스트 진열여부</td>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 돋보기 효과 </td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">사용여부</td>
		<td width="35%" class="t_value">
			<span style="vertical-align: middle"><input type="radio" name="chk_prdshow" value="Y" <?php if($oper_info['chk_prdshow'] == "Y") echo "checked"; ?>></span>사용함
			<span style="vertical-align: middle"><input type="radio" name="chk_prdshow" value="N" <?php if($oper_info['chk_prdshow'] == "N") echo "checked"; ?>></span>사용안함
		</td>
		<td width="15%" class="t_name">사용여부</td>
		<td width="35%" class="t_value">
			<span style="vertical-align: middle"><input type="radio" name="chk_readglass" value="Y" <?php if($oper_info['chk_readglass'] == "Y") echo "checked"; ?>></span>사용함
			<span style="vertical-align: middle"><input type="radio" name="chk_readglass" value="N" <?php if($oper_info['chk_readglass'] == "N") echo "checked"; ?>></span>사용안함
			<div class="sub_tit_alt2 tmar_5"> 상품 상세페이지에 적용됩니다.</div>
		</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 모바일 / PC 구매 체크</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">사용여부</td>
		<td width="85%" class="t_value">
			<span style="vertical-align: middle"><input type="radio" name="chk_connect_type" value="Y" <?php if($oper_info['chk_connect_type'] == "Y") echo "checked"; ?>></span>사용함
			<span style="vertical-align: middle"><input type="radio" name="chk_connect_type" value="N" <?php if($oper_info['chk_connect_type'] == "N") echo "checked"; ?>></span>사용안함

		</td>
	</tr>
</table>
<?php if(!strcmp($site_info['estimate_use'], "Y")) { ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 견적서정보</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">비고</td>
		<td width="85%" class="t_value" style="padding:4px">
			<textarea name="estimate_bigo" rows="5" cols="112" class="textarea" style="width:99%"><?=$site_info['estimate_bigo']?></textarea>
		</td>
	</tr>
</table>
<? } ?>

<br>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
	<tr>
		<td align="center">
			<input type="submit" value="확 인" class="base_btn reg">
			<!-- <img src="../image/btn_cancel_l.gif" style="cursor:pointer" onClick="history.go(-1);"> -->
		</td>
	</tr>
</table>
</form>
<?php if($wiz_admin['designer'] == 'Y' && $oper_info['bankda_use'] == 'Y') { ?>
<iframe id="frminfo" height="20%" frameBorder="0" name="frminfo" scrolling="no"></iframe>
<?php } ?>
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

<? include "../foot.php"; ?>