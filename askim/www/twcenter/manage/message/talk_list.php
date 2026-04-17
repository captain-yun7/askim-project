<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>
<?
$param = "slevel=".$slevel."&searchopt=".$searchopt."&searchkey=".$searchkey;
?>

<script>
	function talk_template_insert(){
		location.href='./talk_template_insert.php';
	}

	function talk_template_request(templateCode){
		if (confirm("검수요청을 진행하시겠습니까?") == true){    //확인
			location.href='./talk_template_request.php?templateCode='+templateCode;
		}else{
			return false;
		}
	}

	function talk_template_update(idx){
		location.href='./talk_template_update.php?idx='+idx;
	}

	function talk_template_delete(idx){
		if (confirm("정말 삭제하시겠습니까??") == true){    //확인
			location.href='./talk_template_save.php?mode=delete&idx='+idx;
		}else{   //취소
			return false;
		}
	}

	function talk_template_show(idx){
		if (confirm("템플릿을 미노출시키시겠습니까?\n미노출이후 복구시 호스팅업체에 문의하셔야합니다.") == true){
			location.href='./talk_template_save.php?mode=tmpshow&idx='+idx;
		}else{   //취소
			return false;
		}
	}

	function rejReason(templateCode){
		var url = "./talk_rej.php?templateCode="+templateCode;
		window.open(url, "", "width=450, height=400, scrollbars=yes, left=200, top=200");
	}
</script>


<?
$server = $site_info['alimtalk_temp_url'];
$senderKey = $site_info['alimtalk_senderkey'];
$custGubun = $site_info['alimtalk_custgubun'];
?>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif" align="absmiddle"></td>
		<td valign="bottom" class="tit">알림톡 템플릿 관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">알림톡 템플릿을 수정/삭제 관리합니다.</td>
	</tr>
</table>

<br>

 
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="10"></td></tr>
</table>

<table width="100%" border="0" cellspacing="10" cellpadding="8" bgcolor="9d9d9d" align="center">
	<tbody><tr>
		<td align="center">
			<table width="100%" border="0" cellspacing="0" cellpadding="6">
				<tbody><tr>
					<td><h3 class="check">Check !</h3></td>
				</tr>
				<tr>
					<td class="chk_alt">
	  - 템플릿 검수 요청 : 템플릿상태가 대기(R)이고 템플릿 검수상태가 등록(REG)인 경우에만 요청 가능합니다.
		<br>
		- 템플릿 수정 요청 : 템플릿상태가 대기(R)이고 템플릿 검수상태가 등록(REG) 또는 반려(REJ)인 경우에만 수정 가능합니다.
		<br>
		- 템플릿 삭제 요청 : 템플릿상태가 대기(R)이고 템플릿 검수상태가 승인(APR) 이 아닌 경우에만 삭제 가능합니다
					</td>
				</tr>
			</tbody></table>
		</td>
	</tr>
</tbody></table>


<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="20"></td></tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
			<input type="button" value="템플릿 등록" class="base_btn reg" onclick="talk_template_insert();">
		</td>
	</tr>
	<tr><td height="30"></td></tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="33.3%"></td>
		<td width="33.3%"></td>
		<td width="33.3%"></td>
	</tr>
	<tr valign="top">
	<?php
	$br_cnt = 1;
	$sql_talk = "
		select *
		  from  wiz_talk
		  where templateShow = 'Y'
		 order by templateCode asc
	";
	$result_talk = query($sql_talk) or error("sql error");

	while($row_talk = sql_fetch_arr($result_talk)){

		//템플릿 조회
		$url = $server."/api/v1/".$custGubun."/template";

		$post_data = "";
		$post_data .= "senderKey=".$senderKey;
		$post_data .= "&templateCode=".$row_talk['templateCode'];
		$ch = curl_init ();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt ($ch, CURLOPT_HTTPHEADER,array ('Content-Type: application/x-www-form-urlencoded;charset=UTF-8')); 
		$ch_result = curl_exec ($ch);
		$json_response = json_decode($ch_result, true);

		$t_inspectionStatus = $json_response['data']['inspectionStatus'];
		$t_status = $json_response['data']['status'];

		//echo $t_inspectionStatus." / ".$t_status."<br>";
		
	?>
		<td width="33.3%">
			<div class="template_style">
				<div class="title"><span>알림톡</span><?=$row_talk['templateName']?></div>
				<div class="message">
					<?php echo nl2br($row_talk['templateContent'])?>
				</div>
				<div class="info">
					<strong>검수 상태</strong> : <u><?=template_inspectionStatus($t_inspectionStatus)?></u>
					<?if($t_inspectionStatus=="REJ"){?>
					<span onclick="rejReason('<?=$row_talk['templateCode']?>')" style="cursor:pointer">[반려사유]</span>
					<?}?>
					<br>
					<strong>템플릿 상태</strong> : <?=template_status($t_status)?>
					<br>
					<strong>템플릿 코드</strong> : <?php echo $row_talk['templateCode']?>
					<br>				
				</div>
				<div class="btn_area">
				<?if($t_inspectionStatus=="REG" && $t_status=="R"){?>
					<span style="cursor:pointer" onclick="talk_template_request('<?=$row_talk['templateCode']?>')" class="template_btn t_check">검수요청</span>
				<?}?>
				<?if($t_inspectionStatus=="REG" && $t_status=="R" || $t_inspectionStatus=="REJ" && $t_status=="R"){?>
					<span style="cursor:pointer" onclick="talk_template_update('<?=$row_talk['idx']?>')" class="template_btn t_edit">수정</span>
				<?}?>
				<?if($t_inspectionStatus=="REG" && $t_status=="R" || $t_inspectionStatus=="REJ" && $t_status=="R" || $t_inspectionStatus=="REQ" && $t_status=="R"){?>
					<span style="cursor:pointer" onclick="talk_template_delete('<?=$row_talk['idx']?>')" class="template_btn t_del">삭제</span>
				<?}?>
					<span style="cursor:pointer" onclick="talk_template_show('<?=$row_talk['idx']?>')" class="template_btn t_check">숨기기</span>
				</div>
			</div>
		</td>
		<?php 
		if($br_cnt%3=="0"){
			echo "</tr><tr>";
		}
		?>
	<?php
		$br_cnt++;
	}
	?>
	</tr>
</table>
<? include "../foot.php"; ?>
