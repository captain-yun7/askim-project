<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/admin_check.php');
include_once(WIZHOME_PATH.'/inc/site_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');
 ?>

<script language="javascript">
function phpCode(code){
	var php_code = "&lt;? \$code=\""+code+"\"; include \"\$_SERVER['DOCUMENT_ROOT']/twcenter/module/banner.php\"; ?&gt;";
	set_ClipBoard(php_code);
}
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">디자인관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 디자인관리를 설정합니다.</td>
        </tr>
      </table>

			<br><br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 디자인관리 생성코드</td>
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
                $php_code = "&lt;?php \$banner_code=\"banner01\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/banner.php\";     // 디자인 ?&gt;";
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
			- 사이트의 특정이미지를 수정/삭제하는 기능이나 이미지 추가를 원할경우 디자인관리를 이용합니다<br>
			- 디자인관리 코드명($code) 은 해당 디자인관리의 코드로 변경합니다.<br>
			- 디자인관리 메뉴에서 디자인를 추가/수정/삭제가 가능합니다.
		  </div>
		</div>
	  </div>


    <?php
	$sql = "select * from wiz_bannerinfo";
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
          <td><span class="title_msg">총 디자인그룹수 : <strong id="total_prd_cnt"><?php echo $total ?></strong></span></td>
          <td align="right"></td>
        </tr>
        <tr><td height="3"></td></tr>
      </table> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th width="10%">코드</th>
          <th>그룹명</th>
          <th width="10%">디자인형태</th>
          <th width="10%">사용여부</th>
          <th width="15%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
			<?
			while(($row = sql_fetch_obj($result)) && $rows){
				
				if(!strcmp($row->isuse, 'Y')) $row->isuse = "사용함";
				else $row->isuse = "사용안함";
				
				if(!strcmp($row->types, 'H')) $row->types = "세로형";
				else $row->types = "가로형";
			 ?>
		    <tr> 
          <td height="35" align="center"><?php echo $no ?></td>
          <td align="center"><?php echo $row->code ?></td>
          <td align=center><?php echo $row->title ?></td>
          <td align=center><?php echo $row->types ?></td>
          <td align=center><?php echo $row->isuse ?></td>
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
		<tr><td height=35 colspan=10 align=center>등록된 디자인그룹이 없습니다.</td></tr>
		<tr><td colspan="20" class="t_line"></td></tr>
		<?
		}
		 ?>
      </table>

<?php include_once(WIZHOME_PATH.'/manage/foot.php');  ?>
