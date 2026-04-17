<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/manage/head.php');

$sql = "select * from wiz_siteinfo";
$result = query($sql);
$site_info = sql_fetch_arr($result);

// stripslashes()
$site_info['msg_url'] 	= stripslashes($site_info['msg_url']);

$page_name = "쪽지설정";
$page_desc = "아래코드를 삽입하여 쪽지관련 페이지를 생성합니다.  ";
$navi_name = "  환경설정  > 쪽지설정";
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
          <td valign="bottom" class="tit">쪽지</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 쪽지를 생성합니다.</td>
        </tr>
      </table>

			<br><br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 쪽지 생성코드</td>
        </tr>
      </table>
      <form name="frm" action="message_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="<?php echo $mode ?>">
      <input type="hidden" name="idx" value="<?php echo $idx ?>">
	  <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
        <tr>
          <td class="t_name" width="15%" colspan="2">스킨</td>
          <td class="t_value" align="left">

          <?php if(!@opendir("../../message")){  ?>

          <font color=red><b>쪽지기능을 신청하지 않았습니다. 추가비용은 10만원입니다.</b></font>

        	<?php }else{  ?>
          <select name="msg_skin" class="select">
          <?php
          $dh = opendir("../../message/skin");
          while(($file = readdir($dh)) !== false){
          	if($file != "." && $file != ".."){
          		$file_list[] = $file;
          	}
          }
          sort ($file_list); reset ($file_list);
          for($ii=0;$ii<count($file_list);$ii++){
           ?>
          <option value="<?php echo $file_list[$ii] ?>"><?php echo $file_list[$ii] ?></option>
          <?php
          }

          $file_list = "";
           ?>
          </select>
          <script language="javascript">
          <!--
            skin = document.frm.msg_skin;
            for(ii=0; ii<skin.length; ii++){
               if(skin.options[ii].value == "<?php echo $site_info['msg_skin'] ?>")
                  skin.options[ii].selected = true;
            }
          -->
          </script>
          스킨위치 : /twcenter/message/skin

          <?php }  ?>

          </td>
        </tr>
        <tr>
          <td class="t_name" colspan="2">쪽지 페이지</td>
          <td class="t_value" align="left">
          	&nbsp; http//<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="msg_url" value="<?php echo $site_info['msg_url'] ?>" size="40" class="input">
          </td>
        </tr>
        <tr>
          <td class="t_name">받은쪽지</td>
		  <td width="7%" class="t_value3" align="center">
		  	<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">
		  </td>
          <td class="t_value2" align="left">
          <?php
          $php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/msg_receive.php\";     // 받은쪽지 ?&gt;";
           ?>
          <script language="javascript">
		  var clipboard = new Clipboard('#clipboardBtn');
          </script>
          <span id="clip"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp;

          </td>
        </tr>
        <tr>
          <td class="t_name">보낸쪽지</td>
 		  <td width="5%" class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn2" data-clipboard-action="copy" data-clipboard-target="#clip2">
		  </td>
         <td class="t_value2" align="left">
          <?php
          $php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/msg_send.php\";     // 보낸쪽지 ?&gt;";
           ?>
          <script language="javascript">
		  var clipboard = new Clipboard('#clipboardBtn2');
          </script>
          <span id="clip2"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp;
          </td>
        </tr>
        <tr>
          <td class="t_name">회원목록</td>
 		  <td width="5%" class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn3" data-clipboard-action="copy" data-clipboard-target="#clip3">
		  </td>

          <td class="t_value2" align="left">
          <?php
          $php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/msg_member.php\";     // 회원목록 ?&gt;";
           ?>
          <script language="javascript">
		  var clipboard = new Clipboard('#clipboardBtn3');
          </script>
          <span id="clip3"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp;
          </td>
        </tr>
        <tr>
          <td class="t_name">친구목록</td>
 		  <td width="5%" class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn4" data-clipboard-action="copy" data-clipboard-target="#clip4">
		  </td>
          <td class="t_value2" align="left">
          <?php
          $php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/msg_friend.php\";     // 친구목록 ?&gt;";
           ?>
          <script language="javascript">
		  var clipboard = new Clipboard('#clipboardBtn4');
          </script>
          <span id="clip4"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp;

          </td>
        </tr>
        <tr>
          <td class="t_name">쪽지개수</td>
 		  <td width="5%" class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn5" data-clipboard-action="copy" data-clipboard-target="#clip5">
		  </td>
          <td class="t_value2" align="left">
          <?php
          $php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/msg_count.php\";     // 쪽지개수 ?&gt;";
           ?>
          <script language="javascript">
		  var clipboard = new Clipboard('#clipboardBtn5');
          </script>
          <span id="clip5"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp;
          <p>쪽지개수 출력 : &lt;?php echo $msg_count ?></p></font>
          </td>
        </tr>
      </table>
      <br>

      <?php if(@opendir("../../message")){  ?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">
			<input type="submit" value="확 인" class="base_btn reg">
          </td>
        </tr>
      </table>
	  </form>
    	<?php }  ?>

	  <div class="helpTip">
		<h4>체크사항</h4>
		<div class="content">
		  <div class="title">
		  </div>
		  <div class="explain">
			  - 스킨위치 :  /twcenter/message/skin<br>
			  - 환경설정 > 기본설정 > "쪽지 사용여부" 에서 사용함으로 설정 후 사용하세요<br>
		  </div>
		</div>
	  </div>


<?php include_once(WIZHOME_PATH.'/manage/foot.php'); ?>
