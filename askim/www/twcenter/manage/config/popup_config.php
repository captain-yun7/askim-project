<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/site_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');
?>
		
		
			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">팝업관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 팝업을 생성합니다.</td>
        </tr>
      </table>

			<br><br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 팝업 생성코드</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td align="left" class="t_name2">
				
                <?
                $php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/popup.php\";     // 팝업관리 ?&gt;";
                ?>
                <script language="javascript">
                var clipboard = new Clipboard('#clipboardBtn');
                </script>
                <span id="clip"><font color=red><?=$php_code?></font></span>&nbsp; &nbsp;
                <input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">&nbsp;
                
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 적용예제</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name2">
<p><font color="#1D7CD4">&lt;?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/module/popup.php";     // 팝업관리 ?&gt;</font></p>
<p>&lt;!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"&gt;</p>
<p>&lt;html&gt;</p>
<p>&lt;head&gt;</p>
<p>&lt;title&gt;Untitled Document&lt;/title&gt;</p>
<p>&lt;meta http-equiv="Content-Type" content="text/html; charset=utf-8"&gt;</p>
<p>&lt;/head&gt;</p>
<p>&lt;body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"&gt;</p>

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
		  - 스킨위치 : /twcenter/popup/popup.php, popup_layer.php<br>
		  - 위의 팝업생성 코드를 웹사이트 첫페이지(index.htm, index.html, index.php)에 삽입합니다.<br>
		  - 팝업내용은 기본생성 > 팝업관리 에서 작성할 수 있습니다.
		  </div>
		</div>
	  </div>



<?php include_once(WIZHOME_PATH.'/manage/foot.php'); ?>
