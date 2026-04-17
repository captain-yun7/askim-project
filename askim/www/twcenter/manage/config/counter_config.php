<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/site_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');

$page_name = "카운터";
$page_desc = "아래코드를 삽입하여 카운터를 생성합니다.";
$navi_name = " 환경설정 > 카운터";
?>


			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">카운터</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 카운터를 생성합니다.</td>
        </tr>
      </table>

			<br><br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 카운터설정 생성코드</td>
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
                $php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/counter.php\";     // 카운터 ?&gt;";
                ?>
                <script language="javascript">
				var clipboard = new Clipboard('#clipboardBtn');
                </script>
                <span id="clip"><font color=red><?=$php_code?></font></span>
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
				<p>&lt;body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"&gt;</p>
				<p>&lt;table width="100%" border="0" cellspacing="0" cellpadding="2"&gt;</p>
				<p>&nbsp; &lt;tr&gt;</p>
				<p>&nbsp; &nbsp; &lt;td align="center"&gt;</p>
				<p>&nbsp; &nbsp; <font color=#1D7CD4>&lt;?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/module/counter.php";     // 카운터 ?&gt;</font></p>
				<p>&nbsp; &nbsp; 오늘접속자 : <font color=#1D7CD4>&lt;?php echo $today_cnt ?&gt;</font></p>
				<p>&nbsp; &nbsp; 어제접속자 : <font color=#1D7CD4>&lt;?php echo $yester_cnt ?&gt;</font></p>
				<p>&nbsp; &nbsp; 전체접속자 : <font color=#1D7CD4>&lt;?php echo $total_cnt ?&gt;</font></p>
				<p>&nbsp; &nbsp; 현재접속자 : <font color=#1D7CD4>&lt;?php echo $now_cnt ?&gt;</font></p>

				<p>&nbsp; &nbsp; &lt;/td&gt;</p>
				<p>&nbsp; &lt;/tr&gt;</p>
				<p>&lt;/table&gt;</p>
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
			  - 카운터를 표현하고자 하는 위치 상단에 카운터 생성코드를 삽입합니다.<br>
			  - 오늘접속자 : &lt;?php echo $today ?&gt; &nbsp; 어제접속자 : &lt;?php echo $yester_cnt ?&gt; &nbsp; 전체접속자 : &lt;?php echo $total_cnt ?&gt; &nbsp; 현재접속자 : &lt;?php echo $now_cnt ?&gt;
		  </div>
		</div>
	  </div>


<?php include_once(WIZHOME_PATH.'/manage/foot.php'); ?>
