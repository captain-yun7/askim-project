<?php
include_once('../common.php');
include_once(WIZHOME_PATH.'/inc/twcenter_check.php');
include_once(WIZHOME_PATH.'/inc/site_info.php');
include_once(WIZHOME_PATH.'/inc/prd_info.php');
include_once(WIZHOME_PATH.'/inc/oper_info.php');
include_once(WIZHOME_PATH.'/manage/head.php');

if($save == "ok"){
	$sql = "
	
	update wiz_prdinfo set 
	
		basket_url          = '$basket_url'
		,m_basket_url       = '$m_basket_url'
		,order_url          = '$order_url'
		,m_order_url        = '$m_order_url'
		,m_orderResult_url  = '$m_orderResult_url'
		,search_url         = '$search_url'
		,m_search_url       = '$m_search_url'
		,wish_url           = '$wish_url'
		,m_wish_url         = '$m_wish_url'
		,order_list_url     = '$order_list_url'
		,m_order_list_url   = '$m_order_list_url'
		,right_scroll       = '$right_scroll'
		,right_prdcnt       = '$right_prdcnt'
		,right_starty       = '$right_starty'
		,site_align         = '$site_align'
		,site_width         = '$site_width'
		
	";
	query($sql);

	// 카테고리 설정
	$sql = "
	
	update wiz_operinfo set 
	
		site_align         = '$site_align'
		,site_width        = '$site_width'
		,cate_sub          = '$cate_sub'
		,cate_suby         = '$cate_suby'
		,cate_subx         = '$cate_subx'
		,cate_menuh        = '$cate_menuh'
		
	";
	query($sql);

	complete("수정되었습니다.","prd_config.php");
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
	window.open(url,"ViewPrdmain","height=150, width=470, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, top=100, left=100");
}
//-->
</script>

<table border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td><img src="../image/ic_tit.gif"></td>
		<td valign="bottom" class="tit">상품관리</td>
		<td width="2"></td>
		<td valign="bottom" class="tit_alt">아래코드를 삽입하여 상품관리를 생성합니다.</td>
	</tr>
</table>
<br><br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif">  쇼핑몰 상품관리 생성코드</td>
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
						$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/product.php\";     // 상품관리 ?&gt;";
						 ?>
						<script language="javascript">
						//function copy_Phpcode1(){
						//	var php_code = "<?php echo str_replace("\"","\\\"",$php_code) ?>";
						//	set_ClipBoard(php_code);
						//}
						var clipboard = new Clipboard('#clipboardBtn');
						</script>
						<span id="clip"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp;
						

					</td>
				</tr>
			</table>
		</td>
	</tr>
