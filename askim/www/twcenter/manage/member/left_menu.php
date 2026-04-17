<?
	if($perm_check["MEMBER"] == false) error("권한이 없습니다.");
?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td class="left_tit">회원관리</td></tr>
				<tr> 
					<td> 
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table2">
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'member_list') !== false | strpos($_SERVER['PHP_SELF'], 'member_input') !== false) echo "class='clickover'"?>><a href="./member_list.php?menucode=MEMBER" class="menu">회원목록</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'level_list') !== false || strpos($_SERVER['PHP_SELF'], 'level_input') !== false) echo "class='clickover'"?>><a href="./level_list.php?menucode=MEMBER" class="menu">회원등급</a></td>
							</tr>
							<!--
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'member_dormant_account_list') !== false || strpos($_SERVER['PHP_SELF'], 'member_dormant_account_input') !== false) echo "class='clickover'"?>><a href="./member_dormant_account_list.php?menucode=MEMBER" class="menu">휴면계정 목록</a></td>
							</tr>
							-->
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'out_list') !== false) echo "class='clickover'"?>><a href="./out_list.php?menucode=MEMBER" class="menu">탈퇴회원</a></td>
							</tr>			
							<?php if($wiz_admin['designer'] == "Y"){ ?>
							<!-- <tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'mail_test') !== false) echo "class='clickover'"?>><a href="./mail_test.php?menucode=MEMBER" class="menu">메일발송테스트</a></td>
							</tr> -->
							<?php } ?>
							<!--
							<tr> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'mail_send') !== false) echo "class='clickover'"?>><a href="./mail_send.php?menucode=MEMBER" class="menu">단체메일발송</a></td>
							</tr>
							-->
							<? if($site_info['msg_use'] == "Y"){ ?>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'message_send') !== false) echo "class='clickover'"?>><a href="./message_send.php?menucode=MEMBER" class="menu">쪽지발송</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if((strpos($_SERVER['PHP_SELF'], 'message_list') !== false || strpos($_SERVER['PHP_SELF'], 'message_input')) && !strpos($_SERVER['PHP_SELF'],"talk_message_list") !== false) echo "class='clickover'"?>><a href="./message_list.php?menucode=MEMBER" class="menu">쪽지목록</a></td>
							</tr>
							<? } ?>
							
							<? if($site_info['point_use'] == "Y"){ ?>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'point_config') !== false) echo "class='clickover'"?>><a href="./point_config.php?menucode=MEMBER" class="menu">포인트설정</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'point_list') !== false) echo "class='clickover'"?>><a href="./point_list.php?menucode=MEMBER" class="menu">포인트목록</a></td>
							</tr>
							<? } ?>

							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'member_analy') !== false) echo "class='clickover'"?>><a href="./member_analy.php?menucode=MEMBER" class="menu">회원통계</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'member_config') !== false) echo "class='clickover'"?>><a href="./member_config.php?menucode=MEMBER" class="menu">약관·개인정보 수집·이용 동의</a></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>