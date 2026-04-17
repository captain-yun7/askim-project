<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/site_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');
 ?>

<script language="javascript">
function phpCode(code){
	var php_code = "&lt;? \$form_code=\""+code+"\"; include \"\$_SERVER['DOCUMENT_ROOT']/twcenter/module/form.php\"; ?&gt;";
	set_ClipBoard(php_code);
}
</script>

      <script language="JavaScript" type="text/javascript">
      <!--
      function delForm(idx){
        if(confirm('선택한 폼메일을 삭제하시겠습니까?')){
          document.location = 'form_save.php?mode=delete&idx=' + idx;
        }
      }
      //-->
      </script>
      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">폼메일</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 폼메일을 생성합니다.</td>
        </tr>
      </table>
      
			<br><br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 폼메일 생성코드</td>
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
                $php_code = "&lt;?php \$form_code = \"contact\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/form.php\"; // 폼메일 ?&gt;";
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
			  - 스킨위치 : /twcenter/form/skin<br>
			  - 폼메일 코드명은 해당 폼메일의 코드로 변경합니다.<br>
			  - 폼메일 생성 > 각 항목을 설정후 해당위치에 폼메일 생성 코드를 삽입합니다.<br>
			  - 작성된 폼메일내용은 관리자 > 폼메일관리 메뉴에서 확인가능합니다.
		  </div>
		</div>
	  </div>

	<?
	$sql = "select * from wiz_forminfo where code != ''";
	$result = query($sql) or error("sql error");
	$total = sql_fetch_row($result);
	
	$rows = 999;
	$lists = 5;
	$page_count = ceil($total/$rows);
	if(!$page || $page > $page_count) $page = 1;
	$start = ($page-1)*$rows;
	$no = $total-$start;
	if($start>1) mysqli_data_seek($result,$start);
	 ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><span class="title_msg">총 폼메일수 : <strong id="total_prd_cnt"><?php echo $total ?></strong></span></td>
          <td align="right">
			<input type="button" value="폼메일생성" class="btnListchk3" onClick="document.location='form_input.php?mode=insert';">
		  </td>
        </tr>
        <tr><td height="3"></td></tr>
      </table> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th width="20%">폼메일코드</th>
          <th>폼메일명</th>
          <th width="20%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
			<?
			while(($row = sql_fetch_obj($result)) && $rows){
			 ?>
		  <tr> 
          <td height="35" align="center"><?php echo $no ?></td>
          <td align="center"><?php echo $row->code ?></td>
          <td align="center"><?php echo $row->title ?></td>
          <td align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">
			<input type="button" value="수정" class="base_btm blue" onClick="document.location='form_input.php?mode=update&idx=<?php echo $row->idx ?>'">
			<input type="button" value="삭제" class="base_btm gray" onClick="delForm('<?php echo $row->idx ?>')">
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
				<?
					$no--;
					$rows--;
				}       
				if($total <= 0){
				 ?>
				<tr><td height=35 colspan=10 align=center>등록된 폼메일이 없습니다.</td></tr>
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