</table><br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif">  일반 상품관리 생성코드</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td class="t_name2">
						<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn2" data-clipboard-action="copy" data-clipboard-target="#clip2">&nbsp;
						<?php
						$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/product2.php\";     // 상품관리 ?&gt;";
						 ?>
						<script language="javascript">
						//function copy_Phpcode2(){
						//	var php_code = "<?php echo str_replace("\"","\\\"",$php_code) ?>";
						//	set_ClipBoard(php_code);
						//}
						var clipboard = new Clipboard('#clipboardBtn2');
						</script>
						<span id="clip2"><font color=red><?php echo $php_code ?></font></span>&nbsp; &nbsp;
						
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table><br><br>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 카테고리/상품검색/오늘본상품 (쇼핑몰 상품에 해당)</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
	<tr>
		<td width="9%" class="t_name">카테고리</td>
		<td width="6%" class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn3" data-clipboard-action="copy" data-clipboard-target="#clip3">
		</td>
		<td class="t_value2">
			<?php
			$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/category.php\";     // 상품카테고리 ?&gt;";
			 ?>
			<script language="javascript">
			var clipboard = new Clipboard('#clipboardBtn3');
			</script>
			<span id="clip3"><font color=red><?php echo $php_code ?></font></span>

		</td>
	</tr>
	<tr>
		<td class="t_name">상품검색</td>
		<td class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn4" data-clipboard-action="copy" data-clipboard-target="#clip4">
		</td>
		<td class="t_value2">
			<?php
			$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/prd_search.php\"; // 상품검색 ?&gt;";
			 ?>
			<script language="javascript">
			var clipboard = new Clipboard('#clipboardBtn4');
			</script>
			<span id="clip4"><font color=red><?php echo $php_code ?></font></span>
		</td>
	</tr>
	<tr>
		<td class="t_name">오늘본상품</td>
		<td class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn5" data-clipboard-action="copy" data-clipboard-target="#clip5">
		</td>
		<td class="t_value2">
			<?php
			$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/prd_view.php\"; // 오늘본상품 ?&gt;";
			 ?>
			<script language="javascript">
			var clipboard = new Clipboard('#clipboardBtn5');
			</script>
			<span id="clip5"><font color=red><?php echo $php_code ?></font></span>
		</td>
	</tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 페이지 (쇼핑몰 상품에 해당)</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
	<tr>
		<td width="9%" class="t_name">장바구니</td>
		<td width="6%" class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn6" data-clipboard-action="copy" data-clipboard-target="#clip6">
		</td>
		<td class="t_value2">
			<?php
			$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/basket.php\";     // 장바구니 ?&gt;";
			 ?>
			<script language="javascript">
			var clipboard = new Clipboard('#clipboardBtn6');
			</script>
			<span id="clip6"><font color=red><?php echo $php_code ?></font></span>
		</td>
	</tr>
	<tr>
		<td class="t_name">상품주문</td>
		<td class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn7" data-clipboard-action="copy" data-clipboard-target="#clip7">
		</td>
		<td class="t_value2">
			<?php
			$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/order.php\";     // 상품주문 ?&gt;";
			 ?>
			<script language="javascript">
			var clipboard = new Clipboard('#clipboardBtn7');
			</script>
			<span id="clip7"><font color=red><?php echo $php_code ?></font></span>
		</td>
	</tr>
	<tr>
		<td class="t_name">주문배송조회</td>
		<td class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn8" data-clipboard-action="copy" data-clipboard-target="#clip8">
		</td>
		<td class="t_value2">
			<?php
			$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/order_list.php\";     // 주문배송조회 ?&gt;";
			 ?>
			<script language="javascript">
			var clipboard = new Clipboard('#clipboardBtn8');
			</script>
			<span id="clip8"><font color=red><?php echo $php_code ?></font></span>
		</td>
	</tr>
	<tr>
		<td class="t_name">적립금내역</td>
		<td class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn9" data-clipboard-action="copy" data-clipboard-target="#clip9">
		</td>
		<td class="t_value2">
			<?php
			$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/reserve.php\";     // 적립금 ?&gt;";
			 ?>
			<script language="javascript">
			var clipboard = new Clipboard('#clipboardBtn9');
			</script>
			<span id="clip9"><font color=red><?php echo $php_code ?></font></span>
		</td>
	</tr>
	<tr>
		<td class="t_name">상품보관함</td>
		<td class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn10" data-clipboard-action="copy" data-clipboard-target="#clip10">
		</td>
		<td class="t_value2">
			<?php
			$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/wish_list.php\";     // 상품보관함 ?&gt;";
			 ?>
			<script language="javascript">
			var clipboard = new Clipboard('#clipboardBtn10');
			</script>
			<span id="clip10"><font color=red><?php echo $php_code ?></font></span>
		</td>
	</tr>
	<tr>
		<td class="t_name">쿠폰내역</td>
		<td class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn11" data-clipboard-action="copy" data-clipboard-target="#clip11">
		</td>
		<td class="t_value2">
			<?php
			$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/coupon.php\";     // 쿠폰내역 ?&gt;";
			 ?>
			<script language="javascript">
			var clipboard = new Clipboard('#clipboardBtn11');
			</script>
			<span id="clip11"><font color=red><?php echo $php_code ?></font></span>
		</td>
	</tr>
	<tr>
		<td class="t_name">상품검색</td>
		<td class="t_value3" align="center">
			<input type="button" value="생성코드복사" class="base_btm reg" id="clipboardBtn12" data-clipboard-action="copy" data-clipboard-target="#clip12">
		</td>
		<td class="t_value2">
			<?php
			$php_code = "&lt;?php include \$_SERVER['DOCUMENT_ROOT'].\"/twcenter/module/prdsearch.php\";     // 상품검색 페이지 ?&gt;";
			 ?>
			<script language="javascript">
			var clipboard = new Clipboard('#clipboardBtn12');
			</script>
			<span id="clip12"><font color=red><?php echo $php_code ?></font></span>
		</td>
	</tr>
