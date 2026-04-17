<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<?
if($mode != "copy"){
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>상품복사</title>
<link href="../wiz_style.css" rel="stylesheet" type="text/css">
<script language="javascript">
<!--
function inputCheck(frm){

	if(!confirm("상품을 복사하시겠습니까?")){
		return false;
	} else {
		frm.mode.value = "copy";
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
		<td width="50%">상품복사</td>
		<td align="right"><img src="../image/btn_pop_close2.png" onClick="self.close();" style="cursor:pointer"></td>
	</tr>
</table>
<span class="tip_br5"></span>

<table align="center" width="100%" border="0" cellspacing="10" cellpadding="0">
	<tr>
		<td align="center">

			<form name="frm" action="<?=$PHP_SELF?>" method="post" onSubmit="return inputCheck(this)">
			<input type="hidden" name="mode" value="">
			<input type="hidden" name="selvalue" value="<?=$selvalue?>">
			<input type="hidden" name="menucode" value="<?=$menucode?>">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td>
			      <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
			        <tr>
			          <td width="25%" align="center" class="t_name">복사할 상품분류</td>
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
						<input type="submit" value=" 상품복사 " class="base_btn reg">
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

	$upfile_path = "../../data/prdimg";

	$selarr = explode("|",$selvalue);

	for($ii=count($selarr); $ii>=0; $ii--){

		if($selarr[$ii]!=""){

			$prdcode = $selarr[$ii];

			// 기존상품 정보
			$sql = "select * from wiz_product where prdcode='$prdcode'";
			$result = query($sql) or error("sql error");
			$prd_info = sql_fetch_obj($result);


			// 상품넘버 만들기
			$sql = "select max(prdcode) as prdcode, max(prior) as prior from wiz_product";
			$result = query($sql) or error("sql error");
			if($row = sql_fetch_obj($result)){

				$datenum = substr($row->prdcode,0,6);
				$prdnum = substr($row->prdcode,6,4);
				$prdnum = substr("000".(++$prdnum),-4);

				if($datenum == date('ymd')) $prdcode = $datenum.$prdnum;
				else $prdcode = date('ymd')."0001";

				// 상품진열 순서
				$prior = $row->prior + 1;

			}else{
				$prdcode = date('ymd')."0001";

				// 상품진열 순서
				$prior = date(ymdHis);

			}

			// 상품이미지
			$prdimg_path = "../../data/prdimg";
			$prdimg_R_name = $prdcode."_R.".substr($prd_info->prdimg_R,-3);
			if(@file($prdimg_path."/".$prd_info->prdimg_R)) copy($prdimg_path."/".$prd_info->prdimg_R, $prdimg_path."/".$prdimg_R_name);

			$prdimg_L_sql = "";
			$prdimg_M_sql = "";
			$prdimg_S_sql = "";

			for($j=1; $j<=5; $j++){

				$L_img_info = pathinfo($prd_info->{'prdimg_L'.$j}, PATHINFO_EXTENSION);
				$M_img_info = pathinfo($prd_info->{'prdimg_M'.$j}, PATHINFO_EXTENSION);
				$S_img_info = pathinfo($prd_info->{'prdimg_S'.$j}, PATHINFO_EXTENSION);

				${'prdimg_L'.$j."_name"} = $prdcode."_L".$j.".".$L_img_info;
				${'prdimg_M'.$j."_name"} = $prdcode."_M".$j.".".$M_img_info;
				${'prdimg_S'.$j."_name"} = $prdcode."_S".$j.".".$S_img_info;

				if(@file($prdimg_path."/".$prd_info->{'prdimg_L'.$j})) 
					copy($prdimg_path."/".$prd_info->{'prdimg_L'.$j}, $prdimg_path."/".${'prdimg_L'.$j."_name"});
				if(@file($prdimg_path."/".$prd_info->{'prdimg_M'.$j})) 
					copy($prdimg_path."/".$prd_info->{'prdimg_M'.$j}, $prdimg_path."/".${'prdimg_M'.$j."_name"});
				if(@file($prdimg_path."/".$prd_info->{'prdimg_S'.$j})) 
					copy($prdimg_path."/".$prd_info->{'prdimg_S'.$j}, $prdimg_path."/".${'prdimg_S'.$j."_name"});

				if($L_img_info) $prdimg_L_sql .= ", prdimg_L".$j." = '".${'prdimg_L'.$j."_name"}."'       ";
				if($M_img_info) $prdimg_M_sql .= ", prdimg_M".$j." = '".${'prdimg_M'.$j."_name"}."'       ";
				if($S_img_info) $prdimg_S_sql .= ", prdimg_S".$j." = '".${'prdimg_S'.$j."_name"}."'       ";

			}

			$prd_info->content = addslashes($prd_info->content);
			$prd_info->prior = $prior;

			$opt_title_sql  = "";
			$opt_code_sql   = "";
			for($i=2; $i<=11; $i++) {
				$opt_title_sql  .= " , opttitle".$i."         = '".$prd_info->{'opttitle'.$i}."'            ";
				$opt_code_sql   .= " , optcode".$i."          = '".$prd_info->{'optcode'.$i}."'             ";
			}

			$info_name_sql  = "";
			$info_value_sql = "";
			for($i=1; $i<=6; $i++) {
				$info_name_sql  .= " , info_name".$i."          = '".$prd_info->{'info_name'.$i}."'            ";
				$info_value_sql .= " , info_value".$i."         = '".$prd_info->{'info_value'.$i}."'           ";
			}

			$opt_req_sql  = "";
			for($i=3; $i<=11; $i++) {
				$opt_req_sql  .= " , opt".$i."_req            = '".$prd_info->{'opt'.$i.'_req'}."'           ";
			}

			// 상품정보 저장
			$sql_com = "";
			$sql_com .= " prdcode               = '$prdcode'                    ";
			$sql_com .= " , prdname             = '$prd_info->prdname'          ";
			$sql_com .= " , prdcom              = '$prd_info->prdcom'           ";
			$sql_com .= " , origin              = '$prd_info->origin'           ";
			$sql_com .= " , showset             = '$prd_info->showset'          ";
			$sql_com .= " , stock               = '$prd_info->stock'            ";
			$sql_com .= " , savestock           = '$prd_info->savestock'        ";
			$sql_com .= " , prior               = '$prd_info->prior'            ";
			$sql_com .= " , viewcnt             = '0'                           ";
			$sql_com .= " , deimgcnt            = '0'                           ";
			$sql_com .= " , basketcnt           = '0'                           ";
			$sql_com .= " , ordercnt            = '0'                           ";
			$sql_com .= " , cancelcnt           = '0'                           ";
			$sql_com .= " , comcnt              = '0'                           ";
			$sql_com .= " , sellprice           = '$prd_info->sellprice'        ";
			$sql_com .= " , conprice            = '$prd_info->conprice'         ";
			$sql_com .= " , reserve             = '$prd_info->reserve'          ";
			$sql_com .= " , strprice            = '$prd_info->strprice'         ";
			$sql_com .= " , new                 = '$prd_info->new'              ";
			$sql_com .= " , best                = '$prd_info->best'             ";
			$sql_com .= " , popular             = '$prd_info->popular'          ";
			$sql_com .= " , recom               = '$prd_info->recom'            ";
			$sql_com .= " , sale                = '$prd_info->sale'             ";
			$sql_com .= " , shortage            = '$prd_info->shortage'         ";
			$sql_com .= " , del_type            = '$prd_info->del_type'         ";
			$sql_com .= " , del_price           = '$prd_info->del_price'        ";
			$sql_com .= " , prdicon             = '$prd_info->prdicon_list'     ";
			$sql_com .= " , prefer              = '$prd_info->prefer'           ";
			$sql_com .= " , brand               = '$prd_info->brand'            ";
			$sql_com .= " , info_use            = '$prd_info->info_use'         ";
			$sql_com .= " {$info_name_sql}                                      ";
			$sql_com .= " {$info_value_sql}                                     ";
			$sql_com .= " , opt_use             = '$prd_info->opt_use'          ";
			$sql_com .= " , opttitle            = '$prd_info->opttitle'         ";
			$sql_com .= " , optcode             = '$prd_info->optcode'          ";
			$sql_com .= " {$opt_title_sql}                                      ";
			$sql_com .= " {$opt_code_sql}                                       ";
			$sql_com .= " {$opt_req_sql}                                        ";
			$sql_com .= " , optvalue            = '$prd_info->optvalue'         ";
			$sql_com .= " , prdimg_R            = '$prdimg_R_name'              ";
			$sql_com .= " {$prdimg_L_sql}                                       ";
			$sql_com .= " {$prdimg_M_sql}                                       ";
			$sql_com .= " {$prdimg_S_sql}                                       ";
			$sql_com .= " , searchkey           = '$prd_info->searchkey'        ";
			$sql_com .= " , stortexp            = '$prd_info->stortexp'         ";
			$sql_com .= " , content             = '$prd_info->content'          ";
			$sql_com .= " , wdate               = now()                         ";
			$sql_com .= " , mdate               = now()                         ";
			$sql_com .= " , mobileShow          = '$prd_info->mobileShow'       ";
			$sql_com .= " , mcontent            = '$prd_info->mcontent'         ";
			$sql_com .= " , eventcouponuse      = '$prd_info->eventcouponuse'   ";
			$sql_com .= " , eventcouponlink     = '$prd_info->eventcouponlink'  ";
			$sql_com .= " , eventcouponidx      = '$prd_info->eventcouponidx'   ";
			$sql_com .= " , prd_seo_use         = '$prd_info->prd_seo_use'      ";
			$sql_com .= " , prd_br_title        = '$prd_info->prd_br_title'     ";
			$sql_com .= " , prd_descript        = '$prd_info->prd_descript'     ";
			$sql_com .= " , prd_keywords        = '$prd_info->prd_keywords'     ";

			$sql = "INSERT INTO wiz_product SET {$sql_com} ";
			query($sql);


			// 카테고리 정보 저장
			if(empty($dep2_code)) $dep2_code = "00";
			if(empty($dep3_code)) $dep3_code = "00";
			if(empty($dep4_code)) $dep4_code = "00";

			$catcode = $dep_code.$dep2_code.$dep3_code.$dep4_code;

			$sql = "insert into wiz_cprelation(idx,prdcode,catcode) values('', '$prdcode', '$catcode')";
			$result = query($sql) or error("sql error");

		}
	}

	echo "<script>alert('복사 되었습니다.');opener.document.location='prd_list.php?dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code&dep4_code=$dep4_code&$menucodeParam';self.close();</script>";

}
?>