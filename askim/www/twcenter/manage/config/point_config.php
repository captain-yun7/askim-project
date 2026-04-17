<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/site_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');
?>

<script language="javascript">
<!--
function inputCheck(frm){

}
//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">포인트</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 회원의 포인트를 생성합니다.</td>
        </tr>
      </table>

			<br><br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 포인트 생성코드</td>
        </tr>
      </table>
      <form name="frm" action="point_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
	  <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
        <tr>
          <td class="t_name" width="15%" colspan="2">스킨</td>
          <td class="t_value" align="left">

          <?php if(!@opendir("../../point")){ ?>

          <font color=red><b>포인트기능을 신청하지 않았습니다. 추가비용은 10만원입니다.</b></font>

          <?php }else{ ?>

          <select name="point_skin" class="select">
          <?php
          $dh = opendir("../../point/skin");
          while(($file = readdir($dh)) !== false){
          	if($file != "." && $file != ".."){
          		$file_list[] = $file;
          	}
          }
          sort ($file_list); reset ($file_list);
          for($ii=0;$ii<count($file_list);$ii++){
          ?>
          <option value="<?php echo $file_list[$ii]?>"><?php echo $file_list[$ii]?></option>
          <?php
          }

          $file_list = "";
          ?>
          </select>
          <script language="javascript">
          <!--
            skin = document.frm.point_skin;
            for(ii=0; ii<skin.length; ii++){
               if(skin.options[ii].value == "<?php echo $site_info['point_skin']?>")
                  skin.options[ii].selected = true;
            }
          -->
          </script>
          스킨위치 : /twcenter/point/skin

        	<?php } ?>

          </td>
        </tr>
        <tr>
          <td class="t_name" colspan="2">포인트내역 페이지</td>
          <td class="t_value2" align="left">
          	&nbsp; http//<?php echo $_SERVER['HTTP_HOST']?>/<input type="text" name="point_url" value="<?php echo $site_info['point_url']?>" size="40" class="input">
          </td>
        </tr>
        <tr>
          <td class="t_name">포인트내역</td>
		  <td width="7%" class="t_value3" align="center">
		  	<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">
		  </td>
		  <td class="t_value2" align="left">
          <?php
          $php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/mypoint.php\";     // 회원포인트내역 ?&gt;";
          ?>
          <script language="javascript">
		  var clipboard = new Clipboard('#clipboardBtn');
          </script>
          <span id="clip"><font color=red><?php echo $php_code?></font></span>&nbsp; &nbsp;
          </td>
        </tr>
        <tr>
          <td class="t_name">총 포인트</td>
		  <td width="7%" class="t_value3" align="center">
		  	<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn2" data-clipboard-action="copy" data-clipboard-target="#clip2">
		  </td>
          <td class="t_value2" align="left">
          <?php
          $php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/point.php\";     // 포인트 ?&gt;";
          ?>
          <script language="javascript">
		  var clipboard = new Clipboard('#clipboardBtn2');
          </script>
          <span id="clip2"><font color=red><?php echo $php_code?></font></span>&nbsp; &nbsp;
          <br>
          총 포인트 : &lt;?php echo $total_point ?></font>
          </td>
        </tr>
      </table>
      <br>

      <?php if(@opendir("../../point")){ ?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">
			<input type="submit" value="확 인" class="base_btn reg">
          </td>
        </tr>
      </table>
      <?php } ?>
	  </form>

	  <div class="helpTip">
		<h4>체크사항</h4>
		<div class="content">
		  <div class="title">
		  </div>
		  <div class="explain">
			  - 환경설정 > 기본설정 > "포인트 사용여부" 에서 사용함으로 설정 후 사용하세요<br>
			  - 포인트를 표현하고자 하는 위치 상단에 포인트 생성코드를 삽입합니다.<br>
			  - 총 포인트 : &lt;?=$total_point?&gt; &nbsp;
		  </div>
		</div>
	  </div>


<?php include_once(WIZHOME_PATH.'/manage/foot.php'); ?>
