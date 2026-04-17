<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/prd_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/oper_info.php";
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/inc/site_info.php";

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

?>

<div class="shop_level">
	<ul>
		<li><p class="step">STEP1</p><p class="txt">장바구니</p></li>
		<li><p class="step">STEP2</p><p class="txt">주문하기</p></li>
		<li class="hover"><p class="step">STEP3</p><p class="txt">결제하기</p></li>
		<li><p class="step" >STEP4</p><p class="txt">주문완료</p></li>
	</ul>
</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>

      <table width="100%" border="0" cellpadding="0" cellspacing="0">
         <tr>
           <td align="left" class="shop_tit">고객님께서 주문하신 상품입니다.</td>
         </tr>
         <tr>
           <td bgcolor="#333333" height="1"></td>
         </tr>
      </table>

    	<?php include $_SERVER['DOCUMENT_ROOT']."/twcenter/product/basket_order.php"; ?>
   	</td>
  </tr>
</table>

<?php include Inc_payment($pay_method,$oper_info['pay_agent']); ?>