</table><br>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 페이지URL (쇼핑몰 상품에 해당)</td>
	</tr>
</table>
<form name="frm" action="<?php echo $PHP_SELF ?>" method="post">
<input type="hidden" name="save" value="ok">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" height="25" align="left" class="t_name">장바구니</td>
					<td width="85%" class="t_value2" colspan="3">
					http://<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="basket_url" value="<?php echo $prd_info['basket_url'] ?>" size="40" class="input">
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">상품주문</td>
					<td class="t_value2" colspan="3">
					http://<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="order_url" value="<?php echo $prd_info['order_url'] ?>" size="40" class="input">
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">상품검색</td>
					<td class="t_value2" colspan="3">
					http://<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="search_url" value="<?php echo $prd_info['search_url'] ?>" size="40" class="input">
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">상품보관함</td>
					<td class="t_value2" colspan="3">
					http://<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="wish_url" value="<?php echo $prd_info['wish_url'] ?>" size="40" class="input">
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">주문배송조회</td>
					<td class="t_value2" colspan="3">
					http://<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="order_list_url" value="<?php echo $prd_info['order_list_url'] ?>" size="40" class="input">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<?php echo $hide_mobile_start  ?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 모바일페이지URL (쇼핑몰 상품에 해당)</td>
	</tr>
