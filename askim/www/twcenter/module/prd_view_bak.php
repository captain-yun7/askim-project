<?
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";

/**
1. 수정 내용 : 로그인시 퀵바의 회원가입 메뉴가 보이지 않도록 수정
2. 수정 일자 : 2015-05-28
3. 수정자 : 한상욱
*/
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/mem_info.php";
$join_html="";
if($wiz_session['id'] == ""){
  $join_html="<td colspan=3><a href=/member/join.php><img src=/img/quick_img03.gif width=80 height=27></a></td>";
}

$total = count($view_list);
$v_idx = $total-1;
$scroll_amount = 50;		// 한번에 스크롤되는 값
if($total > 0) $div_height = 200;

if($total <= 0) $prd_view .= "<tr><td height='71' align='center'></td></tr>";

if(!empty($prd_info['wish_url'])) $prd_info['wish_url'] = "/".$prd_info['wish_url'];
else $prd_info['wish_url'] = "\"javascript:alert('상품보관함 페이지가 설정되지 않았습니다.');\"";

if(!empty($prd_info['basket_url'])) $prd_info['basket_url'] = "/".$prd_info['basket_url'];
else $prd_info['basket_url'] = "\"javascript:alert('장바구니 페이지가 설정되지 않았습니다.');\"";


$prd_view = "
<script>
function gdscroll(gap){
 var gdscroll = document.getElementById('gdscroll');
 gdscroll.scrollTop += gap;
}
</script>

<table border=0 cellspacing=0 cellpadding=0>
<tr>
  <td colspan=3><a href=/shop/order_list.php><img src=/img/quick_img01.gif width=80 height=30></a></td>
</tr>
<tr>
  <!--<td width=1 bgcolor=e3e3e3></td>
  <td height=22 align=center class=font_12_1>(".$total."개)</td>
  <td width=1 bgcolor=e3e3e3></td>-->
  <td colspan=3><a href=/shop/basket.php><img src=/img/quick_img02.gif width=80 height=27></a></td>
</tr>
<tr>
  ".$join_html."
</tr>
<tr>
	
  <td>

  <table width=80 border=0 cellspacing=0 cellpadding=0>
    <tr>
      <td align=center valign=bottom><a href='javascript:gdscroll(-".$scroll_amount.")'><img src=/img/quick_img04.gif width=80 height=37></a></td>
    </tr>
    <tr><td height=4 style='background:url(/img/quick_bg.gif) repeat-y top left;'></td></tr>
    <tr>
      <td align=center valign=bottom>
      <div id=gdscroll style='height:".$div_height."px;overflow:hidden; background:url(/img/quick_bg.gif) repeat-y top left;'>
";

while(0 <= $v_idx){

	// 상품 이미지
	if(!@file($_SERVER['DOCUMENT_ROOT']."/twcenter/data/prdimg/".$view_list[$v_idx][prdimg])) $view_prdimg = "/twcenter/images/noimg_R.gif";
	else $view_prdimg = "/twcenter/data/prdimg/".$view_list[$v_idx][prdimg];

	$prd_view .= "<div><table><tr><td><a href=".$view_list[$v_idx][prdurl]."?ptype=view&prdcode=".$view_list[$v_idx][prdcode]."><img height=50 width=50 src=".$view_prdimg." border=0></a></td></tr></table></div>";
	$v_idx--;
}

$prd_view .= "
      </div>
			</td>
    </tr>
    <tr>
      <td height=14 align=center valign=bottom><a href='javascript:gdscroll(".$scroll_amount.")'><img src=/img/quick_img05.gif width=80 height=18></a></td>
    </tr>
    <!--<tr>
      <td height=24 align=center valign=bottom><a href=".$prd_info['wish_url']."><img src=/twcenter/images/prdview/btn_q_1.gif width=78 height=17 border=0></a></td>
    </tr>
    <tr>
      <td height=21 align=center valign=bottom><a href=".$prd_info['basket_url']."><img src=/twcenter/images/prdview/btn_q_2.gif width=78 height=17 border=0></a></td>
    </tr>-->
  </table></td>
	<td width=1 bgcolor=e3e3e3></td>
</tr>
<tr>
  <td colspan=3><a href=#top><img src=/img/quick_top.gif width=80 height=27></a></td>
</tr>
</table>
";

if($prd_info['right_scroll'] == "Y"){
	if($prd_info['site_align'] == "CENTER"){
		$site_width = ceil($prd_info['site_width']/2);
		echo "<div id='scrollingBanner' style='Z-INDEX:1;POSITION:absolute;LEFT:expression(document.body.clientWidth/2+".$site_width.")px;TOP:".$prd_info['right_starty']."px'>";
	}else{
		echo "<div id='scrollingBanner' style='Z-INDEX:1;POSITION:absolute;LEFT:".$prd_info['site_width']."px;TOP:".$prd_info['right_starty']."px'>";
	}
}else{
	echo "<div style='Z-INDEX:1;POSITION:absolute;LEFT:".$prd_info['site_width']."px;TOP:".$prd_info['right_starty']."px'>";
}

?>
	<table border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td><?=$prd_view?></td>
	  </tr>
	</table>
</div>

<script language="JavaScript" type="text/javascript">
<!--
  self.onError=null;
	currentX = 0;
	currentY = 0;
	whichIt = null;
	lastScrollX = 0; lastScrollY = 0; lastClientWhidth = 0;
	NS = (document.layers) ? 1 : 0;
	IE = (document.all) ? 1: 0;

	IE = (NS == 0 && IE == 0) ? 1 : IE;

	function heartBeat() {

		if(document.all.scrollingBanner != null){
			if(IE) {
				//diffY = document.body.scrollTop;
				//diffY = document.documentElement.scrollTop;
				if(document.body.scrollTop > document.documentElement.scrollTop) diffY = document.body.scrollTop;
				else diffY = document.documentElement.scrollTop;
				diffX = 0;
			}
			if(NS) { diffY = self.pageYOffset; diffX = self.pageXOffset; }
			if(diffY != lastScrollY) {
				percent = .05 * (diffY - lastScrollY);
				if(percent > 0) percent = Math.ceil(percent);
				else percent = Math.floor(percent);
				if(IE) document.all.scrollingBanner.style.pixelTop += percent;
				if(NS) document.scrollingBanner.top += percent;
				lastScrollY = lastScrollY + percent;
			}
			if(diffX != lastScrollX) {
				percent = .05 * (diffX - lastScrollX);
				if(percent > 0) percent = Math.ceil(percent);
				else percent = Math.floor(percent);
				if(IE) document.all.scrollingBanner.style.pixelLeft += percent;
				if(NS) document.scrollingBanner.left += percent;
				lastScrollX = lastScrollX + percent;
			}

			<? if($prd_info['site_align'] == "CENTER"){ ?>

			// 브라우저 창이 늘어나면 위치 수정
			siteWidth = <?=$site_width * 2?>;
			clientWhidth = document.body.clientWidth;
			if(clientWhidth < siteWidth) clientWhidth = siteWidth;	// 브라우저 창이 사이트 가로크기보다 작으면 사이트 가로크기를 기준으로

			if(clientWhidth != lastClientWhidth) {
				document.getElementById("scrollingBanner").style.left = eval((clientWhidth/2) + 520) + "px";
			}

			<? } ?>

		}
	}
	if(NS || IE) action = window.setInterval("heartBeat()",1);

//-->
</script>