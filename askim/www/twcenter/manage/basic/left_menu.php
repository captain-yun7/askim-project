<?
	if($perm_check["BASIC"] == false) error("권한이 없습니다.");
?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td class="left_tit">기본설정</td></tr>
				<tr> 
					<td> 
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table2">
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'site_info') !== false) echo "class='clickover'"?>><a href="./site_info.php?menucode=BASIC" class="menu">사이트정보</a></td>
							</tr>
							<?if($wiz_admin['designer'] == "Y" || $wiz_admin['lev'] == 10000) { ?>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'twcenter_list') !== false || strpos($_SERVER['PHP_SELF'], 'twcenter_input') !== false) echo "class='clickover'"?>><a href="./twcenter_list.php?menucode=BASIC" class="menu">관리자목록</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'twcenter_level') !== false || strpos($_SERVER['PHP_SELF'], 'twcenter_level') !== false) echo "class='clickover'"?>><a href="./twcenter_level.php?menucode=BASIC" class="menu">관리자등급관리</a></td>
							</tr>
							<? } ?>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'popup_list') !== false || strpos($_SERVER['PHP_SELF'], 'popup_input') !== false) echo "class='clickover'"?>><a href="./popup_list.php?menucode=BASIC" class="menu">팝업관리</a></td>
							</tr>
							
							<? if($site_info['mobile_use'] == "Y" && $site_info['app_use'] == "Y"){ ?>
							<tr class="leftMenu"> 
								<td><a href="https://cp.pushwoosh.com/applications/2C0A1-33F21" target="_blank" class="menu">푸쉬발송</a></td>
							</tr>
							<? } ?>
						</table>
					</td>
				</tr>
			</table>

