<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/site_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');
 ?>

<script language="javascript">
function phpCode(code){
	var php_code = "&lt;? \$page_code=\""+code+"\"; include \"\$_SERVER['DOCUMENT_ROOT']/twcenter/module/page.php\"; ?&gt;";
	set_ClipBoard(php_code);
}
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">페이지관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 페이지관리를 설정합니다.</td>
        </tr>
      </table>

			<br><br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 페이지관리 생성코드</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name2">
				<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">&nbsp;
                <?php
                $php_code = "&lt;?php \$page_code=\"page01\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/page.php\";     // 페이지 ?&gt;";
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
			- 제작완료 후에 관리자에서 직접 페이지 수정을 원할경우 페이지관리 기능을 이용합니다.<br>
			- 페이지 관리 메뉴에서 페이지를 추가/수정/삭제가 가능합니다.
		  </div>
		</div>
	  </div>

      <?php
		$sql = "select * from wiz_page";
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
          <td><span class="title_msg">총 페이지수 : <strong id="total_prd_cnt"><?php echo $total ?></strong></span></td>
          <td align="right"></td>
        </tr>
        <tr><td height="3"></td></tr>
      </table> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th width="10%">코드</th>
          <th>페이지</th>
          <th width="35%">주소</th>
          <th width="15%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
			<?
			while(($row = sql_fetch_obj($result)) && $rows){
			 ?>
		    <tr> 
          <td height="35" align="center"><?php echo $no ?></td>
          <td align="center"><?php echo $row->code ?></td>
          <td><?php echo $row->menu ?> > <?php echo $row->title ?></td>
          <td><a href="http://<?php echo $HTTP_HOST ?>/<?php echo $row->url ?>" target="_blank">http://<?php echo $HTTP_HOST ?>/<?php echo $row->url ?></a></td>
          <td align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
		<?
			$no--;
			$rows--;
		}       
		if($total <= 0){
		 ?>
		<tr><td height=35 colspan=10 align=center>등록된 페이지가 없습니다.</td></tr>
		<tr><td colspan="20" class="t_line"></td></tr>
		<?
		}
		 ?>
      </table>


<?php include_once(WIZHOME_PATH.'/manage/foot.php');  ?>
