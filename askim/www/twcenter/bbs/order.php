<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/bbs_info.php"; ?>
<?

if(
	($mem_level == "0") ||																																		// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false) ||	// 게시판관리자
	(!empty($wiz_admin['id']))																																	// 관리자
) {
} else {
	echo "<script>alert('관리자만 접근가능합니다.'); self.close();</script>";
	exit;
}

$param = "code=$code&category=$category&searchopt=$searchopt&searchkey=$searchkey";
if($pos != "") $param .= "&pos=$pos&code_page=$code_page";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>게시물 순서변경</title>
<link href="../manage/wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/twcenter/js/lib.js"></script>
<script language="JavaScript" type="text/javascript">
<!--

// 체크박스 선택리스트
function selectValue(){
	var i;
	var selbbs = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			selbbs = selbbs + document.forms[i].idx.value + "|";
		}
	}
	return selbbs;
}

function prinoCheck(frm) {

	if(!check_Num(frm.prino.value)) {
		alert("진열순서는 숫자만 입력할 수 있습니다.");
		frm.prino.focus();
		return false;
	}

}

//선택게시물 진열순서 일괄 변경
function priBbs(){

	selbbs = selectValue();
   var i;
	var selpri = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].prino != null){
			selpri = selpri + document.forms[i].prino.value + "|";
		}
	}
	if(selbbs == ""){
		alert("순서변경할 게시물을 선택하세요.");
		return false;
	}else{
		if(confirm("게시물의 순서를 변경하시겠습니까?\n\n진열순서에 입력된 값으로 수정됩니다.")) {
			document.location = "save.php?mode=pribbs&code=<?=$code?>&selbbs=" + selbbs + "&selpri=" + selpri + "&<?=$param?>";
		}
	}
}

//-->
</script>

