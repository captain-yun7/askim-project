<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?
if($mode != "move"){
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>상품이동</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="javascript">
<!--
function inputCheck(frm){
	if(!confirm("상품을 이동하시겠습니까?")){
		return false;
	} else {
		frm.mode.value = "move";
	}
}

function catChange(form, idx){
	if(idx == "1"){
		form.dep2_code.options[0].selected = true;
		form.dep3_code.options[0].selected = true;
		form.dep4_code.options[0].selected = true;
	}else if(idx == "2"){
		form.dep3_code.options[0].selected = true;
		form.dep4_code.options[0].selected = true;
	}else if(idx == "3"){
		form.dep4_code.options[0].selected = true;
	}
	form.mode.value = "";
	form.submit();
}
-->
</script>
<body>

<table border="0" cellpadding="0" cellspacing="0" class="popupt">
	<tr>
		<td width="50%">상품이동</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<table align="center" width="100%" border="0" cellspacing="10" cellpadding="0">
	<tr>
		<td align="center" >

			<form name="frm" action="<?=$PHP_SELF?>" method="post" onSubmit="return inputCheck(this)">
			<input type="hidden" name="mode" value="">
			<input type="hidden" name="selvalue" value="<?=$selvalue?>">
			<input type="hidden" name="menucode" value="<?=$menucode?>">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td>
			      <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
			        <tr>
			          <td width="25%" class="t_name">이동할 상품분류</td>
			          <td width="75%" class="t_value">

			          	<select name="dep_code" onChange="catChange(this.form,'1');" class="select">
			            <option value=''>:: 1차분류 ::
			            <?
			            $sql = "select substring(catcode,1,2) as catcode, catname from wiz_category where depthno = 1 order by priorno01 asc";
			            $result = query($sql) or error("sql error");
			            while($row = sql_fetch_obj($result)){
			               if($row->catcode == $dep_code)
			                  echo "<option value='$row->catcode' selected>$row->catname";
			               else
			                  echo "<option value='$row->catcode'>$row->catname";
			            }
			            ?>
			            </select>
			          	<select name="dep2_code" onChange="catChange(this.form,'2');" class="select">
			            <option value=''> :: 2차분류 ::
			            <?
			            if($dep_code != ''){
			               $sql = "select substring(catcode,3,2) as catcode, catname from wiz_category where depthno = 2 and catcode like '$dep_code%' order by priorno02 asc";
			               $result = query($sql) or error("sql error");
			               while($row = sql_fetch_obj($result)){
			                  if($row->catcode == $dep2_code)
			                     echo "<option value='$row->catcode' selected>$row->catname";
			                  else
			                     echo "<option value='$row->catcode'>$row->catname";
			               }
			            }
			            ?>
			            </select>
			            <select name="dep3_code" onChange="catChange(this.form,'3');" class="select">
			            <option value=''> :: 3차분류 ::
			            <?
			            if($dep_code != '' && $dep2_code != ''){
			               $sql = "select substring(catcode,5,2) as catcode, catname from wiz_category where depthno = 3 and catcode like '$dep_code$dep2_code%' order by  priorno03 asc";
			               $result = query($sql) or error("sql error");
			               while($row = sql_fetch_obj($result)){
			                  if($row->catcode == $dep3_code)
			                     echo "<option value='$row->catcode' selected>$row->catname";
			                  else
			                     echo "<option value='$row->catcode'>$row->catname";
			               }
			            }
			            ?>
			            </select>&nbsp;
			            <select name="dep4_code" onChange="catChange(this.form,'4');" class="select">
			            <option value=''> :: 4차분류 ::
			            <?
			            if($dep_code != '' && $dep2_code != '' && $dep3_code != ''){
			               $sql = "select substring(catcode,7,2) as catcode, catname from wiz_category where depthno = 4 and catcode like '$dep_code$dep2_code$dep3_code%' order by  priorno04 asc";
			               $result = query($sql) or error("sql error");
			               while($row = sql_fetch_obj($result)){
			                  if($row->catcode == $dep4_code)
			                     echo "<option value='$row->catcode' selected>$row->catname";
			                  else
			                     echo "<option value='$row->catcode'>$row->catname";
			               }
			            }
			            ?>
			            </select>&nbsp;

			          </td>
			        </tr>
			      </table>
			    </td>
			  </tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center" style="padding:15px">
						<input type="submit" value=" 상품이동 " class="base_btn reg">
						<input type="button" value=" 닫기 " class="base_btn gray" onClick="self.close();">
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
</body>
</html>
<?
}else{

	$selarr = explode("|",$selvalue);

	for($ii=count($selarr); $ii>=0; $ii--){

		if($selarr[$ii]!=""){

		  // 카테고리 정보 저장
			if(empty($dep2_code)) $dep2_code = "00";
			if(empty($dep3_code)) $dep3_code = "00";
			if(empty($dep4_code)) $dep4_code = "00";

			$catcode = $dep_code.$dep2_code.$dep3_code.$dep4_code;

			$sql = "update wiz_cprelation set catcode='$catcode' where prdcode='$selarr[$ii]'";
			query($sql) or error("sql error");

		}
	}

	echo "<script>alert('이동되었습니다.');opener.document.location='prd_list.php?dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code&dep4_code=$dep4_code&$menucodeParam';self.close();</script>";
}
?>