<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

if($_GET['ctl'] == 'p') {
	$_GET['uType'] = 's';
} else {
	$_GET['uType'] = $_GET['uType'];
}

if($wiz_session['id'] == "") error("로그인 후 이용하세요");

$usid = $wiz_session['id'];

$sql = "
	SELECT *
	  FROM wiz_delivery_set
	 WHERE de_id='{$usid}'
	  ORDER BY basicdelivery DESC
";
$drs = query($sql);
?>
<!DOCTYPE html>
<html lang="ko" xml:lang="ko">
<head>
<title>:: 배송지선택 ::</title>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="/comm/css/sub.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
<div id="myDelivery_pop">
	<h1>
		배송지 선택
		<a href="#" class="close" onClick="self.close();"><img src="/twcenter/product//image/id_check_close.gif" width="21" height="21" border="0"></a>
	</h1>
	<div class="cont">
		<?php
		while($drw = sql_fetch_arr($drs)) {

			$idx           = $drw['idx'];
			$re_name       = xss_clean($drw['re_name']);
			$re_addr       = xss_clean($drw['re_addr']);
			$re_addr2      = xss_clean($drw['re_addr2']);
			$re_hphone     = $drw['re_hphone'];
			$re_tphone     = $drw['re_tphone'];
			$basicdelivery = $drw['basicdelivery'];

			if($basicdelivery == "Y") {
				$basic_lo = '<p class="basic">기본배송지</p>';
			} else {
				$basic_lo = '';
			}

			$over_c = ($basicdelivery == "Y") ? "class='over'" : "";
		?>
		<script>
		var Selvalue = function(obj) {

			var uType = "<?php echo $_GET['uType'] ?>";
			var sid   = "<?php echo $_GET['sid'] ?>";

			$.ajax({
				type: "post"
				,url: "/twcenter/member/delivery_proc.php"
				,cache: false
				,async: false
				,data: {idx: obj, uType: uType, sid: sid, gType:"L"}
				,dataType: "json"
				,success: function (data) {
					console.log(data);
					var objData = String(JSON.stringify(data));
					var parsedJson = JSON.parse(objData);

					if(parsedJson.list != null) {

						var len = parsedJson.list.length;
						for(var i=0; i<len; i++){

							var basic_de = parsedJson.list[i].basic_de
							$("input[name='rece_name']",opener.document).val(parsedJson.list[i].re_name);
							$("input[name='rece_post']",opener.document).val(parsedJson.list[i].re_post);
							$("input[name='rece_address1']",opener.document).val(parsedJson.list[i].re_addr);
							$("input[name='rece_address2']",opener.document).val(parsedJson.list[i].re_addr2);
							$("select[name='rece_hphone']",opener.document).val(parsedJson.list[i].hp1);
							$("input[name='rece_hphone2']",opener.document).val(parsedJson.list[i].hp2);
							$("input[name='rece_hphone3']",opener.document).val(parsedJson.list[i].hp3);
							$("select[name='rece_tphone']",opener.document).val(parsedJson.list[i].tp1);
							$("input[name='rece_tphone2']",opener.document).val(parsedJson.list[i].tp2);
							$("input[name='rece_tphone3']",opener.document).val(parsedJson.list[i].tp3);
							if(basic_de == 'Y') {
								$("#adchg_Y",opener.document).prop("checked", true);
							} else {
								$("#adchg_Y",opener.document).prop("checked", false);
							}
							self.close();
						}

					} else {
						
						alert("선택하신 정보를 가져올수 없습니다.\n다시 시도해주세요.");
						self.close();

					}
				
				}
				,error: function (request, status, error) {
					console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
				}

			});

		};
		</script>
		<dl <?php echo $over_c ?>>
		<dt><?php echo $re_name ?></dt>
			<dd class="txt">
				<?php echo $re_addr ?> <?php echo $re_addr2 ?><br/>
				<?php echo $re_hphone ?> / <?php echo $re_tphone ?>
			</dd>
			<dd class="btn"><?php echo $basic_lo ?><input type="button" onClick="location.href='delivery_pop_input.php?idx=<?php echo $idx ?>&type=u&uType=<?php echo $_GET['uType'] ?>&sid=<?php echo $_GET['sid'] ?>'" value="수정" class="modi"><input type="button" value="선택" class="delete" onclick="Selvalue(<?php echo $idx ?>)"></dd>
		</dl>
		<?php } ?>
		<div class="btn_area">
			<input type="button" onClick="location.href='/twcenter/module/delivery_pop_input.php?uType=<?php echo $_GET['uType'] ?>&sid=<?php echo $_GET['sid'] ?>'" value="배송지 추가하기" class="btn_wb"/>
		</div>
	</div>
</div>
</body>
</html>