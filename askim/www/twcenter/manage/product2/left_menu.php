<?
	if($perm_check["PRODUCT2"] == false) error("권한이 없습니다.");
?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td class="left_tit">상품관리</td></tr>
				<tr> 
					<td> 
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table2">
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'prd_list') !== false) echo "class='clickover'"?>><a href="./prd_list.php?menucode=PRODUCT2" class="menu">상품조회</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'prd_input') !== false) echo "class='clickover'"?>><a href="./prd_input.php?menucode=PRODUCT2" class="menu">상품등록</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($PHP_SELF, 'prd_cat') !== false) echo "class='clickover'"?>><a href="./prd_cat.php?menucode=PRODUCT2" class="menu">상품분류</a></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
