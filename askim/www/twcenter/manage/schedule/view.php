<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/sch_info.php"; ?>
<?
// 검색 파라미터
$param = "code=$code&page=$page&category=$category&searchopt=$searchopt&searchkey=$searchkey&$menucodeParam";
if(!empty($idx)) $param .= "&idx=$idx";

// 게시물 정보
$sql = "select *, from_unixtime(wdate, '%Y-%m-%d') as wdate from wiz_bbs where idx = '$idx'";
$result = query($sql) or error("sql error");
$bbs_row = sql_fetch_arr($result);

$_ResizeCheck = false;
// 첨부파일 이미지인경우 보여주기
if(strcmp($sch_info['imgview'], "N")) {

	for($ii = 1; $ii <= 12; $ii++) {
		if(img_type(WIZHOME_PATH."/data/bbs/$code/M".$bbs_row['upfile'.$ii])) {
			${'upimg'.$ii} = "<div align='".$sch_info['img_align']."'><a href=javascript:viewImg('".$bbs_row['upfile'.$ii]."');><img src='/twcenter/data/bbs/$code/M".$bbs_row['upfile'.$ii]."' border='0' name='wiz_target_resize'></a></div>";
			$_ResizeCheck = true;
		}
	}
}

// 이미지 리사이즈를 위해서 처리하는 부분
$bbs_row['content'] = stripslashes($bbs_row['content']);
if(strpos(strtolower($bbs_row['content']), "<img") !== false || $_ResizeCheck == true) {
	$bbs_row['content'] = preg_replace("/(\<img)(.*)(\>?)/i","\\1 name=wiz_target_resize style=\"cursor:pointer\" onclick=window.open(this.src) \\2 \\3", $bbs_row['content']);
	$bbs_row['content'] = "<table border=0 cellspacing=0 cellpadding=0 style='width:".$sch_info['mimgsize']."px;height:0px;' height=1 id='wiz_get_table_width'>
								<col width=100%></col>
								<tr>
									<td><img src='' border='0' name='wiz_target_resize' width='0' height='0'></td>
								</tr>
							</table>
							<table border=0 cellspacing=0 cellpadding=0 width=100%>
								<col width=100%></col>
								<tr><td valign=top>".$bbs_row['content']."</td></tr>
							</table>";
	$_ResizeCheck = true;
}

if($bbs_row['category'] != "") $bbs_row['category'] = "[".$bbs_row['category']."]";
if($bbs_row['ctype'] != "H")  $bbs_row['content'] = str_replace("\n", "<br>", $bbs_row['content']);

// 조회수증가
$sql = "update wiz_bbs set count = count + 1 where idx = '$idx'";
query($sql) or error("sql error");

if($sch_info['recom'] == "Y"){
	$recom_btn = "<a href='/twcenter/bbs/save.php?mode=recom&prev=$PHP_SELF&$param'><image src='$skin_dir/image/btn_recom.gif' border=0></a>";
}

// 첨부파일
for($ii = 1; $ii <= 12; $ii++) {
	if($bbs_row['upfile'.$ii] != "") ${'upfile'.$ii}  = "<a href='/twcenter/bbs/down.php?code=$code&$menucodeParam&idx=$idx&no=".$ii."'>".$bbs_row['upfile'.$ii.'_name']."</a>";
}

if($bbs_row['movie1'] != "") $movie1 = "<embed src='/twcenter/data/bbs/$code/".$bbs_row['movie1']."' autostart=false></embed><br>";
if($bbs_row['movie2'] != "") $movie2 = "<embed src='".$bbs_row['movie2']."' autostart=false></embed><br>";
if($bbs_row['movie3'] != "") $movie3 = "<embed src='".$bbs_row['movie3']."' autostart=false></embed><br>";

