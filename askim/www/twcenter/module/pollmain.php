<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/pollmain_info.php";

// 설문조사 정보
$sql = "select * from wiz_poll where code = '$poll_code' and pollmain != 'N' order by idx desc";
$result = query($sql) or error("sql_error");
$poll_info = sql_fetch_arr($result);

$idx = $poll_info['idx'];
$subject = $poll_info['subject'];
$content = str_replace("\n", "<br>", strip_tags($poll_info['content']));
$polluse = $poll_info['polluse'];

$tdate = date('Y-m-d');
if($poll_info['apermi'] == "M" && $wiz_session['id'] == "") $polluse = "N";
if($poll_info['sdate'] > $tdate || $poll_info['edate'] < $tdate) $polluse = "N";

if(strcmp($polluse, "N")) $vote_btn = "<img src='$skin_dir/image/bt_main_vote.gif'onCLick=\"vote();\" style='cursor:pointer'>";
else $vote_btn = "<img src='$skin_dir/image/bt_main_vote.gif'onCLick=\"alert('설문이 종료되었습니다.');\" style='cursor:pointer'>";
$result_btn = "<img src='$skin_dir/image/bt_main_result.gif' onClick=\"document.location='".$purl."?ptype=view&idx=".$idx."'\" style='cursor:pointer'>";
?>
<script Language="JavaScript">
<!--

function readCookie(cookiename){
 var Found = false;

 cookiedata = document.cookie;
 if ( cookiedata.indexOf(cookiename) >= 0 ){
   Found = true;
 }
 return Found;
}

function setCookie( name, value, expiredays ){
 var todayDate = new Date();
 todayDate.setDate( todayDate.getDate() + expiredays );
 document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function vote(){

	if(!readCookie("wiz_vote<?=$idx?>")){

	var frm = document.pollFrm;
	var checkValue = "";
	<?
	for($ii=0;$ii<10;$ii++){
	?>
		if(frm.answer<?=$ii?> != null){
			var voteCheck = false;
			for(var i=0; i < frm.answer<?=$ii?>.length; i++){
			  if(frm.answer<?=$ii?>[i].checked == true){
			  	 voteCheck = true;
			     checkValue = checkValue + frm.qidx<?=$ii?>.value + ":" + frm.answer<?=$ii?>[i].value + "/";
			  }
			}
			if(voteCheck == false){
			  alert('질문에 대한 답변을 선택하세요.'); return;
			}
		}
	<?
	}
	?>
		var url = "<?=$purl?>?ptype=save&pidx=<?=$idx?>&checkValue=" + checkValue;
		document.location = url;
	  setCookie("wiz_vote<?=$idx?>", "true", 1);

	}else{
		alert('이미 설문에 참여하셨습니다.');
		return;
	}

}

//-->
</script>
<?php

echo "<form name='pollFrm' method='post' style='margin:0;'>";

// 제목, 내용, 투표하기 버튼, 결과보기 버튼 치환
$skin = str_replace("{SUBJECT}",$subject,$skin);
$skin = str_replace("{CONTENT}",$content,$skin);
$skin = str_replace("{SKIN_DIR}",$skin_dir,$skin);
$skin = str_replace("{VOTE_BTN}",$vote_btn,$skin);
$skin = str_replace("{VIEW_BTN}",$result_btn,$skin);

// 헤더,바디,풋터 자르기
$skin = str_replace("[/LOOP]","[LOOP]",$skin);
$skin = str_replace("[/LOOP2]","[LOOP2]",$skin);
list($header,$body,$footer) = explode("[LOOP]",$skin);
list($header2,$body2,$footer2) = explode("[LOOP2]",$body);

echo $header;

$no = 0;
$sql = "select * from wiz_polldata where pidx = '$idx' order by idx asc";
$result = query($sql);
while($row = sql_fetch_arr($result)){

	$total_count = $row['count01']+$row['count02']+$row['count03']+$row['count04']+$row['count05']+$row['count06']+$row['count07']+$row['count08']+$row['count09']+$row['count10'];
	if($total_count == 0) $total_count = 1;

	$answer_list[0][0] = $row['answer01'];
	$answer_list[0][1] = $row['count01'];
	$answer_list[0][2] = "count01";
	$answer_list[0][3] = round(($row['count01']/$total_count)*100,1);

	$answer_list[1][0] = $row['answer02'];
	$answer_list[1][1] = $row['count02'];
	$answer_list[1][2] = "count02";
	$answer_list[1][3] = round(($row['count02']/$total_count)*100,1);

	$answer_list[2][0] = $row['answer03'];
	$answer_list[2][1] = $row['count03'];
	$answer_list[2][2] = "count03";
	$answer_list[2][3] = round(($row['count03']/$total_count)*100,1);

	$answer_list[3][0] = $row['answer04'];
	$answer_list[3][1] = $row['count04'];
	$answer_list[3][2] = "count04";
	$answer_list[3][3] = round(($row['count04']/$total_count)*100,1);

	$answer_list[4][0] = $row['answer05'];
	$answer_list[4][1] = $row['count05'];
	$answer_list[4][2] = "count05";
	$answer_list[4][3] = round(($row['count05']/$total_count)*100,1);

	$answer_list[5][0] = $row['answer06'];
	$answer_list[5][1] = $row['count06'];
	$answer_list[5][2] = "count06";
	$answer_list[5][3] = round(($row['count06']/$total_count)*100,1);

	$answer_list[6][0] = $row['answer07'];
	$answer_list[6][1] = $row['count07'];
	$answer_list[6][2] = "count07";
	$answer_list[6][3] = round(($row['count07']/$total_count)*100,1);

	$answer_list[7][0] = $row['answer08'];
	$answer_list[7][1] = $row['count08'];
	$answer_list[7][2] = "count08";
	$answer_list[7][3] = round(($row['count08']/$total_count)*100,1);

	$answer_list[8][0] = $row['answer09'];
	$answer_list[8][1] = $row['count09'];
	$answer_list[8][2] = "count09";
	$answer_list[8][3] = round(($row['count09']/$total_count)*100,1);

	$answer_list[9][0] = $row['answer10'];
	$answer_list[9][1] = $row['count10'];
	$answer_list[9][2] = "count10";
	$answer_list[9][3] = round(($row['count10']/$total_count)*100,1);

	$pollmain = $header2;
	$pollmain = str_replace("{QUESTION}",$row['question'],$pollmain);

	echo "<input type='hidden' name='qidx".$no."' value='".$row['idx']."'>";
	echo $pollmain;

	for($ii=0;$ii<10;$ii++){
		if($answer_list[$ii][0] != ""){
			$poll = "";

			$poll = "<input onFocus=this.blur(); type=radio name='answer".$no."' value='".$answer_list[$ii][2]."'>";

      $poll .= $answer_list[$ii][0];

			$pollmain = $body2;
			$pollmain = str_replace("{ANSWER}",$poll,$pollmain);

			echo $pollmain;
    }
  }

	$pollmain = $footer2;
	$pollmain = str_replace("{QUESTION}",$row['question'],$pollmain);

	echo $pollmain;

	$no++;

}

echo $footer;

echo "</form>";

?>
