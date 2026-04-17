<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";

if($wiz_session['id'] == "") {
	error("로그인 후 이용하세요");
	exit;
}

$usid = $wiz_session['id'];

$sql = "
	SELECT *
	  FROM wiz_delivery_set
	 WHERE de_id='{$usid}'
	  ORDER BY basicdelivery DESC
";
$drs = query($sql);

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";
?>
<script>
function DeliveryDel(obj, obj2) {

	if (typeof(obj) == 'undefined') return false;

	var type     = 'd';

	if(confirm("삭제된 배송지는 복구할수 없습니다. 정말 삭제하시겠습니까?")) {

		var params = "";
			params += "ptype=save";
			params += "&type="+type;
			params += "&idx="+obj;
			params += "&bd="+obj2;

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
					document.location.replace("<?php echo $_SERVER['PHP_SELF']; ?>?ptype=list&uType=<?php echo $_GET['uType'] ?>&sid=<?php echo $_GET['sid'] ?>");
				}
			}
			,error: function (request, status, error) {
				console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
			}

		});

		return;
	} else {
		return;
	}
}

$(document).on("click", "#delivery_insert", function(){
	location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?ptype=input";
});
</script>

<div id="myDelivery">
	<?php
	$i = 0;
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

		$params = "";
	?>
	<dl>
		<dt><?php echo $re_name ?></dt>
		<dd class="txt">
			<?php echo $re_addr ?> <?php echo $re_addr2 ?><br/>
			<?php echo $re_hphone ?> / <?php echo $re_tphone ?>
		</dd>
		<dd class="btn">
			<?php echo $basic_lo ?>
			<input type="button" onClick="location.href='<?php echo $_SERVER["PHP_SELF"]; ?>?ptype=input&idx=<?php echo $idx ?>&type=u<?php echo $params ?>'" value="수정" class="modi"><input type="button" onClick="DeliveryDel(<?php echo $idx ?>,'<?php echo $basicdelivery ?>')" value="삭제" class="delete">
		</dd>
	</dl>
	<?php 
		$i++;
	}

	if($i == 0){
	?>
	<!-- #### 추가된 배송지가 없을 경우 아래처럼 표시되어야 합니다.-->
	<div class="no_data">
		추가된 배송지가 없습니다.
	</div>
	<!--//-->
	<?php
	}
	?>
	<div class="btn_area">
		<input type="button" value="배송지 추가하기" class="btn_wb" id="delivery_insert"/>
	</div>
</div>