<?
#;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
#
#  Subject : 상품카테고리 분류페이지(등록,수정,삭제)
#
#;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
include "../../common.php";
function print_position($catcode){

	global $prdcode;

	$catcode1 = substr($catcode,0,2);
	$catcode2 = substr($catcode,0,4);
	$catcode3 = substr($catcode,0,6);
	$catcode4 = substr($catcode,0,8);

	$sql = "
	
		SELECT
		
			*
			
		FROM
		
			wiz_category
			
		WHERE
		
			catuse != 'N' AND
			(catcode like '$catcode1%' AND depthno = 1) OR
			(catcode like '$catcode2%' AND depthno = 2) OR
			(catcode like '$catcode3%' AND depthno = 3) OR
			(catcode like '$catcode4%' AND depthno = 4) OR
			(catcode = '$catcode')
		
		ORDER BY depthno ASC
	";
	$result = query($sql);

	$now_position = " &nbsp; Home";
	while($row = sql_fetch_obj($result)){
		$now_position .= " &gt; $row->catname";
	}

	$now_position .= " <a href=prd_save.php?mode=catlist&submode=delete&prdcode=$prdcode&catcode=$catcode&popup=Y><font color=red>[삭제]</font></a>";

	return $now_position;
}

if($mode == "catlist"){

	if($submode == "insert"){

		if(!empty($class04)) {
			$catcode = $class04;
		} else {
			if(!empty($class03)) $catcode = $class03."00";
			else if(!empty($class02)) $catcode = $class02."0000";
			else {
				if(empty($class01)) $class01 = "0000";
				$catcode = $class01."000000";
			}
		}

		$sql = "select * from wiz_cprelation where prdcode = '$prdcode' and catcode = '$catcode'";
		$result = query($sql) or error("sql error");

		if($row = sql_fetch_obj($result)){
			error('이미등록된 분류입니다.');
		}else{
			$sql = "insert into wiz_cprelation(idx,prdcode,catcode) values('', '$prdcode', '$catcode')";
			$result = query($sql) or error("sql error");

			//complete('분류를 추가하였습니다.','');
			echo "<script>alert('분류를 추가하였습니다.');document.location.reload();</script>";

		}

	}else if($submode == "delete"){

		$sql = "delete from wiz_cprelation where prdcode = '$prdcode' and catcode = '$catcode'";
		$result = query($sql) or error("sql error");

		complete('선택한 분류를 삭제하였습니다.','');

	}

}
?>
<html>
<head>
<title>:: 상품 카테고리 ::</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript">
<!--
var prd_class = new Array();
<?
	$no = 0;
	$sql = "SELECT catcode, catname, depthno FROM wiz_category ORDER BY priorno01 ASC, priorno02 ASC, priorno03 ASC, priorno04 ASC";
	$result = query($sql);
	$total = sql_fetch_row($result);
	while($row = sql_fetch_obj($result)){

		$code01 = substr($row->catcode,0,2);
		$code02 = substr($row->catcode,0,4);
		$code03 = substr($row->catcode,0,6);
		$code04 = substr($row->catcode,0,8);

		if($row->depthno == 1){ $catcode = $code01; $parent = 0; }
		if($row->depthno == 2){ $catcode = $code02; $parent = $code01; }
		if($row->depthno == 3){ $catcode = $code03; $parent = $code02; }
		if($row->depthno == 4){ $catcode = $code04; $parent = $code03; }
?>

prd_class[<?=$no?>] = new Array();
prd_class[<?=$no?>][0] = "<?=$catcode?>";
prd_class[<?=$no?>][1] = "<?=$row->catname?>";
prd_class[<?=$no?>][2] = "<?=$parent?>";
prd_class[<?=$no?>][3] = "<?=$row->depthno?>";

<?
	$no++;
	}
?>
var tno = <?=$total?>;

function setClass01(){

	var arrayClass = eval("document.write_form.class01");
	var arrayClass1 = eval("document.write_form.class02");
	var arrayClass2 = eval("document.write_form.class03");
	var arrayClass3 = eval("document.write_form.class04");

	arrayClass.options[0] = new Option(":: 1차분류 ::","");
	arrayClass1.options[0] = new Option(":: 2차분류 ::","");
	arrayClass2.options[0] = new Option(":: 3차분류 ::","");
	arrayClass3.options[0] = new Option(":: 4차분류 ::","");

	for(no=0,sno=1 ; no < tno ; no++){
		if(prd_class[no][3]=='1'){
			arrayClass.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
			sno++;
		}
	}
}

