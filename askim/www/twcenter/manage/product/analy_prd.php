<? include_once "../../common.php"; ?>
<? include_once "../../inc/twcenter_check.php"; ?>
<? include_once "../../inc/site_info.php"; ?>
<? include "../head.php"; ?>
<?
$param = "group=$group&searchopt=$searchopt&keyword=$keyword&dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code";
?>
	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">상품통계분석</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">상품의 상세 통계분석</td>
	    </tr>
	  </table>
	  <br>

		<form name="frm" action="<?=$PHP_SELF?>" method="get">
		<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
			<tr>
				<td class="t_name">상품분류</td>
				<td class="t_value" colspan="3">
					<select name="dep_code" onChange="this.form.submit();" class="select">
						<option value=''>:: 1차분류 ::
						<?
						$sql = "select substring(catcode,1,2) as catcode, catname from wiz_category where depthno = 1";
						$result = query($sql) or error("sql error");
						while($row = sql_fetch_obj($result)){
							if($row->catcode == $dep_code) echo "<option value='$row->catcode' selected>$row->catname";
							else echo "<option value='$row->catcode'>$row->catname";
						}
						?>
					</select>
					<select name="dep2_code" onChange="this.form.submit();" class="select">
						<option value=''>:: 2차분류 ::
						<?
						if($dep_code != ''){
							$sql = "select substring(catcode,3,2) as catcode, catname from wiz_category where depthno = 2 and catcode like '$dep_code%'";
							$result = query($sql) or error("sql error");
							while($row = sql_fetch_obj($result)){
								if($row->catcode == $dep2_code) echo "<option value='$row->catcode' selected>$row->catname";
								else echo "<option value='$row->catcode'>$row->catname";
							}
						}
						?>
					</select>
					<select name="dep3_code" onChange="this.form.submit();" class="select">
						<option value=''>:: 3차분류 ::
						<?
						if($dep_code != '' && $dep2_code != ''){
							$sql = "select substring(catcode,5,2) as catcode, catname from wiz_category where depthno = 3 and catcode like '$dep_code$dep2_code%'";
							$result = query($sql) or error("sql error");
							while($row = sql_fetch_obj($result)){
								if($row->catcode == $dep3_code) echo "<option value='$row->catcode' selected>$row->catname";
								else echo "<option value='$row->catcode'>$row->catname";
							}
						}
						?>
					</select>
					<select name="dep4_code" onChange="this.form.submit();" class="select">
						<option value=''>:: 4차분류 ::
						<?
						if($dep_code != '' && $dep2_code != '' && $dep3_code != ''){
							$sql = "select substring(catcode,7,2) as catcode, catname from wiz_category where depthno = 4 and catcode like '$dep_code$dep2_code$dep3_code%'";
							$result = query($sql) or error("sql error");
							while($row = sql_fetch_obj($result)){
								if($row->catcode == $dep4_code) echo "<option value='$row->catcode' selected>$row->catname";
								else echo "<option value='$row->catcode'>$row->catname";
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td width="15%" class="t_name">상품그룹</td>
				<td width="25%" class="t_value">
					<select name="group" class="select">
						<option value="">:: 분류선택 ::
						<option value="new" <? if($group == "new") echo "selected"; ?>>신상품
						<option value="popular" <? if($group == "popular") echo "selected"; ?>>인기상품
						<option value="recom" <? if($group == "recom") echo "selected"; ?>>추천상품
						<option value="sale" <? if($group == "sale") echo "selected"; ?>>세일상품
						<option value="stock" <? if($group == "stock") echo "selected"; ?>>품절상품
					</select>
				</td>
				<td width="15%" class="t_name">&nbsp; 조건</td>
				<td width="45%" class="t_value">
					<select name="searchopt" class="select">
						<option value="">:: 조건선택 ::
						<option value="prdname" <? if($searchopt == "prdname") echo "selected"; ?>>상품명
						<option value="prdcode" <? if($searchopt == "prdcode") echo "selected"; ?>>상품코드
					</select>
					<input type="text" name="keyword" value="<?=$keyword?>" class="input">
				</td>
			</tr>
		</table>
		<br>
		<table width="100%" cellspacing="1" cellpadding="3" border="0">
			<tr>
				<td align="center">
					<input type="submit" value="검색" class="search_btn2">&nbsp;
					<input type="button" value="전체목록" class="search_default" onclick="location.href='<?=$PHP_SELF?>'">
				</td>
			</tr>
		</table>
		</form>
		<br>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr><td height="10"></td></tr>
		</table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    	<tr><td class="t_rd" colspan="20"></td></tr>
      <tr class="t_th">
        <th width="7%">No</th>
        <th width="10%"></th>
        <th>상품명</th>
        <?
        $view_orderby = "desc";
        $deimg_orderby = "desc";
        $basket_orderby = "desc";
        $order_orderby = "desc";
        $cancel_orderby = "desc";
        $com_orderby = "desc";

        if($orderkey == "viewcnt"){
        	if($orderby == "asc" || $orderby == "") $view_orderby = "desc";
        	else $view_orderby = "asc";
        }else if($orderkey == "deimgcnt"){
        	if($orderby == "asc" || $orderby == "") $deimg_orderby = "desc";
        	else $deimg_orderby = "asc";
        }else if($orderkey == "basketcnt"){
        	if($orderby == "asc" || $orderby == "") $basket_orderby = "desc";
        	else $basket_orderby = "asc";
        }else if($orderkey == "ordercnt"){
        	if($orderby == "asc" || $orderby == "") $order_orderby = "desc";
        	else $order_orderby = "asc";
        }else if($orderkey == "cancelcnt"){
        	if($orderby == "asc" || $orderby == "") $cancel_orderby = "desc";
        	else $cancel_orderby = "asc";
        }else if($orderkey == "comcnt"){
        	if($orderby == "asc" || $orderby == "") $com_orderby = "desc";
        	else $com_orderby = "asc";
        }
        ?>
        <th width="9%"><a href="<?=$PHP_SELF?>?orderkey=viewcnt&orderby=<?=$view_orderby?>&<?=$param?>"><font color="#ffffff"><? if($view_orderby == "desc") echo "▲"; else echo "▼"; ?> 상세보기</font></a></th>
        <th width="9%"><a href="<?=$PHP_SELF?>?orderkey=deimgcnt&orderby=<?=$deimg_orderby?>&<?=$param?>"><font color="#ffffff"><? if($deimg_orderby == "desc") echo "▲"; else echo "▼"; ?> 상세이미지</font></a></th>
        <th width="9%"><a href="<?=$PHP_SELF?>?orderkey=basketcnt&orderby=<?=$basket_orderby?>&<?=$param?>"><font color="#ffffff"><? if($basket_orderby == "desc") echo "▲"; else echo "▼"; ?> 장바구니</font></a></th>
        <th width="9%"><a href="<?=$PHP_SELF?>?orderkey=ordercnt&orderby=<?=$order_orderby?>&<?=$param?>"><font color="#ffffff"><? if($order_orderby == "desc") echo "▲"; else echo "▼"; ?> 주문수</font></a></th>
        <th width="9%"><a href="<?=$PHP_SELF?>?orderkey=cancelcnt&orderby=<?=$cancel_orderby?>&<?=$param?>"><font color="#ffffff"><? if($cancel_orderby == "desc") echo "▲"; else echo "▼"; ?> 주문취소</font></a></th>
        <th width="9%"><a href="<?=$PHP_SELF?>?orderkey=comcnt&orderby=<?=$com_orderby?>&<?=$param?>"><font color="#ffffff"><? if($com_orderby == "desc") echo "▲"; else echo "▼"; ?> 배송완료</font></a></th>
      </tr>
      <tr><td class="t_rd" colspan="20"></td></tr>
      <?
			// 상품그룹
			if(!empty($group)) $group_sql = " and wp.$group = 'Y' ";

			// 조건검색
			if(!empty($searchopt)) $searchopt_sql = " and wp.$searchopt like '%$keyword%' ";

			// 상품분류
			if(!empty($dep_code)) $searchopt_sql .= " and wc.catcode like '$dep_code$dep2_code$dep3_code$dep4_code%' ";

			// 정렬순서
			if(!empty($orderkey) && !empty($orderby)) $order_sql = " order by $orderkey $orderby, wp.prior desc";
			else $order_sql = " order by wp.prior desc";

			$sql = "select prdcode from wiz_product wp where prdcode != '' $group_sql $searchopt_sql $order_sql";

			$sql = "select distinct(wp.prdcode)
							from wiz_product as wp left join wiz_cprelation as wcp on wp.prdcode = wcp.prdcode
							left join wiz_category as wc on wcp.catcode = wc.catcode
							where wp.prdcode != '' $group_sql $searchopt_sql GROUP BY wp.prdcode";
			$result = query($sql) or error("sql error");
			$total = sql_fetch_row($result);

			$rows = 12;
			$lists = 5;
			if(!$page) $page = 1;
			$page_count = ceil($total/$rows);
			$start = ($page-1)*$rows;
			$no = $total-$start;

			$sql = "
				select distinct(wp.prdcode)
					 , wp.prdname
					 , wp.prdimg_R
					 , wp.viewcnt
					 , wp.deimgcnt
					 , wp.basketcnt
					 , wp.ordercnt
					 , wp.cancelcnt
					 , wp.comcnt
					 , wc.purl
				  from wiz_product as wp 
				  left join wiz_cprelation as wcp 
				    on wp.prdcode = wcp.prdcode
				  left join wiz_category as wc 
				    on wcp.catcode = wc.catcode
				 where wp.prdcode != '' 
				  $group_sql 
				  $searchopt_sql 
				 GROUP BY wp.prdcode
				  $order_sql 
				 limit $start, $rows
			";
			$result = query($sql) or error("sql error");

			while(($row = sql_fetch_obj($result)) && $rows){
				if($row->prdimg_R == "" || !@is_file($DOCUMENT_ROOT."/twcenter/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/twcenter/images/noimage.gif";
				else $row->prdimg_R = "/twcenter/data/prdimg/$row->prdimg_R";

				if(!$row->purl) $row->purl = "shop/shop.php";

			?>
			<tr>
			  <td align="center" height="85"><?=$no?></td>
			  <td>&nbsp; <a href="/<?=$row->purl?>?ptype=view&prdcode=<?=$row->prdcode?>" target="_blank"><span class="img_border2"><img src="<?=$row->prdimg_R?>" width="65" height="70" border="0" align="absmiddle"></span></a></td>
			  <td><a href="/<?=$row->purl?>?ptype=view&prdcode=<?=$row->prdcode?>" target="_blank"><?=$row->prdname?></a></td>
			  <td align="center"><?=$row->viewcnt?></td>
			  <td align="center"><?=$row->deimgcnt?></td>
			  <td align="center"><?=$row->basketcnt?></td>
			  <td align="center"><?=$row->ordercnt?></td>
			  <td align="center"><?=$row->cancelcnt?></td>
			  <td align="center"><?=$row->comcnt?></td>
			</tr>
			<tr><td colspan="20" class="t_line"></td></tr>
			<?
			  $no--;
			  $rows--;
			}
			?>
	   </table>

     <br>
		<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="33%"></td>
				<td width="33%"><? print_pagelist($page, $lists, $page_count, "&orderkey=$orderkey&orderby=$orderby&$param"); ?></td>
				<td width="33%" align="right"></td>
			</tr>
		</table>

<? include "../foot.php"; ?>