?>
<? include "../head.php"; ?>

      <script language="JavaScript" type="text/javascript">
      <!--
      function viewImg(img){
        var url = "view_img.php?code=<?=$code?>&img=" + img;
        window.open(url,"viewImg","width=300,height=300,scrollbars=yes");
      }
      function delConfirm(idx){
         if(confirm('선택한 글을 삭제하시겠습니까?')){
           document.location = "save.php?code=<?=$code?>&mode=delete&idx=" + idx + "&<?=$param?>";
         }
      }

      function commentCheck(frm){
	      if(frm.name.value == ""){
	        alert("이름을 입력하세요");
	        frm.name.focus();
	        return false;
	      }
	      if(frm.passwd.value == ""){
	        alert("비밀번호를 입력하세요");
	        frm.passwd.focus();
	        return false;
	      }
		    if(frm.content.value == ""){
	        alert("내용을 입력하세요");
	        frm.content.focus();
	        return false;
	      }
      }

      function delComment(idx){
      	if(confirm("해당 댓글을 삭제하시겠습니까?")){
      		document.location = "save.php?mode=delcomment&code=<?=$code?>&cidx=<?=$idx?>&$menucodeParam&idx=" + idx;
      	}
      }

      //-->
      </script>


			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">일정관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">일정을 추가/삭제/수정 합니다.</td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" class="t_name">이름</td>
                <td width="35%" class="t_value"><?=$bbs_row['name']?></td>
                <td width="15%" class="t_name">이메일</td>
                <td width="35%" class="t_value"><?=$bbs_row['email']?></td>
              </tr>
              <tr>
                <td class="t_name">작성일</td>
                <td class="t_value"><?=$bbs_row['wdate']?></td>
                <td class="t_name">파일첨부</td>
                <td class="t_value"><?=$upfile1?> <?=$upfile2?> <?=$upfile3?></td>
              </tr>
			  <? 
			  if($code=="inquiry"){
				  /*
				  작업자명	: 이상민
				  작업일시	: 2020-03-23
				  작업내용	: 예약가능 시간 및 기간 wiz_bbs에 저장하기 위해 필드명 변경
				  */
			  ?>
              <tr>
                <td class="t_name">예약기간</td>
                <td class="t_value"><?=date("Y년 m월 d일", strtotime($bbs_row["addinfo1"]))." ~ ".date("Y년 m월 d일", strtotime($bbs_row["addinfo2"]))?></td>
                <td class="t_name">예약시간</td>
                <td class="t_value"><?=preg_replace("/\//i", "<br>", $bbs_row["addinfo3"])?></td>
              </tr>
			  <?php
			  }
			  ?>
              <tr>
                <td class="t_name">제목</td>
                <td class="t_value" colspan="3"><?=$bbs_row['category']?><?=$bbs_row['subject']?></td>
              </tr>
              <tr>
                <td class="t_name">내용</td>
                <td class="t_value" colspan="3" valign="top">
                <?=$upimg1?><?=$upimg2?><?=$upimg3?><?=$upimg4?><?=$upimg5?><?=$upimg6?>
                <?=$upimg7?><?=$upimg8?><?=$upimg9?><?=$upimg10?><?=$upimg11?><?=$upimg12?>
      					<?=$movie1?><?=$movie2?><?=$movie3?>
                <?=$bbs_row['content']?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <? if($sch_info['comment'] == "Y"){ ?>
      <br>
      <table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style">
      <?
			$sql = "select * from wiz_comment where ctype='BBS' and cidx='$idx' order by idx desc";
			$result = query($sql) or error("sql error");
			$total = sql_fetch_row($result);

			if($rows == "") $rows = "10";
			if($lists == "") $lists = "10";

			$page_count = ceil($total/$rows);
			if(!$cpage || $cpage > $page_count) $cpage = 1;
			$start = ($cpage-1)*$rows;
			$no = $total-$start;

			$sql = "select * from wiz_comment where ctype='BBS' and cidx='$idx' order by idx desc limit $start, $rows";
			$result = query($sql) or error("sql error");

			while($com_row = sql_fetch_arr($result)){
				$com_row['content'] = str_replace("\n", "<br>", $com_row['content']);
			?>
			<tr>
			  <td class="t_value" width="15%"><b><?=$com_row['name']?></b> (<?=$com_row['memid']?>)</td>
			  <td class="t_value"><?=$com_row['content']?> <a href="javascript:delComment('<?=$com_row['idx']?>');"><font color="red" style="cursor:hand">x</font></a></td>
			  <td class="t_value" width="15%" style="padding-left:5px"><?=$com_row['ip']?><br><?=$com_row['wdate']?></td>
			</tr>
			<?
			}
			?>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td width="25%" height="35">&nbsp;</td>
			    <td width="50%" align="center">

			    	<? print_pagelist($cpage, $lists, $page_count, $param, "C"); ?>

			    </td>
			    <td width="25%" align="right"></td>
			  </tr>
			</table>
      <form name="comment" action="save.php" method="post" onSubmit="return commentCheck(this);">
      <input type="hidden" name="mode" value="comment">
      <input type="hidden" name="code" value="<?=$code?>">
      <input type="hidden" name="memid" value="<?=$wiz_admin['id']?>">
      <input type="hidden" name="cidx" value="<?=$idx?>">
      <input type="hidden" name="menucode" value="<?=$menucode?>">
	  <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
        <tr>
          <td align="center" height=35 class="t_name">
		      <table width="100%" border=0 cellpadding=0 cellspacing=0>
		      <tr>
		        <td width="50%">&nbsp;댓글쓰기</td>
				<td width="45%" align="right">
					이름 <input type="text" name="name" value="<?=$wiz_admin['name']?>" size=10 class="input">&nbsp;&nbsp;
					비밀번호 <input type="text" name="passwd" value="<?=date('is')?>" size=10 class="input">
				</td>
				<td width="5%" align="center">
					<input type="image" src="../image/btn_confirm_s.gif"></td>
				</tr>
		      </table>
          </td>
        </tr>
        <tr><td align=center class="t_name"><textarea name="content" cols="50" rows="3" style="width:100%" class="input"></textarea></td></tr>
      </table>
	  </form>
			<? } ?>

      <br>
      <table width="100%" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td width="50%"><img src="../image/btn_list_l.gif" style="cursor:pointer" onClick="document.location='list.php?<?=$param?>';"></td>
          <td align="right">
			<img src="../image/btn_edit_l.gif" style="cursor:pointer" onClick="document.location='input.php?mode=update&idx=<?=$idx?>&<?=$param?>';">&nbsp;
			<? if($code!="inquiry"){ ?><img src="../image/btn_reply_l.gif" style="cursor:pointer" onClick="document.location='input.php?mode=reply&idx=<?=$idx?>&<?=$param?>';">&nbsp;<? } ?>
			<img src="../image/btn_delete_l.gif" style="cursor:pointer" onClick="javascript:delConfirm('<?=$idx?>');">
		  </td>
        </tr>
      </table>

<? include "../foot.php"; ?>

<? view_img_resize() ?>