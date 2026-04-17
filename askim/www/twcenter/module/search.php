<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";

// 스킨위치
$skin_dir = "/twcenter/search/skin/".$site_info['search_skin'];
$search_url = "/".$site_info['search_url'];
?>
<link href="<?php echo $skin_dir ?>/style.css" rel="stylesheet" type="text/css" />
<form name="search" action="<?php echo $search_url ?>" method="get" onsubmit="return search(this);">
<dl>
<dd><label for="input_search" class="blind">검색어 입력</label><input id="input_search" name="total_searchkey" value="" type="text" class="input_search" title="검색어 입력" /></dd>
<dt><button type="submit">검색<?php include $_SERVER['DOCUMENT_ROOT']."/img/search.svg";?></button></dt>
</dl>
</form>
