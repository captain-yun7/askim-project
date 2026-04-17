<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/site_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');
 ?>


<script language="javascript">
function phpCode(code, idx){
	var php_code = "&lt;?\n \$code=\""+code+"\";\n \$category=\"\";\n \$bidx=\""+idx+"\";\n include \"\$_SERVER['DOCUMENT_ROOT']/twcenter/module/bbsmain.php\"; // 메인게시물\n?&gt;";
	set_ClipBoard(php_code);
}
function delBbsmain(code, idx) {
	if(confirm("삭제하시겠습니까?")) {
		document.location = "bbsmain_save.php?mode=delete&code="+code+"&idx="+idx;
	}
}
function viewBbsmain(code, idx) {
	var url = "bbsmain_view.php?bidx=" + idx + "&code=" + code;
	window.open(url,"ViewBBSMain","height=150, width=450, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, top=100, left=100");
}
function popCategory(code) {
	var url = "../bbs/category.php?code="+code;
	window.open(url,"BBSCategory","height=430, width=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}
</script>
			
			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">메인게시물</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 메인게시물을 생성합니다.</td>
        </tr>
      </table>
      
			<br><br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 메인게시물 생성코드</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td align="left" class="t_name2">
                <?php
                $php_code = "&lt;?php\\n \$code=\"qna\";\\n \$category=\"\";\\n \$bidx=\"8\";\\n include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/bbsmain.php\"; //메인게시물\\n ?&gt;";

                 ?>
                <script language="javascript">
				var clipboard = new Clipboard('#clipboardBtn');
                </script>
				<span id="clip"><font color=red><?php echo str_replace("\\n","<p>",$php_code) ?></font></span><br><br>
				<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip"><br><br>
				</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
	  <BR>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 게시판 자동생성 적용예제</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name2">
				<font color=red>
				<p>/**</p>
				<p> * 게시판 자동생성 영역 (TOP메뉴)</p>
				<p> */</p></font>
				<font color="#1D7CD4">
				<p>&lt;?php</p>
				<p>$page_code = "코드페이지값";</p>
				<p>$bbs_sql = "select code,title,prior from wiz_bbsinfo where grpmap='그룹매핑코드' and boardshow!='N' order by prior "; </p>
				<p>$bbs_result = query($bbs_sql);</p>
				<p>while($b_info = sql_fetch_arr($bbs_result)){</p>
				<p>?&gt;</p>
				<p>&lt;a href="/comm/auto_bbs.php?ptype=list&code=&lt;?=$b_info['code']?&gt;&pos=&lt;?=$b_info['prior']?&gt;&code_page=&lt;?=$page_code?&gt;">&lt;?=$b_info['title']?&gt;&lt;/a&gt;</p>

				<p>&lt;?php</p><p> } </p><p>?&gt;
                </font></p>

				<font color=red>
				<p>/**</p>
				<p> * 게시판 자동생성 영역 (LEFT메뉴)</p>
				<p> */</p><p></font></p>
				<p>&lt;?php</p>
				<p>break;</p>
				<p>case 'community':#커뮤니티</p><p></p>

				<font color=red><p>if(empty($_GET['po'])) $_GET['po'] = $_GET['pos'];</p><p></p>

				<p>$page_code = "코드페이지값";</p>
				<p>$bbs_sql = "select code,title,prior from wiz_bbsinfo where grpmap='그룹매핑코드' and boardshow!='N' and prior='$_GET['po']' order by prior ";</p>
				<p>$bbs_result = query($bbs_sql);</p>
				<p>$b_info = sql_fetch_arr($bbs_result);</p><p></p></font>

				<p>$location_array[0] = 'img/left_tit.gif';</p>
				<p>$location_array[1] = '<a href=free.php class=here_link>커뮤니티</a>';</p>
				<p>switch ( $_GET['po'] ) {</p>
				<p>	&nbsp;&nbsp;<font color=red>case $_GET['po']: $location_array[2] = $b_info['title']; break;</font></p>

				<p>}</p>
				<p>$location_array[3] = 'img/tit_sub'.$_GET['po'].'.gif';  #현재위치 타이틀 이미지</p>
				<p>?&gt;</p><p></p>


				<font color="#1D7CD4">
				<p>&lt;?php</p>
				<p>$page_code = "코드페이지값";</p>
				<p>$bbs_sql = "select code,title,prior from wiz_bbsinfo where grpmap='그룹매핑코드' and boardshow!='N' order by prior "; </p>
				<p>$bbs_result = query($bbs_sql);</p>
				<p>while($b_info = sql_fetch_arr($bbs_result)){</p>
				<p>	if($_GET['pos']==$b_info['prior']) $class_v = "class='left_linktop' "; else $class_v = "";</p>
				<p>?&gt;</p>
				<p>&lt;a href="/comm/auto_bbs.php?ptype=list&code=&lt;?=$b_info['code']?&gt;&pos=&lt;?=$b_info['prior']?&gt;&code_page=&lt;?=$page_code?&gt;">&lt;?=$b_info['title']?&gt;&lt;/a&gt;</p>

				<p>&lt;?php</p><p> } </p>?&gt;
                </font>                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

	  <div class="helpTip">
		<h4>체크사항</h4>
		<div class="content">
		  <div class="title">
		  </div>
		  <div class="explain">
			- 위 메인게시물 생성코드를 해당 위치에 삽입합니다.<br>
			- 메인게시물은 너비값이 100% 로 되어있으므로 적당한 크기에 테이블을 만든 후 테이블안에 생성합니다.<br>
			- 프레임으로 나눈 사이트의 경우 프레임 페이지 상단에 삽입합니다.<br>
			- 스킨위치 : 현재 페이지 메인게시물 수정/생성 페이지에서 수정이 가능합니다.<br>
			- <strong>$code : </strong>게시판 코드명($code) 은 해당 게시판의 코드로 변경합니다.<br>
			- <strong>$category : </strong>카테고리($category)를 설정하면 해당 카테고리 게시물만 추출합니다, 없으면 전체 게시물 추출.<br>
			- 카테고리 값은 "게시판관리 > 게시판설정 > 카테고리관리 > 링크값" 에서 확인가능합니다.<br>
			- <strong>$bidx : </strong>메인게시물 고유값($bidx) 은 해당 메인게시물의 번호로 변경합니다.

		  </div>
		</div>
	  </div>

	<?php
		$sql = "
			select wb.code
				 , wb.title
				 , wm.idx
				 , wm.cnt
				 , wm.subject_len 
			  from wiz_bbsinfo as wb 
			  left join wiz_bbsmain as wm 
			    on wb.code = wm.code 
			 where wb.type='BBS' 
			 order by wm.idx desc
		";
		$result = query($sql);
		$total = sql_fetch_row($result);
		
		$rows = 999;
		$lists = 5;
		$page_count = ceil($total/$rows);
		if(!$page || $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;
		$no = $total-$start;
	 ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="title_msg">총 게시판수 : <strong id="total_prd_cnt"><?php echo $total ?></strong></span></td>
          <td align="right">
			<input type="button" value="메인게시물 생성" class="btnListchk3" onClick="document.location='bbsmain_input.php?mode=insert&<?php echo $menucodeParam ?>';">

          </td>
        </tr>
        <tr><td height="3"></td></tr>
      </table> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="10%">번호($bidx)</th>
          <th>게시판코드</th>
          <th>게시판명</th>
          <th width="10%">게시물수</th>
          <th width="10%">제목 글자수</th>
          <th width="10%">카테고리</th>
          <th width="25%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
		<?php
		while(($row = sql_fetch_arr($result)) && $rows){
		 ?>
		  <tr> 
          <td height="38" align="center"><?php echo $row['idx'] ?></td>
          <td align="center"><?php echo $row['code'] ?></td>
          <td align="center"><?php echo $row['title'] ?></td>
          <td align="center"><?php echo $row['cnt'] ?></td>
          <td align="center"><?php echo $row['subject_len'] ?></td>
          <td align="center">
			<input type="button" value="보기" class="base_btm reg" onclick="popCategory('<?php echo $row['code'] ?>');">
		  </td>
          <td align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">
			<input type="button" value="미리보기" class="base_btm green" onClick="viewBbsmain('<?php echo $row['code'] ?>', '<?php echo $row['idx'] ?>')">
			<input type="button" value="수정" class="base_btm blue" onClick="document.location='bbsmain_input.php?code=<?php echo $row['code'] ?>&idx=<?php echo $row['idx'] ?>'">
			<input type="button" value="삭제" class="base_btm gray" onClick="delBbsmain('<?php echo $row['code'] ?>', '<?php echo $row['idx'] ?>')">

          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
     <?
     		$no--;
        $rows--;
      }
   
    	if($total <= 0){
    	 ?>
    		<tr><td height=38 colspan=8 align=center>등록된 게시판이 없습니다.</td></tr>
    		<tr><td colspan="20" class="t_line"></td></tr>
    	<?
    	}
       ?>
      </table>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td></td>
        </tr>
      </table>

<?php include_once(WIZHOME_PATH.'/manage/foot.php');  ?>
