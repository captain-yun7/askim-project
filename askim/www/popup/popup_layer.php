<?
if($popup_info['linkurl']) {
	$popup_info['linkurl'] = "onclick=window.open('".$popup_info['linkurl']."'); style='cursor:pointer'";
}
?>
<style>
@import url('/comm/css/root.css');
@import url('/comm/css/font.css');

p { margin-top: 0px; margin-bottom: 0px }
table, tr, td {color:var(--basic); margin:0; padding:0; font-family:var(--kor); font-size:15px; line-height:160%;}

.pop_layer {z-index:calc(9999999999 + <?=$popup_info['idx']?>); background-color:var(--white); position:absolute; border:1px solid #222;}



.close {color:rgba(255,255,255,.8); background:#222; font-size:12px; text-align:center; padding:10px 3px; line-height: 1; letter-spacing: -0.35px; width:30%; cursor:pointer;}
.oneDay {border-right:1px solid rgba(255,255,255,0.1); width:70%;}
.close input {vertical-align:middle; margin-left:8px;}
.close input[type='image'] {vertical-align: middle; margin:-2px 0 0 7px;}

/*******************************************************************************
	@media 1100px~1179px
*******************************************************************************/
@media all and (max-width:1179px){

.pop_layer {margin-left: 20px !important; top: 20px !important;}
.pop_layer_table{max-width:980px; height:auto;}
.pop_layer_table img{max-width:980px; height:auto !important}

}

/*******************************************************************************
	@media 681~1024px
*******************************************************************************/
@media all and (max-width:1024px){


.pop_layer{margin-left: 20px !important; top: 20px !important;}
.pop_layer_table{max-width:660px; height:auto;}
.pop_layer_table img{max-width:660px; height:auto !important}

}

/*******************************************************************************
	@media 461~680px
*******************************************************************************/
@media all and (max-width:670px){


.pop_layer{margin-left: 10px !important; top: 10px !important;}
.pop_layer_table{max-width:300px; height:auto;}
.pop_layer_table img{max-width:300px; height:auto !important}


}



/*******************************************************************************
	@media ~460px
*******************************************************************************/
@media all and (max-width:460px){

.pop_layer{margin-left: 10px !important; top: 10px !important;}
.pop_layer_table {max-width:260px; height:auto;}
.pop_layer_table img{max-width:260px; height:auto !important}


}

</style>

<script language="javascript">
<!--
  function popupClose<?=$popup_info['idx']?>(){
    setCookie("popupDayClose<?=$popup_info['idx']?>", "true", 1);
    popup<?=$popup_info['idx']?>.style.display = 'none';
  }

  function popupClose2<?=$popup_info['idx']?>(){
    popup<?=$popup_info['idx']?>.style.display = 'none';
}

//-->
</script>

<div id="popup<?=$popup_info['idx']?>" class="pop_layer" style="margin-left: <?=$popup_info['posi_x']?>px; top: <?=$popup_info['posi_y']?>px; ">
<table class="pop_layer_table" border="0" cellspacing="0" cellpadding="0" style="width:<?=$popup_info['size_x']?>px; height:<?=$popup_info['size_y']?>px;">
<thead></thead>
<tbody>
	<tr>
    <td valign="top" <?=$popup_info['linkurl']?>>
      <table border="0" cellpadding="0" cellspacing="0">
	  <thead></thead>
	  <tbody>
		  <tr><td id="popContent<?=$popup_info['idx']?>"><?=$popup_info['content']?></td></tr>
	  </tbody>
    
	  <tfoot></tfoot>
      </table>
      </td>
  </tr>
  <tr>
     <td height="35" >
	<table width="100%" height="35" bgcolor="#44484D" border="0" cellpadding="0" cellspacing="0">
	<thead></thead>
	<tbody>
		<tr>
          <td class="close oneDay" onClick="popupClose<?=$popup_info['idx']?>();">24시간동안 보지않음.<input type="image" src="/popup/popup_close.gif"  alt="24시간동안 보지않음." align="absmiddle"></td>
		<td class="close" onClick="popupClose2<?=$popup_info['idx']?>();">닫기<input type="image" src="/popup/popup_close.gif" alt="닫기" align="absmiddle"></td>
        </tr>
	</tbody>
		<tfoot></tfoot>
      </table>

	</td>
  </tr>
</tbody>
  
  <tfoot></tfoot>
</table>
</div>



<script language="javascript">
<!--
if(readCookie('popupDayClose<?=$popup_info['idx']?>')){
  popup<?=$popup_info['idx']?>.style.display = 'none';
}
-->
</script>