function changeClass01(){

var arrayClass = eval("document.write_form.class01");
var arrayClass1 = eval("document.write_form.class02");
var arrayClass2 = eval("document.write_form.class03");
var arrayClass3 = eval("document.write_form.class04");

var selidx = arrayClass.selectedIndex;
var selvalue = arrayClass.options[selidx].value;

arrayClass1.options.length=0;
arrayClass2.options.length=0;
arrayClass3.options.length=0;
arrayClass1.options[0] = new Option(":: 2차분류 ::","");
arrayClass2.options[0] = new Option(":: 3차분류 ::","");
arrayClass3.options[0] = new Option(":: 4차분류 ::","");

	for(no=0,sno=1 ; no < tno ; no++){
		if(prd_class[no][3]=='2' && prd_class[no][2]==selvalue){
			arrayClass1.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
			sno++;
		}
	}

}

function changeClass02(){

	var arrayClass1 = eval("document.write_form.class02");
	var arrayClass2 = eval("document.write_form.class03");
	var arrayClass3 = eval("document.write_form.class04");

	var selidx = arrayClass1.selectedIndex;
	var selvalue = arrayClass1.options[selidx].value;

	arrayClass2.options.length=0;
	arrayClass3.options.length=0;
	arrayClass2.options[0] = new Option(":: 3차분류 ::","");
	arrayClass3.options[0] = new Option(":: 4차분류 ::","");

	for(no=0,sno=1 ; no < tno ; no++){
		if(prd_class[no][3]=='3' && prd_class[no][2]==selvalue){
			arrayClass2.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
			sno++;
		}
	}

}

function changeClass03(){

	var arrayClass2 = eval("document.write_form.class03");
	var arrayClass3 = eval("document.write_form.class04");

	var selidx = arrayClass2.selectedIndex;
	var selvalue = arrayClass2.options[selidx].value;

	arrayClass3.options.length=0;
	arrayClass3.options[0] = new Option(":: 4차분류 ::","");

	for(no=0,sno=1 ; no < tno ; no++){
		if(prd_class[no][3]=='4' && prd_class[no][2]==selvalue){
			arrayClass3.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
			sno++;
		}
	}

}

function changeClass04(){
}

function showChangeprice(){
	if(write_form.changeprice.checked == true){
		divprice.style.display = '';
	} else {
		divprice.style.display='none';
	}
}
function showOption02(){
	if(option02.style.display == ''){
		option02.style.display = 'none';
	} else {
		option02.style.display='';
	}
}
function showOption03(){
	if(option03.style.display == ''){
		option03.style.display = 'none';
	} else {
		option03.style.display='';
	}
}

-->
</script>
<body onLoad="setClass01();" topmargin=0 leftmargin=0>
<table><tr><td height="4"></td></table>
<table width="98%" align="center" border="0" cellspacing="0" cellpadding="2">
	<tr>
		<td class="tit_sub"><img src="../image/ics_tit.gif"> <?=$product?>상품 카테고리</td>
	</tr>
</table>
<form name="write_form" action="prd_save.php" method="post">
<input type="hidden" name="mode" value="catlist">
<input type="hidden" name="submode" value="insert">
<input type="hidden" name="prdcode" value="<?=$prdcode?>">
<table width="98%" align="center" border="0" cellspacing="1" cellpadding="2" class="t_style">
	<tr>
		<td height="25" width="120" class="t_name">&nbsp; &nbsp; 분류상태</td>
		<td width="380" class="t_value">
			<table>
			<?
			$sql = "SELECT * FROM wiz_cprelation WHERE prdcode='$prdcode'";
			$result = query($sql) or error("sql error");
			$t_cate = false;
			while($row = sql_fetch_obj($result)){
				$t_cate = true;
				if($row->catcode != '00000000'){
					echo "<tr><td>".print_position($row->catcode)."</td></tr>";
				}
			}

			if(!$t_cate) echo "<tr><td>카테고리 미분류</td></tr>";
			?>
			</table>
		</td>
	</tr>
	<tr>
		<td height="25" class="t_name">&nbsp; &nbsp; 분류추가<br></td>
		<td class="t_value"> &nbsp;
			<select name="class01" class="select" onChange="changeClass01();"></select>
			<select name="class02" class="select" onChange="changeClass02();"></select>
			<select name="class03" class="select" onChange="changeClass03();"></select>
			<select name="class04" class="select" onChange="changeClass04();"></select>&nbsp;
			<input type="image" src="../image/btn_insert_s.gif">
		</td>
	</tr>
</table>
</form>
</body>
</html>