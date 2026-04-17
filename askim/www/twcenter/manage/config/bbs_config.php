<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/site_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');
 ?>
    
<script language="javascript">
function phpCode(code){
	var php_code = "&lt;? \$code=\""+code+"\"; include \"\$_SERVER['DOCUMENT_ROOT']/twcenter/module/bbs.php\"; ?&gt;";
	set_ClipBoard(php_code);
}
function insertBbs(){
	var uri = "/twcenter/manage/bbs/bbs_input.php?mode=insert";
	window.open(uri,"insertBbs","top=200,left=200,width=550,height=280,resizable=yes");
}
</script>


			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">게시판</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 게시판을 생성합니다.</td>
        </tr>
      </table>

			<br><br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 게시판 생성코드</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td align="left" class="t_name2">
				<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">&nbsp;
                <?
                $php_code = "&lt;?php \$code=\"qna\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/bbs.php\";     // 게시판 ?&gt;";
                 ?>
                <script language="javascript">
                var clipboard = new Clipboard('#clipboardBtn');
                </script>
                <span id="clip"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp; 
                </td>
              </tr>
              <tr>
                <td align="left" class="t_name2">
				<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn2" data-clipboard-action="copy" data-clipboard-target="#clip2">&nbsp;

				<?
                $php_code = "&lt;?php \$code=\"qna\"; \$mobile_key=\"M\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/bbs.php\";     // 모바일게시판 ?&gt;";
                 ?>
                <script language="javascript">
                var clipboard = new Clipboard('#clipboardBtn2');
                </script>
                <span id="clip2"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp; 
                </td>
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
			  - 스킨위치 : /twcenter/bbs/skin<br>
			  - 게시판 위치에 게시판 생성코드를 삽입합니다.<br>
			  - 게시판 코드명($code) 은 해당 게시판의 코드로 변경합니다.<br>
			  - 게시판은 너비값이 100% 로 되어있으므로 적당한 크기에 테이블을 만든 후 테이블안에 삽입합니다.<br>
			  - 게시판정보에서 사이트 컨셉에 맞는 형태의 게시판 스킨선택이 가능합니다.<br>
		  </div>
		</div>
	  </div>

      <?php
		$level_info = level_info();
		
		$sql = "select * from wiz_bbsinfo where type = 'BBS' order by code";
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
          <td align="right"></td>
        </tr>
        <tr><td height="3"></td></tr>
      </table> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th width="10%">코드명</th>
          <th>게시판명</th>
          <th width="10%">목록보기</th>
          <th width="10%">내용보기</th>
          <th width="10%">글쓰기</th>
          <th width="10%">답글쓰기</th>
          <th width="10%">코멘트쓰기</th>
          <th width="13%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
		<?php
		while(($row = sql_fetch_obj($result)) && $rows){
		 ?>
		  <tr> 
          <td height="35" align="center"><?php echo $no ?></td>
          <td align="center"><?php echo $row->code ?></td>
          <td align="center"><?php echo $row->title ?></td>
          <td align="center"><?php echo $level_info[$row->lpermi]['name'] ?></td>
          <td align="center"><?php echo $level_info[$row->rpermi]['name'] ?></td>
          <td align="center"><?php echo $level_info[$row->wpermi]['name'] ?></td>
          <td align="center"><?php echo $level_info[$row->apermi]['name'] ?></td>
          <td align="center"><?php echo $level_info[$row->cpermi]['name'] ?></td>
          <td align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">
			<input type="button" value="모바일코드" class="base_btm reg" id="clipboardBtn2" data-clipboard-action="copy" data-clipboard-target="#clip2">

          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
     <?
     		$no--;
         $rows--;
      }
                           
    	if($total <= 0){
    	 ?>
    	<tr><td height="35" colspan="10" align="center">등록된 게시판이 없습니다.</td></tr>
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
