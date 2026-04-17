<?php
/**********************************************************************************************************
# 카테고리
$subimg 				: 서브상단
$catname				: 카테고리 명
***********************************************************************************************************/
if($cat_info['depthno'] == 1)	   $tmp_catcode = substr($catcode,0,2);
else if($cat_info['depthno'] == 2) $tmp_catcode = substr($catcode,0,4);
else if($cat_info['depthno'] == 3) $tmp_catcode = substr($catcode,0,6);
else if($cat_info['depthno'] == 4) $tmp_catcode = substr($catcode,0,8);

$depthno = $cat_info['depthno'];
if($depthno < 4) $depthno++;
/*
2단계까지만 보여줄경우
$tmp_catcode = substr($catcode,0,2);
if($cat_info['depthno'] < 2) $cat_info['depthno']++;
*/

// 카테고리별 찾기
if(!empty($catcode)){
	$catcode01 = substr($catcode,0,2);
	$catcode02 = substr($catcode,2,2);
	$catcode03 = substr($catcode,4,2);
	$catcode04 = substr($catcode,6,2);

	if($catcode01 == "00") $catcode01 = "";
	if($catcode02 == "00") $catcode02 = "";
	if($catcode03 == "00") $catcode03 = "";
	if($catcode04 == "00") $catcode04 = "";

	$s_catcode = $catcode01.$catcode02;
	$t_catcode = $catcode01.$catcode02.$catcode03;

}

## 1차카테고리명 불러오기
if(!empty($catcode01)){
	$sql = "select catcode
				 , catname 
			  from wiz_category 
			 where catuse != 'N' 
			   and catcode like '$catcode01%' 
			   and depthno = 1 
			 order by priorno01 asc
	";
	$result = query($sql);
	$row = sql_fetch_arr($result);
	$catname_1 = "<a href='$purl?ptype=list&catcode=".$row['catcode']."'>".$row['catname']."</a>";
}

## 2차카테고리명 불러오기
if(!empty($catcode02)){
	$sql2 = "select catcode
				  , catname 
			   from wiz_category 
			  where catuse != 'N' 
			    and catcode like '$s_catcode%' 
				and depthno = 2 
			  order by priorno01 asc,priorno02 asc
	";
	$result2 = query($sql2);
	$row2 = sql_fetch_arr($result2);
	$catname_2 = "<a href='$purl?ptype=list&catcode=".$row2['catcode']."'>".$row2['catname']."</a>";
} else {
	$sql2 = "select catcode
				  , catname 
			   from wiz_category 
			  where catuse != 'N' 
			    and catcode like '$s_catcode%' 
				and depthno = 2 
			  order by priorno01 asc,priorno02 asc
	";
	$result2 = query($sql2);
	$row2 = sql_fetch_arr($result2);
	$catname_2 = "<a href='$purl?ptype=list&catcode=".$row2['catcode']."'>".$row2['catname']."</a>";
}

## 3차카테고리명 불러오기
if(!empty($catcode03)){
	$sql3 = "select catcode
				  , catname 
			   from wiz_category 
			  where catuse != 'N' 
			    and catcode like '$t_catcode%' 
				and depthno = 3 
			  order by priorno01 asc,priorno02 asc,priorno03 asc
	";
	$result3 = query($sql3);
	$row3 = sql_fetch_arr($result3);
	$catname_3 = "<a href='$purl?ptype=list&catcode=".$row3['catcode']."'>".$row3['catname']."</a>";
} else {
	$sql3 = "select catcode
				  , catname 
			   from wiz_category 
			  where catuse != 'N' 
			    and catcode like '$t_catcode%' 
				and depthno = 3 
			  order by priorno01 asc,priorno02 asc,priorno03 asc
	";
	$result3 = query($sql3);
	$row3 = sql_fetch_arr($result3);
	$catname_3 = "<a href='$purl?ptype=list&catcode=".$row3['catcode']."'>".$row3['catname']."</a>";
}

$category_sort = $category_all;
if(!empty($catcode01)) $category_sort .= " &gt; ".$catname_1;
if(!empty($catcode02)) $category_sort .= " &gt; ".$catname_2;
if(!empty($catcode03)) $category_sort .= " &gt; ".$catname_3;

$no_next = false;
$sql = "
	SELECT catcode
		 , catname
		 , depthno
	  FROM wiz_category
	 WHERE catuse != 'N' 
	   AND catcode like '$tmp_catcode%' 
	   AND depthno = $depthno
	 ORDER BY priorno02, priorno03, priorno04 ASC
";
$result = query($sql);
$total = sql_fetch_row($result);

if($depthno == 4 && $catcode04 != ""){
	$tmp_catcode1 = substr($tmp_catcode, 0, strlen($tmp_catcode)-2);
	$sql = "
		SELECT catcode
			 , catname
			 , depthno
		  FROM wiz_category
		 WHERE catuse != 'N' 
		   AND catcode like '$tmp_catcode1%' 
		   AND depthno = '".$depthno."'
		 ORDER BY priorno02, priorno03, priorno04 ASC
	";
	$result = query($sql);
} else {
	if($total <= 0) {
		$no_next = true;
		$tmp_catcode1 = substr($tmp_catcode, 0, strlen($tmp_catcode)-2);
		$sql = "
			SELECT catcode
				 , catname
				 , depthno
			  FROM wiz_category
			 WHERE catuse != 'N' 
			   AND catcode like '$tmp_catcode1%' 
			   AND depthno = '".($depthno-1)."'
			 ORDER BY priorno02, priorno03, priorno04 ASC
		";
		$result = query($sql);
	}
}

if($grp == "best" || $grp == "new" || $grp == "sale" || $grp == "recom") {
} else {
?>
<div class="category_area">
	<h3><?php echo $category_sort?></h3>
	<div class="cate_table">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<colgroup>
				<col width="20%" />
				<col width="20%" />
				<col width="20%" />
				<col width="20%" />
				<col width="20%" />
			</colgroup>
			<?php
			$no = 0;
			while($row = sql_fetch_obj($result)){

				// 현재카테고리에 몇개의 상품이 있는지
				$t_catcode = $row->catcode;
				if($row->depthno == 1)		$t_catcode = substr($row->catcode,0,2);
				else if($row->depthno == 2)	$t_catcode = substr($row->catcode,0,4);
				else if($row->depthno == 3)	$t_catcode = substr($row->catcode,0,6);
				else if($row->depthno == 4)	$t_catcode = substr($row->catcode,0,8);

				$t_sql = "select count(wc.idx) as prd_count 
							from wiz_cprelation wc
							   , wiz_product wp 
						   where wc.prdcode = wp.prdcode 
							 and wc.catcode like '$t_catcode%' 
							 and wp.showset!='N'
				";
				$t_result = query($t_sql);
				$t_row = sql_fetch_obj($t_result);
				$prd_count = $t_row->prd_count;

				if(!strcmp($row->catcode, $catcode)) $total = $prd_count;
				if($catcode == $row->catcode) $row->catname = "<strong>".$row->catname." (".$prd_count.")</strong>";
				else $row->catname = $row->catname." (".$prd_count.")";

				$cls = $row->catcode == $catcode ? " class='hover'" : "";
				$catlist[$no] = "<a href='$purl?ptype=list&catcode=".$row->catcode."'>".$row->catname."</a>";
				$no++;
			}

			array_unshift($catlist, $catlist_all);
			for($ii=0;$ii<count($catlist);$ii++){
				if($ii%5 == 0) echo "<tr>";
				echo "<td>".$catlist[$ii]."</td>";
			}
			?>
		</table>
	</div>
</div>
<?php
}
?>