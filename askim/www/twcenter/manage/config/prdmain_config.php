<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/prd_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');

if($save == "ok"){
	//$sql = "update wiz_prdinfo set skin='$skin',purl='$purl', prdcnt='$prdcnt', prdline='$prdline', maintype='$maintype', mainskin='$mainskin', prdname_len='$prdname_len'";
	$sql = "update wiz_prdinfo set skin='$skin'";
	query($sql);
	complete("수정되었습니다.","product_config.php");
	exit;
}
 ?>
<script Language="Javascript">
<!--
function phpCode(idx){
	var php_code = "&lt;?php \$pidx=\""+idx+"\"; include \"\$_SERVER['DOCUMENT_ROOT']/twcenter/module/prdmain.php\"; // 상품추출 ?&gt;";
	set_ClipBoard(php_code);
}

function delPrdmain(idx) {
	if(confirm("삭제하시겠습니까?")) {
		document.location = "prdmain_save.php?mode=delete&idx="+idx;
	}
}
function viewPrdmain(idx) {
	var url = "prdmain_view.php?pidx=" + idx;
	window.open(url,"ViewPrdmain","height=150, width=750, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, top=100, left=100");
}
//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">메인상품</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">아래코드를 삽입하여 메인상품를 생성합니다.</td>
        </tr>
      </table>

			<br><br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif">  메인페이지 쇼핑몰 상품추출 생성코드</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name2" style="padding-top:10px; padding-bottom:10px;">
                <?php
                $php_code = "&lt;?php\\n \$pidx=\"1\";\\n \$pcategory=\"\";\\n include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/prdmain.php\";     // 상품추출\\n?&gt;";
                 ?>
                <script language="javascript">
				var clipboard = new Clipboard('#clipboardBtn');
                </script>
                <span id="clip"><font color=red><?php echo str_replace("\\n","<p>",$php_code) ?></font></span>
				<br />
				<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">&nbsp;<br>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

		<br>
			
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 메인페이지 일반상품추출 생성코드</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name2" style="padding-top:10px; padding-bottom:10px;">
                <?php
                $php_code = "&lt;?php\\n \$pidx=\"1\";\\n \$pcategory=\"\";\\n include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/prdmain2.php\";     // 일반 상품추출\\n?&gt;";
                 ?>
                <script language="javascript">
				var clipboard = new Clipboard('#clipboardBtn2');
                </script>
                <span id="clip2"><font color=red><?php echo str_replace("\\n","<p>",$php_code) ?></font></span><br />
				<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn2" data-clipboard-action="copy" data-clipboard-target="#clip2">&nbsp;<br>
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
			  - 메인상품 생성코드를 복사 후 해당 위치에 삽입합니다.<br>
			  - 메인상품은 너비값이 100% 로 되어있으므로 적당한 크기에 테이블을 만든 후 테이블안에 생성합니다.<br>
			  - 최근등록상품 또는 추천상품 두가지 형태로 추출이 가능합니다.<br>
			  - 카테고리($pcategory)를 설정하면 해당 카테고리 상품만 추출합니다, 없으면 전체 상품 추출.<br>
				&nbsp;&nbsp;카테고리 값은 "상품관리 > 상품분류 > 분류코드" 에서 확인가능합니다.<br>
			  - 스킨위치 : 현재 페이지 생성/수정 페이지에서 가능합니다.<br>
			  - $pcategory : 상품카테고리(미지정시 전체 상품 추출)<br>
			  - $pidx : 메인상품 고유값
			  - <u>일반상품일 경우 최근등록상품, 추천상품을 지정할 수 있으며 인기/베스트/세일/신상품까지 모두 지정 가능합니다.</u>
		  </div>
		</div>
	  </div>

	<?php
	$sql = "select * from wiz_prdmain order by idx desc";
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
          <td><span class="title_msg">총 상품추출수 : <strong id="total_prd_cnt"><?php echo $total ?></strong></span></td>
          <td align="right">
			<input type="button" value="상품추출생성" class="btnListchk3" onClick="document.location='prdmain_input.php?mode=insert&<?php echo $menucodeParam ?>';">

          </td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">번호($pidx)</th>
          <th>추출상품</th>
          <th>상품명 글자수</th>
          <th>상품수</th>
          <th>줄바꿈 상품수</th>
          <th width="30%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
				<?php
				while(($row = sql_fetch_arr($result)) && $rows){

				  switch($row['maintype']) {
				  	case "wdate"    : $row['maintype'] = "최근등록상품 "; break;
				  	case "recom"    : $row['maintype'] = "추천상품 "; break;
				  	case "popular"	: $row['maintype'] = "인기상품 "; break;
				  	case "best" 	: $row['maintype'] = "베스트상품 "; break;
				  	case "sale" 	: $row['maintype'] = "세일상품 "; break;
				  	case "new" 		: $row['maintype'] = "신상품 "; break;
				  }
				 ?>
		  	<tr>
          <td height="38" align="center"><?php echo $row['idx'] ?></td>
          <td align="center"><?php echo $row['maintype'] ?></td>
          <td align="center"><?php echo $row['prdname_len'] ?></td>
          <td align="center"><?php echo $row['prdcnt'] ?></td>
          <td align="center"><?php echo $row['prdline'] ?></td>
          <td align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn" data-clipboard-action="copy" data-clipboard-target="#clip">
			<input type="button" value="미리보기" class="base_btm green" onClick="viewPrdmain('<?php echo $row['idx'] ?>')">
			<input type="button" value="수정" class="base_btm blue" onClick="document.location='prdmain_input.php?idx=<?php echo $row['idx'] ?>'">
			<input type="button" value="삭제" class="base_btm gray" onClick="delPrdmain('<?php echo $row['idx'] ?>')">
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
	     <?php
	     		$no--;
	         $rows--;
	      }

	    	if($total <= 0){
	    	 ?>
	    		<tr><td height=38 colspan=8 align=center>등록된 상품추출이 없습니다.</td></tr>
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




<?php include_once(WIZHOME_PATH.'/manage/foot.php'); ?>
