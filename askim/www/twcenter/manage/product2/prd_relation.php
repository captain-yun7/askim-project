<? include "../../common.php"; ?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="javascript">
<!--
function addReation(){
	<? if($mode == "insert"){ ?>
		alert("상품등록 후 관련상품을 등록하세요.");
	<? }else{ ?>
		var url = "prd_rellist.php?prdcode=<?=$prdcode?>";
		window.open(url, "addReation", "height=600, width=700, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, left=150, top=100");
	<? } ?>
}

function inputCheck(frm){

	var chk = document.getElementsByName('idx[]');
	if(chk == null){
		alert("삭제할 상품이 없습니다.");
		return false;
	}

	var idxLen=chk.length;

	if(idxLen == undefined){
	  if( chk.checked == false ){alert("상품이 선택되지 않았습니다.");chk.focus();return false;  }
	}else {
	  var ChkLike=0;
	  for(i=0;i<idxLen;i++){if( chk[i].checked == true ){ ChkLike=1; break;}}
	  if( ChkLike==0 ){alert("상품이 선택되지 않았습니다.");chk[0].focus();return false; }
	}

}
-->
</script>
<body topmargin="0" leftmargin="0">
<table border="0">
<form name="frm" action="prd_save.php" onSubmit="return inputCheck(this)" method="post">
<input type="hidden" name="mode" value="reldel">
<input type="hidden" name="prdcode" value="<?=$prdcode?>">
	<tr>
		<td width="50">
    	<table border="0">
    	<tr><td><img src="../image/btn_add.gif" style="cursor:pointer" onClick="addReation();"></td></tr>
    	<tr><td><input type="image" src="../image/btn_delete_s.gif"></td></tr>
      </table>
    </td>
<?
if($prdcode != ""){
	$rel_sql = "select wr.idx,wp.prdcode,wp.prdname,wp.prdimg_R from wiz_prdrelation2 wr, wiz_product2 wp where wr.prdcode = '$prdcode' and wr.relcode = wp.prdcode";
	$rel_result = query($rel_sql);
	while($rel_row = sql_fetch_obj($rel_result)){
?>
    <td align="center" width="70">
    	<a href="prd_input.php?mode=update&prdcode=<?=$rel_row->prdcode?>" target="_blank"><img src="/twcenter/data/product2/<?=$rel_row->prdimg_R?>" width="50" height="50" border="0"></a><br>
    	<input type="checkbox" name="idx[]" value="<?=$rel_row->idx?>">
    </td>
<?
	}
}
?>
  </tr>
</form>
</table>
</body>
</html>