<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";

$default_folder = "<img src='/twcenter/manage/image/ic_folder_c.gif' style='vertical-align:middle; padding:0 0 0 8px;'>";
echo '<link href="/twcenter/manage/tree/jquery.treeview.css" rel="stylesheet" type="text/css">'.PHP_EOL;
echo '<script type="text/javascript" src="/twcenter/manage/tree/jquery.treeview.js"></script>'.PHP_EOL;

?>
<script>
$(function() {

	$('a').on("click", function(){
		$('a').removeClass("clickover");
		$('span').removeClass("clickedover");
		$(this).addClass("clickover");
	});

	$("#tree").treeview({
		collapsed: true,
		control:"#Fulltreecontrol",
		animated: 100,			//-- 속도조절
		persist: "location"
	});

});

function reorder(){
	if(confirm("상품 정렬 순서가 변경되지 않는 문제 발생시에만 사용 바랍니다.\n상품분류를 재정렬하시겠습니까?")) {
		location.href="cat_save.php?mode=reorder";
	}
}
</script>
<div id="Fulltreecontrol" class="tree_expend">
	<a href="?#"><input type="button" value="모두 열기" class="btn_tree_expand"></a>
	<a href="?#"><input type="button" value="모두 닫기" class="btn_tree_expand"></a>
	<?if($wiz_admin['designer'] == 'Y') { ?><input type="button" value="분류 재정렬" class="btn_tree_expand"  onclick="reorder()"><? } ?>
