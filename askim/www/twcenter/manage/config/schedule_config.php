<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/manage/head.php');

if($save == "ok"){
	$sql = "update wiz_bbsinfo set pageurl='$pageurl' where code='$code'";
	query($sql);
	complete("수정되었습니다.","schedule_config.php");
	exit;
}

?>

<script language="javascript">
function phpCode(code){
	var php_code = "&lt;? \$code=\""+code+"\"; include \"\$_SERVER['DOCUMENT_ROOT']/twcenter/module/schedule.php\"; // 일정관리(대) ?&gt;";
	set_ClipBoard(php_code);
}
function phpCode2(code){
	var php_code = "&lt;? \$code=\""+code+"\"; include \"\$_SERVER['DOCUMENT_ROOT']/twcenter/module/schedule_s.php\"; // 일정관리(소) ?&gt;";
	set_ClipBoard(php_code);
}
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">일정관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 일정관리을 생성합니다.</td>
        </tr>
      </table>

			<br><br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 일정관리 생성코드</td>
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
                $php_code = "&lt;?php \$code=\"schedule\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/schedule.php\";     // 일정관리(대) ?&gt;";
                ?>
                <script language="javascript">
				var clipboard = new Clipboard('#clipboardBtn');
                </script>
                <span id="clip"><font color=red><?php echo $php_code ?></font></span>
                </td>
              </tr>
            </table>
            <br>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name2">
				<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn2" data-clipboard-action="copy" data-clipboard-target="#clip2">&nbsp;
                <?
                $php_code = "&lt;?php \$code=\"schedule\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/schedule_s.php\";     // 일정관리(소) ?&gt;";
                ?>
                <script language="javascript">
				var clipboard = new Clipboard('#clipboardBtn2');
                </script>
                <span id="clip2"><font color=red><?php echo $php_code?></font></span>
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
			- 스킨위치 : /twcenter/schedule/skin<br>
			- 일정관리 코드명은 해당 일정관리의 코드로 변경합니다.<br>
			- 일정관리는 너비값이 100% 로 되어있으므로 적당한 크기에 테이블을 만든 후 테이블안에 생성합니다.<br>
			- 페이지 주소는 일정관리(대)가 삽입된 페이지로 일정관리(소)를 클릭했을때 연결되는 페이지입니다.
		  </div>
		</div>
	  </div>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            	<tr><td class="t_rd" colspan=20></td></tr>
			        <tr class="t_th">
			          <th width="8%">번호</th>
			          <th width="10%">코드명</th>
			          <th>일정명</th>
			          <th width="40%">페이지 주소</th>
			          <th width="30%">기능</th>
			        </tr>
			        <tr><td class="t_rd" colspan=20></td></tr>
					<?
					$sql = "select * from wiz_bbsinfo where type = 'SCH' order by code";
					$result = query($sql);
					$total = sql_fetch_row($result);

					$no = $total;
					while($sch_info = sql_fetch_arr($result)) {
					?>
					<form name="frm<?php echo $sch_info['code']?>" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
					<input type=hidden name="save" value="ok">
					<input type=hidden name="code" value="<?php echo $sch_info['code']?>">
					  <tr>
					  	<td height="35" align="center"><?php echo $no ?></td>
				      	<td align="center"><?php echo $sch_info['code'] ?></td>
				      	<td align="center"><?php echo $sch_info['title'] ?></td>
				      	<td align="center">
				      		http://<?php echo $_SERVER['HTTP_HOST'] ?>/ <input type="text" name="pageurl" value="<?php echo $sch_info['pageurl'] ?>" class="input" size="35">
				      		<!-- <input type="image" src="../image/btn_confirm_s.gif" align="absmiddle"> -->
							<input type="submit" value="확인" class="base_btm blue">
				      	</td>
				      	<td align="center">
				      		<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip"><b>(대)</b> &nbsp;
				      		<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn2" data-clipboard-action="copy" data-clipboard-target="#clip2"><b>(소)</b>
				      	</td>
				      </tr>
			        <tr><td colspan="20" class="t_line"></td></tr>
            		</form>
					<?
						$no--;
					}
					?>
            </table>
          </td>
        </tr>
      </table>

<?php include_once(WIZHOME_PATH.'/manage/foot.php'); ?>
