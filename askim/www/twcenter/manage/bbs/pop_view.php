<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include_once "../../inc/bbs_info.php"; ?>
<?
// 게시물 정보
$sql = "select wb.*,from_unixtime(wb.wdate, '%Y-%m-%d %H:%i:%s') as wdate, wc.catname as category
				from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
				where wb.idx = '$idx'";
$result = query($sql) or error("sql error");
$bbs_row = sql_fetch_arr($result);

if($bbs_row['category'] != "") $bbs_row['category'] = "[".$bbs_row['category']."]";
if($bbs_row['ctype'] != "H")  $bbs_row['content'] = str_replace("\n", "<br>", $bbs_row['content']);

// 이미지 리사이즈를 위해서 처리하는 부분
$bbs_row['content'] = preg_replace("/(\<img)(.*)(\>?)/i","\\1 name=target_resize style=\"cursor:pointer\" onclick=window.open(this.src) \\2 \\3", $bbs_row['content']);
$bbs_row['content'] = "<table border=0 cellspacing=0 cellpadding=0 width=".$bbs_info['mimgsize']." height=1>
							<col width=100%></col>
							<tr>
								<td>
									<img src='' border='0' width='100%' height='1' name='wiz_get_table_width'><br>
									<img src='' border='0' name='wiz_target_resize' width='1' height='1'>
								</td>
							</tr>
						</table>
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<col width=100%></col>
							<tr><td valign=top>".$bbs_row['content']."</td></tr>
						</table>";
$_ResizeCheck = true;

// 조회수증가
$sql = "update wiz_bbs set count = count + 1 where idx = '$idx'";
query($sql) or error("sql error");

for($ii = 1; $ii <= $upfile_max; $ii++) {
	if(img_type(WIZHOME_PATH."/data/bbs/$code/M".$bbs_row[upfile.$ii])) ${upimg.$ii} = "<div align='".$bbs_info['img_align']."'><a href=javascript:viewImg('".$bbs_row[upfile.$ii]."');><img src='/twcenter/data/bbs/$code/M".$bbs_row[upfile.$ii]."' border='0' name='wiz_target_resize'></a></div>";
}

for($ii = 1; $ii <= $upfile_max; $ii++) {
	if($bbs_row[upfile.$ii] != "") ${upfile.$ii}  = "<a href='down.php?code=$code&idx=$idx&no=".$ii."'>".$bbs_row[upfile.$ii._name]."</a>";
}

for($ii = 1; $ii <= $upfile_max; $ii++) {
	if($bbs_row[upfile.$ii] != "") ${upfile.$ii}  = "<a href='down.php?code=$code&idx=$idx&no=".$ii."'>".$bbs_row[upfile.$ii._name]."</a>";
}

if($bbs_row['movie1'] != "") $movie1 = "<embed src='/twcenter/data/bbs/".$code."/".$bbs_row['movie1']."' autostart=false></embed><br>";
if($bbs_row['movie2'] != "") $movie2 = "<embed src='".$bbs_row['movie2']."' autostart=false></embed><br>";
if($bbs_row['movie3'] != "") $movie3 = "<embed src='".$bbs_row['movie3']."' autostart=false></embed><br>";

?>

<html>
<head>
<title>:: 게시글 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/js/valueCheck.js"></script>
<script language="JavaScript" src="/twcenter/js/lib.js"></script>
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
		document.location = "save.php?mode=delcomment&code=<?=$code?>&cidx=<?=$idx?>&idx=" + idx;
	}
}

function checkBbs() {
	var bidx = "<?=$bbs_row['idx']?>";
	if(bidx == "") {
		alert("존재하지 않는 게시물입니다.");
		self.close();
	}
}

//-->
</script>
</head>
<body onLoad="checkBbs()">
<table width=100% border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td style="padding:10px">
      <form action="list.php" method="post">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="80"><b>선택게시판 :</b></td>
          <td><?=$bbs_info['title']?></td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
	  </form>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="150" height="10" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;이름</td>
                <td class="t_value" colspan="3"><?=$bbs_row['name']?></td>
              </tr>
              <tr>
                <td width="150" height="10" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;이메일</td>
                <td class="t_value" colspan="3"><?=$bbs_row['email']?></td>
              </tr>
              <tr>
                <td width="150" height="10" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;작성일</td>
                <td width="220" class="t_value"><?=$bbs_row['wdate']?></td>
                <td width="150" height="10" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;파일첨부</td>
                <td width="220" class="t_value"><?=$upfile1?> <?=$upfile2?> <?=$upfile3?> <?=$upfile4?> <?=$upfile5?> <?=$upfile6?> <?=$upfile7?> <?=$upfile8?> <?=$upfile9?> <?=$upfile10?> <?=$upfile11?> <?=$upfile12?></td>
              </tr>
              <tr>
                <td width="150" height="10" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;제목</td>
                <td class="t_value" colspan="3"><?=$bbs_row['category']?><?=$bbs_row['subject']?></td>
              </tr>
              <tr>
                <td width="150" height="80" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;내용</td>
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
      <table><tr><td></td></tr></table>

      <? if($bbs_info['comment'] == "Y"){ ?>
      <?
			$sql = "select * from wiz_comment where cidx='$idx' order by idx desc";
			$result = query($sql) or error("sql error");
			while($com_row = sql_fetch_arr($result)){
				$com_row['content'] = str_replace("\n", "<br>", $com_row['content']);
			?>
			<a name="<?=$com_row['idx']?>"></a>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			  <td width="10%" height="25">&nbsp;<b><?=$com_row['name']?></b></td>
			  <td width="90%">
			  <?=$com_row['content']?> <a href="javascript:delComment('<?=$com_row['idx']?>');"><font color="red" style="cursor:hand">x</font></a>
			  </td>
			</tr>
			</table>
			<?
			}
			?>
			<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>

      <form name="comment" action="save.php" method="post" onSubmit="return commentCheck(this);">
      <input type="hidden" name="mode" value="comment">
      <input type="hidden" name="code" value="<?=$code?>">
      <input type="hidden" name="memid" value="<?=$wiz_admin['id']?>">
      <input type="hidden" name="cidx" value="<?=$idx?>">
	  <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
        <tr>
          <td align="center" height=35 class="t_name">
		      <table width="98%" border=0 cellpadding=0 cellspacing=0>
		      <tr>
		        <td>&nbsp;댓글쓰기</td>
			      <td width=140 align=right>이름 <input type="text" name="name" value="<?=$wiz_admin['name']?>" size=10 class="input"></td>
			      <td width=140 align=right>비밀번호 <input type="text" name="passwd" value="<?=date('is')?>" size=10 class="input"></td>
			      <td width=70 align=right><table cellpadding="0" cellspacing="0"><tr><td></td></tr></table><input type="submit" value="쓰기" class="gbtn"></td></tr>
		      </table>
          </td>
        </tr>
        <tr><td align=center class="t_name"><textarea name="content" cols="50" rows="3" style="width:98%" class="input"></textarea></td></tr>
      </table>
	  </form>
			<? } ?>

      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>

      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align=right><input type="button" value="닫 기" onClick="self.close();" class="sbtn"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>