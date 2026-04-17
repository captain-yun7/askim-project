<?php
include_once $_SERVER['DOCUMENT_ROOT']."/twcenter/common.php";
if(empty($prd_info['search_url'])) $prd_info['search_url'] = "shop/search.php";
$spurl = "/".$prd_info['search_url'];

?>
<script Language="Javascript">
<!--
var search_url = "<?=$spurl?>";
function prdSearchCheck() {
	if(search_url == "") {
		alert("상품검색 페이지가 설정되지 않았습니다.");
		return false;
	}
}

function search_prd(){
	var prdname = $("#prdname").val();

	var pno = 0;

	if(prdname==null || prdname==""){


		$("#prd_search").html("");
		$("#prd_search").hide();
	}else{

		$.ajax({
			type:"get"
			, dataType: "xml"
			, url:  "/twcenter/module/search_xml.php"
			, data: "prdname="+prdname
			, success: function(xml) {
					//alert(xml);

					$("#prd_search").show();

					divulli ="";

					divulli +="<div style='height:25px;list-style:none;padding-top:5px;text-align:right; background:#eeeeee;'><a href='#' onClick=\"prd_search.style.display='none'\" style='color:#666666;'>닫기</a></div>";

					$(xml).find("nodeRoot").find("nodeFirstChild").each(function(idx) {
						divulli +="<li style='height:25px;list-style:none;margin-top:5'><a href=javascript:pnameInput('"+pno+"')>"+$(this).find("pro_name").text()+"</a><input type='hidden' name='Hprdname"+pno+"' id ='Hprdname"+pno+"' value='"+$(this).find("prd_name").text()+"'><input type='hidden' name='Hprdcode"+pno+"' id ='Hprdcode"+pno+"' value='"+$(this).find("idx").text()+"'></li>";
						pno++;
					});

					if(divulli==""){
						$("#prd_search").html("");
						$("#prd_search").hide();
					}else{
						$("#prd_search").html(divulli);
					}


			}
			, error: function(){
			}
		});

	}
}

function pnameInput(pno){
	var stext = $("#Hprdname"+pno).val();
	var prdcode = $("#Hprdcode"+pno).val();
	$("#prdname").val(stext);
	$("#prdcode").val(prdcode);
	$("#prd_search").html("");
	$("#prd_search").hide();
	$("#sch_frm").submit();
}

//$("body").click(function(e){
//	prd_search.style.display='none';
//});
-->
</script>


<form id="sch_frm" action="<?=$spurl?>" onSubmit="return prdSearchCheck()">
<input type="hidden" name="searchopt" value="prdname">
	<dd><input name="prdname" id="prdname" type="text" class="input_search" ></dd>
	<dt><input type="image" src="/img/search_btn.png" border="0" alt="검색"></dt>
</form>


<!-- <dl>
<form id="sch_frm" action="<?=$spurl?>" onSubmit="return prdSearchCheck()">
<input type="hidden" name="searchopt" value="prdname">
	<dd><input name="prdname" id="prdname" type="text" class="input_search" onkeyup="search_prd();" Autocomplete="off" onclick="search_prd();" ></dd>
	<dt><input type="image" src="/img/search_btn.gif" border="0"></dt>

	<div id="prd_search" name="prd_search" style="position:absolute; top:38px; left:-2px; border:2px solid #269f79; width:323px; height:150px; overflow-y:scroll;display:none;background-color:#ffffff; z-index:999"> </div>
</form>
</dl> -->

<!-- <form action="<?=$spurl?>" onSubmit="return prdSearchCheck()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<input type="hidden" name="searchopt" value="prdname">
	<tr>
		<td><img src="/twcenter/images/img_search.gif" width="67" height="18"></td>
		<td><input name="searchkey" type="text" class="input" size="10"></td>
		<td style="padding-left:3px"><input type="image" src="/twcenter/images/btn_search.gif" width="29" height="17" border="0"></td>
	</tr>
</table>
</form> -->