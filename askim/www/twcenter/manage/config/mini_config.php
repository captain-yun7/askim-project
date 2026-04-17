<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/manage/head.php');

$sql = "select * from wiz_siteinfo";
$result = query($sql);
$site_info = sql_fetch_arr($result);

// stripslashes()
$site_info['mini_url'] 	= stripslashes($site_info['mini_url']);
 ?>
<script language="javascript">
<!--
function inputCheck(frm){

}
-->
</script>
</head>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">미니홈피</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 미니홈피를 생성합니다.</td>
        </tr>
      </table>

			<br><br>
    	<table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 미니홈피 생성코드</td>
        </tr>
      </table>
      <form name="frm" action="mini_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="<?php echo $mode ?>">
      <input type="hidden" name="idx" value="<?php echo $idx ?>">
	  <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
        <tr width="15%">
          <td class="t_name" colspan="2">메인 페이지</td>
          <td class="t_value" align="left">
          	<?php if(!@opendir("../../mini")){  ?>

          	<font color=red><b>미니홈피기능을 신청하지 않았습니다. </b></font>

        		<?php }else{  ?>
          	&nbsp; http//<?php echo $HTTP_HOST ?>/<input type="text" name="mini_url" value="<?php echo $site_info['mini_url'] ?>" size="40" class="input"><br>
          	&nbsp; 브라우저에 바로 미니홈피 주소를 입력한경우 새창으로 미니홈피가 열리고 원래 브라우저는 위에서 설정한 주소로 이동합니다.
          	<?php }  ?>
          </td>
        </tr>
        <tr>
          <td class="t_name">내 미니홈피가기</td>
		  <td width="7%" class="t_value3" align="center">
		  	<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">
		  </td>

          <td class="t_value2" align="left">
          <?php
          $php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/mini/mini_my.php\";     // 내 미니홈피가기 ?&gt;";
           ?>
          <script language="javascript">
		  var clipboard = new Clipboard('#clipboardBtn');
          </script>
          <span id="clip"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp;
          </td>
        </tr>
      </table>
      <br>

      <?php if(@opendir("../../mini")){  ?>
      <table align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
          	<input type="image" src="../image/btn_confirm_l.gif"> &nbsp;
          	<img src="../image/btn_cancel_l.gif" style="cursor:hand" onClick="history.go(-1);">
          </td>
        </tr>
      </table>
      <?php }  ?>
	  </form>
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
				<p>&nbsp; &nbsp; <font color=#1D7CD4>&lt;?php include $_SERVER['DOCUMENT_ROOT'].'/twcenter/mini/mini_my.php';  // 내 미니홈피가기 ?&gt;</font></p>
				<p>&nbsp; &nbsp; &lt;a href="<font color=#1D7CD4>&lt;?php echo $mini_my ?&gt;</font>">내 미니홈피가기&lt;/a></p>

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
			  - 환경설정 > 기본설정 > "미니홈피 사용여부" 에서 사용함으로 설정 후 사용하세요
		  </div>
		</div>
	  </div>


<?php include_once(WIZHOME_PATH.'/manage/foot.php');  ?>
