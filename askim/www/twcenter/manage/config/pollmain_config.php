<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/poll_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');
 ?>
<script Language="Javascript">
<!--
function phpCode(code){
	var php_code = "&lt;? \$code=\""+code+"\"; include \"\$_SERVER['DOCUMENT_ROOT']/twcenter/module/pollmain.php\"; // 설문조사 ?&gt;";
	set_ClipBoard(php_code);
}
//-->
</script>
			
			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">메인설문</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 메인설문을 생성합니다.</td>
        </tr>
      </table>
      
			<br><br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 메인설문 생성코드</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name2">
				<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">&nbsp;
                <?
                $php_code = "&lt;?php \$code=\"poll\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/pollmain.php\";     // 메인설문조사 ?&gt;";
                 ?>
                <script language="javascript">
                var clipboard = new Clipboard('#clipboardBtn');
                </script>
                <span id="clip"><font color=red><?php echo $php_code ?></font></span>
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
			- 스킨위치 : 메인설문 수정페이지에서 스킨설정이 가능합니다.<br>
			- 설문조사는 너비값이 100% 로 되어있으므로 적당한 크기에 테이블을 만든 후 테이블안에 생성합니다.
		  </div>
		</div>
	  </div>
      
      <?php

	$sql = "select code from wiz_pollinfo";
	$result = query($sql);
	$total = sql_fetch_row($result);

      $rows = 999;
      $lists = 5;
    	$page_count = ceil($total/$rows);
    	if(!$page || $page > $page_count) $page = 1;
    	$start = ($page-1)*$rows;
    	$no = $total-$start;

			$sql = "select * from wiz_pollinfo order by code asc limit $start, $rows";
			$result = query($sql);

			 ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="title_msg">총 설문수 : <strong id="total_prd_cnt"><?php echo $total ?></strong></span></td>
          <td align="right"></td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th width="10%">코드명</th>
          <th>설문조사명</th>
          <th width="15%">스킨</th>
          <th width="20%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
			<?
				while($row = sql_fetch_arr($result)){
			 ?>
		  <tr>
          <td height="35" align="center"><?php echo $no ?></td>
          <td align="center"><?php echo $row['code'] ?></td>
          <td align="center"><?php echo $row['title'] ?></td>
          <td align="center"><?php echo $row['skin'] ?></td>
          <td align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">
			<input type="button" value="수정" class="base_btm blue" onClick="document.location='pollmain_input.php?code=<?php echo $row['code'] ?>'">
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
     <?php
     		$no--;
      }

    	if($total <= 0){
    	 ?>
    		<tr><td height="35" colspan="10" align="center">등록된 게시판이 없습니다.</td></tr>
        <tr><td colspan="20" class="t_line"></td></tr>
    	<?
    	}
       ?>
      </table>

<?php include_once(WIZHOME_PATH.'/manage/foot.php');  ?>
