<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";
include_once "../../inc/site_info.php";

$param = "sdate=".$sdate."&edate=".$edate."&level=".$level."&searchopt=".$searchopt."&searchkey=".$searchkey."&resms=".$resms."&".$menucodeParam;
$site_tel = str_replace("-", "", $site_info['site_tel']);

include "../head.php";
include "../../lib/datepicker_lib.php";

?>
<script type="text/javascript" src="../../js/icode.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
$(function () {

	$(".FSubmitMailSmdSend").on("click", function(e) {

		var frmEl   = this.form;
		var frmObj  = $(frmEl);
		var frmid   = frmObj.attr('id');
		var DirectSend = $("input:checkbox[name='DirectSend']").val();
		var DirectSend_chk = $("input:checkbox[name='DirectSend']").is(":checked");

		var varsend_msg = $("#message").val();
		var varsend_num = $("#send_num").val();

		if (frmid === "MsgForm" && DirectSend === "Y" && DirectSend_chk == true) {
			if ($("#recv_num option").length < 2){
				alert("수신자 번호를 입력해 주세요");
				$("#addCallNum").focus();
				return false;
			}
		}

		if (frmid === "MsgForm" && DirectSend === "Y" && DirectSend_chk == true) {
			var send_mode = "DirectSend";
		} else {
			var send_mode = "SearchSend";
		}

		if (varsend_num  == null || varsend_num == ""){
			alert("회신번호를 입력하세요");
			$("#send_num").focus();
			return false;
		}

		if (!isInteger(varsend_num, 0)){
			alert('회신번호는 숫자만 가능합니다.');
			$("#send_num").focus();
			return false;
		}

		if (varsend_msg == ""){
			alert("메시지를 입력해 주세요");
			$("#send_msg").focus();
			return false;
		}

		var strTelList = '';
		for (k=1; k < $("#recv_num option").length; k++) {
			strTelList += $("#recv_num option:eq("+k+")").val();
		}

		if(typeof frmEl.strTelList === "undefined") frmObj.prepend('<input type="hidden" name="strTelList" value="">');
		frmObj.find("input[name=strTelList]").val(strTelList);

		if(typeof frmEl.mode === "undefined") frmObj.prepend('<input type="hidden" name="mode" value="'+send_mode+'">');
		frmObj.find("input[name=mode]").val(send_mode);

		var actUrl    = "sms_save.php?<?=$menucodeParam?>";

		if(confirm('발송하시겠습니까?')){

			$("#"+frmid).ajaxForm({
				type: "POST"
				,url: actUrl
				,dataType: 'json'
				,success: function(data, textStatus, jqXHR) {
					if (data.result == '00') {
						alert(data.msg);
						document.location.reload();
					} else {
						alert(data.msg);
						return false;
					}
				}
				,error: function (request, status, error) {
					console.log(request);
					console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
				}
			});

			$("#"+frmid).submit();

		} else {
			return false;
		}

	});

});

$(function () {

	$("#DirectSend").on("click", function() {
		var DirectSend = $("input:checkbox[name='DirectSend']").val();
		if (DirectSend === "Y") {
			if($("#DirectShow").css("display") == "none"){
				$("#DirectShow").show();
			} else {
				$("#DirectShow").hide();
			}
		}
	});

});

$(function () {
	$('.remaining').each(function () {
		var $maxcount = $('.maxcount', this);
		var $count = $('.count', this);
		var $input = $("#message", this);

		var maximumByte = $maxcount.text() * 1;
		// update 함수는 keyup, paste, input 이벤트에서 호출한다.
		var update = function () {
			var before = $count.text() * 1;
			var str_len = $input.val().length;
			var cbyte = 0;
			var li_len = 0;
			for (i = 0; i < str_len; i++) {
				var ls_one_char = $input.val().charAt(i);
				if (escape(ls_one_char).length > 4) {
					cbyte += 2; //한글이면 2를더함
				} else {
					cbyte++;    //한글아니면 1을더함
				}
				if (cbyte <= maximumByte) {
					li_len = i + 1;
				}
			}

			if (parseInt(cbyte) > parseInt(maximumByte)) {
				var str = $input.val();
				var str2 = $input.val().substr(0, li_len);
				$input.val(str2);
				var cbyte = 0;
				for (i = 0; i < $input.val().length; i++) {
					var ls_one_char = $input.val().charAt(i);
					if (escape(ls_one_char).length > 4) {
						cbyte += 2; //한글이면 2를더함
					} else {
						cbyte++;    //한글아니면 1을더함
					}
				}
			}
			$count.text(cbyte);
		};
		$input.bind('input keyup keydown paste change', function () {
			setTimeout(update, 0)
		});
		update();
	});
});

$(function() {

	$('#sdate').datepicker({
		language: 'kr',
		autoClose: true

	});
	$('#edate').datepicker({
		language: 'kr',
		autoClose: true
	});

});