</div>
<div id="treeControl" style="padding: 5px 0">
	<img src="/twcenter/manage/image/tree/ServerMag_Etc_Root.gif" style="vertical-align:middle; padding:0 0 0 5px"> <a href="javascript:SmoveCode2('00000000','<?=$menucode?>')" class="a_cat_item">최상위</a>
	<ul id="tree" class="TreeStructure" style="margin-left:8px">
	<?php
	$sql = "
		SELECT catcode
			 , catname
			 , depthno
			 , priorno01
			 , priorno02
			 , priorno03
			 , priorno04
			 , (SELECT COUNT(*)
				  FROM wiz_category2
				 WHERE catcode LIKE CONCAT(LEFT(wca.catcode, 2), '%')
				   AND depthno = wca.depthno + 1
				) AS cnt
		  FROM wiz_category2 wca
		 ORDER BY priorno01, priorno02, priorno03, priorno04
	";
	$result = query($sql) or error("sql error");
	while($row = sql_fetch_obj($result)){
		if($row->depthno == 1){
			if($row->cnt > 0) $cnt02 = "(".$row->cnt.")";
			else $cnt02 = "";

			if($row->catcode == $catcode) $row->catname = "<span class='clickover'>".$row->catname."</span>";
			$catname = $row->catname;
	?>
		<li id="li_<?php echo $row->catcode; ?>">
			<a href="javascript:;" onclick="SmoveCode('<?=$row->catcode?>','<?=$row->depthno?>','<?=$row->priorno01?>','<?=$menucode?>')" class="a_cat_item"><span class="folder"><?=$catname?> <?=$cnt02?></span></a> 
			<ul>
			<?php
			if($row->catcode == substr($catcode,0,2)."000000") echo '<script>$("#li_'.$row->catcode.'").find(".hitarea:first").trigger("click");</script>';
			$s_catcode = substr($row->catcode,0,2);
			$sql2 = "
				SELECT catcode
					 , catname
					 , depthno
					 , priorno01
					 , priorno02
					 , (SELECT COUNT(*)
						  FROM wiz_category2
						 WHERE catcode LIKE CONCAT(LEFT(wca.catcode, 4), '%')
						   AND depthno = wca.depthno + 1
						) AS cnt2
				  FROM wiz_category2 wca
				 WHERE depthno = 2
				   AND catcode like '".$s_catcode."%'
				 ORDER BY priorno01, priorno02
			";
			$result2 = query($sql2) or error("sql error");
			while($row2 = sql_fetch_obj($result2)) {

				if($row2->cnt2 > 0) $cnt03 = "(".$row2->cnt2.")";
				else $cnt03 = "";
				if($row2->catcode == $catcode) $row2->catname = "<span class='clickover'>".$row2->catname."</span>";
			?>
				<li id="li_<?php echo $row2->catcode; ?>">
					<a href="javascript:;" onclick="SmoveCode('<?php echo $row2->catcode ?>','<?php echo $row2->depthno ?>','<?php echo $row2->priorno02 ?>','<?php echo $menucode ?>')" class="a_cat_item"><span class="folder"><?php echo $row2->catname ?> <?=$cnt03?></span></a> 
					<ul>
					<?php
					if($row2->catcode == substr($catcode,0,4)."0000") echo '<script>$("#li_'.$row2->catcode.'").find(".hitarea:first").trigger("click");</script>';
					$s2_catcode = substr($row2->catcode,0,4);
					$sql3 = "
						SELECT catcode
							 , catname
							 , depthno
							 , priorno01
							 , priorno02
							 , priorno03
							 , (SELECT COUNT(*)
								  FROM wiz_category2
								 WHERE catcode LIKE CONCAT(LEFT(wca.catcode, 6), '%')
								   AND depthno = wca.depthno + 1
								) AS cnt3
						  FROM wiz_category2 wca
						 WHERE depthno = 3
						   AND catcode like '".$s2_catcode."%'
						 ORDER BY priorno01, priorno02, priorno03
					";
					$result3 = query($sql3) or error("sql error");
					while($row3 = sql_fetch_obj($result3)) {

						if($row3->cnt3 > 0) $cnt04 = "(".$row3->cnt3.")";
						else $cnt04 = "";
						if($row3->catcode == $catcode) $row3->catname = "<span class='clickover'>".$row3->catname."</span>";

					?>
						<li id="li_<?php echo $row3->catcode; ?>">
							<a href="javascript:;" onclick="SmoveCode('<?php echo $row3->catcode ?>','<?php echo $row3->depthno ?>','<?php echo $row3->priorno03 ?>','<?php echo $menucode ?>')" class="a_cat_item"><span class="folder"><?php echo $row3->catname ?> <?php echo $cnt04 ?></span></a> 
							<ul>
							<?php
							if($row3->catcode == substr($catcode,0,6)."00") echo '<script>$("#li_'.$row3->catcode.'").find(".hitarea:first").trigger("click");</script>';
							$s3_catcode = substr($row3->catcode,0,6);
							$sql4 = "
								SELECT catcode
									 , catname
									 , depthno
									 , priorno01
									 , priorno02
									 , priorno03
									 , priorno04
									 , (SELECT COUNT(*)
										  FROM wiz_category2
										 WHERE catcode LIKE CONCAT(LEFT(wca.catcode, 8), '%')
										   AND depthno = wca.depthno + 1
										) AS cnt
								  FROM wiz_category2 wca
								 WHERE depthno = 4
								   AND catcode like '".$s3_catcode."%'
								 ORDER BY priorno01, priorno02, priorno03, priorno04
							";
							$result4 = query($sql4) or error("sql error");
							while($row4 = sql_fetch_obj($result4)) {
								if($row4->catcode == $catcode) $row4->catname = "<span class='clickover'>".$row4->catname."</span>";
							?>
								<li>
									<a href="javascript:;" onclick="SmoveCode('<?php echo $row4->catcode ?>','<?php echo $row4->depthno ?>','<?php echo $row4->priorno04 ?>','<?php echo $menucode ?>')" class="a_cat_item"><span class="folder"><?php echo $row4->catname ?></span></a>
								</li>
							<?php
							}
							?>
							</ul>
						</li>
					<?php } ?>
					</ul>

				</li>
			<?php
			}
			?>
			</ul>
		</li>
	<?php
		}
	}
	?>
	</ul>
</div>