</table>
<form name="frm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<input type="hidden" name="save" value="ok">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" height="25" align="left" class="t_name">장바구니</td>
					<td width="85%" class="t_value2" colspan="3">
					http://<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="m_basket_url" value="<?php echo $prd_info['m_basket_url'] ?>" size="40" class="input">
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">상품주문</td>
					<td class="t_value2" colspan="3">
					http://<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="m_order_url" value="<?php echo $prd_info['m_order_url'] ?>" size="40" class="input">
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">상품주문 결과페이지</td>
					<td class="t_value2" colspan="3">
					http://<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="m_orderResult_url" value="<?php echo $prd_info['m_orderResult_url'] ?>" size="40" class="input">
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">상품검색</td>
					<td class="t_value2" colspan="3">
					http://<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="m_search_url" value="<?php echo $prd_info['m_search_url'] ?>" size="40" class="input">
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">상품보관함</td>
					<td class="t_value2" colspan="3">
					http://<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="m_wish_url" value="<?php echo $prd_info['m_wish_url'] ?>" size="40" class="input">
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">주문배송조회</td>
					<td class="t_value2" colspan="3">
					http://<?php echo $_SERVER['HTTP_HOST'] ?>/<input type="text" name="m_order_list_url" value="<?php echo $prd_info['m_order_list_url'] ?>" size="40" class="input">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<?php echo $hide_mobile_end  ?>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 오늘본상품 (쇼핑몰 상품에 해당)</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
				<tr>
					<td width="15%" height="25" align="left" class="t_name">따라다니기</td>
					<td width="85%" class="t_value" colspan="3">
						<span style="vertical-align: middle"><input type="radio" name="right_scroll" value="Y" <?php if($prd_info['right_scroll']== "Y") echo "checked";  ?>></span>사용함
						<span style="vertical-align: middle"><input type="radio" name="right_scroll" value="N" <?php if($prd_info['right_scroll'] == "N") echo "checked";  ?>></span>사용안함
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">상품갯수</td>
					<td class="t_value" colspan="3">
						<input type="text" name="right_prdcnt" value="<?php echo $prd_info['right_prdcnt'] ?>" size="12" class="input"> 개
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">쇼핑몰정렬</td>
					<td class="t_value" colspan="3">
						<span style="vertical-align: middle"><input type="radio" name="site_align" value="LEFT" <?php if($prd_info['site_align'] == "LEFT" || $prd_info['site_align'] == "") echo "checked";  ?>></span>좌측정렬&nbsp;
						<span style="vertical-align: middle"><input type="radio" name="site_align" value="CENTER" <?php if($prd_info['site_align'] == "CENTER") echo "checked";  ?>></span>중앙정렬
						<span style="vertical-align: middle"><input type="radio" name="site_align" value="RIGHT" <?php if($prd_info['site_align'] == "RIGHT") echo "checked";  ?>></span>우측정렬
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">가로좌표</td>
					<td class="t_value" colspan="3">
						<input type="text" name="site_width" value="<?php echo $prd_info['site_width'] ?>" size="12" class="input"> 픽셀 (사이트 가로크기를 입력하세요.)
					</td>
				</tr>
				<tr>
					<td height="25" align="left" class="t_name">세로좌표</td>
					<td class="t_value" colspan="3">
						<input type="text" name="right_starty" value="<?php echo $prd_info['right_starty'] ?>" size="12" class="input"> 픽셀<br>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> 카테고리설정 (쇼핑몰 상품에 해당)</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<td width="15%" class="t_name">하위분류 출력여부</td>
		<td width="35%" class="t_value">
			<span style="vertical-align: middle"><input type="radio" name="cate_sub" value="Y" <?php if($oper_info['cate_sub'] == "Y") echo "checked";  ?>></span>사용함
			<span style="vertical-align: middle"><input type="radio" name="cate_sub" value="N" <?php if($oper_info['cate_sub'] == "N") echo "checked";  ?>></span>사용안함
		</td>
		<td width="15%" class="t_name">카테고리 메뉴높이</td>
		<td width="35%" class="t_value"><input type="text" name="cate_menuh" value="<?php echo $oper_info['cate_menuh'] ?>" size="10" class="input"> px</td>
	</tr>
	<tr>
		<td class="t_name">카테고리 세로좌표</td>
		<td class="t_value"><input type="text" name="cate_suby" value="<?php echo $oper_info['cate_suby'] ?>" size="10" class="input"> px</td>
		<td class="t_name">카테고리 가로좌표</td>
		<td class="t_value"><input type="text" name="cate_subx" value="<?php echo $oper_info['cate_subx'] ?>" size="10" class="input"> px</td>
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
<br>
<br>
  <div class="helpTip">
	<h4>체크사항</h4>
	<div class="content">
	  <div class="title">
	  </div>
	  <div class="explain">
		- 쇼핑몰 상품 스킨위치 : /twcenter/product/skin<br>
		- 일반상품 스킨위치 : /twcenter/product2/skin<br>
		<font color="#000000"><b>- 특정 상품분류 열기</b></font><br>
		&nbsp; &nbsp;방법1 : 생성코드 삽입시 코드($catcode)를 포함합니다.  <b>예)&lt;?php $catcode="100000"; include "$_SERVER['DOCUMENT_ROOT']..."</b><br>
		&nbsp; &nbsp;방법2 : 페이지 링크시 분류코드(catcode=100000) 를 추가합니다. <b>예) /product.php?catcode=1000000</b><br>
		- 분류코드는 "상품관리 > 상품분류 > 해당 분류 클릭 > 분류코드" 에 나옵니다.<br>

		<font color="#000000"><b>- 특정 상품그룹 열기</b></font><br>
		&nbsp; &nbsp;생성코드 삽입시 상품그룹($grp)를 포함합니다.  <b>예)&lt;?php $grp="best"; include $_SERVER['DOCUMENT_ROOT']...</b><br>
		- 상품그룹 <br>
		&nbsp; &nbsp;<b> 신상품 : $grp="new"; 베스트 : $grp="best"; 인기 : $grp="popular"; 추천 : $grp="recom"; 세일 : $grp="sale";
	  </div>
	</div>
  </div>

<?php include_once(WIZHOME_PATH.'/manage/foot.php'); ?>
