<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/manage/head.php');

$sql = "select * from wiz_siteinfo";
$result = query($sql) or error("sql_error");
$site_info = sql_fetch_arr($result);

// stripslashes()
$site_info['search_url'] 	= stripslashes($site_info['search_url']);

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
          <td valign="bottom" class="tit">전체검색</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 전체검색 페이지를 생성합니다.</td>
        </tr>
      </table>

			<br><br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 전체검색 생성코드</td>
        </tr>
      </table>

      <form name="frm" action="search_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
      <input type="hidden" name="tmp">
	  <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
        <tr>
          <td class="t_name" width="15%" colspan="2">스킨</td>
          <td class="t_value" align="left">

		  <span class="selBox150">
          <select name="search_skin" class="selectBox">
          <?
          $dh = opendir("../../search/skin");
          while(($file = readdir($dh)) !== false){
          	if($file != "." && $file != ".."){
          		$file_list[] = $file;
          	}
          }
          sort ($file_list); reset ($file_list);
          for($ii=0;$ii<count($file_list);$ii++){
           ?>
          <option value="<?php echo $file_list[$ii] ?>"><?php echo $file_list[$ii] ?></option>
          <?
          }

          $file_list = "";
           ?>
          </select></span>
          <script language="javascript">
          <!--
            skin = document.frm.search_skin;
            for(ii=0; ii<skin.length; ii++){
               if(skin.options[ii].value == "<?php echo $site_info['search_skin'] ?>")
                  skin.options[ii].selected = true;
            }
          -->
          </script>
          스킨위치 : /twcenter/search/skin

          </td>
        </tr>
        <tr>
          <td class="t_name" colspan="2">전체검색 페이지</td>
          <td class="t_value" align="left">
          	&nbsp; http//<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="search_url" value="<?php echo $site_info['search_url'] ?>" size="40" class="input">
          </td>
        </tr>
        <tr>
          <td class="t_name">검색폼</td>
		  <td width="7%" class="t_value3" align="center">
		  	<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">
		  </td>
		  <td class="t_value2" align="left">
          <?
          $php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/search.php\";     // 전체검색 ?&gt;";
           ?>
          <script language="javascript">
		  var clipboard = new Clipboard('#clipboardBtn');
          </script>
          <span id="clip"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp;
          </td>
        </tr>
        <tr>
          <td class="t_name">게시판 전체검색</td>
		  <td width="7%" class="t_value3" align="center">
		  	<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn2" data-clipboard-action="copy" data-clipboard-target="#clip2">
		  </td>
		  <td class="t_value2" align="left">
          <?
          $php_code = "&lt;?php \$stype = \"bbs\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/search_list.php\";     // 전체검색 페이지 ?&gt;";
           ?>
          <script language="javascript">
		  var clipboard = new Clipboard('#clipboardBtn2');
          </script>
          <span id="clip2"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp;

          </td>
        </tr>
        <tr>
          <td class="t_name">상품 전체검색</td>
		  <td width="7%" class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn3" data-clipboard-action="copy" data-clipboard-target="#clip3">
		  </td>
		  <td class="t_value2" align="left">
          <?
          $php_code = "&lt;?php \$stype = \"prd\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/search_list.php\";     // 전체검색 페이지 ?&gt;";
           ?>
          <script language="javascript">
		  var clipboard = new Clipboard('#clipboardBtn3');
          </script>
          <span id="clip3"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp;

          </td>
        </tr>
        <tr>
          <td class="t_name">페이지 전체검색</td>
		  <td width="7%" class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn4" data-clipboard-action="copy" data-clipboard-target="#clip4">
		  </td>

          <td class="t_value2" align="left">
          <?
          $php_code = "&lt;?php \$stype = \"page\"; include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/search_list.php\";     // 전체검색 페이지 ?&gt;";
           ?>
          <script language="javascript">
		  var clipboard = new Clipboard('#clipboardBtn4');
          </script>
          <span id="clip4"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp;

          </td>
        </tr>
      </table>
      <br>

      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">
			<input type="submit" value="확 인" class="base_btn reg">
          </td>
        </tr>
      </table>
	  </form>

	  <div class="helpTip">
		<h4>체크사항</h4>
		<div class="content">
		  <div class="title">
		  </div>
		  <div class="explain">
			- 스킨위치 :  /twcenter/search/skin<br>
		  </div>
		</div>
	  </div>

<?php include_once(WIZHOME_PATH.'/manage/foot.php'); ?>