</head>
<body>

	<table width="100%">
		<tr>
			<td style="padding:10px">


        <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
      	<input type="hidden" name="code" value="<?=$code?>">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="85" class="tit_sub"><img src="../manage/image/ics_tit.gif"> 순서변경</td>
          <td> - <?=$bbs_info['title']?></td>
          <td align="right">
          	<table border="0" cellpadding="0" cellspacing="0">
          		<tr>
          			<td class="rpad_3">
			          <?
			          $sql = "select idx, catname from wiz_bbscat where code = '$code' and gubun != 'A' order by idx asc";
			          $result = query($sql) or error("sql error");
			          $total = sql_fetch_row($result);
			          if($total > 0) {
								?>
				          <select class="select" name="category" onChange="this.form.submit();">
				          <option value="">:: 분류 ::</option>
									<?
										while($row = sql_fetch_arr($result)) {
											if($category == $row['idx']) $selected = "selected";
											else $selected = "";
				          		echo "<option value='".$row['idx']."'>".$row['catname']."</option>";
										}
									?>
									</select>
									<?
									}
									?>
								</td>
          			<td class="rpad_3">
          				<select class="select" name="searchopt">
			            <option value="subject">제목
			            <option value="content">내용
			            </select>
          			</td>
          			<td><input type="text" size="13" name="searchkey" value="<?=$searchkey?>" class="input"></td>
          			<td width="2"></td>
          			<td><input type="submit" value="검색" align="absmiddle" title="검색" class="btnListchk3"><!-- <input type="image" src="../manage/image/btn_search.gif"> --></td>
          		</tr>
          	</table>
					</td>
        </tr>
        <tr><td height="10"></td></tr>
      </table>
	  </form>
      <script language="javascript">
      <!--
      if(document.searchForm.category != null){
      category = document.searchForm.category;
      for(ii=0; ii<category.length; ii++){
        if(category.options[ii].value == "<?=$category?>")
        category.options[ii].selected = true;
      }
    	}
      searchopt = document.searchForm.searchopt;
      for(ii=0; ii<searchopt.length; ii++){
        if(searchopt.options[ii].value == "<?=$searchopt?>")
        searchopt.options[ii].selected = true;
      }
      -->
      </script>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="layout:fixed;">
      	<form>
      	<tr><td colspan=20 class="t_rd"></td></tr>
        <tr class="t_th">
        	<th width="1"></th>
          <th width="45">번호</th>
          <th>제목</th>
          <th width="100">진열순서</th>
          <th width="10%">작성자</th>
          <th width="15%">작성일</th>
          <th width="5%">조회</th>
        </tr>
        <tr><td colspan=20 class="t_rd"></td></tr>
        </form>
      <?
      // 공지글 가져오기
      $sql = "select wb.idx,wb.name,wb.subject,wb.notice, wb.wdate as wtime,date_format(from_unixtime(wb.wdate), '%Y-%m-%d') as wdate,wb.count, wc.catname as category
      				from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
      				where wb.code = '$code' and wb.notice = 'Y'";
      $result = query($sql) or error("sql error");
      $n_total = sql_fetch_row($result);

      while($row = sql_fetch_arr($result)){
      ?>
			<tr>
				<td height="40" align="center"></td>
				<td align="center"><font color='red'>[공지]</font></td>
				<td style="word-break:break-all;"> <a href="view.php?code=<?=$code?>&idx=<?=$row['idx']?>&page=<?=$page?>"><?=$row['subject']?></a></td>
				<td align="center"><?=$row['prino']?></td>
				<td align="center"><?=$row['name']?></td>
				<td align="center"><?=$row['wdate']?></td>
				<td align="center"><?=$row['count']?></td>
			</tr>
			<tr><td colspan="20" class="t_line"></td></tr>
      <?
      }

			if($category) $category_sql = " and category = '$category' ";
			if($searchopt) $search_sql = " and $searchopt like '%$searchkey%' ";

			$sql = "select wb.*, wb.wdate as wtime, from_unixtime(wb.wdate, '%Y-%m-%d') as wdate, wc.catname
							from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
							where wb.code = '$code' $category_sql $search_sql order by wb.prino desc";

			$result = query($sql) or error("sql error");
			$total = sql_fetch_row($result);

      $rows = 20;
      $lists = 5;
      $ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
    	$page_count = ceil($total/$rows);
    	if(!$page || $page > $page_count) $page = 1;
    	$start = ($page-1)*$rows;
    	$no = $total-$start;

			$sql = "select wb.*, wb.wdate as wtime, from_unixtime(wb.wdate, '%Y-%m-%d') as wdate, wc.catname as category
							from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
							where wb.code = '$code' $category_sql $search_sql
							order by wb.prino desc limit $start, $rows";
			$result = query($sql) or error("sql error");

			while($row = sql_fetch_arr($result)){

				$name = $row['name']; $nick = $row['nick']; $wdate = $row['wdate']; $count = $row['count'];
				$tno = $no;
				$subject = $row['subject'];			//subject

				$comment=""; $category=""; $lock=""; $re=""; $new=""; $hot=""; $file=""; $re_space="";
				if($bbs_info['comment'] == "Y") $comment = "(".$row['comment'].")";
				if($row['category'] != "") $category = "[".$row['category']."]";																// category
				if($row['privacy'] == "Y") $lock = "<img src='../manage/image/bbs/lock.gif' border='0' align='absmiddle'>";							// privacy
				if($row['depno'] != 0) $re = "<img src='../manage/image/bbs/re.gif' border='0' align='absmiddle'>";																		// re
				$wtime = $row['wtime'];
				if(($ttime-$wtime)/86400 <= $bbs_info['newc']) $new = "<img src='../manage/image/bbs/new.gif' border='0' align='absmiddle'>";	// new
				if($row['count'] > $bbs_info['hotc']) $hot = "<img src='../manage/image/bbs/hot.gif' border='0' align='absmiddle'>";	// hot
				if(!empty($row['upfile1'])) $file = "<img src='../manage/image/bbs/file.gif' border='0' align='absmiddle'>";	// file
		    for($ii=0; $ii < $row['depno']; $ii++) $re_space .= "&nbsp;&nbsp;";						// respace

				if(img_type(WIZHOME_PATH."/data/member/".$row['memid']."_icon.gif")) $icon = "<img src='/twcenter/data/member/".$row['memid']."_icon.gif' align='absmiddle'>";
				else if(img_type(WIZHOME_PATH."/data/member/".$row['memid']."_icon.jpg")) $icon = "<img src='/twcenter/data/member/".$row['memid']."_icon.jpg' align='absmiddle'>";
				else $icon = "";

				if(!strcmp($bbs_info['name_type'], "name")) $name = $name;
				else if(!strcmp($bbs_info['name_type'], "nick") && !empty($nick)) $name = $nick;
				else if(!strcmp($bbs_info['name_type'], "icon") && !empty($icon)) $name = $icon;
				else if(!strcmp($bbs_info['name_type'], "iname")) $name = $icon." ".$name;
				else if(!strcmp($bbs_info['name_type'], "inick")) {
					if(!empty($nick)) $name = $icon." ".$nick;
					else $name = $icon." ".$name;
				}
			?>
		    <form action="save.php" onSubmit="return prinoCheck(this)">
		    <input type="hidden" name="idx" value="<?=$row['idx']?>">
		    <input type="hidden" name="code" value="<?=$code?>">
		    <input type="hidden" name="mode" value="prino">
		    <input type="hidden" name="param" value="<?=$param?>">
		    <tr>
		  	  <td height="40" align="center"></td>
          <td align="center"><?=$tno?></td>
          <td style="word-break:break-all;"><?=$re_space?><?=$re?><?=$category?><?=$subject?> <?=$comment?> <?=$lock?> <?=$new?> <?=$hot?> <?=$file?></td>
          <td align="center">
	          <input type="text" name="prino" value="<?=$row['prino']?>" class="input" size="4">
			  <input type="submit" value="수정" align="absmiddle" title="수정" class="optadd">
	          <!-- <input type="image" src="../manage/image/btn_edit_s.gif" style="cursro:pointer" align="absmiddle"> -->
         	</td>
          <td align="center"><?=$name?></td>
          <td align="center"><?=$wdate?></td>
          <td align="center"><?=$count?></td>
        </tr>
         <tr><td colspan="20" class="t_line"></td></tr>
        </form>
			<?
			$no--;
      }
      if($total <= 0 && $n_total <= 0){
      ?>
         <tr><td height=25 colspan=5 align=center>작성된 글이 없습니다.</td></tr>
         <tr><td height="1" colspan="10" bgcolor="EBEBEB"></td></tr>
      <?
      }
      ?>
      </table>

      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
      	<tr><td height="5"></td></tr>
        <tr>
           <td><? print_pagelist($page, $lists, $page_count, $param); ?>
		  <div class="right_btn"><input type="button" value="+ 일괄변경" class="btnListchk" onclick="priBbs()"></div>
		  </td>
        </tr>
      </table>

  	</td>
	</tr>
</table>

</body>
</html>