<?
if(!strcmp($oper_info['qna_usetype'], "Y")) {

	$qna_code = "qna";

	// 게시판 정보
	$qna_sql = "select * from wiz_bbsinfo where code = '$qna_code'";
	$qna_result = query($qna_sql) or error("sql error");
	$qna_total = sql_fetch_row($qna_result);
	$bbs_info = sql_fetch_arr($qna_result);
	if($qna_total <= 0) {
		$qna_hide = true;
	}

	$qna_sql2 = "select purl from wiz_bbsmain where code = '$qna_code'";
	$qna_result2 = query($qna_sql2) or error("sql error");
	$bbs_main = sql_fetch_arr($qna_result2);

	if(!empty($bbs_main['purl'])) {
		$purl = "/".$bbs_main['purl'];
		$purl_wirte = "/".$bbs_main['purl']."?code=".$qna_code."&pos=2&code_page=customer&ptype=input&&mode=insert&prdcode=".$prdcode;
		$purl_list = "/".$bbs_main['purl']."?code=".$qna_code."&pos=2&code_page=customer";

	} else {
		$purl = "#";
		$purl_wirte = "javascript:alert('글을 작성할 수 없습니다.');";
		$purl_list = "javascript:alert('전체목록을 볼 수 없습니다.');";
	}

	if($qna_hide != true) {
?>
<script language="javascript">
<!--
var clickvalue='';
function qnaShow(idnum) {

	var qna = document.getElementById("qna"+idnum).style;

	if(clickvalue != qna) {
		if(clickvalue!='') {
			clickvalue.display='none';
		}

		qna.display='block';
		clickvalue=qna;
	} else {
		qna.display='none';
		clickvalue='';
	}

}

function qnaCheck(frm) {
	if(frm.passwd.value == "") {
		alert("비밀번호를 입력하세요.");
		frm.passwd.focus();
		return false;
	}
}
-->
</script>

<!-- 상품 QnA -->
<div class="prd_tab">
  <ul>
	<li><a href="#info">상품정보</a></li>
	<? if(!strcmp($oper_info['prdrel_use'], "Y")) { ?><li><a href="#rel">관련상품</a></li><? } ?>
	<? if(!strcmp($oper_info['qna_usetype'], "Y")) { ?><li><a href="#qna" class="prd_tab_up">상품 Q&amp;A <span class="review_num">(<?=$qna_cnt?>)</span></a></li><? } ?>
	<? if(!strcmp($oper_info['review_usetype'], "Y")) { ?><li><a href="#review">상품후기 <span class="review_num">(<?=@number_format($review_count)?>)</span></a></li><? } ?>
  </ul>
</div>




    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="right" style="padding-bottom:10px;">
		    <input class="btn_type2" onclick="location.href='<?=$purl_wirte?>'" type="button" value="문의하기">
			<input class="btn_type2" onclick="location.href='<?=$purl_list?>'" type="button" value="전체보기">
          </td>
        </tr>
        <tr>
          <td>

          		<!-- 게시판 리스트 -->
              <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                 <tr>
                  <td colspan="5" height="1" bgcolor="#333333"></td>
                </tr>
                <tr>
                  <td width="10%" class="table_tit">번호</td>
                  <td class="table_tit">제목</td>
                  <td width="10%" class="table_tit">작성자</td>
                  <td width="15%" class="table_tit">작성일</td>
                </tr>
                <tr>
                  <td colspan="5" height="1" bgcolor="#d7d7d7"></td>
                </tr>
<?
$qna_sql = "select idx from wiz_bbs where code = '$qna_code' and prdcode='$prdcode' order by prino desc";
$qna_result = query($qna_sql) or error("sql error");

$rows = 30;
$lists = 5;
$total = sql_fetch_row($qna_result);
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;

$qna_sql = "select *, DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y.%m.%d') as wdate from wiz_bbs where code = '$qna_code' and prdcode='$prdcode' order by prino desc limit $start, $rows";
$qna_result = query($qna_sql) or error("sql error");

while(($row = sql_fetch_obj($qna_result)) && $rows){

 	$catname=""; $re_space=""; $depno=""; $lock=""; $new=""; $hot="";

 	$qna_display = "none";

 	$subject = "<a href=\"javascript:qnaShow('$no');\">$row->subject</a>";

 	if($row->privacy == "Y"){

		$grp_sql = "select idx from wiz_bbs where code='$qna_code' and grpno='$row->grpno' and passwd='$passwd' and idx = '$idx'";
		$grp_result = query($grp_sql) or error("sql error");
		$grp_passwd = sql_fetch_row($grp_result);

		if(
		($mem_level == "0" && !empty($wiz_session['id'])) ||																					// 전체관리자
		($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
		($row->memid != "" && $row->memid == $wiz_session['id']) || 																// 자신의글
		($row->passwd != "" && $row->passwd == $passwd && !strcmp($idx, $row->idx)) ||						// 비밀번호일치
		($wiz_session['id'] != "" && strpos($row->memgrp,$wiz_session['id']) !== false) ||						// 그룹의글
		($grp_passwd > 0)																																					// 그룹비번
		){
		}else{
			$subject = "<a href='".$purl."?ptype=passwd&mode=view&idx=$row->idx&code=$qna_code&page=$page&$param'>$row->subject</a>";
		}

 		$lock = "<img src='/twcenter/images/lock.gif' align='absmiddle'>";

 	}

 	$re_space = ""; for($ii=0; $ii < $row->depno; $ii++) $re_space .= "&nbsp;&nbsp;";				// respace
 	if($row->depno != 0) $depno = "<img src='/twcenter/images/re.gif' align='absmiddle'>";			// re

	if($row->catname != "") $catname = "[".$row->catname."]";
?>
							<tr height="40">
								<td align="center"><?=$no?></td>
								<td style="padding-left:10px; text-align:left;"><?=$catname?> <?=$re_space?><?=$depno?> <?=$subject?> <?=$lock?></td>
								<td align="center"><?=$row->name?></td>
								<td align="center"><?=$row->wdate?></td>
							</tr>
							<tr>
								<td colspan="5" height="1" bgcolor="#d7d7d7"></td>
							</tr>

							<tr>
								<td colspan="5">
									<div id="qna<?=$no?>" style="display:<?=$qna_display?>" class="review_con">
									<?=str_replace("\n","<br>",$row->content)?>
									</div>
								</td>
							</tr>
<?
	$no--;
	$rows--;
}
if($total <= 0){
?>
							<tr>
							  <td align="center" colspan="5" height="50">등록된 게시물이 없습니다.</td>
							</tr>
              <tr>
                <td colspan="5" height="1" bgcolor="#d7d7d7"></td>
              </tr>
<?
}
?>
						</table>

						<!-- 페이지 번호 -->
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
						  <tr>
						    <td height="50" align="center">
									<? print_pagelist($page, $lists, $page_count, "&ptype=view&prdcode=$prdcode"); ?>
						    </td>
						  </tr>
						</table>
						<!-- 페이지 번호 끝 -->
          </td>
        </tr>
      </table>


<?
	}
}
?>