//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">단체SMS발송</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">회원을 검색하여 단체 SMS를 발송합니다.</td>
        </tr>
      </table>

      <br>
      <form name="frm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td bgcolor="ffffff">
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
            <tr>
            <td width="15%" class="t_name">&nbsp; 조건검색</td>
            <td width="85%" class="t_value">

             <table cellspacing="2" cellpadding="0">
             <tr>
             <td>
               <select name="level" class="select">
               <option value=""> :: 등급선택 ::</option>
               <?=level_list();?>
               </select>
             </td>
             <td>
               <select name="searchopt" class="select">
               <option value="name">고객명
               <option value="id">아이디
               <option value="email">이메일
               <option value="tphone">전화번호
               <option value="hphone">휴대폰
               </select>
             </td>
             <td><input type="text" name="searchkey" value="<?=$searchkey?>" class="input"></td>
             <!-- <td><input type="image" src="../image/btn_search.gif"></td> -->
             </tr>
             </table>
             <script language="javascript">
             <!--
             level = document.frm.level;
             for(ii=0; ii<level.length; ii++){
               if(level.options[ii].value == "<?=$level?>")
                 level.options[ii].selected = true;
             }
             searchopt = document.frm.searchopt;
             for(ii=0; ii<searchopt.length; ii++){
               if(searchopt.options[ii].value == "<?=$searchopt?>")
                 searchopt.options[ii].selected = true;
             }
             -->
             </script>

           </td>
           </tr>
           <tr>
            <td width="120" class="t_name">&nbsp; 가입기간</td>
            <td class="t_value">
              <? if($sdate == "") $sdate = ""; if($edate == "") $edate = ""; ?>
			  <input type="text" name="sdate" id="sdate" value="<?=$sdate?>" class="input input120">
               ~
              <input type="text" name="edate" id="edate" value="<?=$edate?>" class="input input120">
            </td>
            </tr>
            <tr>
            <td width="120" class="t_name">&nbsp; SMS수신</td>
            <td class="t_value">
            	<span style="vertical-align: middle"><input type="radio" name="resms" value="Y" <? if($resms == "Y" || $resms == "") echo "checked"; ?>></span>회원전체
                <span style="vertical-align: middle"><input type="radio" name="resms" value="N" <? if($resms == "N") echo "checked"; ?>></span>수신거부회원 제외
            </td>
            </tr>
           </table>
          </td>
        </tr>
      </table>
	  <br>
		<table width="100%" cellspacing="1" cellpadding="3" border="0">
			<tr>
				<td align="center">
					<input type="submit" value="검색" class="search_btn2">&nbsp;
					<input type="button" value="전체회원" class="search_default" onclick="location.href='<?=$PHP_SELF?>?<?=$menucodeParam?>'">
				</td>
			</tr>
		</table>
	  </form>
	  <form name="MsgForm" id="MsgForm">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <input type="hidden" name="sdate" value="<?php echo $sdate ?>">
	  <input type="hidden" name="edate" value="<?php echo $edate ?>">
	  <input type="hidden" name="searchopt" value="<?php echo $searchopt ?>">
	  <input type="hidden" name="searchkey" value="<?php echo $searchkey ?>">
	  <input type="hidden" name="level" value="<?php echo $level ?>">
	  <input type="hidden" name="resms" value="<?php echo $resms ?>">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td height="15"></td></tr>
      </table>
      <?
      	$sql = "select id from wiz_member where dchange_type!='Y'";
      	$result = query($sql) or error("sql error");
      	$all_total = sql_fetch_row($result);


         $today = date('n-d');
         $toyear = date('Y');

         $age_syear = substr($toyear-($age+9),-2)+1;
         $age_eyear = substr($toyear-$age,-2)+2;

         $join_sdate = $prev_year."-".$prev_month."-".$prev_day;
         $join_edate = $next_year."-".$next_month."-".$next_day;


         $sql = "select id,passwd,name,hphone,email,visit,resms,wdate from wiz_member where id != '' and dchange_type!='Y'";

         if($sdate != "") 		$sql .= " and wdate > '$sdate'";
         if($edate != "") 		$sql .= " and wdate <= '$edate 23:59:59'";
         if($searchkey != "")	$sql .= " and $searchopt like '%$searchkey%'";
         if($level != "") 		$sql .= " and level = '$level'";
         if($birthday == "Y")	$sql .= " and birthday like '%$today'";
         if($memorial == "Y")	$sql .= " and memorial like '%$today'";
         if($age != "")			$sql .= " and resno > '$age_syear' and resno < '$age_eyear'";
         if($address != "")		$sql .= " and address like '%$address%'";
         if($job != "")			$sql .= " and job = '$job'";
         if($marriage != "")	$sql .= " and marriage = '$marriage'";
         if($resms == "N")		$sql .= " and resms != 'N'";

         $sql .=" order by wdate desc";

         $result = query($sql) or error("sql error");
         $total = sql_fetch_row($result);

         $rows = 6;
         $lists = 5;
       	 $page_count = ceil($total/$rows);
       	 if(!$page || $page > $page_count) $page = 1;
         $start = ($page-1)*$rows;
         $no = $total-$start;
      ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="title_msg">총 회원수 : <strong id="total_prd_cnt"><?=$all_total?></strong> , 검색 회원수 : <strong id="total_prd_cnt"><?=$total?></strong></span></td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td colspan=20 class="t_rd"></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th>이름</th>
          <th>아이디</th>
          <th>휴대폰</th>
          <th>이메일</th>
          <th width="5%">방문수</th>
          <th width="10%">SMS수신</th>
          <th width="10%">가입일</th>
        </tr>
        <tr><td colspan=20 class="t_rd"></td></tr>
		<?
         $sql = "select id,passwd,name,hphone,email,visit,resms,wdate from wiz_member where id != '' and dchange_type!='Y'";

         if($sdate != "") 		$sql .= " and wdate > '$sdate'";
         if($edate != "") 		$sql .= " and wdate <= '$edate 23:59:59'";
         if($searchkey != "")	$sql .= " and $searchopt like '%$searchkey%'";
         if($level != "") 		$sql .= " and level = '$level'";
         if($birthday == "Y")	$sql .= " and birthday like '%$today'";
         if($memorial == "Y")	$sql .= " and memorial like '%$today'";
         if($age != "")			$sql .= " and resno > '$age_syear' and resno < '$age_eyear'";
         if($address != "")		$sql .= " and address like '%$address%'";
         if($job != "")			$sql .= " and job = '$job'";
         if($marriage != "")	$sql .= " and marriage = '$marriage'";
         if($resms == "N")		$sql .= " and resms != 'N'";

         $sql .=" order by wdate desc limit $start, $rows";

         $result = query($sql) or error("sql error");

		while($row = sql_fetch_obj($result)){
			if($row->resms == "N") $row->resms = "아니오";
			else $row->resms = "예";
		?>
        <input type="hidden" name="id" value="<?=$row->id?>">
        <tr>
          <td align="center" height="38"><?=$no?></td>
          <td align="center"><?=$row->name?></td>
          <td align="center"><?=$row->id?></td>
          <td align="center"><?=$row->hphone?></td>
          <td align="center"><?=$row->email?></td>
          <td align="center"><?=$row->visit?></td>
          <td align="center"><?=$row->resms?></td>
          <td align="center"><?=substr($row->wdate,0,10)?> &nbsp;</td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
     <?
     		$no--;
      }

    	if($total <= 0){
    	?>
    		<tr><td height=30 colspan=10 align=center>검색된 회원이 없습니다.</td></tr>
    		<tr><td colspan="20" class="t_line"></td></tr>
    	<?
    	}
      ?>
      </table>

      <br>

      <? print_pagelist($page, $lists, $page_count, "$param"); ?>

      <br>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="15%" class="t_name">발신번호</td>
                <td width="85%" class="t_value">
                <input type="text" name="se_num" id="send_num" value="<?php echo $site_tel ?>" size="25" class="input">
                </td>
              </tr>

              <tr id="DirectShow" style="display:none">
                <td width="15%" class="t_name">수신번호</td>
				<td width="85%" class="t_value" style="padding:5px 10px;">
				<?php
				if($site_info['sms_send_type'] == 'L') {
				?>
					<input name="strSubject" id="strSubject" type="text" maxlength="30" class="input" placeholder="LMS 문자제목(30Byte)" style="width:18%">
					<span class="tip_br5"></span>
				<?php } ?>

					<input name="addCallNum" id="addCallNum" type="text" class="input" size="25">
					<input type="button" value="추가" class="sms_add_button" onclick="addItem();">
					<input type="button" value="삭제" class="sms_del_button" onclick="delItem();">
					<span class="tip_br5"></span>
					<select name="recv_num" id="recv_num" size="10" multiple class="smsNumSel">
						<option value="0">:: 수신번호 리스트 ::</option>
					</select>
				</td>
              </tr>

              <tr>
                <td class="t_name">메시지 내용</td>
                <td class="t_value remaining">
				  <label><input type="checkbox" name="DirectSend" id="DirectSend" value="Y"> 직접발송</label>
				  <span class="tip_br2"></span>
                  <textarea name="message" rows="12" cols="67" id="message" class="smstxt"></textarea>
				  <span class="tip_br2"></span>
					(<font color="#2DA7FE"><b><span class="count">0</span></b></font> / <span class="maxcount"><?php echo $sms_length ?></span> Byte)
					<font color="#2DA7FE">※ 메시지내용은 <?php echo $sms_length ?> 바이트 이상 전송할 수 없습니다.</font>
				  </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">
			 <input type="button" value="발송하기" class="base_btn reg FSubmitMailSmdSend">
          </td>
        </tr>
      </table>
	  </form>


<? include "../foot.php"; ?>