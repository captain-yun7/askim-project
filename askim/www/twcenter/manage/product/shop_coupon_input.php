<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include "../head.php"; ?>

<link href="/comm/jquery-ui/jquery-ui-1.11.3.min.css?ver=1493426261" rel="stylesheet" type="text/css">
<link href="/comm/jquery-ui/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/comm/jquery-ui/jquery-ui-1.11.3.min.js"></script>
<script type="text/javascript" src="/twcenter/js/datepicker.js"></script>

<?
if($sub_mode == "update"){
	$sql = "select * from wiz_coupon where idx = '$idx'";
	$result = query($sql) or error("sql error");
	$coupon_info = sql_fetch_arr($result);
}
?>
<script language="JavaScript" type="text/javascript">
<!--
$(function() {

	$('#coupon_sdate').datepicker({
		language: 'kr',
		autoClose: true
	});
	$('#coupon_edate').datepicker({
		language: 'kr',
		autoClose: true
	});
	$('#coupon_use_edate').datepicker({
		language: 'kr',
		autoClose: true
	});

});

$(document).on("keydown", "#searchkey", function(){
	if(event.keyCode == 13) {
		memsearch();
		return false;
	}
});

$(document).on("change", "input[name=coupon_useE_type]", function() {
	var val = $("input[name=coupon_useE_type]:checked").val();
	if(val == 'E') {
		$("#coupon_useE_type_D").hide();
		$("#coupon_useE_type_S").hide();
	} else if (val == 'D') {
		$("#coupon_useE_type_D").show();
		$("#coupon_useE_type_D").find("input")[0].focus();
		$("#coupon_useE_type_S").hide();
	} else if (val == 'S') {
		$("#coupon_useE_type_D").hide();
		$("#coupon_useE_type_S").show();
		$("#coupon_useE_type_S").find("input")[0].focus();
	}
});

function memsearch() {
	var searchopt = $("#searchopt").val();
	var searchkey = $("#searchkey").val();
	location.href = "shop_coupon_input.php?sub_mode=<?=$sub_mode?>&idx=<?=$idx?>&searchopt="+searchopt+"&searchkey="+searchkey;
}

