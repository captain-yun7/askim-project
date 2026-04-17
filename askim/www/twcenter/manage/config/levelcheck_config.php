<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/site_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');
 ?>

			
			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">페이지접근권한</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 페이지접근권한을 설정합니다.</td>
        </tr>
      </table>

			<br><br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 페이지접근권한 생성코드</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name2">
                <?php
                $php_code = "&lt;?php\\n\$level = 3;\\n\$msg = \"접근권한이 없습니다.\";\\n\$backurl = \"/\";\\ninclude \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/levelcheck.php\"; \\n?&gt;";
                 ?>
                <script language="javascript">
				var clipboard = new Clipboard('#clipboardBtn');
                </script>
                <span id="clip"><font color=red><?php echo str_replace("\\n","<p>",$php_code) ?></font></span><br>
				<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip"><br>
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
			  - 레벨별 접근권한 기능외에 로그인 체크기능으로도 이용할 수 있습니다.<br><br>
			  <b>$level</b> : 접근권한 레벨을 지정합니다. ( 회원관리>회원등급>등급레벨 값을 입력합니다. )&nbsp; 3을 입력한경우 3과 같거나 작은경우 페이지 접근이 가능합니다.<br>
			  <b>$msg</b> : 권한이 없을경우 경고 메세지를 설정합니다. 입력하지 않는경우 기본은 "권한이 없습니다." 입니다.<br>
			  <b>$backurl</b> : 권한이 없어 경고창이 뜬경우 이동할 페이지를 입력합니다. 기본은 history.back(); 입니다.
		  </div>
		</div>
	  </div>


<?php include_once(WIZHOME_PATH.'/manage/foot.php'); ?>
