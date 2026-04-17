<?php
include_once "../../common.php";
include_once "../../inc/twcenter_check.php";

$default_folder = "<img src='/twcenter/manage/image/ic_folder_c.gif' style='vertical-align:middle; padding:0 0 0 8px;'>";
echo '<link href="/twcenter/manage/tree/jquery.treeview.css" rel="stylesheet" type="text/css">'.PHP_EOL;
echo '<script type="text/javascript" src="/twcenter/manage/tree/jquery.treeview.js"></script>'.PHP_EOL;

?>

<script>
$(function() {

	$("a").on("click", function(){
		$("a").removeClass("clickover");
		$("span").removeClass("clickedover");
		$(this).addClass("clickover");
	});

	$("#tree").treeview({
		collapsed: true,
		control:"#Fulltreecontrol",
		animated: 100,			//-- 속도조절
		persist: "location"
	});

});

</script>
<div id="Fulltreecontrol" class="tree_expend">
	<a href="?#"><input type="button" value="모두 열기" class="btn_tree_expand"></a>
	<a href="?#"><input type="button" value="모두 닫기" class="btn_tree_expand"></a>
</div>
<div id="treeControl" style="padding: 5px 0">
	<img src="/twcenter/manage/image/tree/ServerMag_Etc_Root.gif" style="vertical-align:middle; padding:0 0 0 5px"> <a href="javascript:;" onclick="brandCode2('<?=$menucode?>')">최상위</a>
	<ul id="tree" class="TreeStructure" style="margin-left:8px">

	<?php
	$sql = "SELECT * FROM wiz_brand ORDER BY priorno ASC";
	$result = query($sql);
	while($row = sql_fetch_obj($result)){
		if($row->idx == $idx) $row->brdname = "<span class='clickover'>".$row->brdname."</span>";

	?>
		<li>
			<a href="javascript:;" onclick="brandCode('<?=$row->idx?>','<?=$menucode?>')"  id="brd_<?=$row->idx?>"><span class="folder"><?=$row->brdname?></span></a>
		</li>
	
	<?php
	}
	?>
	</ul>