function inputCheck(frm){

   if(frm.name.value == ""){
      alert("쿠폰명을 입력하세요");
      frm.name.focus();
      return false;
   }
   if(frm.coupon_sdate.value == ""){
      alert("기간을 입력하세요");
      frm.coupon_sdate.focus();
      return false;
   }
   if(frm.coupon_edate.value == ""){
      alert("기간을 입력하세요");
      frm.coupon_edate.focus();
      return false;
   }
   if(frm.coupon_dis.value == ""){
      alert("할인율을 입력하세요");
      frm.coupon_dis.focus();
      return false;
   }

}
//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">쿠폰관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">쿠폰을 등록,수정합니다.</td>
        </tr>
      </table>

			<br>
      <form name="frm" action="shop_save.php" method="post" onSubmit="return inputCheck(this);" enctype="multipart/form-data">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="shop_coupon">
      <input type="hidden" name="sub_mode" value="<?=$sub_mode?>">
      <input type="hidden" name="idx" value="<?=$idx?>">
	  <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">쿠폰명</td>
          <td width="85%" class="t_value">
            <input name="coupon_name" value="<?=$coupon_info['coupon_name']?>" type="text" size="60" class="input">
          </td>
        </tr>
        <tr>
          <td class="t_name">발급기간</td>
          <td class="t_value">
            <input name="coupon_sdate" id="coupon_sdate" value="<?=$coupon_info['coupon_sdate']?>" type="text" size="12" class="datepicker-here input2"> ~
            <input name="coupon_edate" id="coupon_edate" value="<?=$coupon_info['coupon_edate']?>" type="text" size="12" class="datepicker-here input2">
          </td>
        </tr>
        <tr>
          <td class="t_name">사용만료일</td>
          <td class="t_value">
			<input type="radio" name="coupon_useE_type" value="E" <?if($coupon_info['coupon_useE_type'] == 'E' || $coupon_info['coupon_useE_type'] == '') echo " checked";?>>발급기간내 사용 &nbsp;
			<input type="radio" name="coupon_useE_type" value="D" <?if($coupon_info['coupon_useE_type'] == 'D') echo " checked";?>>날짜기준
            <span id="coupon_useE_type_D"<?if($coupon_info['coupon_useE_type'] != 'D') echo ' style="display:none"';?>> (<input name="coupon_use_edate" id="coupon_use_edate" value="<?=$coupon_info['coupon_use_edate']?>" type="text" size="12" class="datepicker-here input2">)</span> &nbsp;
			<input type="radio" name="coupon_useE_type" value="S" <?if($coupon_info['coupon_useE_type'] == 'S') echo " checked";?>>발급일 기준
            <span id="coupon_useE_type_S"<?if($coupon_info['coupon_useE_type'] != 'S') echo ' style="display:none"';?>> (발급일로부터 <input name="coupon_use_eday" id="coupon_use_eday" value="<?=$coupon_info['coupon_use_eday']?>" type="text" size="3" class="input">일)</span> &nbsp;
          </td>
        </tr>
         <tr>
          <td class="t_name">쿠폰금액/할인율</td>
          <td class="t_value">
            <input name="coupon_dis" value="<?=$coupon_info['coupon_dis']?>" type="text" class="input">&nbsp;
            <span style="vertical-align: middle"><input type="radio" name="coupon_type" value="%" <? if($coupon_info['coupon_type'] == "" || $coupon_info['coupon_type'] == "%") echo "checked"; ?>></span>% 퍼센트
            <span style="vertical-align: middle"><input type="radio" name="coupon_type" value="원" <? if($coupon_info['coupon_type'] == "원") echo "checked"; ?>></span>원
          </td>
        </tr>
        <tr>
          <td class="t_name">최소사용금액</td>
          <td class="t_value">
            <input name="coupon_price_limit" value="<?=$coupon_info['coupon_price_limit']?>" type="text" class="input">&nbsp;
            <font color="#aaa">미입력시 제한없음</font>
          </td>
        </tr>
        <tr>
          <td class="t_name">쿠폰수량</td>
          <td class="t_value">
            <input name="coupon_amount" value="<?=$coupon_info['coupon_amount']?>" type="text" class="input">&nbsp;
            <span style="vertical-align: middle"><input type="checkbox" name="coupon_limit" value="N" <? if($coupon_info['coupon_limit'] == "N") echo "checked"; ?>  onClick="if(this.checked==true) this.form.coupon_amount.disabled = true; else this.form.coupon_amount.disabled = false;"></span>수량제한없음
          </td>
        </tr>
        <tr>
          <td class="t_name">쿠폰이미지</td>
          <td class="t_value">
			<?
			if(is_file("../../data/coupon/".$coupon_info['coupon_img'])) echo "<span class='img_b-order'><img src='/twcenter/data/coupon/".$coupon_info['coupon_img']."' width='54' align='absmiddle'></span> <a href=shop_save.php?mode=shop_coupon&sub_mode=coupon_img_del&idx=".$coupon_info['idx']."><font color=red>[삭제]</font></a><br>";
			?>
			<div class="filebox preview-image">
			<input class="input upload-name" value="파일선택" disabled="disabled">
			<label for="input-file">파일 업로드</label>
			<input type="file" name="coupon_img" id="input-file" class="upload-hidden">
			</div>

            <!-- <input name="coupon_img" value="<?=$coupon_info['coupon_img']?>" type="file" class="input">&nbsp; -->

          </td>
        </tr>
        <? if($sub_mode == "update"){ ?>
        <tr>
          <td height="25" class="t_name">쿠폰링크</td>
          <td class="t_value">&lt;a href="/twcenter/product/coupon_down.php?eventidx=<?=$idx?>"&gt;링크명&lt;/a&gt;</td>
        </tr>
        <tr>
          <td height="25" class="t_name">쿠폰발급회원</td>
          <td class="t_value">
		  <select id="searchopt" class="select">
		  <option value="wc.memid"<?if($searchopt == 'wc.memid') echo " selected"; ?>>아이디</option>
		  <option value="wm.name"<?if($searchopt == 'wm.name') echo " selected"; ?>>이름</option>
		  </select>
		  <input id="searchkey" value="<?=$searchkey?>" class="input" size="20">
		  <input type="button" value="검색" class="optadd blue" onclick="memsearch()">
		  
          	<table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style">
          		<tr>
          			<td height="20" align="center" class="t_name">번호</td>
          			<td align="center" class="t_name">회원이름</td>
          			<td align="center" class="t_name">회원아이디</td>
          			<td align="center" class="t_name">발급시간</td>
          			<td align="center" class="t_name">사용여부</td>
          	  </tr>
		      <?
				if($searchopt && $searchkey) {
					$searchsql = " and $searchopt like '%".$searchkey."%'";
				}

				$sql_cnt = "select count(*) as cnt from wiz_mycoupon wc left join wiz_member wm on wc.memid=wm.id where wc.eventidx='$idx' $searchsql";
				$row_cnt = sql_fetch($sql_cnt);
				$total = $row_cnt['cnt'];

				$param = "sub_mode=$sub_mode&idx=$idx";

				$rows = 10;
				$lists = 5;
				$page_count = ceil($total/$rows);
				if(!$page || $page > $page_count) $page = 1;
				$start = ($page-1)*$rows;
				$no = $total-$start;

				$sql = "select wc.wdate, wc.coupon_use, wc.coupon_name, wm.id, wm.name from wiz_mycoupon wc left join wiz_member wm on wc.memid=wm.id where wc.eventidx='$idx' $searchsql order by wc.idx desc limit $start, $rows";
				$result = query($sql) or error("sql error");
				while($row = sql_fetch_obj($result)){
				?>
              <tr height="30">
          			<td align="center"><?=$total?></td>
          			<td align="center"><?=$row->name?></td>
          			<td align="center"><?=$row->id?></td>
          			<td align="center"><?=$row->wdate?></td>
          			<td align="center"><?=$row->coupon_use?></td>
          	  </tr>
		          <?
		          	$total--;
		          }
		          ?>
            </table>
			<? print_pagelist($page, $lists, $page_count, "&$param"); ?>
          </td>
        </tr>
      	<? } ?>
      </table>

      <br>
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">
          	<input type="submit" value="확인" class="base_btn reg">&nbsp;
			<input type="button" value="목록" class="base_btn gray" onClick="document.location='shop_coupon.php?menucode=PRODUCT';">

          	<!-- <input type="image" src="../image/btn_confirm_l.gif"> &nbsp;
          	<img src="../image/btn_list_l.gif" style="cursor:hand"  onClick="document.location='shop_coupon.php';"> -->
          </td>
        </tr>
      </table>
	  </form>
      <br><br>

		<br>
		<table width="100%" border="0" cellspacing="10" cellpadding="8" bgcolor="9d9d9d" align="center">
			<tr>
				<td align="center">
					<table width="100%" border="0" cellspacing="0" cellpadding="6">
						<tr>
							<td><img src="../image/check_tit.gif" width="75" height="19" /></td>
						</tr>
						<tr>
							<td class="chk_alt">
							<font color="#000000">쿠폰다운로드 페이지 생성방법</font><br>
							쿠폰을 생성후 다운로드 받을 페이지를 디자인하여 생성합니다.<br>
							생성한 페이지의 적당한 위치에 "쿠폰링크" 태그를 이용하여 쿠폰다운로드를 링크를 생성합니다.<br>
							링크를 클릭하면 쿠폰이 다운로드 됩니다.<br><br>

							"쿠폰링크" 태그로 링크를 걸면 쇼핑몰 어느위치에서든 쿠폰다운로드 기능을 만들수 있습니다.<br>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>


<? include "../foot.php"; ?>