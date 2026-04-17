<?
	if($perm_check["MESSAGE"] == false) error("권한이 없습니다.");
?>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td class="left_tit">메세지관리</td></tr>
				<tr> 
					<td> 
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="left_table2">
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'mail_list') !== false || strpos($_SERVER['PHP_SELF'], 'mail_input') !== false) echo "class='clickover'"?>><a href="./mail_list.php?menucode=MESSAGE" class="menu">메세지설정</a></td>
							</tr>
							
							<? if($site_info['sms_use'] == "Y"){ ?>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'sms_fill') !== false) echo "class='clickover'"?>><a href="./sms_fill.php?menucode=MESSAGE" class="menu">SMS관리</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'sms_send') !== false) echo "class='clickover'"?>><a href="./sms_send.php?menucode=MESSAGE" class="menu">단체SMS발송</a></td>
							</tr>
							<? } ?>

							<?php if($wiz_admin['designer'] == "Y"){ ?>
							<!-- <tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'mail_test') !== false) echo "class='clickover'"?>><a href="./mail_test.php?menucode=MESSAGE" class="menu">메일발송테스트</a></td>
							</tr> -->
							<?php } ?>
							<!--
							<tr> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'mail_send') !== false) echo "class='clickover'"?>><a href="./mail_send.php?menucode=MESSAGE" class="menu">단체메일발송</a></td>
							</tr>
							-->
							<?php
							if($site_info['alimtalk_use'] == 'Y' && $site_info['alimtalk_id']) {
							?>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'talk_charging') !== false) echo "class='clickover'"?>><a href="./talk_charging.php?menucode=MESSAGE" class="menu">알림톡 충전</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'talk_list') !== false || strpos($_SERVER['PHP_SELF'], 'talk_template_insert') !== false) echo "class='clickover'"?>><a href="./talk_list.php?menucode=MESSAGE" class="menu">알림톡 템플릿 설정</a></td>
							</tr>
							<tr class="leftMenu"> 
								<td <? if(strpos($_SERVER['PHP_SELF'], 'talk_message_list') !== false || strpos($_SERVER['PHP_SELF'], 'talk_message_list') !== false) echo "class='clickover'"?>><a href="./talk_message_list.php?menucode=MESSAGE" class="menu">알림톡 발송 내역</a></td>
							</tr>
							<?php } ?>
						</table>
					</td>
				</tr>
			</table>